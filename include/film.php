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
    <script>
        function deleteComment(filmId, time, parentId) {
            let parent = document.getElementById(parentId);

            let request = new XMLHttpRequest();
            request.open('DELETE', '/comments/' + filmId + '/' + time, true);
            request.addEventListener('readystatechange', function () {
                if ((request.readyState === 4) && (request.status === 200)) {
                    if (request.responseText === "") {
                        parent.remove();
                    } else {
                        alert("Ошибка удаления: " + request.responseText)
                    }
                }
            });
            request.send();
        }

        function addComment(filmId) {
            let textarea = document.getElementById("comment");
            let parent = document.getElementById("comments-block");
            let request = new XMLHttpRequest();
            let error = document.getElementById("error");

            if (textarea.value !== "") {
                error.style.display = "none";
                request.open('POST', '/comments', true);
                request.addEventListener('readystatechange', function () {
                    if ((request.readyState === 4) && (request.status === 200)) {
                        if (request.responseText === "") {
                            let requestComment = new XMLHttpRequest();

                            // Определение последнего комментарий id
                            let id = 0;
                            let childrenArray = parent.children;
                            let lastComment = childrenArray[parent.childElementCount - 1];
                            if (lastComment != null)
                                id = +lastComment.id.split("_")[1] + 1;

                            // получаем блок комментария
                            requestComment.open('GET', '/comments?filmId=' + filmId + "&comment=" + textarea.value + "&id=" + id, true);
                            requestComment.addEventListener('readystatechange', function () {
                                if ((requestComment.readyState === 4) && (requestComment.status === 200)) {
                                    let div = document.createElement("div");
                                    div.innerHTML = requestComment.responseText;
                                    parent.insertBefore(div, parent.firstChild);
                                }
                            });
                            requestComment.send();
                        } else {
                            alert("Ошибка добавления: " + request.responseText);
                        }
                    }
                });
                request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
                request.send('filmId=' + window.encodeURIComponent(filmId) + '&comment=' + window.encodeURIComponent(textarea.value));
            } else {
                error.style.display = "block";
            }
        }

        function changeBookmark(userId, filmId) {
            let img = document.getElementById("bookmark_img");
            let btn = document.getElementsByClassName("bookmark__btn")[0];

            const toBookmarks = "bookmark.svg";
            const unBookmark = "unbookmark.svg";

            let method;
            if (btn.value === "false") {
                method = "PUT";
                btn.value = "true";
            } else {
                method = "DELETE";
                btn.value = "false";
            }

            let request = new XMLHttpRequest();
            request.open(method, '/bookmarks/' + filmId + '/' + userId, true);
            request.addEventListener('readystatechange', function () {
                if ((request.readyState === 4) && (request.status === 200)) {
                    const base = "/images/";
                    if (method === "PUT") img.src = base + unBookmark;
                    else img.src = base + toBookmarks;
                }
            });
            request.send();
        }
    </script>
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
            </div>
            <div id="<? echo $filmId ?>" class='film-main'>
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
                        if ($isBookmarked == "true")
                            $img = "unbookmark.svg";
                        else
                            $img = "bookmark.svg";

                        ?>
                        <div class='bookmark_btn-cont'>
                            <button class='bookmark__btn' onclick="<? echo "changeBookmark($userId, '$filmId')" ?>"
                                    value="<? echo $isBookmarked; ?>">
                                <img id="bookmark_img" class='bookmark__btn-link' src='/images/<? echo $img ?>'
                                     alt='bookmark'>
                            </button>
                        </div>
                    <? endif ?>
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
                                :&nbsp;
                                 <b>$rating</b> ($votes)" ?>
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
                                echo "<a class='link' href='/list/filter?country=$countryId'>$country</a>"
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

                                    echo "<a class='link' href='/list/filter?genre=$genre_name&page=1'>$genre</a>";

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
                <div class="slider__container" style="overflow: scroll; overflow-y: hidden;">
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
            <div>
                <textarea id='comment' class='comment-input' name='comment'
                          placeholder='Написать комментарий'></textarea>
                <div id='error' class='error-hint'>Введите текст!</div>
                <button id='add' class='add-btn' onclick='addComment("<? echo $filmId ?>")'>Добавить
                </button>

            </div>
        <? else: ?>
            <div class='comment-input' style='color: orangered; font-weight: bold; height: auto;'>Зарегестрируйтесь или
                войдите, чтобы оставить комментарий!
            </div><br>
        <? endif; ?>
        <div id="comments-block">
            <?
            $comments = $databaseManager->getComments($filmId);

            for ($i = 0; $i < count($comments); ++$i) {
                $user = $databaseManager->getUserByUserId($comments[$i]->getUserId());
                $username = $user->getUsername();
                $password = $user->getPassword();

                if ($_COOKIE['username'] == $username && $_COOKIE['password'] == $password) $isDeletable = true;
                else $isDeletable = false;
                $objectHelper->createComment($user, $comments[$i], $i, $isDeletable, false);
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
</script>
<?php
include('include/footer.php');
?>
</body>
</html>