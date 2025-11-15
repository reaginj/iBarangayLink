<?php
include "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

// join with residents
$query = mysqli_query($conn, "
    SELECT s.*, r.firstname, r.lastname
    FROM service_requests s
    LEFT JOIN residents r ON s.resident_id = r.id
    ORDER BY s.created_at DESC
");
?>

<div class="content">
    <h2>Service Requests</h2>

    <a href="add.php" style="padding:8px; background:#4A67FF; color:white; text-decoration:none;">+ Add Service Request</a>
    <br><br>

    <table border="1" cellpadding="8" width="100%">
        <tr style="background:#ddd;">
            <th>ID</th>
            <th>Resident</th>
            <th>Type</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['lastname'] . ", " . $row['firstname']) ?></td>
                <td><?= htmlspecialchars($row['request_type']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
                    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this service request?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>

    </table>
</div>

<?php include "../../includes/footer.php"; ?>
