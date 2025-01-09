<?php 
require_once "configDB.php";
class Project {
    // Attributes
    private int $id;
    private string $name;
    private string $description;
    private bool $isPublic;
    private string $dueDate;
    private string $createdAt;
    private int $creator;
    private Database $db;

    // Methodes

    public function __construct($name, $description, $isPublic, $creator,$dueDate){
        $this->db = new Database();
        $this->name = $name;
        $this->description = $description;
        $this->isPublic = $isPublic;
        $this->creator = $creator;
        $this->dueDate = $dueDate;
    }
    public function getId(){}
    public static function delet($project_id,$db){
        try {
            $conn = $db->getConnection();
            $query = "DELETE FROM projects WHERE id = :project_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam("project_id", $project_id, PDO::PARAM_INT);
            return $stmt->execute([
                "project_id" => $project_id
            ]);
        } catch (PDOException $e) {
            // Log the error
            error_log("Failed to delete project: " . $e->getMessage());
            // You can throw a custom exception or return false
            return false;
        }
    }
    public function addMember(){}
    public static function removeMember($user_id,$project_id,$db){
        try {
            $conn = $db->getConnection();
            $query = "DELETE FROM team_members WHERE user_id = :user_id AND project_id = :project_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam("project_id", $project_id, PDO::PARAM_INT);
            return $stmt->execute([
                "user_id" => $user_id,
                "project_id" => $project_id
            ]);
        } catch (PDOException $e) {
            error_log("Failed to delete member: " . $e->getMessage());
            
            return false;
        }
    }
    public static function getPublicProjects($db){
    $conn = $db->getConnection();
    $query = "SELECT * FROM projects WHERE isPublic = 1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $projects;
    }
    
    public static function getProjectRequests($db, $project_id = null) {
        try {
            $conn = $db->getConnection();
            $query = "SELECT 
                r.id as request_id,
                r.status,
                r.request_date,
                r.response_date,
                p.id as project_id,
                p.name as project_name,
                p.description as project_description,
                u.id as user_id,
                u.username,
                u.email
            FROM project_join_requests r
            INNER JOIN projects p ON r.project_id = p.id
            INNER JOIN users u ON r.user_id = u.id";
            
            if ($project_id) {
                $query .= " WHERE p.id = :project_id";
                $stmt = $conn->prepare($query);
                $stmt->execute(['project_id' => $project_id]);
            } else {
                $stmt = $conn->prepare($query);
                $stmt->execute();
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching project requests: " . $e->getMessage());
            return [];
        }
    }
    public static function getRequestsBystatus($db,$status ,$project_id = null) {
        try {
            $conn = $db->getConnection();
            $query = "SELECT 
                r.id as request_id,
                r.status,
                r.request_date,
                r.response_date,
                p.id as project_id,
                p.name as project_name,
                p.description as project_description,
                u.id as user_id,
                u.username,
                u.email
            FROM project_join_requests r
            INNER JOIN projects p ON r.project_id = p.id
            INNER JOIN users u ON r.user_id = u.id
            WHERE r.status = :status;
            ";
            
            if ($project_id) {
                $query .= "WHERE r.status = :status AND p.id = :project_id";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":project_id", $project_id);
                $stmt->bindParam(":status", $status);
                $stmt->execute([
                    'project_id' => $project_id,
                    'status' => $status
                ]);
            }
            else {
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":status", $status);
                $stmt->execute(
                    ['status' => $status]
                );
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching project requests: " . $e->getMessage());
            return [];
        }
    }

    public static function getUserRequests($db, $user_id) {
        try {
            $conn = $db->getConnection();
            $query = "SELECT 
                r.id as request_id,
                r.status,
                r.request_date,
                r.response_date,
                p.id as project_id,
                p.name as project_name,
                p.description as project_description
            FROM project_join_requests r
            INNER JOIN projects p ON r.project_id = p.id
            WHERE r.user_id = :user_id
            ORDER BY r.request_date DESC";
            
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            
            // Debug information
            error_log("User ID being queried: " . $user_id);
            error_log("Number of requests found: " . $stmt->rowCount());
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Results: " . print_r($results, true));
            
            return $results;
        } catch (PDOException $e) {
            error_log("Error in getUserRequests: " . $e->getMessage());
            error_log("SQL Query: " . $query);
            error_log("User ID: " . $user_id);
            return [];
        }
    }

    public static function getRequestById($db, $request_id) {
        try {
            $conn = $db->getConnection();
            $query = "SELECT 
                r.id as request_id,
                r.user_id,
                r.project_id,
                r.status,
                r.request_date,
                r.response_date,
                p.name as project_name,
                u.username
            FROM project_join_requests r
            INNER JOIN projects p ON r.project_id = p.id
            INNER JOIN users u ON r.user_id = u.id
            WHERE r.id = :request_id";
            
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':request_id', $request_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching request by ID: " . $e->getMessage());
            return false;
        }
    }

    public static function updateRequestStatus($db, $request_id, $status) {
        try {
            $conn = $db->getConnection();
            $query = "UPDATE project_join_requests 
                     SET status = :status, 
                         response_date = CURRENT_TIMESTAMP 
                     WHERE id = :request_id";
            $stmt = $conn->prepare($query);
            return $stmt->execute([
                'status' => $status,
                'request_id' => $request_id
            ]);
        } catch (PDOException $e) {
            error_log("Error updating request status: " . $e->getMessage());
            return false;
        }
    }

    public static function createJoinRequest($db, $user_id, $project_id) {
        try {
            $conn = $db->getConnection();
            
            // Check if request already exists
            $checkQuery = "SELECT id FROM project_join_requests 
                         WHERE user_id = :user_id AND project_id = :project_id";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->execute([
                'user_id' => $user_id,
                'project_id' => $project_id
            ]);
            
            error_log("Checking for existing request - User ID: $user_id, Project ID: $project_id");
            error_log("Existing requests found: " . $checkStmt->rowCount());
            
            if ($checkStmt->rowCount() > 0) {
                error_log("Request already exists");
                return false;
            }
            
            // Create new request
            $query = "INSERT INTO project_join_requests (user_id, project_id) 
                     VALUES (:user_id, :project_id)";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                'user_id' => $user_id,
                'project_id' => $project_id
            ]);
            
            error_log("New request created - Last Insert ID: " . $conn->lastInsertId());
            return $user_id;
            
        } catch (PDOException $e) {
            error_log("Error creating join request: " . $e->getMessage());
            error_log("User ID: $user_id, Project ID: $project_id");
            return false;
        }
    }
}