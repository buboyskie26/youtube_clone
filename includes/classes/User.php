<?php
class User {

    private $con, $sqlData;

    public function __construct($con, $username) {
        
        $this->con = $con;

        $query = $this->con->prepare("SELECT * FROM users 
            WHERE username = :username");

        $query->bindParam(":username", $username);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public static function isLoggedIn(){
        return isset($_SESSION['userLoggedIn']);
    }

    public function getUsername() {
        return isset($this->sqlData['username']) ? $this->sqlData["username"] : ""; 
    }

    public function getName() {
        return $this->sqlData["firstName"] . " " . $this->sqlData["lastName"];
    }

    public function getFirstName() {
        return $this->sqlData["firstName"];
    }

    public function getLastName() {
        return $this->sqlData["lastName"];
    }

    public function getEmail() {
        return $this->sqlData["email"];
    }

    public function getProfilePic() {
        return isset($this->sqlData["profilePic"]) ? $this->sqlData["profilePic"] : "";
    }

    public function getSignUpDate() {
        return $this->sqlData["signUpDate"];
    }

    public function isSubscribedTo($userTo) {

        $query = $this->con->prepare("SELECT * FROM subscribers
            WHERE userTo=:userTo AND userFrom=:userLoggedIn");

        $userLoggedIn = $this->getUsername();

        $query->bindParam(":userTo", $userTo); 
        $query->bindParam(":userLoggedIn", $userLoggedIn);
        $query->execute();

        return $query->rowCount() > 0;
    }


    public function getSubscriberCount() {
        
        $query = $this->con->prepare("SELECT * FROM subscribers
            WHERE userTo=:userTo");

        $usernameWhoUploadedVideo = $this->getUsername();
        $query->bindParam(":userTo", $usernameWhoUploadedVideo); 
        $query->execute();

        return $query->rowCount();
    }
   
    public function getSubscription() : array{

        $userLoggedIn = $this->getUsername(); 
        // Get all you subscribes to.
        $query= $this->con->prepare("SELECT userTo FROM subscribers
            WHERE userFrom=:userLoggedIn");
            
        $query->bindValue(":userLoggedIn", $userLoggedIn);    

        $query->execute();

        $subs = array();

        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $user = new User($this->con, $row['userTo']);
            // Whos the userFrom Subscribes would be in the array
            array_push($subs, $user);
        }
        return $subs;
    }

    public function subscriptionTo() : array{ 

        $array = array();
        $userLoggedIn = $this->getUsername();

        $query = $this->con->prepare("SELECT userTo FROM subscribers
            WHERE userFrom=:userLoggedIn");

        $query->bindValue(":userLoggedIn", $userLoggedIn);
        $query->execute();

        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $user = new User($this->con, $row['userTo']);

            array_push($array, $user);
        }

        return $array;
    }
}
?>