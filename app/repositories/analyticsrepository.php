<?php
namespace Repositories;

use PDO;
use PDOException;

class AnalyticsRepository extends Repository {

    public function getCountOfActiveUsers() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM Users WHERE role = 'user'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getCountOfTotalTasks() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM Tasks");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getCountOfCompletedTasks() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM Tasks WHERE status = 'completed'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }


    public function getCountOfTotalTasksUser($id) {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM Tasks WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getCountOfCompletedTasksUser($id) {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM Tasks WHERE user_id = :user_id AND status = 'completed'");
        $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
