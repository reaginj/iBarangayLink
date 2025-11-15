<?php
include "../../config/db.php";

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM residents WHERE id=$id");

add_log(
    $_SESSION['userid'],
    "Deleted Resident",
    "Resident ID $id was deleted"
);


header("Location: view.php");
exit();
?>
