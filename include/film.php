<!DOCTYPE html>
<html lang="en">

<?php
require_once 'scripts/php/Objects/Actor.php';
require_once 'scripts/php/Objects/Film.php';
require_once 'scripts/php/Managers/DatabaseManager.php';
require_once 'scripts/php/Managers/ObjectHelper.php';
require_once 'scripts/php/Objects/Comment.php';
require_once 'scripts/php/Objects/User.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Сайт поиска информации про фильмы">
    <meta name="author" content="Иващенко Владислав Романович">
    <title>Трекер фильмов</title>
    <link rel="icon" href="/images/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel='stylesheet' href="/CSS/film.css">
    <link rel='stylesheet' href="/CSS/elements.css">
    <link rel='stylesheet' href="/CSS/slider.css">
</head>

<body>

<?php
include('include/header.php');

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
$sortedActors = array(array());
$sortedActorsHeaders = array("Актер", " Режиссер", "Продюсер", "Сценарист");

for ($i = 0; $i < count($allActors); ++$i) {
    $actor = $allActors[$i];
    $category = $actor->getCategory();

    switch ($category) {
        case "director":
            $sortedActors[1][] = $actor;
            break;
        case "producer":
            $sortedActors[2][] = $actor;
            break;
        case "writer":
            $sortedActors[3][] = $actor;
            break;
        default:
            $sortedActors[0][] = $actor;
    }
}

?>

