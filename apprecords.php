<?php
session_start(); 


require_once "connect.php"; 


$sql = "SELECT a.profileID, u.firstname, u.lastname, a.trainer_name, a.appointment_date 
        FROM tblappointment a
        INNER JOIN tbluserprofile u ON a.profileID = u.profileID";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "<div class='alert alert-danger'>Failed to fetch appointment records: " . mysqli_error($conn) . "</div>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container-oten">
        <h1 class="form-title">Appointment Records</h1>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Client's Name</th>
                        <th>Trainer's Name</th>
                        <th>Appointment Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['firstname'] . " " . $row['lastname']; ?></td>
                            <td><?php echo $row['trainer_name']; ?></td>
                            <td><?php echo $row['appointment_date']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No appointment records found.</div>
        <?php endif; ?>
    </div>
</body>
</html>
