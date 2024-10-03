<?php

namespace api\controllers;

use api\models\task;
use api\utils\response;
use api\utils\validator;

class TaskController
{
    public function index()
    {
        $listId = $_GET['list_id'] ?? null;
        if ($listId) {
            $tasks = Task::getTasksByList($listId);
            Response::success($tasks);
        } else {
            Response::badRequest('List ID not provided');
        }
    }

    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $error = Validator::required($data, ['title', 'list_id']);
        if ($error) {
            Response::badRequest($error);
            return;
        }
        $error = Validator::maxLength('title', $data['title'], 255);
        if ($error) {
            Response::badRequest($error);
            return;
        }
        $error = Validator::integer('list_id', $data['list_id']);
        if ($error) {
            Response::badRequest($error);
            return;
        }
        $taskId = Task::addTask($data);
        Response::created(['success' => true, 'id' => $taskId]);
    }

    public function update()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['id'])) {
            Response::badRequest('Task ID is required');
            return;
        }

        $updateFields = [];
        if (isset($data['title'])) {
            $error = Validator::maxLength('title', $data['title'], 255);
            if ($error) {
                Response::badRequest($error);
                return;
            }
            $updateFields['title'] = $data['title'];
        }

        if (isset($data['description'])) {
            $updateFields['description'] = $data['description'];
        }

        if (empty($updateFields)) {
            Response::badRequest('No fields to update');
            return;
        }

        Task::updateTask($data['id'], $updateFields);
        Response::success(['success' => true]);
    }

    public function toggleStatus()
    {
        $taskId = $_GET['id'] ?? null;
        if ($taskId) {
            Task::toggleTaskStatus($taskId);
            Response::success(['success' => true]);
        } else {
            Response::badRequest('Task ID not provided');
        }
    }

    public function delete()
    {
        $taskId = $_GET['id'] ?? null;
        if ($taskId) {
            Task::deleteTask($taskId);
            Response::success(['success' => true]);
        } else {
            Response::badRequest('Task ID not provided');
        }
    }

    public function upload()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $error = Validator::required($data, ['list_id', 'tasks']);
        if ($error) {
            Response::badRequest($error);
            return;
        }

        $error = Validator::integer('list_id', $data['list_id']);
        if ($error) {
            Response::badRequest($error);
            return;
        }

        if (!is_array($data['tasks'])) {
            Response::badRequest('Tasks should be an array');
            return;
        }

        Task::deleteTasksByList($data['list_id']);

        foreach ($data['tasks'] as $task) {
            $error = Validator::required($task, ['title']);
            if ($error) {
                Response::badRequest($error);
                return;
            }

            $error = Validator::maxLength('title', $task['title'], 255);
            if ($error) {
                Response::badRequest($error);
                return;
            }

            Task::addTask([
                'title' => $task['title'],
                'description' => $task['description'] ?? '',
                'list_id' => $data['list_id']
            ]);
        }

        Response::created(['success' => true, 'message' => 'List uploaded successfully']);
    }
}