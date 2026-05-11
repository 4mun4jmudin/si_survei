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

        return view('admin.laporan.print', compact('laporan', 'kuesioner', 'statistik', 'clusteringResult'));
    }
}
