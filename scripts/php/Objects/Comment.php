<?php

class Comment
{
    private string $filmId, $time, $comment;
    private int $userId;

    public function __construct(string $filmId, string $time, string $comment, int $userId)
    {
        $this->filmId = $filmId;
        $this->time = $time;
        $this->comment = $comment;
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getFilmId(): string
    {
        return $this->filmId;
    }

    /**
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }


}
