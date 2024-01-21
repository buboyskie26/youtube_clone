<?php 

    class ButtonProvider{

        public static function createButton($text, $imageSrc, $action, $class){

            $image = $imageSrc == null ? "" : "<img src='$imageSrc'>";
            
            // Change action if needed.
            return "
                <button class='$class' onclick='$action'>
                    $image
                    <span class='text'>$text</span>
                </button>
            ";
        }

        public static function createUserProfileButton($con, $username){

            $userObj = new User($con, $username);
            $profilePic = $userObj->getProfilePic();
            $link = "profile.php?username=$username";
            
            return "
                <a href='$link'>
                    <img src='$profilePic' class='profilePicture'>
                </a>
            ";

        }

        public static function createHyperLinkButton($text, $imageSrc, $href, $class){
            $image = $imageSrc == null ? "" : "<img src='$imageSrc'>";
            return "
                    <a href='$href'>
                        <button class='$class'>
                            $image
                            <span class='text'>$text</span>
                        </button>
                    </a>
            ";
        }

        public static function createEditVideoButton($videoId){

            $href = "editVideo.php?videoId=$videoId";

            $button = ButtonProvider::createHyperLinkButton("EDIT VIDEO",
                null, $href, "edit button");

            // return "
            //      <div class='editVideoButtonContainer'>
            //         $button
            //     </div>
            // ";
            return "
                <div class='editVideoButtonContainer'>
                    <a href='$href'>
                        <button class='edit button'>
                            <span class='text'>EDIT VIDEO</span>
                        </button>
                    </a>
                </div>
            ";
        }

        public static function createSubscriberButton($con, $userToObj,
            $userLoggedInObj) {

            $userTo = $userToObj->getUsername();
            $userLoggedIn = $userLoggedInObj->getUsername();

            // UserLoggedIn Subscribed to the user whom uploaded the video.
            $isSubscribedTo = $userLoggedInObj->isSubscribedTo($userTo);

            $buttonText = $isSubscribedTo ? "SUBSCRIBED" : "SUBSCRIBE";

            $buttonText .= " " . $userToObj->getSubscriberCount();

            // If logged in user have subscribed
            $buttonClass = $isSubscribedTo ? "unsubscribe button" : "subscribe button";

            $action = "subscribex(\"$userTo\", \"$userLoggedIn\", this)";

            return "<div class='subscribeButtonContainer'>
                        <button class='$buttonClass' onClick='$action'>
                            <span class='text'>$buttonText</span>
                        </button>
                    </div>";

            // REFACTORED. !
            $button = ButtonProvider::createButton($buttonText, null, $action, $buttonClass);
            
            // return "<div class='subscribeButtonContainer'>
            //             $button
            //         </div>";

           
        }

        public static function createUserProfileNavigationButton($con, $username){

            $userObj = new User($con, $username);
            $profilePic = $userObj->getProfilePic();
            $link = "profile.php?username=$username";
            
            if(User::isLoggedIn()){
                return "
                    <a href='$link'>
                        <img src='$profilePic' class='profilePicture'>
                    </a>";
            }else{
                return "
                    <a href='signIn.php'>
                        <span class='signInLink'>SIGN IN</span>
                    </a>
                ";
            }

        }

    }

?>