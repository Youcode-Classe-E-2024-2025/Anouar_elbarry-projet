<?php 
session_start();

// If not logged in, redirect to login page
if(!isset($_SESSION["username"])) {
    header("location: view/home.php");
    exit();
}

// If logged in, redirect to dashboard
header("location: view/dashboard.php");
exit();
