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
    <title>Cyber Hack - About</title>
    <link rel="stylesheet" href="form.css">
    
<?php
user_type_nav();
?>

</head>
<body>

<div class="bg">
  
        <div class="text-box">
        <h2>Vision:</h2><br>
        To lead Canada's cyber security services for charities while motivating and empowering Canadian 
            Veterans to trace and continue their career in the cyber security field.
        </div><br>

        <div class="text-box">
        <h2>Mission:</h2><br>
        <span> In an increasingly connected and digitized world no business is safe from cyber-attacks. 
            At Cyber Hack our mission is to educate and enable charities to protect their computer and information systems allowing them to focus on maximizing their social impact. 
        </span></div><br>

        <div class="text-box">
        <h2>Core Values:</h2>
        </div>

        <div class="text-box">
        <h4>Education</h4><br>
        <span>Knowledge is power. We want to empower the next generation of cyber security talent by providing educational resources. We want to educate the planet to 
            achieve our vision of creating a more secure planet.
        </span></div><br>

        <div class="text-box">
        <h4>Professional Development</h4><br>
        <span>Despite the growing demand for cyber security there is often a barrier of experience in place to prevent students and new graduates from entering the industry. 
            We want to bridge this gap by giving aspiring cyber security professionals an opportunity to gain relevant experience.
        </span></div><br>

        <div class="text-box">
        <h4>Integrity</h4><br>
        <span>We hold ourselves accountable by measuring ourselves against the highest standards of integrity and fiscal responsibility.
        </span></div><br>

        <div class="text-box">
        <h4>Trust</h4><br>
        <span>We build trust through constructive, candid communication that serves the common good. We value the talent, time and intentions of all of our volunteers.
        </span></div><br>

        <div class="text-box">
        <h4>Passion</h4><br>
        <span>We love cyber security. All the industry professionals that work with our organization love cyber security with a passion and do this outside their career. 
            We love the idea of being able to apply our talent to give back to our community.
        </span></div><br>
</div>

<div class="panel-footer">
    &copy; Cyber Hack
</div>
    
</body>
</html>