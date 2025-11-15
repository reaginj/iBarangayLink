<div class="sidebar">
    <h2>iBarangay</h2>

    <a href="/iBarangayLink/dashboard.php">Dashboard</a>
    <a href="/iBarangayLink/modules/residents/view.php">Residents</a>
    <a href="/iBarangayLink/modules/complaints/view.php">Complaints</a>
    <a href="/iBarangayLink/modules/services/view.php">Service Requests</a>
    <a href="/iBarangayLink/modules/certificates/view.php">Certificates</a>
    <a href="/iBarangayLink/modules/announcements/view.php">Announcements</a>

    <a href="/iBarangayLink/modules/users/view.php">Users</a>

    <?php if ($_SESSION['role'] == 'admin'): ?>
        <a href="/iBarangayLink/modules/audit/view.php">Audit Logs</a>
    <?php endif; ?>

    <a href="/iBarangayLink/logout.php">Logout</a>
</div>

