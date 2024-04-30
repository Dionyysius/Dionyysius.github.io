<?php
session_start();

// Include DatabaseConnection.php and UserAuthentication.php files
include 'DatabaseConnection.php';
include 'UserAuthentication.php';

// Deklarasikan pesan kesalahan
$loginError = "";

// Jika formulir telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Membuat objek koneksi database
    $dbConnection = new DatabaseConnection();

    // Membuat objek autentikasi pengguna
    $userAuthentication = new UserAuthentication($dbConnection->conn);

    // Mengambil data dari formulir login
    $username = $_POST['email'];
    $password = $_POST['password'];

    // Memproses login
    $loginSuccessful = $userAuthentication->validateLogin($username, $password);

    // Verifikasi login dan jika berhasil
    if ($loginSuccessful) {
        // Ambil ID pengguna dari database berdasarkan email
        $id = $userAuthentication->getUserID($username);
        if ($id !== null) {
            // Atur sesi untuk pengguna
            $_SESSION['user_id'] = $id;
            // Redirect ke halaman to-do list
            header("Location: todolist.php");
            exit(); // Penting untuk menghentikan eksekusi skrip setelah header redirect
        } else {
            // Handle error if user ID is not found
            $loginError = "User ID not found.";
        }
        // Jika login gagal, set pesan kesalahan
        $loginError = "Incorrect email or password. Please try again.";
    }
}
?>

<!-- Formulir Login HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (!empty($loginError)) { ?>
        <p><?php echo $loginError; ?></p>
    <?php } ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
