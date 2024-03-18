<?php

session_start();

require 'connection.php';
require 'functions.php';
check_login($con);

if($_SESSION['role'] !== 'admin'){

//redirect to login page
header("Location: index.php");
die;
}

login_timeout();

//called on if volunteer button is pressed to download volunteer information
if(isset ($_POST['volButton'])) { 
  
  $f = fopen("pdf/volunteer.txt", "w");

  $query = "SELECT * FROM volunteer ORDER BY datetime DESC";

  $result = mysqli_query($con, $query);

  while($row = $result -> fetch_assoc()){
      $email = $row['email'];
      $name = $row['name'];
      $pnumber = $row['pnumber'];
      $hours = $row['hours'];
      $employed = $row['employed'];
      $education = $row['education'];
      $yearexp = $row['yearexp'];
      $expin = $row['expin'];
      $interest = $row['interest'];
      $datetime = $row['datetime'];

      $volunteers = "$email\n:$name\n:$pnumber\n:$hours\n:$employed\n:$education\n:$yearexp\n:$expin\n:$interest\n:$datetime\n\n\n";
  
      fwrite($f, $volunteers);
  }

  fclose($f);

  echo "Volunteers information downloaded to file.";
} 

//called on if organization button is pressed to download organazation information
if(isset ($_POST['orgButton'])) { 
  
  $f = fopen("pdf/organizationHelpRequest.txt", "w");

  $query = "SELECT * FROM npo ORDER BY datetime DESC";

  $result = mysqli_query($con, $query);

  while($row = $result -> fetch_assoc()){
      $email = $row['email'];
      $name = $row['orgname'];
      $help = $row['help'];
      $datetime = $row['datetime'];

      $volunteers = "$email\n:$name\n:$help\n:$datetime\n\n\n";
  
      fwrite($f, $volunteers);
  }

  fclose($f);

  echo "Organization help information downloaded to file.";
} 
///////////////////////////////////////////////////////////////////////////////
//if delete user is pressed
if(isset($_POST['deluserbtn'])){

  if ($_SERVER['REQUEST_METHOD'] == "POST") {

    //post to database
    $delUser = htmlentities ($_POST['deluser']);
    
      if(!empty($delUser)){

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
        $stmt->bindParam(1, $delUser, PDO::PARAM_STR, 100);
        $stmt->execute();      
        
        //prepare stament and delete from login
        $stmt = $pdo->prepare($sqlVol);     
        $stmt->bindParam(1, $delUser, PDO::PARAM_STR, 100);
        $stmt->execute();  
        
        //prepare stament and delete from login
        $stmt = $pdo->prepare($sqlNpo);     
        $stmt->bindParam(1, $delUser, PDO::PARAM_STR, 100);
        $stmt->execute();   
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


        $message = "Account has been deleted!";
        echo "<script type='text/javascript'> alert('$message'); document.location.href='adminpage.php'; </script>";

    }else{
      $message = "Please enter valid information.";
      echo "<script type='text/javascript'>alert('$message'); document.location.href='adminpage.php'; </script>";
    }  
  }  
}
////////////////////////////////////////////////////////////////////////////////
//change user role
if(isset($_POST['upuser'])){

  $upuser = htmlentities ($_POST['upuser']);
  $role = htmlentities ($_POST['role']);

  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
      if(!empty($upuser)){

            try{
              $pdo = new PDO($attr, $USER, $PASS, $opts);
            }catch (PDOException $e){          
              throw new PDOException($e->getMessage(), (int)$e->getCode());
            }

            $upuserquery = "UPDATE userlogin SET role=? WHERE user_name=?";

            //prepare stament and update role
            $stmt = $pdo->prepare($upuserquery);     
            $stmt->bindParam(1, $role, PDO::PARAM_STR, 5);
            $stmt->bindParam(2, $upuser, PDO::PARAM_STR, 100);
            $stmt->execute();   
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            $message = "User account role has been updated! to $role";
            echo "<script type='text/javascript'> alert('$message'); document.location.href='adminpage.php'; </script>";
  
    }else{
      $message = "Please enter valid information.";
      echo "<script type='text/javascript'>alert('$message'); document.location.href='adminpage.php'; </script>";
    }  
  }  
}
//////////////////////////////////////////////////////////////////////////////////////
//change user password
if (isset($_POST['chgpassbtn'])){
  //change user password
  if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $chgUserPass = htmlentities ($_POST['chgUserPass']);
    $newpass = htmlentities ($_POST['newpass']);
    
    if(!empty($chgUserPass) && !empty($newpass)){

      try{
      $pdo = new PDO($attr, $USER, $PASS, $opts);
      }catch (PDOException $e){          
      throw new PDOException($e->getMessage(), (int)$e->getCode());
      }

      //hash the password
      $options = ['cost' => 10];
      $hashpass = password_hash($newpass, PASSWORD_BCRYPT, $options);

      $sql = "UPDATE userlogin SET password=? WHERE user_name=?";

      //prepare stament to input new hashed password
      $stmt = $pdo->prepare($sql);  
      $stmt->bindParam(1, $hashpass, PDO::PARAM_STR, 100);
      $stmt->bindParam(2, $chgUserPass, PDO::PARAM_STR, 100);
      $stmt->execute();

      $message = "Password reset successfully!";
      echo "<script type='text/javascript'> alert('$message'); document.location.href='adminpage.php'; </script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber Hack - Home</title>
    <link rel="stylesheet" href="form.css">

<?php
user_type_nav();
?>

</head>
<body>

<form method="post" class="registration-form" enctype="multipart/form-data"> 
    <div class="input-group">
    <label for="volButton">Download Volunteer Information</label>
    <input type="submit" name="volButton" class="btn" value="Download"> 
    </div>

    <div class="input-group">
    <label for="orgButton">Download Organization Help Request Information</label>
    <input type="submit" name="orgButton" class="btn" value="Download"> 
    </div>
</form> 

<form method="post" class="registration-form" enctype="multipart/form-data"> 
    <div class="input-group">
    <label for="deluser">Delete user:</label>
    <input type="text" name="deluser" class="form-control" placeholder="Enter username to be deleted"> 
    </div>

    <div>                
    <button onclick="return confirm('Are you sure you want to delete this account?')" class="btn" name="deluserbtn">Delete User</button>
    </div>
</form> 

<form method="post" class="registration-form" enctype="multipart/form-data"> 
    <div class="input-group">
    <label for="upuser">Change user role:</label>
    <input type="text" name="upuser" class="form-control" placeholder="Enter username of user whos role you are updating">     
    </div>

    <div class="input-group">
    <input type="checkbox" id="org" name="role" value="org"> <label for="org"> Change to organization</label>
    </div>

    <div class="input-group">
    <input type="checkbox" id="vol" name="role" value="vol"> <label for="vol"> Change to volunteer</label>
    </div>  

    <div class="input-group">
    <input type="checkbox" id="admin" name="role" value="admin"> <label for="admin"> Change to admin</label>
    </div>  

    <div>                
    <button type="submit" class="btn" name="upuserbtn">Update</button>
    </div>
</form> 

<form method="post" class="registration-form" enctype="multipart/form-data"> 
    <div class="input-group">
    <label for="chgpass">Change users password - Username:</label>
    <input type="text" name="chgUserPass" class="form-control" placeholder="Enter username of user you are updating">     
    </div>
    <div class="input-group">
    <label for="newpass">Change user password to:</label>
    <input type="text" name="newpass" class="form-control" placeholder="Enter new password">     
    </div> 

    <div>                
    <button type="submit" class="btn" name="chgpassbtn">Update</button>
    </div>
</form> 
    
<div class="panel-footer"> &copy; Cyber Hack </div>  

</body>
</html>