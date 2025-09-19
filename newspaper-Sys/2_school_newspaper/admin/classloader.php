<?php  
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Article.php';
require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/classes/Notification.php';  // ✅ fix path here

$databaseObj= new Database();
$userObj = new User();
$articleObj = new Article();

$userObj->startSession();
?>