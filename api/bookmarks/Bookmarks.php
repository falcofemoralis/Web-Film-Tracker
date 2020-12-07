<?php

class Bookmarks extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getIsBookmarked($filmId)
    {
        $getQuery = "SELECT * 
                FROM bookmarks 
                WHERE bookmarks.title_id = '$filmId'";

        $result = mysqli_query($this->connection, $getQuery) or die("Ошибка " . mysqli_error($this->connection));
        $row = mysqli_fetch_row($result);

        if ($row != null) return "true";
        else return "false";
    }

    public function addToBookmarks($filmId, $userId)
    {
        $insertQuery = "INSERT INTO bookmarks(user_id, title_id)
                VALUES ('$userId','$filmId')";
        mysqli_query($this->connection, $insertQuery) or die("Ошибка " . mysqli_error($this->connection));
    }

    public function removeFromBookmarks($filmId, $userId)
    {
        $deleteQuery = "DELETE FROM bookmarks WHERE bookmarks.title_id='$filmId' AND bookmarks.user_id=$userId";
        mysqli_query($this->connection, $deleteQuery) or die("Ошибка " . mysqli_error($this->connection));
    }

    public function getUserBookmarks($userId)
    {
        $query = "SELECT * FROM bookmarks WHERE bookmarks.user_id=$userId";
        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $films = array();
        for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
            $row = mysqli_fetch_row($result);
            $films[] = $row[1];
        }

        return $films;
    }

}