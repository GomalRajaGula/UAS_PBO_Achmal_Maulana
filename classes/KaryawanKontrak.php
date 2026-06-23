<?php

// Pastikan file Karyawan.php sudah di-include atau menggunakan autoloading
require_once 'Karyawan.php';

/**
 * Class KaryawanKontrak
 * 
 * Karena class ini belum mengimplementasikan abstract method dari parent class
 * (hitungTotalHarga dan tampilkanInfoFasilitas), maka secara konsep OOP PHP 
 * class ini juga harus dideklarasikan sebagai abstract class agar tidak error.
 */
abstract class KaryawanKontrak extends Karyawan {
    
    // 1. Property protected sesuai spesifikasi
    protected int $durasi_kontrak_bulan;
    protected string $agensi_penyalur;

    /**
     * 2. Constructor menerima data array dari database
     * 
     * @param array $data Data record dari database (assosiative array)
     */
    public function __construct(array $data) {
        // Memanggil constructor dari parent class (Karyawan)
        parent::__construct($data);

        // 3. Mapping data spesifik untuk KaryawanKontrak
        // Menggunakan null coalescing operator (??) untuk fallback nilai default jika key tidak ada
        $this->durasi_kontrak_bulan = $data['durasi_kontrak_bulan'] ?? 0;
        $this->agensi_penyalur = $data['agensi_penyalur'] ?? 'Tidak diketahui';
    }

    /**
     * 4. Getter untuk property durasi_kontrak_bulan
     * 
     * @return int
     */
    public function getDurasiKontrakBulan(): int {
        return $this->durasi_kontrak_bulan;
    }

    /**
     * 4. Getter untuk property agensi_penyalur
     * 
     * @return string
     */
    public function getAgensiPenyalur(): string {
        return $this->agensi_penyalur;
    }

    // 5. Abstract method hitungTotalHarga() dan tampilkanInfoFasilitas() 
    // sengaja tidak diimplementasikan dulu sesuai instruksi.
}
