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
    public function delet(){}
    public function addMember(){}
    public function removeMember(){}
    public static function getPublicProjects($db){
    $conn = $db->getConnection();
    $query = "SELECT * FROM projects WHERE isPublic = 1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $projects;
    }
}