<?php

namespace Controllers;

use Exception;
use Services\TaskService;
use Models\Task;

class TaskController extends Controller
{
    private $service;

    function __construct()
    {
        parent::__construct();
        $this->service = new TaskService();
    }

    function create()
    {
        try {
            $taskData = $this->createObjectFromPostedJson("Models\\Task");
            $decoded = $this->checkForJwt();
            if (!$decoded) {
                return; 
            }

            $taskData->user_id = $decoded->data->id; 
            $taskId = $this->service->create($taskData);
            $this->respond(['message' => 'Task created successfully', 'task_id' => $taskId]);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    function getAll()
    {
        try {
            $decoded = $this->checkForJwt();
            if (!$decoded) {
                return; 
            }

            $tasks = $this->service->getAll($decoded->data->id);
            $this->respond($tasks);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    function getOne($id)
    {
        try {
            $decoded = $this->checkForJwt();
            if (!$decoded) {
                return; 
            }
            $task = $this->service->getOne($decoded->data->id, $id);
            if (!$task) {
                $this->respondWithError(404, 'Task not found');
                return;
            }
            $this->respond($task);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    function update($id)
    {
        try {
            $taskData = $this->createObjectFromPostedJson("Models\\Task");
            $taskData->task_id = $id;
            $updateCount = $this->service->update($taskData);
            if ($updateCount > 0) {
                $this->respond(['message' => 'Task updated successfully']);
            } else {
                $this->respondWithError(404, 'Task not found or no changes made');
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    function delete($id)
    {
        try {
            $deleteCount = $this->service->delete($id);
            if ($deleteCount > 0) {
                $this->respond(['message' => 'Task deleted successfully']);
            } else {
                $this->respondWithError(404, 'Task not found');
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    function complete($id)
    {
        try {
            $completeCount = $this->service->complete($id);
            if ($completeCount > 0) {
                $this->respond(['message' => 'Task marked as complete']);
            } else {
                $this->respondWithError(404, 'Task not found');
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }


    function shareSingle($id){
        try {
            $decoded = $this->checkForJwt();
            if (!$decoded) {
                return; 
            }
            $sharedTask = $this->service->getOne($decoded->data->id, $id);
            if (!$sharedTask) {
                $this->respondWithError(404, 'Task not found');
                return;
            }
            $this->respond($sharedTask);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }
   
}
