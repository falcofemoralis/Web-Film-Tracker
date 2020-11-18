<?php

class User
{
    private int $userId;
    private string $username, $email;

    /**
     * User constructor.
     * @param int $userId
     * @param string $username
     * @param string $email
     */
    public function __construct(int $userId, string $username, string $email)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }




}