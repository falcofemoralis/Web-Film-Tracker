<?php

require_once 'api/Api.php';
require_once 'api/Database.php';
require_once 'api/bookmarks/Bookmarks.php';

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
        $bookmarks = new Bookmarks();
        $bookmarks->addToBookmarks($filmId, $bookmarks->getUserId($_COOKIE['username']));
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
        $bookmarks = new Bookmarks();
        $bookmarks->removeFromBookmarks($filmId, $bookmarks->getUserId($_COOKIE['username']));
    }
}

