<?php

// Pastikan file Karyawan.php sudah di-include
require_once 'Karyawan.php';

/**
 * Class KaryawanMagang
 * 
 * Class turunan dari Karyawan untuk tipe magang.
 * Class ini tidak lagi bersifat abstract karena sudah mengimplementasikan 
 * seluruh method abstrak dari parent.
 */
class KaryawanMagang extends Karyawan {
    
    // Property tambahan dengan modifier protected
    protected float $uang_saku_bulanan;
    protected string $sertifikat_kampus_merdeka;

    /**
     * Constructor menerima data array dari database
     * 
     * @param PDO $db Objek koneksi database
     * @param array $data Data record dari database (associative array)
     */
    public function __construct(PDO $db, array $data = []) {
        // Memanggil parent constructor (Karyawan)
        parent::__construct($db, $data);

        if (!empty($data)) {
            // Mapping data spesifik untuk KaryawanMagang
            // Melakukan casting ke (float) untuk uang_saku_bulanan, serta memberikan nilai fallback 0.0
            $this->uang_saku_bulanan = isset($data['uang_saku_bulanan']) ? (float) $data['uang_saku_bulanan'] : 0.0;
            
            // Menggunakan null coalescing operator untuk string
            $this->sertifikat_kampus_merdeka = $data['sertifikat_kampus_merdeka'] ?? 'Tidak ada';
        }
    }

    /**
     * Method pencarian data Karyawan Magang
     * 
     * @param string $keyword
     * @return array
     */
    public function search(string $keyword = ''): array {
        $query = "SELECT * FROM tabel_karyawan WHERE jenis_karyawan = 'Magang'";
        
        if (!empty($keyword)) {
            $query .= " AND nama_karyawan LIKE :keyword";
        }
        $query .= " ORDER BY id_karyawan ASC";
        
        $stmt = $this->db->prepare($query);
        
        if (!empty($keyword)) {
            $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Getter untuk property uang_saku_bulanan
     * 
     * @return float
     */
    public function getUangSakuBulanan(): float {
        return $this->uang_saku_bulanan;
    }

    /**
     * Getter untuk property sertifikat_kampus_merdeka
     * 
     * @return string
     */
    public function getSertifikatKampusMerdeka(): string {
        return $this->sertifikat_kampus_merdeka;
    }

    /**
     * Implementasi method overriding untuk menghitung gaji bersih Karyawan Magang
     * 
     * Rumus: (hari_kerja_masuk * gaji_dasar_per_hari) * 0.80
     * Property ini diakses dari parent class (Karyawan) yang bertipe protected.
     * 
     * @return float
     */
    public function hitungGajiBersih(): float {
        // Menghitung total gaji dasar dan memberikan pengali 80% (0.80) untuk anak magang
        return (float) (($this->hari_kerja_masuk * $this->gaji_dasar_per_hari) * 0.80);
    }
}
