<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;
use Models\User;

class UserRepository extends Repository
{
    function checkUsernamePassword($username, $password)
    {
        try {
            // retrieve the user with the given username
            $stmt = $this->connection->prepare("SELECT id, username, password, email FROM user WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
            $user = $stmt->fetch();

            // verify if the password matches the hash in the database
            $result = $this->verifyPassword($password, $user->password);

            if (!$result)
                return false;

            // do not pass the password hash to the caller
            $user->password = "";

            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }
    
    function register(User $user)
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO Users (email, name, password_hash, daily_time_goal) VALUES (:email, :name, :password_hash, :daily_time_goal)");
            $stmt->bindParam(':email', $user->email);
            $stmt->bindParam(':name', $user->name);
            $stmt->bindParam(':password_hash', $this->hashPassword($user->password_hash));
            $stmt->bindParam(':daily_time_goal', $user->daily_time_goal);
            $stmt->execute();

            return $this->connection->lastInsertId();
        } catch (PDOException $e) {

        }
    }
    function update(User $user)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE Users SET email = :email, name = :name, daily_time_goal = :daily_time_goal WHERE user_id = :user_id");
            $stmt->bindParam(':email', $user->email);
            $stmt->bindParam(':name', $user->name);
            $stmt->bindParam(':daily_time_goal', $user->daily_time_goal);
            $stmt->bindParam(':user_id', $user->user_id);
            $stmt->execute();

            return $stmt->rowCount();
        } catch (PDOException $e) {

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


}
