<?php

require_once 'scripts/php/Managers/DatabaseManager.php';
require_once 'api/Api.php';

class AuthApi extends Api
{
    private function saveUser($isSave, $page, $username, $password, $error)
    {
        //если была ошибка, то показываем регистрацию и ошибку
        if (!empty($error)) {
            include("include/" . $page . ".php");
        } else { //иначе сохранаяем пользователя в куки и показываем стартовую страницу
            if ($isSave) $time = time() + 3600 * 24 * 365;
            else $time = 0;

            setcookie("username", $username, $time);
            setcookie("password", $password, $time);
            header('location: /');
        }
    }

    private function checkUser($username, $password, $email, $save, $type)
    {
        if (!empty($username) && !empty($password) && !empty($email)) {
            $databaseManager = new DatabaseManager();

            $email = htmlspecialchars($email);
            $username = htmlspecialchars($username);
            $password = md5(htmlspecialchars($password) . "asdfhgewq123"); //шифрование пароля с солью

            //запоминать ли юзера
            $isSave = false;
            if (!empty($save)) $isSave = true;

            if ($type == "registration") $error = $databaseManager->registerUser($username, $password, $email);
            else $error = $databaseManager->loginUser($username, $password);

            $this->saveUser($isSave, $type, $username, $password, $error);
        }
    }

    // POST - Добавление в базу новых данных
    protected function createAction()
    {
        //если все поля пустые, то просто показываем регистрацию
        if (empty($_POST['username']) && empty($_POST['password']) && empty($_POST['email'])) {
            include('include/registration.php');
        } else {
            $this->checkUser($_POST['username'], $_POST['password'], $_POST['email'], $_POST['isSave'], "registration");
        }
    }

    // PUT - Обновление данных
    protected function updateAction()
    {
        echo "invalid method";
    }

    // GET - Просмотр данных
    protected function viewAction()
    {
        //если все поля пустые, то просто показываем форму логина
        if (empty($_GET['username']) && empty($_GET['password'])) {
            include('include/login.php');
        } else {
            $this->checkUser($_GET['username'], $_GET['password'], "email", $_GET['isSave'], "login");
        }
    }

    // DELETE - Удаление данных
    protected function deleteAction()
    {
        echo "invalid method";
    }
}

