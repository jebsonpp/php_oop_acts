<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../classloader.php';

// Make sure these objects are instantiated.
$articleObj = new Article();
$userObj = new User();

if (isset($_POST['insertNewUserBtn'])) {
	$username = htmlspecialchars(trim($_POST['username']));
	$email = htmlspecialchars(trim($_POST['email']));
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			if (!$userObj->usernameExists($username)) {

				if ($userObj->registerUser($username, $email, $password)) {
					header("Location: ../login.php");
				}

				else {
					$_SESSION['message'] = "An error occured with the query!";
					$_SESSION['status'] = '400';
					header("Location: ../register.php");
				}
			}

			else {
				$_SESSION['message'] = $username . " as username is already taken";
				$_SESSION['status'] = '400';
				header("Location: ../register.php");
			}
		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
		}
	}
	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	if (!empty($email) && !empty($password)) {

		if ($userObj->loginUser($email, $password)) {
			header("Location: ../index.php");
		}
		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../login.php");
	}

}

if (isset($_GET['logoutUserBtn'])) {
	$userObj->logout();
	header("Location: ../index.php");
}

if (isset($_POST['insertAdminArticleBtn'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $author_id = $_SESSION['user_id'];
    $category_id = $_POST['category_id'] ?? null; // NEW: category

    $imageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $fileName = $_FILES['image']['name'];
        $tempFileName = $_FILES['image']['tmp_name'];
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $imageName = sha1(md5(rand(1, 9999999))) . "." . $ext;
        $destination = "../../images/";
        if (!is_dir($destination)) mkdir($destination, 0777, true);
        move_uploaded_file($tempFileName, $destination . $imageName);
    }

    if ($articleObj->createArticle($title, $description, $imageName, $author_id, $category_id)) {
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['message'] = "Failed to create article.";
        $_SESSION['status'] = "400";
        header("Location: ../index.php");
        exit();
    }
}
if (isset($_POST['editArticleBtn'])) {
	$title = $_POST['title'];
	$description = $_POST['description'];
	$article_id = $_POST['article_id'];
	if ($articleObj->updateArticle($article_id, $title, $description)) {
		header("Location: ../articles_submitted.php");
	}
}

if (isset($_POST['deleteArticleBtn'])) {
    $article_id = $_POST['article_id'];
    
    $article = $articleObj->getArticleById($article_id);
    if (!$article) {
        $_SESSION['message'] = "Article not found.";
        $_SESSION['status'] = "404";
        header("Location: ../index.php");
        exit();
    }
    
    if ($articleObj->deleteArticle($article_id)) {
        // Use Notification class to create in-site notification      
        $notificationObj = new Notification();
        $notification_message = "Your article titled \"{$article['title']}\" was deleted by an admin.";
        $notificationObj->createNotification($article['author_id'], $notification_message);

        $_SESSION['message'] = "Article deleted and author notified on the website.";
        $_SESSION['status'] = "200";
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['message'] = "Failed to delete article.";
        $_SESSION['status'] = "400";
        header("Location: ../index.php");
        exit();
    }
}

if (isset($_POST['updateArticleVisibility'])) {
	$article_id = $_POST['article_id'];
	$status = $_POST['status'];
	echo $articleObj->updateArticleVisibility($article_id,$status);
}

if (isset($_POST['requestEditBtn'])) {
    $article_id = $_POST['article_id'];
    $writer_id = $_SESSION['user_id'];

    $article = $articleObj->getArticleById($article_id);
    if ($article) {
        $notificationObj = new Notification();
        $notificationObj->notifyEditRequest($article['author_id'], $article['title'], $writer_id);

        $_SESSION['message'] = "Edit request sent to the author.";
        $_SESSION['status'] = "200";
    } else {
        $_SESSION['message'] = "Article not found.";
        $_SESSION['status'] = "404";
    }
    header("Location: ../index.php");
    exit();
}
?>