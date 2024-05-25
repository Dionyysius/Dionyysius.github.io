<?php
//error login

// Include file DatabaseConnection.php dan UserAuthentication.php
include 'DatabaseConnection.php';
include 'UserAuthentication.php';

// Mulai sesi
session_start();

$loginError = "";
// Membuat objek koneksi database
$dbConnection = new DatabaseConnection();

// Periksa koneksi
if($dbConnection->conn->connect_error){
    die("Koneksi gagal: ".$dbConnection->conn->connect_error);
}

// Handle addition of new task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    // Ambil aksi dari formulir
    $action = $_POST['action'];

    if ($action == 'add_task') {
        $task_name = $_POST['task_name'];
        // Ambil ID pengguna yang sesuai dari tabel pengguna setelah login
        // Misalnya, jika ID pengguna disimpan dalam variabel $_SESSION setelah login
        $user_id = $_SESSION['user_id'];
        
        // Query untuk memasukkan tugas baru ke dalam database
        $sql = "INSERT INTO tasks (task_name, user_id, is_completed) VALUES ('$task_name', $user_id, 0)";
        $result = $dbConnection->conn->query($sql);

        // Periksa jika penambahan berhasil
        if ($result) {
            echo "Task added successfully";
        } else {
            echo "Error adding task: " . $dbConnection->conn->error;
        }
    } else if ($action == 'delete_task') {
        $task_id = $_POST['task_id'];

        // Query untuk menghapus tugas dari database
        $sql = "DELETE FROM tasks WHERE id = $task_id";
        $result = $dbConnection->conn->query($sql);

        // Periksa jika penghapusan berhasil
        if ($result) {
            echo "Task deleted successfully";
        } else {
            echo "Error deleting task: " . $dbConnection->conn->error;
        }
    } else if ($action == 'mark_completed') {
        $task_id = $_POST['task_id'];

        // Query untuk memperbarui status tugas menjadi selesai di database
        $sql = "UPDATE tasks SET is_completed = 1 WHERE id = $task_id";
        $result = $dbConnection->conn->query($sql);

        // Periksa jika pembaruan berhasil
        if ($result) {
            echo "Task marked as completed";
        } else {
            echo "Error marking task as completed: " . $dbConnection->conn->error;
        }
    }
}


// Ambil ID pengguna yang sesuai dari tabel pengguna setelah login
// Misalnya, jika ID pengguna disimpan dalam variabel $_SESSION setelah login
$user_id = $_SESSION['user_id'];

// Ambil semua tugas yang terkait dengan pengguna dari tabel tugas
$sql = "SELECT * FROM tasks WHERE user_id = $user_id";
$result = $dbConnection->conn->query($sql);

echo "<div id='tasks'>";
if ($result->num_rows > 0) {
    // Tampilkan tugas-tugas tersebut dalam halaman to-do list
    while($row = $result->fetch_assoc()) {
        $task_id = $row['id'];
        $task_name = $row["task_name"];
        $is_completed = $row["is_completed"];
        $completed_class = $is_completed ? 'completed' : '';
        echo "<div class='task $completed_class' data-task-id='$task_id'>" . $task_name . "</div>";
    }
} else {
    echo "Tidak ada tugas untuk ditampilkan.";
}
echo "</div>";

// Tutup koneksi database
$dbConnection->conn->close();
?>

<?php
// Include file DatabaseConnection.php dan UserAuthentication.php
include 'DatabaseConnection.php';
include 'UserAuthentication.php';

//memulai sesi 
session_start();


$loginError = "";
//membuat object kponeksi database
$dbConnection = new DatabaseConnection();

//periksa koneksi 
if($dbConnection->conn->connect_error){
    die("Koneksi gagal: ".$dbConnection->conn->connect_error);
}

