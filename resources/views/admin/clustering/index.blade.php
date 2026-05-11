<x-admin-layout>
    <x-slot name="title">Evaluasi Cerdas K-Means</x-slot>
    <x-slot name="header">Hasil Clustering & Evaluasi Cerdas</x-slot>

    <!-- Kuesioner Filter -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 mb-8 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-gradient-to-bl from-indigo-50 to-transparent rounded-bl-full opacity-50 pointer-events-none"></div>
        <div class="relative">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Pilih Periode Survei</h3>
            <p class="text-sm text-gray-500 mb-6">Pilih survei untuk diproses menggunakan algoritma K-Means Clustering.</p>
            
            <form method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <select name="kuesioner_id" class="w-full px-5 py-3 border border-gray-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 bg-gray-50 hover:bg-gray-100 transition cursor-pointer">
                        @foreach($kuesioners as $k)
                            <option value="{{ $k->id }}" {{ $selectedKuesionerId == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kuesioner }} ({{ ucfirst($k->status) }})
                            </option>
                        @endforeach
                        @if($kuesioners->isEmpty())
                            <option value="">Belum ada kuesioner</option>
                        @endif
                    </select>
                </div>
                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white text-sm font-bold rounded-2xl hover:bg-indigo-700 transition shadow-md hover:shadow-lg w-full sm:w-auto flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    Jalankan K-Means
                </button>
            </form>
        </div>
    </div>

    @if($error)
        <div class="bg-red-50 border border-red-100 rounded-3xl p-8 text-center shadow-sm">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm text-red-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Proses Clustering Gagal</h3>
            <p class="text-sm text-red-600 max-w-lg mx-auto">{{ $error }}</p>
        </div>
    @elseif($clusteringResult)
        
        <!-- Summary Cards -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4 tracking-tight flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Distribusi Kategori (Hasil K-Means: {{ $clusteringResult['iterations'] }} iterasi)
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $colors = [
                        'Sangat Baik' => 'emerald',
                        'Baik' => 'indigo',
                        'Cukup' => 'amber',
                        'Kurang' => 'red'
                    ];
                @endphp
                
                @foreach($clusteringResult['clusters'] as $label => $points)
                    @php $color = $colors[$label] ?? 'gray'; @endphp
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] hover:-translate-y-1 transition duration-300">
                        <div class="flex items-start justify-between mb-4">
                            <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-lg bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-100">
                                {{ $label }}
                            </span>
                        </div>
                        <p class="text-4xl font-black text-gray-900 tracking-tight">{{ count($points) }}</p>
                        <p class="text-xs font-medium text-gray-500 mt-2">Siswa masuk di cluster ini</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Centroid Matrix -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">Matriks Pusat Cluster (Centroid)</h3>
                <p class="text-sm text-gray-500 mt-1">Nilai rata-rata pusat setiap indikator pada masing-masing kategori hasil prediksi algoritma.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-white border-b border-gray-100">
                            <th class="text-left px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest bg-gray-50">Indikator Survei</th>
                            @foreach($clusteringResult['centroids'] as $label => $data)
                                <th class="text-center px-6 py-4 text-xs font-bold text-gray-800 uppercase tracking-widest">
                                    {{ $label }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($indikators as $i => $indikatorName)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 text-sm font-bold text-gray-700">{{ $indikatorName }}</td>
                                @foreach($clusteringResult['centroids'] as $label => $data)
                                    <td class="px-6 py-4 text-center">
                                        <div class="inline-flex items-center justify-center px-3 py-1 rounded-lg bg-gray-50 text-sm font-bold text-gray-800 border border-gray-100">
                                            {{ number_format($data['features'][$i] ?? 0, 2) }}
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 text-sm font-bold text-gray-800 uppercase tracking-wider">Skor Pusat Keseluruhan</td>
                            @foreach($clusteringResult['centroids'] as $label => $data)
                                <td class="px-6 py-4 text-center">
                                    <span class="text-lg font-black text-indigo-600">{{ number_format($data['overall_score'], 2) }}</span>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Detail Siswa per Cluster -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">Daftar Evaluasi Siswa</h3>
                <p class="text-sm text-gray-500 mt-1">Pemetaan responden ke dalam 4 kategori berdasarkan kedekatan jarak (Euclidean) ke centroid.</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-gray-100">
                @foreach($clusteringResult['clusters'] as $label => $points)
                    <div class="p-0">
                        <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-100">
                            <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center justify-between">
                                Kategori {{ $label }}
                                <span class="bg-white px-2 py-1 rounded-md text-xs border border-gray-200">{{ count($points) }} Siswa</span>
                            </h4>
                        </div>
                        <ul class="divide-y divide-gray-50 max-h-80 overflow-y-auto">
                            @forelse($points as $p)
                                <li class="px-6 py-3 hover:bg-gray-50 transition flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">{{ $p['user_name'] }}</p>
                                        <p class="text-[11px] text-gray-500 uppercase tracking-wider mt-0.5">Kelas: {{ $p['kelas'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs font-semibold text-gray-500">Skor Rata-rata</p>
                                        <p class="text-sm font-bold text-indigo-600">{{ count($p['features']) > 0 ? number_format(array_sum($p['features'])/count($p['features']), 2) : 0 }}</p>
                                    </div>
                                </li>
                            @empty
                                <li class="px-6 py-8 text-center text-sm text-gray-400 italic">Tidak ada siswa di kategori ini.</li>
                            @endforelse
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>

    @else
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-16 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2 tracking-tight">K-Means Belum Dijalankan</h3>
            <p class="text-sm text-gray-500 max-w-md mx-auto">Pilih kuesioner pada form di atas, lalu klik "Jalankan K-Means" untuk memulai proses clustering dan evaluasi otomatis data survei.</p>
        </div>
    @endif
</x-admin-layout>
