<?php   

    include_once('includes/header.php');
    include_once('includes/classes/Comment.php');
    include_once('includes/classes/VideoPlayer.php');
    include_once('includes/classes/VideoInfoSection.php');
    include_once('includes/classes/CommentSection.php');

    if(!isset($_GET['id'])){
        echo "Video is not parsed";
        // prevent showing up the error.
        exit();
    }
    //
    $video = new Video($con, $_GET['id'], $userLoggedInObj);
    $video->incrementViews();
?>

<script src="assets/js/videoPlayerActions.js"></script>
<script src="assets/js/commentActions.js"></script>

<div class="watchLeftColumn">
    <?php 
    
        // $video has inserted url id.
        $videoPlayer = new VideoPlayer($video);
        echo $videoPlayer->create(false);

        // $video has inserted url id.
        $videoInfoSection = new VideoInfoSection($con, $video, $userLoggedInObj);
        echo $videoInfoSection->create();

        $commentSection = new CommentSection($con, $video, $userLoggedInObj);
        echo $commentSection->create();
    ?>
</div>
 
<div class="suggestions">

    <?php 
        $grid = new VideoGrid($con, $userLoggedInObj);

        echo $grid->create(null, null, false);

        // $gridTwo = new VideoGridTwo($con, $userLoggedInObj);
        // echo $gridTwo->create(null,null);
    ?>


</div>

<?php include_once('includes/footer.php') ?>