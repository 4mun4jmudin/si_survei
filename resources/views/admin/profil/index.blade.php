<x-admin-layout>
    <x-slot name="title">Profil Saya</x-slot>
    <x-slot name="header">Pengaturan Profil</x-slot>

    <div class="max-w-4xl mx-auto space-y-8">

        <!-- Profil Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="relative h-32 bg-gradient-to-r from-indigo-600 to-indigo-500">
                <div class="absolute -bottom-10 left-8">
                    <div class="w-20 h-20 bg-white rounded-2xl shadow-lg flex items-center justify-center border-4 border-white">
                        <span class="text-3xl font-black text-indigo-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                </div>
            </div>
            <div class="pt-14 px-8 pb-8">
                <h3 class="text-xl font-bold text-gray-900 tracking-tight">{{ $user->name }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ $user->email }} &middot; <span class="font-semibold text-indigo-600 uppercase text-xs tracking-wider">{{ $user->role }}</span></p>
                <p class="text-xs text-gray-400 mt-2">Bergabung sejak {{ $user->created_at->format('d F Y') }}</p>
            </div>
        </div>

        <!-- Edit Profil -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800 tracking-tight">Informasi Akun</h3>
                <p class="text-sm text-gray-500 mt-1">Perbarui nama, username, dan email Anda.</p>
            </div>
            <form action="{{ route('admin.profil.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('username') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    @error('email') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white text-sm font-bold rounded-2xl hover:bg-indigo-700 transition shadow-md hover:shadow-lg">Simpan Perubahan</button>
                </div>
            </form>
        </div>

        <!-- Ubah Password -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800 tracking-tight">Ubah Password</h3>
                <p class="text-sm text-gray-500 mt-1">Pastikan Anda menggunakan password yang kuat dan unik.</p>
            </div>
            <form action="{{ route('admin.profil.password') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Password Lama</label>
                    <input type="password" name="current_password" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    @error('current_password') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Password Baru</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @error('password') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>
                </div>
                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit" class="px-6 py-3 bg-red-600 text-white text-sm font-bold rounded-2xl hover:bg-red-700 transition shadow-md hover:shadow-lg">Ubah Password</button>
                </div>
            </form>
        </div>

    </div>
</x-admin-layout>