<article class="page">
    <div class="container">
        <div class="film-container">
            <div class="film__title-row">
                <h1 class='film__title'><? echo "$title" ?></h1>
                <?

                ?>
            </div>
            <div class='film-main'>
                <div class="film-main__poster-cont">
                    <? echo "<img class='film-main__poster' src='/images/posters/$filmId.jpeg' alt='$title'>";
                    if (isset($_COOKIE['username'])) {
                        $isBookmarked = $databaseManager->getIsBookmarked($filmId);
                        $userId = $databaseManager->getUserId($_COOKIE['username']);

                        if ($isBookmarked) echo "<div class='bookmark_btn-cont'>
                                    <a class='bookmark__btn' href='unbookmark?filmId=$filmId&userId=$userId'>
                                       <img class='bookmark__btn-link' src='/images/unbookmark.svg' alt='unbookmark'>
                                    </a>
                                </div>";
                        else echo "<div class='bookmark_btn-cont'>
                                    <a class='bookmark__btn' href='bookmark?filmId=$filmId&userId=$userId'>
                                         <img class='bookmark__btn-link' src='/images/bookmark.svg' alt='bookmark'>
                                    </a>
                                </div>";
                    } ?>
                </div>
                <div id="hover-image" class="poster-hover__content">
                    <span class="close">×</span>
                    <? echo "<img class='poster-hover__image' src='/images/posters/$filmId.jpeg' alt='$title'>" ?>
                    <div class="caption"><? echo "$title" ?> </div>
                </div>

                <div class=' film-main__info'>
                    <table>
                        <tr>
                            <td><B>Рейтинг:</B></td>
                            <td><? echo "<a class='film-main__info-rating' href='https://www.imdb.com/title/$filmId/'>IMDb<a>:&nbsp<b>$rating</b> ($votes)" ?></td>
                        </tr>

                        <tr>
                            <td><b>Дата&nbspвыхода:</b></td>
                            <td><? echo "$year год" ?></td>
                        </tr>
                        <tr>
                            <td><b>Время:</b></td>
                            <td><? echo "$runtime_minutes мин." ?></td>
                        </tr>
                        <tr>
                            <?
                            if ($isAdult !== "") {
                                echo "
                             <td><b>Возраст:</b></td>
                             <td>$isAdult</td>";
                            }
                            ?>
                        </tr>
                        <tr>
                            <td><b>Жанр:</b></td>
                            <td>
                                <? $genres = $film->getGenres();
                                for ($i = 0; $i < count($genres) - 1; $i++) {
                                    $genre = $databaseManager->getGenreById($genres[$i]);
                                    $genreName = $genre[0];
                                    $genreId = $genre[1];

                                    echo "<a class='actor-link' href='list?type=$genreId&page=1'>$genreName</a>";

                                    if ($i != count($genres) - 2) echo ", ";
                                } ?>
                            </td>
                        </tr>

                        <?
                        for ($i = 1; $i < 4; $i++) {
                            if ($sortedActors[$i] != null) {
                                $title = $sortedActorsHeaders[$i];
                                echo "<tr><td><b>$title</b></td><td>";

                                $size = count($sortedActors[$i]);
                                for ($j = 0; $j < $size; $j++) {
                                    $name = $sortedActors[$i][$j]->getName();
                                    $id = $sortedActors[$i][$j]->getPersonId();

                                    echo "<a class='actor-link' href='actors?id=$id'>$name</a>";
                                    if ($j != $size - 1) echo ", ";
                                }

                                echo "</td></tr>";
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
            <div class='film__plot'>
                <h2 class='section__title'>Cюжет фильма</h2>
                <? echo "$plot" ?>
            </div>
        </div>

        <div>
            <h2 class="section__title">Актеры в фильме</h2>
            <div class='slider' style="justify-content:  flex-start;">
                <div class="slider__container" style="overflow: scroll">
                    <?php
                    $size = 5;
                    $pages = 1;

                    if ($sortedActors[0] != null) {
                        $all = count($sortedActors[0]);
                        if ($all < $size) $size = $all;
                        else $pages = $all / $size;

                        for ($i = 1; $i <= count($sortedActors[0]); ++$i) {
                            $actor = $sortedActors[0][$i - 1];
                            $objectHelper->createActor($actor->getPersonId(), $actor->getName(), $actor->getCharacters(), $actor->getCategory());
                        }
                    }

                    ?>
                </div>
            </div>
        </div>

        <?php
        if (isset($_COOKIE['username'])) {
            echo "<form action='addComment' method='post'>
                    <textarea id='comment' class='comment-input' name='comment' onchange='checkText()' placeholder='Написать комментарий'></textarea>
                    <div id='error' class='error-hint'>Введите текст!</div>
                    <button disabled='true' id='add' class='add-btn'>Добавить</button>
                    <input name='filmId' value='$filmId' style='display: none'/> 
                </form>";
        } else {
            echo "<div class='comment-input' style='color: orangered; font-weight: bold; height: auto;'>Зарегестрируйтесь или войдите, чтобы оставить комментарий!</div><br>";
        }
        ?>
        <div>
            <?php

            $comments = $databaseManager->getComments($filmId);

            for ($i = 0; $i < count($comments); ++$i) {
                $user = $databaseManager->getUserByUserId($comments[$i]->getUserId());
                $username = $user->getUsername();
                $time = $comments[$i]->getTime();
                $text = $comments[$i]->getComment();

                echo "<div class='comment'>
                <img src='/images/avatar.jpeg' alt='avatar'/>
                <div class='comment-inside'>
                   <div style='padding-bottom: 10px'> <b>$username</b>, оставлен $time</div>
                       $text
                    </div>
                </div>";
            }

            ?>
        </div>
    </div>
</article>
<script>
    let textarea = document.getElementById("comment");
    textarea.addEventListener('input', (event) => {
        let btn = document.getElementById("add");
        let error = document.getElementById("error");

        if (textarea.value != "") {
            btn.disabled = false;
        } else {
            btn.disabled = true;
            error.style.display = "block";
        }
    });

    let menu = document.getElementById('hover-image');
    let isToggled = false;

    window.onclick = function (event) {
        console.log(event.target);
        if (event.target.matches('.film-main__poster') && isToggled === false) {
            menu.style.display = "block";
            isToggled = true;
        } else if (!event.target.matches('.film-main__poster') && isToggled === true) {
            menu.style.display = "none";
            isToggled = false;
        }
    }

</script>
<?php
include('include/footer.php');
?>
</body>
</html>