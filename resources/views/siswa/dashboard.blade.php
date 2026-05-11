<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($kuesionerTersedia->count() > 0)
            <div class="mb-6 bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-2xl shadow-sm flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-amber-800">Pengingat Penting</h3>
                    <p class="mt-1 text-sm text-amber-700">
                        Anda memiliki <strong>{{ $kuesionerTersedia->count() }}</strong> kuesioner aktif yang belum diisi. Mohon segera melengkapinya.
                    </p>
                </div>
            </div>
            @endif

            <!-- Welcome Header -->
            <div class="mb-10 bg-white rounded-3xl p-8 shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Halo, {{ Auth::user()->name }}! 👋</h2>
                    <p class="text-gray-500 mt-2">Suaramu sangat berarti untuk kemajuan sekolah kita.</p>
                </div>
                <div class="flex items-center gap-4 bg-indigo-50 px-6 py-4 rounded-2xl border border-indigo-100">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-indigo-600 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Status Partisipasi</p>
                        <p class="text-lg font-bold text-gray-900">{{ $kuesionerSelesai->count() }} Survei Diisi</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Survei</p>
                    <p class="text-2xl font-black text-gray-900">{{ $kuesionerTersedia->count() + $kuesionerSelesai->count() }}</p>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-1">Survei Aktif</p>
                    <p class="text-2xl font-black text-gray-900">{{ $kuesionerTersedia->count() }}</p>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Selesai</p>
                    <p class="text-2xl font-black text-gray-900">{{ $kuesionerSelesai->count() }}</p>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-1">Partisipasi</p>
                    @php
                        $total = $kuesionerTersedia->count() + $kuesionerSelesai->count();
                        $percent = $total > 0 ? round(($kuesionerSelesai->count() / $total) * 100) : 0;
                    @endphp
                    <p class="text-2xl font-black text-gray-900">{{ $percent }}%</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- Main Content: Available Surveys -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xl font-bold text-gray-800 tracking-tight">Kuesioner Tersedia</h3>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">{{ $kuesionerTersedia->count() }} Baru</span>
                    </div>

                    @forelse($kuesionerTersedia as $kuesioner)
                        <div class="group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:border-indigo-300 hover:shadow-xl transition-all duration-300">
                            <div class="flex flex-col sm:flex-row justify-between gap-6">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-[10px] font-black uppercase tracking-widest rounded-lg border border-indigo-200">Survei Siswa</span>
                                        <span class="text-gray-400 text-sm flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Hingga {{ \Carbon\Carbon::parse($kuesioner->periode_selesai)->format('d M Y') }}
                                        </span>
                                    </div>
                                    <h4 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition">{{ $kuesioner->nama_kuesioner }}</h4>
                                    <p class="text-gray-500 mt-2 text-sm line-clamp-2">Mohon berikan penilaian objektif Anda mengenai pengalaman belajar dan fasilitas di sekolah selama periode ini.</p>
                                </div>
                                <div class="shrink-0 flex items-center">
                                    <a href="{{ route('siswa.survei.detail', $kuesioner) }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-indigo-600 text-white text-sm font-bold rounded-2xl hover:bg-indigo-700 transition shadow-lg hover:shadow-indigo-200 group-hover:scale-105">
                                        Mulai Survei
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-3xl p-16 text-center shadow-sm border border-gray-100">
                            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <h4 class="text-xl font-bold text-gray-800">Semua Terisi! ✨</h4>
                            <p class="text-gray-500 mt-2">Tidak ada kuesioner aktif yang perlu diisi saat ini.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Sidebar: History -->
                <div class="space-y-6">
                    <h3 class="text-xl font-bold text-gray-800 tracking-tight mb-2">Riwayat Pengisian</h3>
                    
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        @forelse($kuesionerSelesai as $selesai)
                            <div class="p-6 border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Selesai</span>
                                </div>
                                <h5 class="text-sm font-bold text-gray-900 line-clamp-1">{{ $selesai->nama_kuesioner }}</h5>
                                <p class="text-xs text-gray-500 mt-1">{{ $selesai->pertanyaan_count }} Pertanyaan Terjawab</p>
                            </div>
                        @empty
                            <div class="p-10 text-center">
                                <p class="text-sm text-gray-400">Belum ada riwayat.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Tips Card -->
                    <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-3xl p-8 text-white shadow-lg shadow-indigo-100">
                        <svg class="w-10 h-10 mb-4 text-indigo-200 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                        <h4 class="text-lg font-bold mb-2">Pentingnya Suaramu</h4>
                        <p class="text-sm text-indigo-100 leading-relaxed">Setiap penilaian yang kamu berikan membantu sekolah mengidentifikasi hal-hal yang perlu diperbaiki demi kenyamanan belajarmu.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
