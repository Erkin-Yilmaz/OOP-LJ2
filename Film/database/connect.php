<?php


$host = 'localhost';
$db   = 'film_project'; 
$user = 'root';
$pass = ''; 

require __DIR__ . '/../Classes/classes.php';

$database = new Database($host, $db, $user, $pass);
$pdo = $database->getPDO();
$adminManager = new AdminManager($pdo);
$contentManager = new ContentManager($pdo);
?>