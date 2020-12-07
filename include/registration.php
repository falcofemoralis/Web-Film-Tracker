<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Сайт поиска информации про фильмы">
    <meta name="author" content="Иващенко Владислав Романович">
    <title>Трекер фильмов</title>
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
<article>
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