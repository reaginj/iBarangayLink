<?php
include "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

$id = $_GET['id'];

$result = mysqli_query($conn, "
    SELECT * FROM announcements
    WHERE id = $id
    LIMIT 1
");

$ann = mysqli_fetch_assoc($result);

if (!$ann) {
    echo "<div class='content'><p>Announcement not found.</p></div>";
    include "../../includes/footer.php";
    exit();
}

if (isset($_POST['update'])) {
    $title        = $_POST['title'];
    $message      = $_POST['message'];
    $publish_date = $_POST['publish_date'];

    mysqli_query($conn, "
        UPDATE announcements
        SET title='$title',
            message='$message',
            publish_date='$publish_date'
        WHERE id=$id
    ");

    add_log(
    $_SESSION['userid'],
    "Updated Announcement",
    "Announcement ID $id was updated"
);


    header("Location: view.php");
    exit();
}
?>

<div class="content">
    <h2>Edit Announcement</h2>

    <form method="POST">
        <label>Title:</label><br>
        <input type="text" name="title" required style="width:60%;"
               value="<?= htmlspecialchars($ann['title']) ?>">
        <br><br>

        <label>Publish Date:</label><br>
        <input type="date" name="publish_date" value="<?= $ann['publish_date'] ?>">
        <br><br>

        <label>Message:</label><br>
        <textarea name="message" rows="6" style="width:60%;"><?= htmlspecialchars($ann['message']) ?></textarea>
        <br><br>

        <button type="submit" name="update">Update Announcement</button>
    </form>
</div>

<?php include "../../includes/footer.php"; ?>
