<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmsTracker - Авторизация</title>
    <meta name="author" content="FilmsTracker">
    <meta name="description" content="Авторизация на сайте FilmsTracker">
    <meta name="keywords" content="трекер фильмов, лучший трекер фильмов, бесплатный трекер фильмов, кинопоиск, imdb, кинопоиск hd,
     кинопоиск ютуб, кинопоиск топ, гидонлайн кинопоиск, рейтинг imdb, рейтинг фильмов imdb, топ фильмов imdb, в ролях актеры, дата выхода, рейтинги imdb">
    <meta name="language" content="ru">

    <link rel='stylesheet' href="/CSS/validation.css">
    <link rel='stylesheet' href="/CSS/elements.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="icon" href="/images/favicon.ico">
    <script src="/scripts/js/login.js"></script>
</head>
<body>
<?
include('include/header.php');
?>
<article>
    <div class="container">
        <form class="validation-content" onsubmit="return login()">
            <div class="validation-input">
                <h2 class="validation-title">Авторизация</h2>
                <label>Имя пользователя<br>
                    <input id="username" type="text" required>
                </label>

                <label>Пароль<br>
                    <input id="password" type="password" required>
                </label>

                <label>Запомнить меня?
                    <input id="isSave" type="checkbox" style="width: 5%">
                </label>
                <br>
                <span id="error-text" style='color: orangered; font-weight: bold'></span><br>
                <button class="validation-btn">Войти</button>
            </div>
        </form>
    </div>
</article>
<?php
include('include/footer.php');
?>
</body>
</html>