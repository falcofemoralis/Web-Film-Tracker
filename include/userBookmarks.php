<!DOCTYPE html>
<html lang="en">

<?php
require_once 'scripts/php/Objects/Film.php';
require_once 'scripts/php/Managers/DatabaseManager.php';
require_once 'scripts/php/Managers/ObjectHelper.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Сайт поиска информации про фильмы">
    <meta name="author" content="Иващенко Владислав Романович">
    <title>Трекер фильмов</title>
    <link rel='stylesheet' href="/CSS/elements.css">
    <link rel='stylesheet' href="/CSS/bookmarks.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="icon" href="/images/favicon.ico">
</head>

<body>
<?php

include('include/header.php');

$databaseManager = new DatabaseManager();
$objectHelper = new ObjectHelper();

$userId = $databaseManager->getUserId($_COOKIE['username']);
$films = $databaseManager->getUserBookmarks($userId);

?>

<article>
    <div class="container">
        <div>
            <h2 class='text__header'>Закладки</h2>
            <div class="films-table">
                <div class="films-container" style="width: calc(((156px * 4) + (6px * 4 * 2)));">
                    <?php
                    for ($i = 0; $i < count($films); ++$i) {
                        $film = $databaseManager->getFilmByFilmId($films[$i], true);

                        if ($film != null) {
                            $name = $film->getTitle();
                            $objectHelper->createFilm($film->getFilmId(), $film->getTitle(), $film->getPremiered(), $film->getGenres());
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</article>
<?php
include('include/footer.php');
?>
</body>
</html>