<?php
$dbHost="localhost";
$dbUser="root";
$dbPass="";
$dbName="auth";

try{
    $conn= new PDO("mysql:host=localhost;dbname=auth;";);
   // echo "success";
}catch(Exception $e){
    echo $e->getMessage();
    exit();
}