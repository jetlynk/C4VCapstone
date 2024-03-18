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

//check if there is already information on the volunteer
$query = "SELECT * from volunteer where user_name = '$user' limit 1"; 

$result = mysqli_query($con, $query);

if($result && mysqli_num_rows($result) > 0){

    $user_data = mysqli_fetch_assoc($result);

    //fill in form with previous input
    $email = $user_data['email'];
    $fullname = $user_data['fullname'];
    $pnumber = $user_data['pnumber'];
    $hours = $user_data['hours'];
    $employed = $user_data['employed'];
    $education = $user_data['education'];
    $yearexp = $user_data['yearexp'];
    
    //post info
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        if(!empty($_FILES['backcheck']['name']) && !empty($_FILES['ref']['name']) && !empty($_FILES['resume']['name'])){

            foreach($_FILES as $file){
                $newfilename = $email .str_replace(" ", "", basename( $file['name']));
                $target_dir = "pdf/";
                $target_file = $target_dir . basename($file["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            
                // Check if image file is a actual pdf or fake pdf
                if(isset($_POST["submit"])) {
                $check = getfilesize($file['name']["tmp_name"]);
            
            
                if($check !== false) {
                    $uploadOk = 1;
                } else {
                $message = " File is not an pdf.";
                echo "<script type='text/javascript'>alert('$message');  document.location.href='vrf.php'; </script>";
                    $uploadOk = 0;
                }
                }
            
                // Check file size
                if ($file["size"] > 500000) {
                $message = " Sorry, your file is too large.";
                echo "<script type='text/javascript'>alert('$message');  document.location.href='vrf.php'; </script>";
                    $uploadOk = 0;
                }
            
                // Allow certain file formats
                if($imageFileType != "pdf" ) {
                    $message = " Sorry, only PDF files are allowed.";
                    echo "<script type='text/javascript'>alert('$message');  document.location.href='vrf.php'; </script>";
                    $uploadOk = 0;
                }
            
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                $message = " Sorry, ". htmlspecialchars( basename( $file['name'])). " was not uploaded.";
                echo "<script type='text/javascript'>alert('$message');  document.location.href='vrf.php'; </script>";
            
                    // if everything is ok, try to upload file
                } else {
                    if ($uploadOk == 1) {                                
            
                        //uploads file and adds email address to file name
                        move_uploaded_file($file['tmp_name'], "pdf/". $newfilename);
                        $message = " The file ". htmlspecialchars( basename( $file['name'])). " has been uploaded.";
                        echo "<script type='text/javascript'>alert('$message'); </script>";
                    } else {
                        $message = "Sorry, there was an error uploading your file!";
                        echo "<script type='text/javascript'>alert('$message'); document.location.href='vrf.php'; </script>";
                    }
                }        
            }
        }

        $email = htmlentities ($_POST['email']);
        $fullname = htmlentities ($_POST['fullname']);
        $pnumber = htmlentities ($_POST['pnumber']);
        $hours = htmlentities ($_POST['hours']);
        $employed = htmlentities ($_POST['employed']);
        $education = htmlentities ($_POST['education']);
        $yearexp = htmlentities ($_POST['yearexp']);
        $expin = htmlentities ($_POST['expin']);
        $interest = htmlentities ($_POST['interest']);

        if ((!is_numeric($hours)) || ($hours < 0)){
            $hours = 0;
        }
        if ((!is_numeric($yearexp)) || ($yearexp < 0)){
            $yearexp = 0;
        }

        $data = [
            'email' => $email,
            'fullname' => $fullname,
            'pnumber' => $pnumber,
            'hours' => $hours,
            'employed' => $employed,
            'education' => $education,
            'yearexp' => $yearexp,
            'expin' => $expin,
            'interest' => $interest,
            'user_name' => $user,
        ];
        
        $sql = "UPDATE volunteer SET email=:email, fullname=:fullname, hours=:hours, pnumber=:pnumber, employed=:employed, education=:education, yearexp=:yearexp, expin=:expin, interest=:interest WHERE user_name=:user_name";
        $stmt= $pdo->prepare($sql);
        $stmt->execute($data);

        $message = "Information Submitted";
        echo "<script type='text/javascript'>alert('$message'); document.location.href='vrf.php'; </script>";
    }
}else{

    $email = "";
    $fullname = "";
    $pnumber = "";
    $hours = "";
    $employed = "";
    $education = "";
    $yearexp = "";

    //post info
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $email = htmlentities ($_POST['email']);
        $fullname = htmlentities ($_POST['fullname']);
        $pnumber = htmlentities ($_POST['pnumber']);
        $hours = htmlentities ($_POST['hours']);
        $employed = htmlentities ($_POST['employed']);
        $education = htmlentities ($_POST['education']);
        $yearexp = htmlentities ($_POST['yearexp']);
        $expin = htmlentities ($_POST['expin']);
        $interest = htmlentities ($_POST['interest']);

        if ((!is_numeric($hours)) || ($hours < 0)){
            $hours = 0;
        }
        if ((!is_numeric($yearexp)) || ($yearexp < 0)){
            $yearexp = 0;
        }

        //check if files have been selected to be uploaded
        if(!empty($_FILES['backcheck']['name'])){
            if(!empty($_FILES['ref']['name'])){
                if(!empty($_FILES['resume']['name'])){
        
                    foreach($_FILES as $file){
                        $newfilename = $email .str_replace(" ", "", basename( $file['name']));
                        $target_dir = "pdf/";
                        $target_file = $target_dir . basename($file["name"]);
                        $uploadOk = 1;
                        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                    
                        // Check if image file is a actual pdf or fake pdf
                        if(isset($_POST["submit"])) {
                        $check = getfilesize($file['name']["tmp_name"]);
                    
                    
                        if($check !== false) {
                            $uploadOk = 1;
                        } else {
                        $message = " File is not an pdf.";
                        echo "<script type='text/javascript'>alert('$message');  document.location.href='vrf.php'; </script>";
                            $uploadOk = 0;
                        }
                        }
                    
                        // Check file size
                        if ($file["size"] > 500000) {
                        $message = " Sorry, your file is too large.";
                        echo "<script type='text/javascript'>alert('$message');  document.location.href='vrf.php'; </script>";
                            $uploadOk = 0;
                        }
                    
                        // Allow certain file formats
                        if($imageFileType != "pdf" ) {
                            $message = " Sorry, only PDF files are allowed.";
                            echo "<script type='text/javascript'>alert('$message');  document.location.href='vrf.php'; </script>";
                            $uploadOk = 0;
                        }
                    
                        // Check if $uploadOk is set to 0 by an error
                        if ($uploadOk == 0) {
                        $message = " Sorry, ". htmlspecialchars( basename( $file['name'])). " was not uploaded.";
                        echo "<script type='text/javascript'>alert('$message');  document.location.href='vrf.php'; </script>";
                    
                            // if everything is ok, try to upload file
                        } else {
                            if ($uploadOk == 1) {                                
                    
                                //uploads file and adds email address to file name
                                move_uploaded_file($file['tmp_name'], "pdf/". $newfilename);
                                $message = " The file ". htmlspecialchars( basename( $file['name'])). " has been uploaded.";
                                echo "<script type='text/javascript'>alert('$message'); </script>";
                            } else {
                                $message = "Sorry, there was an error uploading your file!";
                                echo "<script type='text/javascript'>alert('$message'); document.location.href='vrf.php'; </script>";
                            }
                        }        
                    }

                    // inserts cleaned information into database
                    $stmt = $pdo->prepare('INSERT INTO volunteer (`email`, `user_name`, `fullname`, `pnumber`, `hours`, `employed`, `education`, `yearexp`, `expin`, `interest`) VALUES(?,?,?,?,?,?,?,?,?,?)'); 
                    $stmt->bindParam(1, $email, PDO::PARAM_STR, 50);
                    $stmt->bindParam(2, $user, PDO::PARAM_STR, 100);
                    $stmt->bindParam(3, $fullname, PDO::PARAM_STR, 75); 
                    $stmt->bindParam(4, $pnumber, PDO::PARAM_STR, 14);
                    $stmt->bindParam(5, $hours, PDO::PARAM_STR, 2);
                    $stmt->bindParam(6, $employed, PDO::PARAM_STR, 100);
                    $stmt->bindParam(7, $education, PDO::PARAM_STR, 200);
                    $stmt->bindParam(8, $yearexp, PDO::PARAM_STR, 2);
                    $stmt->bindParam(9, $expin, PDO::PARAM_STR, 300);
                    $stmt->bindParam(10, $interest, PDO::PARAM_STR, 300);
                    $stmt->execute([$email, $user, $fullname, $pnumber, $hours, $employed, $education, $yearexp, $expin, $interest]);      
                    
                    $message = "Information Submitted";
                    echo "<script type='text/javascript'>alert('$message'); document.location.href='vrf.php';</script>";
                }else{
        
                    $message = "Please select resume pdf to upload!";
                    echo "<script type='text/javascript'>alert('$message'); document.location.href='vrf.php'; </script>";
                    }
            }else{
        
                $message = "Please select References PDF file to upload!";
                echo "<script type='text/javascript'>alert('$message'); document.location.href='vrf.php'; </script>";                                                            
            }
        }else{   
        
        $message = "Please select background check pdf to upload!";
        echo "<script type='text/javascript'>alert('$message'); document.location.href='vrf.php'; </script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber Hack - Volunteer Registration</title>
    <link rel="stylesheet" href="form.css">

<?php
user_type_nav();
?>

</head>
<body>

<div class="panel-heading"><h1>Volunteer Registration Form</h1></div>
                
<form method="post" class="registration-form" enctype="multipart/form-data">

    <div class="input-group">
        <label for="InputEmail">Email address</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" placeholder="Email" >
    </div>

    <div class="input-group">
        <label for="InputName">Full Name</label>
        <input type="text" class="form-control" id="InputName" name="fullname" value="<?php echo $fullname; ?>" placeholder="Full Name">                    
    </div>
    <div class="input-group">
        <label for="InputPN">Phone Number</label>
        <input type="tel" class="form-control" id="InputPN" name="pnumber" value="<?php echo $pnumber; ?>" placeholder="Phone Number">                    
    </div>
    <div class="input-group">
        <label for="InputHours">Number of hours able to commit weekly</label>
        <input type="number" class="form-control" id="InputHours" name="hours" value="<?php echo $hours; ?>" placeholder="Number of hoursto commit weekly">                    
    </div>
    <div class="input-group">
        <label for="InputPos">Position if currently employed (otherwise type N/A)</label>
        <input type="text" class="form-control" id="InputPos" name="employed" value="<?php echo $employed; ?>" placeholder="Position if currently employed (otherwise type N/A)">                    
    </div>
    <div class="input-group">
        <label for="InputEd">Education level</label>
        <input type="text" class="form-control" id="InputEd" name="education" value="<?php echo $education; ?>" placeholder="Education level">                    
    </div>
    <div class="input-group">
        <label for="InputYExp">Years of Experience</label>
        <input type="number" class="form-control" id="InputYExp" name="yearexp" value="<?php echo $yearexp; ?>" placeholder="Years of Experience">                    
    </div>
    <div class="input-group">
        <label for="InputExp">Experienced in:</label><br>
        <textarea id="InputExp" name="expin" class="tall-form-control" placeholder="Experienced in: ?" rows="10" cols="54"></textarea>                    
    </div>
    <div class="input-group-tall">
        <label for="InputInt">Projects interested in</label><br>
        <textarea id="InputInt" name="interest" class="tall-form-control" placeholder="Projects interested in" rows="10" cols="54"></textarea>                    
    </div>
    <div class="input-group">
        <label for="backcheck">Criminal background check</label><br>
        <input type="file" id="backcheck" name="backcheck" accept=".pdf">
        <p class="help-block">Please submit criminal background check here</p>
        </div>
        <div class="input-group">
        <label for="ref">References</label><br>
        <input type="file" id="ref" name="ref" accept=".pdf">
        <p class="help-block">Please submit references here</p>
        </div>
        <div class="input-group">
        <label for="resume">Resume</label><br>
        <input type="file" id="resume" name="resume" accept=".pdf">
        <p class="help-block">Please submit Resume here</p>
        </div>
<div>
    <button type="submit" class="btn">Submit</button>
</div>
</form>

<div class="panel-footer"> &copy; Cyber Hack </div>    

</body>
</html>