<?php
if (!isset($_SESSION)) { session_start(); }
if (!isset($_SESSION['userid'])) {
    // always redirect to the correct login path from anywhere
    header("Location: /iBarangayLink/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>iBarangay Link</title>
    <!-- use ABSOLUTE path from project root -->
    <link rel="stylesheet" href="/iBarangayLink/assets/css/style.css">
</head>
<body>
<div class="wrapper">
