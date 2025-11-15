<?php
include "config/db.php";
include "includes/header_public.php";

$complaint_success = '';
$request_success   = '';
$track_message     = '';
$track_results     = null;

// Handle forms
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Resident submits a complaint ---
    if (isset($_POST['submit_complaint'])) {
        $fname   = mysqli_real_escape_string($conn, $_POST['rc_firstname']);
        $lname   = mysqli_real_escape_string($conn, $_POST['rc_lastname']);
        $addr    = mysqli_real_escape_string($conn, $_POST['rc_address']);
        $contact = mysqli_real_escape_string($conn, $_POST['rc_contact']);
        $subject = mysqli_real_escape_string($conn, $_POST['rc_subject']);
        $desc    = mysqli_real_escape_string($conn, $_POST['rc_description']);

        if ($fname !== '' && $lname !== '' && $subject !== '') {

            // create resident (simple approach)
            $insertRes = "
                INSERT INTO residents (firstname, lastname, address, contact)
                VALUES ('$fname', '$lname', '$addr', '$contact')
            ";
            mysqli_query($conn, $insertRes);
            $resident_id = mysqli_insert_id($conn);

            // complaint
            $insertComp = "
                INSERT INTO complaints (resident_id, subject, description, status)
                VALUES ($resident_id, '$subject', '$desc', 'Pending')
            ";
            mysqli_query($conn, $insertComp);
            $complaint_id = mysqli_insert_id($conn);

            $ref = 'C-' . str_pad($complaint_id, 5, '0', STR_PAD_LEFT);
            $complaint_success = "Your complaint has been submitted. Reference No: <span class='ref-badge'>$ref</span>";
        }
    }

    // --- Resident submits a service request ---
    elseif (isset($_POST['submit_request'])) {
        $fname   = mysqli_real_escape_string($conn, $_POST['sr_firstname']);
        $lname   = mysqli_real_escape_string($conn, $_POST['sr_lastname']);
        $addr    = mysqli_real_escape_string($conn, $_POST['sr_address']);
        $contact = mysqli_real_escape_string($conn, $_POST['sr_contact']);
        $type    = mysqli_real_escape_string($conn, $_POST['sr_type']);
        $details = mysqli_real_escape_string($conn, $_POST['sr_details']);

        if ($fname !== '' && $lname !== '' && $type !== '') {
            $insertRes = "
                INSERT INTO residents (firstname, lastname, address, contact)
                VALUES ('$fname', '$lname', '$addr', '$contact')
            ";
            mysqli_query($conn, $insertRes);
            $resident_id = mysqli_insert_id($conn);

            $insertReq = "
                INSERT INTO service_requests (resident_id, request_type, details, status)
                VALUES ($resident_id, '$type', '$details', 'Pending')
            ";
            mysqli_query($conn, $insertReq);
            $req_id = mysqli_insert_id($conn);

            $ref = 'SR-' . str_pad($req_id, 5, '0', STR_PAD_LEFT);
            $request_success = "Your service request has been submitted. Reference No: <span class='ref-badge'>$ref</span>";
        }
    }

    // --- Tracking form ---
    elseif (isset($_POST['track_submit'])) {
        $lname   = mysqli_real_escape_string($conn, $_POST['track_lastname']);
        $contact = mysqli_real_escape_string($conn, $_POST['track_contact']);

        if ($lname === '') {
            $track_message = "Please enter your last name.";
        } else {

            $cond1 = "r.lastname LIKE '%$lname%'";
            if ($contact !== '') {
                $cond1 .= " AND r.contact LIKE '%$contact%'";
            }

            // complaints + service requests in one result
            $sql = "
                SELECT 
                    'Complaint' AS type,
                    c.id AS ref_id,
                    r.firstname,
                    r.lastname,
                    c.subject AS title,
                    c.status,
                    c.created_at
                FROM complaints c
                JOIN residents r ON c.resident_id = r.id
                WHERE $cond1

                UNION ALL

                SELECT 
                    'Service Request' AS type,
                    s.id AS ref_id,
                    r.firstname,
                    r.lastname,
                    s.request_type AS title,
                    s.status,
                    s.created_at
                FROM service_requests s
                JOIN residents r ON s.resident_id = r.id
                WHERE $cond1

                ORDER BY created_at DESC
            ";

            $track_results = mysqli_query($conn, $sql);

            if (!$track_results || mysqli_num_rows($track_results) == 0) {
                $track_message = "No records found. Please check your details.";
            }
        }
    }
}

// --------- STATS & ANNOUNCEMENTS ----------
$res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM residents");
$totalResidents = mysqli_fetch_assoc($res)['total'] ?? 0;

$res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM announcements");
$totalAnnouncements = mysqli_fetch_assoc($res)['total'] ?? 0;

