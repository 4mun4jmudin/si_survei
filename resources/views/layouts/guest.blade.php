<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:flex-row bg-gray-50">
            <!-- Left Side: Image / Branding -->
            <div class="hidden sm:flex sm:w-1/2 bg-indigo-900 items-center justify-center relative overflow-hidden">
                <div class="absolute inset-0 bg-cover bg-center opacity-50 mix-blend-multiply" style="background-image: url('/images/login_bg.png');"></div>
                <div class="relative z-10 p-12 text-center text-white">
                    <h1 class="text-4xl font-extrabold tracking-tight mb-4">
                        {{ isset($global_setting) ? $global_setting->nama_sekolah : 'Sistem Survei Lingkungan Belajar' }}
                    </h1>
                    <p class="text-lg text-indigo-100 font-medium max-w-md mx-auto">
                        Mendukung Efektivitas Pembelajaran Siswa melalui Analitik dan Evaluasi Berbasis Collaborative Filtering.
                    </p>
                </div>
                <!-- Decorative gradients -->
                <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-indigo-500/20 to-purple-600/40"></div>
            </div>

            <!-- Right Side: Form -->
            <div class="w-full sm:w-1/2 flex items-center justify-center px-6 py-12 sm:px-12 lg:px-24">
                <div class="w-full max-w-md space-y-8">
                    <!-- Mobile Logo (Visible only on small screens) -->
                    <div class="sm:hidden text-center mb-8">
                        @if(isset($global_setting) && $global_setting->logo)
                            <img src="{{ asset('storage/' . $global_setting->logo) }}" alt="Logo" class="w-16 h-16 object-contain mx-auto mb-4 bg-white rounded-xl shadow-sm border border-gray-100">
                        @endif
                        <h2 class="text-3xl font-bold text-gray-900">{{ isset($global_setting) ? $global_setting->nama_sekolah : 'Survei Belajar' }}</h2>
                    </div>

                    <div class="bg-white px-8 py-10 shadow-xl rounded-2xl border border-gray-100">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
