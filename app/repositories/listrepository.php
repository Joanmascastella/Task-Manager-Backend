<?php

namespace Repositories;

use Models\ListModel;
use PDO;
use PDOException;

class ListRepository extends Repository
{
    // Create a new list
    public function create(ListModel $list)
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO Lists (user_id, listname) VALUES (:user_id, :listname)");
            $stmt->bindParam(':user_id', $list->user_id);
            $stmt->bindParam(':listname', $list->listname);
            $stmt->execute();

            return $this->connection->lastInsertId();
        } catch (PDOException $e) {

        }
    }

    // Update an existing list
    public function update($list_id, ListModel $list)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE Lists SET listname = :listname WHERE list_id = :list_id");
            $stmt->bindParam(':listname', $list->listname);
            $stmt->bindParam(':list_id', $list_id);
            $stmt->execute();

            return $stmt->rowCount();
        } catch (PDOException $e) {

        }
    }

    // Delete a list
    public function delete($list_id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM Lists WHERE list_id = :list_id");
            $stmt->bindParam(':list_id', $list_id);
            $stmt->execute();

            return $stmt->rowCount();
        } catch (PDOException $e) {

        }
    }

    // Add a task to a list
    public function addTask($list_id, $task_id)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE Tasks SET list_id = :list_id WHERE task_id = :task_id");
            $stmt->bindParam(':list_id', $list_id);
            $stmt->bindParam(':task_id', $task_id);
            $stmt->execute();

            return $stmt->rowCount();
        } catch (PDOException $e) {

        }
    }


    // Method to get all tasks for a list
    public function share($list_id)
    {
        try {
            $stmt = $this->connection->prepare("
              SELECT t.*, l.listname
              FROM Tasks AS t
              INNER JOIN Lists AS l ON t.list_id = l.list_id
              WHERE l.list_id = :list_id
          ");
            $stmt->bindParam(':list_id', $list_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

        }
    }

    // Retrieve a single list by its ID with all its tasks
    public function getOne($user_id, $list_id)
    {
        try {
            $stmt = $this->connection->prepare("
            SELECT l.*, t.task_id, t.title, t.description, t.deadline, t.status
            FROM Lists l
            LEFT JOIN Tasks t ON l.list_id = t.list_id
            WHERE l.list_id = :list_id AND l.user_id = :user_id
        ");
            $stmt->bindParam(':list_id', $list_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            $listData = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($listData) {
                $listData['tasks'] = [];
                while ($task = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $listData['tasks'][] = $task;
                }
            }
            return $listData;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    // Retrieve all lists for the given user_id with their tasks
    public function getAll($user_id)
    {
        try {
            $stmt = $this->connection->prepare("
            SELECT l.list_id, l.listname, t.task_id, t.title, t.description, t.deadline, t.status
            FROM Lists l
            LEFT JOIN Tasks t ON l.list_id = t.list_id
            WHERE l.user_id = :user_id
            ORDER BY l.list_id, t.task_id
        ");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            $lists = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $listId = $row['list_id'];
                if (!isset($lists[$listId])) {
                    $lists[$listId] = [
                        'list_id' => $row['list_id'],
                        'listname' => $row['listname'],
                        'tasks' => []
                    ];
                }
                if ($row['task_id']) {
                    $lists[$listId]['tasks'][] = [
                        'task_id' => $row['task_id'],
                        'title' => $row['title'],
                        'description' => $row['description'],
                        'deadline' => $row['deadline'],
                        'status' => $row['status']
                    ];
                }
            }

            return array_values($lists);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

}
?>