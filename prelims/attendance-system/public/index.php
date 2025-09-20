<?php
session_start();
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Student.php'; // ✅ add this

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $userObj = new User();
    $user = $userObj->login($username, $password);
if ($user) {
    // If the user is a student, fetch their student profile
    if ($user['role'] === 'student') {
        require_once __DIR__ . '/../classes/Student.php';
        $studentObj = new Student();
        $studentData = $studentObj->getStudentByUserId($user['id']); // find student row

        if ($studentData) {
            $user['student_id'] = $studentData['id']; // ✅ attach student_id
        }
    }

    $_SESSION['user'] = $user;
    header("Location: dashboard.php");
    exit;
}
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="text-center mb-4">Login</h3>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="register.php" class="btn btn-outline-secondary w-100">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
                    }