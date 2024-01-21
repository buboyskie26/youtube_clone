<?php 

    require_once('includes/classes/ButtonProvider.php');

    class VideoInfoControls{

        private $con, $video, $userLoggedInObj;

        public function __construct($video, $userLoggedInObj)
        {
            $this->video = $video;
            $this->userLoggedInObj = $userLoggedInObj;
        }
       
        public function create(){
            $likeButton = $this->createLikeButton();
            $dislikeButton = $this->createDislikeButton();

            $likesCount = $this->video->getLikes();
            $videoId = $this->video->getId();
            $likesAction = "likedVideo(this, $videoId)";
            $likeClass = "likeButton";
            
            $imageThumbUp = "assets/images/icons/thumb-up.png";
            if($this->video->wasLikeBy()){
                $imageThumbUp = "assets/images/icons/thumb-up-active.png";
            }

            return "
                <div class='controls'>
                    <button class='$likeClass' onclick='$likesAction'>
                        <img src='$imageThumbUp' alt='Image'>
                        <span class='text'>$likesCount</span>
                    </button>
                    $dislikeButton
                </div>
            ";

            // return "
            //     <div class='controls'>
            //         $likeButton
            //         $dislikeButton
            //     </div>
            // ";
        }

        private function createLikeButton(){

            $likesCount = $this->video->getLikes();
            $videoId = $this->video->getId();
            $action = "likeVideo(this, $videoId)";
            $class = "likeButton";
            
            $imageSrc = "assets/images/icons/thumb-up.png";
            // return ButtonProvider::createButton($text, $imageSrc,$action, $class);
            if($this->video->wasLikeBy()){
                $imageSrc = "assets/images/icons/thumb-up-active.png";
            }
            return "
                <button class='$class' onclick='$action'>
                    <img src='$imageSrc' alt='Image'>
                    <span class='text'>$likesCount</span>
                </button>
            ";
        }
        //
        private function createDislikeButton(){

            $text = $this->video->getDislikes();
            $videoId = $this->video->getId();
            $action = "dislikeVideo(this, $videoId)";
            $class = "dislikeButton";
            $imageSrc = "assets/images/icons/thumb-down.png";
            
            // return ButtonProvider::createButton($text, $imageSrc,$action, $class);
            if($this->video->wasDislikeBy()){
                $imageSrc = "assets/images/icons/thumb-down-active.png";
            }
            return "
                <button class='$class' onclick='$action'>
                    <img src='$imageSrc' alt='Image'>
                    <span class='text'>$text</span>
                </button>
            ";

            // return "
            //     <button>Dislike</button>
            // ";
        }
    }

?>