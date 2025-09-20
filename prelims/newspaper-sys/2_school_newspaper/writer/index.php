<?php require_once 'classloader.php'; ?>

<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
  exit;
}

if ($userObj->isAdmin()) {
  header("Location: ../admin/index.php");
  exit;
}

// Fetch categories for displaying article info
require_once 'classes/Category.php';
$categoryObj = new Category();
$articles = $articleObj->getActiveArticles(); // Only accepted articles
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style>
    body { font-family: "Arial"; }
  </style>
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="container-fluid mt-4">
  <div class="display-4 text-center">
    Hello <span class="text-success"><?php echo $_SESSION['username']; ?></span>, here are all accepted articles
  </div>

  <div class="row justify-content-center mt-4">
    <div class="col-md-6">
      <?php foreach ($articles as $article) { ?>
        <div class="card mt-4 shadow">
          <div class="card-body">
            <h1><?php echo htmlspecialchars($article['title']); ?></h1>
            <?php if ($article['is_admin'] == 1) { ?>
              <p><small class="bg-primary text-white p-1">Message From Admin</small></p>
            <?php } ?>
            <small><strong><?php echo htmlspecialchars($article['username']); ?></strong> - <?php echo $article['created_at']; ?></small>
            <p>
              <?php echo htmlspecialchars($article['content']); ?>
              <?php if(isset($article['category_id']) && !empty($article['category_id'])): ?>
                <br><strong>Category:</strong> <?= htmlspecialchars($categoryObj->getCategoryById($article['category_id'])['name'] ?? 'Uncategorized') ?>
              <?php endif; ?>
            </p>
            <?php if (!empty($article['imageName'])): ?>
              <img src="../../images/<?php echo htmlspecialchars($article['imageName']); ?>" alt="Article Image" class="img-fluid mb-3">
            <?php endif; ?>
          </div>
        </div>  
      <?php } ?>
    </div>
  </div>
</div>
</body>
</html>
