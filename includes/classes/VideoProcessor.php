<?php
    class VideoProcessor {

        private $con;
        private $sizeLimit = 500000000;
        private $allowedTypes = array("mp4", "flv", "webm", "mkv", "vob",
            "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg");

        private $ffmpegPath;
        private $ffprobePath;

        public function __construct($con) {
            $this->con = $con;
            $this->ffmpegPath = realpath("ffmpeg/bin/ffmpeg.exe");
            $this->ffprobePath = realpath("ffmpeg/bin/ffprobe.exe");
        }

        public function upload($videoUploadData){
          
            $targetDir = "uploads/videos";

            $videoData = $videoUploadData->videoDataArray;
            // Array ( [name] => Permutations and Combinations Tutorial.mp4 [full_path] => Permutations and Combinations Tutorial.mp4 [type] => video/mp4 [tmp_name] => C:\xampp\tmp\php7F17.tmp [error] => 0 [size] => 21351930 ) mp4

            $tempFilePath = $targetDir . uniqid() . basename($videoData['name']);
            // Permutations_and_Combinations_Tutorial.mp4 
            $tempFilePath = str_replace(" ", "_", $tempFilePath);

            $isValidData = $this->processData($videoData, $tempFilePath);
            
            if($isValidData == false){
                return false;
            }

            if(move_uploaded_file($videoData['tmp_name'], $tempFilePath)){
               
                $finalFilePath = $targetDir . uniqid() . ".mp4";
                if(!$this->insertVideoData($videoUploadData, $finalFilePath)){
                    echo "Insert query failed";
                    return false;
                }

                if(!$this->convertVideoToMp4($tempFilePath, $finalFilePath)){
                    echo "Upload Failed";
                    return false;
                }

                if(!$this->deleteFile($tempFilePath)){
                    echo "Upload Failed";
                    return false;
                }

                if(!$this->generateThumbnail($finalFilePath)){
                        echo "Upload Failed, Cant generate thumbnail.\n";
                    return false;
                }
                
                return true;
            }

            
        }
        private function insertVideoData($videoUploadData, $finalFilePath){

            $query = $this->con->prepare("INSERT INTO videos(title, uploadedBy,
                description, privacy, category, filePath)
                VALUES(:title, :uploadedBy, :description, :privacy, :category, :filePath)");

            $query->bindParam(":title", $videoUploadData->title);
            $query->bindParam(":uploadedBy", $videoUploadData->uploadedBy);
            $query->bindParam(":description", $videoUploadData->description);
            $query->bindParam(":privacy", $videoUploadData->privacy);
            $query->bindParam(":category", $videoUploadData->category);
            $query->bindParam(":filePath", $finalFilePath);

            return $query->execute();

        }
        private function processData($videoData, $filePath){
            // mp4
            $videoType = pathinfo($filePath, PATHINFO_EXTENSION);
            
            if(!$this->isValidSize($videoData)){
                echo "File too large, Cant be more than " . $this->sizeLimit . " bytes";
                return false;
            }
            else if(!$this->dontHaveError($videoData)){
                echo "Error code: " . $videoData["error"];
                return false;
            }
            else if(!$this->isValidType($videoType)){
                echo "Invalid file type";
                return false;
            }
            return true;
        }

        public function convertVideoToMp4($tempFilePath, $finalFilePath) {
            $cmd = "$this->ffmpegPath -i $tempFilePath $finalFilePath 2>&1";

            $outputLog = array();
            exec($cmd, $outputLog, $returnCode);
            
            if($returnCode != 0) {
                //Command failed
                foreach($outputLog as $line) {
                    echo $line . "<br>";
                }
                return false;
            }
            return true;
        }

        private function isValidSize($videoType){
            return $videoType["size"] <= $this->sizeLimit;
        }

        private function isValidType($type){
            $lowercased = strtolower($type);
            return in_array($lowercased, $this->allowedTypes);
        }
        private function dontHaveError($data){
            return $data['error'] == 0;
        }

        private function deleteFile($filePath){
            if(!unlink($filePath)){
                echo "Could not delete file\n";
                return false;
            }
            return true;
        }
        private function generateThumbnail($filePath){

            $thumbnailSize = "218x118";
            $numThumbnails = 3;
            $pathToThumbnail = "uploads/videos/thumbnails";

            $duration = $this->getVideoDuration($filePath);
            $videoId = $this->con->lastInsertId();
            $this->updateDuration($duration, $videoId);

            for ($num = 1; $num <= $numThumbnails; $num++) { 

                $imageName = uniqid() . ".png";
                $interval = ($duration * 0.8) / $numThumbnails * $num;
                $fullThumbnailPath = "$pathToThumbnail/$videoId-$imageName";

                $cmd = "$this->ffmpegPath -i $filePath -ss $interval -s $thumbnailSize -vframes 1 $fullThumbnailPath 2>&1";

                $outputLog = array();
                exec($cmd, $outputLog, $returnCode);
                
                if($returnCode != 0) {
                    //Command failed
                    foreach($outputLog as $line) {
                        echo $line . "<br>";
                    }
                }

                $selected = $num == 1 ? 1 : 0;

                $query = $this->con->prepare("INSERT INTO thumbnail (videoId,    filePath,selected)
                VALUES(:videoId, :filePath, :selected)");

                $query->bindParam(":videoId", $videoId);
                $query->bindParam(":filePath", $fullThumbnailPath);
                $query->bindParam(":selected", $selected);

                $success = $query->execute();
                if(!$success) {
                    echo "Error inserting thumbnail\n";
                    return false;
                }
            }

            return true;
        }
        private function getVideoDuration($filePath){
            return (int)shell_exec("$this->ffprobePath -v error -show_entries        format=duration -of default=noprint_wrappers=1:nokey=1 $filePath");
        }

        private function updateDuration($duration, $videoId){
            // $duration = (int)$duration;

            $hours = floor($duration / 3600);
            $mins = floor(($duration - ($hours * 3600)) / 60);
            $secs = floor($duration % 60);

            $hours = ($hours < 1) ? "" : $hours . ":";
            $mins = ($mins < 10) ? "0" . $mins . ":" : $mins . ":";
            $secs = ($secs < 10) ? "0" . $secs : $secs;

            $duration = $hours . $mins . $secs;
            // Update the videos SET duration
            $query = $this->con->prepare("UPDATE videos 
                SET duration=:duration
                WHERE id=:videoId");
            
            $query->bindParam(":duration", $duration);
            $query->bindParam(":videoId", $videoId);
            $query->execute();
        }
    }


?>