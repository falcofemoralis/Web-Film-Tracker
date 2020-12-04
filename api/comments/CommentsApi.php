<?php

require_once 'scripts/php/Managers/DatabaseManager.php';
require_once 'api/Api.php';

class CommentsApi extends Api
{
    // POST - Добавление в базу новых данных
    protected function createAction()
    {
        $comment = htmlspecialchars($_POST['comment']);
        $filmId = $_POST['filmId'];

        $databaseManager = new DatabaseManager();
        $error = $databaseManager->addComment($comment, $filmId, $_COOKIE['username'], time());
        echo $error;
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
        $filmId = array_shift($this->requestUri);
        $time = array_shift($this->requestUri);

        $databaseManager = new DatabaseManager();
        $error = $databaseManager->deleteComment($filmId, $_COOKIE['username'], $time);
        echo $error;
    }
}

