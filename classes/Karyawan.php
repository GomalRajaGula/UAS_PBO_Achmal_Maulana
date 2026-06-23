<?php

/**
 * Abstract Class Karyawan
 * 
 * Base class untuk seluruh entitas Karyawan.
 */
abstract class Karyawan {
    
    // 1. Property protected dipertahankan
    protected int $id_karyawan;
    protected string $nama_karyawan;
    protected string $departemen;
    protected int $hari_kerja_masuk;
    protected float $gaji_dasar_per_hari;
    protected ?PDO $db;

    /**
     * 2. Constructor menerima data array dari database
     * 
     * @param PDO $db Objek koneksi database
     * @param array $data Data array associative dari database
     */
    public function __construct(PDO $db, array $data = []) {
        $this->db = $db;
        
        // Jika data tidak kosong, lakukan mapping
        if (!empty($data)) {
            // Mapping data dengan aman menggunakan casting dan null coalescing
            $this->id_karyawan = isset($data['id_karyawan']) ? (int) $data['id_karyawan'] : 0;
            $this->nama_karyawan = $data['nama_karyawan'] ?? 'Tanpa Nama';
            $this->departemen = $data['departemen'] ?? 'Tidak ada';
            $this->hari_kerja_masuk = isset($data['hari_kerja_masuk']) ? (int) $data['hari_kerja_masuk'] : 0;
            $this->gaji_dasar_per_hari = isset($data['gaji_dasar_per_hari']) ? (float) $data['gaji_dasar_per_hari'] : 0.0;
        }
    }

    /**
     * Getter id_karyawan
     * @return int
     */
    public function getIdKaryawan(): int {
        return $this->id_karyawan;
    }

    /**
     * Getter nama_karyawan
     * @return string
     */
    public function getNamaKaryawan(): string {
        return $this->nama_karyawan;
    }

    /**
     * Getter departemen
     * @return string
     */
    public function getDepartemen(): string {
        return $this->departemen;
    }

    /**
     * Getter hari_kerja_masuk
     * @return int
     */
    public function getHariKerjaMasuk(): int {
        return $this->hari_kerja_masuk;
    }

    /**
     * Getter gaji_dasar_per_hari
     * @return float
     */
    public function getGajiDasarPerHari(): float {
        return $this->gaji_dasar_per_hari;
    }

    // 3. Abstract method lama telah dihapus

    /**
     * 4. Abstract method baru untuk menghitung gaji bersih
     * 
     * Harus diimplementasikan oleh class turunannya.
     * 
     * @return float
     */
    abstract public function hitungGajiBersih(): float;
}
