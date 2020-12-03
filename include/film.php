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
$trailerId = $film->getTrailerId();

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
                    <?
                    $poster = "images/posters/$filmId.jpeg";
                    if (!file_exists($poster)) $poster = "images/posters/noimage_poster.jpeg";
                    ?>
                    <img class='film-main__poster' src='<? echo "$poster" ?>' alt='poster'>
                    <?
                    if (isset($_COOKIE['username'])):

                        $isBookmarked = $databaseManager->getIsBookmarked($filmId);
                        $userId = $databaseManager->getUserId($_COOKIE['username']);

                        if ($isBookmarked): ?>
                            <form class='bookmark_btn-cont' action="bookmarks" method="POST">
                                <input name="userId" type="hidden" value='<? echo "$userId"; ?>'>
                                <input name="filmId" type="hidden" value='<? echo "$filmId"; ?>'>
                                <input name="delete" type="hidden" value='true'>
                                <button class='bookmark__btn'>
                                    <img class='bookmark__btn-link' src='/images/unbookmark.svg' alt='unbookmark'>
                                </button>
                            </form>
                        <? else: ?>
                            <form class='bookmark_btn-cont' action="bookmarks" method="POST">
                                <input name="userId" type="hidden" value='<? echo "$userId"; ?>'>
                                <input name="filmId" type="hidden" value='<? echo "$filmId"; ?>'>
                                <input name="delete" type="hidden" value='false'>
                                <button class='bookmark__btn'>
                                    <img class='bookmark__btn-link' src='/images/bookmark.svg' alt='bookmark'>
                                </button>
                            </form>
                        <? endif; endif ?>
                </div>
                <div id="hover-image" class="poster-hover__content">
                    <span class="close">×</span>
                    <?
                    echo "<img class='poster-hover__image' src='/images/posters/$filmId.jpeg' alt='$title'>" ?>
                    <div class="caption"><? echo "$title" ?> </div>
                </div>

                <div class=' film-main__info'>
                    <table>
                        <tr>
                            <td><b>Рейтинг:</b></td>
                            <td><? echo "
                                <a class='film-main__info-rating' href='https://www.imdb.com/title/$filmId/'>
                                    IMDb
                                </a>
                                :&nbsp; <b>$rating</b> ($votes)" ?>
                            </td>
                        </tr>

                        <tr>
                            <td><b>Дата&nbsp;выхода:</b></td>
                            <td><? echo "$year год" ?></td>
                        </tr>
                        <tr>
                            <td><b>Страна:</b></td>
                            <td><?
                                $countryObj = $databaseManager->getCountryById($film->getCountryId());
                                $country = $countryObj->getCountry();
                                $countryId = $countryObj->getCountryId();
                                echo "<a class='link' href='list?country=$countryId'>$country</a>"
                                ?>
                            </td>

                        </tr>
                        <tr>
                            <td><b>Время:</b></td>
                            <td><? echo "$runtime_minutes мин." ?></td>
                        </tr>
                        <?
                        if ($isAdult !== "") {
                            echo "
                              <tr>
                                 <td><b>Возраст:</b></td>
                                 <td>$isAdult</td> 
                              </tr>";
                        }
                        ?>
                        <tr>
                            <td><b>Жанр:</b></td>
                            <td>
                                <? $genres = $film->getGenres();
                                for ($i = 0; $i < count($genres) - 1; $i++) {
                                    $genreObj = $databaseManager->getGenreById($genres[$i]);
                                    $genre = $genreObj->getGenre();
                                    $genre_name = $genreObj->getGenreName();

                                    echo "<a class='link' href='list?genre=$genre_name&page=1'>$genre</a>";

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

                                    echo "<a class='link' href='actor?id=$id'>$name</a>";
                                    if ($j != $size - 1) echo ", ";
                                }

                                echo "</td></tr>";
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
            <div class='film__section'>
                <h2 class='section__title'>Cюжет фильма</h2>
                <? echo "$plot" ?>
            </div>

            <div class="film__section">
                <h2 class='section__title'>Трейлер фильма</h2>
                <iframe width="100%" height="315"
                    <? echo "src='https://www.youtube.com/embed/$trailerId'" ?>
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
            </div>
        </div>

        <div class="film__section">
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
        <?php if (isset($_COOKIE['username'])) : ?>
            <form action='comments' method='post'>
                <textarea id='comment' class='comment-input' name='comment' onchange='checkText()'
                          placeholder='Написать комментарий'></textarea>
                <div id='error' class='error-hint'>Введите текст!</div>
                <button disabled='true' id='add' class='add-btn'>Добавить</button>
                <? echo "<input name='filmId' value='$filmId' style='display: none'/>" ?>
            </form>
        <? else: ?>
            <div class='comment-input' style='color: orangered; font-weight: bold; height: auto;'>Зарегестрируйтесь или
                войдите, чтобы оставить комментарий!
            </div><br>
        <? endif; ?>
        <div>
            <?php

            $comments = $databaseManager->getComments($filmId);

            for ($i = 0; $i < count($comments); ++$i) {
                $user = $databaseManager->getUserByUserId($comments[$i]->getUserId());
                $username = $user->getUsername();
                $time = $comments[$i]->getTime();
                $text = $comments[$i]->getComment();

                echo "<div class='comment'>
                    <div class='comment-avatar'>
                       <img src='/images/avatar.jpeg' alt='avatar'/>
                    </div>
                    <div class='comment-inside'>
                       <div class='comment-header'>
                            <b>$username</b>, оставлен $time
                       </div>
                       <span>
                            $text
                       </span>
                    </div>
                </div>";
            }

            ?>
        </div>
    </div>
</article>
<script>
    let menu = document.getElementById('hover-image');
    let isToggled = false;

    window.onclick = function (event) {
        if (event.target.matches('.film-main__poster') && isToggled === false) {
            menu.style.display = "block";
            isToggled = true;
        } else if (!event.target.matches('.film-main__poster') && isToggled === true) {
            menu.style.display = "none";
            isToggled = false;
        }
    }

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

</script>
<?php
include('include/footer.php');
?>
</body>
</html>