<?php

class Genre{
    private int $genre_id;
    private string $genre_name, $genre;

    /**
     * genre constructor.
     * @param int $genre_id
     * @param string $genre_name
     * @param string $genre
     */
    public function __construct(int $genre_id, string $genre_name, string $genre)
    {
        $this->genre_id = $genre_id;
        $this->genre_name = $genre_name;
        $this->genre = $genre;
    }

    /**
     * @return int
     */
    public function getGenreId(): int
    {
        return $this->genre_id;
    }

    /**
     * @return string
     */
    public function getGenreName(): string
    {
        return $this->genre_name;
    }

    /**
     * @return string
     */
    public function getGenre(): string
    {
        return $this->genre;
    }




}