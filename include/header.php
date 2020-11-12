<?php
require_once 'scripts/php/Managers/DatabaseManager.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel='stylesheet' href="./CSS//header.css">
</head>

<body style="background-color: #efefef;">
<header class='header'>
    <div class="container" id="header_container">
        <div class="header-top">
            <a class='header__logo' href="/kursach-site/">
                <img src="./images/site_logo.svg" alt="site_logo">
            </a>

            <nav class="header-top__right">
                <a class="mobile-menu__userControl mobile-menu__btn">
                    <img src="./images/userMenu.svg" alt="site_logo">
                </a>
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
                <button class="mobile-menu__list mobile-menu__btn">Жанры</button>
                <ul class="menu__list">
                    <div class="dropdown-menu">
                        <button class="dropdown-menu__btn menu__link">Все жанры</button>
                        <div class="dropdown-content container">
                            <ul>
                                <?php
                                //Блок жанров
                                $databaseManager = new DatabaseManager();

                                $genres = $databaseManager->getGenres();
                                for ($i = 0; $i < mysqli_num_rows($genres); ++$i) {
                                    $row = mysqli_fetch_row($genres);
                                    echo "<li>
                                         <a href='#'>$row[0]</a>
                                         </li>";
                                }

                                // очищаем результат
                                mysqli_free_result($genres);
                                ?>
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
            </nav>
            <button class="mobile-menu__search mobile-menu__btn">Поиск</button>
            <div class='header-bot__right'>
                <img class='header__search_button' src="./images/ic_search.svg" alt="ic_search">
                <input class='header__search_input' name="search" type="text" id="search"
                       placeholder="Я ищу фильм...">
            </div>
        </div>
    </div>
</header>
</body>

</html>