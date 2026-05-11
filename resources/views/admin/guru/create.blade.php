<x-admin-layout>
    <x-slot name="title">Tambah Data Guru</x-slot>
    <x-slot name="header">Tambah Guru Baru</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Informasi Guru</h3>
                <p class="text-sm text-gray-500">Isi detail data guru atau staf pengajar.</p>
            </div>

            <form action="{{ route('admin.guru.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Tautkan Akun -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Tautkan ke Akun Login <span class="text-red-500">*</span></label>
                    <select name="user_id" id="user_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition @error('user_id') border-red-500 @enderror">
                        <option value="">-- Pilih Akun Guru --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->username }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1.5 text-xs text-gray-500">Hanya menampilkan akun dengan role 'guru' yang belum ditautkan.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- NIP -->
                    <div>
                        <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">NIP / ID Pegawai <span class="text-red-500">*</span></label>
                        <input type="text" name="nip" id="nip" value="{{ old('nip') }}" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition @error('nip') border-red-500 @enderror" placeholder="Contoh: 198001012005011001">
                        @error('nip')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap (beserta gelar) <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition @error('nama_lengkap') border-red-500 @enderror" placeholder="Contoh: Budi Santoso, S.Pd.">
                        @error('nama_lengkap')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Mata Pelajaran -->
                <div>
                    <label for="mapel" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran / Posisi <span class="text-red-500">*</span></label>
                    <input type="text" name="mapel" id="mapel" value="{{ old('mapel') }}" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition @error('mapel') border-red-500 @enderror" placeholder="Contoh: Matematika, Kepala Sekolah, Guru BK">
                    @error('mapel')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 mt-8">
                    <a href="{{ route('admin.guru.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
