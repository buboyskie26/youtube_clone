<?php
    class SubscriptionsProvider {

        private $con, $userLoggedInObj;

        public function __construct($con, $userLoggedInObj)
        {
            $this->con = $con;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        public function getVideos(){

            $videos = array();
            $subscriptions = $this->userLoggedInObj->getSubscription();
            
            if(sizeof($subscriptions) > 0){

                $condition = "";
                $i = 0;

                while($i < sizeof($subscriptions)){
                    if($i == 0){
                        $condition .= "WHERE uploadedBy=?";
                    }else{
                        $condition .= " OR uploadedBy=?";
                    }
                    $i++;
                }
                // username1, username2, username3
                // SELECT * FROM videos WHERE uploadedBy = ? OR uploadedBy = ? OR uploadedBy = ? 
                // $query->bindParam(1, "username1");
                // $query->bindParam(2, "username2");
                // $query->bindParam(3, "username3");
                    
                $videoQuery = $this->con->prepare("SELECT * FROM videos $condition 
                    ORDER BY uploadDate DESC");

                $j = 1;

                foreach ($subscriptions as $sub) {
                    $subUsername = $sub->getUsername();
                    $videoQuery->bindValue($j, $subUsername);
                    $j++;
                }
                
                $videoQuery->execute();

                while($row = $videoQuery->fetch(PDO::FETCH_ASSOC)){
                    $video = new Video($this->con, $row, $this->userLoggedInObj);
                    array_push($videos, $video);
                }
            }

            return $videos;
        }
    }
?>