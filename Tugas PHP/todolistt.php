<?php
// Mulai session
session_start();

// Include DatabaseConnection.php dan UserAuthentication.php
include 'DatabaseConnection.php';
include 'UserAuthentication.php';

// Periksa jika pengguna belum masuk, redirect ke halaman login
if (!isset($_SESSION["login"])) {
    header("Location: login.php");  
    exit();
}

// Periksa jika pengguna sudah masuk
if (isset($_SESSION["user_id"])) {
    // Atur session login menjadi true setelah login berhasil
    $_SESSION["login"] = true;
} else {
    // Redirect ke halaman login jika session pengguna tidak diatur
    header("Location: login.php");
    exit();
}

// Handle logout
if (isset($_POST["logout"])) {
    // Hapus semua session
    session_unset();

    // Hancurkan session
    session_destroy();

    // Redirect ke halaman login setelah logout
    header("Location: login.php");
    exit();
}

// Buat instansiasi DatabaseConnection
$dbConnection = new DatabaseConnection();

// Periksa koneksi database
if ($dbConnection->conn->connect_error) {
    die("Connection failed: " . $dbConnection->conn->connect_error);
}

// Query untuk mengambil semua tugas untuk pengguna yang sedang masuk
$user_id = $_SESSION['user_id'];
$sql = "SELECT id, task_name, is_completed FROM tasks WHERE user_id = ?";
$stmt = $dbConnection->conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle pengiriman data melalui metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah session pengguna ada
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Handle penambahan tugas baru
        if (isset($_POST['action']) && $_POST['action'] == 'add_task' && isset($_POST['new-task-input'])) {
            $task_name = $_POST['new-task-input'];

            // Query untuk menyisipkan tugas baru ke dalam database
            $stmt = $dbConnection->conn->prepare("INSERT INTO tasks (user_id, task_name, is_completed) VALUES (?, ?, 0)");
            $stmt->bind_param("is", $user_id, $task_name);
            if ($stmt->execute()) {
                echo "Task added successfully";
            } else {
                echo "Error adding task: " . $stmt->error;
            }
            $stmt->close();
        }

        // Handle penghapusan tugas
        if (isset($_POST['action']) && $_POST['action'] == 'delete_task' && isset($_POST['task_id'])) {
            $task_id = $_POST['task_id'];

            // Query untuk menghapus tugas dari database
            $stmt = $dbConnection->conn->prepare("DELETE FROM tasks WHERE id = ?");
            $stmt->bind_param("i", $task_id);
            if ($stmt->execute()) {
                echo "Task deleted successfully";
            } else {
                echo "Error deleting task: " . $stmt->error;
            }
            $stmt->close();
        }

        // Handle penandaan tugas sebagai selesai
        if (isset($_POST['action']) && $_POST['action'] == 'mark_completed' && isset($_POST['task_id'])) {
            $task_id = $_POST['task_id'];

            // Query untuk memperbarui status tugas menjadi selesai
            $stmt = $dbConnection->conn->prepare("UPDATE tasks SET is_completed = 1 WHERE id = ?");
            $stmt->bind_param("i", $task_id);
            if ($stmt->execute()) {
                echo "Task marked as completed";
            } else {
                echo "Error marking task as completed: " . $stmt->error;
            }
            $stmt->close();
        }

        // Handle penyimpanan perubahan pada tugas yang diedit
        if (isset($_POST['action']) && $_POST['action'] == 'edit_task' && isset($_POST['task_id']) && isset($_POST['new_task_name'])) {
            $task_id = $_POST['task_id'];
            $new_task_name = $_POST['new_task_name'];

            // Query untuk memperbarui nama tugas dalam database
            $stmt = $dbConnection->conn->prepare("UPDATE tasks SET task_name = ? WHERE id = ?");
            $stmt->bind_param("si", $new_task_name, $task_id);
            if ($stmt->execute()) {
                echo "Task edited successfully";
            } else {
                echo "Error editing task: " . $stmt->error;
            }
            $stmt->close();
        }

    } else {
        // Session pengguna tidak valid, tangani dengan benar (redirect atau tampilkan pesan kesalahan)
        header("Location: login.php"); // Redirect ke halaman login
        exit();
    }
}

// Tutup koneksi database
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
    <style>
        .completed .text {
            text-decoration: line-through;
        }
    </style>
