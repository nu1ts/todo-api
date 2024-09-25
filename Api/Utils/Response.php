<?php

namespace Api\Utils;

class Response
{
    public static function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    public static function success($data = [])
    {
        self::json($data, 200);
    }

    public static function created($data = [])
    {
        self::json($data, 201);
    }

    public static function badRequest($message = 'Bad request')
    {
        self::json(['error' => $message], 400);
    }

    public static function notFound($message = 'Not found')
    {
        self::json(['error' => $message], 404);
    }

    public static function internalServerError($message = 'Internal server error')
    {
        self::json(['error' => $message], 500);
    }
}