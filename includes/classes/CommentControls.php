<?php 
    // it has a constructor of Comment.php class
    // Comment.php already below require once path
    // require_once('includes/classes/ButtonProvider.php');
    require_once('ButtonProvider.php');
    
    class CommentControls{

        private $con, $comment, $userLoggedInObj;

        public function __construct($con, $comment, $userLoggedInObj)
        {
            $this->con = $con;
            $this->comment = $comment;
            $this->userLoggedInObj = $userLoggedInObj;
        }
       
        public function create(){
            
            // $replyButton = $this->createReplyButton();
            // Like Count - Dislike Count on each the same commentId.
            $totalLikesCount = $this->comment->getLikes();
            if($totalLikesCount == 0) $totalLikesCount = "0";

            $replySection = $this->createReplySection();

            $commentId = $this->comment->getId();
            $videoId = $this->comment->getVideoId();
            // Like Button
           
            $likeAction = "likeComment($commentId, this, $videoId)";
            $likeImageSrc = "assets/images/icons/thumb-up.png";
            $likeClass = "likeButton";

            if($this->comment->wasLikedBy()){
                $likeImageSrc = "assets/images/icons/thumb-up-active.png";
            }
            // Dislike Button
            $dislikeAction = "dislikeComment($commentId, this, $videoId)";
            $dislikeImageSrc = "assets/images/icons/thumb-down.png";
            $dislikeClass = "dislikeButton";

            if($this->comment->wasDislikedBy()){
                $dislikeImageSrc = "assets/images/icons/thumb-down-active.png";
            }

            return "
                <div class='controls'>
                    <button onclick='toggleReply(this)'>
                        <span class='text'>REPLY</span>
                    </button>

                    <span class='likesCount'>$totalLikesCount</span>

                     <button class='$likeClass' onclick='$likeAction'>
                        <img src='$likeImageSrc' alt='Image'>
                        <span class='text'></span>
                    </button>

                    <button class='$dislikeClass' onclick='$dislikeAction'>
                        <img src='$dislikeImageSrc' alt='Image'>
                        <span class='text'></span>
                    </button>
                </div>
                $replySection
            ";

            // return "
            //     <div class='controls'>
            //         $likeButton
            //         $dislikeButton
            //     </div>
            // ";
        }
        private function createReplySection(){

            $postedBy = $this->userLoggedInObj->getUsername();

            $commentId = $this->comment->getId();
            $videoId = $this->comment->getVideoId();

            $profileButton = ButtonProvider::createUserProfileButton($this->con,
                $postedBy);

            $cancelButtonAction = "toggleReply(this)";
            $cancelButton = ButtonProvider::createButton("Cancel",null,
                $cancelButtonAction,"cancelComment");

            $postButtonAction = "postComment(this, \"$postedBy\", $videoId,
                $commentId, \"repliesSection\")";
                
            $postButton = ButtonProvider::createButton("Reply",null,
                $postButtonAction,"postComment");

            $profilePic = $this->userLoggedInObj->getProfilePic();
            $username = $this->userLoggedInObj->getUsername();
            $link = "profile.php?username=$username";
            
            return "
               <div class='commentForm hidden'>
                    <a href='$link'>
                        <img src='$profilePic' class='profilePicture'>
                    </a>
                    <textarea class='commentBodyClass'
                        placeholder='Add a public comment'></textarea>

                    <button class='cancelComment' onclick='$cancelButtonAction'>
                        <span class='text'>Cancel</span>
                    </button>

                    <button class='postComment' onclick='$postButtonAction'>
                        <span class='text'>Reply</span>
                    </button>
                </div>
            ";
        }

        private function createReplyButton(){

            $text = "TEXT";
            $action = "toggleReply(this)";
            ButtonProvider::createButton($text, null, $action, null);
            return "
                <button class='' onclick='$action'>
                    <span class='text'>$text</span>
                </button>
            ";
        }
        
        private function createLikesCount(){
            $totalLikesCount = $this->comment->getLikes();
            if($totalLikesCount == 0) $totalLikesCount = "";

            return "    
                <span class='likesCount'>$totalLikesCount</span>
            ";
        }
        private function createLikeButton(){

            $commentId = $this->comment->getId();
            $videoId = $this->comment->getVideoId();
            $action = "likeComment($commentId, this, $videoId)";
            $imageSrc = "assets/images/icons/thumb-up.png";
            $class = "likeButton";

            if($this->comment->wasLikedBy()){
                $imageSrc = "assets/images/icons/thumb-up-active.png";
            }
            
            return "
                    <button class='$class' onclick='$action'>
                        <img src='$imageSrc' alt='Image'>
                        <span class='text'></span>
                    </button>
                ";
        }
        //
        private function createDislikeButton(){

            $commentId = $this->comment->getId();
            $videoId = $this->comment->getVideoId();
            $action = "dislikeComment($commentId, this, $videoId)";
            $imageSrc = "assets/images/icons/thumb-down.png";
            $class = "dislikeButton";

            if($this->comment->wasDislikedBy()){
                $imageSrc = "assets/images/icons/thumb-down-active.png";
            }
            return "
                    <button class='$class' onclick='$action'>
                        <img src='$imageSrc' alt='Image'>
                        <span class='text'></span>
                    </button>
                ";
        }
    }

?>