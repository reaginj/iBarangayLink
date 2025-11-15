<?php
include "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

// get residents for dropdown
$residents = mysqli_query($conn, "SELECT id, firstname, lastname FROM residents ORDER BY lastname, firstname");

if (isset($_POST['save'])) {
    $resident_id  = $_POST['resident_id'];
    $request_type = $_POST['request_type'];
    $details      = $_POST['details'];
    $status       = $_POST['status'];
    $created_by   = $_SESSION['userid'];

    $query = "INSERT INTO service_requests (resident_id, request_type, details, status, created_by)
              VALUES ('$resident_id', '$request_type', '$details', '$status', '$created_by')";

    mysqli_query($conn, $query);

    $req_id = mysqli_insert_id($conn);
    add_log(
    $_SESSION['userid'],
    "Added Service Request",
    "Service Request ID $req_id was added"
);


    header("Location: view.php");
    exit();
}
?>

<div class="content">
    <h2>Add Service Request</h2>

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

        <label>Request Type:</label><br>
        <input type="text" name="request_type" required style="width:60%;" placeholder="e.g. Barangay Clearance, ID Application, Assistance">
        <br><br>

        <label>Details:</label><br>
        <textarea name="details" rows="5" style="width:60%;"></textarea>
        <br><br>

        <label>Status:</label><br>
        <select name="status">
            <option>Pending</option>
            <option>Approved</option>
            <option>Completed</option>
            <option>Rejected</option>
        </select>
        <br><br>

        <button type="submit" name="save">Save Request</button>
    </form>
</div>

<?php include "../../includes/footer.php"; ?>
