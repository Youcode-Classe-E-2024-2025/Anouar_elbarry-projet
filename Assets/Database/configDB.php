<?php 
 $db_server = "localhost";
 $db_username = "root";
 $db_password = "Jppp5734";
 $db_name = "ProTask";
 $conn = "";
 
 try {
     $conn = mysqli_connect($db_server,$db_username,$db_password,$db_name);
 }
 catch (mysqli_sql_exception) {
    die ("your are not connected to the db");
 }
 if($conn){
    die("you are connected to the db");
 }