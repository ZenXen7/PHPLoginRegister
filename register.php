<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <div class="container">
    <?php
require_once "connect.php"; 

if (!$conn) {
    echo "Failed to connect to the database: " . mysqli_connect_error();
    exit; 
}

if(isset($_POST["submit"])){
    $firstName = $_POST["firstname"];
    $lastName = $_POST["lastname"];
    $email = $_POST["email"];
    $userName = $_POST["username"];
    $password = $_POST["password"];
    $rptPassword = $_POST["repeat_password"];
    $gender = $_POST["gender"];
    $birthdate = $_POST["date"]; 
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $errors = array();

    if(empty($firstName) || empty($lastName) || empty($email) || empty($userName) || empty($password) || empty($rptPassword) || empty($gender)){
        array_push($errors, "All fields are required");
    }
    
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        array_push($errors, "Email is not valid");
    }
    if(strlen($password) < 8){
        array_push($errors, "Password must be at least 8 characters long");
    }
    if($password !== $rptPassword){
        array_push($errors, "Password does not match");
    }

    $sql = "SELECT * FROM tbluseraccount WHERE email = '$email'";
    $usr = "SELECT * FROM tbluseraccount WHERE username = '$userName'";
    $resultUser = mysqli_query($conn, $usr);
    $rowCountUser = mysqli_num_rows($resultUser);
    $result = mysqli_query($conn, $sql);
    $rowCount = mysqli_num_rows($result);

    if($rowCount > 0){
        array_push($errors, "Email already exists!");
    }
    if($rowCountUser > 0){
        array_push($errors, "Username Taken!");
    }

    if(count($errors) > 0){
        foreach($errors as $error){
            echo "<div class = 'alert alert-danger'>$error</div>";
        }
    } else {
       
        $sqlProfile = "INSERT INTO tbluserprofile (firstname, lastname, birthdate, gender) VALUES (?, ?, ?, ?)";
        $stmtProfile = mysqli_stmt_init($conn);
        if(mysqli_stmt_prepare($stmtProfile, $sqlProfile)){
            mysqli_stmt_bind_param($stmtProfile, "ssss", $firstName, $lastName, $birthdate, $gender);
            if(mysqli_stmt_execute($stmtProfile)){
                
                $sqlAccount = "INSERT INTO tbluseraccount (username, password, email, createdAt, updatedAt) VALUES (?, ?, ?, NOW(), NOW())";
                $stmtAccount = mysqli_stmt_init($conn);
                if(mysqli_stmt_prepare($stmtAccount, $sqlAccount)){
                    mysqli_stmt_bind_param($stmtAccount, "sss", $userName, $passwordHash, $email);
                    if(mysqli_stmt_execute($stmtAccount)){
                        echo "<div class = 'alert alert-success'>You are registered successfully.</div>";
                    } else {
                        echo "<div class = 'alert alert-danger'>Failed to register account: " . mysqli_stmt_error($stmtAccount) . "</div>";
                    }
                } else {
                    echo "<div class = 'alert alert-danger'>Failed to prepare statement for account: " . mysqli_error($conn) . "</div>";
                }
            } else {
                echo "<div class = 'alert alert-danger'>Failed to register profile: " . mysqli_stmt_error($stmtProfile) . "</div>";
            }
        } else {
            echo "<div class = 'alert alert-danger'>Failed to prepare statement for profile: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>
          <form action="register.php" method="post">
            <h1 class="form-title">Register</h1>
            <div class="form-group">
                <input type="text" class="form-control" name="firstname" placeholder="First Name">
            </div> 
            <div class="form-group">
                <input type="text" class="form-control" name="lastname" placeholder="Last Name">
            </div> 
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email">
            </div> 
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username">
            </div> 
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div> 
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Confirm Password">
            </div> 
            <div class="form-group">
                <select class="form-select" name="gender">
                    <option selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div> 
            <div class="form-group">
                <input type="date" class="form-control" name="date" placeholder="Date">
            </div> 

            <div class="text-center">
                <button type="submit" class="btn btn-primary" name="submit">Register</button>
            </div>
        </form>

        <div class="mt-3 text-center">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
    
</body>
</html>
