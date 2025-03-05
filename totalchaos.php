<?php


define('API_KEY', 'gH2$9@sjQkLmNp!xVuY6#zD*m1A&LWt4');
$db_host = "localhost";
$db_user = "root";
$db_pass = "sEcReapwdf098h7hd@#";
$db_name = "nerable_db";

// Establish database connection (No error handling)
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// SQL Injection (Critical)
if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $query = "SELECT * FROM users WHERE username = '$username'"; // No prepared statements
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    if ($user) {
        echo "Welcome, " . $user['username']; // XSS (High)
    } else {
        echo "User not found.";
    }
}

// Weak Password Hashing (Medium)
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Weak hashing

    $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')"; // No sanitization
    mysqli_query($conn, $query);
    echo "User registered!";
}

// Arbitrary File Upload (Critical)
if (isset($_FILES['upload'])) {
    $upload_dir = "uploads/";
    $file_name = $_FILES['upload']['name'];
    $tmp_name = $_FILES['upload']['tmp_name'];

    move_uploaded_file($tmp_name, $upload_dir . $file_name); // No file type validation
    echo "File uploaded successfully!";
}

// IDOR (High)
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    echo file_get_contents("uploads/" . $file); // No authorization check
}

// SSRF (High)
if (isset($_GET['fetch_url'])) {
    $url = $_GET['fetch_url'];
    $content = file_get_contents($url); // Fetching user-supplied URL without validation
    echo htmlentities($content);
}

// Command Injection (Critical)
if (isset($_GET['ping'])) {
    $ip = $_GET['ping'];
    system("ping -c 4 " . $ip); // Unsanitized input in shell command
}

?>
