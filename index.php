<?
require_once 'scripts/php/Helpers/ObjectHelper.php';
require_once 'scripts/php/Objects/Film.php';

require_once 'api/Database.php';
require_once 'api/auth/Auth.php';
require_once 'api/bookmarks/Bookmarks.php';
require_once 'api/comments/Comments.php';
require_once 'api/list/FilmsList.php';

require_once 'scripts/php/Objects/Actor.php';
require_once 'scripts/php/Objects/Comment.php';
require_once 'scripts/php/Objects/Country.php';
require_once 'scripts/php/Objects/Film.php';
require_once 'scripts/php/Objects/Genre.php';
require_once 'scripts/php/Objects/User.php';

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
    case "list":
        include 'api/list/FilmsListApi.php';

        if (empty((new Auth)->loginUser($_COOKIE['username'], $_COOKIE['password']))) $isAuthed = true;
        else $isAuthed = false;

        $listApi = new FilmsListApi($requestUri);
        $listApi->run();
        break;
    case "random":
        $databaseManager = new Database();
        $films = (new FilmsList())->getFilmsIdsBySearch("");
        $filmKey = array_rand($films, 1);
        $filmId = $films[$filmKey];
        $url = "location: /film?id=$filmId";
        header($url);
        break;
    case "checkAvatar":
        echo User::checkAvatar($_GET['username']);
        break;
    case "exit":
        setcookie("username", "", time() - 3600 * 24 * 365);
        header('location: /');
        break;
    default:
        if (empty((new Auth)->loginUser($_COOKIE['username'], $_COOKIE['password']))) $isAuthed = true;
        else $isAuthed = false;

        if (empty ($arg)) $arg = "main";
        include("include/" . $arg . ".php");
}
