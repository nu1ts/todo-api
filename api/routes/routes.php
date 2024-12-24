<?php

use api\controllers\taskController;
use api\controllers\listController;
use api\utils\response;

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routes = [
    'GET' => [
        '/tasks' => [TaskController::class, 'index'],
        '/lists' => [ListController::class, 'index'],
        '/lists/{id}' => [ListController::class, 'show'],
        '/health' => [TaskController::class, 'health'],
    ],
    'POST' => [
        '/tasks' => [TaskController::class, 'store'],
        '/tasks/upload' => [TaskController::class, 'upload'],
        '/lists' => [ListController::class, 'store'],
    ],
    'PUT' => [
        '/tasks' => [TaskController::class, 'update'],
        '/lists/{id}' => [ListController::class, 'update'],
    ],
    'PATCH' => [
        '/tasks/status' => [TaskController::class, 'toggleStatus'],
    ],
    'DELETE' => [
        '/tasks' => [TaskController::class, 'delete'],
        '/lists/{id}' => [ListController::class, 'delete'],
    ]
];

$requestUriParts = explode('/', trim($requestUri, '/'));
foreach ($routes[$requestMethod] ?? [] as $route => [$controller, $method]) {
    $routeParts = explode('/', trim($route, '/'));
    
    if (count($routeParts) === count($requestUriParts)) {
        $params = [];
        foreach ($routeParts as $key => $routePart) {
            if (preg_match('/^{\w+}$/', $routePart)) {
                $params[] = $requestUriParts[$key];
            } elseif ($routePart !== $requestUriParts[$key]) {
                continue 2;
            }
        }
        $controllerInstance = new $controller();
        $controllerInstance->$method(...$params);
        exit();
    }
}

Response::notFound('Route not found');