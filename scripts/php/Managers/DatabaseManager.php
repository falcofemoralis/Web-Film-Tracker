<?php
require_once 'scripts/php/Managers/FilmsHelper.php';
require_once 'scripts/php/Objects/Film.php';

class DatabaseManager
{
    public $connection, $genresArray;
    public string $FILM_SELECT_QUERY = 'SELECT * 
            FROM films INNER JOIN films_Translated ON films.title_id=films_Translated.title_id 
            INNER JOIN ratings ON films.title_id=ratings.title_id';

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
        $query = "SELECT genres.genre FROM genres WHERE lang_id = 3;";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));
        if ($result)
            return $result;
    }

    public function getGenreById($genreId)
    {
        if ($this->genresArray == null)
            $this->loadGenres();

        return $this->genresArray[$genreId - 1];
    }

    private function loadGenres()
    {
        $query = "SELECT genres.genre, genres.genre_id FROM genres WHERE genres.lang_id = 3";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));
        if ($result) {

            for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
                $row = mysqli_fetch_row($result);
                $this->genresArray[$row[1] - 1] = $row[0];
            }
        }
    }

    //Популярные фильмы
    public function getPopularFilms($limit)
    {
        $query = $this->FILM_SELECT_QUERY . " WHERE films_Translated.lang_id = 3 AND ratings.rating > 6.6 AND ratings.votes > 40000 AND films.premiered = 2020 
                        ORDER BY ratings.votes DESC LIMIT $limit";
        return $this->getFilmsFromQuery($query);
    }

    //фильмы 2020 года
    public function getFilmsByYear($year, $limit)
    {
        $query = $this->FILM_SELECT_QUERY . " WHERE films_Translated.lang_id = 3 AND premiered=$year limit $limit";
        return $this->getFilmsFromQuery($query);
    }

    private function getFilmsFromQuery($query)
    {
        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $films = null;
        for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
            $row = mysqli_fetch_row($result);
            $films[$i] = new Film($row[0], $row[6], $row[1], $row[2], $row[3], $row[4], $row[7], $row[10], $row[11]);
        }

        return $films;
    }

}