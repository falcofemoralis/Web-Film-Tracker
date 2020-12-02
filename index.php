<?php

require_once 'api/Api.php';
require_once 'scripts/php/Managers/DatabaseManager.php';

$requestUri = explode('/', stristr($_SERVER['REQUEST_URI'] . '?', '?', true));
array_shift($requestUri); //т.к 1 элемент пустой, поэтому сдигаем

$arg = array_shift($requestUri);
if (!empty ($arg)) {
    switch ($arg) {
        case "comments":
            include 'api/comments/CommentsApi.php';
            $commentsApi = new CommentsApi($requestUri);
            $commentsApi->run();
            break;
        case "bookmarks":
            include 'api/bookmarks/BookmarksApi.php';
            $bookmarksApi = new BookmarksApi($requestUri);
            $bookmarksApi->run();
            break;
        case "auth":
            include 'api/auth/AuthApi.php';
            $authApi = new AuthApi($requestUri);
            $authApi->run();
            break;
        case "random":
            $databaseManager = new DatabaseManager();
            $films = $databaseManager->getFilmsIdsBySearch("");

            $filmKey = array_rand($films, 1);
            $filmId = $films[$filmKey];
            $url = "location: film?id=$filmId";
            header($url);
            break;
        case "list":
            // Получаем данные из гет запроса
            $cur_page = $_GET["page"]; //текущая страницы
            $genreName = $_GET["type"]; //имя жанра
            $searchParam = $_GET["search"]; //аргумент поиска

            $filers = array("genre", "country", "director", "from", "to", "sort");
            $databaseManager = new DatabaseManager();

            // Заполняем необходимые переменные для списка
            $filmsIDs = null; // Фильмы которые будут отображаться
            $filmsHeader = null; // Заголовок списка
            $link = null; // Ссылка на след страницу

            if ($genreName != null) {
                $genre = $databaseManager->getGenreByName($genreName); //жанр
                $filmsIDs = $databaseManager->getFilmsIdsByCategory($genre[0]);
                $filmsHeader = "Фильмы жанра " . $genre[1];
                $link = "list?type=" . $genreName;
            } else if ($searchParam != null) {
                $filmsIDs = $databaseManager->getFilmsIdsBySearch($searchParam);
                $filmsHeader = "Результаты поиска «" . $searchParam . "»";
                $link = "list?search=" . $searchParam;
            } else {
                $where = array();
                $order = null;

                for ($i = 0; $i < count($filers); ++$i) {
                    $data = $_GET[$filers[$i]];

                    if ($data != null) {
                        switch ($filers[$i]) {
                            case $filers[0]:
                                $genreId = $databaseManager->getGenreByName($data)[0];
                                $where[] = " (films.genres like '%,$genreId,%' OR films.genres like '$genreId,%') ";
                                break;
                            case $filers[1]:
                                $where[] = " films.country = '$data' ";
                                break;
                            case $filers[3]:
                                $where[] = "  films.premiered >= $data ";
                                break;
                            case $filers[4]:
                                $where[] = "  films.premiered <= $data ";
                                break;
                            case $filers[5]:
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
                                break;
                        }
                    }
                }

                $filmsIDs = $databaseManager->getFilmsByFilters($where, $order);
                $filmsHeader = "Результаты поиска по установленым фильтрам";
            }

            if ($cur_page == null) $cur_page = 1; // Если страница не пришла, то это 1 страница
            if ($filmsIDs != null) $filmsAmount = count($filmsIDs); // Кол-во фильмов, для установки страниц в списке

            include("include/list.php");
            break;
        case "exit":
            setcookie("username", "", time() - 3600 * 24 * 365);
            header('location: /');
            break;
        default:
            include("include/" . $arg . ".php");
    }
} else {
    include('include/main.php');
}
