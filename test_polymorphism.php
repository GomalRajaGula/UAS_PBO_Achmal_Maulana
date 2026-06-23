<?php

/**
 * File: test_polymorphism.php
 * 
 * Script ini mendemonstrasikan penerapan konsep Polymorphism dalam OOP PHP.
 * Meskipun pemanggilan method-nya persis sama ($karyawan->hitungGajiBersih()),
 * output yang dihasilkan akan berbeda sesuai dengan instance dari masing-masing object.
 */

// 1. Import seluruh class
require_once 'classes/KaryawanKontrak.php';
require_once 'classes/KaryawanTetap.php';
require_once 'classes/KaryawanMagang.php';

// 2. Pembuatan Data Array Dummy untuk Masing-masing Jenis Karyawan
$dataKontrak = [
    'id_karyawan' => 1,
    'nama_karyawan' => 'Achmal Maulana',
    'departemen' => 'IT Support',
    'hari_kerja_masuk' => 22,
    'gaji_dasar_per_hari' => 250000,
    'durasi_kontrak_bulan' => 12,
    'agensi_penyalur' => 'PT Outsource Nusantara'
];

$dataTetap = [
    'id_karyawan' => 8,
    'nama_karyawan' => 'Budi Santoso',
    'departemen' => 'Manager IT',
    'hari_kerja_masuk' => 22,
    'gaji_dasar_per_hari' => 500000,
    'tunjangan_kesehatan' => 1500000,
    'opsi_saham_id' => 'OPS001'
];

$dataMagang = [
    'id_karyawan' => 15,
    'nama_karyawan' => 'Alfi Syahputra',
    'departemen' => 'Frontend Developer',
    'hari_kerja_masuk' => 20,
    'gaji_dasar_per_hari' => 100000,
    'uang_saku_bulanan' => 1500000,
    'sertifikat_kampus_merdeka' => 'Ada'
];

// Instansiasi Object (Masing-masing satu objek)
$karyawanKontrak = new KaryawanKontrak($dataKontrak);
$karyawanTetap = new KaryawanTetap($dataTetap);
$karyawanMagang = new KaryawanMagang($dataMagang);

// 3. Simpan semua objek ke dalam array
/** @var Karyawan[] $listKaryawan */
$listKaryawan = [
    $karyawanKontrak,
    $karyawanTetap,
    $karyawanMagang
];

// Tampilan CLI agar lebih rapi
echo "=================================================\n";
echo " DEMO POLYMORPHISM - SISTEM MANAJEMEN KARYAWAN\n";
echo "=================================================\n\n";

// 4. Gunakan foreach untuk memanggil hitungGajiBersih() secara dinamis
foreach ($listKaryawan as $karyawan) {
    // get_class() adalah built-in method PHP untuk mendapatkan nama class dari sebuah object
    $jenisKaryawan = get_class($karyawan);
    
    // 5. Menampilkan Nama Karyawan, Jenis Karyawan, dan Gaji Bersih
    echo "Nama Karyawan  : " . $karyawan->getNamaKaryawan() . "\n";
    echo "Jenis Karyawan : " . $jenisKaryawan . "\n";
    
    // Pemanggilan $karyawan->hitungGajiBersih() yang menghasilkan perhitungan berbeda-beda (Polymorphism)
    $gajiBersih = $karyawan->hitungGajiBersih();
    $gajiBersihFormatted = "Rp " . number_format($gajiBersih, 0, ',', '.');
    
    echo "Gaji Bersih    : " . $gajiBersihFormatted . "\n";
    echo "-------------------------------------------------\n";
}

// 6. Kesimpulan
echo "\nKesimpulan:\n";
echo "Di dalam loop foreach, kita hanya menggunakan satu method yaitu `hitungGajiBersih()`.\n";
echo "Namun karena berlakunya prinsip Polymorphism (Polimorfisme), sistem secara cerdas\n";
echo "membedakan cara perhitungan gaji berdasarkan instansiasi objek aslinya:\n";
echo "- KaryawanKontrak : (22 * 250.000) = Rp 5.500.000\n";
echo "- KaryawanTetap   : (22 * 500.000) + 1.500.000 (Tunjangan) = Rp 12.500.000\n";
echo "- KaryawanMagang  : (20 * 100.000) * 0.80 (Potongan 20%) = Rp 1.600.000\n";
