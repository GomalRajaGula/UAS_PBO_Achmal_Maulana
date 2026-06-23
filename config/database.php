<?php

class Database {
    // Properti untuk konfigurasi database
    // Menggunakan typed properties (fitur PHP 7.4/8)
    private string $host = "localhost";
    private string $db_name = "DB_UAS_PBO_TRPL1B_Achmal_Maulana";
    private string $username = "root";
    private string $password = "";
    
    // Properti untuk menyimpan objek koneksi PDO
    // Menggunakan tipe data nullable (?PDO) karena awalnya bernilai null
    private ?PDO $conn = null;

    /**
     * Method untuk membuat dan mengembalikan koneksi database
     * 
     * @return PDO|null Mengembalikan objek PDO jika berhasil, null jika gagal
     */
    public function connect(): ?PDO {
        $this->conn = null;

        try {
            // Membuat Data Source Name (DSN) dengan menyertakan charset utf8mb4
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            
            // Inisialisasi koneksi PDO baru
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Konfigurasi atribut PDO
            // Mengaktifkan mode exception untuk penanganan error (PDO::ERRMODE_EXCEPTION)
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Mengatur mode fetch default menjadi array asosiatif untuk kemudahan akses data
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch (PDOException $exception) {
            // Menangkap error jika koneksi gagal dan menghentikan eksekusi (atau bisa menampilkan pesan)
            // Cocok untuk proses debugging saat presentasi
            echo "Koneksi Database Gagal: " . $exception->getMessage();
        }

        // Mengembalikan objek koneksi (berupa PDO object atau null jika terjadi error)
        return $this->conn;
    }
}
