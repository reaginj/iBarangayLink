<?php
include "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM residents WHERE id=$id");
$resident = mysqli_fetch_assoc($result);

if(isset($_POST['update'])) {

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $middlename = $_POST['middlename'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    mysqli_query($conn, "UPDATE residents SET 
        firstname='$firstname',
        lastname='$lastname',
        middlename='$middlename',
        gender='$gender',
        dob='$dob',
        contact='$contact',
        address='$address'
        WHERE id=$id");

        add_log(
    $_SESSION['userid'],
    "Updated Resident",
    "Resident ID $id was updated"
);


    header("Location: view.php");
    exit();
}
?>

<div class="content">
    <h2>Edit Resident</h2>

    <form method="POST">
        <label>First Name:</label><br>
        <input type="text" name="firstname" required value="<?= $resident['firstname'] ?>"><br><br>

        <label>Last Name:</label><br>
        <input type="text" name="lastname" required value="<?= $resident['lastname'] ?>"><br><br>

        <label>Middle Name:</label><br>
        <input type="text" name="middlename" value="<?= $resident['middlename'] ?>"><br><br>

        <label>Gender:</label><br>
        <select name="gender">
            <option <?= $resident['gender']=='Male'?'selected':'' ?>>Male</option>
            <option <?= $resident['gender']=='Female'?'selected':'' ?>>Female</option>
        </select><br><br>

        <label>Date of Birth:</label><br>
        <input type="date" name="dob" value="<?= $resident['dob'] ?>"><br><br>

        <label>Contact:</label><br>
        <input type="text" name="contact" value="<?= $resident['contact'] ?>"><br><br>

        <label>Address:</label><br>
        <textarea name="address"><?= $resident['address'] ?></textarea><br><br>

        <button type="submit" name="update">Update</button>
    </form>
</div>

<?php include "../../includes/footer.php"; ?>
