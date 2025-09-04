<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$user = $_SESSION['user'];
$message = "";

if ($user['role'] === 'admin' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_user'])) {
    require_once __DIR__ . '/../classes/Admin.php';
    $adminObj = new Admin();
    $user_id = $_POST['user_id'] ?? null;
    $course_id = $_POST['course_id'] ?? null;
    $year_level = $_POST['year_level'] ?? null;
    if ($user_id && $course_id && $year_level) {
        if ($adminObj->assignUserToStudent($user_id, $course_id, $year_level)) {
            $message = "User assigned as student successfully!";
        } else {
            $message = "Failed to assign user as student.";
        }
    } else {
        $message = "Please fill all fields.";
    }
}

if ($user['role'] === 'admin') {
    require_once __DIR__ . '/../classes/Admin.php';
    $adminObj = new Admin();

    // Get list of users with role 'student'
    $conn = $adminObj->connect();
    $stmt = $conn->prepare("SELECT * FROM users WHERE role = 'student'");
    $stmt->execute();
    $usersList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get list of courses
    $stmt2 = $conn->prepare("SELECT * FROM courses");
    $stmt2->execute();
    $coursesList = $stmt2->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Welcome, <?= htmlspecialchars($user['full_name']) ?> (<?= ucfirst($user['role']) ?>)</h2>
  <hr>
  <?php if ($user['role'] === 'admin'): ?>
    <a href="add_course.php" class="btn btn-primary">Add Course</a>
    <a href="view_attendance.php" class="btn btn-success">View Attendance</a>
    <button class="btn btn-warning" id="toggleAssignForm">Assign User as Student</button>
    
    <?php if ($message): ?>
      <div class="alert alert-info mt-3"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <div id="assignForm" style="display:none; margin-top:20px;">
      <form method="post" action="dashboard.php">
        <div class="mb-3">
          <label for="user_id" class="form-label">Select User</label>
          <select name="user_id" id="user_id" class="form-select" required>
            <option value="">-- Select User --</option>
            <?php foreach ($usersList as $usr): ?>
              <option value="<?= htmlspecialchars($usr['id']) ?>"><?= htmlspecialchars($usr['full_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="course_id" class="form-label">Select Course</label>
          <select name="course_id" id="course_id" class="form-select" required>
            <option value="">-- Select Course --</option>
            <?php foreach ($coursesList as $course): ?>
              <option value="<?= htmlspecialchars($course['id']) ?>"><?= htmlspecialchars($course['course_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="year_level" class="form-label">Year Level</label>
          <input type="number" name="year_level" id="year_level" class="form-control" required>
        </div>
        <button type="submit" name="assign_user" class="btn btn-warning">Assign as Student</button>
      </form>
    </div>
    
    <script>
      document.getElementById('toggleAssignForm').addEventListener('click', function(){
          var formDiv = document.getElementById('assignForm');
          formDiv.style.display = formDiv.style.display === 'none' ? 'block' : 'none';
      });
    </script>
  <?php else: ?>
    <a href="attendance.php" class="btn btn-primary">File Attendance</a>
    <a href="history.php" class="btn btn-success">View My Attendance</a>
  <?php endif; ?>
  <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
</div>
</body>
</html>
