<?php 
class Project {
    // Attributes
    private int $id;
    private string $name;
    private string $description;
    private bool $isPublic;
    private string $createdAt;
    private string $creator;

    // Methodes
    
    public function __construct($name, $description, $isPublic,$creator){
        $this->name = $name;
        $this->description = $description;
        $this->isPublic = $isPublic;
        $this->creator = $creator;
    }
    public function getId(){}
    public function creat($name, $description, $isPublic, $creator){
        $this->name = $name;
        $this->description = $description;
        $this->isPublic = $isPublic;
        $this->creator = $creator;
    }
    public function update($name, $description, $isPublic){
        $this->name = $name;
        $this->description = $description;
        $this->isPublic = $isPublic;
    }
    public function delet(){}
    public function addMember(){}
    public function removeMember(){}
}