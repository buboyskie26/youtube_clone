<?php 


    require_once("../includes/config.php");
    require_once("../includes/classes/Video.php");
    require_once("../includes/classes/User.php");


    if(isset($_POST['videoId'])){

        $videoId = $_POST['videoId'];
        $userLoggedIn = $_SESSION['userLoggedIn'];

        $userObj = new User($con, $userLoggedIn);
        $video = new Video($con, $videoId, $userObj);

        $uploadedBy = $video->getUploadedBy();
        
        $query = $con->prepare("SELECT * FROM likes
            WHERE videoId=:videoId AND username=:username");

        $query->bindValue(":videoId", $videoId);
        $query->bindValue(":username", $userLoggedIn);
        $query->execute();
        
        if($query->rowCount() > 0){
            
            // Delete the existing dislikes if ever
            $deleteExistingLikes = $con->prepare("DELETE FROM likes
                WHERE username=:userLoggedIn AND videoId=:videoId");

            $deleteExistingLikes->bindValue(":userLoggedIn", $userLoggedIn);
            $deleteExistingLikes->bindValue(":videoId", $videoId);
            $deleteExistingLikes->execute();

            $countToDelete = $deleteExistingLikes->rowCount();

            $results = array(
                "likes" => 0 - $countToDelete,
                "dislikes" => 0
            );
            echo json_encode($results);
        }else{
            // Insert
            $insertLike = $con->prepare("INSERT INTO likes(username, videoId)
                VALUES(:userLoggedIn, :videoId)");

            $insertLike->bindValue(":userLoggedIn", $userLoggedIn);
            $insertLike->bindValue(":videoId", $videoId);
            $insertLike->execute();

            // Delete the existing dislikes if ever
            $deleteExistingDislike = $con->prepare("DELETE FROM dislikes
                WHERE username=:userLoggedIn AND videoId=:videoId");

            $deleteExistingDislike->bindValue(":userLoggedIn", $userLoggedIn);
            $deleteExistingDislike->bindValue(":videoId", $videoId);
            $deleteExistingDislike->execute();

            $countToDelete = $deleteExistingDislike->rowCount();

            $results = array(
                "likes" => 1,
                "dislikes" => 0 - $countToDelete
            );

            echo json_encode($results);
        }

    }

?>