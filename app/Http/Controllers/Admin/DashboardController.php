<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kuesioner;
use App\Models\Pertanyaan;
use App\Models\Jawaban;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $kuesionerAktif = Kuesioner::where('status', 'aktif')->count();
        $totalPertanyaan = Pertanyaan::count();
        $totalRespon = Jawaban::select('user_id', 'kuesioner_id')->distinct()->count();
        $kuesionerTerbaru = Kuesioner::latest()->take(5)->get();

        // Data untuk Chart: Distribusi Role User
        $roleDistribusi = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();

        // Data untuk Chart: Rata-rata Skor per Indikator (kuesioner aktif terbaru)
        $kuesionerChart = Kuesioner::where('status', 'aktif')->latest()->first();
        $chartIndikator = [];
        $chartSkor = [];

        if ($kuesionerChart) {
            $indikatorData = DB::table('jawaban')
                ->join('pertanyaan', 'jawaban.pertanyaan_id', '=', 'pertanyaan.id')
                ->where('jawaban.kuesioner_id', $kuesionerChart->id)
                ->whereNotNull('jawaban.nilai_jawaban')
                ->select('pertanyaan.indikator', DB::raw('ROUND(AVG(jawaban.nilai_jawaban), 2) as avg_skor'))
                ->groupBy('pertanyaan.indikator')
                ->get();

            foreach ($indikatorData as $row) {
                $chartIndikator[] = $row->indikator ?: 'Umum';
                $chartSkor[] = (float) $row->avg_skor;
            }
        }

        // Data untuk Chart: Tren Jawaban Masuk per Hari (7 hari terakhir)
        $trendData = DB::table('jawaban')
            ->select(DB::raw('DATE(created_at) as tanggal'), DB::raw('COUNT(DISTINCT user_id) as total_responden'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal')
            ->get();

        $trendLabels = $trendData->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))->toArray();
        $trendValues = $trendData->pluck('total_responden')->toArray();

        return view('admin.dashboard', compact(
            'totalUsers',
            'kuesionerAktif',
            'totalPertanyaan',
            'totalRespon',
            'kuesionerTerbaru',
            'roleDistribusi',
            'chartIndikator',
            'chartSkor',
            'trendLabels',
            'trendValues'
        ));
    }
}
