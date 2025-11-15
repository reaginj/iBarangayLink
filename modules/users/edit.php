<?php
include "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

if ($_SESSION['role'] != 'admin') {
    echo "<div class='content'><h2>Access Denied</h2><p>Admins only.</p></div>";
    include "../../includes/footer.php";
    exit();
}

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM users WHERE id = $id LIMIT 1");
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "<div class='content'><p>User not found.</p></div>";
    include "../../includes/footer.php";
    exit();
}

$error = '';

if (isset($_POST['update'])) {
    $username = trim($_POST['username']);
    $role     = $_POST['role'];
    $password = $_POST['password']; // optional

    if ($username == '') {
        $error = "Username is required.";
    } else {
        // check if username taken by another
        $check = mysqli_query($conn, "
            SELECT id FROM users 
            WHERE username='$username' AND id != $id
            LIMIT 1
        ");
        if (mysqli_num_rows($check) > 0) {
            $error = "Username already in use by another account.";
        } else {
            if ($password != '') {
                $hashed = md5($password);
                mysqli_query($conn, "
                    UPDATE users 
                    SET username='$username', role='$role', password='$hashed'
                    WHERE id=$id
                ");
            } else {
                mysqli_query($conn, "
                    UPDATE users 
                    SET username='$username', role='$role'
                    WHERE id=$id
                ");
            }

             add_log(
                $_SESSION['userid'],
                "Updated User",
                "User ID $id was updated (username: $username, role: $role)"
            );

            // if you edited yourself, update session username/role
            if ($id == $_SESSION['userid']) {
                $_SESSION['username'] = $username;
                $_SESSION['role']     = $role;
            }

            header("Location: view.php");
            exit();
        }
    }
}
?>

<div class="content">
    <h2>Edit User</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required value="<?= htmlspecialchars($user['username']) ?>"><br><br>

        <label>Role:</label><br>
        <select name="role">
            <option value="admin" <?= $user['role']=='admin' ? 'selected' : '' ?>>Admin</option>
            <option value="staff" <?= $user['role']=='staff' ? 'selected' : '' ?>>Staff</option>
        </select>
        <br><br>

        <label>New Password (leave blank to keep current):</label><br>
        <input type="password" name="password"><br><br>

        <button type="submit" name="update">Update User</button>
    </form>
</div>

<?php include "../../includes/footer.php"; ?>
