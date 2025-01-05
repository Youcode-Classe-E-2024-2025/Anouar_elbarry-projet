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
        // Create the project
        $projectId = $user->creatproject($projectName, $projectDescription, $isPublic, $creatorId, $dueDate);
        
        if ($projectId) {
            // Handle team member assignments if any
            if (isset($_POST['member_roles']) && is_array($_POST['member_roles'])) {
                foreach ($_POST['member_roles'] as $memberId => $role) {
                    if (!empty($role)) {
                        $user->assignProjectToUser($projectId, $memberId, $role);
                    }
                }
            }
            $_SESSION["projectCreated"] = "The project was created successfully";
        } else {
            $_SESSION["error"] = "Failed to create the project";
        }
    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();
    }
    
    header("Location: ../view/project-manager-dashboard.php");
    exit();
}

// Handle project member assignment
if(isset($_POST['assignMember'])) {
    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $projectId = $_POST['project_id'];
    $assignedUserId = $_POST['user_id'];
    $role = $_POST['member_role'] ?? 'TEAM_MEMBER';
    $creatorId = $_SESSION['userid']; // This is the project manager's ID

    // Debug information
    error_log("Attempting to assign user {$assignedUserId} to project {$projectId} with role {$role}");
    error_log("Creator ID: {$creatorId}");

    // First verify if the logged-in user is the project creator
    $user = new User($_SESSION['username'], $_SESSION['email']);
    $user->setId($creatorId);
    
    // Get project details to verify creator
    $project = $user->getProjectById($projectId);
    
    if ($project && $project['creator_id'] == $creatorId) {
        if($user->assignProjectToUser($projectId, $assignedUserId, $role)) {
            $_SESSION['success'] = "Member successfully assigned to the project";
            error_log("Successfully assigned user to project");
        } else {
            $_SESSION['error'] = "Failed to assign member to the project";
            error_log("Failed to assign user to project");
        }
    } else {
        $_SESSION['error'] = "You don't have permission to assign members to this project";
        error_log("Permission denied - Creator ID mismatch or project not found");
        error_log("Project data: " . print_r($project, true));
    }
    
    header('Location: ../view/project-manager-dashboard.php');
    exit();
}

// Handle project member removal
if(isset($_POST['removeMember'])) {
    $projectId = $_POST['project_id'];
    $memberUserId = $_POST['user_id'];
    $creatorId = $_SESSION['userid']; // This is the project manager's ID

    $user = new User($_SESSION['username'], $_SESSION['email']);
    $user->setId($creatorId);

    // Verify if the logged-in user is the project creator
    $project = $user->getProjectById($projectId);
    
    if ($project && $project['creator_id'] == $creatorId) {
        if($user->removeProjectMember($projectId, $memberUserId)) {
            $_SESSION['success'] = "Member successfully removed from the project";
        } else {
            $_SESSION['error'] = "Failed to remove member from the project";
        }
    } else {
        $_SESSION['error'] = "You don't have permission to remove members from this project";
    }
    
    header('Location: ../view/project-manager-dashboard.php');
    exit();
}