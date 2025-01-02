<?php
require_once 'classes/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    if (isset($_POST['email']) && isset($_POST['password'])) {
        // Trim and sanitize inputs
        $email = trim($_POST['email']);
        $password = $_POST['password'];


$db = new Database();
$db->getConnection();
        $is_logged = User::login($db,$email,$password);
        if ($is_logged) {
            header('Location: ../view/user-dashboard.php');
        }
        else{
           
            echo 'error';
        }
        
    }
}