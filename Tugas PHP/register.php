<?php
session_start();
include 'DatabaseConnection.php';
include 'UserAuthentication.php';

$dbConnection = new DatabaseConnection();

// Pastikan $dbConnection sudah didefinisikan
if (!isset($dbConnection) || !$dbConnection->conn) {
    // Gagal mengakses DatabaseConnection.php, mungkin file tidak ditemukan atau terjadi kesalahan lain
    echo "Database connection error!";
    exit(); // Keluar dari script
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username already exists
    $stmt = $dbConnection->conn->prepare("SELECT * FROM users WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo "Username already exists!";
        } else {
            // Insert new user into database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $dbConnection->conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt) {
                $stmt->bind_param("ss", $username, $hashed_password);
                if ($stmt->execute()) {
                    echo "Registration successful!";
                } else {
                    echo "Error registering user: " . $stmt->error;
                }
            } else {
                echo "Error preparing statement: " . $dbConnection->conn->error;
            }
        }
        // Tutup statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $dbConnection->conn->error;
    }
}
// Tutup koneksi database
$dbConnection->conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Register</h2>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" placeholder="Enter username" name="username">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
            <p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
</body>
</html>
