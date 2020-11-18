<?php
require_once 'scripts/php/Objects/Film.php';
require_once 'scripts/php/Objects/Actor.php';

class DatabaseManager
{
    public $connection, $genresArray;

    function __construct()
    {
        $host = 'localhost'; // адрес сервера 
        $database = 'filmsdb'; // имя базы данных
        $user = 'root'; // имя пользователя
        $password = 'root'; // пароль

        $connection = mysqli_connect($host, $user, $password, $database)
        or die("Ошибка " . mysqli_error($connection));

        $this->connection = $connection;
    }

    public function getGenres()
    {
        if ($this->genresArray == null) {
            $this->loadGenres();
            return $this->genresArray;
        } else {
            return $this->genresArray;
        }
    }

    public function getGenreById($genreId)
    {
        if ($this->genresArray == null)
            $this->loadGenres();

        return $this->genresArray[$genreId - 1][0];
    }

    public function getGenreByName($genreName)
    {
        $query = "SELECT  genres.genre_id, genres_translated.genre
                FROM genres 
                INNER JOIN genres_translated on genres_translated.genre_id=genres.genre_id
                WHERE genres.name = '$genreName' AND genres_translated.lang_id = 3";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));
        return mysqli_fetch_row($result);
    }

    private function loadGenres()
    {
        $query = "SELECT genres_translated.genre, genres.name, genres.genre_id FROM genres 
        INNER JOIN genres_translated on genres_translated.genre_id=genres.genre_id 
        WHERE genres_translated.lang_id = 3";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));
        if ($result) {

            for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
                $row = mysqli_fetch_row($result);
                $this->genresArray[$row[2] - 1] = array($row[0], $row[1]);
            }
        }
    }

    //Популярные фильмы
    public function getPopularFilms($limit)
    {
        $query = "SELECT films.title_id, films_translated.title, films.premiered, films.genres
            FROM films INNER JOIN films_Translated ON films.title_id=films_Translated.title_id 
            INNER JOIN ratings ON films.title_id=ratings.title_id
            WHERE films_Translated.lang_id = 3 AND ratings.rating > 6.5 AND ratings.votes > 40000 AND films.premiered = 2020 
            ORDER BY ratings.votes DESC LIMIT $limit";

        return $this->getShortFilmsFromQuery($query);
    }

    //фильмы 2020 года
    public function getFilmsByYear($year, $limit)
    {
        $query = "SELECT films.title_id, films_translated.title, films.premiered, films.genres
            FROM films INNER JOIN films_Translated ON films.title_id=films_Translated.title_id 
            INNER JOIN ratings ON films.title_id=ratings.title_id
            WHERE films_Translated.lang_id = 3 AND premiered=$year limit $limit";

        return $this->getShortFilmsFromQuery($query);
    }

    public function getFilmByFilmId($id, $isShort)
    {
        $queryParam = "WHERE films_Translated.lang_id = 3 AND films.title_id = '$id'";

        $film = null;
        if ($isShort) {
            $query = "SELECT films.title_id, films_translated.title, films.premiered, films.genres
             FROM films INNER JOIN films_Translated ON films.title_id=films_Translated.title_id " . $queryParam;
            $film = $this->getShortFilmsFromQuery($query);
        } else {
            $query = "SELECT films.title_id, films_translated.title, films.is_adult, films.premiered,
            films.runtime_minutes, films.genres, films_translated.plot, ratings.rating, ratings.votes
            FROM films INNER JOIN films_Translated ON films.title_id=films_Translated.title_id 
            INNER JOIN ratings ON films.title_id=ratings.title_id " . $queryParam;
            $film = $this->getLongFilmsFromQuery($query);
        }


        return $film[0];
    }

    //получение короткой информации про фильм
    private function getShortFilmsFromQuery($query)
    {
        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $films = null;
        for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
            $row = mysqli_fetch_row($result);
            $films[$i] = new Film($row[0], $row[1], "", $row[2], "", $row[3], "", "", "");
        }

        return $films;
    }

    //получение полной информации про фильм
    private function getLongFilmsFromQuery($query)
    {
        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $films = null;
        for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
            $row = mysqli_fetch_row($result);
            $films[$i] = new Film($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8]);
        }

        return $films;
    }

    public function getActorsByFilmId($filmId)
    {
        $query = "SELECT people.person_id, people.name, crew.characters, crew.category 
            FROM crew INNER JOIN people ON people.person_id = crew.person_id 
            WHERE crew.title_id = '$filmId'";
        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $actors = null;
        for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
            $row = mysqli_fetch_row($result);
            $actors[$i] = new Actor($row[0], $row[1], 0, 0, $row[2], $row[3]);
        }
        return $actors;
    }

    public function getFilmsAmountInCategory($genreId)
    {
        $query = "SELECT COUNT(*)
            FROM films
            WHERE (films.genres like '%,$genreId,%' OR films.genres like '$genreId,%') 
            ";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));
        $row = mysqli_fetch_row($result);
        return $row[0];
    }

    public function getFilmsIdsByCategory($genreId)
    {
        $query = "SELECT films.title_id
            FROM films
            INNER JOIN ratings ON films.title_id=ratings.title_id
            WHERE (films.genres like '%,$genreId,%' OR films.genres like '$genreId,%') 
            ORDER BY ratings.votes DESC, ratings.rating DESC";

        return $this->getFilmsFromQuery($query);
    }

    public function getFilmsAmountInSearch($param)
    {
        $query = "SELECT COUNT(*)
                FROM films
                INNER JOIN films_translated on films_translated.title_id=films.title_id
                WHERE films_translated.lang_id=3 AND films_translated.title like '%$param%'
            ";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));
        $row = mysqli_fetch_row($result);
        return $row[0];
    }

    public function getFilmsIdsBySearch($param)
    {
        $query = "SELECT films.title_id
            FROM films
            INNER JOIN ratings ON films.title_id=ratings.title_id
             INNER JOIN films_translated on films_translated.title_id=films.title_id
            WHERE films_translated.lang_id=3 AND films_translated.title like '%$param%'
            ORDER BY ratings.votes DESC, ratings.rating DESC";

        return $this->getFilmsFromQuery($query);
    }

    public function getActorById($actorId)
    {
        $query = "SELECT *
            FROM people
            WHERE people.person_id = '$actorId'";
        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $row = mysqli_fetch_row($result);
        return new Actor($row[0], $row[1], $row[2], $row[3], "", "");
    }

    private function getFilmsFromQuery($query)
    {
        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $filmsIDs = null;
        for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
            $row = mysqli_fetch_row($result);
            $filmsIDs[] = $row[0];
        }

        return $filmsIDs;
    }

    public function getFilmsByPersonId($personId)
    {
        $query = "SELECT DISTINCT crew.title_id
            FROM crew
            INNER JOIN ratings on ratings.title_id = crew.title_id 
            WHERE crew.person_id = '$personId'
            ORDER BY ratings.rating DESC";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $films = null;
        for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
            $row = mysqli_fetch_row($result);
            $query = "SELECT films.title_id, films_translated.title, films.premiered, films.genres
            FROM films INNER JOIN films_Translated ON films.title_id=films_Translated.title_id 
            INNER JOIN ratings ON films.title_id=ratings.title_id
            WHERE films_Translated.lang_id = 3 and films.title_id = '$row[0]'";
            $films[] = $this->getShortFilmsFromQuery($query)[0];
        }

        return $films;
    }

    public function getRoleByPersonAndTitleId($personId, $filmId)
    {
        $query = "SELECT crew.category 
            FROM crew 
            WHERE crew.person_id = '$personId' and crew.title_id = '$filmId'";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $roles = null;
        for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
            $row = mysqli_fetch_row($result);
            $roles[] = $row[0];
        }

        return $roles;
    }

    public function registerUser($username, $password, $email)
    {
        $getQuery = "SELECT * 
                FROM users
                WHERE username='$username'";

        $result = mysqli_query($this->connection, $getQuery) or die("Ошибка " . mysqli_error($this->connection));

        if (mysqli_num_rows($result) == 0) {
            $insertQuery = "INSERT INTO users
              (username, password, email)
                VALUES('$username', '$password', '$email')";

            $result = mysqli_query($this->connection, $insertQuery) or die("Ошибка " . mysqli_error($this->connection));
            if (!$result) $error = "Запрос не выполнен. Повторите позже.";
        } else {
            $error = "Такой пользователь уже существует!";
        }

        return $error;
    }

    public function authUser($username, $password)
    {
        $getQuery = "SELECT * 
                FROM users
                WHERE username='$username' AND password='$password'";

        $result = mysqli_query($this->connection, $getQuery) or die("Ошибка " . mysqli_error($this->connection));

        if (mysqli_num_rows($result) == 0) {
            $error = "Пользователь не найден!";
        }

        return $error;
    }

    private function getUserId($username)
    {
        $getQuery = "SELECT users.userId
                FROM users
                WHERE users.username='$username'";

        $result = mysqli_query($this->connection, $getQuery) or die("Ошибка " . mysqli_error($this->connection));

        $row = mysqli_fetch_row($result);
        return $row[0];
    }

    public function addComment($comment, $filmId, $user, $time)
    {
        $userId = $this->getUserId($user);
        $insertQuery = "INSERT INTO films_comments
              (titleId, userId, time, comment)
                VALUES('$filmId', '$userId', FROM_UNIXTIME($time), '$comment')";

        $result = mysqli_query($this->connection, $insertQuery) or die("Ошибка " . mysqli_error($this->connection));
        if (!$result) $error = "Ошибка добавления";
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
    public function getUsernameByUserId($userId){
        $getQuery = "SELECT users.username
                FROM users
                WHERE users.userId='$userId'";

        $result = mysqli_query($this->connection, $getQuery) or die("Ошибка " . mysqli_error($this->connection));

        $row = mysqli_fetch_row($result);
        return $row[0];
    }
}