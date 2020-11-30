<?php
require_once 'scripts/php/Managers/DatabaseManager.php';

class ObjectHelper
{
    public DatabaseManager $databaseManager;

    function __construct()
    {
        $this->databaseManager = new DatabaseManager();
    }

    function createFilm($filmId, $title, $year, $genres)
    {
        $poster = "./images/posters/$filmId.jpeg";
        if (!file_exists($poster)) $poster = "/images/posters/noimage_poster.jpeg";

        echo "
        <div class='film'>
            <a href='film?id=$filmId'>
            <img src='$poster' alt='poster'>
            <p><b class='film-title'>$title</b></p>
            <div>$year, ";

        for ($i = 0; $i < count($genres) - 1; $i++) {
            $genre = $this->databaseManager->getGenreById($genres[$i]);
            $genreName = $genre[0];
            echo "$genreName";

            if ($i != count($genres) - 2) echo ", ";
        }
        echo "</div></a></div>";
    }

    function createActor($actorId, $name, $characters, $category)
    {
        $photo = "./images/photos/$actorId.jpeg";
        if (!file_exists($photo)) $photo = "/images/photos/noimage_photo.jpeg";

        echo "
        <a class='actor' href='actor?id=$actorId'>
            <img src='$photo' alt='photo'>
            <div class='actor__info'>
                <h1>$name</h1>";

        if ($characters[0] === "") {
            echo "$category";
        } else {
            for ($i = 0; $i < count($characters); $i++) {
                echo "$characters[$i]";
                if ($i != count($characters) - 1) echo ", ";
            }
        }

        echo "</div></a>";
    }
}