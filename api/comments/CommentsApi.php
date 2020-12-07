<?php

require_once 'scripts/php/Helpers/ObjectHelper.php';
require_once 'api/Api.php';
require_once 'api/Database.php';
require_once 'api/comments/Comments.php';

class CommentsApi extends Api
{
    // POST - Добавление в базу новых данных
    protected function createAction()
    {
        $comment = htmlspecialchars($_POST['comment']);
        $filmId = $_POST['filmId'];
        echo (new Comments())->addComment($comment, $filmId, $_COOKIE['username'], time());;
    }

    // PUT - Обновление данных
    protected function updateAction()
    {
        echo "invalid method";
    }

    // GET - Просмотр данных
    protected function viewAction()
    {
        $objectHelper = new ObjectHelper();
        $comments = new Comments();
        $user = $comments->getUserByUserId($comments->getUserId($_COOKIE['username']));
        $comment = new Comment($_GET['filmId'], time(), $_GET['comment'], $user->getUserId());
        $objectHelper->createComment($user, $comment, $_GET['id'], true, false);
    }

    // DELETE - Удаление данных
    protected function deleteAction()
    {
        $filmId = array_shift($this->requestUri);
        $time = array_shift($this->requestUri);
        echo (new Comments())->deleteComment($filmId, $_COOKIE['username'], $time);;
    }
}

