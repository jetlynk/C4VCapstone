<?php

session_start();

require 'connection.php';
require 'functions.php';

check_login($con);
login_timeout();

try{
  $pdo = new PDO($attr, $USER, $PASS, $opts);
}catch (PDOException $e){
  throw new PDOException($e->getMessage(), (int)$e->getCode());
}

$user = $_SESSION['user_name'];
$userEmail = $_SESSION['email'];

//check if there is already information on the organization
$query = "SELECT * from npo where user_name = '$user' limit 1"; 

$result = mysqli_query($con, $query);

if($result && mysqli_num_rows($result) > 0){
    
    $user_data = mysqli_fetch_assoc($result);

    //fill in form with previous input
    $name = $user_data['orgname'];
    
    //post info
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $email = htmlentities ($_POST['email']);
        $orgname = htmlentities ($_POST['orgname']);
        $help = htmlentities ($_POST['help']);

        $data = [
            'email' => $email,
            'orgname' => $orgname,
            'help' => $help,
            'user_name' => $user,
        ];
        
        $sql = "UPDATE npo SET email=:email, orgname=:orgname, help=:help WHERE user_name=:user_name";
        $stmt= $pdo->prepare($sql);
        $stmt->execute($data);

        $message = "Information Submitted";
        echo "<script type='text/javascript'>alert('$message'); document.location.href='orp.php';</script>";
    }

}else{

    $name = "";

    //post info
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $email = htmlentities ($_POST['email']);
        $orgname = htmlentities ($_POST['orgname']);
        $help = htmlentities ($_POST['help']);

        $stmt = $pdo->prepare('INSERT INTO npo (`email`, `user_name`, `orgname`, `help`) VALUES(?,?,?,?)'); 
        $stmt->bindParam(1, $email, PDO::PARAM_STR, 50);
        $stmt->bindParam(2, $user, PDO::PARAM_STR, 100);
        $stmt->bindParam(3, $orgname, PDO::PARAM_STR, 75); 
        $stmt->bindParam(4, $help, PDO::PARAM_STR, 300);
        $stmt->execute([$email, $user, $orgname, $help]);

        $message = "Information Submitted";
        echo "<script type='text/javascript'>alert('$message'); document.location.href='orp.php';</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber Hack - Help Request</title>
    <link rel="stylesheet" href="form.css">
</head>
<body>

<?php
user_type_nav();
?>

<div class="panel-heading"> <h1>Charity Organization Help Request Form</h1></div>

<form method="post" action="" class="registration-form" autofill>

    <div class="input-group">
        <label for="InputEmail">Email address</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" placeholder="Email" >
    </div>

    <div class="input-group">
        <label for="InputName">Organization name</label>
        <input type="text" class="form-control" id="orgname" name="orgname" value="<?php echo $name; ?>" placeholder="Organization name">                    
    </div>

    <div class="input-group">
        <label for="InputExp">Help services needed</label><br>
        <textarea type="text" rows="10" cols="54" id="help" name="help" class="tall-form-control" placeholder="Help services needed"></textarea>                    
    </div>
    
    <div>
        <br><br><br><br>
    <button type="submit" class="btn">Submit</button>
    </div>

</form>


<div class="panel-footer"> &copy; Cyber Hack </div>   

</body>
</html>