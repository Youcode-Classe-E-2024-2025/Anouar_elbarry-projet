<?php
require_once __DIR__ ."/configDB.php";
class Category{
    private int $id;
    private string $name;
    private string $description;
    private Database $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function creatCategory(string $name ,string $description){
        $conn = $this->db->getConnection();
        $query = "INSERT INTO categories (name, description) 
                 VALUES (:name, :description)";
        try {
            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':name' => $name,
                ':description' => $description
            ]);
            return $conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating category: " . $e->getMessage());
            return false;
        }
    }
    public static function getByID(int $id,$db){
        $conn = $db->getConnection();
        $query = "SELECT * FROM categories WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam("id", $id, PDO::PARAM_INT);
        $stmt->execute(
            [
                "id"=> $id
            ]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } 
    public static function getByname(string $name,$db){
        $conn = $db->getConnection();
        $query = "SELECT * FROM categories WHERE name = :name";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            "name" => $name
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    } 
    public static function getAll($db){
        $conn = $db->getConnection();
        $query = "SELECT * FROM categories";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 
    public static function getAllName($db){
        $conn = $db->getConnection();
        $query = "SELECT name FROM categories";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 
}