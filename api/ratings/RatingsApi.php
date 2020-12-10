<?php

require_once 'api/Api.php';
require_once 'api/Database.php';
require_once 'api/ratings/Ratings.php';

class RatingsApi extends Api
{
    // POST - Добавление в базу новых данных
    protected function createAction()
    {
        $ratings = new Ratings();
        $userId = $ratings->getUserId($_COOKIE['username']);
        $ratings->addRating($_POST['filmId'], $userId, $_POST['rating']);
    }

    // PUT - Обновление данных
    protected function updateAction()
    {
        echo "invalid method";
    }

    // GET - Просмотр данных
    protected function viewAction()
    {
        $ratings = new Ratings();
        $rating = $ratings->getTrackerRating($_GET['filmId']);
        echo $rating[0] . " " . $rating[1];
    }

    // DELETE - Удаление данных
    protected function deleteAction()
    {
        echo "invalid method";
    }
}