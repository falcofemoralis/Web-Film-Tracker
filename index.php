<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel='stylesheet' href="./CSS//style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<body style="background-color: #efefef;">
    <header class='header'>
        <div class="container">
            <div class="header-top">
                <img class='header__logo' src="./images/site_logo.svg" alt="site_logo">
                <nav class="header-top__right">
                    <ul class="menu__list">
                        <li class='menu__item'>
                            <a href="#" class='menu__link'>Закладки</a>
                        </li>
                        <li class='menu__item'>
                            <a href="#" class='menu__link'>Пользователь</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class='header-bot'>
                <nav class="header-bot__left" id="header-bot__left">
                    <ul class="menu__list">
                        <li class='menu__item'>
                            <a href="#" class='menu__link'>Все жанры</a>
                        </li>
                        <li class='menu__item'>
                            <a href="#" class='menu__link'>Боевики</a>
                        </li>
                        <li class='menu__item'>
                            <a href="#" class='menu__link'>Комедии</a>
                        </li>
                        <li class='menu__item'>
                            <a href="#" class='menu__link'>Драма</a>
                        </li>
                        <li class='menu__item'>
                            <a href="#" class='menu__link'>Фантастика</a>
                        </li>
                        <li class='menu__item'>
                            <a href="#" class='menu__link'>Триллеры</a>
                        </li>

                    </ul>
                </nav>
                <div class='header-bot__right'>
                    <img class='header__search_button' src="./images/ic_search.svg" alt="ic_search">
                    <input class='header__search_input' name="search" type="text" id="search"
                        placeholder=" Я ищу фильм...">
                </div>
            </div>
        </div>
    </header>
    <article>
        <div class="container">
            <?php
			require_once 'connection.php'; // подключаем скрипт

			// подключаемся к серверу
			$link = mysqli_connect($host, $user, $password, $database)
				or die("Ошибка " . mysqli_error($link));

			$query = "SELECT * FROM titles limit 5";

			$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
			if ($result) {
				$rows = mysqli_num_rows($result); // количество полученных строк

				echo "<p class='films-row__name'>Популярные фильмы</p>";
				echo "<div class='films-row__popular'>";
				for ($i = 0; $i < $rows; ++$i) {
					$row = mysqli_fetch_row($result);
					echo "<div class='films-row__film'>";
					echo "<img class='films-row__poster' src='./images/posters/$row[0].jpeg' alt='poster'>";
					echo "<h1>$row[1]</h1>";
					echo " 2020, Приключения";
					echo "</div>";
				}
				echo "</div>";

				// очищаем результат
				mysqli_free_result($result);
			}

			// закрываем подключение
			mysqli_close($link);
			?>
        </div>
    </article>
</body>
<script>
search.onblur = function() {
    search.style.width = "250px";
    var left_side = document.getElementById("header-bot__left");
    setTimeout(() => {
        left_side.style.display = "block";
    }, 350);;
};

search.onfocus = function() {
    search.style.width = "1100px";
    var left_side = document.getElementById("header-bot__left");
    left_side.style.display = "none";
};
</script>

</html>