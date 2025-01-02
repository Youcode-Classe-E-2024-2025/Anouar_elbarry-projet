<?php
require_once 'classes/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Trim and sanitize inputs
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // Create user instance
        $user = new User($username, $email, $password);
        // die("dddddddddd");
        // Attempt registration
        $result = $user->regester($username, $email, $password);
        
        if ($result) {
            // Successful registration
            header('Location: ../view/auth/login.php');
            exit();
        } else {
            // Registration failed
            header('Location: ../view/auth/register.php');
            exit();
        }
    } else {
        // Missing required fields
        header('Location: ../view/auth/register.php');
        exit();
    }
} else {
    // Not a POST request
    header('Location: ../view/auth/register.php');
    exit();
}