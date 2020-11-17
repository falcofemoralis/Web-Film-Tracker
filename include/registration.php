<!DOCTYPE html>
<html lang="en">

<head>

</head>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Сайт поиска информации про фильмы">
    <meta name="author" content="Иващенко Владислав Романович">
    <title>Трекер фильмов</title>
    <link rel='stylesheet' href="./CSS//registration.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="icon" href="./images/favicon.ico">
</head>

<body>
<?php
include('include/header.php');
?>

<article class="page">
    <div class="container">
        <form class="registration-content" action="register" method="post">
            <div class="registration-input">
                <div class="registration-title">Регистрация</div>
                <label>E-mail<br>
                    <input name="email" type="email">
                </label>
                <label>Имя пользователя<br>
                    <input name="username" type="text">
                </label>

                <label>Пароль<br>
                    <input name="password" type="password">
                </label>

                <button class="registration-btn_reg" type="submit">Зарегистрироваться</button>
            </div>
        </form>
    </div>
</article>
</body>
</html>