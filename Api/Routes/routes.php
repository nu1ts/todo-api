<?php

use Api\Controllers\TaskController;
use Api\Utils\Response;

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routes = [
    'GET' => [
        '/tasks' => [TaskController::class, 'index'],
    ],
    'POST' => [
        '/tasks' => [TaskController::class, 'store'],
        '/tasks/upload' => [TaskController::class, 'upload'],
    ],
    'PUT' => [
        '/tasks' => [TaskController::class, 'update'],
    ],
    'PATCH' => [
        '/tasks/status' => [TaskController::class, 'toggleStatus'],
    ],
    'DELETE' => [
        '/tasks' => [TaskController::class, 'delete'],
    ]
];

if (isset($routes[$requestMethod][$requestUri])) {
    [$controller, $method] = $routes[$requestMethod][$requestUri];
    $controllerInstance = new $controller();
    $controllerInstance->$method();
} else {
    Response::notFound('Route not found');
}