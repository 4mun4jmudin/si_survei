<x-admin-layout>
    <x-slot name="title">Buat Arsip Laporan Baru</x-slot>
    <x-slot name="header">Buat Arsip Laporan Akhir</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-3xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] border border-gray-100 p-8">
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 tracking-tight">Detail Laporan</h3>
                <p class="text-sm text-gray-500 mt-1">Buat arsip laporan hasil survei berdasarkan periode kuesioner yang sudah selesai.</p>
            </div>

            <form action="{{ route('admin.laporan.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="kuesioner_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Periode Kuesioner <span class="text-red-500">*</span></label>
                    <select name="kuesioner_id" id="kuesioner_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        <option value="">-- Pilih Kuesioner --</option>
                        @foreach($kuesioners as $k)
                            <option value="{{ $k->id }}" {{ old('kuesioner_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kuesioner }} ({{ ucfirst($k->status) }})
                            </option>
                        @endforeach
                    </select>
                    @error('kuesioner_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="judul_laporan" class="block text-sm font-bold text-gray-700 mb-2">Judul Laporan <span class="text-red-500">*</span></label>
                    <input type="text" name="judul_laporan" id="judul_laporan" value="{{ old('judul_laporan') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition" placeholder="Contoh: Laporan Evaluasi Kepuasan Siswa Semester Ganjil 2026">
                    @error('judul_laporan') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="ringkasan" class="block text-sm font-bold text-gray-700 mb-2">Kesimpulan / Rekomendasi (Opsional)</label>
                    <textarea name="ringkasan" id="ringkasan" rows="5" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition" placeholder="Tuliskan interpretasi singkat, rekomendasi perbaikan, atau tindak lanjut dari hasil survei ini...">{{ old('ringkasan') }}</textarea>
                    @error('ringkasan') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                    <a href="{{ route('admin.laporan.index') }}" class="px-6 py-3 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-2xl transition">Batal</a>
                    <button type="submit" class="px-6 py-3 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-2xl shadow-md hover:shadow-lg transition">Simpan Laporan</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
