<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}
require 'koneksi.php';
$error = '';
$kls = 'b';
$username = $_SESSION['user'];
if (isset($_POST['tambah'])) {
    if (!empty($_POST['todo'])) {
        tambahTodo($_POST, $username);
        header("Location: todoList.php");
        exit();
    } else {
        echo "<script> 
                  alert('data harus diisi');
                  </script>";
    }
}

foreach ($_POST as $key => $value) {
    if (strpos($key, 'hapus') !== false) {
        $index = substr($key, strlen('hapus'));
        hapus($_POST, $index);
    }
}


foreach ($_POST as $key => $value) {
    if (strpos($key, 'selesai') !== false) {
        $index = substr($key, strlen('selesai'));
        selesai($_POST, $index);
    }
}

if (isset($_POST['logout'])) {
    logout();
}

$text = query($username);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TodoList</title>
    <link rel="stylesheet" href="todo.css">
</head>

<body>
    <form action="" method="post">

        <div class="tambah-todo">
            <input type="text" name="todo" placeholder="Teks todo" class="input" onkeydown="return event.key != 'Enter';">
            <button class="tambah" name="tambah" class="tambah"> Tambah</button>
            <h2 class="info"><?php echo $username; ?></h2>
            <button class="logout" name="logout" class="logout"> logout</button>
        </div>

        <div class="tampil">
            <?php foreach ($text as $index => $isi) : ?>
                <div class="show-todo">
                    <input type="text" name="isi<?php echo $index; ?>" value="<?php echo $isi['todolist']; ?>" class="<?php echo $isi['status']; ?>" id="show" readonly onkeydown="return event.key != 'Enter';">
                    <button class="sel" name="selesai<?php echo $index; ?>">Selesai</button>
                    <button class="hapus" name="hapus<?php echo $index; ?>">Hapus</button>
                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                </div>
            <?php endforeach; ?>
        </div>
    </form>
</body>
<?php

?>

</html>