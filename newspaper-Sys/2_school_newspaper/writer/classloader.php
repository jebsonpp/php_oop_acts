<?php  
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Article.php';
require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/../admin/classes/Notification.php';

$databaseObj = new Database();
$userObj = new User();
$articleObj = new Article();

$userObj->startSession();
?>