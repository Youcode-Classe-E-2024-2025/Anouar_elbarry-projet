<?php
class Task {
    //Attributes
    private int $id;
    private string $title;
    private string $description;
    private string $status;
    private string $priority;
    private string $dueDate;
    private string $createdAt;
    //Methodes
    public function __construct(int $id, string $title, string $description, string $status, string $priority, string $dueDate, string $createdAt) {}

    public function creatTask($title, $description, $priority, $dueDate){}
    public function updateTask($title, $description, $priority, $dueDate){}
    public function updateStatus($status){}
    public function deletTask(){}
    public function addTag(){}
    public function removeTag(){}
}