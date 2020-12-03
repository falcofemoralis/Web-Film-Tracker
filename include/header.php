<?php
require_once 'scripts/php/Managers/DatabaseManager.php';
?>

<link rel='stylesheet' href="/CSS/header.css">

<script>
    let isOpened = 0;
    let last = "";

    function toggle(id) {
        if (last != id) {
            let lastMenu = document.getElementsByClassName(last);
            if (lastMenu.length > 0) {
                lastMenu[0].style.visibility = "hidden";
                lastMenu[0].style.opacity = "0";
                isOpened = 0;
            }
        }

        let menu = document.getElementsByClassName(id);
        if (isOpened) {
            menu[0].style.visibility = "hidden";
            menu[0].style.opacity = "0";
            isOpened = 0;
        } else {
            menu[0].style.visibility = "visible";
            menu[0].style.opacity = "1";
            isOpened = 1;
            last = id;
        }
    }
</script>

<?php

function setGenres()
{
    $databaseManager = new DatabaseManager();
    $genres = $databaseManager->getGenres();

    for ($i = 0; $i < count($genres); ++$i) {
        $genre_name = $genres[$i]->getGenreName();
        $genre = $genres[$i]->getGenre();
        echo "<li><a href='list?genre=$genre_name&page=1'>$genre</a></li>";
    }
}

?>
<header class='header'>
    <div class="container" id="header_container">
        <div class="header-top">
            <a class='header__logo' href="/">
                <img src="/images/site_logo.svg" alt="site_logo">
            </a>
            <!-- Desktop user navigations -->
            <nav class="header-top__right">
                <ul class="menu__list">
                    <?php
                    if (isset($_COOKIE['username'])): ?>
                        <li class='menu__item'>
                            <a href='userBookmarks' class='menu__link'>Закладки</a>
                        </li>
                        <li class='menu__item'>
                            <a href='user' class='menu__link'>Пользователь</a>
                        </li>
                    <? else: ?>
                        <li class='menu__item'>
                            <a href='login' class='menu__link'>Войти</a>
                        </li>
                        <li class='menu__item'>
                            <a href='registration' class='menu__link'>Регистрация</a>
                        </li>
                    <? endif; ?>
                </ul>
            </nav>
        </div>

        <div class='header-bot'>
            <nav class="header-bot__left">
                <!-- Mobile button -->
                <div class="mobile-controls">
                    <button class="mobile-menu__button-genres mobile-menu__btn" onclick="toggle('genres')">
                        <img class="button-image" src="/images/ic_menu.svg" alt="site_logo">
                    </button>
                    <div>
                        <ul class="mobile-dropdown__menu genres">
                            <li style="width: 100%; display: flex; justify-content: center"><a href='random'
                                                                                               class="random"
                                                                                               style="color: dodgerblue; font-size: 20px">Мне
                                    повезет!</a></li>
                            <?php setGenres(); ?>
                        </ul>
                    </div>
                </div>

                <!-- Desktop button -->
                <ul class="menu__list">
                    <li class="dropdown-menu">
                        <button class="dropdown-menu__btn menu__link">Все жанры</button>
                        <div class="dropdown-content container">
                            <ul>
                                <?php setGenres(); ?>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="list?genre=action&page=1" class='menu__link'>Боевики</a>
                    </li>
                    <li>
                        <a href="list?genre=comedy&page=1" class='menu__link'>Комедии</a>
                    </li>
                    <li>
                        <a href="list?genre=drama&page=1" class='menu__link'>Драма</a>
                    </li>
                    <li>
                        <a href="list?genre=scifi&page=1" class='menu__link'>Фантастика</a>
                    </li>
                    <li>
                        <a href="list?genre=thriller&page=1" class='menu__link'>Триллеры</a>
                    </li>
                    <li>
                        <a href="random" class='menu__link random'>Мне повезет!</a>
                    </li>
                </ul>
                <div class='header-bot__search'>
                    <img class='header__search_button' src="/images/ic_search.svg" alt="ic_search">
                    <form action="list" style="width: 100%">
                        <input class='header__search_input' type="search" placeholder="Я ищу фильм..." name="search">
                        <button type="submit" style="display: none"></button>
                    </form>
                </div>

                <!-- Mobile user navigations -->
                <div class="mobile-controls">
                    <button class="mobile-menu__button-genres mobile-menu__btn" onclick="toggle('login')">
                        <?php if (isset($_COOKIE['username'])) : ?>
                            <img class='button-image' src='/images/ic_userPanel.svg' alt='site_logo'>
                        <? else: ?>
                            <img class='button-image' src='/images/ic_authPanel.svg' alt='site_logo'>
                        <? endif; ?>
                    </button>
                    <div>
                        <ul class="mobile-dropdown__menu login">
                            <?php
                            if (isset($_COOKIE['username'])): ?>
                                <li class='menu__item'>
                                    <a href='userBookmarks' class='menu__link'>Закладки</a>
                                </li>
                                <li class='menu__item'>
                                    <a href='user' class='menu__link'>Пользователь</a>
                                </li>
                            <? else: ?>
                                <li class='menu__item'>
                                    <a href='login' class='menu__link'>Логин</a>
                                </li>
                                <li class='menu__item'>
                                    <a href='registration' class='menu__link'>Регистрация</a>
                                </li>
                            <? endif; ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>
