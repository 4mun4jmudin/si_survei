<?php

namespace App\Http\Controllers;

use App\Models\Kuesioner;
use App\Models\Jawaban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuruPortalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil kuesioner yang aktif dan belum diisi oleh guru ini
        $kuesionerTersedia = Kuesioner::where('status', 'aktif')
            ->whereDoesntHave('jawaban', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        // Ambil kuesioner yang sudah diisi
        $kuesionerSelesai = Kuesioner::whereHas('jawaban', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->withCount('pertanyaan')
        ->latest()
        ->get();

        return view('guru.dashboard', compact('kuesionerTersedia', 'kuesionerSelesai'));
    }

    public function showDetail(Kuesioner $kuesioner)
    {
        $user = Auth::user();
        $sudahIsi = Jawaban::where('kuesioner_id', $kuesioner->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($sudahIsi) return redirect()->route('guru.dashboard')->with('error', 'Anda sudah mengisi kuesioner ini.');

        $pertanyaanCount = $kuesioner->pertanyaan()->count();
        return view('guru.survei_detail', compact('kuesioner', 'pertanyaanCount'));
    }

    public function showSurvei(Kuesioner $kuesioner)
    {
        $user = Auth::user();

        // Cek apakah sudah pernah mengisi
        $sudahIsi = Jawaban::where('kuesioner_id', $kuesioner->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($sudahIsi) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda sudah mengisi kuesioner ini.');
        }

        if ($kuesioner->status !== 'aktif') {
            return redirect()->route('guru.dashboard')->with('error', 'Kuesioner ini sudah tidak aktif.');
        }

        // Ambil pertanyaan dan kelompokkan berdasarkan indikator
        $pertanyaanGrouped = $kuesioner->pertanyaan()
            ->orderBy('nomor_urutan')
            ->get()
            ->groupBy('indikator');

        return view('guru.survei_step', compact('kuesioner', 'pertanyaanGrouped'));
    }

    public function simpanSurvei(Request $request, Kuesioner $kuesioner)
    {
        $user = Auth::user();

        $request->validate([
            'jawaban' => 'required|array',
            'jawaban.*' => 'required|integer|min:1|max:5',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->jawaban as $pertanyaanId => $nilai) {
                Jawaban::create([
                    'user_id' => $user->id,
                    'kuesioner_id' => $kuesioner->id,
                    'pertanyaan_id' => $pertanyaanId,
                    'nilai_jawaban' => $nilai,
                ]);
            }

            DB::commit();

            return redirect()->route('guru.dashboard')->with('success', 'Terima kasih telah mengisi survei! Kontribusi Anda sangat berharga.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan jawaban.')->withInput();
        }
    }

    public function profil()
    {
        $user = Auth::user();
        $user->load('guru');
        return view('guru.profil', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
