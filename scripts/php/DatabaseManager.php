<?php
require_once 'scripts/php/FilmsHelper.php';
require_once 'scripts/php/objects/Film.php';

//singelton
final class DatabaseManager
{
    private static $instance = null;
    public $connection;

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new DatabaseManager();
        }

        return static::$instance;
    }

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


    function getGenres()
    {
        $query = "SELECT genres.genre FROM genres;";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));
        if ($result) {
            return $result;
        }
    }

    function getPopularFilms()
    {
        $query = "SELECT * 
        FROM titles INNER JOIN ratings ON titles.title_id=ratings.title_id 
        WHERE ratings.rating > 7 AND ratings.votes > 5000 AND titles.premiered = 2020 
        ORDER BY ratings.votes DESC LIMIT 10";

        //Популярные фильмы
        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));
        if ($result) {
            for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
                $row = mysqli_fetch_row($result);
                $films[$i] = new Film($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8]);
            }

            return $films;
        }
    }

    function getFilmsByGenre($genre)
    {
        //фильмы 2020 года
        $query = "SELECT * FROM titles where genre=$genre limit 15";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));
        if ($result) {
            return $result;
        }
    }

    function getFilmsByYear($year)
    {
        //фильмы 2020 года
        $query = "SELECT * FROM titles where premiered=$year limit 15";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));
        if ($result) {
            return $result;
        }
    }
}