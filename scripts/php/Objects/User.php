<?php

class User
{
    private int $userId;
    private string $username, $email, $password;

    /**
     * User constructor.
     * @param int $userId
     * @param string $username
     * @param string $email
     * @param string $password
     */
    public function __construct(int $userId, string $username, string $email, string $password)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
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

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}