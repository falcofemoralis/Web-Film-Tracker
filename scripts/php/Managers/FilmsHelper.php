<?php
require_once 'scripts/php/Managers/DatabaseManager.php';

class FilmsHelper
{
    public DatabaseManager $databaseManager;

    function __construct()
    {
        $this->databaseManager = new DatabaseManager();
    }

    function createFilm($filmId, $title, $year, $genres)
    {
        echo "<div class='film'> 
        <img src='./images/posters/$filmId.jpeg' alt='poster'>
        <div class='film__info'>
        <h1>$title</h1>
         $year, ";

        for ($i = 0; $i < count($genres) - 1; $i++) {
            $genre = $this->databaseManager->getGenreById($genres[$i]);
            echo "$genre";

            if ($i != count($genres) - 2) echo ", ";
        }

        echo "</div></div>";
    }
}