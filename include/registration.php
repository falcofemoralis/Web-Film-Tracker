<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmsTracker - Регистрация</title>
    <meta name="author" content="FilmsTracker">
    <meta name="description" content="Регистрация на сайте FilmsTracker">
    <meta name="keywords" content="трекер фильмов, лучший трекер фильмов, бесплатный трекер фильмов, кинопоиск, imdb, кинопоиск hd,
     кинопоиск ютуб, кинопоиск топ, гидонлайн кинопоиск, рейтинг imdb, рейтинг фильмов imdb, топ фильмов imdb, в ролях актеры, дата выхода, рейтинги imdb">
    <meta name="language" content="ru">

    <link rel='stylesheet' href="/CSS/validation.css">
    <link rel='stylesheet' href="/CSS/elements.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="icon" href="/images/favicon.ico">
    <script src="/scripts/js/registration.js"></script>
</head>
<body>
<?
include('include/header.php');
?>
<article style=" margin-bottom: 5%; margin-top: 3%;">
    <div class="container">
        <form enctype="multipart/form-data" id="register" class="validation-content" onsubmit="return register()">
            <div class="validation-input">
                <h2 class="validation-title">Регистрация</h2>
                <label>E-mail<br>
                    <input name="email" type="email" required>
                </label>
                <label>Имя пользователя<br>
                    <input name="username" type="text" required>
                </label>

                <label>Пароль<br>
                    <input name="password" type="password" required>
                </label>

                <label>Загрузить аватар <br> <span style="font-size: 12px">Максимальный размер файла 1мб</span>
                    <input name="avatar" type="file" style="border: none; padding: 12px 0 12px 0"
                           accept=".jpg, .jpeg, .png">
                </label>

                <label>Запомнить меня?
                    <input name="isSave" type="checkbox" style="width: 5%">
                </label>
                <br>

                <label id="error-text" style='color: orangered; font-weight: bold'></label><br>
                <button class="validation-btn" type="submit">Зарегистрироваться</button>
            </div>
        </form>
    </div>
</article>
<?
include('include/footer.php');
?>
</body>
</html>