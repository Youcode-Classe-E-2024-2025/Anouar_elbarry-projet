<?php
class Task {
    //Attributes

    const  DONE = "DONE";
    const  IN_PROGRESS = "IN_PROGRESS";
    const  TODO = "TODO";
    private int $id;
    private string $title;
    private string $description;
    private string $priority;
    private string $dueDate;
    private string $createdAt;
    private Database $db;
    //Methodes
    public function __construct() {
        $this->db = new Database(); 
    }

    public static function creatTask(string $title, string $description,string $tag, string $priority, string $dueDate, $db, $project_id,$category_id, $members = []) {
        try {
            $conn = $db->getConnection();
            
            // First, create the task
            $query = "INSERT INTO tasks (title, description, priority, dueDate, project_id,category_id,tag) VALUES (:title, :description, :priority, :dueDate, :project_id, :category_id, :tag)";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':priority' => $priority,
                ':dueDate' => $dueDate,
                ':project_id' => $project_id,
                ':category_id' => $category_id,
                ':tag' => $tag
            ]);
            
            $taskId = $conn->lastInsertId();
            
            // Then, assign members if any
            if (!empty($members)) {
                $assignQuery = "INSERT INTO task_assignments (task_id, user_id, project_id) VALUES (:task_id, :user_id, :project_id)";
                $assignStmt = $conn->prepare($assignQuery);
                
                foreach ($members as $memberId) {
                    $assignStmt->execute([
                        ':task_id' => $taskId,
                        ':user_id' => $memberId,
                        ':project_id' => $project_id
                    ]);
                }
            }
            
            return $taskId;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
public static function getTaskByStatus($db, $status, $project_id) {
        $conn =  $db->getConnection();
        $query = 'SELECT * FROM tasks WHERE status = :status AND project_id = :project_id';
        $stmt = $conn->prepare($query);
        $stmt->bindParam('status', $status, PDO::PARAM_STR);
        $stmt->bindParam('project_id', $project_id, PDO::PARAM_STR);
        $stmt->execute(
            [
                'status'=> $status,
                'project_id'=> $project_id
            ]
        );
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tasks;
}
public static function getTasksByUserAndStatus($db, $user_id, $project_id, $status) {
    $conn = $db->getConnection();
    $query = "SELECT 
        t.id,
        t.title,
        t.description,
        t.tag,
        t.status,
        t.priority,
        t.dueDate,
        t.createdAt,
        u.username as user_name,
        c.name as category_name
    FROM tasks t
    INNER JOIN task_assignments ta ON t.id = ta.task_id
    INNER JOIN users u ON ta.user_id = u.id
    INNER JOIN projects p ON ta.project_id = p.id
    LEFT JOIN categories c ON t.category_id = c.id
    WHERE ta.project_id = :project_id 
    AND ta.user_id = :user_id";

    // Add status condition if provided
    if ($status) {
        $query .= " AND t.status = :status";
    }

    try {
        $stmt = $conn->prepare($query);
        
        $params = [
            ':user_id' => $user_id,
            ':project_id' => $project_id
        ];

        if ($status) {
            $params[':status'] = $status;
        }

        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching tasks: " . $e->getMessage());
    }
}
public static function getAllTaskByStatus($db, $status) {
        $conn =  $db->getConnection();
        $query = 'SELECT * FROM tasks WHERE status = :status';
        $stmt = $conn->prepare($query);
        $stmt->bindParam('status', $status, PDO::PARAM_STR);
        $stmt->execute(
            [
                'status'=> $status
            ]
        );
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tasks;
}
public static function getTaskById($db, $id) {
        $conn =  $db->getConnection();
        $query = 'SELECT * FROM tasks WHERE id = :id';
        $stmt = $conn->prepare($query);
        $stmt->bindParam('id', $id, PDO::PARAM_STR);
        $stmt->execute(
            [
                'id'=> $id
            ]
        );
        $task = $stmt->fetch(PDO::FETCH_ASSOC);
        return $task;
}
public static function getTaskMembers($db, $taskId, $projectId) {
    $conn = $db->getConnection();
    
    $query = "SELECT 
            u.id,
            u.username,
            u.email,
            u.role
        FROM users u
        INNER JOIN task_assignments ta ON u.id = ta.user_id
        WHERE ta.task_id = :task_id 
        AND ta.project_id = :project_id";

    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':task_id' => $taskId,
            ':project_id' => $projectId
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getTaskMembers: " . $e->getMessage());
        return [];
    }
}

    public function updateTask($title, $description, $priority, $dueDate){}
    public function updateStatus($taskId, $newStatus) {
        try {
            $sql = "UPDATE tasks SET status = :status WHERE id = :id";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':status', $newStatus);
            $stmt->bindParam(':id', $taskId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating task status: " . $e->getMessage());
            return false;
        }
    }
    public static function deletTask($taskId, $db, $projectId){
        $conn = $db->getConnection();
        
        // First delete from task_assignments
        $deleteAssignments = "DELETE FROM task_assignments WHERE task_id = :taskId AND project_id = :projectId";
        try {
            $stmt = $conn->prepare($deleteAssignments);
            $stmt->execute([
                ':taskId' => $taskId,
                ':projectId' => $projectId
            ]);
        } catch (PDOException $e) {
            error_log("Failed to delete task assignments: " . $e->getMessage());
            return false;
        }
        
        // Then delete the task
        $deleteTask = "DELETE FROM tasks WHERE id = :taskId AND project_id = :projectId";
        try {
            $stmt = $conn->prepare($deleteTask);
            $stmt->execute([
                ':taskId' => $taskId,
                ':projectId' => $projectId
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Failed to delete task: " . $e->getMessage());
            return false;
        }
    }
    public function addTag(){}
    public function removeTag(){}
}