<?php

class FilmsList extends Database
{
    public function __construct()
    {
        parent::__construct();
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

    public function getCountries()
    {
        $query = "SELECT * FROM countries";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $countries = array();
        for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
            $row = mysqli_fetch_row($result);
            $countries[] = new Country($row[0], $row[1]);
        }

        return $countries;
    }

    public function getFilmsIdsBySearch($param)
    {
        $query = "SELECT DISTINCT films.title_id
            FROM films
            INNER JOIN ratings ON films.title_id=ratings.title_id
            INNER JOIN films_translated on films_translated.title_id=films.title_id
            WHERE (films_translated.lang_id=3 AND (films_translated.title like '%$param%' OR films_translated.plot like '%$param%'))
            OR (films_translated.lang_id=1 AND (films_translated.title like '%$param%' OR films_translated.plot like '%$param%'))
            ORDER BY ratings.votes DESC, ratings.rating DESC";

        return $this->getFilmsFromQuery($query);
    }

    public function getYearsRange()
    {
        $query = "SELECT DISTINCT films.premiered FROM films ORDER BY films.premiered DESC;";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $years = array();
        for ($i = 0; $i < mysqli_num_rows($result); ++$i)
            $years[] = mysqli_fetch_row($result)[0];

        return $years;
    }

    public function getFilmsByFilters($where, $order)
    {
        $whereQuery = "WHERE ";
        for ($i = 0; $i < count($where); ++$i) {
            $whereQuery .= $where[$i];
            if ($i != count($where) - 1) $whereQuery .= " AND ";
        }

        if ($where != null) $whereQuery .= " AND ";
        $whereQuery .= " films_translated.lang_id = 3";

        $query = "SELECT DISTINCT films.title_id
            FROM films
            INNER JOIN ratings ON films.title_id=ratings.title_id
            INNER JOIN films_genres ON films_genres.title_id=films.title_id 
            INNER JOIN films_translated on films_translated.title_id=films.title_id " . $whereQuery . " ORDER BY " . $order;

        return $this->getFilmsFromQuery($query);
    }

/*  public function getRelevantFilms($filmId)
    {
        $filmsIDs = array();

        // Получение ключ слов в фильма
        $keywords = $this->getFilmKeywords($filmId);

        // Получаем фильмы ids за ключевым СЛОВОМ
        for ($i = 0; $i < count($keywords); ++$i) {
            $keyword = $keywords[$i];
            $getFilmIds = "SELECT DISTINCT films_keywords.title_id
                FROM films_keywords
                WHERE films_keywords.keyword = '$keyword'";

            $result = mysqli_query($this->connection, $getFilmIds) or die("Ошибка " . mysqli_error($this->connection));

            // Достаем ключ слово
            for ($j = 0; $j < mysqli_num_rows($result); ++$j) {
                $filmsID = mysqli_fetch_row($result)[0];

                // Получаем кл слова найденного фильма
                $newFilmKeywords = $this->getFilmKeywords($filmsID);

                // Находим кол-во одинаковых кл слов
                $n = 0;
                for ($k = 0; $k < count($newFilmKeywords); ++$k) {
                    if (in_array($newFilmKeywords[$k], $keywords))
                        $n++;
                }

                if ($n > 3) {
                    if (!in_array($filmsID, $filmsIDs))
                        $filmsIDs[] = $filmsID;
                }
            }
        }

        $filmsWords = array();
        $words = array();

        // Переставляем фильмы, которыя являются частью франшизы
        for ($i = 0; $i < count($filmsIDs); $i++) {
            $title = $this->getFilmTitle($filmsIDs[$i]);
            $splitWords = explode(" ", $title);
            for ($j = 0; $j < count($splitWords); ++$j) {
                $filmsWords[$filmsIDs[$i]] = $splitWords[$j];
            }
        }

        // Находим наиболее встречающее слово
        for ($i = 0; $i < count($filmsWords); $i++) {
            $words[$filmsWords[$i]]++;
        }

        arsort($words);

        for ($i = 0; $i < count($words); ++$i) {
            $filmsIDs = array_search($words[$i], $filmsWords);

            echo "founded = " . array_search($words[$i], гарри);
        }

        return $filmsIDs;
    }*/

/*    public function getFilmKeywords($filmId)
    {
        $query = "SELECT films_keywords.keyword FROM films_keywords WHERE films_keywords.title_id = '$filmId'";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));

        $keywords = array();
        for ($i = 0; $i < mysqli_num_rows($result); ++$i)
            $keywords[] = mysqli_fetch_row($result)[0];

        return $keywords;
    }

    public function getFilmTitle($id)
    {
        $query = "SELECT films_translated.title
            FROM films INNER JOIN films_translated ON films.title_id=films_translated.title_id 
            WHERE films_translated.lang_id = 3 and films.title_id = '$id'";

        $result = mysqli_query($this->connection, $query) or die("Ошибка " . mysqli_error($this->connection));
        return mysqli_fetch_row($result)[0];
    }*/
}
