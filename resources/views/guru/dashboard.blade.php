<x-app-layout>
    <div class="min-h-screen" style="background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 40%, #f0fdf4 100%);">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

            {{-- Notification Banner --}}
            @if($kuesionerTersedia->count() > 0)
            <div class="mb-8 relative overflow-hidden rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 p-[1px]">
                <div class="bg-white/95 backdrop-blur-sm rounded-[15px] px-5 py-4 flex items-center gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800">{{ $kuesionerTersedia->count() }} Kuesioner Menunggu</p>
                        <p class="text-xs text-slate-500 mt-0.5">Silakan lengkapi survei aktif Anda</p>
                    </div>
                    <a href="#survei-tersedia" class="flex-shrink-0 px-4 py-2 text-xs font-bold text-amber-700 bg-amber-50 rounded-xl hover:bg-amber-100 transition">Lihat</a>
                </div>
            </div>
            @endif

            {{-- Hero Welcome --}}
            <div class="mb-10 relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-800 p-8 sm:p-10 text-white">
                {{-- Background decoration --}}
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/3"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4"></div>

                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/15 backdrop-blur-sm rounded-full text-[10px] font-bold uppercase tracking-widest mb-4 border border-white/10">
                            <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></div>
                            Portal Guru
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Selamat Datang, {{ Auth::user()->name }} 👋</h1>
                        <p class="text-indigo-200 mt-2 text-sm sm:text-base max-w-md">Kontribusi Anda dalam evaluasi sangat berharga untuk peningkatan kualitas sekolah.</p>
                    </div>
                    <div class="flex-shrink-0 bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/10 text-center min-w-[130px]">
                        <p class="text-3xl sm:text-4xl font-black">{{ $kuesionerSelesai->count() }}</p>
                        <p class="text-xs text-indigo-200 font-semibold mt-1">Survei Selesai</p>
                    </div>
                </div>
            </div>

            {{-- Stats Row --}}
            @php
                $total = $kuesionerTersedia->count() + $kuesionerSelesai->count();
                $percent = $total > 0 ? round(($kuesionerSelesai->count() / $total) * 100) : 0;
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-10">
                <div class="bg-white/80 backdrop-blur-sm p-5 rounded-2xl border border-white shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $total }}</p>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mt-1">Total</p>
                </div>
                <div class="bg-white/80 backdrop-blur-sm p-5 rounded-2xl border border-white shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $kuesionerTersedia->count() }}</p>
                    <p class="text-[11px] font-semibold text-indigo-500 uppercase tracking-wider mt-1">Aktif</p>
                </div>
                <div class="bg-white/80 backdrop-blur-sm p-5 rounded-2xl border border-white shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $kuesionerSelesai->count() }}</p>
                    <p class="text-[11px] font-semibold text-emerald-500 uppercase tracking-wider mt-1">Selesai</p>
                </div>
                <div class="bg-white/80 backdrop-blur-sm p-5 rounded-2xl border border-white shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $percent }}%</p>
                    <p class="text-[11px] font-semibold text-amber-500 uppercase tracking-wider mt-1">Partisipasi</p>
                </div>
            </div>

            {{-- Main Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8" id="survei-tersedia">
                {{-- Available Surveys (3/5) --}}
                <div class="lg:col-span-3 space-y-5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Kuesioner Tersedia</h2>
                        @if($kuesionerTersedia->count() > 0)
                        <span class="px-2.5 py-1 bg-indigo-100 text-indigo-700 text-[10px] font-bold rounded-full">{{ $kuesionerTersedia->count() }} baru</span>
                        @endif
                    </div>

                    @forelse($kuesionerTersedia as $kuesioner)
                    <div class="group relative bg-white/80 backdrop-blur-sm rounded-2xl border border-white shadow-sm hover:shadow-lg hover:border-indigo-200 transition-all duration-300 overflow-hidden">
                        {{-- Top accent --}}
                        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-indigo-500 to-blue-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="p-6">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-11 h-11 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap mb-2">
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-md uppercase tracking-wider border border-emerald-100">Aktif</span>
                                        <span class="text-xs text-slate-400 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            s/d {{ \Carbon\Carbon::parse($kuesioner->periode_selesai)->format('d M Y') }}
                                        </span>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-900 group-hover:text-indigo-700 transition-colors line-clamp-1">{{ $kuesioner->nama_kuesioner }}</h3>
                                    <p class="text-sm text-slate-400 mt-1 line-clamp-1">Berikan penilaian objektif Anda untuk periode ini.</p>
                                </div>
                            </div>

                            <div class="mt-5 flex items-center justify-between">
                                <div class="flex items-center gap-2 text-xs text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $kuesioner->pertanyaan()->count() }} pertanyaan
                                </div>
                                <a href="{{ route('guru.survei.detail', $kuesioner) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700 transition shadow-sm hover:shadow-indigo-200 group-hover:scale-[1.02]">
                                    Mulai
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-12 text-center border border-dashed border-slate-200">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-700">Semua Terisi! ✨</h3>
                        <p class="text-sm text-slate-400 mt-1">Tidak ada kuesioner aktif saat ini.</p>
                    </div>
                    @endforelse
                </div>

                {{-- Sidebar (2/5) --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Riwayat --}}
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 tracking-tight mb-4">Riwayat Pengisian</h2>
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white shadow-sm overflow-hidden">
                            @forelse($kuesionerSelesai as $selesai)
                            <div class="p-4 border-b border-slate-50 last:border-0 hover:bg-slate-50/50 transition flex items-center gap-3">
                                <div class="flex-shrink-0 w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-bold text-slate-800 truncate">{{ $selesai->nama_kuesioner }}</h4>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $selesai->pertanyaan_count }} pertanyaan dijawab</p>
                                </div>
                            </div>
                            @empty
                            <div class="p-8 text-center">
                                <p class="text-sm text-slate-400">Belum ada riwayat.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Info Card --}}
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 p-6 text-white">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                        <div class="relative z-10">
                            <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="text-base font-bold mb-2">Evaluasi Berkala</h3>
                            <p class="text-sm text-slate-300 leading-relaxed">Partisipasi Anda dalam survei membantu sekolah terus meningkatkan kualitas pengajaran dan pelayanan.</p>
                        </div>
                    </div>

                    {{-- Quick Link Profil --}}
                    <a href="{{ route('guru.profil') }}" class="flex items-center gap-3 bg-white/80 backdrop-blur-sm rounded-2xl border border-white shadow-sm p-4 hover:shadow-md hover:border-indigo-200 transition-all group">
                        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-800 group-hover:text-indigo-700 transition-colors">Profil & Keamanan</p>
                            <p class="text-xs text-slate-400">Ubah password akun Anda</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
