<?php
class UserAuthentication {
    private $conn;

    // Constructor yang menerima objek koneksi database
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

   // Metode untuk memproses login
public function processLogin($username, $password) {
    // Retrieve user data from the database based on the provided username
    $stmt = $this->conn->prepare("SELECT id, username, password FROM userdata WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if a user with the provided username exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $db_username, $db_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $db_password)) {
            // Password is correct, set the user's session
            $_SESSION['user_id'] = $id;
            // Redirect the user to the next page
            header("Location: todolistt.php");
            exit();
        } else {
            // Password is incorrect, display an error message
            return "Incorrect password";
        }
    } else {
        // User with the provided username does not exist, display an error message
        return "User not found";
    }

    $stmt->close();
}

    
    public function validateLogin($username, $password) {
        // Hindari penggunaan kueri SQL langsung seperti ini karena rentan terhadap serangan SQL injection
        // Gunakan metode parameterized query atau ORM untuk menghindari masalah keamanan
        $query = "SELECT * FROM userdata WHERE username = '$username' AND password = '$password'";
    
        // Jalankan kueri ke database
        $result = $this->conn->query($query);
    
        // Periksa apakah hasil kueri mengembalikan setidaknya satu baris
        if ($result && $result->num_rows > 0) {
            // Jika hasilnya ada, berarti login berhasil
            return true;
        } else {
            // Jika tidak, berarti login gagal
            return false;
        }
    }

    public function getUserID($username) {
        // Query the database to get the user ID based on the email
        $sql = "SELECT id FROM userdata WHERE username = '$username'";
        $result = $this->conn->query($sql);
    
        // Check if the query was successful
        if ($result && $result->num_rows > 0) {
            // Fetch the user ID from the result
            $row = $result->fetch_assoc();
            return $row['id'];
        } else {
            // Return null if no user ID found
            return null;
        }
    }
    
}



?>
