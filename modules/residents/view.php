<?php
include "../../config/db.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";
?>

<div class="content">
    <h2>Residents</h2>

    <a href="add.php" style="padding:8px; background:#4A67FF; color:white; text-decoration:none;">+ Add Resident</a>
    <br><br>

    <table border="1" cellpadding="10" width="100%">
        <tr style="background:#ddd;">
            <th>ID</th>
            <th>Name</th>
            <th>Gender</th>
            <th>DOB</th>
            <th>Contact</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>

        <?php
        $query = mysqli_query($conn, "SELECT * FROM residents ORDER BY lastname, firstname");

        while($row = mysqli_fetch_assoc($query)) {
            echo "<tr>
                    <td>".$row['id']."</td>
                    <td>".$row['lastname'].", ".$row['firstname']." ".$row['middlename']."</td>
                    <td>".$row['gender']."</td>
                    <td>".$row['dob']."</td>
                    <td>".$row['contact']."</td>
                    <td>".$row['address']."</td>
                    <td>
                        <a href='edit.php?id=".$row['id']."'>Edit</a> |
                        <a href='delete.php?id=".$row['id']."' onclick='return confirm(\"Delete this resident?\")'>Delete</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>
</div>

<?php include "../../includes/footer.php"; ?>
