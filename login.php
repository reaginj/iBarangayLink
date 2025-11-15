<?php
session_start();
require_once "config/db.php";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        $_SESSION['userid'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid login. Try again.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>iBarangay Link - Login</title>
    <link rel="stylesheet" href="/iBarangayLink/assets/css/login.css">
</head>
<body>

<div class="login-box">
    <h2>iBarangay Link</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="login">Login</button>
    </form>
</div>

</body>
</html>
