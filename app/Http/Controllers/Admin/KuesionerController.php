<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kuesioner;
use Illuminate\Http\Request;

class KuesionerController extends Controller
{
    public function index(Request $request)
    {
        $query = Kuesioner::query();

        if ($request->filled('search')) {
            $query->where('nama_kuesioner', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $kuesioners = $query->latest()->paginate(10);
        return view('admin.kuesioner.index', compact('kuesioners'));
    }

    public function create()
    {
        return view('admin.kuesioner.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kuesioner' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after:periode_mulai',
            'status' => 'required|in:draft,aktif,selesai',
        ]);

        Kuesioner::create([
            ...$request->only(['nama_kuesioner', 'deskripsi', 'periode_mulai', 'periode_selesai', 'status']),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.kuesioner.index')->with('success', 'Kuesioner berhasil dibuat!');
    }

    public function edit(Kuesioner $kuesioner)
    {
        return view('admin.kuesioner.edit', compact('kuesioner'));
    }

    public function update(Request $request, Kuesioner $kuesioner)
    {
        $request->validate([
            'nama_kuesioner' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after:periode_mulai',
            'status' => 'required|in:draft,aktif,selesai',
        ]);

        $kuesioner->update($request->only(['nama_kuesioner', 'deskripsi', 'periode_mulai', 'periode_selesai', 'status']));

        return redirect()->route('admin.kuesioner.index')->with('success', 'Kuesioner berhasil diperbarui!');
    }

    public function destroy(Kuesioner $kuesioner)
    {
        $kuesioner->delete();
        return redirect()->route('admin.kuesioner.index')->with('success', 'Kuesioner berhasil dihapus!');
    }
}
