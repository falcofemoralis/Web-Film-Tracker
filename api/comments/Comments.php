<?php

class Comments extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addComment($comment, $filmId, $user, $time)
    {
        $userId = $this->getUserId($user);
        $insertQuery = "INSERT INTO films_comments(titleId, userId, time, comment)
                VALUES('$filmId', '$userId', '$time', '$comment')";

        $result = mysqli_query($this->connection, $insertQuery) or die("Ошибка " . mysqli_error($this->connection));
        if (!$result) $error = "Ошибка добавления";
        return $error;
    }

    public function deleteComment($filmId, $user, $time)
    {
        $userId = $this->getUserId($user);
        $deleteQuery = "DELETE FROM films_comments
            WHERE films_comments.titleId = '$filmId' AND films_comments.userId = " . $userId . " AND films_comments.time = '$time'";

        $result = mysqli_query($this->connection, $deleteQuery) or die("Ошибка " . mysqli_error($this->connection));
        if (!$result) $error = "Ошибка удаления";
        return $error;
    }

    public function getComments($filmId)
    {
        $query = "SELECT films_comments.titleId, films_comments.userId, films_comments.time, films_comments.comment 
            FROM films_comments 
            WHERE films_comments.titleId='$filmId'
            ORDER BY films_comments.time DESC";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $comments = array();
        for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
            $row = mysqli_fetch_row($result);
            $comments[] = new Comment($row[0], $row[2], $row[3], $row[1]);
        }

        return $comments;
    }

    public function getLastComments()
    {
        $query = "SELECT films_comments.titleId, films_comments.userId, films_comments.time, films_comments.comment 
            FROM films_comments 
            ORDER BY films_comments.time DESC
            LIMIT 10";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $comments = array();
        for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
            $row = mysqli_fetch_row($result);
            $comments[] = new Comment($row[0], $row[2], $row[3], $row[1]);
        }

        return $comments;
    }
}

