<?php

class User
{
    private int $userId;
    private string $username, $email, $password, $avatar;

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
        $this->avatar = User::checkAvatar($username);
        $this->password = $password;

    }

    public static function checkAvatar($username)
    {
        $username = str_replace(" ", "_", $username);
        $avatar = "images/avatars/" . $username . ".png";
        if (!file_exists($avatar)) $avatar = "/images/avatar.jpeg";
        return $avatar;
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

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }
}