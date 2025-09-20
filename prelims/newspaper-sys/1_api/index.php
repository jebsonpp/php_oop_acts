<?php require_once 'writer/classloader.php'; ?>
<?php 
// Article API call examples 
// 1.) Create article using the method from Article class
// $articleObj->createArticle("Title from UCOS 42", "Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, praesentium necessitatibus velit tenetur neque quae et delectus similique at a fugiat molestiae mollitia quo labore repellendus consequuntur quia. Cumque, asperiores.", 1);

// $articleObj->createArticle("Title from UCOS 42", "another Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, praesentium necessitatibus velit tenetur neque quae et delectus similique at a fugiat molestiae mollitia quo labore repellendus consequuntur quia. Cumque, asperiores.", 1);

// $articleObj->createArticle("Title from UCOS 42", "and another Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore, praesentium necessitatibus velit tenetur neque quae et delectus similique at a fugiat molestiae mollitia quo labore repellendus consequuntur quia. Cumque, asperiores.", 1);

// 2.) Update article using the method from Article class
// $articleObj->updateArticle(49, "im now edited and still ucos 42 EDITEDITEDITEDIT", "im now edited and still ucos 42 Lorem ipsum dolor sit, amet consectetur adipisicing elit. Suscipit illo voluptas dolore, excepturi maiores velit. Vitae harum quia, eaque laborum, fuga doloribus provident eos quo, neque magni sequi, in reprehenderit.");


// 3.) Delete article using the method from Article class
// $articleObj->deleteArticle(52);
// $articleObj->deleteArticle(53);
// $articleObj->deleteArticle(54);


// echo "<pre>";
// print_r($articleObj->getArticles()); 
// echo "<pre>";


// User API call examples
// 1.) Create user account
// $userObj->registerUser("new_admin_from_ucos_4_2","new_admin_from_ucos_4_2@email.com", "secretucos42", "1");


echo "<pre>";
print_r($userObj->getUsers());
echo "<pre>";


?>







