<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        $userName = $_POST["username"];
        $password = $_POST["password"];
        $errors = array();

        if(empty($userName) || empty($password)){
            array_push($errors, "Username and password are required");
        }

        if(count($errors) > 0){
            foreach($errors as $error){
                echo "<div class='alert alert-danger'>$error</div>";
            }
        } else {
            $sql = "SELECT * FROM tbluseraccount WHERE username = '$userName'";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);
                if(password_verify($password, $row['password'])){
                    echo "<div class='alert alert-success'>Login successful!</div>";
                    header("Location: https://www.youtube.com");
                    
                } else {
                    echo "<div class='alert alert-danger'>Incorrect password</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Username not found</div>";
            }
        }
    }
    ?>
        <form action="login.php" method="post">
            <h1 class="form-title">Login</h1>
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username">
            </div> 
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div> 

            <div class="text-center">
                <button type="submit" class="btn btn-primary" name="submit">Login</button>
            </div>
        </form>

        <div class="mt-3 text-center">
            <p>Don't have an account yet? <a href="register.php">Register</a></p>
        </div>
    </div>
</body>
</html>
