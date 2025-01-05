<?php
require_once 'classes/user.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    if (isset($_POST['email']) && isset($_POST['password'])) {
        // Trim and sanitize inputs
        $email = trim($_POST['email']);
        $password = $_POST['password'];
      
         // Create user instance
        

$db = new Database();
$db->getConnection();
        $is_logged = User::login($db,$email,$password);
        if ($is_logged) {
            $_SESSION["email"] = $email;
            $_SESSION["password"] = $password;
            $user_Data = User::getUserData($email);
            $_SESSION['username'] = $user_Data["username"];
            $_SESSION['userid'] = $user_Data["id"];
            $_SESSION['userRole'] = $user_Data["role"];
            $_SESSION['email'] = $user_Data["email"]; 
          
            header('Location: ../view/dashboard.php');
        }
        else{
           
            echo 'user not found';
        }
        
    }
}
