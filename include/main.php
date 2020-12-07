<!DOCTYPE html>
<html lang="en">
<?
global $isAuthed;
$database = new Database();
$objectHelper = new ObjectHelper();
$filmList = new FilmsList();

$min = $database->getRating("min");

function setYears($filmList)
{
    $years = $filmList->getYearsRange();

    for ($i = 0; $i < count($years); ++$i) {
        $year = $years[$i];
        echo "<option value='$year'>$year</option>";
    }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmsTracker - Трекер информации про фильмы</title>
    <meta name="author" content="FilmsTracker">
    <meta name="description" content="Сайт поиска информации про фильмы">
    <meta name="keywords" content="трекер фильмов, лучший трекер фильмов, бесплатный трекер фильмов, кинопоиск, imdb, кинопоиск hd,
     кинопоиск ютуб, кинопоиск топ, гидонлайн кинопоиск, рейтинг imdb, рейтинг фильмов imdb, топ фильмов imdb, в ролях актеры, дата выхода, рейтинги imdb">
    <meta name="language" content="ru">

    <link rel='stylesheet' href="/CSS/elements.css">
    <link rel='stylesheet' href="/CSS/main.css">
    <link rel='stylesheet' href="/CSS/slider.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="icon" href="/images/favicon.ico">
    <script src="/scripts/js/slider.js"></script>
    <script src="/scripts/js/main.js"></script>
    <script src="/scripts/js/comment.js"></script>
</head>
<body>
<?
include('include/header.php');
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
                    <?
                    //Блок популярных фильмов
                    $popularFilms = $database->getPopularFilms(12);
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
                        <?
                        //Список 2020 года
                        $films2020 = $database->getFilmsByYear(2020, 24);
                        for ($i = 0; $i < count($films2020); ++$i) {
                            $film = $films2020[$i];
                            $objectHelper->createFilm($film->getFilmId(), $film->getTitle(), $film->getPremiered(), $film->getGenres());
                        }
                        ?>
                    </div>
                </div>
                <div class="right-block">
                    <div class="films-filters">
                        <form action="/list/filter" method="GET" id="form-filters">
                            <div class="filter center">
                                <select size="1" name="genre" onchange="findFilmsByFilters()">
                                    <option disabled selected>Жанр</option>
                                    <?
                                    $genres = $database->getGenres();

                                    for ($i = 0; $i < count($genres); ++$i) {
                                        $genre_name = $genres[$i]->getGenreName();
                                        $genre = $genres[$i]->getGenre();
                                        echo "<option value='$genre_name'>$genre</option>";
                                    }
                                    ?>
                                </select>
                                <select size="1" name="country" onchange="findFilmsByFilters()">
                                    <option disabled selected>Страна</option>
                                    <?
                                    $countries = $filmList->getCountries();

                                    for ($i = 0; $i < count($countries); ++$i) {
                                        $countryId = $countries[$i]->getCountryId();
                                        $country = $countries[$i]->getCountry();
                                        echo "<option value='$countryId'>$country</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="filter center">
                                <select id="from" size="1" name="from"
                                        onchange="restrictYears('from', 'to', -1); findFilmsByFilters()">
                                    <option disabled selected>Год от</option>
                                    <? setYears($filmList); ?>
                                </select>
                                <select id="to" size="1" name="to"
                                        onchange="restrictYears('to', 'from', 1); findFilmsByFilters()">
                                    <option disabled selected>до</option>
                                    <? setYears($filmList); ?>
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
                                       oninput="document.getElementById('minRating').textContent = document.getElementById('rangeSlider').value; findFilmsByFilters()">
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
                        <div id="comments-block">
                            <?
                            $commentsObj = new Comments();
                            $comments = $commentsObj->getLastComments();
                            for ($i = 0; $i < count($comments); ++$i)
                                $objectHelper->createComment($commentsObj->getUserByUserId($comments[$i]->getUserId()), $comments[$i], $i, false, true);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
<script>
    sliderInit(true);
    initFiltersResize();
    initComments();
</script>
<?
include('include/footer.php');
?>
</body>
</html>