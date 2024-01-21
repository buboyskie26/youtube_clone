<?php 
    require_once("includes/classes/ButtonProvider.php");
    require_once("includes/classes/Video.php");

    class CommentSection{

        private $con, $video, $userLoggedInObj;

        public function __construct($con, $video, $userLoggedInObj)
        {
            $this->con = $con;
            $this->video = $video;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        public function create(){

            $numComments = $this->video->getNumberOfComments();
            $postedBy = $this->userLoggedInObj->getUsername();
            $videoId = $this->video->getId();

            $commentAction = "postComment(this, \"$postedBy\", $videoId,
                null, \"comments\")";

            $commentButton = ButtonProvider::createButton("COMMENT",null,
                $commentAction,"postComment");
            
            $comments = $this->video->getComments();

            $commentsItem = "";

            foreach ($comments as $value) {
                $commentsItem .= $value;
            }
            
            $profileButton = ButtonProvider::createUserProfileButton($this->con,
                $postedBy);
            $profilePic =  $this->userLoggedInObj->getProfilePic();
            $link = "profile.php?username=$postedBy";

            return "
                <div class='commentSection'>

                    <div class='header'>
                        <span class='commentCount'>$numComments Comments</span>

                        <div class='commentForm'>
                            <a href='$link'>
                                <img src='$profilePic' class='profilePicture'>
                            </a>
                            <textarea class='commentBodyClass' 
                                placeholder='Add a public comment'></textarea>
                            
                            <button class='postComment' onclick='$commentAction'>
                                <span class='text'>COMMENT</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class='comments'>
                        $commentsItem
                    </div>

                </div>
            ";
        }

        // public function createCommentSection(){
           
           
        // }

        
    }


?>