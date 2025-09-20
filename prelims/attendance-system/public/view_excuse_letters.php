<?php
session_start();
require_once __DIR__ . '/../classes/ExcuseLetter.php';

// Ensure admin is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$excuseLetter = new ExcuseLetter();
$message = "";

// Handle status update
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $status = ($_GET['action'] == 'approve') ? 'Approved' : 'Rejected';
    if ($excuseLetter->updateStatus($id, $status)) {
        $message = "Excuse letter status updated to $status.";
    } else {
        $message = "Failed to update excuse letter status.";
    }
}

// Handle filtering
$programFilter = isset($_GET['program']) ? $_GET['program'] : null;
$excuses = $excuseLetter->getAllExcuses($programFilter);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Review Excuse Letters</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📑 Review Excuse Letters</h2>
        <a href="dashboard.php" class="btn btn-outline-primary">⬅ Back to Dashboard</a>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Filter Form -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="program" class="form-control" placeholder="Filter by Program"
                   value="<?= htmlspecialchars($programFilter ?? '') ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
        <div class="col-md-2">
            <a href="view_excuse_letters.php" class="btn btn-secondary w-100">Clear</a>
        </div>
    </form>

    <!-- Excuse Letters Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Program</th>
                    <th>Letter</th>
                    <th>Attachment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($excuses): ?>
                    <?php foreach ($excuses as $excuse): ?>
                        <tr>
                            <td><?= $excuse['id']; ?></td>
                            <td><?= htmlspecialchars($excuse['student_name']); ?></td>
                            <td><?= htmlspecialchars($excuse['student_program']); ?></td>
                            <td class="text-start"><?= nl2br(htmlspecialchars($excuse['letter_text'])); ?></td>
                            <td>
                                <?php if ($excuse['attachment']): ?>
                                    <a href="<?= $excuse['attachment']; ?>" target="_blank" class="btn btn-sm btn-outline-info">View</a>
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
                            <td><?= $excuse['created_at']; ?></td>
                            <td>
                                <?php if ($excuse['status'] == 'Pending'): ?>
                                    <a href="view_excuse_letters.php?action=approve&id=<?= $excuse['id']; ?>" 
                                       class="btn btn-sm btn-success">Approve</a>
                                    <a href="view_excuse_letters.php?action=reject&id=<?= $excuse['id']; ?>" 
                                       class="btn btn-sm btn-danger">Reject</a>
                                <?php else: ?>
                                    <span class="text-muted">No action</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-muted">No excuse letters found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
