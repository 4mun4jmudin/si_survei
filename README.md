# SiSurvei Akademik

**SiSurvei Akademik** adalah sistem informasi manajemen survei dan kuesioner berskala instansi/sekolah yang dirancang untuk mengumpulkan, mengelola, dan menganalisis *feedback* dari Siswa dan Guru. Dibangun menggunakan **Laravel 11/12** dan **Tailwind CSS**, sistem ini menawarkan antarmuka yang modern (premium look), interaktif, dan responsif, serta dilengkapi dengan fitur analitik cerdas (K-Means Clustering).

## 🚀 Fitur Utama

### 1. Multi-Role Authentication
- **Admin**: Mengelola seluruh data master, membuat kuesioner, memantau respons, mengatur konfigurasi global (Logo, Nama Sekolah), serta melihat hasil analisis survei (Termasuk algoritma K-Means).
- **Siswa**: Memiliki portal khusus untuk melihat riwayat survei, mengisi kuesioner interaktif, dan mengatur profil/password.
- **Guru**: Memiliki portal tersendiri untuk berpartisipasi dalam pengisian survei evaluasi sekolah.

### 2. Manajemen Kuesioner Dinamis
- Pembuatan soal/pertanyaan yang dinamis dengan pengelompokan berdasarkan **Indikator**.
- Skala Likert 1-5 (Sangat Tidak Setuju - Sangat Setuju).
- Periode kuesioner (tanggal mulai dan selesai) serta status aktif/draft.

### 3. Analitik & Pelaporan
- **Dashboard Statistik**: Total responden, rata-rata skor per indikator, dan kategori penilaian (Sangat Baik, Baik, Cukup, Kurang).
- **K-Means Clustering**: Pengelompokan siswa berdasarkan pola jawaban untuk menemukan kluster spesifik (misalnya: puas terhadap fasilitas tapi kurang di pengajaran).
- **Export Data**: Unduh data mentah jawaban ke **Excel** (`.xlsx`) untuk diolah lebih lanjut.
- **Cetak PDF**: Unduh laporan analisis ke dalam format PDF yang siap dicetak.

### 4. Utilitas & Otomatisasi
- **Global Settings**: Ubah logo dan nama sekolah langsung dari dashboard Admin tanpa menyentuh *source code*.
- **Bulk Import Excel**: Unggah data ratusan Siswa dan Guru sekaligus menggunakan file Excel (membuat akun login + profil otomatis).
- **Notification System**: Banner peringatan interaktif jika pengguna memiliki kuesioner aktif yang belum diisi.

---

## 🛠️ Teknologi yang Digunakan
- **Backend**: Laravel (PHP)
- **Frontend**: Blade Templating, Tailwind CSS, Alpine.js
- **Database**: MySQL
- **Assets/Build Tool**: Vite
- **Packages**:
  - `maatwebsite/excel` (Import/Export Excel)
  - `barryvdh/laravel-dompdf` (Cetak Laporan PDF)

---

## 💻 Cara Instalasi (Local Development)

Ikuti langkah-langkah berikut untuk menjalankan sistem ini di komputer Anda:

### 1. Prasyarat Sistem
Pastikan Anda telah menginstal perangkat lunak berikut:
- **PHP** >= 8.2
- **Composer** (Package Manager PHP)
- **Node.js & npm** (Untuk kompilasi frontend Vite)
- **MySQL** (Atau aplikasi bundle seperti XAMPP / Laragon)
- **Git**

### 2. Kloning Repositori
Clone proyek ini ke dalam folder komputer Anda:
```bash
git clone https://github.com/4mun4jmudin/si_survei.git
cd si_survei
```

### 3. Install Dependensi PHP & Node
Jalankan perintah berikut untuk menginstal semua *library* yang dibutuhkan:
```bash
# Install package PHP
composer install

# Install package Frontend
npm install
```

### 4. Konfigurasi Environment (`.env`)
Salin file konfigurasi bawaan dan ubah sesuai pengaturan database lokal Anda:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan bagian database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Application Key & Storage Link
```bash
# Buat App Key
php artisan key:generate

# Link folder storage agar gambar (logo sekolah) bisa diakses secara publik
php artisan storage:link
```

### 6. Migrasi Database & Seeding (Opsional)
Buat struktur tabel di database Anda (pastikan Anda sudah membuat database kosong di MySQL sebelumnya):
```bash
php artisan migrate
```
*(Catatan: Jika ada *seeder* bawaan untuk akun admin, Anda bisa menjalankannya dengan `php artisan migrate --seed`)*.

### 7. Kompilasi Assets Frontend
Agar tampilan Tailwind CSS dan Alpine.js berjalan dengan sempurna, lakukan proses kompilasi:
```bash
# Untuk keperluan development (Live Reload)
npm run dev

# ATAU, untuk keperluan production
npm run build
```

### 8. Jalankan Server Lokal
Buka terminal baru (atau biarkan `npm run dev` tetap berjalan), lalu jalankan perintah:
```bash
php artisan serve
```
Aplikasi kini dapat diakses melalui browser di alamat: `http://localhost:8000`

---

## 🔐 Kredensial Default
Jika Anda menggunakan *seeder* / data contoh, gunakan akses berikut untuk masuk:

| Role | Username | Password |
|---|---|---|
| **Admin** | `admin` | `password` |
| **Siswa** | `siswa` / NIS siswa | `password123` |
| **Guru** | `guru` / NIP guru | `password123` |

*(Harap segera ubah password default setelah sistem berjalan)*.

---

## 📝 Lisensi
Proyek ini dibuat untuk keperluan akademis dan manajemen sekolah. Kode sumber (*source code*) dilindungi dan merupakan hak cipta pengembang. 

Dibuat dengan ❤️ untuk kemajuan pendidikan.
