<?php
require_once 'scripts/php/Managers/ObjectHelper.php';
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
            WHERE films_Translated.lang_id = 3 AND ratings.rating > 6.6 AND ratings.votes > 40000 AND films.premiered = 2020 
            ORDER BY ratings.votes DESC LIMIT $limit";

        return $this->getShortFilmFromQuery($query);
    }

    //фильмы 2020 года
    public function getFilmsByYear($year, $limit)
    {
        $query = "SELECT films.title_id, films_translated.title, films.premiered, films.genres
            FROM films INNER JOIN films_Translated ON films.title_id=films_Translated.title_id 
            INNER JOIN ratings ON films.title_id=ratings.title_id
            WHERE films_Translated.lang_id = 3 AND premiered=$year limit $limit";

        return $this->getShortFilmFromQuery($query);
    }

    public function getFilmByFilmId($id, $isShort)
    {
        $queryParam = "WHERE films_Translated.lang_id = 3 AND films.title_id = '$id'";

        $film = null;
        if ($isShort) {
            $query = "SELECT films.title_id, films_translated.title, films.premiered, films.genres
             FROM films INNER JOIN films_Translated ON films.title_id=films_Translated.title_id " . $queryParam;
            $film = $this->getShortFilmFromQuery($query);
        } else {
            $query = "SELECT films.title_id, films_translated.title, films.is_adult, films.premiered,
            films.runtime_minutes, films.genres, films_translated.plot, ratings.rating, ratings.votes
            FROM films INNER JOIN films_Translated ON films.title_id=films_Translated.title_id 
            INNER JOIN ratings ON films.title_id=ratings.title_id " . $queryParam;
            $film = $this->getLongFilmFromQuery($query);
        }


        return $film[0];
    }

    //получение короткой информации про фильм
    private function getShortFilmFromQuery($query)
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
    private function getLongFilmFromQuery($query)
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
        $query = "SELECT people.person_id, people.name, people.born, people.died, crew.characters, crew.category 
            FROM crew INNER JOIN people ON people.person_id = crew.person_id 
            WHERE crew.title_id = '$filmId'";
        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $actors = null;
        for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
            $row = mysqli_fetch_row($result);
            if ($row[3] == null) $died = 0;
            else $died = $row[3];

            if ($row[2] == null) $born = 0;
            else $born = $row[2];

            $actors[$i] = new Actor($row[0], $row[1], $born, $died, $row[4], $row[5]);
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

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $filmsIDs = null;
        for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
            $row = mysqli_fetch_row($result);
            $filmsIDs[] = $row[0];
        }

        return $filmsIDs;
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

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $filmsIDs = null;
        for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
            $row = mysqli_fetch_row($result);
            $filmsIDs[] = $row[0];
        }

        return $filmsIDs;
    }
}