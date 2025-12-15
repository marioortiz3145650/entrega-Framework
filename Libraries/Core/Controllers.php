<?php
// Libraries/Core/Controllers.php

require_once __DIR__ . '/Views.php';

class Controllers
{
    /** @var Views */
    protected $views;

    /** @var object|null */
    protected $model = null;

    public function __construct()
    {
        $this->views = new Views();
        $this->loadModel();
    }

    protected function loadModel(): void
    {
        $model = get_class($this) . "Model";             // HomeModel
        $route = __DIR__ . "/../../Models/{$model}.php"; // Libraries/Core -> Models

        if (file_exists($route)) {
            require_once $route;
            $this->model = new $model();
        }
    }
}
