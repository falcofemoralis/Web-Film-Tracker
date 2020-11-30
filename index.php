<?php

require_once 'api/Api.php';

$requestUri = explode('/', stristr($_SERVER['REQUEST_URI'] . '?', '?', true));
array_shift($requestUri); //т.к 1 элемент пустой, поэтому сдигаем

$arg = array_shift($requestUri);
switch ($arg) {
    case "comments":
        include 'api/comments/CommentsApi.php';
        $commentsApi = new CommentsApi($requestUri);
        $commentsApi->run();
        break;
    case "bookmarks":
        include 'api/bookmarks/BookmarksApi.php';
        $bookmarksApi = new BookmarksApi($requestUri);
        $bookmarksApi->run();
        break;
    case "auth":
        include 'api/auth/AuthApi.php';
        $authApi = new AuthApi($requestUri);
        $authApi->run();
        break;
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
        include('include/registration.php');
        break;
    case "login":
        include('include/login.php');
        break;
    case "user":
        include('include/user.php');
        break;
    case "userBookmarks":
        include('include/bookmarks.php');
        break;
    case "exit":
        setcookie("username", "", time() - 3600 * 24 * 365);
        header('location: /');
        break;
    default:
        include('include/main.php');
}

