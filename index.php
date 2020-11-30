<?php

require_once 'api/Api.php';

$requestUri = explode('/', stristr($_SERVER['REQUEST_URI'] . '?', '?', true));
array_shift($requestUri); //т.к 1 элемент пустой, поэтому сдигаем

$arg = array_shift($requestUri);
if(!empty ($arg)){
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
        case "exit":
            setcookie("username", "", time() - 3600 * 24 * 365);
            header('location: /');
            break;
        default:
            include("include/".$arg.".php");
    }
}else{
    include('include/main.php');
}
