<?php
require_once 'scripts/php/Managers/DatabaseManager.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel='stylesheet' href="./CSS//header.css">

    <script>
        let isOpened = 0;
        let last = "";

        function toggle(id) {
            console.log(last);
            console.log(id);

            if (last != id) {
                let lastMenu = document.getElementsByClassName(last);
                if (lastMenu.length > 0) {
                    lastMenu[0].style.display = "none";
                    isOpened = 0;
                }
            }

            let menu = document.getElementsByClassName(id);
            if (isOpened) {
                menu[0].style.display = "none";
                isOpened = 0;
            } else {
                menu[0].style.display = "block";
                isOpened = 1;
                last = id;
            }
        }
    </script>
</head>

<body>
<?php

function setGenres()
{
    $databaseManager = new DatabaseManager();
    $genres = $databaseManager->getGenres();

    for ($i = 0; $i < count($genres); ++$i) {

        $genre_id = $genres[$i][1];
        $genre_name = $genres[$i][0];
        echo "<li><a href='list?type=$genre_id&page=1'>$genre_name</a></li>";
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
                    <?php
                    if (isset($_COOKIE['username'])) {
                        echo "<li class='menu__item'>
                          <a href='#' class='menu__link'>Закладки</a>
                               </li>
                          <li class='menu__item'>
                              <a href='#' class='menu__link'>Пользователь</a>
                          </li> ";
                    } else {
                        echo "<li class='menu__item'>
                          <a href='auth' class='menu__link'>Логин</a>
                               </li>
                          <li class='menu__item'>
                              <a href='register' class='menu__link'>Регистрация</a>
                          </li> ";
                    }
                    ?>
                </ul>
            </nav>
        </div>

        <div class='header-bot'>
            <nav class="header-bot__left">
                <!-- Mobile button -->
                <div class="mobile-controls">
                    <button class="mobile-menu__button-genres mobile-menu__btn" onclick="toggle('genres')">
                        <img class="button-image" src="./images/ic_menu.svg" alt="site_logo">
                    </button>
                    <div>
                        <ul class="mobile-dropdown__menu genres">
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
                        <a href="list?type=action&page=1" class='menu__link'>Боевики</a>
                    </li>
                    <li>
                        <a href="list?type=comedy&page=1" class='menu__link'>Комедии</a>
                    </li>
                    <li>
                        <a href="list?type=drama&page=1" class='menu__link'>Драма</a>
                    </li>
                    <li>
                        <a href="list?type=scifi&page=1" class='menu__link'>Фантастика</a>
                    </li>
                    <li>
                        <a href="list?type=thriller&page=1" class='menu__link'>Триллеры</a>
                    </li>
                </ul>
                <div class='header-bot__search'>
                    <img class='header__search_button' src="./images/ic_search.svg" alt="ic_search">
                    <form action="list" style="width: 100%">
                        <input class='header__search_input' type="search" placeholder="Я ищу фильм..." name="search">
                        <button type="submit" style="display: none"></button>
                    </form>
                </div>

                <!-- Mobile button -->
                <div class="mobile-controls">
                    <button class="mobile-menu__button-genres mobile-menu__btn" onclick="toggle('auth')">
                        <img class="button-image" src="./images/ic_userPanel.svg" alt="site_logo">
                    </button>
                    <div>
                        <ul class="mobile-dropdown__menu auth">
                            <?php
                            if (isset($_COOKIE['username'])) {
                                echo "<li class='menu__item'>
                                  <a href='#' class='menu__link'>Закладки</a>
                                       </li>
                                  <li class='menu__item'>
                                      <a href='#' class='menu__link'>Пользователь</a>
                                  </li> ";
                            } else {
                                echo "<li class='menu__item'>
                                  <a href='auth' class='menu__link'>Логин</a>
                                       </li>
                                  <li class='menu__item'>
                                      <a href='register' class='menu__link'>Регистрация</a>
                                  </li> ";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>
</body>
</html>