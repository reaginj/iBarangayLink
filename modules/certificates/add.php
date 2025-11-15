<?php
include "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

// list of residents
$residents = mysqli_query($conn, "SELECT id, firstname, lastname FROM residents ORDER BY lastname, firstname");

if (isset($_POST['save'])) {
    $resident_id = $_POST['resident_id'];
    $cert_type   = $_POST['cert_type'];
    $purpose     = $_POST['purpose'];
    $status      = $_POST['status'];
    $created_by  = $_SESSION['userid'];

    $query = "INSERT INTO certificates (resident_id, cert_type, purpose, status, created_by)
              VALUES ('$resident_id', '$cert_type', '$purpose', '$status', '$created_by')";

    mysqli_query($conn, $query);

    $cert_id = mysqli_insert_id($conn);

    // 🔹 LOG: certificate created
    add_log(
        $_SESSION['userid'],
        "Added Certificate",
        "Certificate ID $cert_id ($cert_type) for resident ID $resident_id"
    );


    header("Location: view.php");
    exit();
}
?>

<div class="content">
    <h2>Add Certificate Request</h2>

    <form method="POST">
        <label>Resident:</label><br>
        <select name="resident_id" required>
            <option value="">-- Select Resident --</option>
            <?php while($r = mysqli_fetch_assoc($residents)): ?>
                <option value="<?= $r['id'] ?>">
                    <?= htmlspecialchars($r['lastname'] . ", " . $r['firstname']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label>Certificate Type:</label><br>
        <select name="cert_type" required>
            <option value="Indigency">Certificate of Indigency</option>
            <option value="Barangay Clearance">Barangay Clearance</option>
            <option value="Residency">Certificate of Residency</option>
        </select>
        <br><br>

        <label>Purpose:</label><br>
        <textarea name="purpose" rows="4" style="width:60%;" placeholder="Purpose of the certificate"></textarea>
        <br><br>

        <label>Status:</label><br>
        <select name="status">
            <option>Pending</option>
            <option>Prepared</option>
            <option>Released</option>
            <option>Rejected</option>
        </select>
        <br><br>

        <button type="submit" name="save">Save</button>
    </form>
</div>

<?php include "../../includes/footer.php"; ?>
