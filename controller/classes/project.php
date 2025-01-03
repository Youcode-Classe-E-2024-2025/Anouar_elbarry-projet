<?php 
class Project {
    // Attributes
    private int $id;
    private string $name;
    private string $description;
    private bool $isPublic;
    private string $createdAt;
    private int $creator;

    // Methodes

    public function __construct($name, $description, $isPublic,$creator){
        $this->name = $name;
        $this->description = $description;
        $this->isPublic = $isPublic;
        $this->creator = $creator;
    }
    public function getId(){}
    public function creat($name, $description, $isPublic, $creator){
        $db = new Database();
        $conn = $db->getConnection();

        $query = "INSERT INTO projects (name, description, isPublic, creator_id) VALUES (:name, :description, :isPublic, :creator)";
        $stmt = $conn->prepare($query);
        
        try {
            $stmt->execute([
                'name' => $name,
                'description' => $description,
                'isPublic' => $isPublic ? 1 : 0,
                'creator' => $creator
            ]);
            
            // Return the last inserted ID
            return $conn->lastInsertId();
        } catch (PDOException $e) {
            // Log the error or handle it appropriately
            error_log("Project creation error: " . $e->getMessage());
            return false;
        }
    }
    public function update($name, $description, $isPublic){
      
    }
    public function delet(){}
    public function addMember(){}
    public function removeMember(){}
}