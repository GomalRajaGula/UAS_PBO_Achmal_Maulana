<?php

// Pastikan file Karyawan.php sudah di-include atau menggunakan autoloading
require_once 'Karyawan.php';

/**
 * Class KaryawanKontrak
 * 
 * Class turunan dari Karyawan untuk tipe kontrak.
 * Class ini tidak lagi bersifat abstract karena sudah mengimplementasikan 
 * seluruh method abstrak dari parent.
 */
class KaryawanKontrak extends Karyawan {
    
    // Property protected sesuai spesifikasi
    protected int $durasi_kontrak_bulan;
    protected string $agensi_penyalur;

    /**
     * Constructor menerima data array dari database
     * 
     * @param array $data Data record dari database (associative array)
     */
    public function __construct(array $data) {
        // Memanggil constructor dari parent class (Karyawan)
        parent::__construct($data);

        // Mapping data spesifik untuk KaryawanKontrak
        $this->durasi_kontrak_bulan = isset($data['durasi_kontrak_bulan']) ? (int) $data['durasi_kontrak_bulan'] : 0;
        $this->agensi_penyalur = $data['agensi_penyalur'] ?? 'Tidak diketahui';
    }

    /**
     * Getter untuk property durasi_kontrak_bulan
     * 
     * @return int
     */
    public function getDurasiKontrakBulan(): int {
        return $this->durasi_kontrak_bulan;
    }

    /**
     * Getter untuk property agensi_penyalur
     * 
     * @return string
     */
    public function getAgensiPenyalur(): string {
        return $this->agensi_penyalur;
    }

    /**
     * Implementasi method overriding untuk menghitung gaji bersih Karyawan Kontrak
     * 
     * Rumus: hari_kerja_masuk * gaji_dasar_per_hari
     * Property ini diakses dari parent class (Karyawan) yang bertipe protected.
     * 
     * @return float
     */
    public function hitungGajiBersih(): float {
        // Menghitung total gaji dan memastikannya bertipe float
        return (float) ($this->hari_kerja_masuk * $this->gaji_dasar_per_hari);
    }
}
