<x-admin-layout>
    <x-slot name="title">Edit Pertanyaan</x-slot>
    <x-slot name="header">Edit Pertanyaan Survei</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Detail Pertanyaan</h3>
                <p class="text-sm text-gray-500">Perbarui isi atau pengaturan pertanyaan ini.</p>
            </div>

            <form action="{{ route('admin.pertanyaan.update', $pertanyaan) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Pilih Kuesioner -->
                <div>
                    <label for="kuesioner_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Kuesioner <span class="text-red-500">*</span></label>
                    <select name="kuesioner_id" id="kuesioner_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition @error('kuesioner_id') border-red-500 @enderror">
                        <option value="">-- Pilih Kuesioner --</option>
                        @foreach($kuesioners as $k)
                            <option value="{{ $k->id }}" {{ old('kuesioner_id', $pertanyaan->kuesioner_id) == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kuesioner }} ({{ ucfirst($k->status) }})
                            </option>
                        @endforeach
                    </select>
                    @error('kuesioner_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Indikator -->
                    <div>
                        <label for="indikator" class="block text-sm font-medium text-gray-700 mb-2">Indikator Penilaian</label>
                        <input type="text" name="indikator" id="indikator" value="{{ old('indikator', $pertanyaan->indikator) }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition @error('indikator') border-red-500 @enderror" placeholder="Contoh: Kenyamanan, Fasilitas, dll">
                        @error('indikator')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Urutan -->
                    <div>
                        <label for="nomor_urutan" class="block text-sm font-medium text-gray-700 mb-2">Nomor Urutan <span class="text-red-500">*</span></label>
                        <input type="number" name="nomor_urutan" id="nomor_urutan" value="{{ old('nomor_urutan', $pertanyaan->nomor_urutan) }}" required min="1" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition @error('nomor_urutan') border-red-500 @enderror" placeholder="Contoh: 1">
                        @error('nomor_urutan')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Isi Pertanyaan -->
                <div>
                    <label for="isi_pertanyaan" class="block text-sm font-medium text-gray-700 mb-2">Isi Pertanyaan <span class="text-red-500">*</span></label>
                    <textarea name="isi_pertanyaan" id="isi_pertanyaan" rows="3" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition @error('isi_pertanyaan') border-red-500 @enderror" placeholder="Tuliskan pertanyaan secara jelas...">{{ old('isi_pertanyaan', $pertanyaan->isi_pertanyaan) }}</textarea>
                    @error('isi_pertanyaan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Tipe Jawaban -->
                    <div>
                        <label for="tipe_jawaban" class="block text-sm font-medium text-gray-700 mb-2">Tipe Jawaban <span class="text-red-500">*</span></label>
                        <select name="tipe_jawaban" id="tipe_jawaban" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition @error('tipe_jawaban') border-red-500 @enderror">
                            <option value="pilihan_ganda" {{ old('tipe_jawaban', $pertanyaan->tipe_jawaban) == 'pilihan_ganda' ? 'selected' : '' }}>Pilihan Ganda</option>
                            <option value="skala_likert" {{ old('tipe_jawaban', $pertanyaan->tipe_jawaban) == 'skala_likert' ? 'selected' : '' }}>Skala Likert (1-5)</option>
                            <option value="esai" {{ old('tipe_jawaban', $pertanyaan->tipe_jawaban) == 'esai' ? 'selected' : '' }}>Jawaban Esai / Teks</option>
                        </select>
                        @error('tipe_jawaban')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bobot -->
                    <div>
                        <label for="bobot" class="block text-sm font-medium text-gray-700 mb-2">Bobot Nilai <span class="text-red-500">*</span></label>
                        <input type="number" step="0.1" name="bobot" id="bobot" value="{{ old('bobot', $pertanyaan->bobot) }}" required min="0" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition @error('bobot') border-red-500 @enderror" placeholder="Contoh: 1.0">
                        @error('bobot')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 mt-8">
                    <a href="{{ route('admin.pertanyaan.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
