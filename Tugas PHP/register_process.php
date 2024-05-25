<?php
session_start();
include 'DatabaseConnection.php';

// Pastikan $dbConnection sudah didefinisikan
if (!isset($dbConnection)) {
    // Gagal mengakses DatabaseConnection.php, mungkin file tidak ditemukan atau terjadi kesalahan lain
    echo "Database connection error!";
    exit(); // Keluar dari script
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username already exists
    $stmt = $dbConnection->conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "Username already exists!";
    } else {
        // Insert new user into database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $dbConnection->conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);
        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error registering user: " . $stmt->error;
        }
    }
    // Tutup statement
    $stmt->close();
}
// Tutup koneksi database
$dbConnection->conn->close();
?>
