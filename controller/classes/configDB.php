<?php 
class Database {
   private string $host = "localhost";
   private string $username = "root";
   private string $password = "Jppp5734";
   private string $db_name = "ProTask";
   private string $conn;

   public function __construct() {
      try {
         $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
      }
      catch (mysqli_sql_exception $e) {
         echo "Database Connection Error: ". $e->getMessage();
      }
    // Check connection
    if (!$this->conn) {
      die("Connection failed ");
  }
   
}
}     