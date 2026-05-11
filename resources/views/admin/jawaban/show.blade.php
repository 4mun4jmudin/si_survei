<x-admin-layout>
    <x-slot name="title">Detail Jawaban Responden</x-slot>
    <x-slot name="header">Detail Jawaban: {{ $user->name }}</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.jawaban.index', ['kuesioner_id' => $kuesioner->id]) }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-indigo-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Responden
        </a>
    </div>

    <!-- Info Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Informasi Kuesioner</h3>
                <p class="text-lg font-bold text-gray-800">{{ $kuesioner->nama_kuesioner }}</p>
                <p class="text-sm text-gray-500 mt-1">Periode: {{ \Carbon\Carbon::parse($kuesioner->periode_mulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($kuesioner->periode_selesai)->format('d M Y') }}</p>
            </div>
            <div class="md:border-l md:border-gray-100 md:pl-6">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Informasi Responden</h3>
                <div class="flex items-center gap-3 mt-2">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                        <span class="text-sm font-bold text-indigo-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">NIS: {{ $user->siswa->nis ?? '-' }} | Kelas: {{ $user->siswa->kelas ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- List Jawaban -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-semibold text-gray-800">Hasil Pengisian Survei</h3>
            <p class="text-sm text-gray-500">Total: {{ $jawabans->count() }} jawaban tersimpan.</p>
        </div>
        
        <div class="divide-y divide-gray-100">
            @forelse($jawabans as $index => $jawaban)
                <div class="p-6 hover:bg-gray-50 transition">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold text-sm">
                                {{ $jawaban->pertanyaan->nomor_urutan ?? ($index + 1) }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-800 leading-relaxed">{{ $jawaban->pertanyaan->isi_pertanyaan ?? 'Pertanyaan tidak ditemukan' }}</h4>
                                    <p class="text-xs text-gray-400 mt-1">Indikator: {{ $jawaban->pertanyaan->indikator ?? '-' }}</p>
                                </div>
                                <span class="px-2.5 py-1 text-[10px] font-semibold bg-gray-100 text-gray-500 rounded-lg uppercase tracking-wider whitespace-nowrap">
                                    {{ str_replace('_', ' ', $jawaban->pertanyaan->tipe_jawaban ?? 'unknown') }}
                                </span>
                            </div>
                            
                            <div class="mt-4 p-4 rounded-xl {{ $jawaban->nilai_jawaban ? 'bg-indigo-50/50 border border-indigo-100' : 'bg-gray-50 border border-gray-100' }}">
                                @if($jawaban->pertanyaan && $jawaban->pertanyaan->tipe_jawaban == 'esai')
                                    <p class="text-sm text-gray-700 italic">"{{ $jawaban->jawaban_teks ?? 'Kosong' }}"</p>
                                @else
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-500">Nilai Skor:</span>
                                        <span class="text-base font-bold {{ $jawaban->nilai_jawaban >= 4 ? 'text-emerald-600' : ($jawaban->nilai_jawaban >= 3 ? 'text-indigo-600' : 'text-amber-600') }}">
                                            {{ $jawaban->nilai_jawaban ?? 'Belum dijawab' }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="text-sm text-gray-500">Belum ada jawaban yang tersimpan.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-admin-layout>
