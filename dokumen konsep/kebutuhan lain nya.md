## Relasi antar tabel

Relasi yang paling masuk akal:

* `users` 1..1 `siswa`
* `users` 1..1 `guru`
* `kuesioner` 1..n `pertanyaan`
* `users` 1..n `jawaban`
* `pertanyaan` 1..n `jawaban`
* `kuesioner` 1..n `jawaban`
* `kuesioner` 1..n `hasil_survei`
* `users` 1..n `hasil_survei`
* `kuesioner` 1..n `hasil_cluster`
* `kuesioner` 1..n `laporan`

Kalau mau lebih sederhana, `admin`, `guru`, dan `siswa` bisa disatukan di `users`, lalu tabel detailnya hanya untuk data khusus masing-masing. Itu biasanya lebih enak dipakai di Laravel.

## 5) Desain proses bisnis

Ini versi yang cocok buat ditulis di bab analisis:

### Proses 1: Pengelolaan survei

Admin membuat periode survei, menyusun pertanyaan, dan mengaktifkan survei.

### Proses 2: Pengisian survei

Siswa login, mengisi pertanyaan sesuai indikator, lalu sistem menyimpan jawaban.

### Proses 3: Pengolahan hasil

Sistem menghitung skor tiap indikator dan total nilai responden.

### Proses 4: Klasifikasi hasil

Sistem mengelompokkan responden atau hasil survei ke kategori tertentu, misalnya:

* sangat baik
* baik
* cukup
* kurang

### Proses 5: Laporan

Admin melihat rekap, grafik, dan hasil analisis untuk evaluasi sekolah.

## 6) Alur data

Kalau dibuat secara data flow sederhana:

**Admin** → input kuesioner → `kuesioner` dan `pertanyaan`

**Siswa** → isi jawaban → `jawaban`

**Sistem** → hitung hasil → `hasil_survei`

**Sistem** → cluster data → `hasil_cluster`

**Admin/Guru** → lihat laporan → `laporan`

## 7) Saran rancangan algoritma

Di laporan, Collaborative Filtering diposisikan untuk membantu laporan evaluasi otomatis, sedangkan K-Means dipakai untuk pengelompokan hasil. Supaya lebih mudah dipertanggungjawabkan di sidang, saya sarankan pembagiannya begini:

### Collaborative Filtering

Pakai untuk mencari kemiripan pola jawaban antar siswa.

Contoh:

* siswa A dan B punya pola jawaban mirip,
* sistem bisa melihat kecenderungan jawaban yang sama,
* hasilnya dipakai sebagai dasar rekomendasi atau interpretasi.

### K-Means

Pakai untuk mengelompokkan skor akhir survei ke kategori.

Contoh:

* Cluster 1 = lingkungan sangat baik
* Cluster 2 = lingkungan baik
* Cluster 3 = lingkungan perlu perbaikan

Kalau mau aman, di laporan jelaskan bahwa  **CF dipakai untuk analisis kemiripan respon** , sedangkan  **K-Means untuk pengelompokan kategori hasil** . Jangan dibuat terlalu “berat” kalau belum ada bukti implementasi matematis yang lengkap.

## 8) Use case inti

Aktor yang saya sarankan:

* Admin
* Siswa
* Guru/Kepala sekolah

Use case:

* Login
* Logout
* Kelola pengguna
* Kelola kuesioner
* Kelola pertanyaan
* Isi survei
* Lihat hasil survei
* Cetak laporan
* Lihat grafik hasil

## 9) Output sistem

Output yang bagus untuk laporan:

* persentase tiap indikator
* grafik batang/pie
* tabel rekap jawaban
* kategori hasil
* laporan PDF
* arsip laporan per periode

Kalau mau, langkah berikutnya saya bisa bantu bikin salah satu dari ini:

 **ERD database** ,  **use case diagram** , atau **flowchart/alur sistem** dalam bentuk yang siap kamu masukkan ke skripsi.
