<?php

//abstract class Api
abstract class Api
{
    protected $method = ''; // Метод запроса (GET/POST/PUT/DELETE)
    protected $action = ''; // Название метода для действия
    protected $requestUri = []; // URI

    //Конструктор "вынимает" из запроса все необходимые данные (тип запроса, параметры переданные в URI, параметры переданные в теле запроса)
    public function __construct($requestUri)
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->requestUri = $requestUri;
    }

    // Обрабатывает запрос
    public function run()
    {
        $this->action = $this->getAction(); // Определяем действие
        return $this->{$this->action}(); // Вызываем метод в соответствии с методом
    }

    // Метод API который будет выполнятся в зависимости от типа запроса
    public function getAction()
    {
        switch ($this->method) {
            case "POST":
                return 'createAction';
            case "GET":
                return 'viewAction';
            case "DELETE":
                return 'deleteAction';
            case "PUT":
                return 'updateAction';
            default:
                return null;
        }
    }

    abstract protected function createAction();

    abstract protected function viewAction();

    abstract protected function deleteAction();

    abstract protected function updateAction();
}
