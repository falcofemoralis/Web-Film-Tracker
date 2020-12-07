<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Сайт поиска информации про фильмы">
    <meta name="author" content="Иващенко Владислав Романович">
    <title>Трекер фильмов</title>
    <link rel='stylesheet' href="/CSS/elements.css">
    <link rel='stylesheet' href="/CSS/list.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="icon" href="/images/favicon.ico">
</head>

<body>
<?
include('include/header.php');

$database = new Database();
$objectHelper = new ObjectHelper();
$filmsPerPage = 24; //кол-во отображаемых фильмов на странице
$pages = intval($filmsAmount / $filmsPerPage) + 1; // кол-во страниц
?>

<article>
    <div class="container">
        <div>
            <? echo "<h2 class='text__header'>$filmsHeader</h2>"; ?>
            <div class="films-table">
                <div class="films-container">
                    <?php
                    for ($i = $filmsPerPage * ($cur_page - 1); $i < $filmsPerPage * $cur_page; $i++) {
                        if ($i == $filmsAmount) break;

                        $film = $database->getFilmByFilmId($filmsIDs[$i], true);

                        if ($film != null) {
                            $name = $film->getTitle();
                            $objectHelper->createFilm($film->getFilmId(), $film->getTitle(), $film->getPremiered(), $film->getGenres());
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?
        if ($filmsAmount == 0) echo "По вашему запросу ничего не найдено.";
        else createPagesControls($pages, $cur_page, $link);
        ?>
    </div>
</article>
<?
include('include/footer.php');
?>
</body>
</html>