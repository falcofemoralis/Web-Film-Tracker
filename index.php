<?php
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
    default:
        include('include/main.php');
}
