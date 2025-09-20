<?php
require_once '../writer/classloader.php';

// Only allow admins
if (!$userObj->isLoggedIn() || !$userObj->isAdmin()) {
    header("Location: ../login.php");
    exit;
}

// Include Category class
require_once '../writer/classes/Category.php';


$categoryObj = new Category(); // This creates the object
// Handle form submission
if (isset($_POST['addCategoryBtn'])) {
    $name = trim($_POST['name']);
    if (!empty($name)) {
        $categoryObj->createCategory($name);
        header("Location: categories.php");
        exit;
    }
}


$categoryObj = new Category();
// Fetch all categories to display
$categories = $categoryObj->getAllCategories();
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<title>Manage Categories</title>
</head>
<body>

<!-- Include Navbar -->
<?php include '../admin/includes/navbar.php'; ?>

<div class="container mt-5">
    <h2>Manage Categories</h2>

    <form method="POST" class="mb-4">
        <div class="form-group">
            <input type="text" name="name" class="form-control" placeholder="Category name" required>
        </div>
        <input type="submit" name="addCategoryBtn" class="btn btn-success" value="Add Category">
    </form>

    <h4>Existing Categories</h4>
    <ul class="list-group">
        <?php foreach ($categories as $cat): ?>
            <li class="list-group-item"><?= htmlspecialchars($cat['name']); ?></li>
        <?php endforeach; ?>
    </ul>
</div>

</body>
</html>
