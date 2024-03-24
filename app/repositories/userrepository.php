<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;
use Models\User;

class UserRepository extends Repository
{
    function checkUsernamePassword($email, $password)
    {
        try {
            // retrieve the user with the given email
            $stmt = $this->connection->prepare("SELECT user_id, email, password_hash, name, role FROM Users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
            $user = $stmt->fetch();

            // verify if the password matches the hash in the database
            $result = $this->verifyPassword($password, $user->password_hash);

            if (!$result)
                return false;

            // do not pass the password hash to the caller
            $user->password_hash = "";

            return $user;

            
        } catch (PDOException $e) {
            // Handle exception
        }
    }


    function register(User $user)
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO Users (email, name, password_hash, role) VALUES (:email, :name, :password_hash, :role)");
            $stmt->bindParam(':email', $user->email);
            $stmt->bindParam(':name', $user->name);
            $stmt->bindParam(':password_hash', $user->password_hash);
            $stmt->bindParam(':role', $user->role);
            $stmt->execute();

            return $this->connection->lastInsertId();
        } catch (PDOException $e) {

        }
    }

    function update(User $user)
    {
        try {

            $stmt = $this->connection->prepare("UPDATE Users SET email = :email, name = :name, role = :role WHERE user_id = :user_id");
            $stmt->bindParam(':email', $user->email);
            $stmt->bindParam(':name', $user->name);
            $stmt->bindParam(':role', $user->role);
            $stmt->bindParam(':user_id', $user->user_id);
            $stmt->execute();

            return $stmt->rowCount();
        } catch (PDOException $e) {
            // Handle exception
        }
    }


    // Delete a user
    function delete($user_id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM Users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            return $stmt->rowCount();
        } catch (PDOException $e) {

        }
    }

    // hash the password (currently uses bcrypt)
    function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // verify the password hash
    function verifyPassword($input, $hash)
    {
        return password_verify($input, $hash);
    }
    public function getOne($user_id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT user_id, email, name, role FROM Users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function getUserById($userId){
        try {
           
            $stmt = $this->connection->prepare("SELECT * FROM Users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
            $user = $stmt->fetch();

            return $user;

            
        } catch (PDOException $e) {
            // Handle exception
        }
    }
    
    public function getAll($limit = 10, $offset = 0)
    {
        try {
          
            $stmt = $this->connection->prepare("SELECT user_id, email, name, role FROM Users LIMIT :limit OFFSET :offset");
            
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
     
            $stmt->execute();
   
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
    

}
