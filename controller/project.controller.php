<?php
session_start();
require_once __DIR__ . "/classes/configDB.php";
require_once __DIR__ . "/classes/user.php";
require_once __DIR__ . "/classes/project.php";
$db = new Database();
if (isset($_POST['newProjectForm'])) {
    // Get form data
    $projectName = trim($_POST['project_name']);
    $projectDescription = trim($_POST['project_description']);
    $isPublic = isset($_POST['isPublic']) ? 1 : 0;
    $dueDate = $_POST['dueDate'];
    $creatorId = $_SESSION['userid'];

    // Create user object
    $user = new User($_SESSION['username'], $_SESSION['email']);
    $user->setId($creatorId);
    
    try {
        // Create the project
        $projectId = $user->creatproject($projectName, $projectDescription, $isPublic, $creatorId, $dueDate);
        
        if ($projectId) {
            // First assign the creator as a project manager
            $user->assignProjectToUser($projectId, $creatorId, 'PROJECT_MANAGER');

            // Then assign selected team members (only as TEAM_MEMBER)
            if (isset($_POST['member_roles']) && is_array($_POST['member_roles'])) {
                foreach ($_POST['member_roles'] as $memberId => $role) {
                    if (!empty($role)) {
                        // Force role to be TEAM_MEMBER regardless of what was selected
                        $user->assignProjectToUser($projectId, $memberId, 'TEAM_MEMBER');
                    }
                }
            }
            $_SESSION["success"] = "Project created successfully";
        } else {
            $_SESSION["error"] = "Failed to create the project";
        }
    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();
    }
    
    header("Location: ../view/dashboard.php");
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

if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['action'] === 'delet'){
    $project_id = $_GET['project_id'];
    $project = Project::delet($project_id, $db );
    if(isset($project)){
        $_SESSION['success'] = 'the project have been deleted sucessfuly';
        header('Location: ../view/dashboard.php');
    }
}
if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['action'] === 'delet_member'){
    $member_id = $_GET['member_id'];
    $project_id = $_GET['project_id'];
    $project = Project::removeMember($member_id,$project_id, $db );
    if(isset($project)){
        // echo'member have been removed sucessfuly';
        $_SESSION['successD'] = 'member have been removed sucessfuly';
        header('Location: ../view/components/project_details.php?project_id=' . $project_id);
    }
}