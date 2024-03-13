<?php
namespace Services;

use Models\User;
use Repositories\UserRepository;
use Repositories\AnalyticsRepository;
class UserService
{

    private $repository;
    private $repository2;

    function __construct()
    {
        $this->repository = new UserRepository();
        $this->repository2 = new AnalyticsRepository();

    }

    // Verify username and password
    public function checkUsernamePassword($username, $password)
    {
        return $this->repository->checkUsernamePassword($username, $password);
    }

    // Register a new user
    public function register(User $user)
    {
        return $this->repository->register($user);
    }

    // Update existing user details
    public function update(User $user)
    {
        return $this->repository->update($user);
    }

    // Delete a user
    public function delete($user_id)
    {
        return $this->repository->delete($user_id);
    }

    public function hashPassword($password)
    {
        return $this->repository->hashPassword($password);
    }



    public function getOne($user_id)
    {
        return $this->repository->getOne($user_id);
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getUserById($userId){
        return $this->repository->getUserById($userId);
    }

    public function getTotalActiveUsers() {
        return $this->repository2->getCountOfActiveUsers();
    }

    public function getTotalTasks() {
        return $this->repository2->getCountOfTotalTasks();
    }

    public function getTotalCompletedTasks() {
        $totalTasks = $this->repository2->getCountOfTotalTasks();
        $completedTasks = $this->repository2->getCountOfCompletedTasks();
        return [
            'completed' => $completedTasks,
            'total' => $totalTasks,
            'percentage' => ($totalTasks > 0) ? ($completedTasks / $totalTasks) * 100 : 0
        ];
    }

}
?>