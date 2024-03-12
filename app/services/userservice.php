<?php
namespace Services;

use Models\User;
use Repositories\UserRepository;

class UserService {

    private $repository;

    function __construct()
    {
        $this->repository = new UserRepository();
    }

    // Verify username and password
    public function checkUsernamePassword($username, $password) {
        return $this->repository->checkUsernamePassword($username, $password);
    }

    // Register a new user
    public function register(User $user) {
        return $this->repository->register($user);
    }

    // Update existing user details
    public function update(User $user) {
        return $this->repository->update($user);
    }

    // Delete a user
    public function delete($user_id) {
        return $this->repository->delete($user_id);
    }

    public function hashPassword($password){
        return $this->repository->hashPassword($password);
    }
   
}
?>
