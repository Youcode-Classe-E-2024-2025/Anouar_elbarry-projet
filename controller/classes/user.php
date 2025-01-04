<?php
require_once "configDB.php";
require_once "project.php";
class User {
    //Attributes

    // roles
    const ROLE_TEAM_MEMBER = 'TEAM_MEMBER';
    const ROLE_PROJECT_MANAGER = 'PROJECT_MANAGER';  
    

    private int $id = 0;
    private string $username;
    private string $email;
    private string $password;
    private string $role;
    private Project $project;
    private array $projects = [];
    private Database $db;
    //Methodes
    public function __construct($username, $email, $password, $role = self::ROLE_TEAM_MEMBER) {
        $this->db = new Database(); 
        $this->username = $username;
        $this->email = $email;
        $this->setPassword($password);
        $this->role = $role;
    }
    
    public function setRole($role){
        // role validation
        if(in_array($role,[self::ROLE_TEAM_MEMBER,self::ROLE_PROJECT_MANAGER])){
            $this->role = $role;

            // Only attempt to update role in database if user has an ID
            if ($this->id > 0) {
                $conn = $this->db->getConnection();
                $sql = "UPDATE users SET role = :role WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['role' => $role, 'id' => $this->id]);
            }
        }
    }

    public function getRole(){
        return $this->role;
    }
    public function regester($username, $email, $password){
        $conn = $this->db->getConnection();
        $query = "INSERT INTO users (username, email, usarPassword, role) VALUES (:username, :email, :password, :role)";
        $stmt = $conn->prepare($query);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt->execute([
                "username"=> $username,
                "email"=> $email,
                "password"=> $hashedPassword,
                "role"=> self::ROLE_TEAM_MEMBER,
            ]);
            
            // Return the last inserted ID
            return $conn->lastInsertId();
        } catch (PDOException $e) {
            // Log the error or handle it appropriately
            error_log("Registration error: " . $e->getMessage());
            return false;
        }
    }
    
    static function login($db ,$email, $password){
        $conn = $db->getConnection();
        $query = "SELECT * FROM users WHERE email=:email";
        $stmt = $conn->prepare($query);
        $stmt->execute(["email"=> $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
         if($user && password_verify($password, $user["usarPassword"])){
           return true;
         }
         else {
            return false;
         }
    }
    
    public function updateProfile($name, $email){
    }
    public function creatproject($name, $description, $isPublic = true){
        // Ensure the user is a project manager or has appropriate permissions
        if ($this->role !== self::ROLE_PROJECT_MANAGER) {
            throw new Exception("Only project managers can create projects");
        }

        // Ensure the user is logged in (has an ID)
        if ($this->id <= 0) {
            throw new Exception("User must be logged in to create a project");
        }

        // Create a new project
        $project = new Project($name, $description, $isPublic, $this->id);
        $projectId = $project->creat($name, $description, $isPublic, $this->id);

        // Optionally, add the project to the user's projects array
        if ($projectId) {
            $this->projects[] = $project;
        }

        return $projectId;
    }
    // handle password
    private function setPassword( $password ){
       
       $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
    public function verifyPassword($inputPassword){
       return password_verify($inputPassword , $this->password);
    }   
    public static function getUsers(){
        $db = new Database();
        $conn = $db->getConnection();
        $query = "SELECT * FROM users";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }   
    public static function getUsername($email){
        $db = new Database();
        $conn = $db->getConnection();
        $query = "SELECT name FROM users WHERE email= :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }   
}