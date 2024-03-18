<?php
/////////////////////////////////////////////////////////////////////////////////
//check if username and password match with user in database
function check_login($con){
    
    if(isset($_SESSION['user_name'])){

        $id = $_SESSION['user_name'];

        $query = "SELECT * from userlogin where user_name = '$id' limit 1";

        $result = mysqli_query($con, $query);
        
        if($result && mysqli_num_rows($result) > 0){
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }else{

    //redirect to login page
    header("Location: login.php");
    die;
    }
}
////////////////////////////////////////////////////////////////////////////////
//logout after 1 hour
function login_timeout(){

    if(isset($_SESSION['user_name'])){
        if (time()-$_SESSION["login_time_stamp"] > 3600){

            session_unset();
            session_destroy();

            $message = "Your session has ended! Please sign in again.";
            echo "<script type='text/javascript'>alert('$message'); document.location.href='login.php'; </script>";
        }
    }
}
///////////////////////////////////////////////////////////////////////////////
//check if user is NPO or volunteer
function user_type_nav(){

    if(isset($_SESSION['role'])){

        //if org load this nav bar
        if($_SESSION['role'] == 'org'){
?>
            <div class="navBar">
                <li><a href="index.php">Home</a></li>
                <li><a href="orp.php">Help Request Form</a></li>
                <li><a href="volPage.php">Available Volunteers</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="userprofilepage.php">Account Page</a></li>
                <li><a>Welcome <?php echo $_SESSION['user_name']; ?> !</a></li>
                <li><a href="logout.php">Logout</a></li>
            </div>

<?php
        //if volunteer load this nav bar
        }elseif($_SESSION['role'] == 'vol'){
?>
            <div class="navBar">
                <li><a href="index.php">Home</a></li>
                <li><a href="vrf.php">Volunteer Sign-up Form</a></li>
                <li><a href="orgPage.php">Charity Help Requests</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="userprofilepage.php">Account Page</a></li>
                <li><a>Welcome <?php echo $_SESSION['user_name']; ?> !</a></li>
                <li><a href="logout.php">Logout</a></li>
            </div>
<?php
        //if admin load this nav bar
        }elseif($_SESSION['role'] == 'admin'){
?>
            <div class="navBar">
                <li><a href="index.php">Home</a></li>
                <li><a href="volPage.php">Available Volunteers</a></li>
                <li><a href="orgPage.php">Charity Help Requests</a></li>
                <li><a href="adminpage.php">Admin Page</a></li>
                <li><a href="about.php">About</a></li>
                <li><a>Welcome <?php echo $_SESSION['user_name']; ?> !</a></li>
                <li><a href="logout.php">Logout</a></li>
            </div>
<?php

        }
    }
}
/////////////////////////////////////////////////////////////////////////////////////
function fileUpload(){

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
/////////////////////////////////////////////////////////////////////////////////////
function show_volunteers($con){

    $query = "SELECT * FROM volunteer ORDER BY datetime DESC";

    $result = mysqli_query($con, $query);

    //returns assoc array
    while ($row = mysqli_fetch_assoc($result)){ 

        $email = htmlspecialchars($row['email']);

?>

        <div class="text-box">
        <h2>Volunteers Name:</h2><br>
            <?php echo htmlspecialchars($row['fullname']); ?>
        <br><br>
        <h2>Weekly available hours:</h2><br>
            <?php echo htmlspecialchars($row['hours']); ?>
        <br><br>
        <h2>Education:</h2><br>
            <?php echo htmlspecialchars($row['education']); ?>
        <br><br>
        <h2>Years of experience:</h2><br>
            <?php echo htmlspecialchars($row['yearexp']); ?>
        <br><br>
        <h2>Skills and Experience:</h2><br>
            <?php echo htmlspecialchars($row['expin']); ?>
        <br><br>
        <h2>Type of projects they are interested in:</h2><br>
            <?php echo htmlspecialchars($row['interest']); ?>
        <br><br>
        <h2>To email Volunteer directly click email link below:</h2><br>
            <?php
                echo "<a href='mailto:".$row['email']."'>".$row['email']."</a></br>";
            ?>
            
        </div>
        <br>
        
<?php

    }
    echo "<br>";
    
}
/////////////////////////////////////////////////////////////////////////////////////
function show_help_request($con){

    $query = "SELECT * FROM npo ORDER BY datetime DESC";

    $result = mysqli_query($con, $query);

    //returns assoc array
    while ($row = mysqli_fetch_assoc($result)){ 

        $email = htmlspecialchars($row['email']);

?>

        <div class="text-box">
        <h2>Non-Profit Organization:</h2><br>
            <?php echo htmlspecialchars($row['orgname']); ?>
        <br><br>
        <h2>Help needed:</h2><br>
            <?php echo htmlspecialchars($row['help']); ?>
        <br><br>
        <h2>To email Organization directly click email link below:</h2><br>
            <?php
                echo "<a href='mailto:".$row['email']."'>".$row['email']."</a></br>";
            ?>
            
        </div>
        <br>
        
<?php

    }
    echo "<br>";
}