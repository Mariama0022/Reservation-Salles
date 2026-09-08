<?php

declare(strict_types=1);

use FastRoute\Dispatcher;

require dirname(__DIR__) . '/vendor/autoload.php';

require dirname(__DIR__) . '/config/database.php';

$container = require dirname(__DIR__) . '/config/container.php';

$routes = require dirname(__DIR__) . '/routes/web.php';

$dispatcher = FastRoute\simpleDispatcher($routes);

$httpMethod = $_SERVER['REQUEST_METHOD'];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (!is_string($uri)) {
    $uri = '/';
}

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        http_response_code(404);

        require dirname(__DIR__) . '/templates/error/404.php';

        break;

    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);

        header('Allow: ' . implode(', ', $routeInfo[1]));

        require dirname(__DIR__) . '/templates/error/405.php';

        break;

    case Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $params = $routeInfo[2];

        [$controllerClass, $action] = $handler;

        $controller = $container->get($controllerClass);

        $params = array_map('intval', $params);

        $controller->$action(...array_values($params));

        break;
}