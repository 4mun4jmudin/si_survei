<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GuruImport;

class GuruController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            Excel::import(new GuruImport, $request->file('file'));
            return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->route('admin.guru.index')->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }
    public function index(Request $request)
    {
        $query = Guru::with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%');
            });
        }

        $gurus = $query->latest()->paginate(10);
        return view('admin.guru.index', compact('gurus'));
    }

    public function create()
    {
        $users = User::where('role', 'guru')->doesntHave('guru')->get();
        return view('admin.guru.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:guru,user_id',
            'nip' => 'required|string|unique:guru,nip',
            'nama_lengkap' => 'required|string|max:255',
            'mapel' => 'required|string|max:255',
        ]);

        Guru::create($request->all());

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil ditambahkan!');
    }

    public function edit(Guru $guru)
    {
        $users = User::where('role', 'guru')->where(function ($q) use ($guru) {
            $q->doesntHave('guru')->orWhere('id', $guru->user_id);
        })->get();
        return view('admin.guru.edit', compact('guru', 'users'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:guru,user_id,' . $guru->id,
            'nip' => 'required|string|unique:guru,nip,' . $guru->id,
            'nama_lengkap' => 'required|string|max:255',
            'mapel' => 'required|string|max:255',
        ]);

        $guru->update($request->all());

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui!');
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();
        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil dihapus!');
    }
}
