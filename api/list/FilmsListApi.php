<?php

require_once 'api/Api.php';
require_once 'api/Database.php';
require_once 'api/list/FilmsList.php';

class FilmsListApi extends Api
{
    // POST - Добавление в базу новых данных
    protected function createAction()
    {
        echo "invalid method";
    }

    // PUT - Обновление данных
    protected function updateAction()
    {
        echo "invalid method";
    }

    // GET - Просмотр данных
    protected function viewAction()
    {
        $filmsList = new FilmsList();

        // Получаем данные из гет запроса
        global $cur_page, $filmsIDs, $filmsHeader, $link;
        $cur_page = $_GET["page"]; //текущая страницы

        switch (array_shift($this->requestUri)) {
            case "filter":
                $filers = array("genre", "country", "director", "from", "to", "min", "sort");
                $where = array();
                $order = " ratings.votes DESC, ratings.rating DESC ";
                $link = "filter?";
                global $linkAttributes;

                for ($i = 0; $i < count($filers); ++$i) {
                    $data = $_GET[$filers[$i]];

                    if ($data != null && $data != "null") {
                        switch ($filers[$i]) {
                            case $filers[0]:
                                $genreObj = $filmsList->getGenreByName($data);
                                $genreId = $genreObj->getGenreId();
                                $where[] = " (films.genres like '%,$genreId,%' OR films.genres like '$genreId,%') ";
                                $filmsHeader = "Поиск фильмов жанра " . $genreObj->getGenre();
                                break;
                            case $filers[1]:
                                $where[] = " films.country_id = '$data' ";
                                $country = $filmsList->getCountryById($data);
                                $filmsHeader = "Поиск фильмов из страны " . $country->getCountry();
                                break;
                            case $filers[3]:
                                $where[] = "  films.premiered >= $data ";
                                break;
                            case $filers[4]:
                                $where[] = "  films.premiered <= $data ";
                                $filmsHeader = "Поиск фильмов за " . $data . " год";
                                break;
                            case $filers[5]:
                                $where[] = "  ratings.rating >= $data ";
                                break;
                            case $filers[6]:
                                switch ($data) {
                                    case "rating":
                                        $order = "ratings.rating DESC";
                                        break;
                                    case "year":
                                        $order = "films.premiered DESC";
                                        break;
                                    case "abc":
                                        $order = "films_translated.title";
                                        break;
                                    case "votes":
                                        $order = "ratings.votes DESC";
                                        break;
                                }
                                $filmsHeader = "Результаты поиска по установленым фильтрам";
                                break;
                        }
                        $linkAttributes[] = array($i, $data);
                    }
                }

                for ($i = 0; $i < count($linkAttributes); ++$i) {
                    $link .= ($filers[$linkAttributes[$i][0]] . "=" . $linkAttributes[$i][1]);
                    if ($i != count($linkAttributes) - 1) $link .= "&";
                }

                $filmsIDs = $filmsList->getFilmsByFilters($where, $order);
                if (array_shift($this->requestUri) == "find") {
                    if ($filmsIDs != null)
                        echo count($filmsIDs);
                    else
                        echo 0;
                    return;
                }

                break;
            case "search":
                global $searchParam;
                $searchParam = $_GET["search"]; //аргумент поиска
                $filmsIDs = $filmsList->getFilmsIdsBySearch($searchParam);
                $filmsHeader = "Результаты поиска «" . $searchParam . "»";
                $link = "search?search=" . $searchParam;
                break;
        }

        global $filmsAmount;
        if ($cur_page == null) $cur_page = 1; // Если страница не пришла, то это 1 страница
        if ($filmsIDs != null) $filmsAmount = count($filmsIDs); // Кол-во фильмов, для установки страниц в списке

        include("include/list.php");
    }

    // DELETE - Удаление данных
    protected function deleteAction()
    {
        echo "invalid method";
    }
}

