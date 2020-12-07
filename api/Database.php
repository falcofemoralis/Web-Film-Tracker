<?php

class Database
{
    public $connection;

    function __construct()
    {
        $host = 'localhost'; // адрес сервера 
        $database = 'filmsdb'; // имя базы данных
        $user = 'root'; // имя пользователя
        $password = 'root'; // пароль

        $connection = mysqli_connect($host, $user, $password, $database)
        or die("Ошибка подключения к базе" . mysqli_error($connection));

        $this->connection = $connection;
    }

    // Получение всех жанров фильмов
    public function getGenres()
    {
        $query = "SELECT * FROM genres";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));
        $genres = array();
        if ($result) {
            for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
                $row = mysqli_fetch_row($result);
                $genres[$row[0] - 1] = new Genre($row[0], $row[1], $row[2]);
            }
        }

        return $genres;
    }

    // Популярные фильмы
    public function getPopularFilms($limit)
    {
        $query = "SELECT films.title_id, films_translated.title, films.premiered, films.genres
            FROM films INNER JOIN films_translated ON films.title_id=films_translated.title_id 
            INNER JOIN ratings ON films.title_id=ratings.title_id
            WHERE films_translated.lang_id = 3 AND ratings.rating > 6.5 AND ratings.votes > 40000 AND films.premiered = 2020 
            ORDER BY ratings.votes DESC LIMIT $limit";

        return $this->getShortFilmsFromQuery($query);
    }

    //фильмы year года
    public function getFilmsByYear($year, $limit)
    {
        $query = "SELECT films.title_id, films_translated.title, films.premiered, films.genres
            FROM films INNER JOIN films_translated ON films.title_id=films_translated.title_id 
            INNER JOIN ratings ON films.title_id=ratings.title_id
            WHERE films_translated.lang_id = 3 AND premiered=$year limit $limit";

        return $this->getShortFilmsFromQuery($query);
    }

    // Получение жанра по его id
    public function getGenreById($genreId)
    {
        $query = "SELECT * FROM genres WHERE genres.genre_id =" . $genreId;

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));
        $row = mysqli_fetch_row($result);
        return new Genre($row[0], $row[1], $row[2]);
    }

    // Получение жанра по его названию (action, animation и т.д)
    public function getGenreByName($genreName)
    {
        $query = "SELECT * FROM genres WHERE genres.genre_name = '$genreName'";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));
        $row = mysqli_fetch_row($result);
        return new Genre($row[0], $row[1], $row[2]);
    }

    // Получение фильма по его id
    public function getFilmByFilmId($id, $isShort)
    {
        $queryParam = "WHERE films_translated.lang_id = 3 AND films.title_id = '$id'";

        $film = null;
        if ($isShort) {
            $query = "SELECT films.title_id, films_translated.title, films.premiered, films.genres
             FROM films INNER JOIN films_translated ON films.title_id=films_translated.title_id " . $queryParam;
        } else {
            $query = "SELECT films.title_id, films_translated.title, films.is_adult, films.premiered,
            films.runtime_minutes, films.genres, films_translated.plot, ratings.rating, ratings.votes, films.trailerId, films.country_id 
            FROM films INNER JOIN films_translated ON films.title_id=films_translated.title_id 
            INNER JOIN ratings ON films.title_id=ratings.title_id " . $queryParam;
        }

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));
        $row = mysqli_fetch_row($result);

        if ($isShort) return new Film($row[0], $row[1], "", $row[2], "", $row[3], "", "", "", "", "");
        else return new Film($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10]);
    }

    //получение короткой информации про фильм
    private function getShortFilmsFromQuery($query)
    {
        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $films = null;
        for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
            $row = mysqli_fetch_row($result);
            $films[$i] = new Film($row[0], $row[1], "", $row[2], "", $row[3], "", "", "", "", "");
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

    public function getActorById($actorId)
    {
        $query = "SELECT *
            FROM people
            WHERE people.person_id = '$actorId'";
        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $row = mysqli_fetch_row($result);
        return new Actor($row[0], $row[1], $row[2], $row[3], "", "");
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
            FROM films INNER JOIN films_translated ON films.title_id=films_translated.title_id 
            INNER JOIN ratings ON films.title_id=ratings.title_id
            WHERE films_translated.lang_id = 3 and films.title_id = '$row[0]'";
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

    public function getUserId($username)
    {
        $getQuery = "SELECT users.userId
                FROM users
                WHERE users.username='$username'";

        $result = mysqli_query($this->connection, $getQuery) or die("Ошибка " . mysqli_error($this->connection));

        $row = mysqli_fetch_row($result);
        return $row[0];
    }

    public function getUserByUserId($userId)
    {
        $getQuery = "SELECT users.username, users.email, users.password
                FROM users
                WHERE users.userId='$userId'";

        $result = mysqli_query($this->connection, $getQuery) or die("Ошибка " . mysqli_error($this->connection));

        $row = mysqli_fetch_row($result);
        return new User($userId, $row[0], $row[1], $row[2]);
    }

    public function getCountryById($countryId)
    {
        $query = "SELECT * FROM countries WHERE country_id = " . $countryId;

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));
        $row = mysqli_fetch_row($result);
        return new Country($row[0], $row[1]);
    }

    public function getRating($type)
    {
        $query = "SELECT " . $type . "(ratings.rating) FROM ratings";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));
        $row = mysqli_fetch_row($result);
        return $row[0];
    }
}


