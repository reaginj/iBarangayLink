<?php
include "config/db.php";
include "includes/header.php";
include "includes/sidebar.php";

// ---------- STATS QUERIES ----------

// total residents
$res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM residents");
$totalResidents = mysqli_fetch_assoc($res)['total'] ?? 0;

// complaints
$res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaints");
$totalComplaints = mysqli_fetch_assoc($res)['total'] ?? 0;

$res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaints WHERE status='Pending'");
$pendingComplaints = mysqli_fetch_assoc($res)['total'] ?? 0;

// service requests
$res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM service_requests");
$totalServices = mysqli_fetch_assoc($res)['total'] ?? 0;

$res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM service_requests WHERE status='Pending'");
$pendingServices = mysqli_fetch_assoc($res)['total'] ?? 0;

// certificates
$res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM certificates");
$totalCertificates = mysqli_fetch_assoc($res)['total'] ?? 0;

$res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM certificates WHERE status='Pending'");
$pendingCertificates = mysqli_fetch_assoc($res)['total'] ?? 0;

// announcements
$res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM announcements");
$totalAnnouncements = mysqli_fetch_assoc($res)['total'] ?? 0;

// recent 5 complaints
$recentComplaints = mysqli_query($conn, "
    SELECT c.id, c.subject, c.status, c.created_at, r.lastname, r.firstname
    FROM complaints c
    LEFT JOIN residents r ON c.resident_id = r.id
    ORDER BY c.created_at DESC
    LIMIT 5
");

// recent 5 service requests
$recentServices = mysqli_query($conn, "
    SELECT s.id, s.request_type, s.status, s.created_at, r.lastname, r.firstname
    FROM service_requests s
    LEFT JOIN residents r ON s.resident_id = r.id
    ORDER BY s.created_at DESC
    LIMIT 5
");
?>

<div class="content">
    <h1>Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

    <h3>Summary</h3>
    <div class="cards">
        <div class="card">
            <div class="card-label">Residents</div>
            <div class="card-value"><?php echo $totalResidents; ?></div>
        </div>

        <div class="card">
            <div class="card-label">Complaints (Total)</div>
            <div class="card-value"><?php echo $totalComplaints; ?></div>
            <div class="card-sub">Pending: <?php echo $pendingComplaints; ?></div>
        </div>

        <div class="card">
            <div class="card-label">Service Requests (Total)</div>
            <div class="card-value"><?php echo $totalServices; ?></div>
            <div class="card-sub">Pending: <?php echo $pendingServices; ?></div>
        </div>

        <div class="card">
            <div class="card-label">Certificates (Total)</div>
            <div class="card-value"><?php echo $totalCertificates; ?></div>
            <div class="card-sub">Pending: <?php echo $pendingCertificates; ?></div>
        </div>

        <div class="card">
            <div class="card-label">Announcements</div>
            <div class="card-value"><?php echo $totalAnnouncements; ?></div>
        </div>
    </div>

    <br><br>

    <div class="dashboard-columns">
        <div class="dashboard-panel">
            <h3>Recent Complaints</h3>
            <table border="1" cellpadding="6" width="100%">
                <tr style="background:#ddd;">
                    <th>ID</th>
                    <th>Resident</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
                <?php while($c = mysqli_fetch_assoc($recentComplaints)): ?>
                    <tr>
                        <td><?php echo $c['id']; ?></td>
                        <td><?php echo htmlspecialchars($c['lastname'].", ".$c['firstname']); ?></td>
                        <td><?php echo htmlspecialchars($c['subject']); ?></td>
                        <td><?php echo htmlspecialchars($c['status']); ?></td>
                        <td><?php echo $c['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="dashboard-panel">
            <h3>Recent Service Requests</h3>
            <table border="1" cellpadding="6" width="100%">
                <tr style="background:#ddd;">
                    <th>ID</th>
                    <th>Resident</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
                <?php while($s = mysqli_fetch_assoc($recentServices)): ?>
                    <tr>
                        <td><?php echo $s['id']; ?></td>
                        <td><?php echo htmlspecialchars($s['lastname'].", ".$s['firstname']); ?></td>
                        <td><?php echo htmlspecialchars($s['request_type']); ?></td>
                        <td><?php echo htmlspecialchars($s['status']); ?></td>
                        <td><?php echo $s['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
