<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;
use Models\Activity;

class Activityrepository extends Repository
{

    function getDailyGoal($user_id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT daily_time_goal FROM Users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (PDOException $e) {
         
        }
    }

    // Update the daily time goal for a user
    function updateDailyGoal($user_id, $daily_time_goal)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE Users SET daily_time_goal = :daily_time_goal WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':daily_time_goal', $daily_time_goal);
            $stmt->execute();

            return $stmt->rowCount();
        } catch (PDOException $e) {
           
        }
    }

    // Get the streak of consecutive days meeting the daily goal
    function getStreak($user_id)
    {
        try {
            $stmt = $this->connection->prepare("
                SELECT COUNT(*) AS streak
                FROM (
                    SELECT date, SUM(total_time_worked) AS total_time
                    FROM ActivityLog
                    WHERE user_id = :user_id
                    GROUP BY date
                    HAVING total_time >= (SELECT daily_time_goal FROM Users WHERE user_id = :user_id)
                ) AS days_with_goal_met
            ");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (PDOException $e) {
      
        }
    }

    function logActivity(Activity $activity)
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO ActivityLog (user_id, date, total_time_worked) VALUES (:user_id, :date, :total_time_worked)");
            $stmt->bindParam(':user_id', $activity->user_id);
            $stmt->bindParam(':date', $activity->date);
            $stmt->bindParam(':total_time_worked', $activity->total_time_worked);
            $stmt->execute();

            return $this->connection->lastInsertId();
        } catch (PDOException $e) {
       
        }
    }

    // Get the activity log for a user
    function getActivityLog($user_id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM ActivityLog WHERE user_id = :user_id ORDER BY date DESC");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Models\Activity');
        } catch (PDOException $e) {
            
        }
    }
    
}
