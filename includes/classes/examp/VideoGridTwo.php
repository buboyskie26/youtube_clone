<?php 

    class VideoGridTwo{

        private $con, $userLoggedInObj;

        public function __construct($con, $userLoggedInObj)
        {
            $this->con = $con;
            $this->userLoggedInObj = $userLoggedInObj;
        }


        public function create($videosSubs, $title){

            if($videosSubs == null){
                $output = $this->generateVideoWithoutSubs();

            }else if($videosSubs != null){
                $output = $this->generateVideoWithSubs($videosSubs);

            }
            $header = "";

            if($title != null){
                $header = $this->generateHeader($title);
            }
            return "
                $header
                <div class='videoGrid'>
                    $output
                </div>
            ";
        }
        public function generateVideoWithoutSubs(){

            $query = $this->con->prepare("SELECT * FROM videos
                ORDER BY RAND() LIMIT 15");
            $query->execute();

            $elementOutput = "";

            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                $video = new Video($this->con, $row, $this->userLoggedInObj);
                // Other class we put the HTML Code
                $gridItem = new VideoGridItemTwo($video, $this->userLoggedInObj);


                $elementOutput .= $gridItem->create();
            }

            return $elementOutput;
        }

        public function generateVideoWithSubs($videosSubs){

            $elementOutput = "";
            
            foreach ($videosSubs as $value) {

                // $ideo = new Video($this->con, $value, $this->userLoggedInObj);

                $videoGridTwo = new VideoGridItemTwo($value, $this->userLoggedInObj);

                $elementOutput .= $videoGridTwo->create();
            }
            return $elementOutput;
        }
        public function generateHeader($title){
            return "
            
                <div class='videoGridHeader'>
                    <div class='left'>
                        $title
                    </div>
                </div>
            ";
        }
        
    }

?>