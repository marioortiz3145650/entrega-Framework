<?php
// index.php

define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);


require_once ROOT . 'Libraries/Core/Autoload.php';


require_once ROOT . 'Config/Config.php';
require_once ROOT . 'Helpers/Helpers.php';


$url = !empty($_GET['url']) ? $_GET['url'] : "home/home";
$arrUrl = explode("/", $url);

$controller = $arrUrl[0];
$method = !empty($arrUrl[1]) ? $arrUrl[1] : 'home';
$params = "";

if (!empty($arrUrl[2])) {
    for ($i = 2; $i < count($arrUrl); $i++) {
        $params .= $arrUrl[$i] . ',';
    }
    $params = trim($params, ',');
}


require_once ROOT . 'Libraries/Core/Load.php';


loadController($controller, $method, $params);