// Jika formulir telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Membuat objek koneksi database
        
    // Ambil ID pengguna yang sesuai dari tabel pengguna setelah login
    // Misalnya, jika ID pengguna disimpan dalam variabel $_SESSION setelah login
    $user_id = $_SESSION['user_id'];

    // Ambil semua tugas yang terkait dengan pengguna dari tabel tugas
    $sql = "SELECT * FROM tasks WHERE user_id = $user_id";
    $result = $dbConnection->$conn->query($sql);

    if ($result->num_rows > 0) {
        // Tampilkan tugas-tugas tersebut dalam halaman to-do list
        while($row = $result->fetch_assoc()) {
            echo "<div class='task'>" . $row["task_name"] . "</div>";
        }
    } else {
        echo "Tidak ada tugas untuk ditampilkan.";
    }


    // Handle addition of new task
    if (isset($_POST['action']) && $_POST['action'] == 'add_task') {
        $task_name = $_POST['task_name'];

        // Query to insert new task into database
        $sql = "INSERT INTO tasks (task_name, is_completed) VALUES ('$task_name', 0)";
        $result = $dbConnection->$conn->query($sql);

        // Check if insertion was successful
        if ($result) {
            echo "Task added successfully";
        } else {
            echo "Error adding task: " . $dbConnection->$conn->error;
        }
    }

    // Handle deletion of a task
    if (isset($_POST['action']) && $_POST['action'] == 'delete_task') {
        $task_id = $_POST['task_id'];

        // Query to delete task from database
        $sql = "DELETE FROM tasks WHERE id = $task_id";
        $result = $dbConnection->$conn->query($sql);

        // Check if deletion was successful
        if ($result) {
            echo "Task deleted successfully";
        } else {
            echo "Error deleting task: " . $conn->error;
        }
    }
    //jangan lupa call the object 
    // Handle marking a task as completed
    if (isset($_POST['action']) && $_POST['action'] == 'mark_completed') {
        $task_id = $_POST['task_id'];

        // Query to update task status to completed
        $sql = "UPDATE tasks SET is_completed = 1 WHERE id = $task_id";
        $result = $dbConnection->$conn->query($sql);

        // Check if update was successful
        if ($result) {
            echo "Task marked as completed";
        } else {
            echo "Error marking task as completed: " . $dbConnection->$conn->error;
        }
    }

    //tutup data base
    $dbConnection->$conn->close();
?>


<?php
//sebelim sesion diaktifkan 

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
    // Misalnya, jika ID pengguna disimpan dalam variabel $_SESSION setelah login
    $user_id = $_SESSION['user_id'];

    // Ambil semua tugas yang terkait dengan pengguna dari tabel tugas
    $sql = "SELECT * FROM tasks WHERE user_id = $user_id";
    $result = $dbConnection->conn->query($sql);

    if ($result->num_rows > 0) {
        // Tampilkan tugas-tugas tersebut dalam halaman to-do list
        while($row = $result->fetch_assoc()) {
            echo "<div class='task'>" . $row["task_name"] . "</div>";
        }
    } else {
        echo "Tidak ada tugas untuk ditampilkan.";
    }

    // Handle addition of new task
    if (isset($_POST['action']) && $_POST['action'] == 'add_task') {
        $task_name = $_POST['new-task-input']; // Perhatikan penanganan input form di sini

        // Query to insert new task into database
        $sql = "INSERT INTO tasks (task_name, is_completed, user_id) VALUES ('$task_name', 0, $user_id)";
        $result = $dbConnection->conn->query($sql);

        // Check if insertion was successful
        if ($result) {
            echo "Task added successfully";
        } else {
            echo "Error adding task: " . $dbConnection->conn->error;
        }
    }

    // Handle deletion of a task
    if (isset($_POST['action']) && $_POST['action'] == 'delete_task') {
        $task_id = $_POST['task_id'];

        // Query to delete task from database
        $sql = "DELETE FROM tasks WHERE id = $task_id";
        $result = $dbConnection->conn->query($sql);

        // Check if deletion was successful
        if ($result) {
            echo "Task deleted successfully";
        } else {
            echo "Error deleting task: " . $dbConnection->conn->error;
        }
    }

    // Handle marking a task as completed
    if (isset($_POST['action']) && $_POST['action'] == 'mark_completed') {
        $task_id = $_POST['task_id'];

        // Query to update task status to completed
        $sql = "UPDATE tasks SET is_completed = 1 WHERE id = $task_id";
        $result = $dbConnection->conn->query($sql);

        // Check if update was successful
        if ($result) {
            echo "Task marked as completed";
        } else {
            echo "Error marking task as completed: " . $dbConnection->conn->error;
        }
    }

    // Tutup koneksi database
    $dbConnection->conn->close();
}
?>

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
        if (isset($_POST['action']) && $_POST['action'] == 'add_task' && isset($_POST['new-task-input'])) {
            $task_name = $_POST['new-task-input'];

            // Query to insert new task into database
            $stmt = $dbConnection->conn->prepare("INSERT INTO tasks (user_id, task_name, is_completed) VALUES (?, ?, 0)");
            $stmt->bind_param("is", $user_id, $task_name);
            if ($stmt->execute()) {
                echo "Task added successfully";
            } else {
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


