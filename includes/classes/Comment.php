<?php

    require_once('ButtonProvider.php');
    require_once('CommentControls.php');
    // require_once('includes/classes/CommentControls.php');

    class Comment{

        private $con, $sqlData, $userLoggedInObj, $videoId;

        public function __construct($con, $input, $userLoggedInObj, $videoId)
        {
            // If not array because $input result aas an array.
            if(!is_array($input)){

                $query = $con->prepare("SELECT * FROM comments
                    WHERE id=:commentId");

                $query->bindParam(":commentId", $input);
                $query->execute();

                $input = $query->fetch(PDO::FETCH_ASSOC);
            }
            //
            $this->sqlData = $input;
            $this->con=$con;
            $this->userLoggedInObj = $userLoggedInObj;
            $this->videoId = $videoId;
        }

        public function getId(){
            return $this->sqlData['id'];
        }
        public function getVideoId(){
            return $this->videoId;
        }

        public function create(){

            $body = $this->sqlData['body'];

            $postedBy = $this->sqlData['postedBy'];

            $timeSpan= $this->time_elapsed_string($this->sqlData['datePosted']);

            $username = $this->userLoggedInObj->getUsername();

            $profilePic = $this->userLoggedInObj->getProfilePic();
            $link = "profile.php?username=$username";

            $profileButton = ButtonProvider::createUserProfileButton($this->con,
                $postedBy);

            $commentControlsObj = new CommentControls($this->con, $this,
                $this->userLoggedInObj);

            $commentControls =  $commentControlsObj->create();
            //
            $numOfResponses = $this->getNumberOfReplies();
        

            $commentId = $this->getId();
            $videoId = $this->getVideoId();

            // if($numOfResponses > 0){
            //     $viewRepliesText = "
            //         <span class='repliesSection viewReplies'
            //             onClick='getReplies($commentId, this, $videoId)'>
            //                 View All $numOfResponses replies
            //         </span>
            //     ";
            // }else{
            //     $viewRepliesText = "
            //         <div class='repliesSection'></div>
            //     ";
            // }
            $commentReplies = $this->getReplies();

            if($numOfResponses > 0){

                $viewRepliesText = "
                        <span style='padding-left: 64px;' class='viewReplies'
                            onClick='getReplies($commentId, this, $videoId)'>
                                View All <span class='replyCount'>$numOfResponses</span> replies
                        </span>
                        <div class='repliesSection hidden'>
                            $commentReplies
                        </div>
                ";
            }else{
                $viewRepliesText = "
                    <div class='repliesSection'></div>
                ";
            }
            
            $res =  "
                    <div class='itemContainer'>
                        <div class='comment'>
                            <a href='$link'>
                                <img src='$profilePic' class='profilePicture'>

                            </a>
                            <div class='mainContainer'>
                                <div class='commentHeader'>
                                    <a href='profile.php?username=$postedBy'>
                                        <span class='username'>$postedBy</span>
                                    </a>
                                    <span class='timestamp'>$timeSpan $numOfResponses</span>
                                </div>

                                <div class='body'>
                                    $body
                                </div>
                            </div>
                        </div>
                        $commentControls
                        $viewRepliesText
                    </div>
                ";

            $results = array(
                "result" => $res,
                "replies" => $numOfResponses
            );

            return $res;
        }

        public function create2(){

            $body = $this->sqlData['body'];

            $postedBy = $this->sqlData['postedBy'];

            $timeSpan= $this->time_elapsed_string($this->sqlData['datePosted']);

            $username = $this->userLoggedInObj->getUsername();

            $profilePic = $this->userLoggedInObj->getProfilePic();
            $link = "profile.php?username=$username";

            $profileButton = ButtonProvider::createUserProfileButton($this->con,
                $postedBy);

            $commentControlsObj = new CommentControls($this->con, $this,
                $this->userLoggedInObj);

            $commentControls =  $commentControlsObj->create();
            //
            $numOfResponses = $this->getNumberOfReplies();

            $commentId = $this->getId();
            $videoId = $this->getVideoId();
 
            $commentReplies = $this->getReplies();

            if($numOfResponses > 0){
                $viewRepliesText = "
                        <span style='padding-left: 64px;' class='viewReplies'
                            onClick='getReplies($commentId, this, $videoId)'>
                                View All <span class='replyCount'>$numOfResponses</span> replies
                        </span>
                        <div class='repliesSection hidden'>
                            $commentReplies
                        </div>
                ";
            }else{
                $viewRepliesText = "
                    <div class='repliesSection'></div>
                ";
            }
            
            $res =  "
                    <div class='itemContainer'>

                        <div class='comment'>
                            <a href='$link'>
                                <img src='$profilePic' class='profilePicture'>
                            </a>
                            <div class='mainContainer'>
                                <div class='commentHeader'>
                                    <a href='profile.php?username=$postedBy'>
                                        <span class='username'>$postedBy</span>
                                    </a>
                                    <span class='timestamp'>$timeSpan</span>
                                </div>

                                <div class='body'>
                                    $body
                                </div>
                            </div>
                        </div>
                        $commentControls
                        $viewRepliesText
                    </div>
                ";


            $results = array(
                "result" => $res,
                "replies" => $numOfResponses
            );

            return json_encode($results);
        }

        public function getNumberOfReplies(){

            $commentId = $this->sqlData['id'];
            
            $query = $this->con->prepare("SELECT count(*) FROM comments
                WHERE responseTo=:responseTo");
            
            $query->bindParam(":responseTo", $commentId);

            $query->execute();
            return $query->fetchColumn();
        }
        
        //
        public function time_elapsed_string($datetime, $full = false) {
            $now = new DateTime;
            $ago = new DateTime($datetime);
            $diff = $now->diff($ago);
        
            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;
        
            $string = array(
                'y' => 'year',
                'm' => 'month',
                'w' => 'week',
                'd' => 'day',
                'h' => 'hour',
                'i' => 'minute',
                's' => 'second',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                } else {
                    unset($string[$k]);
                }
            }
        
            if (!$full) $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) . ' ago' : 'just now';
        }
        public function getLikes(){
            $likes = $this->con->prepare("SELECT count(*) as 'count' FROM likes
                WHERE commentId=:commentId");

            $commentId = $this->getId();

            $likes->bindParam(":commentId", $commentId);
            $likes->execute();

            $data = $likes->fetch(PDO::FETCH_ASSOC);
            $likeCount = $data['count'];

            $dislikes = $this->con->prepare("SELECT count(*) as 'count' FROM dislikes
                WHERE commentId=:commentId");

            $dislikes->bindParam(":commentId", $commentId);
            $dislikes->execute();
            
            $data = $dislikes->fetch(PDO::FETCH_ASSOC);
            $dislikesCount = $data['count'];

            return $likeCount - $dislikesCount;
        }
        public function wasLikedBy(){

            $username = $this->userLoggedInObj->getUsername();
            $commentId = $this->getId();

            $query = $this->con->prepare("SELECT * FROM likes 
                WHERE username=:username AND commentId=:commentId");

            $query->bindParam(":username", $username);
            $query->bindParam(":commentId", $commentId);

            $query->execute();
            return $query->rowCount() > 0;
        }

        public function wasDislikedBy(){
            $username = $this->userLoggedInObj->getUsername();
            $commentId = $this->getId();

            $query = $this->con->prepare("SELECT * FROM dislikes 
                WHERE username=:username AND commentId=:commentId");

            $query->bindParam(":username", $username);
            $query->bindParam(":commentId", $commentId);

            $query->execute();
            return $query->rowCount() > 0;
        }

        public function like(){

            $commentId = $this->getId();
            $username = $this->userLoggedInObj->getUsername();

            if($this->wasLikedBy()){

                $deleteLike = $this->con->prepare("DELETE FROM likes
                    WHERE username=:username AND commentId=:commentId");

                $deleteLike->bindParam(":commentId", $commentId);
                $deleteLike->bindParam(":username", $username);
                $deleteLike->execute();

                $count = $deleteLike->rowCount();

                // $result = arraY(
                //     "likes" => -1,
                //     "dislikes" => 0,
                // );
                // return json_encode($result);

                return -1;
            }else{
                   
                $deleteDislike = $this->con->prepare("DELETE FROM dislikes
                    WHERE username=:username AND commentId=:commentId");

                $deleteDislike->bindParam(":commentId", $commentId);
                $deleteDislike->bindParam(":username", $username);
                $deleteDislike->execute();

                $count = $deleteDislike->rowCount();
                
                $insertLike = $this->con->prepare("INSERT INTO likes (commentId, username)
                    VALUES(:commentId, :username)");
                $insertLike->bindParam(":commentId", $commentId);
                $insertLike->bindParam(":username", $username);
                $insertLike->execute();

                // If you dislike it will reflect as -1
                // Then 1 + 1 = 2, it would reflect as +1
                return 1 + $count;
            }
        }

        public function dislike(){

            $commentId = $this->getId();
            $username = $this->userLoggedInObj->getUsername();

            if($this->wasDislikedBy()){
                
                $deleteLike = $this->con->prepare("DELETE FROM dislikes
                    WHERE username=:username AND commentId=:commentId");

                $deleteLike->bindParam(":commentId", $commentId);
                $deleteLike->bindParam(":username", $username);
                $deleteLike->execute();

                $count = $deleteLike->rowCount();

                return 1;
            }else{
                   
                $deleteLike = $this->con->prepare("DELETE FROM likes
                    WHERE username=:username AND commentId=:commentId");
                    
                $deleteLike->bindParam(":commentId", $commentId);
                $deleteLike->bindParam(":username", $username);
                $deleteLike->execute();

                $count = $deleteLike->rowCount();
                
                $insertDislike = $this->con->prepare("INSERT INTO dislikes (commentId, username)
                    VALUES(:commentId, :username)");
                $insertDislike->bindParam(":commentId", $commentId);
                $insertDislike->bindParam(":username", $username);
                $insertDislike->execute();
 
                return -1 - $count;
            }
        }

        public function getReplies(){

            $commentId = $this->getId();
            $videoId = $this->getVideoId();

            $query = $this->con->prepare("SELECT * FROM comments
                WHERE responseTo=:commentId AND videoId=:videoId
                ORDER BY id ASC");
            
            $query->bindParam(":commentId", $commentId);
            $query->bindParam(":videoId", $videoId);

            $query->execute();

            $comments = "";

            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $comment = new Comment($this->con, $row,
                    $this->userLoggedInObj, $videoId);
                
                $comments .= $comment->create();
            }
            // will return an html string.
            return $comments;
        }
    }

?>