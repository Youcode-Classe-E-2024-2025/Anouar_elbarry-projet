<?php
session_start();
require_once __DIR__ . "/classes/configDB.php";
require_once __DIR__ . "/classes/project.php";
require_once __DIR__ . "/classes/user.php";

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Location: ../view/dashboard.php');
    exit();
}

// create user object
$userId = $_SESSION['userid'];
$userEmail = $_SESSION['email'];
$user = new User($userId, $userEmail);

$db = new Database();
$action = $_GET['action'] ?? '';
$project_id = $_GET['project_id'] ?? '';
$request_id = $_GET['request_id'] ?? '';

// Handle new join request
if ($action === 'join' && $project_id) {
    $userM_id = Project::createJoinRequest($db, $_SESSION['userid'], $project_id);
    if($userM_id){
        $_SESSION['success'] = 'Request sent successfully';
    }
    else{
        $_SESSION['error'] = 'Request already exists';
    }
    header('Location: ../view/dashboard.php#public-projects');
    exit();
}

// Handle request status update (accept/reject)
if ($request_id && in_array($action, ['accept', 'reject'])) {
    // First get the request details to get user_id and project_id
    $request = Project::getRequestById($db, $request_id);
    if (!$request) {
        $_SESSION['error'] = "Request not found";
        header('Location: ../view/dashboard.php#project-requests');
        exit();
    }

    $status = $action === 'accept' ? 'ACCEPTED' : 'REJECTED';
    $success = Project::updateRequestStatus($db, $request_id, $status);
    
    if ($success && $status === 'ACCEPTED') {
        // Use the user_id and project_id from the request
        $user->assignProjectToUser($request['project_id'], $request['user_id'], 'TEAM_MEMBER');
    }
    
    if ($success) {
        $_SESSION['success'] = "Request has been " . strtolower($status) . " successfully";
    } else {
        $_SESSION['error'] = "Failed to update request status";
    }
    
    header('Location: ../view/dashboard.php#project-requests');
    exit();
}

$_SESSION['error'] = 'Invalid request parameters';
header('Location: ../view/dashboard.php');
exit();
