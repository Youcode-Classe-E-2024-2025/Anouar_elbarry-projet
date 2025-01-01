<?php
class User {
    //Attributes

    // roles
    const ROLE_GEST = 'GEST';
    const ROLE_TEAM_MEMBER = 'TEAM_MEMBER';
    const ROLE_PROJECT_MANAGER = 'PROJECT_MANAGER';  
    
    private $id;
    private $username;
    private $email;
    private $password;
    private $role;
    private $projects = [];
    //Methodes
    public function __construct($id, $username, $email, $password, $role = self::ROLE_GEST) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->setPassword($password);
        $this->setRole($role);
    }
    
    public function setRole($role){}
    public function getRole($role){}
    public function regester($name, $email, $password){
    }
    
    public function login($email, $password){
    }
    
    public function updateProfile($name, $email){
    }
    public function creatproject($name, $email){
    }
    // handle password
    private function setPassword( $password ){
       
       $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
    public function verifyPassword($inputPassword){
       return password_verify($inputPassword , $this->password);
}  
}