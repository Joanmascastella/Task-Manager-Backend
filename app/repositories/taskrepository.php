<?php

namespace Repositories;

use Models\Task;
use PDO;
use PDOException;

class TaskRepository extends Repository
{
    // Create a new task
    function create(Task $task)
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO Tasks (user_id, title, description, deadline, status, list_id) VALUES (:user_id, :title, :description, :deadline, :status, :list_id)");
            $stmt->bindParam(':user_id', $task->user_id);
            $stmt->bindParam(':title', $task->title);
            $stmt->bindParam(':description', $task->description);
            $stmt->bindParam(':deadline', $task->deadline);
            $stmt->bindParam(':status', $task->status);
            $stmt->bindParam(':list_id', $task->list_id);
            $stmt->execute();

            return $this->connection->lastInsertId();
        } catch (PDOException $e) {

        }
    }

    // Retrieve all tasks for a user
    function getAll($user_id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM Tasks WHERE user_id = :user_id AND status != 'completed' ORDER BY list_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
    
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Models\Task');
        } catch (PDOException $e) {
            // Handle exceptions here
        }
    }
    

    // Update a task
    function update(Task $task)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE Tasks SET title = :title, description = :description, deadline = :deadline, status = :status, list_id = :list_id WHERE task_id = :task_id");
            $stmt->bindParam(':title', $task->title);
            $stmt->bindParam(':description', $task->description);
            $stmt->bindParam(':deadline', $task->deadline);
            $stmt->bindParam(':status', $task->status);
            $stmt->bindParam(':list_id', $task->list_id);
            $stmt->bindParam(':task_id', $task->task_id);
            $stmt->execute();

            return $stmt->rowCount();
        } catch (PDOException $e) {
        }
    }

    // Delete a task
    function delete($task_id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM Tasks WHERE task_id = :task_id");
            $stmt->bindParam(':task_id', $task_id);
            $stmt->execute();

            return $stmt->rowCount();
        } catch (PDOException $e) {

        }
    }

    // Mark a task as complete
    function complete($task_id, $status)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE Tasks SET status = :status WHERE task_id = :task_id");
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':task_id', $task_id);
            $stmt->execute();

            return $stmt->rowCount();
        } catch (PDOException $e) {

        }
    }

    // Retrieve a single task by its ID
    function getOne($user_id, $task_id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM Tasks WHERE task_id = :task_id AND user_id = :user_id LIMIT 1");
            $stmt->bindParam(':task_id', $task_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Task');
            $task = $stmt->fetch();

            if ($task) {
                return $task;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }

    function shareOne($task_id){
        try {
            $stmt = $this->connection->prepare("SELECT * FROM Tasks WHERE task_id = :task_id LIMIT 1");
            $stmt->bindParam(':task_id', $task_id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Task');
            $task = $stmt->fetch();

            if ($task) {
                return $task;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }

    // Update the time elapsed on a task
    public function updateTimeElapsed($task_id, $timeElapsed)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE Tasks SET time_elapsed = time_elapsed + :time_elapsed WHERE task_id = :task_id");
            $stmt->bindParam(':time_elapsed', $timeElapsed);
            $stmt->bindParam(':task_id', $task_id);
            $stmt->execute();

            return $stmt->rowCount();
        } catch (PDOException $e) {
        }
    }
}
