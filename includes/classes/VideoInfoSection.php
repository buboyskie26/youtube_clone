<?php 
    require_once("includes/classes/VideoInfoControls.php");

    class VideoInfoSection{
        private $con, $video, $userLoggedInObj;

        public function __construct($con, $video, $userLoggedInObj)
        {
            $this->con = $con;
            $this->video = $video;
            $this->userLoggedInObj = $userLoggedInObj;
        }

       
        public function create(){
            return $this->createPrimaryInfo() .  $this->createSecondaryInfo();
        }

        public function createPrimaryInfo(){
            // It was possible because of this.
            //  $video = new Video($con, $_GET['id'], $userLoggedInObj);
            //  $videoInfoSection = new VideoInfoSection($con, $video, $userLoggedInObj);
            $title = $this->video->getTitle();
            $views = $this->video->getViews();

            $videoInfoControls = new VideoInfoControls($this->video,
                $this->userLoggedInObj);

            $buttons = $videoInfoControls->create();
            
            return "
                <div class='videoInfo'>
                    <h1>$title</h1>
                    <div class='bottomSection'>
                        <span class='viewCount'>$views</span>
                        $buttons
                    </div>
                </div>
            ";
        }

        public function createSecondaryInfo(){
            // 
            $description = $this->video->getDescription();
            $uploadDate = $this->video->getUploadDate();
            $uploadedBy = $this->video->getUploadedBy();

            $profileButton = ButtonProvider::createUserProfileButton(
                $this->con, $uploadedBy);

            if($uploadedBy == $this->userLoggedInObj->getUsername()){
                // Edit own video
                $actionButton = ButtonProvider::createEditVideoButton($this->video->getId());
            }else{
                
                $userToObj = new User($this->con, $uploadedBy);
                // Subscribe
                $actionButton = ButtonProvider::createSubscriberButton($this->con,
                    $userToObj, $this->userLoggedInObj);
            }
            return "
                <div class='secondaryInfo'>
                    <div class='topRow'>
                        $profileButton
                
                        <div class='uploadInfo'>
                            <span class='owner'>
                                <a href='profile.php?username=$uploadedBy'>
                                    $uploadedBy
                                </a>
                            </span>
                            <span class='date'>Published on $uploadDate</span>
                        </div>
                        $actionButton
                    </div>

                    <div class='descriptionContainer'>
                        $description
                    </div>

                </div>
            ";


        }
    }

?>