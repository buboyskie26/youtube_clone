<?php 

    require_once("../includes/config.php");


    if(isset($_POST['userTo']) && isset($_POST['userFrom'])){
         
        $userTo = $_POST['userTo'];
        $userFrom = $_POST['userFrom'];

        // check if the user is subbed
        $query = $con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
        $query->bindParam(":userTo", $userTo);
        $query->bindParam(":userFrom", $userFrom);
        $query->execute();

        if($query->rowCount() > 0) {

            // DELETE
            $delete = $con->prepare("DELETE FROM subscribers 
                WHERE userTo=:userTo AND userFrom=:userFrom");
            $delete->bindParam(":userTo", $userTo);
            $delete->bindParam(":userFrom", $userFrom);

            $delete->execute();

        }else if($query->rowCount() == 0){
            // insert

            $insert = $con->prepare("INSERT INTO subscribers(userTo, userFrom) 
                VALUES(:userTo, :userFrom)");
            $insert->bindParam(":userTo", $userTo);
            $insert->bindParam(":userFrom", $userFrom);
            $insert->execute();
            
        }

        $query = $con->prepare("SELECT * FROM subscribers 
            WHERE userTo=:userTo");

        $query->bindParam(":userTo", $userTo);
        $query->execute();

        echo $query->rowCount();
    }else{
        echo "sometihng wrht";
    }
    
?>