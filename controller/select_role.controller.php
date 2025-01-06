<?php
session_start();
require_once __DIR__ ."/classes/user.php";
if($_SERVER['REQUEST_METHOD'] == "POST") {
    $user = new User($_SESSION['username'], $_SESSION['email'] ,$_SESSION["password"]);
     if(isset($_POST["role"])){    
         $user->setRole($_POST['role']);
         header("location: ../view/dashboard.php");
     }
}