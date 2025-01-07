<?php
session_start();
require_once __DIR__ . "/classes/configDB.php";
require_once __DIR__ . "/classes/project.php";
require_once __DIR__ . "/classes/task.php";
require_once __DIR__ . "/classes/category.php";

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: ../index.php');
    exit();
}
$task = new Task();
$db = new Database();
$conn = $db->getConnection();
$project_id = $_SESSION["project_id"];
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $dueDate = trim($_POST['dueDate']);
    $priority = trim($_POST['priority']);
    $category = trim($_POST['category']);
    $tag = trim($_POST['tag']);
    
    // Get category ID
    $category_id = Category::getByname($category, $db);
    if (!$category_id) {
        header('Location: ../view/index.view.php?error=invalid_category');
        exit();
    }
    
    // Get assigned members array from checkboxes
    $assignedMembers = [];
    if (isset($_POST['assignedTo']) && is_array($_POST['assignedTo'])) {
        foreach ($_POST['assignedTo'] as $memberId) {
            if (is_numeric($memberId)) {
                $assignedMembers[] = (int)$memberId;
            }
        }
    }

    // Create task
    $Task_id = Task::creatTask(
        $title, 
        $description, 
        $tag,
        $priority, 
        $dueDate,
        $db, 
        $project_id,
        $category_id, 
        $assignedMembers
    );

    if ($Task_id) {
        header('Location: ../view/index.view.php?success=task_created');
        exit();
    } else {
        header('Location: ../view/index.view.php?error=task_creation_failed');
        exit();
    }
}