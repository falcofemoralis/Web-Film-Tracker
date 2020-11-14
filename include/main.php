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
    <meta name="author" content="Владислав Иващенко Романович">
    <title>Трекер фильмов</title>
    <link rel='stylesheet' href="./CSS//main.css">
    <link rel='stylesheet' href="./CSS//elements.css">
    <link rel='stylesheet' href="./CSS//slider.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="icon" href="./images/favicon.ico">
    <script src="./scripts/js/slider.js"></script>
</head>

<body style="background-color: #efefef;">

<?php
include('include/header.php');

$databaseManager = new DatabaseManager();
$objectHelper = new ObjectHelper();
?>

<article class="page">
    <div class="container">
        <section>
            <h2 class='text__header'>Популярные фильмы</h2>
            <div class='slider'>
                <button class='slider__button' onclick="plusSlides(-1)">&#10094;</button>
                <div class="slider__container">
                    <?php
                    //Блок популярных фильмов
                    $popularFilms = $databaseManager->getPopularFilms(12);
                    for ($i = 1; $i <= count($popularFilms); ++$i) {
                        $film = $popularFilms[$i - 1];
                        $objectHelper->createFilm($film->getFilmId(), $film->getTitle(), $film->getPremiered(), $film->getGenres());
                    }
                    ?>
                </div>
                <button class='slider__button' onclick="plusSlides(1)">&#10095;</button>
            </div>
        </section>

        <section><h2 class='text__header'>Фильмы 2020 года</h2>
            <div class='films-table'>
                <?php
                //Список 2020 года
                $films2020 = $databaseManager->getFilmsByYear(2020, 15);
                for ($i = 0; $i < count($films2020); ++$i) {
                    $film = $films2020[$i];
                    $objectHelper->createFilm($film->getFilmId(), $film->getTitle(), $film->getPremiered(), $film->getGenres());
                }
                ?>
        </section>
    </div>
</article>
<script>sliderInit("film")</script>
<?php
include('include/footer.php');
?>
</body>

</html>