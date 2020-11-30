<?php

require_once 'scripts/php/Managers/DatabaseManager.php';
require_once 'api/Api.php';

class AuthApi extends Api
{
    // POST - Добавление в базу новых данных
    protected function createAction()
    {
        //если все поля пустые, то просто показываем регистрацию
        if (empty($_POST['username']) && empty($_POST['password']) && empty($_POST['email'])) {
            include('include/registration.php');
        }

        if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['email'])) {
            $email = htmlspecialchars($_POST['email']);
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);
            $password = md5(htmlspecialchars($password) . "asdfhgewq123"); //шифрование пароля с солью

            //запоминать ли юзера
            $isSave = false;
            if (!empty($_POST['isSave'])) $isSave = true;

            $databaseManager = new DatabaseManager();
            $error = $databaseManager->registerUser($username, $password, $email);

            //если была ошибка, то показываем регистрацию и ошибку
            if (!empty($error)) {
                include('include/registration.php');
            } else { //иначе сохранаяем пользователя в куки и показываем стартовую страницу
                if ($isSave) setcookie("username", $username, time() + 3600 * 24 * 365);
                else  setcookie("username", $username);
                header('location: /');
            }
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
        }

        //если какое-то из полей пустое, сообщаем, что нужно ввести все поля
        if (!empty($_GET['username']) && !empty($_GET['password'])) {
            $username = htmlspecialchars($_GET['username']);
            $password = htmlspecialchars($_GET['password']);
            $password = md5(htmlspecialchars($password) . "asdfhgewq123");

            //запоминать ли юзера
            $isSave = false;
            if (!empty($_GET['isSave'])) $isSave = true;

            $databaseManager = new DatabaseManager();
            $error = $databaseManager->loginUser($username, $password);

            if (!empty($error)) {
                include('include/login.php');
            } else {
                if ($isSave) setcookie("username", $username, time() + time() + 3600 * 24 * 365);
                else  setcookie("username", $username);
                header('location: /');
            }
        }
    }

    // DELETE - Удаление данных
    protected function deleteAction()
    {
        echo "invalid method";
    }
}

