<?php

require_once 'scripts/php/Managers/DatabaseManager.php';

$requestUri = explode('/', stristr($_SERVER['REQUEST_URI'] . '?', '?', true));
array_shift($requestUri); //т.к 1 элемент пустой, поэтому сдигаем

//array_shift отдает 0 значений и сдигет вправо
array_shift($requestUri); //выдаст название сайта

$arg = array_shift($requestUri);
switch ($arg) {
    case "films":
        include('include/film.php');
        break;
    case "list":
        include('include/list.php');
        break;
    case "actors":
        include('include/actor.php');
        break;
    case "register":
        if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['email'])) {
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
            $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
            $password = md5($password . "asdfhgewq123");

            $databaseManager = new DatabaseManager();
            $message = $databaseManager->registerUser($username, $password, $email);

            if (!empty($message)) {
                echo "Ошибка: $message";
                include('include/registration.php');
            } else {
                include('include/main.php');
            }
        } else {
            include('include/registration.php');
        }

        break;
    default:
        include('include/main.php');
}
