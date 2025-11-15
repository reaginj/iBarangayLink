<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: /iBarangayLink/login.php");
    exit();
}

include "../../config/db.php";

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM service_requests WHERE id = $id");

add_log(
    $_SESSION['userid'],
    "Deleted Service Request",
    "Service Request ID $id was deleted"
);


header("Location: view.php");
exit();
?>
