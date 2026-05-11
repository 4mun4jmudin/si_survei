<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SiSurvei') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .glass-nav {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }
        </style>
    </head>
    <body class="antialiased bg-gray-50 text-slate-900">
        <div class="min-h-screen flex flex-col">
            <!-- Navbar -->
            <nav class="sticky top-0 z-40 glass-nav border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-20">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                                @if(isset($global_setting) && $global_setting->logo)
                                    <img src="{{ asset('storage/' . $global_setting->logo) }}" alt="Logo" class="w-9 h-9 object-contain bg-white rounded-lg shadow-sm border border-gray-100">
                                @else
                                    <div class="w-9 h-9 bg-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-indigo-100">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                @endif
                                <span class="text-lg font-black tracking-tight truncate max-w-[200px] sm:max-w-xs" title="{{ $global_setting->nama_sekolah ?? 'SiSurvei' }}">
                                    {{ $global_setting->nama_sekolah ?? 'SiSurvei' }}
                                </span>
                            </a>
                        </div>

                        <!-- User Dropdown -->
                        <div class="flex items-center">
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-3 px-4 py-2 bg-white border border-gray-100 rounded-2xl hover:bg-gray-50 transition shadow-sm">
                                    <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 font-bold text-xs">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-bold text-gray-700 hidden sm:block">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" x-transition.opacity class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 overflow-hidden">
                                    <div class="px-4 py-3 border-b border-gray-50 mb-1">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ ucfirst(Auth::user()->role) }} Account</p>
                                    </div>
                                    <a href="{{ Auth::user()->role == 'guru' ? route('guru.profil') : route('siswa.profil') }}" class="block px-4 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
                                        Profil Saya
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 transition">
                                            Keluar Aplikasi
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>

            <!-- Simple Footer -->
            <footer class="py-10 bg-white border-t border-gray-100 text-center">
                <p class="text-xs text-gray-400 font-medium">&copy; 2026 SiSurvei Academic &middot; Dibuat untuk evaluasi sekolah</p>
            </footer>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed bottom-10 right-10 z-50 bg-emerald-600 text-white px-8 py-4 rounded-3xl shadow-2xl flex items-center gap-3 animate-bounce">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed bottom-10 right-10 z-50 bg-red-600 text-white px-8 py-4 rounded-3xl shadow-2xl flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-bold">{{ session('error') }}</span>
            </div>
        @endif
    </body>
</html>
