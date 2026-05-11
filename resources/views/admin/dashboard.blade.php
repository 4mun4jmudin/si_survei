<x-admin-layout>
    <x-slot name="title">Dashboard Utama</x-slot>
    <x-slot name="header">Ringkasan Sistem</x-slot>

    <!-- Welcome Message -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Selamat Datang, {{ Auth::user()->name }} 👋</h2>
        <p class="text-sm text-gray-500 mt-1">Berikut adalah ringkasan data dan aktivitas terbaru di sistem survei.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Total Pengguna -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] hover:-translate-y-1 hover:shadow-lg transition duration-300 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-full opacity-50 group-hover:scale-150 transition duration-500"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 mb-1">Total Pengguna</p>
                    <p class="text-4xl font-bold text-gray-900 tracking-tight">{{ $totalUsers }}</p>
                </div>
                <div class="w-14 h-14 bg-white border-2 border-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
            <p class="text-xs font-medium text-gray-400 mt-4 relative">Seluruh civitas akademik</p>
        </div>

        <!-- Kuesioner Aktif -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] hover:-translate-y-1 hover:shadow-lg transition duration-300 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-full opacity-50 group-hover:scale-150 transition duration-500"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 mb-1">Survei Aktif</p>
                    <p class="text-4xl font-bold text-gray-900 tracking-tight">{{ $kuesionerAktif }}</p>
                </div>
                <div class="w-14 h-14 bg-white border-2 border-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
            </div>
            <p class="text-xs font-medium text-gray-400 mt-4 relative">Periode yang sedang berjalan</p>
        </div>

        <!-- Total Pertanyaan -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] hover:-translate-y-1 hover:shadow-lg transition duration-300 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-amber-50 to-amber-100 rounded-full opacity-50 group-hover:scale-150 transition duration-500"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 mb-1">Bank Soal</p>
                    <p class="text-4xl font-bold text-gray-900 tracking-tight">{{ $totalPertanyaan }}</p>
                </div>
                <div class="w-14 h-14 bg-white border-2 border-amber-50 rounded-2xl flex items-center justify-center text-amber-600 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-xs font-medium text-gray-400 mt-4 relative">Total item pertanyaan survei</p>
        </div>

        <!-- Total Respon -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] hover:-translate-y-1 hover:shadow-lg transition duration-300 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-gradient-to-br from-sky-50 to-sky-100 rounded-full opacity-50 group-hover:scale-150 transition duration-500"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 mb-1">Data Responden</p>
                    <p class="text-4xl font-bold text-gray-900 tracking-tight">{{ $totalRespon }}</p>
                </div>
                <div class="w-14 h-14 bg-white border-2 border-sky-50 rounded-2xl flex items-center justify-center text-sky-600 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-xs font-medium text-gray-400 mt-4 relative">Siswa yang sudah berpartisipasi</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-10">
        <!-- Chart: Rata-rata Skor per Indikator -->
        <div class="xl:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-gray-800 tracking-tight">Rata-rata Skor per Indikator</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Kuesioner aktif terbaru</p>
                </div>
                <span class="px-3 py-1 text-[11px] font-bold text-indigo-700 bg-indigo-50 rounded-lg border border-indigo-100 uppercase tracking-wider">Radar Chart</span>
            </div>
            <div style="height: 320px;">
                <canvas id="chartIndikator"></canvas>
            </div>
        </div>

        <!-- Chart: Distribusi Role User -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <div class="mb-6">
                <h3 class="text-base font-bold text-gray-800 tracking-tight">Distribusi Pengguna</h3>
                <p class="text-xs text-gray-500 mt-0.5">Berdasarkan role</p>
            </div>
            <div class="flex items-center justify-center" style="height: 200px;">
                <canvas id="chartRole"></canvas>
            </div>
            <div class="flex items-center justify-center gap-6 mt-4">
                @foreach($roleDistribusi as $role => $count)
                <div class="text-center">
                    <p class="text-lg font-bold text-gray-900">{{ $count }}</p>
                    <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">{{ ucfirst($role) }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Data -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <!-- Quick Actions -->
        <div>
            <h3 class="text-base font-bold text-gray-800 mb-4 tracking-tight flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Akses Cepat
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('admin.kuesioner.create') }}" class="group bg-white p-5 rounded-2xl border border-gray-100 hover:border-indigo-200 hover:shadow-md transition-all duration-200 flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 group-hover:scale-110 transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">Buat Survei Baru</p>
                        <p class="text-xs text-gray-500 mt-0.5">Buka periode kuesioner</p>
                    </div>
                </a>
                <a href="{{ route('admin.pertanyaan.create') }}" class="group bg-white p-5 rounded-2xl border border-gray-100 hover:border-emerald-200 hover:shadow-md transition-all duration-200 flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">Tambah Soal</p>
                        <p class="text-xs text-gray-500 mt-0.5">Edit bank pertanyaan</p>
                    </div>
                </a>
                <a href="{{ route('admin.hasil-survei.index') }}" class="group bg-white p-5 rounded-2xl border border-gray-100 hover:border-amber-200 hover:shadow-md transition-all duration-200 flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 group-hover:scale-110 transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">Analisis Hasil</p>
                        <p class="text-xs text-gray-500 mt-0.5">Lihat perhitungan skor</p>
                    </div>
                </a>
                <a href="{{ route('admin.clustering.index') }}" class="group bg-white p-5 rounded-2xl border border-gray-100 hover:border-sky-200 hover:shadow-md transition-all duration-200 flex items-center gap-4">
                    <div class="w-12 h-12 bg-sky-50 rounded-xl flex items-center justify-center text-sky-600 group-hover:scale-110 transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">K-Means Clustering</p>
                        <p class="text-xs text-gray-500 mt-0.5">Evaluasi otomatis</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Kuesioner -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-800 tracking-tight flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Kuesioner Terbaru
                </h3>
                <a href="{{ route('admin.kuesioner.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">Lihat Semua &rarr;</a>
            </div>
            <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] overflow-hidden">
                @if($kuesionerTerbaru->count() > 0)
                    <div class="divide-y divide-gray-50">
                        @foreach($kuesionerTerbaru as $kuesioner)
                            <div class="p-5 hover:bg-gray-50/80 transition flex items-center justify-between group">
                                <div>
                                    <p class="text-sm font-bold text-gray-800 group-hover:text-indigo-600 transition">{{ $kuesioner->nama_kuesioner }}</p>
                                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ \Carbon\Carbon::parse($kuesioner->periode_mulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($kuesioner->periode_selesai)->format('d M Y') }}
                                    </p>
                                </div>
                                <span class="px-3 py-1 text-[11px] font-bold uppercase tracking-wider rounded-lg border 
                                    {{ $kuesioner->status === 'aktif' ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 
                                    ($kuesioner->status === 'draft' ? 'bg-gray-50 border-gray-200 text-gray-600' : 'bg-amber-50 border-amber-100 text-amber-700') }}">
                                    {{ $kuesioner->status }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-10 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-600">Belum ada kuesioner</p>
                        <p class="text-xs text-gray-400 mt-1">Buat kuesioner baru untuk memulai.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart: Rata-rata Skor per Indikator (Radar Chart Premium)
            const ctxInd = document.getElementById('chartIndikator').getContext('2d');
            
            // Gradient fill
            const gradientFill = ctxInd.createLinearGradient(0, 0, 0, 400);
            gradientFill.addColorStop(0, 'rgba(99, 102, 241, 0.25)');
            gradientFill.addColorStop(1, 'rgba(99, 102, 241, 0.02)');

            new Chart(ctxInd, {
                type: 'radar',
                data: {
                    labels: @json($chartIndikator),
                    datasets: [{
                        label: 'Skor Aktual',
                        data: @json($chartSkor),
                        backgroundColor: gradientFill,
                        borderColor: 'rgb(99, 102, 241)',
                        borderWidth: 2.5,
                        pointBackgroundColor: 'rgb(99, 102, 241)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 9,
                        pointHoverBackgroundColor: 'rgb(79, 70, 229)',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3,
                        fill: true,
                        tension: 0.1,
                    },
                    {
                        label: 'Skor Ideal (5.0)',
                        data: @json(array_fill(0, count($chartIndikator), 5)),
                        backgroundColor: 'rgba(226, 232, 240, 0.08)',
                        borderColor: 'rgba(148, 163, 184, 0.3)',
                        borderWidth: 1.5,
                        borderDash: [6, 4],
                        pointBackgroundColor: 'rgba(148, 163, 184, 0.4)',
                        pointBorderColor: 'transparent',
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                font: { family: 'Inter', size: 11, weight: '600' },
                                color: '#6b7280',
                                padding: 20,
                                usePointStyle: true,
                                pointStyleWidth: 10,
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { family: 'Inter', size: 13, weight: '700' },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 14,
                            cornerRadius: 12,
                            displayColors: true,
                            callbacks: {
                                label: function(ctx) {
                                    return ' ' + ctx.dataset.label + ': ' + ctx.parsed.r.toFixed(2) + ' / 5.00';
                                }
                            }
                        }
                    },
                    scales: {
                        r: {
                            min: 0,
                            max: 5,
                            ticks: {
                                stepSize: 1,
                                font: { family: 'Inter', size: 10, weight: '500' },
                                color: '#9ca3af',
                                backdropColor: 'transparent',
                                showLabelBackdrop: false,
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.04)',
                                lineWidth: 1,
                            },
                            angleLines: {
                                color: 'rgba(0, 0, 0, 0.06)',
                                lineWidth: 1,
                            },
                            pointLabels: {
                                font: { family: 'Inter', size: 12, weight: '700' },
                                color: '#374151',
                                padding: 16,
                            }
                        }
                    },
                    elements: {
                        line: { borderJoinStyle: 'round' }
                    }
                }
            });

            // Chart: Distribusi Role (Doughnut)
            const ctxRole = document.getElementById('chartRole').getContext('2d');
            new Chart(ctxRole, {
                type: 'doughnut',
                data: {
                    labels: @json(array_map('ucfirst', array_keys($roleDistribusi))),
                    datasets: [{
                        data: @json(array_values($roleDistribusi)),
                        backgroundColor: ['rgba(239, 68, 68, 0.8)', 'rgba(245, 158, 11, 0.8)', 'rgba(16, 185, 129, 0.8)'],
                        borderColor: '#fff',
                        borderWidth: 4,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { family: 'Inter', weight: '600' },
                            bodyFont: { family: 'Inter' },
                            padding: 12,
                            cornerRadius: 10,
                        }
                    }
                }
            });
        });
    </script>
</x-admin-layout>
