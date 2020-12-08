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
     кинопоиск ютуб, кинопоиск топ, гидонлайн кинопоиск, рейтинг imdb, рейтинг фильмов imdb, топ фильмов imdb, в ролях актеры, дата выхода, рейтинги imdb, закладки, любимое">
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
<article style=" margin-bottom: 5%; margin-top: 3%;">
    <h1 style="display: none">Мои закладки</h1>
    <div class="container">
        <div class="films-bookmarks">
            <h2 class='text-header__centered'>Закладки</h2>
            <div class="films-table">
                <? if (count($films) > 0): ?>
                    <div class="films-container">
                        <?
                        for ($i = count($films) - 1; $i >= 0; --$i) {
                            $film = $database->getFilmByFilmId($films[$i], true);

                            if ($film != null) {
                                $name = $film->getTitle();
                                $objectHelper->createFilm($film->getFilmId(), $film->getTitle(), $film->getPremiered(), $film->getGenres());
                            }
                        }
                        ?>
                    </div>
                <? else: ?>
                    <span style="margin-top: 15px">В данный момент тут пусто! Ищите фильмы и добавляйте в закладки</span>
                <? endif; ?>
            </div>
        </div>
    </div>
</article>
<?
include('include/footer.php');
?>
</body>
</html>