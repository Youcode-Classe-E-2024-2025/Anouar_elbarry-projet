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
    public function removeMember($project_id,$db){
        try {
            $conn = $db->getConnection();
            $query = "DELETE FROM team WHERE id = :project_id";
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
    public static function getPublicProjects($db){
    $conn = $db->getConnection();
    $query = "SELECT * FROM projects WHERE isPublic = 1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $projects;
    }
    
}