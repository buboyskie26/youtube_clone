<?php 

    require_once("../includes/config.php");
    require_once("../includes/classes/User.php");
    require_once("../includes/classes/Comment.php");


    $videoId = $_POST['videoId'];
    $commentId = $_POST['commentId'];
    $username = $_SESSION['userLoggedIn'];

    $userLoggedInObj = new User($con, $username);

    $comment = new Comment($con, $commentId, $userLoggedInObj, $videoId);

    echo $comment->dislike();
?>