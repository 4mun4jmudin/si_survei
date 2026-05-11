<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Profil & Keamanan</h1>
                <p class="text-gray-500 mt-2">Kelola informasi akun dan amankan akses Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Data Akun -->
                <div class="md:col-span-1">
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 text-center">
                        <div class="w-24 h-24 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 font-black text-3xl mx-auto mb-6 shadow-lg shadow-indigo-50 border-4 border-white">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500 mb-6">{{ $user->username }}</p>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">NISN</span>
                                <span class="text-sm font-bold text-gray-700">{{ $user->siswa->nisn ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Kelas</span>
                                <span class="text-sm font-bold text-gray-700">{{ $user->siswa->kelas ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ganti Password -->
                <div class="md:col-span-2">
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Ubah Password</h3>
                        </div>

                        <form action="{{ route('siswa.profil.password') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Password Saat Ini</label>
                                <input type="password" name="current_password" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder-gray-400" placeholder="••••••••">
                                @error('current_password') <p class="mt-2 text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Password Baru</label>
                                <input type="password" name="password" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder-gray-400" placeholder="Minimal 8 karakter">
                                @error('password') <p class="mt-2 text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder-gray-400" placeholder="Ulangi password baru">
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="w-full py-4 bg-indigo-600 text-white font-black rounded-2xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 hover:shadow-indigo-200">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
