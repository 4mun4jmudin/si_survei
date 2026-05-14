<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\Kuesioner;
use App\Models\Jawaban;
use Illuminate\Http\Request;
use App\Services\KMeansService;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = Laporan::with('kuesioner')->latest()->paginate(10);
        return view('admin.laporan.index', compact('laporans'));
    }

    public function create()
    {
        $kuesioners = Kuesioner::latest()->get();
        return view('admin.laporan.create', compact('kuesioners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kuesioner_id' => 'required|exists:kuesioner,id',
            'judul_laporan' => 'required|string|max:255',
            'ringkasan' => 'nullable|string',
        ]);

        Laporan::create($request->all());

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan berhasil dibuat dan diarsipkan!');
    }

    public function destroy(Laporan $laporan)
    {
        $laporan->delete();
        return redirect()->route('admin.laporan.index')->with('success', 'Arsip laporan berhasil dihapus!');
    }

    public function cetak(Laporan $laporan)
    {
        $kuesioner = $laporan->kuesioner;
        $kuesioner->load('pertanyaan');

        // Statistik Dasar
        $totalResponden = Jawaban::where('kuesioner_id', $kuesioner->id)->distinct('user_id')->count('user_id');
        $rataRataSkor = Jawaban::where('kuesioner_id', $kuesioner->id)->whereNotNull('nilai_jawaban')->avg('nilai_jawaban');

        $statistik = [
            'total_responden' => $totalResponden,
            'rata_rata_skor' => round($rataRataSkor ?? 0, 2),
        ];

        // Jalankan K-Means untuk dapatkan Summary Clustering
        $indikators = DB::table('pertanyaan')
            ->where('kuesioner_id', $kuesioner->id)
            ->whereIn('tipe_jawaban', ['pilihan_ganda', 'skala_likert'])
            ->select('indikator')
            ->distinct()
            ->pluck('indikator')
            ->filter()
            ->values()
            ->toArray();
            
        if (empty($indikators)) $indikators = ['Umum'];

        $jawabans = DB::table('jawaban')
            ->join('pertanyaan', 'jawaban.pertanyaan_id', '=', 'pertanyaan.id')
            ->where('jawaban.kuesioner_id', $kuesioner->id)
            ->whereNotNull('jawaban.nilai_jawaban')
            ->select('jawaban.user_id', 'pertanyaan.indikator', 'jawaban.nilai_jawaban')
            ->get();

        $userScores = [];
        foreach ($jawabans as $j) {
            $ind = $j->indikator ?: 'Umum';
            if (!isset($userScores[$j->user_id])) $userScores[$j->user_id] = [];
            if (!isset($userScores[$j->user_id][$ind])) $userScores[$j->user_id][$ind] = ['sum' => 0, 'count' => 0];
            $userScores[$j->user_id][$ind]['sum'] += $j->nilai_jawaban;
            $userScores[$j->user_id][$ind]['count']++;
        }

        $dataset = [];
        foreach ($userScores as $userId => $scores) {
            $features = [];
            foreach ($indikators as $ind) {
                if (isset($scores[$ind]) && $scores[$ind]['count'] > 0) {
                    $features[] = $scores[$ind]['sum'] / $scores[$ind]['count'];
                } else {
                    $features[] = 0;
                }
            }
            $dataset[] = [
                'user_id' => $userId,
                'features' => $features
            ];
        }

        $clusteringResult = null;
        if (count($dataset) >= 4) {
            $kMeans = new KMeansService(4, 100);
            $clusteringResult = $kMeans->cluster($dataset);
            if (isset($clusteringResult['error'])) $clusteringResult = null;
        }

        // Persiapkan data untuk AI
        $dataForAi = [];
        foreach ($indikators as $ind) {
            $sum = 0;
            $count = 0;
            foreach ($jawabans as $j) {
                $jInd = $j->indikator ?: 'Umum';
                if ($jInd === $ind) {
                    $sum += $j->nilai_jawaban;
                    $count++;
                }
            }
            $rataRata = $count > 0 ? $sum / $count : 0;
            $dataForAi[] = [
                'indikator' => $ind,
                'rata_rata' => round($rataRata, 2)
            ];
        }

        // Dapatkan rekomendasi AI via Cache (simpan 24 jam)
        $cacheKey = 'ai_recommendations_kuesioner_' . $kuesioner->id;
        $aiRecommendations = \Illuminate\Support\Facades\Cache::remember($cacheKey, 86400, function () use ($dataForAi) {
            $gemini = new \App\Services\GeminiService();
            return $gemini->generateRecommendations($dataForAi);
        });

        // Evaluasi & Rekomendasi Otomatis Berdasarkan Indikator
        $evaluasiOtomatis = [];
        foreach ($indikators as $ind) {
            $sum = 0;
            $count = 0;
            foreach ($jawabans as $j) {
                $jInd = $j->indikator ?: 'Umum';
                if ($jInd === $ind) {
                    $sum += $j->nilai_jawaban;
                    $count++;
                }
            }
            $rataRata = $count > 0 ? $sum / $count : 0;
            
            $predikat = '';
            $rekomendasi = '';
            
            if ($rataRata >= 4.21) {
                $predikat = 'Sangat Baik';
                $rekomendasi = 'Pertahankan kualitas dan jadikan sebagai standar percontohan.';
            } elseif ($rataRata >= 3.41) {
                $predikat = 'Baik';
                $rekomendasi = 'Kembangkan lebih lanjut dan pertahankan konsistensi.';
            } elseif ($rataRata >= 2.61) {
                $predikat = 'Cukup';
                $rekomendasi = 'Lakukan evaluasi untuk mengetahui dan memperbaiki aspek yang masih kurang.';
            } elseif ($rataRata >= 1.81) {
                $predikat = 'Kurang';
                $rekomendasi = 'Perlu perbaikan segera. Lakukan peninjauan ulang terhadap prosedur dan layanan.';
            } elseif ($rataRata > 0) {
                $predikat = 'Sangat Kurang';
                $rekomendasi = 'Tindakan mendesak diperlukan. Identifikasi masalah fundamental dan perbaiki total.';
            } else {
                $predikat = 'Belum Ada Data';
                $rekomendasi = '-';
            }

            // Gunakan rekomendasi cerdas dari AI jika tersedia, jika tidak gunakan fallback rekomendasi khusus statis
            if (isset($aiRecommendations[$ind]) && !empty($aiRecommendations[$ind])) {
                $rekomendasi .= ' Analisis AI: ' . $aiRecommendations[$ind];
            } else {
                // Menentukan rekomendasi khusus berdasarkan kata kunci pada indikator
                $indLower = strtolower($ind);
                if ($rataRata > 0 && $rataRata <= 3.40) { // Rekomendasi tambahan jika dirasa Cukup, Kurang, atau Sangat Kurang
                    if (str_contains($indLower, 'keamanan')) {
                        $rekomendasi .= ' Tindak Lanjut Spesifik: Tingkatkan upaya pencegahan, perketat pengawasan, dan pastikan SOP keamanan berjalan ketat.';
                    } elseif (str_contains($indLower, 'fasilitas') || str_contains($indLower, 'sarana')) {
                        $rekomendasi .= ' Tindak Lanjut Spesifik: Segera periksa kelayakan fasilitas terkait, lakukan perbaikan, atau sediakan pembaruan sarana yang memadai.';
                    } elseif (str_contains($indLower, 'layanan') || str_contains($indLower, 'pelayanan')) {
                        $rekomendasi .= ' Tindak Lanjut Spesifik: Berikan evaluasi pada staf pelayanan, tingkatkan responsivitas, dan adakan pelatihan service excellence jika perlu.';
                    } elseif (str_contains($indLower, 'pembelajaran') || str_contains($indLower, 'guru') || str_contains($indLower, 'akademik') || str_contains($indLower, 'materi') || str_contains($indLower, 'mengajar')) {
                        $rekomendasi .= ' Tindak Lanjut Spesifik: Evaluasi metode pengajaran, diskusikan kendala dengan pendidik, dan kembangkan variasi pembelajaran yang lebih interaktif.';
                    } elseif (str_contains($indLower, 'kebersihan') || str_contains($indLower, 'lingkungan')) {
                        $rekomendasi .= ' Tindak Lanjut Spesifik: Jadwalkan pembersihan lebih intensif dan tingkatkan kesadaran warga sekolah untuk menjaga lingkungan sekitar.';
                    } elseif (str_contains($indLower, 'komunikasi') || str_contains($indLower, 'informasi')) {
                        $rekomendasi .= ' Tindak Lanjut Spesifik: Perbaiki saluran komunikasi agar informasi lebih transparan, cepat, dan mudah diakses oleh seluruh pihak terkait.';
                    } else {
                        $rekomendasi .= ' Tindak Lanjut Spesifik: Lakukan identifikasi lebih mendalam pada area ' . $ind . ' untuk merumuskan solusi yang tepat sasaran.';
                    }
                }
            }

            $evaluasiOtomatis[] = [
                'indikator' => $ind,
                'rata_rata' => round($rataRata, 2),
                'predikat' => $predikat,
                'rekomendasi' => $rekomendasi
            ];
        }

        // Tambahan data Esai untuk laporan
        $jawabanEsai = DB::table('jawaban')
            ->join('pertanyaan', 'jawaban.pertanyaan_id', '=', 'pertanyaan.id')
            ->where('jawaban.kuesioner_id', $kuesioner->id)
            ->where('pertanyaan.tipe_jawaban', 'esai')
            ->whereNotNull('jawaban.jawaban_teks')
            ->select('pertanyaan.isi_pertanyaan', 'jawaban.jawaban_teks')
            ->get()
            ->groupBy('isi_pertanyaan');

        // Distribusi PG untuk laporan
        $pertanyaanPG = $kuesioner->pertanyaan()->where('tipe_jawaban', 'pilihan_ganda')->get();
        $distribusiPG = [];
        foreach ($pertanyaanPG as $p) {
            $counts = DB::table('jawaban')
                ->where('pertanyaan_id', $p->id)
                ->select('nilai_jawaban', DB::raw('count(*) as total'))
                ->groupBy('nilai_jawaban')
                ->get();
            $distribusiPG[$p->isi_pertanyaan] = [
                'opsi' => $p->opsi_jawaban,
                'data' => $counts
            ];
        }

        return view('admin.laporan.print', compact('laporan', 'kuesioner', 'statistik', 'clusteringResult', 'evaluasiOtomatis', 'jawabanEsai', 'distribusiPG'));
    }
}
