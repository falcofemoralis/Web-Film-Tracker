<!DOCTYPE html>
<html lang="en">

<?php
require_once 'scripts/php/Objects/Actor.php';
require_once 'scripts/php/Objects/Film.php';
require_once 'scripts/php/Managers/DatabaseManager.php';
require_once 'scripts/php/Managers/ObjectHelper.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Сайт поиска информации про фильмы">
    <meta name="author" content="Иващенко Владислав Романович">
    <title>Трекер фильмов</title>
    <link rel="icon" href="./images/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel='stylesheet' href="./CSS//actor.css">
    <link rel='stylesheet' href="./CSS//elements.css">
    <link rel='stylesheet' href="./CSS//slider.css">
    <script src="./scripts/js/slider.js"></script>
</head>

<body>

<?php
include('include/header.php');

$databaseManager = new DatabaseManager();
$objectHelper = new ObjectHelper();

$personId = $_GET["id"];
$actor = $databaseManager->getActorById($personId);

$name = $actor->getName();
$born = $actor->getBorn();
$died = $actor->getDied();

$films = $databaseManager->getFilmsByPersonId($personId);
$sortedFilms = array(array());
$sortedFilmsHeaders = array("Актер", " Режиссер", "Продюсер", "Сценарист");

for ($i = 0; $i < count($films); $i++) {
    $roles = $databaseManager->getRoleByPersonAndTitleId($personId, $films[$i]->getFilmId());

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
        <section class="actor-container">
            <h1 class='actor_name'><? echo "$name" ?></h1>
            <div class='actor-main'>
                <img class='actor-main__photo' src='./images/photos/<? echo "$personId" ?>.jpeg' alt='photo'>
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
                                        echo "($age лет)";
                                    }
                                } else {
                                    echo "Неизвестно";
                                }
                                ?>
                            </td>
                        </tr>
                        <?

                        if ($died != 0) {
                            $age = $died - $born;
                            echo " <tr>
                            <td>
                                <B>Дата смерти:</B>
                            </td>
                            <td>
                                 $died г. (в возрасте $age лет)
                            </td>
                        </tr>";
                        }
                        ?>

                    </table>
                </div>
            </div>
        </section>

        <section class="section">
            <?
            for ($i = 0; $i < 4; $i++) {
                if ($sortedFilms[$i] != null) {
                    $title = $sortedFilmsHeaders[$i];
                    echo "<h2 class='section__title'>$title</h2>
                          <div class='films-table'>";

                    for ($j = 0; $j < count($sortedFilms[$i]); $j++) {
                        echo "<div class='content__inline'>";
                        $film = $sortedFilms[$i][$j];
                        $objectHelper->createFilm($film->getFilmId(), $film->getTitle(), $film->getPremiered(), $film->getGenres());
                        echo "</div>";
                    }

                    echo "</div>";
                }
            }
            ?>
        </section>
    </div>
</article>
<?php
include('include/footer.php');
?>
</body>
</html>