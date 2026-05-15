<x-guest-layout>
    <div class="mb-8 text-center sm:text-left">
        <h2 class="text-2xl font-bold text-gray-900">Selamat Datang Kembali</h2>
        <p class="text-sm text-gray-500 mt-2">Silakan masuk ke akun Anda untuk melanjutkan.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username -->
        <div>
            <x-input-label for="username" :value="__('Nomor Induk / Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="w-full sm:w-auto justify-center">
                {{ __('Masuk Sekarang') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Bantuan Login -->
    <div class="mt-10 pt-6 border-t border-gray-100">
        <div class="bg-indigo-50/50 rounded-2xl p-4 border border-indigo-100/50">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-indigo-900">Bantuan Login</h4>
                    <p class="text-xs text-indigo-600">Lupa Nomor Induk? Cari nama Anda di sini.</p>
                </div>
            </div>
            
            <div class="relative">
                <input type="text" id="searchSiswa" 
                    class="w-full px-4 py-2.5 text-sm border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white placeholder:text-gray-400" 
                    placeholder="Ketik Nama Lengkap Anda...">
                
                <div id="searchResult" class="absolute z-20 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl hidden overflow-hidden">
                    <div id="resultContent" class="max-h-48 overflow-y-auto divide-y divide-gray-50">
                        <!-- Results go here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchSiswa');
        const searchResult = document.getElementById('searchResult');
        const resultContent = document.getElementById('resultContent');
        const usernameInput = document.getElementById('username');

        let timeout = null;

        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            const query = this.value;

            if (query.length < 2) {
                searchResult.classList.add('hidden');
                return;
            }

            timeout = setTimeout(() => {
                fetch(`/api/search-siswa?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        resultContent.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(item => {
                                const div = document.createElement('div');
                                div.className = 'px-4 py-3 hover:bg-indigo-50 cursor-pointer transition flex flex-col';
                                div.innerHTML = `
                                    <span class="text-sm font-bold text-gray-900">${item.nama_lengkap}</span>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded">${item.nis}</span>
                                        <span class="text-xs text-gray-400">${item.kelas}</span>
                                    </div>
                                `;
                                div.onclick = () => {
                                    usernameInput.value = item.nis;
                                    searchResult.classList.add('hidden');
                                    searchInput.value = item.nama_lengkap;
                                    // Beri feedback visual ke input username
                                    usernameInput.classList.add('ring-2', 'ring-indigo-500');
                                    setTimeout(() => usernameInput.classList.remove('ring-2', 'ring-indigo-500'), 1000);
                                };
                                resultContent.appendChild(div);
                            });
                            searchResult.classList.remove('hidden');
                        } else {
                            resultContent.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500 italic text-center">Nama tidak ditemukan</div>';
                            searchResult.classList.remove('hidden');
                        }
                    });
            }, 300);
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResult.contains(e.target)) {
                searchResult.classList.add('hidden');
            }
        });
    </script>
</x-guest-layout>
