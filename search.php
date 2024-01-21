 
<?php 

    include_once('includes/header.php'); 

    if(!isset($_GET['term']) || $_GET['term'] == ""){
        echo "You must enter to search";
        exit();
    }

    $term = $_GET["term"];

    if(!isset($_GET['orderBy']) || $_GET['orderBy'] == "views"){
        $orderBy = "views";
    }else{
        $orderBy = "uploadDate";
    }

    $searchResults = new SearchResultsProvider($con, $userLoggedInObj);

    $videos = $searchResults->getVideos($term, $orderBy);

    $videoGrid = new VideoGrid($con, $userLoggedInObj);

?>

<div class="largeVideoGridContainer">
    <?php 
    
        if(sizeof($videos) > 0){
            echo $videoGrid->createLarge(
                $videos, sizeof($videos) . " results found", true);
        }else{
            echo "Not Found";
        }
    ?>
</div>

