<?php
session_start();
require_once __DIR__ . '/../classes/ExcuseLetter.php';
require_once __DIR__ . '/../classes/Student.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: /attendance-system/public/index.php");
    exit;
}

// Store user info in a variable
$user = $_SESSION['user'];

// ✅ Fix: fetch student_id from $user, not directly from $_SESSION
if (!isset($user['student_id'])) {
    die("Error: Student profile not found. Please contact the administrator.");
}

$student_id = $user['student_id'];

$student = new Student();
$studentData = $student->getStudentById($student_id);

$excuseLetter = new ExcuseLetter();
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // use student’s course/program if available
    $program = $studentData['course_id'] ?? null;
    $letter_text = $_POST['letter_text'];
    $attachment = null;

    if (!empty($_FILES['attachment']['name'])) {
        $targetDir = __DIR__ . "/uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $attachmentPath = $targetDir . basename($_FILES['attachment']['name']);
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $attachmentPath)) {
            $attachment = "uploads/" . basename($_FILES['attachment']['name']); // relative path for DB
        }
    }

    if ($excuseLetter->submitExcuse($student_id, $program, $letter_text, $attachment)) {
        $message = "Excuse letter submitted successfully.";
    } else {
        $message = "Failed to submit excuse letter.";
    }
}

$myExcuses = $excuseLetter->getStudentExcuses($student_id);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submit Excuse Letter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Submit Excuse Letter</h2>
        <a href="dashboard.php" class="btn btn-outline-primary">⬅ Back to Dashboard</a>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Excuse Letter Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Excuse Letter</label>
                    <textarea name="letter_text" class="form-control" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Attachment (optional)</label>
                    <input type="file" name="attachment" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>

    <!-- My Excuse Letters -->
    <h3> My Excuse Letters</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Letter</th>
                    <th>Attachment</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($myExcuses): ?>
                    <?php foreach ($myExcuses as $excuse): ?>
                        <tr>
                            <td><?= $excuse['id']; ?></td>
                            <td class="text-start"><?= nl2br(htmlspecialchars($excuse['letter_text'])); ?></td>
                            <td>
                                <?php if (!empty($excuse['attachment'])): ?>
                                    <a href="<?= htmlspecialchars($excuse['attachment']); ?>" target="_blank" class="btn btn-sm btn-outline-info">View</a>
                                <?php else: ?>
                                    <span class="text-muted">None</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($excuse['status'] === 'Pending'): ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php elseif ($excuse['status'] === 'Approved'): ?>
                                    <span class="badge bg-success">Approved</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Rejected</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($excuse['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-muted">No excuse letters submitted yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
