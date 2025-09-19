<?php

require_once 'classloader.php';

if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$notificationObj = new Notification();
$notifications = $notificationObj->getNotificationsByUserId($_SESSION['user_id']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Notifications</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
  <?php include 'includes/navbar.php'; ?>
  <div class="container mt-4">
    <h1>Your Notifications</h1>
    <?php if(!empty($notifications)): ?>
      <ul class="list-group">
        <?php foreach ($notifications as $notification): ?>
          <li class="list-group-item <?php echo $notification['is_read'] ? '' : 'font-weight-bold'; ?>">
            <?php echo htmlspecialchars($notification['message']); ?>
            <br>
            <small class="text-muted"><?php echo htmlspecialchars($notification['created_at']); ?></small>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>No notifications at this time.</p>
    <?php endif; ?>
  </div>
</body>
</html>