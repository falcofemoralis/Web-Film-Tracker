<!DOCTYPE html>
<html lang="en">

<?php
require_once 'scripts/php/Objects/Film.php';
require_once 'scripts/php/Managers/DatabaseManager.php';
require_once 'scripts/php/Managers/ObjectHelper.php';
require_once 'scripts/php/Managers/PageHelper.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Сайт поиска информации про фильмы">
    <meta name="author" content="Владислав Иващенко Романович">
    <title>Трекер фильмов</title>
    <link rel='stylesheet' href="./CSS//category.css">
    <link rel='stylesheet' href="./CSS//elements.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="icon" href="./images/favicon.ico">
</head>

<body>
<?php
include('include/header.php');

$databaseManager = new DatabaseManager();
$objectHelper = new ObjectHelper();

$genreName = $_GET["type"];
$cur_page = $_GET["page"]; //текущая страницы

$genre = $databaseManager->getGenreByName($genreName); //жанр
$filmsAmount = $databaseManager->getFilmsAmountInCategory($genre[0]); //кол-во фильмов в жанре (для установки макс страницы)
$filmsIDs = $databaseManager->getFilmsIdsByCategory($genre[0]); //id шники фильмов

$filmsPerPage = 35; //кол-во отображаемых фильмов на странице
$pages = intval($filmsAmount / $filmsPerPage) + 1; // кол-во страниц
?>

<article>
    <div class="container">
        <section><? echo "<h2 class='text__header'>Фильмы жанра $genre[1]</h2>"; ?>
            <div class='films-table'>
                <?php
                $films2020 = $databaseManager->getFilmsByYear(2020, 15);
                for ($i = $filmsPerPage * ($cur_page - 1); $i < $filmsPerPage * $cur_page; $i++) {
                    $film = $databaseManager->getFilmByFilmId($filmsIDs[$i], true);

                    if ($film != null) {
                        $name = $film->getTitle();
                        $objectHelper->createFilm($film->getFilmId(), $film->getTitle(), $film->getPremiered(), $film->getGenres());
                    }
                }
                ?>
        </section>

        <?
        createPagesControls($pages, $cur_page, "category?type=" . $genreName);
        ?>
    </div>
</article>

<?php
include('include/footer.php');
?>

</body>
</html>