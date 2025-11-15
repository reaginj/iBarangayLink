<?php
include "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

if(isset($_POST['save'])) {

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $middlename = $_POST['middlename'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    $query = "INSERT INTO residents (firstname, lastname, middlename, gender, dob, contact, address)
              VALUES ('$firstname','$lastname','$middlename','$gender','$dob','$contact','$address')";

    mysqli_query($conn, $query);

    $resident_id = mysqli_insert_id($conn);

    add_log(
    $_SESSION['userid'],
    "Added Resident",
    "Resident ID $resident_id - $firstname $middlename $lastname"
    );



    header("Location: view.php");
    exit();
}
?>

<div class="content">
    <h2>Add Resident</h2>

    <form method="POST">
        <label>First Name:</label><br>
        <input type="text" name="firstname" required><br><br>

        <label>Last Name:</label><br>
        <input type="text" name="lastname" required><br><br>

        <label>Middle Name:</label><br>
        <input type="text" name="middlename"><br><br>

        <label>Gender:</label><br>
        <select name="gender" required>
            <option>Male</option>
            <option>Female</option>
        </select><br><br>

        <label>Date of Birth:</label><br>
        <input type="date" name="dob"><br><br>

        <label>Contact:</label><br>
        <input type="text" name="contact"><br><br>

        <label>Address:</label><br>
        <textarea name="address" required></textarea><br><br>

        <button type="submit" name="save">Save</button>
    </form>
</div>

<?php include "../../includes/footer.php"; ?>
