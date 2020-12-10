<?php

class Ratings extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addRating($filmId, $userId, $rating)
    {
        $query = "INSERT INTO films_ratings(title_id, user_id, rating) VALUES ('$filmId', $userId, $rating)";

        mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));
    }

    public function getTrackerRating($filmId)
    {
        $query = "SELECT films_ratings.rating FROM films_ratings WHERE films_ratings.title_id = '$filmId'";
        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $rating = null;
        for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
            $row = mysqli_fetch_row($result);
            $rating += $row[0];
        }

        if($rating != null){
            $votes = mysqli_num_rows($result);
            $ratingArr[] = $rating/$votes;
            $ratingArr[] = $votes;
            return $ratingArr;
        }else{
            return array("n/a", "n/a");
        }

    }

    public function isRatingAdded($filmId)
    {
        $userId = $this->getUserId($_COOKIE['username']);
        $query = "SELECT * FROM films_ratings WHERE films_ratings.user_id = $userId AND films_ratings.title_id = '$filmId'";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        if (mysqli_num_rows($result) > 0) return true;
        else return false;
    }
}