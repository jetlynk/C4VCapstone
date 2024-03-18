<?php

session_start();

require 'connection.php';
require 'functions.php';

try
{
$pdo = new PDO($attr, $USER, $PASS, $opts);
}
catch (PDOException $e)
{
throw new PDOException($e->getMessage(), (int)$e->getCode());
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {



$email = htmlentities ($_POST['email']);
$user_name = htmlentities ($_POST['user_name']);
$password = htmlentities ($_POST['password']);

$role = "";
if(isset($_POST['role'])){

    $role = htmlentities ($_POST['role']);
}

    if(!empty($user_name) && !empty($password) && !is_numeric($user_name) && !empty($role)){

        //check if username or email is already in use
        $query = "SELECT * FROM userlogin where user_name = ?";
        $query2 = "SELECT * FROM userlogin where email = ?";

        $stmt = $pdo->prepare($query);         
        $stmt->bindParam(1, $user_name, PDO::PARAM_STR, 100);
        $stmt->execute(); 
        $result = $stmt->fetchAll(); 

        $stmt2 = $pdo->prepare($query2); 
        $stmt2->bindParam(1, $email, PDO::PARAM_STR, 50);
        $stmt2->execute(); 
        $result2 = $stmt2->fetchAll(); 
        
        

        if(count($result2) == 0){

            if(count($result) == 0){
                    
                $stmt = $pdo->prepare('INSERT INTO userlogin (`email`, `user_name`, `password`, `role`) VALUES(?,?,?,?)');
                
                //hash the password
                $options = ['cost' => 10];
                $hashpass = password_hash($password, PASSWORD_BCRYPT, $options);
                
                $stmt->bindParam(1, $email, PDO::PARAM_STR, 50);
                $stmt->bindParam(2, $user_name, PDO::PARAM_STR, 100); 
                $stmt->bindParam(3, $hashpass, PDO::PARAM_STR, 100);
                $stmt->bindParam(4, $role, PDO::PARAM_STR, 5);
                //post to database
                $stmt->execute([$email, $user_name, $hashpass, $role]);

                $message = "Sign-up Successful!";
                echo "<script type='text/javascript'> alert('$message'); document.location.href='login.php'; </script>";
                die;

            }else{

                $message = "Invalid Username!";
                echo "<script type='text/javascript'>alert('$message');</script>";
            }
        }else{

            $message = "Invalid Email!";
            echo "<script type='text/javascript'>alert('$message');</script>";
        }

    }else{

        $message = "Please enter valid information and make appropriate user type selection.";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cyber Hack - Sign-Up</title>
<link rel="stylesheet" href="form.css">

</head>
<body>

<div class="container">
    <div class="panel-heading"> <h1>Sign Up</h1></div>
        <form method="post" action="" class="registration-form">

            <div class="input-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email">
            </div>

            <div class="input-group">
            <label for="InputUsername">Username</label>
            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Username">
            </div>

            <div class="input-group">
            <label for="InputPassword">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password">                    
            </div>

            <div class="input-group">
            <input type="radio" id="org" name="role" value="org"> <label for="org"> I am an organization looking for help.</label>
            </div>

            <div class="input-group">
            <input type="radio" id="vol" name="role" value="vol"> <label for="vol"> I am a volunteer looking to help.</label>
            </div>

            <br><br>
            
            <div>                
            <button type="submit" class="btn">Submit</button>
            </div>

            <div class="input-group">
            <a href="login.php" style="color: #fff; font-size:150%;">Click here to Login</a> 
            </div>

        </form>
</div>

    <div class="panel-footer">
        &copy; Cyber Hack
    </div>

</body>
</html>