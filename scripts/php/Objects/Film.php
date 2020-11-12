<?php

class Film
{
    private string $film_id, $title, $rating, $votes, $runtime_minutes, $premiered, $plot, $isAdult;
    private array $genres;

    function __construct($film_id, $title, $isAdult, $premiered, $runtime_minutes, $genres, $plot, $rating, $votes)
    {
        $this->film_id = $film_id;
        $this->title = $title;
        $this->rating = $rating;
        $this->votes = $votes;
        $this->runtime_minutes = $runtime_minutes;
        $this->premiered = $premiered;

        if ($isAdult == 1)  $this->isAdult = "18+";
        else  $this->isAdult = "";

        $this->genres = preg_split('/,/', $genres, -1);;
        $this->plot = $plot;
    }

    /**
     * @return string
     */
    public function getFilmId(): string
    {
        return $this->film_id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getRating(): string
    {
        return $this->rating;
    }

    /**
     * @return string
     */
    public function getVotes(): string
    {
        return $this->votes;
    }

    /**
     * @return string
     */
    public function getRuntimeMinutes(): string
    {
        return $this->runtime_minutes;
    }

    /**
     * @return string
     */
    public function getPremiered(): string
    {
        return $this->premiered;
    }

    /**
     * @return string
     */
    public function getPlot(): string
    {
        return $this->plot;
    }

    /**
     * @return string
     */
    public function getIsAdult(): string
    {
        return $this->isAdult;
    }

    /**
     * @return array|false|string[]
     */
    public function getGenres()
    {
        return $this->genres;
    }


}