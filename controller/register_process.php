<?php
require_once 'classes/user.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Trim and sanitize inputs
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // Create user instance
        $user = new User($username, $email, $password);
        // Attempt registration
        $result = $user->register($password);
        
        if ($result) {
            // Successful registration
            header('Location: ../view/auth/login.php');
            exit();
        } else {
            // Registration failed
            echo'MOCHKIL';
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