</head>
<body>
    <header>
        <h1>Task List 2024</h1>
        <form id="new-task-form" method="post">
            <input type="hidden" name="action" value="add_task">
            <input type="text" name="new-task-input" id="new-task-input" placeholder="What do you have planned?">
            <input type="submit" id="new-task-submit" value="Add task" name="add-task">
        </form>
        <form method="post" class="logout-form">
            <input type="hidden" name="logout" value="1">
            <button type="submit" class="logout-button">Logout</button>
        </form>

    </header>
    <main>
        <section class="task-list">
            <h2>Tasks</h2>
            <div id="tasks">
                <?php
                while ($row = $result->fetch_assoc()) {
                    // Tentukan status tugas
                    $status = $row['is_completed'] ? 'Completed' : 'Pending';
                    // Tampilkan baris tugas
                    echo "<div class='task " . ($row['is_completed'] ? 'completed' : '') . "' data-task-id='" . $row['id'] . "'>";
                    echo "<div class='content'>";
                    echo "<input class='text' type='text' value='" . $row['task_name'] . "' readonly>";
                    echo "</div>";
                    echo "<div class='actions'>";
                    // Tombol Edit
                    echo "<button class='edit'>Edit</button>";
                    // Tombol Done
                    echo "<button class='done'>" . ($row['is_completed'] ? 'Completed' : 'Done') . "</button>";
                    // Tombol Delete
                    echo "<button class='delete'>Delete</button>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </div>
        </section>
    </main>

    <script>
       document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#new-task-form');
    const input = document.querySelector('#new-task-input');
    const tasksDiv = document.querySelector('#tasks');

    // Fungsi untuk mengirim permintaan POST ke server dan menangani respons
    function sendRequest(url, bodyParams, successCallback) {
        fetch(url, {
            method: 'POST',
            body: new URLSearchParams(bodyParams)
        })
        .then(response => response.text())
        .then(data => {
            if (data.startsWith('Task')) {
                successCallback(data);
            } else {
                console.error(data);
            }
        });
    }

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        if (input.value.trim()) {
            const task = input.value.trim();
            sendRequest('<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', {
                action: 'add_task',
                'new-task-input': task
            }, (data) => {
                const taskEl = createTaskElement(task);
                tasksDiv.appendChild(taskEl);
                input.value = '';
            });
        }
    });

    tasksDiv.addEventListener('click', (e) => {
        if (e.target.tagName === 'BUTTON') {
            const button = e.target;
            const taskEl = button.closest('.task');
            const taskId = taskEl.dataset.taskId;

            if (button.classList.contains('delete')) {
                sendRequest('<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', {
                    action: 'delete_task',
                    task_id: taskId
                }, () => {
                    taskEl.remove();
                });
            } else if (button.classList.contains('done')) {
                sendRequest('<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', {
                    action: 'mark_completed',
                    task_id: taskId
                }, () => {
                    taskEl.classList.toggle('completed');
                    button.innerText = 'Completed';
                    button.classList.add('completed');
                    button.disabled = true;
                });
            } else if (button.classList.contains('edit')) {
                const taskInput = taskEl.querySelector('.text');
                taskInput.removeAttribute('readonly');
                taskInput.focus();
                button.innerText = 'Save';
                button.classList.add('save');
                button.classList.remove('edit');
            } else if (button.classList.contains('save')) {
                const taskInput = taskEl.querySelector('.text');
                const newTaskName = taskInput.value.trim();
                if (newTaskName) {
                    sendRequest('<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', {
                        action: 'edit_task',
                        task_id: taskId,
                        new_task_name: newTaskName
                    }, () => {
                        taskInput.setAttribute('readonly', 'readonly');
                        button.innerText = 'Edit';
                        button.classList.add('edit');
                        button.classList.remove('save');
                    });
                }
            }
        }
    });

    function createTaskElement(task) {
        const taskEl = document.createElement('div');
        taskEl.classList.add('task');
        taskEl.dataset.taskId = generateUniqueId();

        const taskContentEl = document.createElement('div');
        taskContentEl.classList.add('content');

        const taskInputEl = document.createElement('input');
        taskInputEl.classList.add('text');
        taskInputEl.type = 'text';
        taskInputEl.value = task;
        taskInputEl.setAttribute('readonly', 'readonly');

        taskContentEl.appendChild(taskInputEl);
        taskEl.appendChild(taskContentEl);

        const taskActionsEl = document.createElement('div');
        taskActionsEl.classList.add('actions');

        const taskEditEl = document.createElement('button');
        taskEditEl.classList.add('edit');
        taskEditEl.innerText = 'Edit';

        const taskDeleteEl = document.createElement('button');
        taskDeleteEl.classList.add('delete');
        taskDeleteEl.innerText = 'Delete';

        const taskDoneEl = document.createElement('button');
        taskDoneEl.classList.add('done');
        taskDoneEl.innerText = 'Done';

        taskActionsEl.appendChild(taskEditEl);
        taskActionsEl.appendChild(taskDoneEl);
        taskActionsEl.appendChild(taskDeleteEl);

        taskEl.appendChild(taskActionsEl);

        return taskEl;
    }

    function generateUniqueId() {
        return Date.now().toString(36) + Math.random().toString(36).substr(2);
    }
});

    </script>
</body>
</html>
