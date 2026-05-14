<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kuesioner;
use App\Models\Jawaban;
use App\Models\Pertanyaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HasilSurveiController extends Controller
{
    public function index(Request $request)
    {
        $kuesioners = Kuesioner::where('status', '!=', 'draft')->latest()->get();
        $selectedKuesionerId = $request->kuesioner_id ?? ($kuesioners->first()->id ?? null);
        
        $kuesioner = null;
        $statistik = null;
        $indikatorStats = [];

        if ($selectedKuesionerId) {
            $kuesioner = Kuesioner::with('pertanyaan')->find($selectedKuesionerId);
            
            // Total Responden
            $totalResponden = Jawaban::where('kuesioner_id', $selectedKuesionerId)->distinct('user_id')->count('user_id');
            
            // Average Score (hanya untuk skala_likert dan pilihan_ganda jika ada nilai_jawaban)
            $rataRataSkor = Jawaban::where('kuesioner_id', $selectedKuesionerId)
                ->whereNotNull('nilai_jawaban')
                ->avg('nilai_jawaban');

            // Hitung berdasarkan indikator
            $indikatorStatsRaw = DB::table('jawaban')
                ->join('pertanyaan', 'jawaban.pertanyaan_id', '=', 'pertanyaan.id')
                ->where('jawaban.kuesioner_id', $selectedKuesionerId)
                ->whereNotNull('jawaban.nilai_jawaban')
                ->select(
                    'pertanyaan.indikator', 
                    DB::raw('AVG(jawaban.nilai_jawaban) as rata_rata'),
                    DB::raw('COUNT(jawaban.id) as total_jawaban')
                )
                ->groupBy('pertanyaan.indikator')
                ->get();

            // Persiapkan data untuk AI
            $dataForAi = [];
            foreach ($indikatorStatsRaw as $item) {
                $ind = $item->indikator ?: 'Umum';
                $dataForAi[] = [
                    'indikator' => $ind,
                    'rata_rata' => round($item->rata_rata, 2)
                ];
            }

            // Dapatkan rekomendasi AI via Cache (simpan 24 jam)
            $cacheKey = 'ai_recommendations_kuesioner_' . $selectedKuesionerId;
            $aiRecommendations = \Illuminate\Support\Facades\Cache::remember($cacheKey, 86400, function () use ($dataForAi) {
                $gemini = new \App\Services\GeminiService();
                return $gemini->generateRecommendations($dataForAi);
            });

            $indikatorStats = $indikatorStatsRaw->map(function($item) use ($aiRecommendations) {
                $ind = $item->indikator ?: 'Umum';
                $rataRata = $item->rata_rata;
                
                $kategori = '';
                $rekomendasi = '';
                
                if ($rataRata >= 4.21) {
                    $kategori = 'Sangat Baik';
                    $rekomendasi = 'Pertahankan kualitas dan jadikan sebagai standar percontohan.';
                } elseif ($rataRata >= 3.41) {
                    $kategori = 'Baik';
                    $rekomendasi = 'Kembangkan lebih lanjut dan pertahankan konsistensi.';
                } elseif ($rataRata >= 2.61) {
                    $kategori = 'Cukup';
                    $rekomendasi = 'Lakukan evaluasi untuk mengetahui dan memperbaiki aspek yang masih kurang.';
                } elseif ($rataRata >= 1.81) {
                    $kategori = 'Kurang';
                    $rekomendasi = 'Perlu perbaikan segera. Lakukan peninjauan ulang terhadap prosedur dan layanan.';
                } elseif ($rataRata > 0) {
                    $kategori = 'Sangat Kurang';
                    $rekomendasi = 'Tindakan mendesak diperlukan. Identifikasi masalah fundamental dan perbaiki total.';
                } else {
                    $kategori = 'Belum Ada Data';
                    $rekomendasi = '-';
                }

                // Gunakan rekomendasi cerdas dari AI jika tersedia, jika tidak gunakan fallback rekomendasi khusus statis
                if (isset($aiRecommendations[$ind]) && !empty($aiRecommendations[$ind])) {
                    $rekomendasi .= ' Analisis AI: ' . $aiRecommendations[$ind];
                } else {
                    // Fallback statis
                    $indLower = strtolower($ind);
                    if ($rataRata > 0 && $rataRata <= 3.40) {
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

                return [
                    'indikator' => $ind,
                    'rata_rata' => round($rataRata, 2),
                    'total' => $item->total_jawaban,
                    'kategori' => $kategori,
                    'rekomendasi' => $rekomendasi
                ];
            });

            $statistik = [
                'total_responden' => $totalResponden,
                'rata_rata_skor' => round($rataRataSkor ?? 0, 2),
            ];
        }

        return view('admin.hasil_survei.index', compact('kuesioners', 'selectedKuesionerId', 'kuesioner', 'statistik', 'indikatorStats'));
    }

    public function exportExcel($kuesioner_id)
    {
        $kuesioner = Kuesioner::findOrFail($kuesioner_id);
        $fileName = 'Hasil_Survei_' . str_replace(' ', '_', $kuesioner->nama_kuesioner) . '_' . date('Ymd_His') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\JawabanExport($kuesioner_id), $fileName);
    }
}
