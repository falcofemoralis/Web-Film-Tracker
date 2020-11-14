<!DOCTYPE html>
<html lang="en">

<?php
require_once 'scripts/php/Objects/Actor.php';
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
    <link rel="icon" href="./images/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel='stylesheet' href="./CSS//film.css">
    <link rel='stylesheet' href="./CSS//elements.css">
    <link rel='stylesheet' href="./CSS//slider.css">
    <script src="./scripts/js/slider.js"></script>
</head>

<body>

<?php
include('include/header.php');
?>

<article class="page">
    <div class="container">
        <section class="film-container">
            <?php
            $databaseManager = new DatabaseManager();
            $objectHelper = new ObjectHelper();

            $filmId = $_GET["id"];
            $film = $databaseManager->getFilmByFilmId($filmId, false);
            $title = $film->getTitle();
            $rating = $film->getRating();
            $votes = $film->getVotes();
            $plot = $film->getPlot();
            $year = $film->getPremiered();
            $runtime_minutes = $film->getRuntimeMinutes();
            $isAdult = $film->getIsAdult();

            $allActors = $databaseManager->getActorsByFilmId($filmId);
            $actors = array();
            $directors = array();
            $producers = array();
            $writers = array();

            for ($i = 0; $i < count($allActors); ++$i) {
                $actor = $allActors[$i];
                $category = $actor->getCategory();

                switch ($category) {
                    case "director":
                        $directors[] = $actor;
                        break;
                    case "producer":
                        $producers[] = $actor;
                        break;
                    case "writer":
                        $writers[] = $actor;
                        break;
                    default:
                        $actors[] = $actor;
                }
            }
            ?>

            <h1 class='film__title'><? echo "$title" ?></h1>
            <div class='film-main'>
                <img class='film-main__poster' src='./images/posters/<? echo "$filmId" ?>.jpeg' alt='poster'>
                <div class='film-main__info'>
                    <table>
                        <tr>
                            <td>
                                <B>Рейтинг:</B>
                            </td>
                            <td>
                                <? echo "<a class='film-main__info-rating' href='https://www.imdb.com/title/$filmId/'>IMDb<a>:&nbsp<b>$rating</b> ($votes)" ?>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <b>Дата&nbspвыхода:</b>
                            </td>
                            <td>
                                <? echo "$year год" ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Время:</b>
                            </td>
                            <td>
                                <? echo "$runtime_minutes мин." ?>
                            </td>
                        </tr>
                        <tr>
                            <?
                            if ($isAdult !== "") {
                                echo "
                             <td>
                                 <b>Возраст:</b>
                             </td>
                             <td>
                                 $isAdult
                             </td>";
                            }
                            ?>
                        </tr>
                        <tr>
                            <td>
                                <b>Жанр:</b>
                            </td>
                            <td>
                                <? $genres = $film->getGenres();
                                for ($i = 0; $i < count($genres) - 1; $i++) {
                                    $genre = $databaseManager->getGenreById($genres[$i]);
                                    echo "$genre";

                                    if ($i != count($genres) - 2) echo ", ";
                                } ?>
                            </td>
                        </tr>
                        <tr>
                            <?
                            $size = count($directors);
                            if ($size > 0) {
                                echo "  
                            <td>
                                <b>Режиссер:</b>
                            </td>
                            <td>";
                                for ($i = 0; $i < $size; $i++) {
                                    $name = $directors[$i]->getName();
                                    echo "$name";
                                    if ($i != $size - 1) echo ", ";
                                }
                                echo "</td>";
                            }
                            ?>
                        </tr>
                        <tr>
                            <?
                            $size = count($producers);
                            if ($size > 0) {
                                echo "  
                            <td>
                                <b>Продюсер:</b>
                            </td>
                            <td>";
                                for ($i = 0; $i < $size; $i++) {
                                    $name = $producers[$i]->getName();
                                    echo "$name";
                                    if ($i != $size - 1) echo ", ";
                                }
                                echo "</td>";
                            }
                            ?>
                        </tr>
                        <tr>
                            <?
                            $size = count($writers);
                            if ($size > 0) {
                                echo "  
                            <td>
                                <b>Сценарист:</b>
                            </td>
                            <td>";
                                for ($i = 0; $i < $size; $i++) {
                                    $name = $writers[$i]->getName();
                                    echo "$name";
                                    if ($i != $size - 1) echo ", ";
                                }
                                echo "</td>";
                            }
                            ?>
                        </tr>
                    </table>
                </div>
            </div>
            <div class='film__plot'>
                <h2 class='section__title'>Cюжет фильма</h2>
                <? echo "$plot" ?>
            </div>
        </section>

        <section>
            <h2 class="section__title">Актеры в фильме</h2>
            <div class='slider' style="justify-content:  flex-start;">
                <button class='slider__button' onclick="plusSlides(-1)">&#10094;</button>
                <div class="slider__container">
                    <?php
                    $size = 5;
                    $pages = 1;

                    $all = count($actors);
                    if ($all < $size) $size = $all;
                    else $pages = $all / $size;

                    for ($i = 1; $i <= count($actors); ++$i) {
                        $actor = $actors[$i - 1];
                        $objectHelper->createActor($actor->getPersonId(), $actor->getName(), $actor->getCharacters(), $actor->getCategory());
                    }
                    ?>
                </div>
                <button class='slider__button' onclick="plusSlides(1)">&#10095;</button>
            </div>
        </section>
    </div>
</article>
<script>sliderInit("actor")</script>
<?php
include('include/footer.php');
?>
</body>
</html>