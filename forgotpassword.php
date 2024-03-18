<?php

session_start();

require 'connection.php';
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {

  $sendemail = "email.php";

  //read from database
  $email = htmlentities ($_POST['email']);  

  if(!empty($email)){   

    //prepare statment and read from database
    $sql = "SELECT * FROM userlogin WHERE email=?;";
    $stmt = mysqli_stmt_init($con);
    $query = mysqli_stmt_prepare($stmt, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $user_data = mysqli_fetch_assoc($result);

      //checks is query returned a result if no result move on
      if(!is_null($user_data)){
      
      $userEmail = $user_data['email'];

      $token = bin2hex(random_bytes(16));

      $token_hash = hash('sha256', $token);
  
      $expiry = date("Y-m-d H:i:s",time() + 60 *30);

      $reset_query = "UPDATE userlogin SET reset_token_hash='$token_hash', reset_token_expire='$expiry' WHERE email='$userEmail'";

      if(mysqli_query($con, $reset_query)){ 

        $mail = require __DIR__ . "/email.php";

        $message = "Password reset email has been sent successfully!";
        echo "<script type='text/javascript'> alert('$message'); document.location.href='login.php'; </script>";
        die;
      }else{ 
    
        $message = "Password reset email has been sent successfully!";
        echo "<script type='text/javascript'> alert('$message'); document.location.href='login.php'; </script>";
        die;
      } 
      mysqli_close($con);
      
    }else{ 
    
      $message = "Password reset email has been sent successfully!";
      echo "<script type='text/javascript'> alert('$message'); document.location.href='login.php'; </script>";
      die;
    } 

  }else{             
    
    $message = "Email field empty!";
    echo "<script type='text/javascript'>alert('$message');</script>";
  }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber hack - Account Page</title>
    <link rel="stylesheet" href="form.css">

</head>
<body>
    
<div class="panel-heading"> <h1> Forgot Password Page </h1></div>

<form method="post" class="registration-form" enctype="multipart/form-data" action="<?php $sendemail ?>"> 

    <div class="input-group">
    <label for="email">Enter your accounts email addres to recieve reset link.</label>
    <input type="email" name="email" class="form-control" placeholder="Email Address">     
    </div> 

    <div>                
    <button type="submit" class="btn" name="chgpassbtn">Submit</button>
    </div>

    <div class="input-group">
    <a href="login.php" style="color: #fff; font-size:150%;">Click here to Login</a> 
    </div>

</form> 

<div class="panel-footer"> &copy; Cyber Hack </div>  
</body>
</html>