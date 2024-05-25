<?php
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Include DatabaseConnection.php file
include 'DatabaseConnection.php';

// Create an instance of DatabaseConnection
$dbConnection = new DatabaseConnection();

// Check database connection
if ($dbConnection->conn->connect_error) {
    die("Connection failed: " . $dbConnection->conn->connect_error);
}

// Retrieve tasks for the logged-in user
$user_id = $_SESSION['user_id'];
$stmt = $dbConnection->conn->prepare("SELECT id, task_name, is_completed FROM tasks WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$tasks = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Close database connection
$dbConnection->conn->close();
?>

<!-- HTML untuk Menampilkan Todolist -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
</head>
<body>
    <h2>Todo List</h2>
    <ul>
        <?php foreach ($tasks as $task) { ?>
            <li>
                <?php echo $task['task_name']; ?>
                <?php if ($task['is_completed']) { ?>
                    <span>(Completed)</span>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>
    <a href="logout.php">Logout</a>
</body>
</html>
