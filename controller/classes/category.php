<?php
class Category{
    private $id;
    private $name;
    private $description;
    private Database $db;

    public function __construct($id, $name, $description){
        $this->db = new Database();
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function creatCategory($name , $description){
    }
    public function updateCategory(){}
    public function deleteCategory(){}
}