<?php

class Film
{
    private string $film_id, $title, $rating, $votes, $runtime_minutes, $premiered, $plot, $isAdult;
    private $genres;

    function __construct($film_id, $title, $isAdult, $premiered, $runtime_minutes, $genres, $plot, $rating, $votes)
    {
        $this->film_id = $film_id;
        $this->title = $title;
        $this->rating = $rating;
        $this->votes = $votes;
        $this->runtime_minutes = $runtime_minutes;
        $this->premiered = $premiered;
        $this->isAdult = $isAdult;
        $this->genres = preg_split('/,/', $genres, -1);;
        $this->plot = $plot;
    }

    function getFilmId()
    {
        return $this->film_id;
    }

    function getTitle()
    {
        return $this->title;
    }

    function getPremiered()
    {
        return $this->premiered;
    }

    function getGenres()
    {
        return $this->genres;
    }
}