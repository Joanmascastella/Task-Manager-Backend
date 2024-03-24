<?php

namespace Controllers;

use Exception;
use Models\ListModel;
use Services\ListService;

class ListController extends Controller
{
    private $service;

    function __construct()
    {
        parent::__construct(); 
        $this->service = new ListService();
    }

    function create()
    {
        try {
            $decoded = $this->checkForJwt();
            if (!$decoded) {
                return; 
            }

            $listData = $this->createObjectFromPostedJson("Models\\ListModel");
            $listData->user_id = $decoded->data->id; 
            $listId = $this->service->create($listData);
            $this->respond($listId);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    function update($list_id)
    {
        try {
            $decoded = $this->checkForJwt();
            if (!$decoded) {
                return;
            }

            $listData = $this->createObjectFromPostedJson("Models\\ListModel");
            $result = $this->service->update($list_id, $listData);
            if ($result > 0) {
                $this->respond(['message' => 'List updated successfully']);
            } else {
                $this->respondWithError(404, 'List not found or no changes made');
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    function delete($list_id)
    {
        try {
            $decoded = $this->checkForJwt();
            if (!$decoded) {
                return;
            }

            $result = $this->service->delete($list_id);
            if ($result > 0) {
                $this->respond(['message' => 'List deleted successfully']);
            } else {
                $this->respondWithError(404, 'List not found');
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    function addTask($list_id, $task_id)
    {
        try {
            $decoded = $this->checkForJwt();
            if (!$decoded) {
                return;
            }

            $result = $this->service->addTask($list_id, $task_id);
            if ($result > 0) {
                $this->respond(['message' => 'Task added to list successfully']);
            } else {
                $this->respondWithError(404, 'Task or List not found');
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    function getOne($list_id) {
        try {
            $decoded = $this->checkForJwt();
            if (!$decoded) {
                return;
            }
    
            $list = $this->service->getOne($decoded->data->id, $list_id);
            if ($list) {
                $this->respond($list);
            } else {
                $this->respondWithError(404, 'List not found');
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }
    
    public function getAll() {
        try {
            $decoded = $this->checkForJwt();
            if (!$decoded) {
                return;
            }
            
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; 
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0; 
            
            $lists = $this->service->getAll($decoded->data->id, $limit, $offset);
            $this->respond($lists);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }
    
    
}
