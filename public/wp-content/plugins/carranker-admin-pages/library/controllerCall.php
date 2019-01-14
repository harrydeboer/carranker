<?php

declare(strict_types=1);

$controller = explode('/', $url)[0];

require_once dirname(__DIR__) . '/app/Controllers/Controller.php';
require_once dirname(__DIR__) . '/app/Controllers/' . ucfirst($controller) . 'Controller.php';

$controllerName = '\CarrankerAdmin\App' . '\\' . ucfirst($controller) . 'Controller';
$controllerObj = new $controllerName($controller, $action);
if (isset($request)) {
    $controllerObj->{$action}($request);
} else {
    $controllerObj->{$action}($urlParams);
}
