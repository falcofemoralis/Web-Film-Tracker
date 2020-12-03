<?php

require_once 'scripts/php/Managers/DatabaseManager.php';
require_once 'api/Api.php';

class BookmarksApi extends Api
{
    // POST - Добавление в базу новых данных
    protected function createAction()
    {
        $filmId = $_POST['filmId'];
        $userId = $_POST['userId'];
        $isDelete = $_POST['delete'];

        $databaseManager = new DatabaseManager();
        if ($isDelete == "true")
            $databaseManager->removeFromBookmarks($filmId, $userId);
        else
            $databaseManager->addToBookmarks($filmId, $userId);

        $url = "location: /film?id=$filmId";
        header($url);
    }

    // PUT - Обновление данных
    protected function updateAction()
    {
        echo "invalid method";
    }

    // GET - Просмотр данных
    protected function viewAction()
    {
        echo "invalid method";
    }

    // DELETE - Удаление данных
    protected function deleteAction()
    {
        parse_str(file_get_contents("php://input"), $data);
        $filmId = $data['filmId'];
        $userId = $data['userId'];

        $databaseManager = new DatabaseManager();
        $databaseManager->removeFromBookmarks($filmId, $userId);
        $url = "location: /film?id=$filmId";
        header($url);
    }
}

