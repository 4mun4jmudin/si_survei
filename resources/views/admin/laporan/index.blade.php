<x-admin-layout>
    <x-slot name="title">Daftar Arsip Laporan</x-slot>
    <x-slot name="header">Arsip Laporan Hasil Survei</x-slot>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Dokumen Laporan Akhir</h3>
                <p class="text-sm text-gray-500 mt-1">Kelola dan cetak arsip laporan evaluasi hasil survei.</p>
            </div>
            <a href="{{ route('admin.laporan.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white text-sm font-bold rounded-2xl hover:bg-indigo-700 transition shadow-md hover:shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Laporan Baru
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest w-24">Tanggal</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Judul Laporan</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Periode Survei</th>
                        <th class="text-right px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($laporans as $laporan)
                    <tr class="hover:bg-gray-50 transition group">
                        <td class="px-6 py-5">
                            <span class="text-sm font-bold text-gray-800 block">{{ $laporan->created_at->format('d M') }}</span>
                            <span class="text-xs text-gray-500">{{ $laporan->created_at->format('Y') }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-sm font-bold text-indigo-600 group-hover:text-indigo-700 transition">{{ $laporan->judul_laporan }}</p>
                            <p class="text-xs text-gray-500 mt-1 truncate max-w-md">{{ Str::limit($laporan->ringkasan, 80) ?? 'Tidak ada ringkasan' }}</p>
                        </td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                {{ $laporan->kuesioner->nama_kuesioner ?? 'Dihapus' }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.laporan.cetak', $laporan) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-bold rounded-xl transition border border-emerald-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    Cetak PDF
                                </a>
                                <form action="{{ route('admin.laporan.destroy', $laporan) }}" method="POST" onsubmit="return confirm('Hapus arsip laporan ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <p class="text-sm font-bold text-gray-600">Belum ada arsip laporan</p>
                            <p class="text-xs text-gray-400 mt-1">Buat laporan baru setelah masa survei berakhir.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $laporans->links() }}
        </div>
    </div>
</x-admin-layout>
