<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Трекер фильмов</title>
    <link rel='stylesheet' href="./CSS//main.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<body style="background-color: #efefef;">
    <?php
    require_once 'scripts/php/FilmsHelper.php';
    require_once 'scripts/php/DatabaseManager.php';

    $databaseManager = DatabaseManager::getInstance();

    include('include/header.php');
    ?>


    <article style="margin: 25px;">
        <div class="container">
            <?php
            $popularFilms = $databaseManager->getPopularFilms();

            echo "<p class='films-row__name'>Популярные фильмы</p>
                  <div class='films-row__popular'>";

            for ($i = 0; $i < count($popularFilms); ++$i) {
                echo "<div class='films-row__film'>";
                createFilm($popularFilms[$i]->getFilmId(), $popularFilms[$i]->getTitle(), $popularFilms[$i]->getPremiered(), $popularFilms[$i]->getGenres());
                echo "</div>";
            }
            echo "</div>";

            $films2020 = $databaseManager->getFilmsByYear(2020);

            echo "<p class='films-row__name'>Фильмы 2020 года</p>
                        <table class='films-table'><tr>";

            for ($i = 1; $i <= mysqli_num_rows($films2020); ++$i) {
                $row = mysqli_fetch_row($films2020);

                echo "<td>";
                createFilm($row[0], $row[1], "2020", "Приключения");
                echo "</td>";
                if (($i % 4) == 0) echo "<tr>";
            }
            echo "</table>";

            // очищаем результат
            mysqli_free_result($films2020);

            ?>
        </div>

    </article>
    <?php
    include('include/footer.php');
    ?>
</body>

</html>