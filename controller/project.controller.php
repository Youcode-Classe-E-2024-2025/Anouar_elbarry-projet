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
    
    $user = new User($_SESSION['username'], $_SESSION['email']);
    // Get the creator's ID from session
    $creatorId = $_SESSION['id'];
    
    if (!$creatorId) {
       die('makaynch l id');
    }
    
    try {
        
        // Create the project in database
        $projectId = $user->creatproject($projectName, $projectDescription, $isPublic ,$creatorId);
        
        if ($projectId) {
            die('sf mcha');
        } else {
            die('malgach l id dial lprojct');
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
    
    header("Location: ../view/project-manager-dashboard.php");
    exit();
}