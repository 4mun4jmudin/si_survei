<x-admin-layout>
    <x-slot name="title">Hasil Analisis Survei</x-slot>
    <x-slot name="header">Hasil Analisis Survei</x-slot>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label for="kuesioner_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Periode Survei / Kuesioner</label>
                <select name="kuesioner_id" id="kuesioner_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                    @foreach($kuesioners as $k)
                        <option value="{{ $k->id }}" {{ $selectedKuesionerId == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kuesioner }} ({{ ucfirst($k->status) }})
                        </option>
                    @endforeach
                    @if($kuesioners->isEmpty())
                        <option value="">Belum ada kuesioner aktif / selesai</option>
                    @endif
                </select>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition w-full sm:w-auto h-[42px] flex items-center justify-center">
                Analisis Data
            </button>
        </form>
    </div>

    @if($kuesioner)
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Ringkasan Statistik</h3>
                <p class="text-sm text-gray-500">Menampilkan hasil dari kuesioner terpilih.</p>
            </div>
            <a href="{{ route('admin.hasil-survei.export', $kuesioner->id) }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export Excel (Mentah)
            </a>
        </div>

        <!-- Ringkasan Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50"></div>
                <div class="relative">
                    <p class="text-sm font-medium text-gray-500">Total Responden</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <p class="text-4xl font-bold text-gray-900">{{ $statistik['total_responden'] }}</p>
                        <p class="text-sm text-gray-500">Siswa</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full opacity-50"></div>
                <div class="relative">
                    <p class="text-sm font-medium text-gray-500">Total Pertanyaan</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <p class="text-4xl font-bold text-gray-900">{{ $kuesioner->pertanyaan->count() }}</p>
                        <p class="text-sm text-gray-500">Soal</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full opacity-50"></div>
                <div class="relative">
                    <p class="text-sm font-medium text-gray-500">Rata-rata Skor Keseluruhan</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <p class="text-4xl font-bold text-gray-900">{{ $statistik['rata_rata_skor'] }}</p>
                        <p class="text-sm text-gray-500">dari skala 5.0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Per Indikator -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Evaluasi Berdasarkan Indikator</h3>
                <p class="text-sm text-gray-500">Rata-rata nilai untuk setiap aspek yang diukur dalam survei.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aspek / Indikator</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Data</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rata-rata Skor</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori Penilaian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($indikatorStats as $stat)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $stat['indikator'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $stat['total'] }} jawaban</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-bold text-gray-800 w-8">{{ number_format($stat['rata_rata'], 2) }}</span>
                                        <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden max-w-[120px]">
                                            <div class="h-full rounded-full {{ $stat['rata_rata'] >= 4 ? 'bg-emerald-500' : ($stat['rata_rata'] >= 3 ? 'bg-indigo-500' : 'bg-amber-500') }}" style="width: {{ ($stat['rata_rata'] / 5) * 100 }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg 
                                        {{ $stat['kategori'] == 'Sangat Baik' ? 'bg-emerald-100 text-emerald-700' : 
                                        ($stat['kategori'] == 'Baik' ? 'bg-indigo-100 text-indigo-700' : 
                                        ($stat['kategori'] == 'Cukup' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700')) }}">
                                        {{ $stat['kategori'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">Belum ada data indikator atau jawaban numerik (Skala Likert/Pilihan Ganda) untuk kuesioner ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <h3 class="text-lg font-semibold text-gray-800 mb-1">Pilih Kuesioner</h3>
            <p class="text-sm text-gray-500">Pilih kuesioner dari dropdown di atas untuk melihat hasil analisis statistiknya.</p>
        </div>
    @endif
</x-admin-layout>
