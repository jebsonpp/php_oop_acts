<?php  
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../classloader.php';

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

if (isset($_POST['insertArticleBtn'])) {
    $title = $_POST['title'];
    $content = $_POST['description'];  
    $author_id = $_SESSION['user_id'];
    
    // Check if an image has been uploaded
    if (isset($_FILES['images']) && $_FILES['images']['error'] === 0) {
        // Get file details
        $fileName = $_FILES['images']['name'];
        $tempFileName = $_FILES['images']['tmp_name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        
        // Generate unique image name
        $uniqueID = sha1(md5(rand(1, 9999999)));
        $imageName = $uniqueID . "." . $fileExtension;
        
        // Specify destination folder relative to this file
        $destinationFolder = "../../../images/";

        // Optionally, create the folder if it does not exist
        if (!is_dir($destinationFolder)) {
            mkdir($destinationFolder, 0777, true);
        }

        $folder = $destinationFolder . $imageName;  
        
        // Move the uploaded file to the destination folder
        if (move_uploaded_file($tempFileName, $folder)) {
            // Pass image name along with article data
            if ($articleObj->createArticle($title, $content, $imageName, $author_id)) {
                header("Location: ../index.php");
                exit();
            } else {
                exit();
            }
        } else {
            echo "Error uploading image. Check folder permissions.<br>";
            exit();
        }
    } else {
        echo "Please attach an image.<br>";
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
	echo $articleObj->deleteArticle($article_id);

	if (isset($_POST['respondEditRequestBtn'])) {
    $article_id = $_POST['article_id'];
    $writer_id = $_POST['writer_id'];
    $status = $_POST['status']; // "accepted" or "rejected"

    $article = $articleObj->getArticleById($article_id);
    if ($article) {
        $notificationObj = new Notification();
        $notificationObj->notifyEditResponse($writer_id, $article['title'], $status);

        $_SESSION['message'] = "You {$status} the edit request.";
        $_SESSION['status'] = "200";
    } else {
        $_SESSION['message'] = "Article not found.";
        $_SESSION['status'] = "404";
    }
    header("Location: ../index.php");
    exit();
}
}