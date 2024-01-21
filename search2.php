<?php 

    include_once('includes/header.php'); 


    if(!isset($_GET['term'])){
        echo "You must enter to search";
        exit();
    }

    $term = $_GET['term'];

    if(!isset($_GET['orderBy']) || $_GET['orderBy'] == 'views')
        $orderBy = "views";
    else
        $orderBy = "uploadDate";

    $search = new SearchResultTwo($con, $userLoggedInObj);

    $searchResult = $search->getVideos($term, $orderBy);

    $videoGrid = new VideoGrid($con, $userLoggedInObj);
?>


<div class="largeVideoContainer">
    <?php
        if(sizeof($searchResult) > 0){
            echo $videoGrid->createLargeTwo($searchResult,
            sizeof($searchResult) . " results found", true);
        }else{
            echo "No results found";
        }
    
    ?>
</div>