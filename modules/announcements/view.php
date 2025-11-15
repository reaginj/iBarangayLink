<?php
include "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

$query = mysqli_query($conn, "
    SELECT * FROM announcements
    ORDER BY publish_date DESC, created_at DESC
");
?>

<div class="content">
    <h2>Announcements</h2>

    <a href="add.php" style="padding:8px; background:#4A67FF; color:white; text-decoration:none;">
        + Add Announcement
    </a>
    <br><br>

    <table border="1" cellpadding="8" width="100%">
        <tr style="background:#ddd;">
            <th>ID</th>
            <th>Title</th>
            <th>Publish Date</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= $row['publish_date'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
                    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this announcement?')">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>

    </table>
</div>

<?php include "../../includes/footer.php"; ?>
