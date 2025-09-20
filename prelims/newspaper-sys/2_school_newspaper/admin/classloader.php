<?php  
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Article.php';
require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/classes/Notification.php';  
require_once __DIR__ . '/classes/category.php';

$databaseObj= new Database();
$userObj = new User();
$articleObj = new Article();
$categoryObj = new Category(); 

$userObj->startSession();
?>