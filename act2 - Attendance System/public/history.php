<?php
session_start();
require_once '../classes/Student.php';

if ($_SESSION['user']['role'] != 'student') {
    header("Location: dashboard.php");
    exit;
}

$studentObj = new Student();
$conn = $studentObj->connect();
$stmt = $conn->prepare("SELECT * FROM students WHERE user_id=?");
$stmt->execute([$_SESSION['user']['id']]);
$student = $stmt->fetch();

$history = $studentObj->getHistory($student['id']);
?>
<!DOCTYPE html>
<html>
<head>
  <title>My Attendance History</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h3>Attendance History</h3>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Date</th>
        <th>Time In</th>
        <th>Status</th>
        <th>Late?</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($history as $row): ?>
        <tr>
          <td><?= $row['date'] ?></td>
          <td><?= $row['time_in'] ?></td>
          <td><?= $row['status'] ?></td>
          <td><?= $row['is_late'] ? 'Yes' : 'No' ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <a href="dashboard.php" class="btn btn-secondary">Back</a>
</body>
</html>
