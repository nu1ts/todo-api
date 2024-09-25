<?php

namespace Api\Models;

use PDO;

class Task
{
    private static function getConnection()
    {
        $config = require __DIR__ . '/../../config/database.php';
        return new PDO($config['dsn'], $config['username'], $config['password']);
    }

    public static function getTasksByList($listId)
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE list_id = ?");
        $stmt->execute([$listId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function addTask($data)
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description, list_id) VALUES (?, ?, ?)");
        $stmt->execute([$data['title'], $data['description'], $data['list_id']]);
        return $pdo->lastInsertId();
    }

    public static function updateTask($data)
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ? WHERE id = ?");
        $stmt->execute([$data['title'], $data['description'], $data['id']]);
    }

    public static function toggleTaskStatus($id)
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("UPDATE tasks SET status = NOT status WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function deleteTask($id)
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function deleteTasksByList($listId)
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE list_id = ?");
        $stmt->execute([$listId]);
    }
}
