<?php
class Database {
    protected $conn;
    
    public function __construct() { 
        $this->connect(); 
    }
    
    protected function connect() {
        $this->conn = new mysqli("localhost", "root", "", "perpustakaan_db");
        
        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
        
        $this->conn->set_charset("utf8mb4");
    }
}
?>