<?php

class Auth extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function registerUser($username, $password, $email)
    {
        $getQuery = "SELECT * 
                FROM users
                WHERE username='$username'";

        $result = mysqli_query($this->connection, $getQuery) or die("Ошибка " . mysqli_error($this->connection));

        if (mysqli_num_rows($result) == 0) {
            $insertQuery = "INSERT INTO users
              (username, password, email)
                VALUES('$username', '$password', '$email')";

            $result = mysqli_query($this->connection, $insertQuery) or die("Ошибка " . mysqli_error($this->connection));
            if (!$result) $error = "Запрос не выполнен. Повторите позже.";
        } else {
            $error = "Такой пользователь уже существует!";
        }

        return $error;
    }

    public function loginUser($username, $password)
    {
        $getQuery = "SELECT * 
                FROM users
                WHERE username='$username' AND password='$password'";

        $result = mysqli_query($this->connection, $getQuery) or die("Ошибка " . mysqli_error($this->connection));

        if (mysqli_num_rows($result) == 0) $error = "Пользователь не найден!";
        return $error;
    }

}