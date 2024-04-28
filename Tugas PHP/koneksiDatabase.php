<?php
class DatabaseConnection {
    private $host = "localhost";
    private $username = "root";
    private $password = "password_baru";
    private $database = "user";
    public $conn;

    // Constructor untuk membuat koneksi ke database
    public function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }
}
?>
