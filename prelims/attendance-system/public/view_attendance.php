<?php
session_start();
require_once '../classes/Admin.php';

if ($_SESSION['user']['role'] != 'admin') {
    header("Location: dashboard.php");
    exit;
}

$adminObj = new Admin();
$conn = $adminObj->connect();
$courses = $conn->query("SELECT * FROM courses")->fetchAll(PDO::FETCH_ASSOC);
$records = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];
    $year_level = $_POST['year_level'];
    $records = $adminObj->viewAttendanceByCourse($course_id, $year_level);
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>View Attendance</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h3>View Attendance by Course & Year Level</h3>
  <form method="POST" class="row g-3 mb-3">
    <div class="col-md-4">
      <label>Course</label>
      <select name="course_id" class="form-select" required>
        <?php foreach($courses as $course): ?>
          <option value="<?= $course['id'] ?>"><?= $course['course_name'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label>Year Level</label>
      <select name="year_level" class="form-select" required>
        <option value="1">1st Year</option>
        <option value="2">2nd Year</option>
        <option value="3">3rd Year</option>
        <option value="4">4th Year</option>
      </select>
    </div>
    <div class="col-md-3 d-flex align-items-end">
      <button type="submit" class="btn btn-primary">View</button>
    </div>
  </form>

  <?php if(!empty($records)): ?>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Name</th>
        <th>Course</th>
        <th>Year</th>
        <th>Date</th>
        <th>Time In</th>
        <th>Status</th>
        <th>Late?</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($records as $row): ?>
        <tr>
          <td><?= $row['full_name'] ?></td>
          <td><?= $row['course_name'] ?></td>
          <td><?= $row['year_level'] ?></td>
          <td><?= $row['date'] ?></td>
          <td><?= $row['time_in'] ?></td>
          <td><?= $row['status'] ?></td>
          <td><?= $row['is_late'] ? 'Yes' : 'No' ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>

  <a href="dashboard.php" class="btn btn-secondary">Back</a>
</body>
</html>