$ann = mysqli_query($conn, "
    SELECT * FROM announcements
    ORDER BY publish_date DESC, created_at DESC
    LIMIT 5
");
?>

<div class="resident-container">
    <div class="resident-header">
        <h1>Resident Dashboard</h1>
        <p>Welcome to the iBarangay Link resident portal.</p>
    </div>

    <h3>Barangay Summary</h3>
    <div class="cards">
        <div class="card">
            <div class="card-label">Registered Residents</div>
            <div class="card-value"><?php echo $totalResidents; ?></div>
        </div>

        <div class="card">
            <div class="card-label">Announcements</div>
            <div class="card-value"><?php echo $totalAnnouncements; ?></div>
        </div>
    </div>

    <br>

    <div class="dashboard-panel">
        <h3>Latest Announcements</h3>
        <?php if (mysqli_num_rows($ann) == 0): ?>
            <p>No announcements at the moment.</p>
        <?php else: ?>
            <table border="1" cellpadding="8" width="100%">
                <tr style="background:#ddd;">
                    <th>Title</th>
                    <th>Date</th>
                    <th>Message</th>
                </tr>
                <?php while($a = mysqli_fetch_assoc($ann)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($a['title']); ?></td>
                        <td><?php echo $a['publish_date']; ?></td>
                        <td><?php echo nl2br(htmlspecialchars($a['message'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </div>

    <br>

    <div class="dashboard-columns">
        <!-- Complaint form -->
        <div class="dashboard-panel">
            <h3>Submit Complaint</h3>

            <?php if ($complaint_success): ?>
                <div class="alert-success"><?php echo $complaint_success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>First Name*</label>
                    <input type="text" name="rc_firstname" class="input-text" required>
                </div>
                <div class="form-group">
                    <label>Last Name*</label>
                    <input type="text" name="rc_lastname" class="input-text" required>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="rc_address" class="input-text">
                </div>
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="rc_contact" class="input-text">
                </div>
                <div class="form-group">
                    <label>Subject*</label>
                    <input type="text" name="rc_subject" class="input-text" required>
                </div>
                <div class="form-group">
                    <label>Complaint Details</label>
                    <textarea name="rc_description" class="input-textarea"></textarea>
                </div>

                <button type="submit" name="submit_complaint" class="btn-primary">
                    Submit Complaint
                </button>
            </form>
        </div>

        <!-- Service request form -->
        <div class="dashboard-panel">
            <h3>Request Service</h3>

            <?php if ($request_success): ?>
                <div class="alert-success"><?php echo $request_success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>First Name*</label>
                    <input type="text" name="sr_firstname" class="input-text" required>
                </div>
                <div class="form-group">
                    <label>Last Name*</label>
                    <input type="text" name="sr_lastname" class="input-text" required>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="sr_address" class="input-text">
                </div>
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="sr_contact" class="input-text">
                </div>
                <div class="form-group">
                    <label>Request Type*</label>
                    <input type="text" name="sr_type" class="input-text" placeholder="e.g. Barangay Clearance, Assistance" required>
                </div>
                <div class="form-group">
                    <label>Details</label>
                    <textarea name="sr_details" class="input-textarea"></textarea>
                </div>

                <button type="submit" name="submit_request" class="btn-primary">
                    Submit Request
                </button>
            </form>
        </div>
    </div>

    <br>

    <!-- Tracking panel -->
    <div class="dashboard-panel tracking-panel">
        <h3>Track Your Complaint / Request</h3>
        <p style="font-size: 13px; color:#555;">
            Enter the last name you used when submitting. You may also add your contact number to narrow the search.
        </p>

        <?php if ($track_message): ?>
            <p style="color:#c00; font-size:13px;"><?php echo htmlspecialchars($track_message); ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Last Name*</label>
                <input type="text" name="track_lastname" class="input-text" required>
            </div>
            <div class="form-group">
                <label>Contact Number (optional)</label>
                <input type="text" name="track_contact" class="input-text">
            </div>
            <button type="submit" name="track_submit" class="btn-primary">Search</button>
        </form>

        <?php if ($track_results && mysqli_num_rows($track_results) > 0): ?>
            <br>
            <table border="1" cellpadding="6" width="100%">
                <tr style="background:#ddd;">
                    <th>Type</th>
                    <th>Reference No.</th>
                    <th>Name</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Submitted</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($track_results)): ?>
                    <tr>
                        <td><?php echo $row['type']; ?></td>
                        <td>
                            <?php
                                if ($row['type'] === 'Complaint') {
                                    $ref = 'C-' . str_pad($row['ref_id'], 5, '0', STR_PAD_LEFT);
                                } else {
                                    $ref = 'SR-' . str_pad($row['ref_id'], 5, '0', STR_PAD_LEFT);
                                }
                                echo "<span class='ref-badge'>$ref</span>";
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['lastname'].", ".$row['firstname']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </div>

    <br>
    <p style="font-size: 13px; color:#666;">
        For barangay staff and officials, please use the
        <a href="login.php">Staff / Admin Login</a>.
    </p>
</div>

<?php include "includes/footer_public.php"; ?>
