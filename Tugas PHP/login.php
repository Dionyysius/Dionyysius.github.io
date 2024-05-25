<?php
session_start();
include 'DatabaseConnection.php';


$dbConnection = new DatabaseConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the database connection is available
    if ($dbConnection && $dbConnection->conn) {
        // Check if the prepared statement can be created
        $stmt = $dbConnection->conn->prepare("SELECT * FROM users WHERE username = ?");
        if ($stmt) {
            // Bind parameters and execute the statement
            $stmt->bind_param("s", $username);
            $stmt->execute();

            // Get the result
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    // Authentication successful, set session variables
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $username;
                    $_SESSION['login'] = true;
                    header("Location: todolistt.php");
                    exit();
                } else {
                    echo "Invalid password!";
                }
            } else {
                echo "Username not found!";
            }

            // Close the prepared statement
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $dbConnection->conn->error;
        }

        // Close the database connection
        $dbConnection->conn->close();
    } else {
        echo "Database connection error!";
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="Login.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="card">
        <img src="IMG_0140..jpg" class="img-fluid rounded-circle" alt="Foto Profil">
        <h1 class="display-4 mt-4">Herodion Yulis Putra Anugrah</h1>
        <p class="lead">NIM: 225314001</p>
    </div>
    <div class="container mt-5">
        <h2>Login</h2>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" placeholder="Enter username" name="username">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <p class="mt-3">Don't have an account? <a href="register.php">Register here</a></p>
        </form>
    </div>
</body>
</html>

