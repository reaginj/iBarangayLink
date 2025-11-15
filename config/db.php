<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "ibarangay_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function add_log($user_id, $action, $description) {
    global $conn;
    $action = mysqli_real_escape_string($conn, $action);
    $description = mysqli_real_escape_string($conn, $description);
    mysqli_query($conn, "
        INSERT INTO audit_logs (user_id, action, description)
        VALUES ('$user_id', '$action', '$description')
    ");
}

?>
