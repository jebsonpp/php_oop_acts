<?php
session_start();
require_once __DIR__ . '/../classes/Student.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: index.php");
    exit;
}

$studentObj = new Student();
$user_id = $_SESSION['user']['id'];

// Fetch the student record
$conn = $studentObj->connect();
$stmt = $conn->prepare("SELECT * FROM students WHERE user_id = ?");
$stmt->execute([$user_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("<div style='color:red; font-family:Arial; text-align:center; margin-top:50px;'>
            ❌ You are logged in as a student, but no student profile was found.<br>
            Please ask the admin to assign you to a course/year level first.
         </div>");
}

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $is_late = isset($_POST['is_late']) ? 1 : 0;
    $result = $studentObj->recordAttendance($student['id'], $is_late);

    if ($result) {
        $success = "Attendance recorded successfully!";
    } else {
        $error = "Something went wrong while recording attendance.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>File Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="text-center mb-4">File Attendance</h3>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php elseif (!empty($success)): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_late" id="is_late">
                            <label class="form-check-label" for="is_late">
                                I am late
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit Attendance</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="dashboard.php" class="btn btn-outline-secondary w-100">Back to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
