<?php

namespace Api\Controllers;

use Api\Models\Task;
use Api\Utils\Response;

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
        if (isset($data['title'], $data['list_id'])) {
            $taskId = Task::addTask($data);
            Response::created(['success' => true, 'id' => $taskId]);
        } else {
            Response::badRequest('Missing title or list_id');
        }
    }

    public function update()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'], $data['title'])) {
            Task::updateTask($data);
            Response::success(['success' => true]);
        } else {
            Response::badRequest('Missing task ID or title');
        }
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
        
        if (isset($data['list_id'], $data['tasks']) && is_array($data['tasks'])) {
            $listId = $data['list_id'];
            $tasks = $data['tasks'];
            
            Task::deleteTasksByList($listId);
            
            foreach ($tasks as $task) {
                if (isset($task['title'])) {
                    Task::addTask([
                        'title' => $task['title'],
                        'description' => $task['description'] ?? '',
                        'list_id' => $listId
                    ]);
                }
            }
            
            Response::created(['success' => true, 'message' => 'List uploaded successfully']);
        } else {
            Response::badRequest('Invalid input data');
        }
    }
}