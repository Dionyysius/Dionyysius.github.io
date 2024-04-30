<?php


// Include file DatabaseConnection.php and UserAuthentication.php
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
        // $id = $userAuthentication->getUserID($username);
        // if ($id !== null) {
            // Atur sesi untuk pengguna
            session_start();
            $_SESSION['user_id'] = $id; // Fix: Use $id instead of $user_id
            // Redirect ke halaman to-do list
            header("Location: todolistt.php"); // Fix: Correct redirection page
            exit(); // Penting untuk menghentikan eksekusi skrip setelah header redirect
        // } else {
        //     // Handle error if user ID is not found
        //     $loginError = "User ID not found.";
        // }
    } else {
        // Jika login gagal, set pesan kesalahan
        $loginError = "Incorrect email or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
</head>
<body class="container">
<div class="mask d-flex align-items-center h-100 gradient-custom-3">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                <div class="card" style="border-radius: 15px;">
                    <div class="card-body p-5">
                        <!-- Tampilkan pesan kesalahan -->
                        <?php if (!empty($loginError)) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $loginError; ?>
                            </div>
                        <?php } ?>

                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <!-- Email input -->
                            <div class="form-outline mb-4">
                                <input type="email" name="email" id="form2Example1" class="form-control"/>
                                <label class="form-label" for="form2Example1">Email address</label>
                            </div>

                            <!-- Password input -->
                            <div class="form-outline mb-4">
                                <input type="password" name="password" id="form2Example2" class="form-control"/>
                                <label class="form-label" for="form2Example2">Password</label>
                            </div>

                            <!-- Submit button -->
                            <button type="submit" class="btn btn-primary btn-block mb-4">LOGIN</button>

                            <!-- Register buttons -->
                            <div class="text-center">
                                <p>Not a member? <a href="SignUp.php">Register</a></p>
                                <p>or sign up with:</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
