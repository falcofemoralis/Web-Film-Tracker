<?php

require_once 'scripts/php/Managers/DatabaseManager.php';
require_once 'api/Api.php';

class BookmarksApi extends Api
{
    // POST - Добавление в базу новых данных
    protected function createAction()
    {
        echo "invalid method";
    }

    // PUT - Обновление данных
    protected function updateAction()
    {
        $filmId = array_shift($this->requestUri);

        $databaseManager = new DatabaseManager();
        $databaseManager->addToBookmarks($filmId, $databaseManager->getUserId($_COOKIE['username']));
    }

    // GET - Просмотр данных
    protected function viewAction()
    {
        echo "invalid method";
    }

    // DELETE - Удаление данных
    protected function deleteAction()
    {
        $filmId = array_shift($this->requestUri);

        $databaseManager = new DatabaseManager();
        $databaseManager->removeFromBookmarks($filmId, $databaseManager->getUserId($_COOKIE['username']));
    }
}

