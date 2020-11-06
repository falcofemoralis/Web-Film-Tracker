<?php
function createFilm($filmId, $title, $year, $genres)
{
    echo "<div>
        <img class='films-row__poster' src='./images/posters/$filmId.jpeg' alt='poster'>
        <h1>$title</h1>
        $year, ";

    for ($i = 0; $i < count($genres); ++$i) {
        echo "$genres[$i]";
    }
    echo  "</div>";
}