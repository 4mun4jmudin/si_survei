<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistem Survei Kepuasan Siswa - {{ config('app.name', 'SiSurvei') }}</title>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .glass {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
            }
            .gradient-text {
                background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            .hero-bg {
                background-image: radial-gradient(circle at 0% 0%, rgba(79, 70, 229, 0.05) 0%, transparent 50%),
                                  radial-gradient(circle at 100% 100%, rgba(124, 58, 237, 0.05) 0%, transparent 50%);
            }
        </style>
    </head>
    <body class="antialiased bg-white text-slate-900 hero-bg min-h-screen">
        <!-- Navbar -->
        <nav class="fixed top-0 left-0 right-0 z-50 glass border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    @if(isset($global_setting) && $global_setting->logo)
                        <img src="{{ asset('storage/' . $global_setting->logo) }}" alt="Logo" class="w-10 h-10 object-contain bg-white rounded-xl shadow-sm border border-gray-100">
                    @else
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    @endif
                    <span class="text-xl font-extrabold tracking-tight truncate max-w-[150px] sm:max-w-xs" title="{{ $global_setting->nama_sekolah ?? 'SiSurvei' }}">
                        {{ $global_setting->nama_sekolah ?? 'SiSurvei' }}
                    </span>
                </div>

                <div class="flex items-center gap-6">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition">Log In</a>
                        <a href="{{ route('login') }}" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">Mulai Survei</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="pt-40 pb-20 px-6">
            <div class="max-w-5xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold mb-8 border border-indigo-100 uppercase tracking-widest">
                    🚀 Academic Survey Excellence
                </div>
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight leading-tight mb-8">
                    Bangun Masa Depan <br> 
                    <span class="gradient-text">Sekolah yang Lebih Baik</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-500 max-w-2xl mx-auto mb-12 leading-relaxed">
                    Platform survei kepuasan akademik terintegrasi dengan algoritma K-Means untuk evaluasi kualitas pendidikan yang lebih objektif dan terukur.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-20">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-10 py-5 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition shadow-xl shadow-slate-200 flex items-center justify-center gap-2 group">
                        Masuk ke Akun
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <div class="flex items-center gap-2 text-slate-400 font-medium">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        Keamanan Data Terjamin
                    </div>
                </div>

                <!-- Feature Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Analisis K-Means</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Pengelompokan hasil survei secara otomatis untuk melihat tingkat kepuasan di setiap area.</p>
                    </div>

                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Mudah & Cepat</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Interface yang bersih memudahkan siswa memberikan umpan balik tanpa hambatan teknis.</p>
                    </div>

                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300">
                        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2.472a2 2 0 001.838-1.236L22.162 12a2 2 0 00-1.838-2.764H19.04V6a2 2 0 00-2-2H7.6a2 2 0 00-2 2v4H3.776a2 2 0 00-1.838 2.764L3.69 15.764A2 2 0 005.528 17H8m9 0V7H8v10m9 0h-9"/></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Laporan PDF</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Ekspor hasil survei menjadi laporan profesional siap cetak untuk keperluan administrasi.</p>
                    </div>
                </div>
            </div>
        </main>

        <footer class="py-12 border-t border-slate-100 text-center">
            <p class="text-slate-400 text-sm">&copy; 2026 SiSurvei Academic Management System. All rights reserved.</p>
        </footer>
    </body>
</html>
