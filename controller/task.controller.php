<?php
session_start();
require_once __DIR__ . "/classes/configDB.php";
require_once __DIR__ . "/classes/project.php";

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: ../index.php');
    exit();
}

$db = new Database();
$conn = $db->getConnection();

// Handle different actions
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'get':
        // Get task details
        $taskId = $_GET['taskId'];
        $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = :taskId");
        $stmt->execute(['taskId' => $taskId]);
        $task = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($task);
        break;

    case 'updateStatus':
        // Update task status
        $taskId = $_POST['taskId'];
        $status = $_POST['status'];
        $stmt = $conn->prepare("UPDATE tasks SET status = :status WHERE id = :taskId");
        $stmt->execute([
            'status' => $status,
            'taskId' => $taskId
        ]);
        echo json_encode(['success' => true]);
        break;

    case 'delete':
        // Delete task
        $taskId = $_POST['taskId'];
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = :taskId");
        $stmt->execute(['taskId' => $taskId]);
        echo json_encode(['success' => true]);
        break;

    default:
        // Create or update task
        $taskId = $_POST['taskId'] ?? null;
        $projectId = $_POST['projectId'];
        $title = $_POST['title'];
        $description = $_POST['description'] ?? '';
        $dueDate = $_POST['dueDate'];
        $priority = $_POST['priority'];
        
        if ($taskId) {
            // Update existing task
            $stmt = $conn->prepare("
                UPDATE tasks 
                SET title = :title, 
                    description = :description,
                    due_date = :dueDate,
                    priority = :priority
                WHERE id = :taskId
            ");
            $stmt->execute([
                'title' => $title,
                'description' => $description,
                'dueDate' => $dueDate,
                'priority' => $priority,
                'taskId' => $taskId
            ]);
        } else {
            // Create new task
            $stmt = $conn->prepare("
                INSERT INTO tasks (project_id, title, description, due_date, priority, status, created_at)
                VALUES (:projectId, :title, :description, :dueDate, :priority, 'in_progress', NOW())
            ");
            $stmt->execute([
                'projectId' => $projectId,
                'title' => $title,
                'description' => $description,
                'dueDate' => $dueDate,
                'priority' => $priority
            ]);
        }
        
        header('Location: ../view/index.view.php');
        break;
}
