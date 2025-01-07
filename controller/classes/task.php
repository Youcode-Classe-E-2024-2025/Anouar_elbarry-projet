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

    // public function getAssignedMembers($taskId) {
    //     try {
    //         $conn = $this->db->getConnection();
    //         $query = "SELECT u.* FROM users u 
    //                  JOIN task_assignments ta ON u.id = ta.user_id 
    //                  WHERE ta.task_id = :taskId";
    //         $stmt = $conn->prepare($query);
    //         $stmt->execute([':taskId' => $taskId]);
    //         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     } catch (PDOException $e) {
    //         echo $e->getMessage();
    //         return [];
    //     }
    // }

    public function updateTask($title, $description, $priority, $dueDate){}
    public function updateStatus($status){}
    public function deletTask(){}
    public function addTag(){}
    public function removeTag(){}
}