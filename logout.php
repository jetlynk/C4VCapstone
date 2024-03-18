<?php

session_start();
session_unset();
session_destroy();

//Back to login page
header("location: ../Capstone/login.php?error=none");