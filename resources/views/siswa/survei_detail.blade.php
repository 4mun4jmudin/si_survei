<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('siswa.dashboard') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-indigo-600 transition mb-6">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-100">
                <div class="p-10 md:p-16">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-full text-[10px] font-black uppercase tracking-widest mb-8 border border-indigo-100">
                        📋 Detail Kuesioner
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl font-black text-gray-900 leading-tight mb-6">
                        {{ $kuesioner->nama_kuesioner }}
                    </h1>

                    <p class="text-lg text-gray-500 leading-relaxed mb-10">
                        Mohon luangkan waktu Anda sejenak untuk memberikan penilaian yang jujur. Jawaban Anda akan digunakan sebagai bahan evaluasi untuk meningkatkan kualitas pelayanan dan fasilitas di lingkungan sekolah.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                        <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Jumlah Pertanyaan</p>
                            <p class="text-2xl font-black text-gray-900">{{ $pertanyaanCount }} Soal</p>
                        </div>
                        <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Estimasi Waktu</p>
                            <p class="text-2xl font-black text-gray-900">{{ ceil($pertanyaanCount * 0.5) }} Menit</p>
                        </div>
                        <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Status Akun</p>
                            <p class="text-2xl font-black text-emerald-600 italic">Terverifikasi</p>
                        </div>
                    </div>

                    <div class="bg-indigo-600 rounded-3xl p-10 text-white relative overflow-hidden group">
                        <div class="relative z-10">
                            <h3 class="text-xl font-bold mb-4">Siap untuk memberikan suara?</h3>
                            <p class="text-indigo-100 mb-8 max-w-md">Klik tombol di bawah untuk memulai. Progress Anda akan tersimpan secara otomatis setiap kali Anda menjawab.</p>
                            
                            <a href="{{ route('siswa.survei.show', $kuesioner) }}" class="inline-flex items-center justify-center gap-3 px-10 py-5 bg-white text-indigo-600 font-black rounded-2xl hover:bg-indigo-50 transition shadow-xl group-hover:scale-105">
                                Mulai Sekarang
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </a>
                        </div>
                        
                        <!-- Decoration -->
                        <svg class="absolute right-[-10%] bottom-[-20%] w-64 h-64 text-indigo-500 opacity-20 transform rotate-12 group-hover:rotate-45 transition duration-1000" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
