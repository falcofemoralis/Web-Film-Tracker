<!DOCTYPE html>
<html lang="en">
<?
global $isAuthed;

$database = new Database();
$objectHelper = new ObjectHelper();
$bookmarks = new Bookmarks();

$filmId = $_GET["id"];
$film = $database->getFilmByFilmId($filmId, false);
$title = $film->getTitle();
$originalTitle = $database->getOriginalFilmTitle($filmId);
$rating = $film->getRating();
$votes = $film->getVotes();
$plot = $film->getPlot();
$year = $film->getPremiered();
$runtime_minutes = $film->getRuntimeMinutes();
$isAdult = $film->getIsAdult();
$trailerId = $film->getTrailerId();

$allActors = $database->getActorsByFilmId($filmId);
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
$pageTitle = "Информация про фильм " . $title;
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <? echo $pageTitle ?></title>
    <meta name="author" content="FilmsTracker">
    <meta name="description" content="Смотреть трейлер и читать информацию про фильм <? echo $title ?>">
    <meta name="keywords" content="трекер фильмов, лучший трекер фильмов, бесплатный трекер фильмов, кинопоиск, imdb, кинопоиск hd,
     кинопоиск ютуб, кинопоиск топ, гидонлайн кинопоиск, рейтинг imdb, рейтинг фильмов imdb, топ фильмов imdb, в ролях актеры,
     дата выхода, рейтинги imdb, смотреть трейлер, <? echo $title ?>">
    <meta name="language" content="ru">
    <meta property="og:image" content="http://a0488451.xsph.ru/images/posters/<? echo $filmId ?>.jpeg">

    <link rel="icon" href="/images/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel='stylesheet' href="/CSS/film.css">
    <link rel='stylesheet' href="/CSS/elements.css">
    <link rel='stylesheet' href="/CSS/slider.css">
    <script src="/scripts/js/film.js"></script>
    <script src="/scripts/js/hoverImage.js"></script>
    <script src="/scripts/js/comment.js"></script>
</head>
<body>
<?
include('include/header.php');
?>
<article class="page">
    <h1 style="display: none"><? echo $pageTitle ?></h1>
    <div class="container">
        <div class="film-container">
            <div class="film__title-row">
                <div style="margin-left: 10px">
                    <h1 class='film__title'><? echo "$title" ?></h1>
                    <h2 class="film__title sub-fim__title "><? echo $originalTitle ?></h2>
                </div>
            </div>
            <div id="<? echo $filmId ?>" class='film-main'>
                <div class="film-main__poster-cont">
                    <?
                    $poster = "images/posters/$filmId.jpeg";
                    if (!file_exists($poster)) $poster = "images/posters/noimage_poster.jpeg";
                    ?>
                    <img class='film-main__poster' src='<? echo "$poster" ?>' alt='poster'>

                    <?

                    if ($isAuthed):
                        $userId = $database->getUserId($_COOKIE['username']);
                        $isBookmarked = $bookmarks->getIsBookmarked($filmId, $userId);
                        if ($isBookmarked == "true")
                            $img = "unbookmark.svg";
                        else
                            $img = "bookmark.svg";

                        ?>
                        <div class='bookmark_btn-cont'>
                            <button class='bookmark__btn' onclick="<? echo "changeBookmark('$filmId')" ?>"
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
                                $countryObj = $database->getCountryById($film->getCountryId());
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
                                    $genreObj = $database->getGenreById($genres[$i]);
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
                <iframe style="border: none; width: 100%; height: 315px"
                    <? echo "src='https://www.youtube.com/embed/$trailerId'" ?>
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
            </div>
        </div>

        <div class="film__section">
            <h2 class="section__title">Актеры в фильме</h2>
            <div class='slider' style="justify-content:  flex-start;">
                <div class="slider__container" style="overflow: scroll; overflow-y: hidden;">
                    <?
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
        <? if ($isAuthed) : ?>
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
            $commentsObj = new Comments();
            $comments = $commentsObj->getComments($filmId);

            for ($i = 0; $i < count($comments); ++$i) {
                $user = $commentsObj->getUserByUserId($comments[$i]->getUserId());
                $username = $user->getUsername();
                $password = $user->getPassword();

                if ($_COOKIE['username'] == $username && $_COOKIE['password'] == $password && $isAuthed) $isDeletable = true;
                else $isDeletable = false;

                $objectHelper->createComment($user, $comments[$i], $i, $isDeletable, false);
            }
            ?>
        </div>
    </div>
</article>
<script>
    initHoverImg()
    initComments();
</script>
<?
include('include/footer.php');
?>
</body>
</html>