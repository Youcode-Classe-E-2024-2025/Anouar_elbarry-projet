<?php 
class Database {
   private string $host = "localhost";
   private string $username = "root";
   private string $password = "Jppp5734";
   private string $db_name = "ProTask";
   private PDO $conn;

   public function __construct() {
      try {
         $this->conn = new PDO(
           "mysql:host=".$this->host.
           ";dbname=".$this->db_name,
           $this->username,
           $this->password
         );
      
         $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
      catch (PDOException $e) {
         echo "Database Connection Error: ". $e->getMessage();
         die();
      }
   }
   public function getConnection() {
      return $this->conn;
   }
}