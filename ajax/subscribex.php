<?php 

    require_once("../includes/config.php");

    if(isset($_POST['userTo']) && isset($_POST['userFrom'])){

        $userWhoUploadedVideo = $_POST['userTo'];
        $userLoggedIn = $_POST['userFrom'];
        
        // Check the subscribes count.
        $query = $con->prepare("SELECT * FROM subscribers 
            WHERE userTo=:userTo AND userFrom=:userFrom");

        $query->bindParam(":userTo", $userWhoUploadedVideo);
        $query->bindParam(":userFrom", $userLoggedIn);
        $query->execute();

        // Check if subscribes count > 0
        if($query->rowCount() > 0){
            // DELETE
            $delete = $con->prepare("DELETE FROM subscribers
                WHERE userTo=:userTo AND userFrom=:userFrom");
            $delete->bindParam(":userTo", $userWhoUploadedVideo);
            $delete->bindParam(":userFrom", $userLoggedIn);
            $delete->execute();


        }else{
            // INSERT
            $insert = $con->prepare("INSERT INTO subscribers(userTo, userFrom)
                VALUES(:userTo, :userFrom)");

            $insert->bindParam(":userTo", $userWhoUploadedVideo);
            $insert->bindParam(":userFrom", $userLoggedIn);
            $insert->execute();
        }


        $query = $con->prepare("SELECT * FROM subscribers 
            WHERE userTo=:userTo");

        $query->bindParam(":userTo", $userWhoUploadedVideo);
        $query->execute();

        echo $query->rowCOunt();
    }



?>