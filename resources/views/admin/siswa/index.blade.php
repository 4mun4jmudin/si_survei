<x-admin-layout>
    <x-slot name="title">Data Siswa</x-slot>
    <x-slot name="header">Data Siswa</x-slot>

    <div x-data="{ importModal: false }" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
        <div class="p-6 sm:p-8 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Daftar Siswa</h3>
                    <p class="text-sm text-gray-500 mt-1">Kelola data responden (siswa) untuk survei.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button @click="importModal = true" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-emerald-50 text-emerald-700 text-sm font-bold rounded-2xl hover:bg-emerald-100 transition border border-emerald-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Import Excel
                    </button>
                    <a href="{{ route('admin.siswa.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white text-sm font-bold rounded-2xl hover:bg-indigo-700 transition shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Siswa
                    </a>
                </div>
            </div>

            <!-- Import Modal -->
            <div x-show="importModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm" x-cloak style="display: none;">
                <div @click.away="importModal = false" class="bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full mx-4 relative"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Import Data Siswa</h3>
                        <button @click="importModal = false" class="text-gray-400 hover:text-red-500 transition p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Upload File Excel</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center hover:bg-gray-50 transition cursor-pointer relative">
                                <svg class="w-10 h-10 text-emerald-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="text-sm font-medium text-gray-600 mb-1">Klik atau drag file Excel (.xlsx) kesini</p>
                                <p class="text-[11px] text-gray-400">Pastikan file memiliki header: nama, username, nis, dll.</p>
                                <input type="file" name="file" accept=".xlsx, .xls, .csv" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>
                        <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Mulai Import
                        </button>
                    </form>
                </div>
            </div>
            <form method="GET" class="mt-6 flex flex-col sm:flex-row gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIS..." class="flex-1 px-5 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                <input type="text" name="kelas" value="{{ request('kelas') }}" placeholder="Filter Kelas (misal: X-IPA-1)" class="w-full sm:w-1/3 px-5 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                <button type="submit" class="px-6 py-3 bg-gray-100 text-gray-700 text-sm font-bold rounded-2xl hover:bg-gray-200 transition">Filter</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">NIS</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Nama Lengkap</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Kelas</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">L/P</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Akun Login</th>
                        <th class="text-right px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($siswas as $siswa)
                    <tr class="hover:bg-gray-50/50 transition group">
                        <td class="px-6 py-5 text-sm font-bold text-gray-800">{{ $siswa->nis }}</td>
                        <td class="px-6 py-5 text-sm font-medium text-gray-700">{{ $siswa->nama_lengkap }}</td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 text-[11px] font-bold bg-sky-50 text-sky-700 rounded-lg border border-sky-100">{{ $siswa->kelas }}</span>
                        </td>
                        <td class="px-6 py-5 text-sm text-gray-600">{{ $siswa->jenis_kelamin }}</td>
                        <td class="px-6 py-5 text-sm">
                            @if($siswa->user)
                                <span class="text-indigo-600 font-semibold">{{ $siswa->user->username }}</span>
                            @else
                                <span class="text-red-500 italic text-xs">Belum ditautkan</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.siswa.edit', $siswa) }}" class="p-2.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.siswa.destroy', $siswa) }}" method="POST" onsubmit="return confirm('Yakin hapus data siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
                            </div>
                            <p class="text-sm font-bold text-gray-600">Tidak ada data siswa</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $siswas->withQueryString()->links() }}
        </div>
    </div>
</x-admin-layout>
