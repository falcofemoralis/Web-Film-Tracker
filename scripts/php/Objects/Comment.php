<?php

class Comment
{
    private string $filmId, $time, $comment, $timestamp;
    private int $userId;

    public function __construct(string $filmId, $time, string $comment, int $userId)
    {
        $this->filmId = $filmId;
        $this->time = $time;
        $timezone = 2;
        $this->timestamp = gmdate('Y-m-d H:i:s', $time + 3600 * ($timezone + date("I")));
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

    /**
     * @return false|string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}
