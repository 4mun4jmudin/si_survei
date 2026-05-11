<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pertanyaan;
use App\Models\Kuesioner;
use Illuminate\Http\Request;

class PertanyaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pertanyaan::with('kuesioner');

        if ($request->filled('search')) {
            $query->where('isi_pertanyaan', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('kuesioner_id')) {
            $query->where('kuesioner_id', $request->kuesioner_id);
        }

        $pertanyaans = $query->orderBy('nomor_urutan')->paginate(10);
        $kuesioners = Kuesioner::all();
        return view('admin.pertanyaan.index', compact('pertanyaans', 'kuesioners'));
    }

    public function create()
    {
        $kuesioners = Kuesioner::all();
        return view('admin.pertanyaan.create', compact('kuesioners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kuesioner_id' => 'required|exists:kuesioner,id',
            'indikator' => 'nullable|string|max:255',
            'nomor_urutan' => 'required|integer',
            'isi_pertanyaan' => 'required|string',
            'tipe_jawaban' => 'required|in:pilihan_ganda,skala_likert,esai',
            'bobot' => 'required|numeric|min:0',
        ]);

        Pertanyaan::create($request->all());

        return redirect()->route('admin.pertanyaan.index')->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    public function edit(Pertanyaan $pertanyaan)
    {
        $kuesioners = Kuesioner::all();
        return view('admin.pertanyaan.edit', compact('pertanyaan', 'kuesioners'));
    }

    public function update(Request $request, Pertanyaan $pertanyaan)
    {
        $request->validate([
            'kuesioner_id' => 'required|exists:kuesioner,id',
            'indikator' => 'nullable|string|max:255',
            'nomor_urutan' => 'required|integer',
            'isi_pertanyaan' => 'required|string',
            'tipe_jawaban' => 'required|in:pilihan_ganda,skala_likert,esai',
            'bobot' => 'required|numeric|min:0',
        ]);

        $pertanyaan->update($request->all());

        return redirect()->route('admin.pertanyaan.index')->with('success', 'Pertanyaan berhasil diperbarui!');
    }

    public function destroy(Pertanyaan $pertanyaan)
    {
        $pertanyaan->delete();
        return redirect()->route('admin.pertanyaan.index')->with('success', 'Pertanyaan berhasil dihapus!');
    }
}
