<?php
function loadController($controllerName, $methodName, $paramsString) {
    $controllerClass = ucwords($controllerName);
    $controllerFile = "Controllers/{$controllerClass}.php";

    if (file_exists($controllerFile)) {
        require_once($controllerFile);
        $controllerObj = new $controllerClass();

        if (method_exists($controllerObj, $methodName)) {
            $controllerObj->$methodName($paramsString);
        } else {
            require_once("Controllers/Error.php");
        }
    } else {
        require_once("Controllers/Error.php");
    }
}
?>