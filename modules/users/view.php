<?php
include "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

if ($_SESSION['role'] != 'admin') {
    echo "<div class='content'><h2>Access Denied</h2><p>Admins only.</p></div>";
    include "../../includes/footer.php";
    exit();
}

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
?>

<div class="content">
    <h2>Users & Roles</h2>

    <a href="add.php" style="padding:8px; background:#4A67FF; color:white; text-decoration:none;">
        + Add User
    </a>
    <br><br>

    <table border="1" cellpadding="8" width="100%">
        <tr style="background:#ddd;">
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>

        <?php while($u = mysqli_fetch_assoc($users)): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['role']) ?></td>
                <td><?= $u['created_at'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $u['id'] ?>">Edit</a>
                    <?php if ($u['id'] != $_SESSION['userid']): ?>
                        | <a href="delete.php?id=<?= $u['id'] ?>"
                             onclick="return confirm('Delete this user?')">Delete</a>
                    <?php else: ?>
                        | (You)
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include "../../includes/footer.php"; ?>
