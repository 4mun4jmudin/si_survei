## Struktur database yang saya sarankan

Kalau untuk skripsi, database jangan terlalu ribet, tapi tetap normal dan jelas. Berdasarkan tabel yang sudah disebut di laporan seperti `tbl_admin`, `tbl_users`, `tbl_pertanyaan`, `tbl_jawaban`, `tbl_kuesioner`, `tbl_laporan`, dan `tbl_hasil_cluster`, saya sarankan versi yang lebih rapi seperti ini.

### Tabel inti

#### a. `users`

Untuk semua akun login.

* id
* nama
* username
* password
* role (`admin`, `guru`, `siswa`)
* status_aktif
* created_at
* updated_at

#### b. `siswa`

Data detail siswa.

* id
* user_id
* nis
* nama_lengkap
* kelas
* jenis_kelamin
* created_at
* updated_at

#### c. `guru`

Kalau guru juga dipakai.

* id
* user_id
* nip
* nama_lengkap
* mapel
* created_at
* updated_at

#### d. `kuesioner`

Untuk periode survei.

* id
* nama_kuesioner
* deskripsi
* periode_mulai
* periode_selesai
* status
* created_by
* created_at
* updated_at

#### e. `pertanyaan`

Semua pertanyaan survei.

* id
* kuesioner_id
* indikator
* nomor_urutan
* isi_pertanyaan
* tipe_jawaban
* bobot
* created_at
* updated_at

#### f. `jawaban`

Menampung jawaban siswa.

* id
* kuesioner_id
* pertanyaan_id
* user_id
* nilai_jawaban
* jawaban_teks
* created_at
* updated_at

#### g. `hasil_survei`

Hasil olahan per siswa atau per periode.

* id
* kuesioner_id
* user_id
* total_nilai
* rata_rata
* kategori
* created_at
* updated_at

#### h. `hasil_cluster`

Kalau mau simpan hasil clustering.

* id
* kuesioner_id
* user_id
* cluster
* centroid_distance
* kategori_cluster
* created_at
* updated_at

#### i. `laporan`

Untuk arsip laporan.

* id
* kuesioner_id
* judul_laporan
* ringkasan
* file_pdf
* created_at
* updated_at
