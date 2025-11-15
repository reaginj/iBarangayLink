<?php
include "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

// get complaints with resident name
$query = mysqli_query($conn, "
    SELECT c.*, r.firstname, r.lastname
    FROM complaints c
    LEFT JOIN residents r ON c.resident_id = r.id
    ORDER BY c.created_at DESC
");
?>

<div class="content">
    <h2>Complaints</h2>

    <a href="add.php" style="padding:8px; background:#4A67FF; color:white; text-decoration:none;">+ Add Complaint</a>
    <br><br>

    <table border="1" cellpadding="8" width="100%">
        <tr style="background:#ddd;">
            <th>ID</th>
            <th>Resident</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td>
                    <?= htmlspecialchars($row['lastname'] . ", " . $row['firstname']) ?>
                </td>
                <td><?= htmlspecialchars($row['subject']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
                    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this complaint?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>

    </table>
</div>

<?php include "../../includes/footer.php"; ?>
