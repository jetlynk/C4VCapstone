<?php

session_start();

require 'connection.php';
require 'functions.php';

check_login($con);
login_timeout();

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
     
      <div class="main-heading"><h1>Welcome to Cyber Hack</h1><h3>One of Canada's top leading cyber security service providers for charities</h3></div>

      <div class="align"><img src="woman-hacked.jpg" alt="woman hacked" style="float: left; width: 450px; margin: 2rem;">
      <span> As a small business or NGO, is your cybersecurity program strong enough to protect against today's sophisticated cyber threats? 
        Can you offer paying for a cyber specialist to fix you system problems?
      </span>
      </div>
      
      <div class="align"><span>Cyber hack is a company that connects the providers of the cyber security specialists to the clients that they need this service. 
        Our clients are mainly non-for-profit organizations. Cyber Security Professionals can use our web application to apply to be one of our 
        volunteers and help in their service. Non-For-Profit Organizations may also apply to be a client for our company. So, our main goal is to connect the two 
        ends and make sure that the need for help is fulfilled.
      </span><img src="callhelp.png" alt="we can help" style="float: right; width: 300px; margin: 2rem;">
      
      </div>

      <div class="align"><img src="volunteer-hands.png" alt="volunteers needed" style="float: left; width: 400px; margin: 2rem;">
      <span>Cyber threats are ever increasing. If you have the skills and the time to donate to a worthy cause please fill out an application.</span>
      </div>
    <br><br>
      <div class="main-heading"><h2>Partnerships<h2>
      <div class="center">
      <a href="https://www.google.com"><img src="google.png" alt="Google" style="padding: 20px; width: 100px; margin: 2rem;"></a>  
      <a href="https://linkedin.com"><img src="linkedin.png" alt="Linkedin" style="padding: 20px; width: 100px; margin: 2rem;"></a>    
      <a href="https://github.com/"><img src="github.png" alt="github" style="padding: 20px; width: 100px; margin: 2rem;"></a>   
      </div>

<div class="panel-footer"> &copy; Cyber Hack </div>     
</body>
</html>
