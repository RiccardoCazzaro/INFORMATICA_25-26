<?php
session_start(); 
require 'config.php';

$dns = "mysql:host=$host;dbname=$db;charset=UTF8";

try {
    $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
    
    $conn = new PDO($dns, $user, $password, $options);
    
    if($conn) {
        echo "Connect to the $db database successfully!";
        $_SESSION['db_connected'] = true; 
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>