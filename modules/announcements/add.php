<?php
include "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

if (isset($_POST['save'])) {
    $title        = $_POST['title'];
    $message      = $_POST['message'];
    $publish_date = $_POST['publish_date'];
    $created_by   = $_SESSION['userid'];

    $query = "INSERT INTO announcements (title, message, publish_date, created_by)
              VALUES ('$title', '$message', '$publish_date', '$created_by')";

    mysqli_query($conn, $query);

    $ann_id = mysqli_insert_id($conn);
    add_log(
    $_SESSION['userid'],
    "Added Announcement",
    "Announcement ID $ann_id was added"
);

    header("Location: view.php");
    exit();
}
?>

<div class="content">
    <h2>Add Announcement</h2>

    <form method="POST">
        <label>Title:</label><br>
        <input type="text" name="title" required style="width:60%;">
        <br><br>

        <label>Publish Date:</label><br>
        <input type="date" name="publish_date" value="<?= date('Y-m-d'); ?>">
        <br><br>

        <label>Message:</label><br>
        <textarea name="message" rows="6" style="width:60%;" required></textarea>
        <br><br>

        <button type="submit" name="save">Save Announcement</button>
    </form>
</div>

<?php include "../../includes/footer.php"; ?>
