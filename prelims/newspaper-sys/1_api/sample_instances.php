<?php require_once 'writer/classloader.php'; ?>
<?php 

echo $userObj->usernameExists("johndoemewwww") ? "Exists\n" : "Available\n";

if ($userObj->registerUser("johndoemewwww", "john@example.com", "pass123")) {
    echo "User registered\n";
} else {
    echo "Registration failed\n";
}

if ($userObj->loginUser("john@example.com", "pass123")) {
    echo "User logged in\n";
}

echo $userObj->isLoggedIn() ? "User is logged in\n" : "Not logged in\n";
echo $userObj->isAdmin() ? "User is admin\n" : "User is not admin\n";

$userObj->logout();
echo "User logged out\n";

print_r($userObj->getUsers());          // Get all users
print_r($userObj->getUsers(1));         // Get user by ID

echo "Updated rows: " . $userObj->updateUser(1, "john_updated_nnew", "john@newmail.com", true) . "\n";

// Article class functions
$newArticleId = $articleObj->createArticle("Sample Title", "Article content here", 1);
echo "Created Article ID: $newArticleId\n";

print_r($articleObj->getArticles());         // Get all articles
print_r($articleObj->getArticles($newArticleId));  // Get article by ID

echo "Updated articles: " . $articleObj->updateArticle($newArticleId, "New Title", "Updated content") . "\n";

echo "Visibility update: " . $articleObj->updateArticleVisibility($newArticleId, true) . "\n";
?>