<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Kuesioner;
use App\Models\Pertanyaan;
use App\Models\Jawaban;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SurveySimulationSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil admin ID
        $admin = User::where('role', 'admin')->first();
        if (!$admin) return;

        // 1. Buat Kuesioner
        $kuesioner = Kuesioner::create([
            'nama_kuesioner' => 'Survei Lingkungan Belajar Semester Ganjil 2026',
            'deskripsi' => 'Survei ini bertujuan untuk mengevaluasi kualitas fasilitas, kebersihan, dan kenyamanan di lingkungan sekolah.',
            'periode_mulai' => Carbon::now()->subDays(7),
            'periode_selesai' => Carbon::now()->addDays(7),
            'status' => 'aktif',
            'created_by' => $admin->id
        ]);

        // 2. Buat Pertanyaan berdasarkan Indikator
        $indikators = [
            'Fasilitas' => [
                'Sejauh mana kualitas meja dan kursi di dalam kelas?',
                'Bagaimana ketersediaan alat tulis dan papan tulis?',
                'Apakah proyektor di kelas berfungsi dengan baik?'
            ],
            'Kebersihan' => [
                'Bagaimana tingkat kebersihan toilet sekolah?',
                'Apakah tempat sampah tersedia di setiap sudut kelas?',
                'Seberapa bersih lingkungan kantin sekolah?'
            ],
            'Interaksi Sosial' => [
                'Bagaimana hubungan antar sesama siswa di kelas?',
                'Sejauh mana guru mendengarkan keluhan siswa?',
                'Apakah lingkungan sekolah terasa aman dari bullying?'
            ]
        ];

        $pertanyaanModels = [];
        $no = 1;
        foreach ($indikators as $indikator => $texts) {
            foreach ($texts as $text) {
                $pertanyaanModels[] = Pertanyaan::create([
                    'kuesioner_id' => $kuesioner->id,
                    'indikator' => $indikator,
                    'isi_pertanyaan' => $text,
                    'tipe_jawaban' => 'skala_likert',
                    'nomor_urutan' => $no++
                ]);
            }
        }

        // 3. Buat 40 Siswa Dummy (untuk testing clustering K=4)
        $polaJawaban = [
            'sangat_puas' => ['range' => [4, 5], 'count' => 10],
            'puas' => ['range' => [3, 4], 'count' => 12],
            'cukup' => ['range' => [2, 3], 'count' => 10],
            'kurang' => ['range' => [1, 2], 'count' => 8],
        ];

        $siswaCount = 2; 
        foreach ($polaJawaban as $pola => $config) {
            for ($i = 0; $i < $config['count']; $i++) {
                $nama = "Siswa " . $siswaCount;
                $user = User::create([
                    'name' => $nama,
                    'username' => 'siswa' . $siswaCount,
                    'email' => 'siswa' . $siswaCount . '@example.com',
                    'password' => bcrypt('password'),
                    'role' => 'siswa'
                ]);

                Siswa::create([
                    'user_id' => $user->id,
                    'nis' => '1000' . $siswaCount,
                    'nama_lengkap' => $nama,
                    'kelas' => 'X-IPA-' . rand(1, 3),
                    'jenis_kelamin' => rand(0, 1) ? 'L' : 'P'
                ]);

                // 4. Isi Jawaban untuk Siswa ini
                foreach ($pertanyaanModels as $q) {
                    Jawaban::create([
                        'kuesioner_id' => $kuesioner->id,
                        'pertanyaan_id' => $q->id,
                        'user_id' => $user->id,
                        'jawaban_teks' => 'Simulasi',
                        'nilai_jawaban' => rand($config['range'][0], $config['range'][1])
                    ]);
                }
                $siswaCount++;
            }
        }
    }
}
