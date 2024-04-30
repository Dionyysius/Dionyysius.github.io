<?php


session_start();
// Include DatabaseConnection.php and UserAuthentication.php files
include 'DatabaseConnection.php';
include 'UserAuthentication.php';

// Check if the user is not logged in, redirect to login page
// if (!isset($_SESSION["login"])) {
//     header("Location: loginnn.php");
//     exit();
// }

// Check if the user is logged in
if (isset($_SESSION["loginnn"])) {
    // Set session login to true after successful login
    $_SESSION["loginnn"] = true;
} else {
    // Redirect to login page if user session is not set
    header("Location: loginnn.php");
    exit();
}

// Create an instance of DatabaseConnection
$dbConnection = new DatabaseConnection();

// Check database connection
if ($dbConnection->conn->connect_error) {
    die("Connection failed: " . $dbConnection->conn->connect_error);
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user session exists
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Handle addition of new task
        if (isset($_POST['action']) && $_POST['action'] == 'add_task' && isset($_POST['new-task-input'])) {
            $task_name = $_POST['new-task-input'];

            // Query to insert new task into database
            $stmt = $dbConnection->conn->prepare("INSERT INTO tasks (user_id, task_name, is_completed) VALUES (?, ?, 0)");
            $stmt->bind_param("is", $user_id, $task_name);
            if ($stmt->execute()) {
                echo "Task added successfully";
            } else {
                echo "Error adding task: " . $stmt->error;
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
                echo "Error deleting task: " . $stmt->error;
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
                echo "Error marking task as completed: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        // Invalid user session, handle appropriately (redirect or display error message)
        header("Location: loginnn.php"); // Redirect to login page
        exit();
    }
}

// Close database connection
$dbConnection->conn->close();
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
        <form id="new-task-form" method="post">
            <input type="hidden" name="action" value="add_task">
            <input type="text" name="new-task-input" id="new-task-input" placeholder="What do you have planned?">
            <input type="submit" id="new-task-submit" value="Add task" name="add-task">
        </form>
        <form method="post">
            <input type="hidden" name="logout">
            <button type="submit">Logout</button>
        </form>
    </header>
    <main>
        <section class="task-list">
            <h2>Tasks</h2>
            <div id="tasks">
                <!-- Tasks will be displayed here -->
            </div>
        </section>
    </main>
    <script src="todo.js"></script>
</body>
</html>
