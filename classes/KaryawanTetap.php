<?php

// Memastikan base class Karyawan.php termuat
require_once 'Karyawan.php';

/**
 * Class KaryawanTetap
 * 
 * Class turunan dari Karyawan untuk tipe tetap.
 * Class ini tidak lagi bersifat abstract karena sudah mengimplementasikan 
 * seluruh method abstrak dari parent.
 */
class KaryawanTetap extends Karyawan {
    
    // Property tambahan dengan modifier protected
    protected float $tunjangan_kesehatan;
    protected string $opsi_saham_id;

    /**
     * Constructor menerima data array dari database
     * 
     * @param PDO $db Objek koneksi database
     * @param array $data Data row dari database (associative array)
     */
    public function __construct(PDO $db, array $data = []) {
        // Memanggil parent constructor (Karyawan)
        parent::__construct($db, $data);

        if (!empty($data)) {
            // Mapping data spesifik untuk KaryawanTetap
            // Casting ke float untuk tunjangan_kesehatan agar aman, dan fallback jika null
            $this->tunjangan_kesehatan = isset($data['tunjangan_kesehatan']) ? (float) $data['tunjangan_kesehatan'] : 0.0;
            
            // Menggunakan null coalescing untuk string
            $this->opsi_saham_id = $data['opsi_saham_id'] ?? 'Tidak ada';
        }
    }

    /**
     * Method pencarian data Karyawan Tetap
     * 
     * @param string $keyword
     * @return array
     */
    public function search(string $keyword = ''): array {
        $query = "SELECT * FROM tabel_karyawan WHERE jenis_karyawan = 'Tetap'";
        
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
     * Getter untuk property tunjangan_kesehatan
     * 
     * @return float
     */
    public function getTunjanganKesehatan(): float {
        return $this->tunjangan_kesehatan;
    }

    /**
     * Getter untuk property opsi_saham_id
     * 
     * @return string
     */
    public function getOpsiSahamId(): string {
        return $this->opsi_saham_id;
    }

    /**
     * Implementasi method overriding untuk menghitung gaji bersih Karyawan Tetap
     * 
     * Rumus: (hari_kerja_masuk * gaji_dasar_per_hari) + tunjangan_kesehatan
     * Mengakses property protected milik parent dan private milik class ini sendiri.
     * 
     * @return float
     */
    public function hitungGajiBersih(): float {
        // Menghitung total gaji dan menambahkan tunjangan kesehatan, lalu memastikannya bertipe float
        return (float) (($this->hari_kerja_masuk * $this->gaji_dasar_per_hari) + $this->tunjangan_kesehatan);
    }
}
