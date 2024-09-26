<?php

namespace Api\Controllers;

use Api\Models\ListModel;
use Api\Utils\Response;
use Api\Utils\Validator;

class ListController
{
    public function index()
    {
        $lists = ListModel::getAllLists();
        Response::success($lists);
    }

    public function show($id)
    {
        $list = ListModel::getListById($id);
        if ($list) {
            Response::success($list);
        } else {
            Response::notFound('List not found');
        }
    }

    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $error = Validator::required($data, ['name']);
        if ($error) {
            Response::badRequest($error);
            return;
        }

        $error = Validator::maxLength('name', $data['name'], 255);
        if ($error) {
            Response::badRequest($error);
            return;
        }

        $listId = ListModel::addList($data['name']);
        Response::created(['success' => true, 'id' => $listId]);
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['name'])) {
            $error = Validator::maxLength('name', $data['name'], 255);
            if ($error) {
                Response::badRequest($error);
                return;
            }

            ListModel::updateList($id, $data['name']);
            Response::success(['success' => true]);
        } else {
            Response::badRequest('List name is required');
        }
    }

    public function delete($id)
    {
        if (ListModel::deleteList($id)) {
            Response::success(['success' => true]);
        } else {
            Response::notFound('List not found');
        }
    }
}
