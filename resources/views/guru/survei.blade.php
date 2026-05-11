<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Progress Bar & Header -->
            <div class="mb-8 text-center">
                <a href="{{ route('siswa.dashboard') }}" class="inline-flex items-center text-sm font-semibold text-gray-500 hover:text-indigo-600 mb-6 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Dashboard
                </a>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">{{ $kuesioner->nama_kuesioner }}</h1>
                <p class="text-gray-500 mt-2">Silakan pilih jawaban yang paling sesuai menurut pendapat Anda.</p>
            </div>

            <form action="{{ route('siswa.survei.store', $kuesioner) }}" method="POST" id="surveyForm" class="space-y-6">
                @csrf
                
                @foreach($pertanyaan as $index => $item)
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 transition-all hover:shadow-md">
                        <div class="flex items-start gap-4 mb-6">
                            <span class="flex-shrink-0 w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold text-sm border border-indigo-100">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-[10px] font-bold uppercase tracking-widest rounded mb-2 inline-block">{{ $item->indikator ?: 'Umum' }}</span>
                                <h3 class="text-lg font-bold text-gray-800 leading-relaxed">{{ $item->teks_pertanyaan }}</h3>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                            @php
                                $options = [
                                    1 => ['label' => 'Sangat Tidak Setuju', 'color' => 'bg-red-50 text-red-700 border-red-100 peer-checked:bg-red-600'],
                                    2 => ['label' => 'Tidak Setuju', 'color' => 'bg-orange-50 text-orange-700 border-orange-100 peer-checked:bg-orange-500'],
                                    3 => ['label' => 'Ragu-ragu / Netral', 'color' => 'bg-amber-50 text-amber-700 border-amber-100 peer-checked:bg-amber-500'],
                                    4 => ['label' => 'Setuju', 'color' => 'bg-emerald-50 text-emerald-700 border-emerald-100 peer-checked:bg-emerald-500'],
                                    5 => ['label' => 'Sangat Setuju', 'color' => 'bg-blue-50 text-blue-700 border-blue-100 peer-checked:bg-blue-600'],
                                ];
                            @endphp

                            @foreach($options as $val => $opt)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="jawaban[{{ $item->id }}]" value="{{ $val }}" required class="peer sr-only">
                                    <div class="h-full flex flex-col items-center justify-center p-4 rounded-2xl border-2 border-transparent {{ explode(' ', $opt['color'])[0] }} {{ explode(' ', $opt['color'])[1] }} text-center transition-all peer-checked:border-indigo-600 peer-checked:bg-indigo-600 peer-checked:text-white hover:scale-[1.02]">
                                        <span class="text-xl font-black mb-1">{{ $val }}</span>
                                        <span class="text-[10px] font-bold leading-tight uppercase">{{ $opt['label'] }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <!-- Submit Button -->
                <div class="pt-10 pb-20">
                    <button type="submit" class="w-full flex items-center justify-center gap-3 px-10 py-6 bg-indigo-600 text-white text-lg font-black rounded-3xl hover:bg-indigo-700 transition shadow-xl shadow-indigo-100 hover:shadow-indigo-200 hover:-translate-y-1">
                        Kirim Jawaban
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </button>
                    <p class="text-center text-gray-400 text-sm mt-4">Pastikan semua pertanyaan telah dijawab sebelum mengirim.</p>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Custom scroll behavior for smooth focus */
        html { scroll-behavior: smooth; }
    </style>
</x-app-layout>
