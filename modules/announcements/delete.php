<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: /iBarangayLink/login.php");
    exit();
}

include "../../config/db.php";

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM announcements WHERE id = $id");

add_log(
    $_SESSION['userid'],
    "Deleted Announcement",
    "Announcement ID $id was deleted"
);


header("Location: view.php");
exit();
?>
