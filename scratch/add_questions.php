<?php

use App\Models\Pertanyaan;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$kuesioner_id = 1;
$start_order = Pertanyaan::where('kuesioner_id', $kuesioner_id)->max('nomor_urutan') ?? 0;

$pg_questions = [
    [
        'isi' => 'Sejauh mana Anda merasa aman berada di lingkungan sekolah?',
        'indikator' => 'Keamanan',
        'opsi' => "Sangat Aman|5\nAman|4\nCukup|3\nKurang Aman|2\nTidak Aman|1"
    ],
    [
        'isi' => 'Bagaimana kualitas kebersihan di dalam ruang kelas Anda?',
        'indikator' => 'Kebersihan',
        'opsi' => "Sangat Bersih|5\nBersih|4\nCukup|3\nKurang Bersih|2\nKotor|1"
    ],
    [
        'isi' => 'Seberapa mudah Anda mengakses fasilitas perpustakaan sekolah?',
        'indikator' => 'Fasilitas',
        'opsi' => "Sangat Mudah|5\nMudah|4\nCukup|3\nSulit|2\nSangat Sulit|1"
    ],
    [
        'isi' => 'Bagaimana kondisi meja dan kursi di dalam kelas saat ini?',
        'indikator' => 'Sarana Prasarana',
        'opsi' => "Sangat Baik|5\nBaik|4\nCukup|3\nKurang Baik|2\nRusak|1"
    ],
    [
        'isi' => 'Apakah pencahayaan di dalam ruang kelas sudah cukup memadai?',
        'indikator' => 'Lingkungan Fisik',
        'opsi' => "Sangat Memadai|5\nMemadai|4\nCukup|3\nKurang|2\nSangat Gelap|1"
    ],
    [
        'isi' => 'Bagaimana ketersediaan air bersih di area toilet sekolah?',
        'indikator' => 'Kebersihan',
        'opsi' => "Sangat Selalu Tersedia|5\nSering Tersedia|4\nKadang Tersedia|3\nJarang Tersedia|2\nTidak Pernah Tersedia|1"
    ],
    [
        'isi' => 'Seberapa nyaman suhu udara di dalam ruang kelas saat kegiatan belajar?',
        'indikator' => 'Lingkungan Fisik',
        'opsi' => "Sangat Nyaman|5\nNyaman|4\nCukup|3\nKurang Nyaman|2\nSangat Panas|1"
    ],
    [
        'isi' => 'Apakah koneksi internet/Wi-Fi sekolah membantu proses belajar Anda?',
        'indikator' => 'Teknologi',
        'opsi' => "Sangat Membantu|5\nMembantu|4\nCukup|3\nKurang Membantu|2\nTidak Membantu|1"
    ],
    [
        'isi' => 'Bagaimana kualitas peralatan praktek di laboratorium?',
        'indikator' => 'Fasilitas',
        'opsi' => "Sangat Modern & Lengkap|5\nLengkap|4\nCukup|3\nKurang Lengkap|2\nBanyak yang Rusak|1"
    ],
    [
        'isi' => 'Seberapa luas ruang gerak Anda di dalam kelas (tidak berdesakan)?',
        'indikator' => 'Kenyamanan',
        'opsi' => "Sangat Luas|5\nLuas|4\nCukup|3\nSempit|2\nSangat Berdesakan|1"
    ],
];

$essay_questions = [
    ['isi' => 'Apa masukan Anda untuk meningkatkan keamanan di lingkungan sekolah?', 'indikator' => 'Keamanan'],
    ['isi' => 'Sebutkan fasilitas sekolah yang menurut Anda paling mendesak untuk diperbaiki.', 'indikator' => 'Fasilitas'],
    ['isi' => 'Bagaimana pendapat Anda tentang kebersihan lingkungan sekolah secara umum?', 'indikator' => 'Kebersihan'],
    ['isi' => 'Fasilitas apa yang Anda harapkan ada di sekolah namun saat ini belum tersedia?', 'indikator' => 'Fasilitas'],
    ['isi' => 'Bagaimana kenyamanan belajar Anda terganggu oleh kebisingan dari luar kelas?', 'indikator' => 'Kenyamanan'],
    ['isi' => 'Apa saran Anda agar toilet sekolah tetap bersih dan wangi?', 'indikator' => 'Kebersihan'],
    ['isi' => 'Bagaimana pendapat Anda tentang kualitas udara dan sirkulasi di dalam kelas?', 'indikator' => 'Lingkungan Fisik'],
    ['isi' => 'Berikan kritik atau saran untuk perpustakaan agar lebih menarik dikunjungi.', 'indikator' => 'Fasilitas'],
    ['isi' => 'Bagaimana peran fasilitas olahraga dalam mendukung hobi atau kesehatan Anda?', 'indikator' => 'Fasilitas'],
    ['isi' => 'Secara keseluruhan, apa satu hal yang paling ingin Anda ubah dari lingkungan fisik sekolah?', 'indikator' => 'Umum'],
];

DB::beginTransaction();
try {
    $order = $start_order + 1;
    
    // Insert PG
    foreach ($pg_questions as $q) {
        Pertanyaan::create([
            'kuesioner_id' => $kuesioner_id,
            'indikator' => $q['indikator'],
            'nomor_urutan' => $order++,
            'isi_pertanyaan' => $q['isi'],
            'tipe_jawaban' => 'pilihan_ganda',
            'opsi_jawaban' => $q['opsi'],
            'bobot' => 1
        ]);
    }

    // Insert Essay
    foreach ($essay_questions as $q) {
        Pertanyaan::create([
            'kuesioner_id' => $kuesioner_id,
            'indikator' => $q['indikator'],
            'nomor_urutan' => $order++,
            'isi_pertanyaan' => $q['isi'],
            'tipe_jawaban' => 'esai',
            'bobot' => 1
        ]);
    }
    
    DB::commit();
    echo "Successfully added 10 PG and 10 Essay questions.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
