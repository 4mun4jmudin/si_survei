<x-admin-layout>
    <x-slot name="title">Data Jawaban Responden</x-slot>
    <x-slot name="header">Data Jawaban Survei</x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Responden</h3>
                    <p class="text-sm text-gray-500">Lihat siswa yang sudah mengisi kuesioner dan detail jawabannya.</p>
                </div>
            </div>

            <!-- Filters -->
            <form method="GET" class="mt-4 flex flex-col sm:flex-row gap-3">
                <select name="kuesioner_id" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 w-full sm:w-1/3">
                    @foreach($kuesioners as $k)
                        <option value="{{ $k->id }}" {{ request('kuesioner_id', $selectedKuesioner->id ?? '') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kuesioner }} ({{ ucfirst($k->status) }})
                        </option>
                    @endforeach
                </select>
                <input type="text" name="kelas" value="{{ request('kelas') }}" placeholder="Filter Kelas (opsional)" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-1/4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/NIS siswa..." class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition">Lihat Data</button>
            </form>
        </div>

        @if($selectedKuesioner)
        <div class="px-6 py-4 bg-indigo-50 border-b border-gray-100 flex items-center justify-between">
            <div class="text-sm">
                <span class="font-semibold text-indigo-900">Total Pertanyaan di Kuesioner Ini:</span> 
                <span class="text-indigo-700">{{ $selectedKuesioner->pertanyaan_count ?? 0 }} soal</span>
            </div>
            <div class="text-sm">
                <span class="font-semibold text-indigo-900">Total Responden:</span> 
                <span class="text-indigo-700">{{ $respondens->total() }} orang</span>
            </div>
        </div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Siswa Responden</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Pengisian</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($respondens as $responden)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-bold text-indigo-600">{{ strtoupper(substr($responden->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-800 block">{{ $responden->name }}</span>
                                    <span class="text-xs text-gray-500">NIS: {{ $responden->siswa->nis ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-semibold bg-gray-100 text-gray-700 rounded-lg">{{ $responden->siswa->kelas ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($responden->total_dijawab > 0)
                                @if($selectedKuesioner && $responden->total_dijawab >= $selectedKuesioner->pertanyaan_count)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Selesai ({{ $responden->total_dijawab }} soal)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold bg-amber-100 text-amber-700 rounded-full">
                                        Menyicil ({{ $responden->total_dijawab }} soal)
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full">
                                    Belum Mengisi
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($responden->total_dijawab > 0 && $selectedKuesioner)
                                <div class="flex items-center justify-end gap-2" x-data="{ open: false }">
                                    <a href="{{ route('admin.jawaban.show', [$selectedKuesioner->id, $responden->id]) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-200 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50 transition shadow-sm">
                                        Lihat
                                    </a>
                                    
                                    <!-- Dropdown Menu -->
                                    <div class="relative">
                                        <button @click="open = !open" class="p-1.5 hover:bg-gray-100 rounded-lg transition text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg>
                                        </button>
                                        
                                        <div x-show="open" @click.away="open = false" x-transition.opacity class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2 text-left overflow-hidden">
                                            <form action="{{ route('admin.jawaban.reset', [$selectedKuesioner->id, $responden->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA jawaban siswa ini? Tindakan ini tidak bisa dibatalkan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm font-bold text-red-600 hover:bg-red-50 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Reset Jawaban
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic font-medium">Belum ada data</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">Tidak ada data responden ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $respondens->withQueryString()->links() }}
        </div>
    </div>
</x-admin-layout>
