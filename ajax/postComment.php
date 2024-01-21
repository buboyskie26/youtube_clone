<?php 


    require_once("../includes/config.php");
    require_once("../includes/classes/User.php");
    require_once("../includes/classes/Comment.php");

    if(isset($_POST['commentText']) && isset($_POST['videoId']) 
        && isset($_POST['postedBy'])){

        $commentText = $_POST['commentText'];
        $videoId = $_POST['videoId'];
        $postedBy = $_POST['postedBy'];

        $responseTo = isset($_POST['responseTo']) ? $_POST['responseTo'] : 0;

        $userLoggedInObj = new User($con, $_SESSION['userLoggedIn']);

        $insertComment = $con->prepare("INSERT INTO comments(postedBy, videoId, responseTo, body)
            VALUES(:postedBy, :videoId, :responseTo, :body)");

        $insertComment->bindParam(":postedBy", $postedBy);
        $insertComment->bindParam(":videoId", $videoId);
        $insertComment->bindParam(":responseTo", $responseTo);
        $insertComment->bindParam(":body", $commentText);

        $insertComment->execute();

        $comment = new Comment($con, $con->lastInsertId(),
            $userLoggedInObj, $videoId);

        $samp = $comment->getNumberOfReplies();
        
        echo $comment->create();
    }else {
        echo "One or more parameters are not passed into subscribe.php the file";
    }

?>