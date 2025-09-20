<?php require_once 'writer/classloader.php'; ?>

<?php
// Fetch all active articles
$articles = $articleObj->getActiveArticles();

// Fetch all categories to build a map
require_once 'writer/classes/Category.php';
$categoryObj = new Category();
$categories = $categoryObj->getAllCategories();

// Build category map for quick lookup
$categoryMap = [];
foreach ($categories as $cat) {
    $categoryMap[$cat['category_id']] = $cat['name'];
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style>
    body {
      font-family: "Arial";
      background-image: url("https://static4.depositphotos.com/1004590/372/i/450/depositphotos_3729494-stock-photo-new-paper-article.jpg");
      background-size: cover;
      background-attachment: fixed;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark p-4" style="background-color: #355E3B;">
  <a class="navbar-brand" href="#">School Publication Homepage</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
</nav>

<div class="container-fluid">
  <div class="row justify-content-center p-4">
    <div class="col-md-5">
      <div class="card shadow">
        <div class="card-body">
          <h1><a href="writer/index.php">Writer</a></h1>
          <img src="https://images.unsplash.com/photo-1577900258307-26411733b430?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0" class="img-fluid">
          <p>Content writers create clear, engaging, and informative content that helps businesses communicate their services or products effectively, build brand authority, attract and retain customers, and drive web traffic and conversions.</p>
        </div>
      </div>
    </div>
    <div class="col-md-5">
      <div class="card shadow">
        <div class="card-body">
          <h1><a href="admin/index.php">Admin</a></h1>
          <img src="https://plus.unsplash.com/premium_photo-1661582394864-ebf82b779eb0?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0" class="img-fluid">
          <p>Admin writers play a key role in content team development. They are the highest-ranking editorial authority responsible for managing the entire editorial process, and aligning all published material with the publication’s overall vision and strategy. </p>
        </div>
      </div>
    </div>
  </div>

  <div class="display-4 text-center mt-4">All Articles</div>

  <div class="row justify-content-center">
    <div class="col-md-6">
      <?php foreach ($articles as $article): ?>
      <div class="card-body mb-4 shadow p-3">
        <h1><?= htmlspecialchars($article['title']); ?></h1> 

        <?php if ($article['is_admin'] == 1): ?>
          <p><small class="bg-primary text-white p-1">Message From Admin</small></p>
        <?php endif; ?>

        <small><strong><?= htmlspecialchars($article['username']); ?></strong> - <?= htmlspecialchars($article['created_at']); ?></small>

        <?php if (!empty($article['imageName'])): ?>
          <img src="../images/<?= htmlspecialchars($article['imageName']); ?>" alt="Article Image" class="img-fluid my-3">
        <?php endif; ?>

        <p>
          <?= htmlspecialchars($article['content']); ?>
          <?php if (!empty($article['category_id'])): ?>
            <br><strong>Category:</strong> <?= htmlspecialchars($categoryMap[$article['category_id']] ?? 'Uncategorized'); ?>
          <?php endif; ?>
        </p>

        <!-- Request Edit button (only for logged-in writers) -->
        <?php if (isset($_SESSION['user_id'])): ?>
          <form method="POST" action="admin/core/handleForms.php" class="mt-3">
            <input type="hidden" name="article_id" value="<?= $article['id']; ?>">
            <button type="submit" name="requestEditBtn" class="btn btn-warning btn-sm">
              Request Edit
            </button>
          </form>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>   
    </div>
  </div>
</div>
</body>
</html>
