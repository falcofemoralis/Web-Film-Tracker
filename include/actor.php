<!DOCTYPE html>
<html lang="en">
<?
$database = new Database();
$objectHelper = new ObjectHelper();

$personId = $_GET["id"];
$actor = $database->getActorById($personId);

$name = $actor->getName();
$born = $actor->getBorn();
$died = $actor->getDied();

$films = $database->getFilmsByPersonId($personId);
$sortedFilms = array(array());
$sortedFilmsHeaders = array("Актер", " Режиссер", "Продюсер", "Сценарист");

for ($i = 0; $i < count($films); $i++) {
    $roles = $database->getRoleByPersonAndTitleId($personId, $films[$i]->getFilmId());

    for ($j = 0; $j < count($roles); $j++) {
        switch ($roles[$j]) {
            case "director":
                $sortedFilms[1][] = $films[$i];
                break;
            case "producer":
                $sortedFilms[2][] = $films[$i];
                break;
            case "writer":
                $sortedFilms[3][] = $films[$i];
                break;
            default:
                $sortedFilms[0][] = $films[$i];
        }
    }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><? echo $name?> - фильмы актера</title>
    <meta name="author" content="FilmsTracker">
    <meta name="description" content="<? echo $name?> - смотреть и читать информацию про актера в фильмах">
    <meta name="keywords" content="трекер фильмов, лучший трекер фильмов, бесплатный трекер фильмов, кинопоиск, imdb, кинопоиск hd,
     кинопоиск ютуб, кинопоиск топ, гидонлайн кинопоиск, рейтинг imdb, рейтинг фильмов imdb, топ фильмов imdb, в ролях актеры,
     дата выхода, рейтинги imdb, смотреть трейлер">
    <meta name="language" content="ru">
    <meta property="og:image" content="http://a0488451.xsph.ru/images/photos/<? echo $personId ?>.jpeg">

    <link rel="icon" href="/images/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel='stylesheet' href="/CSS/elements.css">
    <link rel='stylesheet' href="/CSS/actor.css">
</head>
<body>
<?
include('include/header.php');
?>
<article class="page">
    <div class="container">
        <div class="actor-container">
            <h1 class='actor_name'><? echo "$name" ?></h1>
            <div class='actor-main'>
                <?
                $photo = "images/photos/$personId.jpeg";
                if (!file_exists($photo)) $photo = "images/photos/noimage_photo.jpeg";
                ?>
                <img class='actor-main__photo' src='<? echo "$photo" ?>' alt='photo'>
                <div class='actor-main__info'>
                    <table>
                        <tr>
                            <td>
                                <B>Дата рождения:</B>
                            </td>
                            <td>
                                <?
                                if ($born != 0) {
                                    echo "$born г.";
                                    if ($died == 0) {
                                        $age = date('Y') - $born;
                                        echo " ($age лет)";
                                    }
                                } else {
                                    echo "Неизвестно";
                                }
                                ?>
                            </td>
                        </tr>
                        <? if ($died != 0) :$age = $died - $born; ?>
                            <tr>
                                <td>
                                    <b>Дата смерти:</b>
                                </td>
                                <td>
                                    <? echo " $died г. (в возрасте $age лет)" ?>
                                </td>
                            </tr>
                        <? endif; ?>
                    </table>
                </div>
            </div>
        </div>

        <div class="section">
            <?
            for ($i = 0; $i < 4; $i++) {
                if ($sortedFilms[$i] != null) {
                    $title = $sortedFilmsHeaders[$i];
                    echo "<h2 class='section__title'>$title</h2>
                            <div class='films-table__actor'>
                               <div class='films-container'>";

                    for ($j = 0; $j < count($sortedFilms[$i]); $j++) {
                        $film = $sortedFilms[$i][$j];
                        $objectHelper->createFilm($film->getFilmId(), $film->getTitle(), $film->getPremiered(), $film->getGenres());
                    }

                    echo "</div></div>";
                }
            }
            ?>
        </div>
    </div>
</article>
<?
include('include/footer.php');
?>
</body>
</html>