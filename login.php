<?php

session_start();

require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {    

    $user_name = htmlentities ($_POST['user_name']);
    $password = htmlentities ($_POST['password']);

    if(!empty($user_name) && !empty($password) && !is_numeric($user_name)){

        //prepare statment and read from database
        $sql = "SELECT * FROM userlogin WHERE user_name=?;";
        $stmt = mysqli_stmt_init($con);
        $query = mysqli_stmt_prepare($stmt, $sql);

        mysqli_stmt_bind_param($stmt, "s", $user_name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $user_data = mysqli_fetch_assoc($result);

        //checks is query returned a result if no result move on
        if(!is_null($user_data)){

            //check if password matchs with hash
            if (password_verify($password, $user_data['password'])){

                $_SESSION['user_name'] = $user_data['user_name'];
                $_SESSION['email'] = $user_data['email'];
                $_SESSION['role'] = $user_data['role'];
                $_SESSION['login_time_stamp'] = time(); 

                if($user_data['role'] === 'admin'){

                    $message = "Login Successful!";
                    echo "<script type='text/javascript'> alert('$message'); document.location.href='adminpage.php'; </script>";
                    die;
                    
                }else{

                    $message = "Login Successful!";
                    echo "<script type='text/javascript'> alert('$message'); document.location.href='index.php'; </script>";
                    die;
                }   

            }else{

                $message = "Login failed, incorrect username or password!";
                echo "<script type='text/javascript'>alert('$message');</script>";
            } 
        }else{

            $message = "Login failed, incorrect username or password!";
            echo "<script type='text/javascript'>alert('$message');</script>";
        } 
    }else{
        
        $message = "Username or password field must not be empty!";
        echo "<script type='text/javascript'>alert('$message'); </script>";
    }
}    
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cyber Hack - Login</title>
<link rel="stylesheet" href="form.css">
</head>
<body>
    
    <div class="panel-heading"> <h1>Login</h1></div>

        <form method="post" action="" class="registration-form">

            <div class="input-group">
                <label for="InputUsername">Username</label>
                <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Username">
                </div>

            <div class="input-group">
                <label for="InputPassword">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">                    
            </div>

            <br><br>

            <div>
            <button type="submit" class="btn"  id="login" name="login">Submit</button>
            </div>

            <div class="input-group">
            <a href="signup.php" style="color: #fff; font-size:150%;">Click here to Signup</a> 
            </div>

            <div class="input-group">
            <a href="forgotpassword.php" style="color: #fff; font-size:100%;">Forgot Password?</a> 
            </div>
            
            <br><br>

        </form>


<div class="panel-footer">
    &copy; Cyber Hack
</div>

</body>
</html>