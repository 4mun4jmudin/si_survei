<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kuesioner;
use App\Models\Jawaban;
use App\Models\User;
use App\Services\KMeansService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClusteringController extends Controller
{
    public function index(Request $request)
    {
        $kuesioners = Kuesioner::where('status', '!=', 'draft')->latest()->get();
        $selectedKuesionerId = $request->kuesioner_id ?? ($kuesioners->first()->id ?? null);
        
        $kuesioner = null;
        $clusteringResult = null;
        $error = null;
        $indikators = [];

        if ($selectedKuesionerId) {
            $kuesioner = Kuesioner::find($selectedKuesionerId);
            
            // 1. Ambil daftar indikator unik dari pertanyaan kuesioner ini
            $indikators = DB::table('pertanyaan')
                ->where('kuesioner_id', $selectedKuesionerId)
                ->whereIn('tipe_jawaban', ['pilihan_ganda', 'skala_likert'])
                ->select('indikator')
                ->distinct()
                ->pluck('indikator')
                ->filter()
                ->values()
                ->toArray();
                
            if (empty($indikators)) {
                // Fallback jika indikator kosong
                $indikators = ['Umum'];
            }

            // 2. Ambil semua jawaban kuantitatif (punya nilai) dari kuesioner ini
            $jawabans = DB::table('jawaban')
                ->join('pertanyaan', 'jawaban.pertanyaan_id', '=', 'pertanyaan.id')
                ->where('jawaban.kuesioner_id', $selectedKuesionerId)
                ->whereNotNull('jawaban.nilai_jawaban')
                ->select('jawaban.user_id', 'pertanyaan.indikator', 'jawaban.nilai_jawaban')
                ->get();

            // 3. Ekstrak Fitur: Rata-rata per indikator untuk tiap siswa
            $userScores = [];
            foreach ($jawabans as $j) {
                $ind = $j->indikator ?: 'Umum';
                if (!isset($userScores[$j->user_id])) {
                    $userScores[$j->user_id] = [];
                }
                if (!isset($userScores[$j->user_id][$ind])) {
                    $userScores[$j->user_id][$ind] = ['sum' => 0, 'count' => 0];
                }
                $userScores[$j->user_id][$ind]['sum'] += $j->nilai_jawaban;
                $userScores[$j->user_id][$ind]['count']++;
            }

            // Format menjadi dataset K-Means
            $dataset = [];
            $userIds = array_keys($userScores);
            
            // Ambil data user sekaligus untuk efisiensi
            $users = User::with('siswa')->whereIn('id', $userIds)->get()->keyBy('id');

            foreach ($userScores as $userId => $scores) {
                $features = [];
                foreach ($indikators as $ind) {
                    if (isset($scores[$ind]) && $scores[$ind]['count'] > 0) {
                        $features[] = $scores[$ind]['sum'] / $scores[$ind]['count'];
                    } else {
                        // Jika siswa tidak menjawab indikator ini, beri nilai rata-rata tengah (misal 3) atau 0.
                        // Idealnya kita beri nilai rata-rata keseluruhan indikator tsb, tapi untuk kemudahan set 0.
                        $features[] = 0; 
                    }
                }

                if (isset($users[$userId])) {
                    $dataset[] = [
                        'user_id' => $userId,
                        'user_name' => $users[$userId]->name,
                        'kelas' => $users[$userId]->siswa->kelas ?? '-',
                        'features' => $features
                    ];
                }
            }

            // 4. Jalankan K-Means jika data cukup (minimal 4 data untuk K=4)
            if (count($dataset) >= 4) {
                $kMeans = new KMeansService(4, 100);
                $clusteringResult = $kMeans->cluster($dataset);
                
                if (isset($clusteringResult['error'])) {
                    $error = $clusteringResult['error'];
                    $clusteringResult = null;
                }
            } else {
                $error = "Data responden terlalu sedikit untuk melakukan clustering. Minimal butuh 4 responden yang telah mengisi survei secara kuantitatif.";
            }
        }

        return view('admin.clustering.index', compact(
            'kuesioners', 
            'selectedKuesionerId', 
            'kuesioner', 
            'indikators', 
            'clusteringResult', 
            'error'
        ));
    }
}
