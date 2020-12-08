<?php

class ObjectHelper
{
    public Database $databaseManager;

    function __construct()
    {
        $this->databaseManager = new Database();
    }

    function createFilm($filmId, $title, $year, $genres)
    {
        $poster = "./images/posters/$filmId.jpeg";
        if (!file_exists($poster)) $poster = "/images/posters/noimage_poster.jpeg";
        else $poster = "/images/posters/$filmId.jpeg";

        echo "
        <div class='film'>
            <a href='/film?id=$filmId'>
            <img src='$poster' alt='poster'>
            <p><b class='film-title'>$title</b></p>
            <div>$year, ";

        for ($i = 0; $i < count($genres) - 1; $i++) {
            $genreObj = $this->databaseManager->getGenreById($genres[$i]);
            $genre = $genreObj->getGenre();
            echo "$genre";

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
                <h2>$name</h2>";

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

    function createComment(User $user, Comment $comment, $i, $isDeletable, $isShowFilm)
    {
        $username = $user->getUsername();
        $avatar = $user->getAvatar();
        $timestamp = $comment->getTimestamp();
        $filmId = $comment->getFilmId();
        $text = $comment->getComment();
        $time = $comment->getTime();

        if ($isShowFilm) {
            $databaseManager = new Database();
            $filmName = $databaseManager->getFilmByFilmId($filmId, true)->getTitle();
            $additional = "на <a href='/film?id=$filmId'>$filmName</a>";
        }

        if ($isDeletable)
            $btn = "<button class='comment-button delete' onclick='deleteComment(\"$filmId\", \"$time\" ,\"comment_$i\")'>×</button>";

        echo "
           <div class='comment' id='comment_$i'>
               <div class='comment-avatar'>
                    <img src='$avatar' alt='$username'/>
               </div>
               <div style='width: 100%'> 
                   <div class='comment-inside' id='commentInside_$i'>
                        <div class='comment-header'>
                            <span><b>$username</b>, оставлен $timestamp $additional</span>" . $btn . "
                        </div>
                        <span id='commentText_$i'>
                            $text
                        </span>              
                   </div>
                    <button class='comment-more' id='commentReveal_$i' onclick='revealComment(\"commentInside_$i\",\"commentText_$i\")'>Читать полностью</button>
               </div>
           </div>";
    }
}