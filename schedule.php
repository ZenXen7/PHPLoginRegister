<?php
session_start(); 





require_once "connect.php"; 


$trainerNames = array("John", "Sarah", "Michael", "Emma", "David", "Sophia", "James", "Olivia", "Daniel", "Ava");


if(isset($_POST["submit"])){
  
   
    $trainerName = $_POST["trainer_name"]; 
    $appointmentDate = $_POST["appointment_date"]; 

   

    
    $sql = "INSERT INTO tblappointment (profileID, trainer_name, appointment_date) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "iss", $profileID, $trainerName, $appointmentDate);
        if(mysqli_stmt_execute($stmt)){
            echo "<div class='alert alert-success'>Appointment scheduled successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to schedule appointment: " . mysqli_stmt_error($stmt) . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Failed to prepare statement: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Appointment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1 class="form-title">Schedule Appointment</h1>
        <form action="schedule.php" method="post">
            <div class="form-group">
                <label for="trainer_name">Select Trainer:</label>
                <select class="form-select" name="trainer_name" id="trainer_name" required>
                    <!-- Populate dropdown options with random trainer names -->
                    <?php foreach($trainerNames as $name): ?>
                        <option value="<?php echo $name; ?>"><?php echo $name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div> 
            <div class="form-group">
                <label for="appointment_date">Select Appointment Date:</label>
                <input type="date" class="form-control" name="appointment_date" id="appointment_date" required>
            </div> 

            <div class="text-center">
                <button type="submit" class="btn btn-primary" name="submit">Schedule Appointment</button>
            </div>
        </form>
    </div>
</body>
</html>
