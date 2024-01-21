<?php

    class LikedVideosProvider{
        private $con, $userLoggedInObj;

        public function __construct($con, $userLoggedInObj) {
            $this->con = $con;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        public function getVideos(){

            $array = array();

            $username = $this->userLoggedInObj->getUsername();
            // If you dont have commentId on the likes
            // and you have a username in the db, then you have a videoId.
            $query = $this->con->prepare("SELECT * FROM likes
                WHERE commentId=0 AND username=:username");

            $query->bindValue(":username", $username);
            $query->execute();

            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $video = new Video($this->con, $row['videoId'], $this->userLoggedInObj);
                array_push($array, $video);
            }

            return $array;
        }

    }
?>