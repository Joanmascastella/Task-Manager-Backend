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

}

