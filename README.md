# Sistem Manajemen Karyawan Berbasis PHP OOP

## 📖 Deskripsi Project
Sistem Manajemen Karyawan ini adalah proyek akhir (UAS) untuk mata kuliah Pemrograman Berorientasi Objek (PBO). Aplikasi ini dibangun menggunakan PHP Native yang mengimplementasikan konsep *Object-Oriented Programming* (OOP) secara ketat. Aplikasi difokuskan pada pengelolaan entitas data karyawan, serta simulasi perhitungan gaji bersih dinamis yang dibedakan berdasarkan jenis atau tipe kontrak masing-masing karyawan.

## 🚀 Teknologi yang Digunakan
- **PHP 8** - Bahasa pemrograman utama di sisi *backend* (mengimplementasikan fitur modern seperti *type hinting* & *null coalescing*).
- **MySQL** - Sistem manajemen basis data relasional.
- **PDO (PHP Data Objects)** - Ekstensi PHP untuk manajemen koneksi database yang aman dan andal.
- **HTML + CSS Sederhana** - Antarmuka pengguna (*dashboard view*) yang rapi, responsif, elegan, dan dilengkapi efek *hover*.

## 📁 Struktur Folder Project
```text
project/
│
├── classes/
│   ├── Karyawan.php          (Abstract Base Class)
│   ├── KaryawanKontrak.php   (Subclass Karyawan)
│   ├── KaryawanMagang.php    (Subclass Karyawan)
│   └── KaryawanTetap.php     (Subclass Karyawan)
│
├── config/
│   └── database.php          (Konfigurasi PDO & Database Class)
│
├── database/
│   └── DB_UAS_PBO_TRPL1B_Achmal_Maulana.sql (Skema & Data Dummy)
│
├── index.php                 (Halaman Utama / Dashboard View)
└── README.md                 (Dokumentasi Proyek)
```

## ✨ Fitur Aplikasi
1. **Manajemen Data Karyawan**: Mengambil dan melengkapi data langsung dari database *tabel_karyawan*.
2. **Implementasi OOP Terstruktur**: Memanfaatkan prinsip *Clean Code* dalam pilar utama *Abstraction*, *Inheritance*, dan *Polymorphism*.
3. **Perhitungan Gaji Bersih Terpersonalisasi**: Kalkulasi matematis unik untuk *Gaji Bersih* yang dibedakan berdasarkan formula khusus tiap jenis karyawan.
4. **Tampilan Data Dinamis**: *Dashboard* HTML yang merender data objek secara visual ke dalam bentuk tabel *real-time*.

## 💡 Penjelasan Konsep OOP
Proyek ini mengadopsi pilar utama OOP untuk memastikan kode yang mudah dikembangkan (*scalable*) dan kuat (*robust*):

- **Abstraction** (`classes/Karyawan.php`)
  Class `Karyawan` bertindak sebagai model cetak biru (*blueprint*) dasar yang bersifat *abstract*. Class ini tidak dapat diinstansiasi secara langsung menjadi objek (menggunakan `new Karyawan()`). Ia mendefinisikan properti inti (*protected*) dan mendeklarasikan sebuah *abstract method* `hitungGajiBersih()` yang memaksa seluruh class turunannya untuk merumuskan ulang fungsi perhitungan gaji tersebut.

- **Inheritance** (Pewarisan)
  Konsep pewarisan diwujudkan oleh tiga class utama yang meng-*extends* (mewarisi) class `Karyawan`:
  - `KaryawanKontrak`: Mewarisi properti dasar dan menambahkan durasi kontrak serta agensi penyalur.
  - `KaryawanTetap`: Mewarisi properti dasar dan menambahkan tunjangan kesehatan serta opsi saham.
  - `KaryawanMagang`: Mewarisi properti dasar dan menambahkan uang saku bulanan serta informasi sertifikat.

- **Polymorphism** (Polimorfisme)
  Setiap *subclass* di atas memodifikasi (melakukan *overriding*) method `hitungGajiBersih()` dengan rumusnya masing-masing. Di halaman utama (`index.php`), sistem menampung semua objek yang berbeda ini dalam satu array dan hanya memanggil perintah tunggal: `$karyawan->hitungGajiBersih()` dalam sebuah perulangan (*loop*). PHP secara cerdas akan mengeksekusi rumus yang tepat sesuai dengan wujud kelas spesifik dari masing-masing karyawan pada runtime.

## 🛠️ Cara Menjalankan Project
Untuk menguji dan menjalankan proyek ini di *local environment* Anda, ikuti langkah-langkah praktis berikut:

1. **Persiapan Server Lokal**:
   Pastikan Anda sudah menginstal **XAMPP**, WAMP, atau *software web server* sejenis.
2. **Jalankan Apache & MySQL**:
   Buka aplikasi *Control Panel* XAMPP Anda, lalu klik **Start** pada modul **Apache** dan **MySQL**.
3. **Pindahkan Folder Project**:
   Pastikan folder proyek ini diletakkan di dalam direktori `htdocs` (contoh path: `C:\xampp\htdocs\UAS_PBO_Achmal_Maulana`).
4. **Import Database**:
   - Buka browser dan akses `http://localhost/phpmyadmin`.
   - Buat database baru. Anda dapat menamainya dengan `db_uas_pbo_trpl1b_achmal_maulana` (atau disesuaikan).
   - Klik tab **Import**, telusuri dan *upload* file `database/DB_UAS_PBO_TRPL1B_Achmal_Maulana.sql`.
   - Klik tombol **Go** / **Import** di bagian bawah.
5. **Akses via Browser**:
   Buka tab baru di browser Anda dan kunjungi url: 
   `http://localhost/UAS_PBO_Achmal_Maulana` (sesuaikan dengan nama folder Anda di *htdocs*).

---
*Dibuat oleh Achmal Maulana - Kelas TRPL 1B untuk pemenuhan tugas Ujian Akhir Semester (UAS) Pemrograman Berorientasi Objek.*
