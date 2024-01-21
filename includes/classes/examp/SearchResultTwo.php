<?php


class SearchResultTwo{

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj) {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }


    public function getVideos($term, $orderBy){

        $query = $this->con->prepare("SELECT * FROM videos
            WHERE title LIKE CONCAT('%', :term, '%')
            OR uploadedBy LIKE CONCAT('%', :term, '%')
            ORDER BY $orderBy DESC");

        $query->bindValue(":term", $term);
        $query->execute();

        $array = array();
        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $video = new Video($this->con, $row, $this->userLoggedInObj);
            array_push($array, $video);
        }

        return $array;
    }
}

?>