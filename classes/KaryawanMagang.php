<?php

// Pastikan file Karyawan.php sudah di-include
require_once 'Karyawan.php';

/**
 * Class KaryawanMagang
 * 
 * Sesuai prinsip OOP PHP, karena class ini belum mengimplementasikan 
 * abstract method dari parent class (hitungTotalHarga dan tampilkanInfoFasilitas),
 * maka class ini dideklarasikan sebagai abstract class.
 */
abstract class KaryawanMagang extends Karyawan {
    
    // 1. Property tambahan dengan modifier protected
    protected float $uang_saku_bulanan;
    protected string $sertifikat_kampus_merdeka;

    /**
     * 2. Constructor menerima data array dari database
     * 
     * @param array $data Data record dari database (associative array)
     */
    public function __construct(array $data) {
        // Memanggil parent constructor (Karyawan)
        parent::__construct($data);

        // 3. Mapping data spesifik untuk KaryawanMagang
        // Melakukan casting ke (float) untuk uang_saku_bulanan, serta memberikan nilai fallback 0.0
        $this->uang_saku_bulanan = isset($data['uang_saku_bulanan']) ? (float) $data['uang_saku_bulanan'] : 0.0;
        
        // Menggunakan null coalescing operator untuk string
        $this->sertifikat_kampus_merdeka = $data['sertifikat_kampus_merdeka'] ?? 'Tidak ada';
    }

    /**
     * 4. Getter untuk property uang_saku_bulanan
     * 
     * @return float
     */
    public function getUangSakuBulanan(): float {
        return $this->uang_saku_bulanan;
    }

    /**
     * 4. Getter untuk property sertifikat_kampus_merdeka
     * 
     * @return string
     */
    public function getSertifikatKampusMerdeka(): string {
        return $this->sertifikat_kampus_merdeka;
    }

    // 5. Abstract method (hitungTotalHarga dan tampilkanInfoFasilitas) 
    // sengaja tidak diimplementasikan dulu sesuai instruksi.
}
