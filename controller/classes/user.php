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
    public function __construct($username, $email, $password = null , $role = self::ROLE_TEAM_MEMBER) {
        $this->db = new Database(); 
        $this->username = $username;
        $this->email = $email;
        if ($password !== null) {
            $this->setPassword($password);
        }
        $this->role = $role;
    }
    

    public function regester($username, $email, $password){
        $conn = $this->db->getConnection();
        $query = "INSERT INTO users (username, email, userPassword) VALUES (:username, :email, :password)";
        $stmt = $conn->prepare($query);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt->execute([
                "username"=> $username,
                "email"=> $email,
                "password"=> $hashedPassword
            ]);
            
            // Return the last inserted ID
            $this->id = (int)$conn->lastInsertId(); // Cast to int and set the ID
            return true;
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

        if($user && password_verify($password, $user["userPassword"])){
        // Create and return a new User instance with the ID set
        $newUser = new User($user['username'], $user['email']);
        $newUser->setId((int)$user['id']);
        return $newUser;
    }
    return false;
    }
    
    public function updateProfile($name, $email){
    }
    public function creatproject($name, $description, $isPublic , $creatorId, $dueDate){
        

        // Set user as project manager when creating their first project
        if ($this->id <= 0) {
            $this->id = $creatorId; // Ensure the ID is set before changing role
        }

        $this->setRole(self::ROLE_PROJECT_MANAGER);
        $conn = $this->db->getConnection();
        $query = "INSERT INTO projects (name, description, isPublic, creator_id,dueDate) VALUES (:name, :description, :isPublic, :creator_id,:dueDate)";
        $stmt = $conn->prepare($query);
        
        try {
            $stmt->execute([
                'name' => $name,
                'description' => $description,
                'isPublic' => $isPublic,
                'creator_id' => $creatorId,
                'dueDate' => $dueDate
            ]);
            return $conn->lastInsertId();
        } catch (PDOException $e) {
            // Handle error appropriately
            return false;
        }
    }
    // handle password
   
    public function verifyPassword($inputPassword){
       return password_verify($inputPassword , $this->password);
    }   
    // setters
    private function setPassword( $password ){
       
        $this->password = password_hash($password, PASSWORD_DEFAULT);
     }
  
    public function setId($id){
         $this->id=$id;
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
                else{echo $this->id;}
            }
    }
        // getters
        
    public function getId(){
        return $this->id;
        }
   

    public function getUserProjects() {
        try {
            $conn = $this->db->getConnection();
            
            switch ($_SESSION['userRole']) {
                case 'PROJECT_MANAGER':
                    $query = "SELECT * FROM projects WHERE creator_id = :creator_id";
                    $stmt = $conn->prepare($query);
                    $stmt->execute(['creator_id' => $this->id]);
                    break;
                    
                default: // For team members
                    $query = "SELECT p.* 
                             FROM projects p 
                             JOIN team_members tm ON p.id = tm.project_id 
                             WHERE tm.user_id = :user_id 
                             ORDER BY p.created_at DESC";
                    $stmt = $conn->prepare($query);
                    $stmt->execute(['user_id' => $this->id]);
                    break;
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting user projects: " . $e->getMessage());
            return [];
        }
    }
    public function getProjectMembers($projectId) {
        try {
            $conn = $this->db->getConnection();
            $query = "SELECT u.id, u.username, u.email, tm.role, tm.joinedAt 
                     FROM users u 
                     JOIN team_members tm ON u.id = tm.user_id 
                     WHERE tm.project_id = :project_id";
            $stmt = $conn->prepare($query);
            $stmt->execute(['project_id' => $projectId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getProjectById($projectId) {
        try {
            $conn = $this->db->getConnection();
            $query = "SELECT * FROM projects WHERE id = :project_id";
            $stmt = $conn->prepare($query);
            $stmt->execute(['project_id' => $projectId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function removeProjectMember($projectId, $userId) {
        try {
            $conn = $this->db->getConnection();
            $query = "DELETE FROM team_members WHERE project_id = :project_id AND user_id = :user_id";
            $stmt = $conn->prepare($query);
            return $stmt->execute([
                'project_id' => $projectId,
                'user_id' => $userId
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function assignProjectToUser($projectId, $userId, $role = self::ROLE_TEAM_MEMBER) {
        try {
            $conn = $this->db->getConnection();
            $query = "INSERT INTO team_members (user_id, project_id, role) 
                     VALUES (:user_id, :project_id, :role)";
            $stmt = $conn->prepare($query);
            
            // Log the values being inserted
            error_log("Attempting to assign project with values:");
            error_log("User ID: " . $userId);
            error_log("Project ID: " . $projectId);
            error_log("Role: " . $role);
            
            $result = $stmt->execute([
                'user_id' => $userId,
                'project_id' => $projectId,
                'role' => $role
            ]);
            
            if (!$result) {
                error_log("SQL Error: " . implode(", ", $stmt->errorInfo()));
            }
            
            return $result;
        } catch (PDOException $e) {
            // Log the error
            error_log("Error assigning project member: " . $e->getMessage());
            return false;
        }
    }

    public static function getUsers(){
                $db = new Database();
                $conn = $db->getConnection();
                $query = "SELECT * FROM users";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    }   
    public static function getUserData($email){
                $db = new Database();
                $conn = $db->getConnection();
                $query = "SELECT * FROM users WHERE email= :email";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result;
    }      
    
    public function getRole(){
        return $this->role;
    }
}