<?php

class Film
{
    private string $film_id, $title, $runtime_minutes, $premiered, $plot, $isAdult, $trailerId, $country_id;
    private array $genres;
    private int $rating, $votes;

    function __construct($film_id, $title, $isAdult, $premiered, $runtime_minutes, $genres, $plot, $rating, $votes, $trailerId, $country_id)
    {
        $this->film_id = $film_id;
        $this->title = $title;
        $this->rating = $rating;
        $this->votes = $votes;
        $this->runtime_minutes = $runtime_minutes;
        $this->premiered = $premiered;

        if ($isAdult == 1) $this->isAdult = "18+";
        else  $this->isAdult = "";

        $this->genres = $genres;
        $this->plot = $plot;
        $this->trailerId = $trailerId;
        $this->country_id = $country_id;
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
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @return int
     */
    public function getVotes(): int
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

    /**
     * @return string
     */
    public function getTrailerId(): string
    {
        return $this->trailerId;
    }

    /**
     * @return string
     */
    public function getCountryId(): string
    {
        return $this->country_id;
    }


}