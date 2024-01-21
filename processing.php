<?php
    require_once('includes/header.php');   
    require_once('includes/classes/VideoUploadData.php');   
    require_once('includes/classes/VideoProcessor.php');   

    if(!isset($_POST['uploadButton'])){
        echo "No file sent";
        exit();
    }
    // 1. Create file upload Data.
    $videoUploadData = new VideoUploadData(
                            $_FILES['fileInput'],
                            $_POST['titleInput'],
                            $_POST['descriptionInput'],
                            $_POST['privacyInput'],
                            $_POST['categoryInput'],
                            $userLoggedInObj->getUsername());
    
    // 2.Process Video Data.
    $videoProcessor = new VideoProcessor($con);

    $wasSuccessful = $videoProcessor->upload($videoUploadData);
    
    // 3. Check if upload was succesful.
    if($wasSuccessful == true){
        echo "Upload successful";
    }
?>