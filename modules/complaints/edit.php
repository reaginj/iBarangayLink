<?php
include "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

$id = $_GET['id'];

// get complaint + resident
$result = mysqli_query($conn, "
    SELECT c.*, r.firstname, r.lastname
    FROM complaints c
    LEFT JOIN residents r ON c.resident_id = r.id
    WHERE c.id = $id
    LIMIT 1
");
$complaint = mysqli_fetch_assoc($result);

if (!$complaint) {
    echo "<div class='content'><p>Complaint not found.</p></div>";
    include "../../includes/footer.php";
    exit();
}

if (isset($_POST['update'])) {
    $subject     = $_POST['subject'];
    $description = $_POST['description'];
    $status      = $_POST['status'];

    mysqli_query($conn, "
        UPDATE complaints 
        SET subject='$subject',
            description='$description',
            status='$status'
        WHERE id=$id
    ");

    add_log(
    $_SESSION['userid'],
    "Updated Complaint",
    "Complaint ID $id was updated"
);


    header("Location: view.php");
    exit();
}
?>

<div class="content">
    <h2>Edit Complaint</h2>

    <p><b>Resident:</b> 
        <?= htmlspecialchars($complaint['lastname'] . ", " . $complaint['firstname']) ?>
    </p>

    <form method="POST">
        <label>Subject:</label><br>
        <input type="text" name="subject" required style="width:60%;" 
               value="<?= htmlspecialchars($complaint['subject']) ?>"><br><br>

        <label>Description:</label><br>
        <textarea name="description" rows="5" style="width:60%;"><?= htmlspecialchars($complaint['description']) ?></textarea><br><br>

        <label>Status:</label><br>
        <select name="status">
            <option <?= $complaint['status']=='Pending' ? 'selected' : '' ?>>Pending</option>
            <option <?= $complaint['status']=='In Progress' ? 'selected' : '' ?>>In Progress</option>
            <option <?= $complaint['status']=='Resolved' ? 'selected' : '' ?>>Resolved</option>
            <option <?= $complaint['status']=='Rejected' ? 'selected' : '' ?>>Rejected</option>
        </select>
        <br><br>

        <button type="submit" name="update">Update Complaint</button>
    </form>
</div>

<?php include "../../includes/footer.php"; ?>
