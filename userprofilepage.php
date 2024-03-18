<?php

session_start();

require 'connection.php';
require 'functions.php';

check_login($con);
login_timeout();

$user = $_SESSION['user_name'];

//check what button is pressed
if (isset($_POST['chgpassbtn'])){
  //change user password
  if ($_SERVER['REQUEST_METHOD'] == "POST") {

    //post to database
    $newpass = htmlentities ($_POST['newpass']);
    $oldpass = htmlentities ($_POST['oldpass']);
    
        if(!empty($newpass) && !empty($oldpass)){

          //prepare statment and read from database
          $sql = "SELECT * FROM userlogin WHERE user_name=?;";
          $stmt = mysqli_stmt_init($con);
          $query = mysqli_stmt_prepare($stmt, $sql);

          mysqli_stmt_bind_param($stmt, "s", $user);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);

          $user_data = mysqli_fetch_assoc($result);

          //checks is query returned a result if no result move on
          if(!is_null($user_data)){

              //check if password matchs with hash
              if (password_verify($oldpass, $user_data['password'])){

                try{
                  $pdo = new PDO($attr, $USER, $PASS, $opts);
                }catch (PDOException $e){          
                  throw new PDOException($e->getMessage(), (int)$e->getCode());
                }

                //hash the password
                $options = ['cost' => 10];
                $hashpass = password_hash($newpass, PASSWORD_BCRYPT, $options);
                  
                $sql = "UPDATE userlogin SET password='$hashpass' WHERE user_name='$user' limit 1";
      
                //prepare stament to input new hashed password
                $stmt = $pdo->prepare($sql);        
      
                $stmt->bindParam(1, $hashpass, PDO::PARAM_STR, 100);
                $stmt->execute();
      
                $message = "Password reset successfully!";
                echo "<script type='text/javascript'> alert('$message'); document.location.href='userprofilepage.php'; </script>";
      
              }else{

                  $message = "Incorrrect password!";
                  echo "<script type='text/javascript'>alert('$message'); document.location.href='userprofilepage.php';</script>";
              } 
          }else{

              $message = "Login failed, incorrect username or password!";
              echo "<script type='text/javascript'>alert('$message');</script>";
          } 

        }else{
          $message = "Please enter valid information.";
          echo "<script type='text/javascript'>alert('$message'); document.location.href='userprofilepage.php'; </script>";
        }  
  }
//check if delete button is pressed
}else if(isset($_POST['deluserbtn'])){

  if ($_SERVER['REQUEST_METHOD'] == "POST") {

    //post to database
    $password = htmlentities ($_POST['deluser']);
    
        if(!empty($password)){

          //prepare statment and read from database
          $sql = "SELECT * FROM userlogin WHERE user_name=?;";
          $stmt = mysqli_stmt_init($con);
          $query = mysqli_stmt_prepare($stmt, $sql);

          mysqli_stmt_bind_param($stmt, "s", $user);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);

          $user_data = mysqli_fetch_assoc($result);
              
          $name = $user_data['user_name'];

          //checks is query returned a result if no result move on
          if(!is_null($user_data)){

              //check if password matchs with hash
              if (password_verify($password, $user_data['password'])){

                try{
                  $pdo = new PDO($attr, $USER, $PASS, $opts);
                }catch (PDOException $e){          
                  throw new PDOException($e->getMessage(), (int)$e->getCode());
                }
                  
                $sqlLogin = "DELETE FROM userlogin WHERE user_name = ?";
                $sqlVol = "DELETE FROM volunteer WHERE user_name = ?";
                $sqlNpo = "DELETE FROM npo WHERE user_name = ?";
      
                //prepare stament and delete from login
                $stmt = $pdo->prepare($sqlLogin);     
                $stmt->bindParam(1, $name, PDO::PARAM_STR, 100);
                $stmt->execute();      
                
                //prepare stament and delete from login
                $stmt = $pdo->prepare($sqlVol);     
                $stmt->bindParam(1, $name, PDO::PARAM_STR, 100);
                $stmt->execute();  
                
                //prepare stament and delete from login
                $stmt = $pdo->prepare($sqlNpo);     
                $stmt->bindParam(1, $name, PDO::PARAM_STR, 100);
                $stmt->execute();   
      
                $message = "Account has been deleted!";
                echo "<script type='text/javascript'> alert('$message'); document.location.href='logout.php'; </script>"; //document.location.href='login.php';
      
              }else{

                  $message = "Incorrrect password!";
                  echo "<script type='text/javascript'>alert('$message'); document.location.href='userprofilepage.php';</script>";
              } 
          }else{

              $message = "Something went wrong!";
              echo "<script type='text/javascript'>alert('$message'); document.location.href='userprofilepage.php';</script>";
          } 

        }else{
          $message = "Please enter valid information.";
          echo "<script type='text/javascript'>alert('$message'); document.location.href='userprofilepage.php'; </script>";
        }  
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

<?php
user_type_nav();
?>

</head>
<body>
    
<div class="panel-heading"> <h1> <?php echo $_SESSION['user_name']; ?> Account Page </h1></div>

<form method="post" class="registration-form" enctype="multipart/form-data"> 

    <div class="input-group">
    <label for="newpass">To change account password, enter new password:</label>
    <input type="text" name="newpass" class="form-control" placeholder="Enter new password">     
    </div> 

    <div class="input-group">
    <label for="oldpass">Confirm old password and click Change Password:</label>
    <input type="text" name="oldpass" class="form-control" placeholder="Enter old password">     
    </div>

    <div>                
    <button type="submit" class="btn" name="chgpassbtn">Change Password</button>
    </div>

</form> 

<form method="post" class="registration-form" enctype="multipart/form-data"> 

    <div class="input-group">
    <label for="deluser">To delete account enter password and press delete:</label>
    <input type="text" name="deluser" class="form-control" placeholder="Enter password">     
    </div> 

    <div>                
    <button onclick="return confirm('Are you sure you want to delete your account?')" class="btn" name="deluserbtn">Delete User</button>
    </div>

</form> 

<div class="panel-footer"> &copy; Cyber Hack </div>  
</body>
</html>