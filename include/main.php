<!DOCTYPE html>
<html lang="en">

<?php
require_once 'scripts/php/Objects/Film.php';
require_once 'scripts/php/Managers/ObjectHelper.php';
require_once 'scripts/php/Managers/DatabaseManager.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Сайт поиска информации про фильмы">
    <meta name="author" content="Иващенко Владислав Романович">
    <title>Трекер фильмов</title>
    <link rel='stylesheet' href="/CSS/elements.css">
    <link rel='stylesheet' href="/CSS/main.css">
    <link rel='stylesheet' href="/CSS/slider.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="icon" href="/images/favicon.ico">
    <script src="/scripts/js/slider.js"></script>
    <script>
        function restrictYears(selectedYear, restrictedYears, direction) {
            let year = document.getElementById(selectedYear).value;
            let years = document.getElementById(restrictedYears);
            let children = years.children;

            for (let i = 0; i < children.length; i++) {
                if (children[i].textContent * direction > year * direction) children[i].classList.add("hide");
                else children[i].classList.remove("hide");
            }
        }
    </script>
</head>

<body>
<?php
include('include/header.php');

$databaseManager = new DatabaseManager();
$objectHelper = new ObjectHelper();

$min = $databaseManager->getRating("min");

function setYears(DatabaseManager $db)
{
    $years = $db->getYearsRange();

    for ($i = 0; $i < count($years); ++$i) {
        $year = $years[$i];
        echo "<option value='$year'>$year</option>";
    }
}

