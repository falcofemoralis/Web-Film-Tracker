<?php

require_once("lib/simple_html_dom.php");

ini_set('max_execution_time', 1400000);

$host = 'localhost'; // адрес сервера
$database = 'filmsdb'; // имя базы данных
$user = 'root'; // имя пользователя
$password = 'root'; // пароль

$connection = mysqli_connect($host, $user, $password, $database)
or die("Ошибка подключения к базе" . mysqli_error($connection));

$query = "SELECT films_translated.title, films_translated.title_id
            FROM films 
            INNER JOIN films_translated ON films.title_id=films_translated.title_id 
            WHERE films_translated.lang_id = 3 AND films.country = '0'";

$result = mysqli_query($connection, $query) or die("Ошибка " . mysqli_error($connection));

for ($i = 0; $i < mysqli_num_rows($result); ++$i) {
    $row = mysqli_fetch_row($result);

    $name = str_replace(" ", "+", $row[0]);
    $filmId = $row[1];

    $toFind = "трейлер+" . $name;
    $toFind = rawurlencode($toFind);
    $search = "https://www.youtube.com/results?search_query=" . $toFind;
    $html = file_get_html($search);

    $findme = '/watch?v=';
    $pos = strpos($html, $findme);

    $res = substr($html, $pos, 100);
    $link = explode("\"", $res);
    $id = explode("=", $link[0]);

    $ytId = $id[1];

    $insertQuery = "UPDATE films SET trailerId='$ytId' WHERE films.title_id='$filmId'";

    try {
        mysqli_query($connection, $insertQuery);
    } catch (Exception $e) {
        echo $e;
    }

    if ($i % 500 == 0) sleep(3);
}