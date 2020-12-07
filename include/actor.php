<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Сайт поиска информации про фильмы">
    <meta name="author" content="Иващенко Владислав Романович">
    <title>Трекер фильмов</title>
    <link rel="icon" href="/images/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel='stylesheet' href="/CSS/actor.css">
    <link rel='stylesheet' href="/CSS/elements.css">
</head>

<body>
<?
include('include/header.php');

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

<article class="page">
    <div class="container">
        <div class="actor-container">
            <h2 class='actor_name'><? echo "$name" ?></h2>
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