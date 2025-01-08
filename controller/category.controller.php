<?php
session_start();
require_once __DIR__ ."/classes/category.php";
$category = new Category();
$project_id = $_SESSION["project_id"];

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['name']) &&isset($_POST['description'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $new_category = $category->creatCategory($name,$description);
    if($new_category) {
        $_SESSION['successT'] = 'Category created successfully';
        header("Location: ../view/index.view.php?project_id=$project_id");
    } else {
        $_SESSION['errorT'] = 'Failed to Creat Category';
        header("Location: ../view/index.view.php?project_id=$project_id");
    }
    exit();
}