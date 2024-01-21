<?php 

    class SubscriptionTwo{

        private $con, $userLoggedInObj;

        public function __construct($con, $userLoggedInObj)
        {
            $this->con = $con;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        
        public function getVideos() : array{

            $userSubscriptionsTo = $this->userLoggedInObj->subscriptionTo();
            $videos = array();

            $i = 0;
            $condition = "";

            if(sizeof($userSubscriptionsTo) > 0){

                while($i < sizeof($userSubscriptionsTo)){
                    if($i == 0){
                        $condition .= "WHERE uploadedBy=?";
                    }else{
                        $condition .= " OR uploadedBy=?";
                    }
                    $i++;
                }

                $videoSql = "SELECT * FROM videos $condition 
                    ORDER BY uploadDate DESC";

                $videoQuery = $this->con->prepare($videoSql);

                $j = 1;
                 // username1, username2, username3
                // SELECT * FROM videos WHERE uploadedBy = ? OR uploadedBy = ? OR uploadedBy = ? 
                // $query->bindValue(1, "username1");
                // $query->bindValue(2, "username2");
                // $query->bindValue(3, "username3");
                foreach ($userSubscriptionsTo as $user) {

                    $usersSubs = $user->getUsername();
                    $videoQuery->bindValue($j, $usersSubs);
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