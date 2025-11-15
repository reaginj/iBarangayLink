<?php
include "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

$id = $_GET['id'];

$result = mysqli_query($conn, "
    SELECT c.*, r.firstname, r.lastname
    FROM certificates c
    LEFT JOIN residents r ON c.resident_id = r.id
    WHERE c.id = $id
    LIMIT 1
");

$cert = mysqli_fetch_assoc($result);

if (!$cert) {
    echo "<div class='content'><p>Certificate not found.</p></div>";
    include "../../includes/footer.php";
    exit();
}

if (isset($_POST['update'])) {
    $cert_type = $_POST['cert_type'];
    $purpose   = $_POST['purpose'];
    $status    = $_POST['status'];

    mysqli_query($conn, "
        UPDATE certificates
        SET cert_type='$cert_type',
            purpose='$purpose',
            status='$status'
        WHERE id=$id
    ");

    $cert_id = mysqli_insert_id($conn);

    add_log(
    $_SESSION['userid'],
    "Updated Certificate",
    "Certificate ID $id was updated"
);


    header("Location: view.php");
    exit();
}
?>

<div class="content">
    <h2>Edit Certificate Request</h2>

    <p><b>Resident:</b> <?= htmlspecialchars($cert['lastname'] . ", " . $cert['firstname']) ?></p>

    <form method="POST">
        <label>Certificate Type:</label><br>
        <select name="cert_type" required>
            <option value="Indigency" <?= $cert['cert_type']=='Indigency' ? 'selected' : '' ?>>Certificate of Indigency</option>
            <option value="Barangay Clearance" <?= $cert['cert_type']=='Barangay Clearance' ? 'selected' : '' ?>>Barangay Clearance</option>
            <option value="Residency" <?= $cert['cert_type']=='Residency' ? 'selected' : '' ?>>Certificate of Residency</option>
        </select>
        <br><br>

        <label>Purpose:</label><br>
        <textarea name="purpose" rows="4" style="width:60%;"><?= htmlspecialchars($cert['purpose']) ?></textarea>
        <br><br>

        <label>Status:</label><br>
        <select name="status">
            <option <?= $cert['status']=='Pending' ? 'selected' : '' ?>>Pending</option>
            <option <?= $cert['status']=='Prepared' ? 'selected' : '' ?>>Prepared</option>
            <option <?= $cert['status']=='Released' ? 'selected' : '' ?>>Released</option>
            <option <?= $cert['status']=='Rejected' ? 'selected' : '' ?>>Rejected</option>
        </select>
        <br><br>

        <button type="submit" name="update">Update</button>
    </form>
</div>

<?php include "../../includes/footer.php"; ?>
