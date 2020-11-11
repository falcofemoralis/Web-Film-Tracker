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
    <title>Трекер фильмов</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel='stylesheet' href="./CSS//elements.css">
    <link rel='stylesheet' href="./CSS//film.css">
    <link rel='stylesheet' href="./CSS//slider.css">
    <script src="./scripts/js/slider.js"></script>
</head>

<body>

<?php
include('include/header.php');
?>

<article style="padding: 25px;">
    <div class="container">
        <section class="film-container">
            <?php
            $databaseManager = new DatabaseManager();
            $objectHelper = new ObjectHelper();

            $filmId = $_GET["id"];
            $film = $databaseManager->getFilmByFilmId($filmId);
            $title = $film->getTitle();
            $rating = $film->getRating();
            $votes = $film->getVotes();
            $plot = $film->getPlot();
            $year = $film->getPremiered();
            $runtime_minutes = $film->getRuntimeMinutes();
            $isAdult = $film->getIsAdult();

            if ($isAdult == 1) $isAdult = "18+";
            else $isAdult = "0+";

            $allActors = $databaseManager->getActorsByFilmId($filmId);
            $actorsInx = null;
            $directorsInx = null;
            $producersInx = null;
            $writersInx = null;

            $j = 0;
            $d = 0;
            $p = 0;
            $w = 0;
            for ($i = 0; $i < count($allActors); ++$i) {
                $category = $allActors[$i]->getCategory();

                switch ($category) {
                    case "director":
                        $directorsInx[$d] = $i;
                        $d++;
                        break;
                    case "producer":
                        $producersInx[$p] = $i;
                        $p++;
                        break;
                    case "writer":
                        $writersInx[$w] = $i;
                        $w++;
                        break;
                    default:
                        $actorsInx[$j] = $i;
                        $j ++;
                }
            }

            echo "
        <h1 class='film__title'>$title</h1>
        <div class='film-main'>
            <img class='film-main__poster' src='./images/posters/$filmId.jpeg' alt='poster'>
            <div class='film-main__info'>
                 <ul>
                     <li><B>Рейтинг:</B></li>
                     <li><b>Дата&nbspвыхода:</b></li>
                     <li><b>Время:</b></li> 
                     <li><b>Возраст:</b></li>
                     <li><b>Жанр:</b></li>
                     <li><b>Режиссер:</b></li>
                     <li><b>Продюсер:</b></li>
                     <li><b>Сценарист:</b></li>
                 </ul>
                  <ul>
                     <li><a class='film-main__info-rating' href='https://www.imdb.com/title/$filmId/'>IMDb<a>: <b>$rating</b> ($votes)</li>
                     <li>$year</li>
                     <li>$runtime_minutes мин.</li>
                     <li>$isAdult</li><li>";

            $genres = $film->getGenres();
            for ($i = 0; $i < count($genres) - 1; $i++) {
                $genre = $databaseManager->getGenreById($genres[$i]);
                echo "$genre";

                if ($i != count($genres) - 2) echo ", ";
            }

            echo "</li><li>";

            for ($i = 0; $i < count($directorsInx); $i++) {
                $actor = $allActors[$directorsInx[$i]];
                $name = $actor->getName();
                echo "$name";
                if ($i != count($directorsInx) - 1) echo ", ";
            }

            echo "</li><li>";
            for ($i = 0; $i < count($producersInx); $i++) {
                $actor = $allActors[$producersInx[$i]];
                $name = $actor->getName();
                echo "$name";
                if ($i != count($producersInx) - 1) echo ", ";
            }

            echo "</li><li>";
            for ($i = 0; $i < count($writersInx); $i++) {
                $actor = $allActors[$writersInx[$i]];
                $name = $actor->getName();
                echo "$name";
                if ($i != count($writersInx) - 1) echo ", ";
            }
            echo "        </li>
                 </ul>
            </div>
        </div>
        <div class='film__plot'>
            <h2 class='section__title'>Cюжет фильма</h2>
            $plot
        </div>"; ?>
        </section>

        <section>
            <h2 class="section__title">Актеры в фильме</h2>
            <div class='slider'>
                <button class='slider__button' onclick="plusSlides(-1)">&#10094;</button>
                <div class="slider__container">
                    <?php
                    $size = 5;
                    $pages = 1;

                    $all = count($actorsInx);
                    if ($all < $size) $size = $all;
                    else $pages = $all / $size;

                    for ($i = 1; $i <= count($actorsInx); ++$i) {
                        if (($i - 1) % $size == 0) echo "<div class='slider__item'>";
                        $actor = $allActors[$actorsInx[$i - 1]];

                        $objectHelper->createActor($actor->getPersonId(), $actor->getName(), $actor->getCharacters(), $actor->getCategory());
                        if ($i % $size == 0 || $i == count($actorsInx)) echo "</div>";
                    }
                    ?>
                </div>
                <button class='slider__button' onclick="plusSlides(1)">&#10095;</button>
            </div>
        </section>
    </div>
</article>
<script>sliderInit()</script>
<?php
include('include/footer.php');
?>
</body>
</html>