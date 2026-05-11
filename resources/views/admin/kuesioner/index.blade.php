<x-admin-layout>
    <x-slot name="title">Data Kuesioner</x-slot>
    <x-slot name="header">Daftar Kuesioner</x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Periode Survei / Kuesioner</h3>
                    <p class="text-sm text-gray-500">Kelola kuesioner yang akan diisi oleh responden.</p>
                </div>
                <a href="{{ route('admin.kuesioner.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Kuesioner
                </a>
            </div>

            <!-- Filters -->
            <form method="GET" class="mt-4 flex flex-col sm:flex-row gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul kuesioner..." class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                <button type="submit" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition">Filter</button>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul Kuesioner</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dibuat Pada</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($kuesioners as $kuesioner)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-800">{{ $kuesioner->nama_kuesioner }}</p>
                            <p class="text-xs text-gray-500 mt-1 truncate max-w-xs">{{ $kuesioner->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600">
                                <div><span class="text-gray-400 text-xs">Mulai:</span> {{ \Carbon\Carbon::parse($kuesioner->periode_mulai)->format('d M Y') }}</div>
                                <div><span class="text-gray-400 text-xs">Akhir:</span> {{ \Carbon\Carbon::parse($kuesioner->periode_selesai)->format('d M Y') }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($kuesioner->status == 'draft')
                                <span class="px-2.5 py-1 text-xs font-semibold bg-gray-100 text-gray-600 rounded-lg">Draft</span>
                            @elseif($kuesioner->status == 'aktif')
                                <span class="px-2.5 py-1 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded-lg">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-semibold bg-blue-50 text-blue-700 rounded-lg">Selesai</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $kuesioner->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.kuesioner.edit', $kuesioner) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.kuesioner.destroy', $kuesioner) }}" method="POST" onsubmit="return confirm('Yakin hapus kuesioner ini? Semua pertanyaan di dalamnya juga bisa terhapus jika berelasi.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">Tidak ada data kuesioner.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $kuesioners->withQueryString()->links() }}
        </div>
    </div>
</x-admin-layout>
