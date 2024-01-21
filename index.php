<?php include_once('includes/header.php') ?>


<div class="videoSection">
    <?php   
        //
        $subsProvider = new SubscriptionsProvider($con, $userLoggedInObj);
        $subscriptionVideos = $subsProvider->getVideos();

        $videoGrid = new VideoGrid($con, $userLoggedInObj);

        if(User::isLoggedIn() && sizeof($subscriptionVideos) > 0){
            echo $videoGrid->create($subscriptionVideos, "Subscriptions", false);
        }
        echo $videoGrid->create(null, "Recommended", false);

        // $subscribe = new SubscriptionTwo($con, $userLoggedInObj);
        // $subs = $subscribe->getVideos();
        // // print_r($subs);
        // $videoGridTwo = new VideoGridTwo($con, $userLoggedInObj);

        // if(User::isLoggedIn() && sizeof($subs) > 0){
        //     echo $videoGridTwo->create($subs, "Subscription");
        // }
        // echo $videoGridTwo->create(null, "Recommended");
        
    ?>
</div>

<?php include_once('includes/footer.php') ?>