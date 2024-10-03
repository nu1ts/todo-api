<?php

namespace api\models;

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

    public static function updateTask($id, $fields)
    {
        $pdo = self::getConnection();
        $setClause = [];
        $params = [];
        if (isset($fields['title'])) {
            $setClause[] = 'title = ?';
            $params[] = $fields['title'];
        }
        if (isset($fields['description'])) {
            $setClause[] = 'description = ?';
            $params[] = $fields['description'];
        }
        $params[] = $id;
        if (!empty($setClause)) {
            $sql = 'UPDATE tasks SET ' . implode(', ', $setClause) . ' WHERE id = ?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        }
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
