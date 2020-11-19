<!DOCTYPE html>
<html lang="en">

<?php
require_once 'scripts/php/Objects/Film.php';
require_once 'scripts/php/Managers/DatabaseManager.php';
require_once 'scripts/php/Managers/ObjectHelper.php';
require_once 'scripts/php/Managers/PagesHelper.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Сайт поиска информации про фильмы">
    <meta name="author" content="Иващенко Владислав Романович">
    <title>Трекер фильмов</title>
    <link rel='stylesheet' href="/CSS/list.css">
    <link rel='stylesheet' href="/CSS/elements.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="icon" href="/images/favicon.ico">
</head>

<body>
<?php
include('include/header.php');

$filmsPerPage = 30; //кол-во отображаемых фильмов на странице
$isGenre = false; //является ли параметр жанром
$databaseManager = new DatabaseManager();
$objectHelper = new ObjectHelper();

$genreName = $_GET["type"]; //имя жанра
$searchParam = $_GET["search"]; //аргумент поиска
$cur_page = $_GET["page"]; //текущая страницы

if ($genreName != null) $isGenre = true;

if ($isGenre) {
    $genre = $databaseManager->getGenreByName($genreName); //жанр
    $filmsAmount = $databaseManager->getFilmsAmountInCategory($genre[0]); //кол-во фильмов в жанре (для установки макс страницы)
    $filmsIDs = $databaseManager->getFilmsIdsByCategory($genre[0]); //id шники фильмов
} else {
    if ($cur_page == null) $cur_page = 1;
    $filmsAmount = $databaseManager->getFilmsAmountInSearch($searchParam); //кол-во фильмов в жанре (для установки макс страницы)
    $filmsIDs = $databaseManager->getFilmsIdsBySearch($searchParam); //id шники фильмов
}

$pages = intval($filmsAmount / $filmsPerPage) + 1; // кол-во страниц
?>

<article>
    <div class="container">
        <div>
            <?
            if ($isGenre) echo "<h2 class='text__header'>Фильмы жанра $genre[1]</h2>";
            else echo "<h2 class='text__header'>Результаты поиска «$searchParam" . "»</h2>";
            ?>
            <div class="films-table">
                <div class="films-container">
                    <?php
                    for ($i = $filmsPerPage * ($cur_page - 1); $i < $filmsPerPage * $cur_page; $i++) {
                        $film = $databaseManager->getFilmByFilmId($filmsIDs[$i], true);

                        if ($film != null) {
                            $name = $film->getTitle();
                            $objectHelper->createFilm($film->getFilmId(), $film->getTitle(), $film->getPremiered(), $film->getGenres());
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?
        if ($filmsAmount == 0) {
            echo "По вашему запросу ничего не найдено.";
        } else {
            if ($isGenre) $link = "list?type=" . $genreName;
            else $link = "list?search=" . $searchParam;
            createPagesControls($pages, $cur_page, $link);
        }
        ?>
    </div>
</article>

<?php
include('include/footer.php');
?>

</body>
</html>