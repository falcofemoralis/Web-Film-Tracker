<?php
require_once 'scripts/php/Objects/Film.php';
require_once 'scripts/php/Managers/DatabaseManager.php';
require_once 'scripts/php/Managers/FilmsHelper.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Трекер фильмов</title>
    <link rel='stylesheet' href="./CSS//main.css">
    <link rel='stylesheet' href="./CSS//elements.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<body style="background-color: #efefef;">
<?php
include('include/header.php');
?>

<article style="margin: 25px;">
    <div class="container">
        <?php
        $databaseManager = new DatabaseManager();
        $filmHelper = new FilmsHelper();

        //Блок популярных фильмов
        $popularFilms = $databaseManager->getPopularFilms();

        echo "<p class='films-row__header'>Популярные фильмы</p>
                  <div class='films-row__popular'>";

        for ($i = 0; $i < count($popularFilms); ++$i) {
            echo "<div class='films-row__film'>";
            $filmHelper->createFilm($popularFilms[$i]->getFilmId(), $popularFilms[$i]->getTitle(), $popularFilms[$i]->getPremiered(), $popularFilms[$i]->getGenres());
            echo "</div>";
        }
        echo "</div>";


        //Список 2020 года
        $films2020 = $databaseManager->getFilmsByYear(2020);

        echo "<p class='films-row__header'>Фильмы 2020 года</p>
                        <table class='films-table'><tr>";

        for ($i = 1; $i <= count($films2020); ++$i) {
            echo "<td>";
            $film = $films2020[$i-1];
            $filmHelper->createFilm($film->getFilmId(), $film->getTitle(), $film->getPremiered(), $film->getGenres());
            echo "</td>";
            if (($i % 4) == 0) echo "<tr>";
        }
        echo "</table>";

        ?>
    </div>

</article>
<?php
include('include/footer.php');
?>
</body>

</html>