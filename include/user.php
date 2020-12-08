<!DOCTYPE html>
<html lang="en">
<?
$databaseManager = new Database();

$username = $_COOKIE['username'];
$user = $databaseManager->getUserByUserId($databaseManager->getUserId($username));
$email = $user->getEmail();
$avatar = $user->getAvatar();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пользователь <? echo $username ?></title>
    <meta name="author" content="FilmsTracker">
    <meta name="description" content="Настройка профиля пользователя <? echo $username ?>">
    <meta name="keywords" content="трекер фильмов, лучший трекер фильмов, бесплатный трекер фильмов, кинопоиск, imdb, кинопоиск hd,
     кинопоиск ютуб, кинопоиск топ, гидонлайн кинопоиск, рейтинг imdb, рейтинг фильмов imdb, топ фильмов imdb, в ролях актеры, дата выхода, рейтинги imdb">
    <meta name="language" content="ru">

    <link rel='stylesheet' href="/CSS/elements.css">
    <link rel='stylesheet' href="/CSS/user.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="icon" href="/images/favicon.ico">
</head>
<body>
<?
include('include/header.php');
?>
<article style=" margin-bottom: 5%; margin-top: 3%;">
    <div class="container">
        <div class="main">
            <h1 class="text-header__centered" style="margin-bottom: 15px">Настройки профиля</h1>
            <div class="center">
                <? echo "<img style='width: 30%' src='$avatar' alt='$username'>" ?>
            </div>
            <label><b>Никнейм</b></label>
            <div class="row"><? echo "$username"; ?> <br></div>
            <label><b>Email</b></label>
            <div class="row"><? echo "$email"; ?></div>
            <form action="exit" method="post">
                <button class="exit-btn">Выйти</button>
            </form>
        </div>
    </div>
</article>
<?
include('include/footer.php');
?>
</body>
</html>