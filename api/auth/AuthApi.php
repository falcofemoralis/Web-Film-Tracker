<?php

require_once 'scripts/php/Managers/DatabaseManager.php';
require_once 'api/Api.php';

class AuthApi extends Api
{
    private function saveUser($isSave, $username, $password, $error)
    {
        // сохранаяем пользователя в куки и показываем стартовую страницу
        if (empty($error)) {
            if ($isSave) $time = time() + 3600 * 24 * 365;
            else $time = 0;

            setcookie("username", $username, $time);
            setcookie("password", $password, $time);
        } else { //если была ошибка, то показываем регистрацию и ошибку
            echo $error;
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

            if ($type == "registration") {
                $error = $databaseManager->registerUser($username, $password, $email);
                if (empty($error)) {
                    $pathToSave = $_SERVER['DOCUMENT_ROOT'] . "\\images\\avatars\\" . $username . ".png";

                    if ($_FILES['avatar']['size'] < 1 * 1024 * 1024) {
                        move_uploaded_file($_FILES['avatar']['tmp_name'], $pathToSave);
                    } else {
                        $error = "Файл слишком большой!";
                    }
                }
            } else {
                $error = $databaseManager->loginUser($username, $password);
            }

            $this->saveUser($isSave, $username, $password, $error);
        }
    }

    // POST - Добавление в базу новых данных
    protected function createAction()
    {
        $this->checkUser($_POST['username'], $_POST['password'], $_POST['email'], $_POST['isSave'], "registration");
    }

    // PUT - Обновление данных
    protected function updateAction()
    {
        echo "invalid method";
    }

    // GET - Просмотр данных
    protected function viewAction()
    {
        $this->checkUser($_GET['username'], $_GET['password'], "null", $_GET['isSave'], "login");
    }

    // DELETE - Удаление данных
    protected function deleteAction()
    {
        echo "invalid method";
    }
}

