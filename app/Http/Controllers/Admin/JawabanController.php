<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jawaban;
use App\Models\Kuesioner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JawabanController extends Controller
{
    public function index(Request $request)
    {
        $kuesioners = Kuesioner::latest()->get();
        $selectedKuesionerId = $request->kuesioner_id ?? ($kuesioners->first()->id ?? null);

        $query = User::whereHas('siswa')->with('siswa');

        if ($selectedKuesionerId) {
            $query->whereHas('jawaban', function ($q) use ($selectedKuesionerId) {
                $q->where('kuesioner_id', $selectedKuesionerId);
            })->withCount(['jawaban as total_dijawab' => function ($q) use ($selectedKuesionerId) {
                $q->where('kuesioner_id', $selectedKuesionerId);
            }]);
        }

        if ($request->filled('kelas')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('siswa', function ($sq) use ($request) {
                      $sq->where('nis', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $respondens = $query->paginate(10);
        $selectedKuesioner = $selectedKuesionerId ? Kuesioner::withCount('pertanyaan')->find($selectedKuesionerId) : null;

        return view('admin.jawaban.index', compact('respondens', 'kuesioners', 'selectedKuesioner'));
    }

    public function show($kuesioner_id, $user_id)
    {
        $kuesioner = Kuesioner::findOrFail($kuesioner_id);
        $user = User::with('siswa')->findOrFail($user_id);
        
        $jawabans = Jawaban::with('pertanyaan')
            ->where('kuesioner_id', $kuesioner_id)
            ->where('user_id', $user_id)
            ->get();

        return view('admin.jawaban.show', compact('kuesioner', 'user', 'jawabans'));
    }

    public function reset($kuesioner_id, $user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            Jawaban::where('kuesioner_id', $kuesioner_id)
                ->where('user_id', $user_id)
                ->delete();

            return back()->with('success', "Jawaban dari {$user->name} berhasil di-reset.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mereset jawaban.');
        }
    }
}
