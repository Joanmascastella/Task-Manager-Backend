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
        $decoded = $this->checkForJwt();
        if (!$decoded) {
            return;
        }
        try {
            $taskData = $this->createObjectFromPostedJson("Models\\Task");
            $taskData->user_id = $decoded->data->id;
            $taskId = $this->service->create($taskData);
            $this->respond($taskId);

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
            $limit = isset ($_GET['limit']) ? (int) $_GET['limit'] : 10;
            $offset = isset ($_GET['offset']) ? (int) $_GET['offset'] : 0;

            $tasks = $this->service->getAll($decoded->data->id, $limit, $offset);
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
            $task = $this->service->getOne($decoded->data->id);
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
            $decoded = $this->checkForJwt();
            if (!$decoded) {
                return;
            }

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
            $decoded = $this->checkForJwt();
            if (!$decoded) {
                return;
            }

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

    function complete($id, $status)
    {
        try {
            $decoded = $this->checkForJwt();
            if (!$decoded) {
                return;
            }

            $completeCount = $this->service->complete($id, $status);
            if ($completeCount > 0) {
                $this->respond(['message' => 'Task marked as complete']);
            } else {
                $this->respondWithError(404, 'Task not found');
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function updateTimeElapsed($task_id)
    {
        try {
            $decoded = $this->checkForJwt();
            if (!$decoded) {
                return;
            }

            $taskData = $this->createObjectFromPostedJson("Models\\Task");
            $taskData->task_id = $task_id;
            $updateCount = $this->service->updateTimeElapsed($task_id, $taskData->time_elapsed);
            if ($updateCount > 0) {
                $this->respond(['message' => 'Task time updated successfully']);
            } else {
                $this->respondWithError(404, 'Task not found or no changes made');
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }


}
