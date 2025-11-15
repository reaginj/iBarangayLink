<?php
include "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

if ($_SESSION['role'] != 'admin') {
    echo "<div class='content'><h2>Access Denied</h2><p>Admins only.</p></div>";
    include "../../includes.footer.php";
    exit();
}

$error = '';

if (isset($_POST['save'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role     = $_POST['role'];

    // basic validation
    if ($username == '' || $password == '') {
        $error = "Username and password are required.";
    } else {
        // check if username exists
        $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' LIMIT 1");
        if (mysqli_num_rows($check) > 0) {
            $error = "Username already exists.";
        } else {
            $hashed = md5($password); // same as login

            mysqli_query($conn, "
                INSERT INTO users (username, password, role)
                VALUES ('$username', '$hashed', '$role')
            ");

            $new_user_id = mysqli_insert_id($conn);
            add_log(
                $_SESSION['userid'],
                "Added User",
                "User ID $new_user_id with role $role was added"
            );
            

            header("Location: view.php");
            exit();
        }
    }
}
?>

<div class="content">
    <h2>Add User</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Role:</label><br>
        <select name="role">
            <option value="admin">Admin</option>
            <option value="staff">Staff</option>
        </select>
        <br><br>

        <button type="submit" name="save">Save User</button>
    </form>
</div>

<?php include "../../includes/footer.php"; ?>
