<?php

session_start();

require 'connection.php';
require 'functions.php';

check_login($con);
login_timeout();

//check if user is NPO or volunteer for orgpage.
if(isset($_SESSION['role'])){

    //if org send to volpage
    if($_SESSION['role'] == 'org'){

        header("Location:volpage.php");
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
show_help_request($con);
?>

<div class="panel-footer"> &copy; Cyber Hack </div>       

</body>
</html>