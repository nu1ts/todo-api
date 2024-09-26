<?php

namespace Api\Models;

use PDO;

class ListModel
{
    private static function getConnection()
    {
        $config = require __DIR__ . '/../../config/database.php';
        return new PDO($config['dsn'], $config['username'], $config['password']);
    }

    public static function getAllLists()
    {
        $pdo = self::getConnection();
        $stmt = $pdo->query('SELECT * FROM lists');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getListById($id)
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM lists WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function addList($name)
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare('INSERT INTO lists (name) VALUES (?)');
        $stmt->execute([$name]);
        return $pdo->lastInsertId();
    }

    public static function updateList($id, $name)
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare('UPDATE lists SET name = ? WHERE id = ?');
        $stmt->execute([$name, $id]);
    }

    public static function deleteList($id)
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare('DELETE FROM lists WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
