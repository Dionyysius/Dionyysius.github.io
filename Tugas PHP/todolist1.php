<?php
// Include file DatabaseConnection.php dan UserAuthentication.php
include 'DatabaseConnection.php';
include 'UserAuthentication.php';

// Memulai sesi
session_start();

$loginError = "";
// Membuat objek koneksi database
$dbConnection = new DatabaseConnection();

// Periksa koneksi 
if($dbConnection->conn->connect_error){
    die("Koneksi gagal: ".$dbConnection->conn->connect_error);
}

// Jika formulir telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil ID pengguna yang sesuai dari tabel pengguna setelah login
    // Pastikan $_SESSION['user_id'] sudah ada setelah pengguna berhasil login
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Handle addition of new task
        // if (isset($_POST['action']) && $_POST['action'] == 'add_task' && isset($_POST['new-task-input'])) {
        //     $task_name = $_POST['new-task-input'];

        //     // Query to insert new task into database
        //     $stmt = $dbConnection->conn->prepare("INSERT INTO tasks (user_id, task_name, is_completed) VALUES (1, ?, 0)");
        //     $stmt->bind_param("is", $user_id, $task_name);
        //     if ($stmt->execute()) {
        //         echo "Task added successfully";
        //     } else {
        //         echo "Error adding task: " . $dbConnection->conn->error;
        //     }
        //     $stmt->close();
        // }
        // Handle addition of new task
        if (isset($_POST['action']) && $_POST['action'] == 'add_task' && isset($_POST['new-task-input'])) {
            $task_name = $_POST['new-task-input'];

            // Debugging: Check POST data
            echo "Task name from form: " . $task_name . "<br>";

            // Query to insert new task into database
            $stmt = $dbConnection->conn->prepare("INSERT INTO tasks (user_id, task_name, is_completed) VALUES (1, ?, 0)");
            $stmt->bind_param("is", $user_id, $task_name);
            if ($stmt->execute()) {
                echo "Task added successfully";
            } else {
                // Debugging: Display database error
                echo "Error adding task: " . $dbConnection->conn->error;
            }
            $stmt->close();
        }


        // Handle deletion of a task
        if (isset($_POST['action']) && $_POST['action'] == 'delete_task' && isset($_POST['task_id'])) {
            $task_id = $_POST['task_id'];

            // Query to delete task from database
            $stmt = $dbConnection->conn->prepare("DELETE FROM tasks WHERE id = ?");
            $stmt->bind_param("i", $task_id);
            if ($stmt->execute()) {
                echo "Task deleted successfully";
            } else {
                echo "Error deleting task: " . $dbConnection->conn->error;
            }
            $stmt->close();
        }

        // Handle marking a task as completed
        if (isset($_POST['action']) && $_POST['action'] == 'mark_completed' && isset($_POST['task_id'])) {
            $task_id = $_POST['task_id'];

            // Query to update task status to completed
            $stmt = $dbConnection->conn->prepare("UPDATE tasks SET is_completed = 1 WHERE id = ?");
            $stmt->bind_param("i", $task_id);
            if ($stmt->execute()) {
                echo "Task marked as completed";
            } else {
                echo "Error marking task as completed: " . $dbConnection->conn->error;
            }
            $stmt->close();
        }
    } else {
        echo "Sesi pengguna tidak valid.";
    }

    // Tutup koneksi database
    $dbConnection->conn->close();
}

// Set user session after successful authentication
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle user authentication
    // Example: You might have a function like authenticateUser() which sets $_SESSION['user_id'] upon successful login
    // Authenticate the user and set $_SESSION['user_id']
    $authenticated_user_id = authenticateUser(); // Example function, replace it with your actual authentication logic
    if ($authenticated_user_id) {
        $_SESSION['user_id'] = $authenticated_user_id;
        $user_id = $authenticated_user_id;
    } else {
        // Set error message for login failure
        $loginError = "Invalid username or password";
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List 2024</title>
    <link rel="stylesheet" href="todo.css">
</head>
<body>
    <header>
        <h1>Task List 2024</h1>
        <form id="new-task-form">
            <input type="text" name="new-task-input" id="new-task-input" placeholder="What do you have planned?">
            <input type="submit" id="new-task-submit" value="Add task" name="add-task">
        </form>
    </header>
    <main>
        <section class="task-list">
            <h2>Tasks</h2>
            <div id="tasks"></div>
        </section>
    </main>
    <script src="todo.js"></script>
</body>
</html>
