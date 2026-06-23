<?php

class Karyawan {
    private $conn;
    private $table_name = "karyawan";

    // Properties
    public $id;
    public $nip;
    public $nama;
    public $jabatan;
    public $email;
    public $no_telp;

    // Constructor with DB connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Read all karyawan
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Create Karyawan record
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nip=:nip, nama=:nama, jabatan=:jabatan, email=:email, no_telp=:no_telp";
        
        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->nip = htmlspecialchars(strip_tags($this->nip));
        $this->nama = htmlspecialchars(strip_tags($this->nama));
        $this->jabatan = htmlspecialchars(strip_tags($this->jabatan));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->no_telp = htmlspecialchars(strip_tags($this->no_telp));

        // Bind parameters
        $stmt->bindParam(":nip", $this->nip);
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":jabatan", $this->jabatan);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":no_telp", $this->no_telp);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update Karyawan record
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nip=:nip, nama=:nama, jabatan=:jabatan, email=:email, no_telp=:no_telp 
                  WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->nip = htmlspecialchars(strip_tags($this->nip));
        $this->nama = htmlspecialchars(strip_tags($this->nama));
        $this->jabatan = htmlspecialchars(strip_tags($this->jabatan));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->no_telp = htmlspecialchars(strip_tags($this->no_telp));

        // Bind parameters
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":nip", $this->nip);
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":jabatan", $this->jabatan);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":no_telp", $this->no_telp);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete Karyawan record
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind parameter
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get single Karyawan by ID
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nip = $row['nip'];
            $this->nama = $row['nama'];
            $this->jabatan = $row['jabatan'];
            $this->email = $row['email'];
            $this->no_telp = $row['no_telp'];
            return true;
        }
        return false;
    }
}
