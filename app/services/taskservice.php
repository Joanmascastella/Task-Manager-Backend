<?php
namespace Services;

use Models\Task;
use Repositories\TaskRepository;

class TaskService
{

    private $repository;

    function __construct()
    {
        $this->repository = new TaskRepository();
    }

    // Create a new task
    public function create(Task $task)
    {
        return $this->repository->create($task);
    }

    // Retrieve all tasks for a user
    public function getAll($user_id)
    {
        return $this->repository->getAll($user_id);
    }

    // Retrieve a single task by its ID
    public function getOne($user_id, $task_id)
    {
        return $this->repository->getOne($user_id, $task_id);
    }

    // Update a task
    public function update(Task $task)
    {
        return $this->repository->update($task);
    }

    // Delete a task
    public function delete($task_id)
    {
        return $this->repository->delete($task_id);
    }

    // Mark a task as complete
    public function complete($task_id)
    {
        return $this->repository->complete($task_id);
    }

    public function updateTimeElapsed($task_id, $timeElapsed)
    {
        return $this->repository->updateTimeElapsed($task_id, $timeElapsed);
    }


}
?>