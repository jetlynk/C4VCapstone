<?php

session_start();

require 'connection.php';
require 'functions.php';

check_login($con);
login_timeout();

//check if user is NPO or volunteer for volpage.
if(isset($_SESSION['role'])){

    //if vol send to orgpage
    if($_SESSION['role'] == 'vol'){

        header("Location:orgpage.php");
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


<?php
show_volunteers($con);
?>

</body>
<footer>
<div class="panel-footer"> &copy; Cyber Hack </div>   
</footer>
</html>