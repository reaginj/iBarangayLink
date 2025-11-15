<?php
include "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

// admin only
if ($_SESSION['role'] != 'admin') {
    echo "<div class='content'><h2>Access Denied</h2><p>Admins only.</p></div>";
    include "../../includes/footer.php";
    exit();
}

// get logs with usernames
$logs = mysqli_query($conn, "
    SELECT a.*, u.username 
    FROM audit_logs a
    LEFT JOIN users u ON a.user_id = u.id
    ORDER BY a.created_at DESC
");
?>

<div class="content">
    <h2>Audit Logs</h2>
    <p style="font-size:13px; color:#555;">This page shows recorded system activities performed by staff/admin users.</p>

    <table border="1" cellpadding="8" width="100%">
        <tr style="background:#ddd;">
            <th>Date & Time</th>
            <th>User</th>
            <th>Action</th>
            <th>Description</th>
        </tr>

        <?php if (mysqli_num_rows($logs) == 0): ?>
            <tr>
                <td colspan="4">No logs found.</td>
            </tr>
        <?php else: ?>
            <?php while($log = mysqli_fetch_assoc($logs)): ?>
                <tr>
                    <td><?= $log['created_at']; ?></td>
                    <td><?= htmlspecialchars($log['username'] ?? 'Unknown'); ?></td>
                    <td><?= htmlspecialchars($log['action']); ?></td>
                    <td><?= nl2br(htmlspecialchars($log['description'])); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php endif; ?>
    </table>
</div>

<?php include "../../includes/footer.php"; ?>
