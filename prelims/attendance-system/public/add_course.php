<?php
session_start();
require_once '../classes/Admin.php';

if ($_SESSION['user']['role'] != 'admin') {
    header("Location: dashboard.php");
    exit;
}

$adminObj = new Admin();
$conn = $adminObj->connect();
$success = "";
$error = "";

/* --- Handle Delete Action --- */
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $course_id = (int)$_GET['id'];
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    if ($stmt->execute([$course_id])) {
        $success = "Course deleted successfully!";
    } else {
        $error = "Failed to delete course.";
    }
    // Redirect to avoid resubmission on refresh
    header("Location: add_course.php");
    exit;
}

/* --- Handle Edit Action --- */
$editMode = false;
$editCourse = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $editMode = true;
    $course_id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([$course_id]);
    $editCourse = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Process edit form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_course'])) {
    $course_id = (int)$_POST['course_id'];
    $course_name = trim($_POST['course_name']);
    if (!empty($course_name)) {
        $stmt = $conn->prepare("UPDATE courses SET course_name = ? WHERE id = ?");
        if ($stmt->execute([$course_name, $course_id])) {
            $success = "Course updated successfully!";
            // Exit edit mode
            $editMode = false;
        } else {
            $error = "Failed to update course.";
        }
    }
}

// Process add course form submission (only when not editing)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_course'])) {
    $course_name = trim($_POST['course_name']);
    if (!empty($course_name)) {
        if ($adminObj->addCourse($course_name)) {
            $success = "Course added successfully!";
        } else {
            $error = "Failed to add course.";
        }
    }
}

// Fetch all courses
$stmt = $conn->prepare("SELECT * FROM courses");
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Add Course</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h3><?php echo $editMode ? 'Edit Course' : 'Add Course'; ?></h3>
  <?php if(!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>
  <?php if(!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if ($editMode && $editCourse): ?>
    <form method="POST">
      <input type="hidden" name="course_id" value="<?= htmlspecialchars($editCourse['id']) ?>">
      <div class="mb-3">
        <label>Course Name</label>
        <input type="text" name="course_name" class="form-control" value="<?= htmlspecialchars($editCourse['course_name']) ?>" required>
      </div>
      <button type="submit" name="edit_course" class="btn btn-primary">Update</button>
      <a href="add_course.php" class="btn btn-secondary">Cancel</a>
    </form>
  <?php else: ?>
    <form method="POST">
      <div class="mb-3">
        <label>Course Name</label>
        <input type="text" name="course_name" class="form-control" required>
      </div>
      <button type="submit" name="add_course" class="btn btn-primary">Add</button>
      <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </form>
  <?php endif; ?>

  <hr>
  <h3>Course List</h3>
  <table class="table table-bordered">
      <thead>
          <tr>
              <th>ID</th>
              <th>Course Name</th>
              <th>Actions</th>
          </tr>
      </thead>
      <tbody>
          <?php if(!empty($courses)): ?>
              <?php foreach($courses as $course): ?>
              <tr>
                  <td><?= htmlspecialchars($course['id']) ?></td>
                  <td><?= htmlspecialchars($course['course_name']) ?></td>
                  <td>
                      <a href="add_course.php?action=edit&id=<?= htmlspecialchars($course['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                      <a href="add_course.php?action=delete&id=<?= htmlspecialchars($course['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                  </td>
              </tr>
              <?php endforeach; ?>
          <?php else: ?>
              <tr>
                  <td colspan="3">No courses found.</td>
              </tr>
          <?php endif; ?>
      </tbody>
  </table>
</body>
</html>
