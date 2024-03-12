<?php
namespace Services;

use Models\Activity;
use Repositories\ActivityRepository;

class ActivityService {

    private $repository;

    function __construct()
    {
        $this->repository = new ActivityRepository();
    }

    // Get the daily time goal for a user
    public function getDailyGoal($user_id) {
        return $this->repository->getDailyGoal($user_id);
    }

    // Update the daily time goal for a user
    public function updateDailyGoal($user_id, $daily_time_goal) {
        return $this->repository->updateDailyGoal($user_id, $daily_time_goal);
    }

    // Get the streak of consecutive days meeting the daily goal
    public function getStreak($user_id) {
        return $this->repository->getStreak($user_id);
    }

    // Log activity for a user
    public function logActivity(Activity $activity) {
        return $this->repository->logActivity($activity);
    }

    // Get the activity log for a user
    public function getActivityLog($user_id) {
        return $this->repository->getActivityLog($user_id);
    }

}
?>
