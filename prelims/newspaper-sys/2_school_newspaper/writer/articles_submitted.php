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

// Fetch categories for dropdown
require_once 'classes/Category.php';
$categoryObj = new Category();
$categories = $categoryObj->getAllCategories();

// Build category map for fast lookup
$categoryMap = [];
foreach ($categories as $cat) {
    $categoryMap[$cat['category_id']] = $cat['name'];
}

// Fetch all articles submitted by the current user
$articles = $articleObj->getArticlesByUserID($_SESSION['user_id']);
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
  <h2 class="text-center">Submit a New Article</h2>

  <!-- Article Submission Form -->
  <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
    <div class="form-group mt-4">
      <input type="text" class="form-control" name="title" placeholder="Input title here" required>
    </div>
    <div class="form-group mt-4">
      <textarea name="description" class="form-control" placeholder="Write your article..." required></textarea>
    </div>
    <div class="form-group mt-4">
      <select name="category_id" class="form-control" required>
        <option value="">Select Category</option>
        <?php foreach($categories as $cat): ?>
          <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group mt-4">
      <input type="file" name="image" class="form-control" required>
    </div>
    <input type="submit" class="btn btn-primary form-control mt-4 mb-4" name="insertArticleBtn" value="Submit Article">
  </form>

  <h2 class="text-center mt-5">Your Submitted Articles</h2>
  <?php foreach ($articles as $article) { ?>
    <div class="card mt-4 shadow articleCard">
      <div class="card-body">
        <h3><?= htmlspecialchars($article['title']); ?></h3>
        <small><?= htmlspecialchars($article['username']); ?> - <?= $article['created_at']; ?></small>
        
        <?php if ($article['is_active'] == 0): ?>
          <p class="text-danger">Status: PENDING</p>
        <?php else: ?>
          <p class="text-success">Status: ACTIVE</p>
        <?php endif; ?>

        <p>
          <?= htmlspecialchars($article['content']); ?>
          <?php if(isset($article['category_id']) && !empty($article['category_id'])): ?>
            <br><strong>Category:</strong> <?= htmlspecialchars($categoryMap[$article['category_id']] ?? 'Uncategorized'); ?>
          <?php endif; ?>
        </p>

        <?php if (!empty($article['imageName'])): ?>
          <img src="../../images/<?= htmlspecialchars($article['imageName']); ?>" alt="Article Image" class="img-fluid mb-3">
        <?php endif; ?>

        <!-- Delete Button -->
        <form class="deleteArticleForm">
          <input type="hidden" name="article_id" value="<?= $article['article_id']; ?>" class="article_id">
          <input type="submit" class="btn btn-danger float-right mb-4 deleteArticleBtn" value="Delete">
        </form>

        <!-- Edit Form (Hidden by default) -->
        <div class="updateArticleForm d-none mt-3">
          <h4>Edit Article</h4>
          <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($article['title']); ?>" required>
            </div>
            <div class="form-group">
              <textarea name="description" class="form-control" required><?= htmlspecialchars($article['content']); ?></textarea>
            </div>
            <div class="form-group">
              <select name="category_id" class="form-control" required>
                <option value="">Select Category</option>
                <?php foreach($categories as $cat): ?>
                  <option value="<?= $cat['category_id'] ?>" <?= ($article['category_id'] == $cat['category_id']) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($cat['name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <input type="file" name="image" class="form-control">
              <small class="text-muted">Leave empty to keep existing image</small>
            </div>
            <input type="hidden" name="article_id" value="<?= $article['article_id']; ?>">
            <input type="submit" class="btn btn-primary float-right" name="editArticleBtn" value="Update Article">
          </form>
        </div>
      </div>
    </div>
  <?php } ?>
</div>

<script>
  // Toggle edit form
  $('.articleCard').on('dblclick', function () {
    $(this).find('.updateArticleForm').toggleClass('d-none');
  });

  // Delete article
  $('.deleteArticleForm').on('submit', function (event) {
    event.preventDefault();
    var formData = {
      article_id: $(this).find('.article_id').val(),
      deleteArticleBtn: 1
    };
    if (confirm("Are you sure you want to delete this article?")) {
      $.ajax({
        type: "POST",
        url: "core/handleForms.php",
        data: formData,
        success: function (data) {
          if (data) location.reload();
          else alert("Deletion failed");
        }
      });
    }
  });
</script>
</body>
</html>
