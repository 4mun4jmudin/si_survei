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

            $indikatorStats = $indikatorStatsRaw->map(function($item) {
                $kategori = 'Kurang';
                if ($item->rata_rata >= 4) $kategori = 'Sangat Baik';
                elseif ($item->rata_rata >= 3) $kategori = 'Baik';
                elseif ($item->rata_rata >= 2) $kategori = 'Cukup';

                return [
                    'indikator' => $item->indikator ?: 'Umum',
                    'rata_rata' => round($item->rata_rata, 2),
                    'total' => $item->total_jawaban,
                    'kategori' => $kategori
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
