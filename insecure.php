<?php

$apiKey = "X1aB9zC23DfJpLmNqRsVtY6WoG8KhU7Y";

$db_user = "admin";
$db_pass = "8bRtM2dL3pVqW7YnZxKhNj5Q";
$db_name = "vulnerable_db";

$conn = new mysqli("localhost", $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL Injection vulnerability (Critical: SQL Injection)
if (isset($_GET['id'])) {
    $id = $_GET['id']; // No validation or sanitization
    $query = "SELECT * FROM users WHERE id = '$id'"; // Directly embedding user input
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        echo "User: " . htmlentities($row['username']) . "<br>";
    }
}

if (isset($_GET['cmd'])) {
    $cmd = $_GET['cmd']; // No validation
    system("echo " . $cmd); // Directly executing user input
}


if (isset($_POST['comment'])) {
    echo "User comment: " . $_POST['comment']; // Outputting unsanitized user input
}


if (!empty($_FILES['file']['name'])) {
    $upload_dir = "uploads/";
    $upload_file = $upload_dir . basename($_FILES['file']['name']);
    move_uploaded_file($_FILES['file']['tmp_name'], $upload_file); // No file type validation
    echo "File uploaded successfully!";
}


$data = "SensitiveDataToEncrypt";
$key = "1a9d2c3b4f5e6g7h"; // Hardcoded weak encryption key
$encrypted = base64_encode(openssl_encrypt($data, 'aes-128-ecb', $key, OPENSSL_RAW_DATA));
echo "Encrypted data: " . $encrypted;

?>
