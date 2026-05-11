<x-admin-layout>
    <x-slot name="title">Pengaturan Sistem</x-slot>
    <x-slot name="header">Konfigurasi Global</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Identitas Sekolah</h2>
                        <p class="text-sm text-gray-500 mt-1">Ubah nama sekolah, logo, dan tahun ajaran yang akan ditampilkan ke seluruh pengguna.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf

                <div class="space-y-6">
                    <!-- Logo Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Logo Sekolah</label>
                        <div class="flex items-center gap-6">
                            <div class="w-24 h-24 rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center bg-gray-50 overflow-hidden relative group">
                                @if($setting->logo)
                                    <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" class="w-full h-full object-contain p-2">
                                @else
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @endif
                                
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                    <span class="text-white text-xs font-bold">Ubah</span>
                                </div>
                                <input type="file" name="logo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                            </div>
                            <div class="text-sm text-gray-500">
                                <p class="font-medium text-gray-700 mb-1">Upload logo baru</p>
                                <p>Format: PNG, JPG, atau SVG.</p>
                                <p>Ukuran maksimal: 2MB.</p>
                                <p>Rasio yang disarankan: 1:1 (Kotak).</p>
                            </div>
                        </div>
                        @error('logo') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nama Sekolah -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Sekolah / Instansi</label>
                        <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $setting->nama_sekolah) }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        @error('nama_sekolah') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tahun Ajaran -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Ajaran Aktif</label>
                            <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', $setting->tahun_ajaran) }}" placeholder="Contoh: 2025/2026" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            @error('tahun_ajaran') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Semester -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Semester Aktif</label>
                            <select name="semester" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <option value="Ganjil" {{ old('semester', $setting->semester) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="Genap" {{ old('semester', $setting->semester) == 'Genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                            @error('semester') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
