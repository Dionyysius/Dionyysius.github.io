<?php
class DatabaseConnection {
    private $host = "localhost";
    private $username = "root";
    private $password = "password_baru";
    private $database = "users_db";
    public $conn;

    // Constructor untuk membuat koneksi ke database
    public function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        // Periksa koneksi database
        if ($this->conn->connect_error) {
            die("Database connection error: " . $this->conn->connect_error);
        } else {
            echo "Koneksi berhasil!";
        }
    }

    // Fungsi untuk mendapatkan koneksi database
    public function getConnection() {
        return $this->conn;
    }
}
?>
