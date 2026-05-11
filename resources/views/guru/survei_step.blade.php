<x-app-layout>
    <div x-data="surveyWizard()" class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Progress Header -->
            <div class="mb-10">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-black text-gray-400 uppercase tracking-widest">
                        Step <span x-text="currentStep + 1"></span> of <span x-text="totalSteps"></span>:
                        <span class="text-indigo-600" x-text="steps[currentStep].name"></span>
                    </h2>
                    <span class="text-sm font-black text-indigo-600" x-text="progress + '%'"></span>
                </div>
                <div class="h-3 w-full bg-gray-200 rounded-full overflow-hidden shadow-inner">
                    <div class="h-full bg-indigo-600 transition-all duration-500 ease-out"
                        :style="'width: ' + progress + '%'"></div>
                </div>
            </div>

            <form action="{{ route('siswa.survei.store', $kuesioner) }}" method="POST" id="surveyForm">
                @csrf

                @foreach($pertanyaanGrouped as $indicator => $questions)
                    <div x-show="currentStep === {{ $loop->index }}" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-8"
                        x-transition:enter-end="opacity-100 transform translate-x-0" class="space-y-6">

                        <div class="mb-8">
                            <h3 class="text-3xl font-black text-gray-900 tracking-tight">{{ $indicator ?: 'Umum' }}</h3>
                            <p class="text-gray-500 mt-1">Berikan penilaian Anda untuk indikator ini.</p>
                        </div>

                        @foreach($questions as $index => $item)
                            <div
                                class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 transition-all hover:shadow-md">
                                <div class="flex items-start gap-4 mb-6">
                                    <span
                                        class="flex-shrink-0 w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold text-sm border border-indigo-100">
                                        {{ $loop->parent->index + 1 }}.{{ $loop->index + 1 }}
                                    </span>
                                    <h3 class="text-lg font-bold text-gray-800 leading-relaxed">{{ $item->teks_pertanyaan }}
                                    </h3>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                                    @php
                                        $options = [
                                            1 => ['label' => 'Sangat Tidak Setuju', 'color' => 'bg-red-50 text-red-700'],
                                            2 => ['label' => 'Tidak Setuju', 'color' => 'bg-orange-50 text-orange-700'],
                                            3 => ['label' => 'Netral', 'color' => 'bg-amber-50 text-amber-700'],
                                            4 => ['label' => 'Setuju', 'color' => 'bg-emerald-50 text-emerald-700'],
                                            5 => ['label' => 'Sangat Setuju', 'color' => 'bg-blue-50 text-blue-700'],
                                        ];
                                    @endphp

                                    @foreach($options as $val => $opt)
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="jawaban[{{ $item->id }}]" value="{{ $val }}" required
                                                @change="markAnswered({{ $item->id }})" class="peer sr-only">
                                            <div
                                                class="h-full flex flex-col items-center justify-center p-4 rounded-2xl border-2 border-transparent {{ $opt['color'] }} text-center transition-all peer-checked:border-indigo-600 peer-checked:bg-indigo-600 peer-checked:text-white group-hover:scale-[1.02]">
                                                <span class="text-xl font-black mb-1">{{ $val }}</span>
                                                <span
                                                    class="text-[9px] font-black leading-tight uppercase tracking-tighter">{{ $opt['label'] }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach

                <!-- Navigation Buttons -->
                <div class="mt-12 flex flex-col sm:flex-row items-center justify-between gap-4 pb-20">
                    <button type="button" x-show="currentStep > 0" @click="prevStep()"
                        class="w-full sm:w-auto px-10 py-5 bg-white text-gray-600 font-bold rounded-2xl border border-gray-200 hover:bg-gray-50 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Sebelumnya
                    </button>

                    <div class="flex-1"></div>

                    <button type="button" x-show="currentStep < totalSteps - 1" @click="nextStep()"
                        class="w-full sm:w-auto px-12 py-5 bg-indigo-600 text-white font-black rounded-2xl hover:bg-indigo-700 transition shadow-xl shadow-indigo-100 flex items-center justify-center gap-2">
                        Selanjutnya
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <button type="submit" x-show="currentStep === totalSteps - 1"
                        class="w-full sm:w-auto px-12 py-5 bg-emerald-600 text-white font-black rounded-2xl hover:bg-emerald-700 transition shadow-xl shadow-emerald-100 flex items-center justify-center gap-2">
                        Kirim Semua Jawaban
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function surveyWizard() {
            return {
                currentStep: 0,
                totalSteps: {{ $pertanyaanGrouped->count() }},
                answeredQuestions: new Set(),
                totalQuestions: {{ $kuesioner->pertanyaan()->count() }},
                steps: [
                    @foreach($pertanyaanGrouped as $indicator => $questions)
                        { name: "{{ $indicator ?: 'Umum' }}", count: {{ $questions->count() }} },
                    @endforeach
                ],
                get progress() {
                    return Math.round((this.answeredQuestions.size / this.totalQuestions) * 100);
                },
                markAnswered(id) {
                    this.answeredQuestions.add(id);
                },
                nextStep() {
                    if (this.currentStep < this.totalSteps - 1) {
                        this.currentStep++;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },
                prevStep() {
                    if (this.currentStep > 0) {
                        this.currentStep--;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                }
            }
        }
    </script>
</x-app-layout>