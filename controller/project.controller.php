<?php
session_start();
require_once __DIR__ . "/classes/configDB.php";
require_once __DIR__ . "/classes/user.php";
require_once __DIR__ . "/classes/project.php";

if (isset($_POST['creatProject'])) {
    // Get form data
    $projectName = trim($_POST['project_name']);
    $projectDescription = trim($_POST['project_description']);
    $isPublic = isset($_POST['isPublic']) ? 1 : 0;
    $dueDate = trim($_POST['dueDate']);
    
    $user = new User($_SESSION['username'], $_SESSION['email']);
    // Get the creator's ID from session
    $creatorId = $_SESSION['userid'];
    echo $_SESSION['userRole'];
    if (!$creatorId) {
       die('there is no id');
    }
    
    try {
        
        // Create the project in database
        $projectId = $user->creatproject($projectName, $projectDescription, $isPublic ,$creatorId,$dueDate);
        
        if ($projectId) {
            $_SESSION["projectCreated"] = "the project created successfuly";
        } else {
            die('malgach l id dial lprojct');
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
    
    header("Location: ../view/project-manager-dashboard.php");
    exit();
}