<?php
require_once 'scripts/php/Managers/DatabaseManager.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel='stylesheet' href="./CSS//header.css">
    <script>
        let isOpened = 0

        function toggle() {
            let menu = document.getElementsByClassName("mobile-dropdown__menu");

            if (isOpened) {
                menu[0].style.display = "none";
                isOpened = 0;
            } else {
                menu[0].style.display = "block";
                isOpened = 1;
            }
        }

        // Закрытие если нажато было из вне
        window.onclick = function (event) {
            if (!event.target.matches('.button-image')) {
                let menu = document.getElementsByClassName("mobile-dropdown__menu");
                menu[0].style.display = "none";
                isOpened = 0;
            }
        }
    </script>
</head>

<body style="background-color: #efefef;">
<?php

function setGenres()
{
    $databaseManager = new DatabaseManager();
    $genres = $databaseManager->getGenres();

    for ($i = 0; $i < mysqli_num_rows($genres); ++$i) {
        $row = mysqli_fetch_row($genres);
        echo "<li><a href='#'>$row[0]</a></li>";
    }
}

?>
<header class='header'>
    <div class="container" id="header_container">
        <div class="header-top">
            <a class='header__logo' href="/kursach-site/">
                <img src="./images/site_logo.svg" alt="site_logo">
            </a>
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
            <nav class="header-bot__left">
                <!-- Mobile button -->
                <div class="mobile-controls">
                    <button class="mobile-menu__button-genres mobile-menu__btn" onclick="toggle()">
                        <img class="button-image" src="./images/ic_menu.svg" alt="site_logo">
                    </button>
                    <div>
                        <ul class="mobile-dropdown__menu">
                            <?php setGenres(); ?>
                        </ul>
                    </div>
                </div>

                <ul class="menu__list">
                    <div class="dropdown-menu">
                        <button class="dropdown-menu__btn menu__link">Все жанры</button>
                        <div class="dropdown-content container">
                            <ul>
                                <?php setGenres(); ?>
                            </ul>
                        </div>
                    </div>
                    <li>
                        <a href="#" class='menu__link'>Боевики</a>
                    </li>
                    <li>
                        <a href="#" class='menu__link'>Комедии</a>
                    </li>
                    <li>
                        <a href="#" class='menu__link'>Драма</a>
                    </li>
                    <li>
                        <a href="#" class='menu__link'>Фантастика</a>
                    </li>
                    <li>
                        <a href="#" class='menu__link'>Триллеры</a>
                    </li>
                </ul>
                <div class='header-bot__search'>
                    <img class='header__search_button' src="./images/ic_search.svg" alt="ic_search">
                    <input class='header__search_input' name="search" type="text" id="search"
                           placeholder="Я ищу фильм...">
                </div>

                <!-- Mobile button -->
                <div class="mobile-controls">
                    <button class="mobile-menu__button-genres mobile-menu__btn" onclick="toggle()">
                        <img class="button-image" src="./images/ic_userPanel.svg" alt="site_logo">
                    </button>
                    <div>
                        <ul class="mobile-dropdown__menu">
                            <?php setGenres(); ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>
</body>

</html>