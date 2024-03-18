<?php

$options = ['cost' => 10];

$hashpass = password_hash($password, PASSWORD_BCRYPT, $options);

if (password_verify($password, $hashpass)){

    echo "Login Successful!";
}else{

    echo "Login Failed";
}