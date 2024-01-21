<?php
class Video {

    private $con, $sqlData, $userLoggedInObj;

    public function __construct($con, $input, $userLoggedInObj) {

        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;

        if(!is_array($input)) {
            //
            $query = $this->con->prepare("SELECT * FROM videos WHERE id = :id");
            $query->bindParam(":id", $input);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
        else {
            $this->sqlData = $input;
        }
    }
    
    public function getId() {
        return $this->sqlData["id"];
    }

    public function getUploadedBy() {
        return $this->sqlData["uploadedBy"];
    }

    public function getTitle() {
        return $this->sqlData["title"];
    }

    public function getDescription() {
        return $this->sqlData["description"];
    }

    public function getPrivacy() {
        return $this->sqlData["privacy"];
    }

    public function getFilePath() {
        return $this->sqlData["filePath"];
    }

    public function getCategory() {
        return $this->sqlData["category"];
    }

    public function getUploadDate() {
        $date= $this->sqlData["uploadDate"];

        return date("M j Y", strtotime($date));

    }

    public function getViews() {
        return $this->sqlData["views"];
    }

    public function getDuration() {
        return $this->sqlData["duration"];
    }

    public function getThumbnail() {

        $videoId = $this->getId();
        $queryThumbNail = $this->con->prepare("SELECT filePath FROM thumbnail
            WHERE videoId=:videoId AND selected=1");
        
        $queryThumbNail->bindParam(":videoId", $videoId);
        $queryThumbNail->execute();

        return $queryThumbNail->fetchColumn();
    }
    public function getThumbnailTwo() {

        $videoId = $this->getId();

        $query = $this->con->prepare("SELECT filePath FROM thumbnail
            WHERE selected=1 AND videoId=:videoId");
        $query->bindValue(":videoId", $videoId);

        $query->execute();

        return $query->fetchColumn();
    }

    public function incrementViews() {

        $videoId = $this->getId();

        
        $query = $this->con->prepare("UPDATE videos
            SET views=views+1
            WHERE id=:id");

        $query->bindParam(":id", $videoId);
        $query->execute();

        $this->sqlData['views'] = $this->sqlData['views'] + 1;

    }
    public function getLikes() {

        $videoId = $this->getId();
        $query = $this->con->prepare("SELECT count(*) as 'count' FROM likes 
            WHERE videoId=:videoId");

        $query->bindParam(":videoId", $videoId);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);

        return $data['count'];
    }

    public function getDislikes() {

        $videoId = $this->getId();
        $query = $this->con->prepare("SELECT count(*) as 'count' FROM dislikes 
            WHERE videoId=:videoId");

        $query->bindParam(":videoId", $videoId);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);

        return $data['count'];
    }
    public function like(){

        $videoId = $this->getId();
        $username = $this->userLoggedInObj->getUsername();
 
        // $query = $this->con->prepare("SELECT * FROM likes 
        //     WHERE username=:un AND videoId=:videoId");

        // $query->bindParam(":un", $username);
        // $query->bindParam(":videoId", $videoId);
        // $query->execute();
        // return $query->rowCount() > 0;

        if($this->wasLikeBy()){

            $deleteLike = $this->con->prepare("DELETE FROM likes
                WHERE videoId=:videoId AND username=:un");
            
            $deleteLike->bindParam(":un", $username);
            $deleteLike->bindParam(":videoId", $videoId);
            $deleteLike->execute();

            $result = arraY(
                "likes" => -1,
                "dislikes" => 0,
            );
            return json_encode($result);

        }else{

            $insertLike = $this->con->prepare("INSERT INTO likes(username, videoId)
                VALUES(:un, :videoId)");

            $insertLike->bindParam(":un", $username);
            $insertLike->bindParam(":videoId", $videoId);
            $insertLike->execute();

            $deleteDisLike = $this->con->prepare("DELETE FROM dislikes
                WHERE videoId=:videoId AND username=:un");
                
            $deleteDisLike->bindParam(":un", $username);
            $deleteDisLike->bindParam(":videoId", $videoId);
            $deleteDisLike->execute();

            $count = $deleteDisLike->rowCount();

            $result = array(
                "likes" => 1,
                "dislikes" => 0 - $count,
            );
            return json_encode($result);
        }
    }

    public function dislike(){

        $videoId = $this->getId();

        $query = $this->con->prepare("SELECT * FROM dislikes 
            WHERE username=:un AND videoId=:videoId");

        $username = $this->userLoggedInObj->getUsername();

        $query->bindParam(":un", $username);
        $query->bindParam(":videoId", $videoId);
        $query->execute();

        if($this->wasDislikeBy()){

            $deleteDisLike = $this->con->prepare("DELETE FROM dislikes
                WHERE videoId=:videoId AND username=:un");
                
            $deleteDisLike->bindParam(":un", $username);
            $deleteDisLike->bindParam(":videoId", $videoId);
            $deleteDisLike->execute();

            $result = arraY(
                "likes" => 0,
                "dislikes" => -1,
            );
            return json_encode($result);

        // If dislike
        }else{
            $insertDislike = $this->con->prepare("INSERT INTO dislikes(username, videoId)
                VALUES(:un, :videoId)");
            $insertDislike->bindParam(":un", $username);
            $insertDislike->bindParam(":videoId", $videoId);
            $insertDislike->execute();

            $deleteLike = $this->con->prepare("DELETE FROM likes
                WHERE videoId=:videoId AND username=:un");
                
            $deleteLike->bindParam(":un", $username);
            $deleteLike->bindParam(":videoId", $videoId);
            $deleteLike->execute();

            $count = $deleteLike->rowCOunt();

            $result = arraY(
                "likes" => 0 - $count,
                "dislikes" => 1,
            );
            return json_encode($result);
        }
    }

    public function wasLikeBy(){

        $videoId = $this->getId();

        $query = $this->con->prepare("SELECT * FROM likes 
            WHERE username=:un AND videoId=:videoId");

        $username = $this->userLoggedInObj->getUsername();

        $query->bindParam(":un", $username);
        $query->bindParam(":videoId", $videoId);
        $query->execute();

        return $query->rowCount() > 0;
    }

     public function wasDislikeBy(){

        $videoId = $this->getId();

        $query = $this->con->prepare("SELECT * FROM dislikes 
            WHERE username=:un AND videoId=:videoId");

        $username = $this->userLoggedInObj->getUsername();

        $query->bindParam(":un", $username);
        $query->bindParam(":videoId", $videoId);
        $query->execute();

        return $query->rowCount() > 0;
    }


    public function getNumberOfComments(){
        $query = $this->con->prepare("SELECT * FROM comments
            WHERE videoId=:videoId");

        $videoId = $this->getId();
        $query->bindParam("videoId", $videoId);
        $query->execute();

        return $query->rowCount();
    }

    public function getComments(){

        $videoId = $this->getId();

        $query = $this->con->prepare("SELECT * FROM comments
            WHERE videoId=:videoId AND responseTo=0
            ORDER BY id DESC");

        $query->bindParam(":videoId", $videoId);
        $query->execute();

        $comments = array();
        $id = $this->getId();

        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            //
            $comment = new Comment($this->con, $row,
                $this->userLoggedInObj, $id);

            $comment_str = $comment->create();
             
            array_push($comments, $comment_str);
        }
        return $comments;
    }
}   
?>