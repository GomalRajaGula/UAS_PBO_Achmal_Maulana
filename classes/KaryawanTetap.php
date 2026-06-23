<?php

// Memastikan base class Karyawan.php termuat
require_once 'Karyawan.php';

/**
 * Class KaryawanTetap
 * 
 * Sesuai prinsip OOP PHP, karena class ini belum mengimplementasikan 
 * abstract method dari parent class (hitungTotalHarga dan tampilkanInfoFasilitas),
 * maka class ini dideklarasikan sebagai abstract class.
 */
abstract class KaryawanTetap extends Karyawan {
    
    // 1. Property tambahan dengan modifier protected
    protected float $tunjangan_kesehatan;
    protected string $opsi_saham_id;

    /**
     * 2. Constructor menerima data array dari database
     * 
     * @param array $data Data row dari database (associative array)
     */
    public function __construct(array $data) {
        // Memanggil parent constructor (Karyawan)
        parent::__construct($data);

        // 3. Mapping data spesifik untuk KaryawanTetap
        // Casting ke float untuk tunjangan_kesehatan agar aman, dan fallback jika null
        $this->tunjangan_kesehatan = isset($data['tunjangan_kesehatan']) ? (float) $data['tunjangan_kesehatan'] : 0.0;
        
        // Menggunakan null coalescing untuk string
        $this->opsi_saham_id = $data['opsi_saham_id'] ?? 'Tidak ada';
    }

    /**
     * 4. Getter untuk property tunjangan_kesehatan
     * 
     * @return float
     */
    public function getTunjanganKesehatan(): float {
        return $this->tunjangan_kesehatan;
    }

    /**
     * 4. Getter untuk property opsi_saham_id
     * 
     * @return string
     */
    public function getOpsiSahamId(): string {
        return $this->opsi_saham_id;
    }

    // 5. Abstract method sengaja tidak diimplementasikan dulu sesuai spesifikasi.
}