?>
<article class="page">
    <div class="container">
        <div>
            <h2 class='text__header' style="margin-left: 13%;">Популярные фильмы</h2>
            <div class='slider'>

                <button class='slider__button' onclick="plusSlides(-1)" style="margin-right: 5px">
                    <svg viewBox="0 0 17 49" style="transform: rotate(-180deg)">
                        <path d="M14.5824 24.2177L0.169802 1.64078C-0.133787 1.16522 -0.0203484 0.520408 0.43077 0.190628C0.896295 -0.149684 1.53586 -0.0208369 1.84885 0.469445L17 24.2034L1.85515 48.5205C1.54761 49.0143 0.909647 49.151 0.440354 48.8163C-0.0145324 48.4918 -0.134893 47.8483 0.163502 47.3692L14.5824 24.2177Z"></path>
                    </svg>
                </button>

                <div class="slider__container">
                    <?php
                    //Блок популярных фильмов
                    $popularFilms = $databaseManager->getPopularFilms(12);
                    for ($i = 1; $i <= count($popularFilms); ++$i) {
                        $film = $popularFilms[$i - 1];
                        $objectHelper->createFilm($film->getFilmId(), $film->getTitle(), $film->getPremiered(), $film->getGenres());
                    }
                    ?>
                </div>

                <button class='slider__button' onclick="plusSlides(1)" style="margin-left: 5px;">
                    <svg viewBox="0 0 17 49">
                        <path d="M14.5824 24.2177L0.169802 1.64078C-0.133787 1.16522 -0.0203484 0.520408 0.43077 0.190628C0.896295 -0.149684 1.53586 -0.0208369 1.84885 0.469445L17 24.2034L1.85515 48.5205C1.54761 49.0143 0.909647 49.151 0.440354 48.8163C-0.0145324 48.4918 -0.134893 47.8483 0.163502 47.3692L14.5824 24.2177Z"></path>
                    </svg>
                </button>

            </div>
        </div>

        <div class="mobile-filters">
            <h2 class="text__header">Фильтр фильмов</h2>
        </div>

        <div style="display: flex; flex-direction: column; justify-content: center">
            <h2 class='text__header'>Фильмы 2020 года</h2>
            <div class="films-content">
                <div class="films-table">
                    <div class="films-container">
                        <?php
                        //Список 2020 года
                        $films2020 = $databaseManager->getFilmsByYear(2020, 24);
                        for ($i = 0; $i < count($films2020); ++$i) {
                            $film = $films2020[$i];
                            $objectHelper->createFilm($film->getFilmId(), $film->getTitle(), $film->getPremiered(), $film->getGenres());
                        }
                        ?>
                    </div>
                </div>
                <div class="right-block">
                    <div class="films-filters">
                        <form action="list/filter" method="GET">
                            <div class="filter center">
                                <select size="1" name="genre">
                                    <option disabled selected>Жанр</option>
                                    <?
                                    $genres = $databaseManager->getGenres();

                                    for ($i = 0; $i < count($genres); ++$i) {
                                        $genre_name = $genres[$i]->getGenreName();
                                        $genre = $genres[$i]->getGenre();
                                        echo "<option value='$genre_name'>$genre</option>";
                                    }
                                    ?>
                                </select>
                                <select size="1" name="country">
                                    <option disabled selected>Страна</option>
                                    <?
                                    $countries = $databaseManager->getCountries();

                                    for ($i = 0; $i < count($countries); ++$i) {
                                        $countryId = $countries[$i]->getCountryId();
                                        $country = $countries[$i]->getCountry();
                                        echo "<option value='$countryId'>$country</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="filter center">
                                <select id="from" size="1" name="from" onchange="restrictYears('from', 'to', -1)">
                                    <option disabled selected>Год от</option>
                                    <? setYears($databaseManager); ?>
                                </select>
                                <select id="to" size="1" name="to" onchange="restrictYears('to', 'from', 1)">
                                    <option disabled selected>до</option>
                                    <? setYears($databaseManager); ?>
                                </select>
                            </div>
                            <ul class="filter">
                                <li>
                                    <label>
                                        <input type="radio" name="sort" value="rating" checked>
                                        По рейтингу
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="sort" value="votes">
                                        По голосам
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="sort" value="year">
                                        По году
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="sort" value="abc">
                                        По алфавиту
                                    </label>
                                </li>
                            </ul>
                            <div class="filter-range">
                                <label> Минимальный рейтинг <b id="minRating"><? echo $min ?></b></label>
                                <input name="min" id="rangeSlider" type="range" min="<? echo $min ?>" max="10"
                                       value="<? echo $min ?>" step="0.1"
                                       oninput="document.getElementById('minRating').textContent = document.getElementById('rangeSlider').value;">
                                <div style="display: flex; justify-content: space-between; padding: 5px 4px 5px 4px">
                                    <span><? echo $min ?></span>
                                    <span>
                                        <?
                                        echo ((10 - $min) / 2) + $min;
                                        ?>
                                    </span>
                                    <span>10</span>
                                </div>
                            </div>
                            <button class="filer-button">Найти</button>
                        </form>
                    </div>
                    <div class="films-comments">
                        <h2>Последние комментарии</h2>
                        <div>
                            <?php

                            $comments = $databaseManager->getLastComments();

                            for ($i = 0; $i < count($comments); ++$i) {
                                $user = $databaseManager->getUserByUserId($comments[$i]->getUserId());
                                $username = $user->getUsername();
                                $avatar = $user->getAvatar();

                                $timestamp = $comments[$i]->getTimestamp();
                                $text = $comments[$i]->getComment();
                                $filmId = $comments[$i]->getFilmId();

                                $film = $databaseManager->getFilmByFilmId($filmId, true);
                                $filmName = $film->getTitle();


                                echo "<div class='comment' style=''>
                                    <div class='comment-avatar'>
                                        <img src='$avatar' alt='$username'/>
                                    </div>
                                    <div class='comment-inside'>
                                       <div class='comment-header'> 
                                       <span> <b>$username</b>, оставлен $timestamp на <a href='/film?id=$filmId'>$filmName</a></span></div>
                                       <span>
                                         $text
                                       </span>
                                    </div>
                                </div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
<script>
    sliderInit(true)
    let isMoved = false;

    function onResize() {
        reportWindowSize();

        let filters = document.querySelectorAll(".films-filters");
        let widthOfScreen = document.body.offsetWidth;

        if (widthOfScreen < 720) {
            let filtersContainer = document.getElementsByClassName("mobile-filters")[0];
            filtersContainer.appendChild(filters[0]);
            isMoved = true;
        } else if (isMoved) {
            let filtersContainer = document.getElementsByClassName("films-comments")[0];
            filtersContainer.before(filters[0]);
            isMoved = false;
        }
    }

    onResize();
    window.onresize = onResize;
</script>
<?php
include('include/footer.php');
?>
</body>

</html>