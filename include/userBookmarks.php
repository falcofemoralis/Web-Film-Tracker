<!DOCTYPE html>
<html lang="en">
<?
$database = new Database();
$bookmarks = new Bookmarks();
$objectHelper = new ObjectHelper();

$userId = $database->getUserId($_COOKIE['username']);
$films = $bookmarks->getUserBookmarks($userId);
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои закладки</title>
    <meta name="author" content="FilmsTracker">
    <meta name="description" content="Закладки фильмов пользователя <? echo $_COOKIE['username'] ?>">
    <meta name="keywords" content="трекер фильмов, лучший трекер фильмов, бесплатный трекер фильмов, кинопоиск, imdb, кинопоиск hd,
     кинопоиск ютуб, кинопоиск топ, гидонлайн кинопоиск, рейтинг imdb, рейтинг фильмов imdb, топ фильмов imdb, в ролях актеры, дата выхода, рейтинги imdb">
    <meta name="language" content="ru">

    <link rel='stylesheet' href="/CSS/elements.css">
    <link rel='stylesheet' href="/CSS/bookmarks.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="icon" href="/images/favicon.ico">
</head>
<body>
<?
include('include/header.php');
?>
<article>
    <div class="container">
        <div>
            <h2 class='text__header'>Закладки</h2>
            <div class="films-table">
                <div class="films-container" style="width: calc(((156px * 4) + (6px * 4 * 2)));">
                    <?
                    for ($i = 0; $i < count($films); ++$i) {
                        $film = $database->getFilmByFilmId($films[$i], true);

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
<?
include('include/footer.php');
?>
</body>
</html>