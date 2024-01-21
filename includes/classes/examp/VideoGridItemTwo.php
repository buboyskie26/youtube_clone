<?php 

    class VideoGridItemTwo{

        private $video, $userLoggedInObj;

        public function __construct($video,$userLoggedInObj)
        {
            $this->video = $video;
            $this->userLoggedInObj = $userLoggedInObj;
        }

        public function create(){

            $thumbnail = $this->createThumbnail();
            $description =  $this->createDetails();
            $videoId = $this->video->getId();

            return "
                <a href='watch.php?id=".$videoId."'>
                    <div class='videoGridItem'>
                        $thumbnail
                        $description
                    </div>
                </a>
            ";
        }

        public function createThumbnail(){

            $thumbnail = $this->video->getThumbnailTwo(); 
            $duration = $this->video->getDuration(); 
            return "    
                    <div class='thumbnail'>
                        <img src='$thumbnail'>
                        <div class='duration'>
                            <span class='duration'>$duration</span>
                        </div>
                    </div>
            ";
        }

        public function createDetails(){
            
            $title = $this->video->getTitle();
            $uploadedBy = $this->video->getUploadedBy();
            $views = $this->video->getViews();
            $date = $this->video->getUploadDate();

            $description = $this->video->getDescription();

            $description = (strlen($description) > 350 
                ? substr($description, 0 ,347) . "..." : "");

            return "
                <div class='details'>
                    <h3 class='title'>$title</h3>
                    <span class='username'>$uploadedBy</span>

                    <div class='stats'>
                        <span class='viewCount'>$views views</span>
                        <span class='timeStamp'>$date</span>    
                    </div>
                    <span class='description'>$description</span> 
                </div>
            ";
        }

    }
?>