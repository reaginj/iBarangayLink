<?php
include "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

$id = $_GET['id'];

$result = mysqli_query($conn, "
    SELECT s.*, r.firstname, r.lastname
    FROM service_requests s
    LEFT JOIN residents r ON s.resident_id = r.id
    WHERE s.id = $id
    LIMIT 1
");
$request = mysqli_fetch_assoc($result);

if (!$request) {
    echo "<div class='content'><p>Service request not found.</p></div>";
    include "../../includes/footer.php";
    exit();
}

if (isset($_POST['update'])) {
    $request_type = $_POST['request_type'];
    $details      = $_POST['details'];
    $status       = $_POST['status'];

    mysqli_query($conn, "
        UPDATE service_requests
        SET request_type='$request_type',
            details='$details',
            status='$status'
        WHERE id=$id
    ");

    add_log(
    $_SESSION['userid'],
    "Updated Service Request",
    "Service Request ID $id was updated"
);


    header("Location: view.php");
    exit();
}
?>

<div class="content">
    <h2>Edit Service Request</h2>

    <p><b>Resident:</b>
        <?= htmlspecialchars($request['lastname'] . ", " . $request['firstname']) ?>
    </p>

    <form method="POST">
        <label>Request Type:</label><br>
        <input type="text" name="request_type" required style="width:60%;"
               value="<?= htmlspecialchars($request['request_type']) ?>">
        <br><br>

        <label>Details:</label><br>
        <textarea name="details" rows="5" style="width:60%;"><?= htmlspecialchars($request['details']) ?></textarea>
        <br><br>

        <label>Status:</label><br>
        <select name="status">
            <option <?= $request['status']=='Pending' ? 'selected' : '' ?>>Pending</option>
            <option <?= $request['status']=='Approved' ? 'selected' : '' ?>>Approved</option>
            <option <?= $request['status']=='Completed' ? 'selected' : '' ?>>Completed</option>
            <option <?= $request['status']=='Rejected' ? 'selected' : '' ?>>Rejected</option>
        </select>
        <br><br>

        <button type="submit" name="update">Update Request</button>
    </form>
</div>

<?php include "../../includes/footer.php"; ?>
