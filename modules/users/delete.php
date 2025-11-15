<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: /iBarangayLink/login.php");
    exit();
}

if ($_SESSION['role'] != 'admin') {
    die("Access denied.");
}

include "../../config/db.php";

$id = $_GET['id'];

// prevent deleting yourself
if ($id == $_SESSION['userid']) {
    header("Location: view.php");
    exit();
}

mysqli_query($conn, "DELETE FROM users WHERE id = $id");

add_log(
    $_SESSION['userid'],
    "Deleted User",
    "User ID $id was deleted"
);

header("Location: view.php");
exit();
?>
