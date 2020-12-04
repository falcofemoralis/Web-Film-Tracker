<?php

require_once 'scripts/php/Managers/DatabaseManager.php';
require_once 'api/Api.php';

class ListApi extends Api
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
        $databaseManager = new DatabaseManager();
        $filers = array("genre", "country", "director", "from", "to", "min", "sort");
        // Получаем данные из гет запроса

        $cur_page = $_GET["page"]; //текущая страницы

        // необходимые переменные для списка
        $filmsIDs = null; // Фильмы которые будут отображаться
        $filmsHeader = null; // Заголовок списка
        $link = null; // Ссылка на след страницу

        switch (array_shift($this->requestUri)) {
            case "filter":
                $where = array();
                $order = " ratings.votes DESC, ratings.rating DESC ";
                $link = "filter?";
                $linkAttributes = array();

                for ($i = 0; $i < count($filers); ++$i) {
                    $data = $_GET[$filers[$i]];

                    if ($data != null) {
                        switch ($filers[$i]) {
                            case $filers[0]:
                                $genreObj = $databaseManager->getGenreByName($data);
                                $genreId = $genreObj->getGenreId();
                                $where[] = " (films.genres like '%,$genreId,%' OR films.genres like '$genreId,%') ";
                                $filmsHeader = "Фильмы жанра " . $genreObj->getGenre();
                                break;
                            case $filers[1]:
                                $where[] = " films.country_id = '$data' ";
                                $country = $databaseManager->getCountryById($data);
                                $filmsHeader = "Фильмы из страны " . $country->getCountry();
                                break;
                            case $filers[3]:
                                $where[] = "  films.premiered >= $data ";
                                break;
                            case $filers[4]:
                                $where[] = "  films.premiered <= $data ";
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

                $filmsIDs = $databaseManager->getFilmsByFilters($where, $order);
                break;
            case "search":
                $searchParam = $_GET["search"]; //аргумент поиска
                $filmsIDs = $databaseManager->getFilmsIdsBySearch($searchParam);
                $filmsHeader = "Результаты поиска «" . $searchParam . "»";
                $link = "search?search=" . $searchParam;
                break;
        }

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

