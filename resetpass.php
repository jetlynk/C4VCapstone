<?php

session_start();

require 'connection.php';
require 'functions.php';

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$sql = "SELECT * from userlogin where reset_token_hash = ?"; 
$stmt = mysqli_stmt_init($con);
$query = mysqli_stmt_prepare($stmt, $sql);

mysqli_stmt_bind_param($stmt, "s", $token_hash);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);


if(mysqli_num_rows($result) == 0){

    $user_data = mysqli_fetch_assoc($result);

    if(strtotime($user_data['reset_token_expire']) <= time()){

        dir("token has expired please submit new request");
        
        //redirect to forgot password page
        header("Location: forgotpassword.php");
        die;
    }
}else{

        //redirect to forgot password page
        //header("Location: forgotpassword.php");
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    //post to database

    $newpass = htmlentities ($_POST['password']);
    //$user_name = $user_data['user_name'];

    //hash the password
    $options = ['cost' => 10];
    $hashpass = password_hash($newpass, PASSWORD_BCRYPT, $options);
    
        if(!empty($newpass)){

            try{
                $pdo = new PDO($attr, $USER, $PASS, $opts);
            }catch (PDOException $e){
        
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
               
            $sql = "UPDATE userlogin SET password='$hashpass' WHERE reset_token_hash='$token_hash' limit 1";

            //prepare stament to input new hashed password
            $stmt = $pdo->prepare($sql);        

            $stmt->bindParam(1, $hashpass, PDO::PARAM_STR, 100);
            $stmt->execute();

            $message = "Password reset successfully!";
            echo "<script type='text/javascript'> alert('$message'); document.location.href='login.php'; </script>";
            die;

        }else{
            echo "Please enter valid information.";
        }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber hack - Reset Password</title>
    <link rel="stylesheet" href="form.css">

</head>
<body>
    
<div class="panel-heading"> <h1> Reset Password Page </h1></div>

<form method="post" class="registration-form" enctype="multipart/form-data">

    <input type="hidden" name="token" class="form-control" value="<?= htmlspecialchars($token) ?>">     
    
    <div class="input-group">
    <label for="InputPassword">Enter new Password</label>
    <input type="password" class="form-control" id="password" name="password" placeholder="New Password">                    
    </div>  

    <div>                
    <button type="submit" class="btn">Submit</button>
    </div>

</form> 

<div class="panel-footer"> &copy; Cyber Hack </div>  
</body>
</html>