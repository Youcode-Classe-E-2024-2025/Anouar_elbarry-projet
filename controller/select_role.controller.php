<?php
session_start();
require_once __DIR__ ."/classes/configDB.php";
require_once __DIR__ ."/classes/user.php";

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $user = new User($_SESSION['username'], $_SESSION['email'], $_SESSION["password"]);
    $user->setId($_SESSION['userid']);
    
    if(isset($_POST["role"])) {   
        switch($_POST["role"]) {
            case "PROJECT_MANAGER": 
                $user->setRole(User::ROLE_PROJECT_MANAGER); 
                break;
            case "TEAM_MEMBER": 
                $user->setRole(User::ROLE_TEAM_MEMBER); 
                break;
            default:
                header("Location: ../view/role_selection.php");
                exit();
        }
        
        $_SESSION['userRole'] = $user->getRole();
        header("Location: ../view/dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Please select a role";
        header("Location: ../view/role_selection.php");
        exit();
    }
}