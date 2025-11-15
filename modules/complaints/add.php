<?php
include "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

// get residents for dropdown
$residents = mysqli_query($conn, "SELECT id, firstname, lastname FROM residents ORDER BY lastname, firstname");

if (isset($_POST['save'])) {
    $resident_id = $_POST['resident_id'];
    $subject     = $_POST['subject'];
    $description = $_POST['description'];
    $status      = $_POST['status'];
    $created_by  = $_SESSION['userid']; // from login session

    $query = "INSERT INTO complaints (resident_id, subject, description, status, created_by)
              VALUES ('$resident_id', '$subject', '$description', '$status', '$created_by')";

    mysqli_query($conn, $query);

    $complaint_id = mysqli_insert_id($conn);
    add_log(
    $_SESSION['userid'],
    "Added Complaint",
    "Complaint ID $complaint_id was added"
    );


    header("Location: view.php");
    exit();
}
?>

<div class="content">
    <h2>Add Complaint</h2>

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

        <label>Subject:</label><br>
        <input type="text" name="subject" required style="width:60%;"><br><br>

        <label>Description:</label><br>
        <textarea name="description" rows="5" style="width:60%;"></textarea><br><br>

        <label>Status:</label><br>
        <select name="status">
            <option>Pending</option>
            <option>In Progress</option>
            <option>Resolved</option>
            <option>Rejected</option>
        </select>
        <br><br>

        <button type="submit" name="save">Save Complaint</button>
    </form>
</div>

<?php include "../../includes/footer.php"; ?>
