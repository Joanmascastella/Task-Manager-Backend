<?php

namespace Controllers;

require __DIR__ . '/../vendor/autoload.php';
use Exception;
use Models\User;
use Services\UserService;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class UserController extends Controller
{
    private $service;

    function __construct()
    {
        parent::__construct();
        $this->service = new UserService();
    }

    public function register()
    {
        try {
            $user = $this->createObjectFromPostedJson("Models\\User");
            $user->password_hash = $this->service->hashPassword($user->password_hash);
            $user = $this->service->register($user);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond($user);
    }

    public function login()
    {
        $postedUser = $this->createObjectFromPostedJson("Models\\User");
        $user = $this->service->checkUsernamePassword($postedUser->email, $postedUser->password_hash);

        if (!$user) {
            $this->respondWithError(401, "Invalid Login");
            return;
        }
        try {
            $tokenResponse = $this->generateJWT($user);
            $this->respond($tokenResponse);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }


    }


    public function update($user_id)
    {
        $decoded = $this->checkForJwt();
        if (!($decoded->data->role == "admin" || $decoded->data->id == $user_id)) {
            $this->respondWithError(403, "Forbidden - You can only update your own account unless you're an admin.");
            return;
        }

        try {
            $userData = $this->createObjectFromPostedJson("Models\\User");
            $userData->user_id = $user_id;
            $user = $this->service->update($userData);
            $this->respond($user);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }


    public function delete($user_id)
    {
        $decoded = $this->checkForJwt();

        if (!($decoded->data->role == "admin" || $decoded->data->id == $user_id)) {
            $this->respondWithError(403, "Forbidden - You can only delete your own account.");
            return;
        }

        try {
            $this->service->delete($user_id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond(['success' => true]);
    }




    public function getOne($user_id)
    {
        $decoded = $this->checkForJwt();

        if ($decoded->data->id != $user_id) {
            $this->respondWithError(403, "Forbidden - You can only access your own account.");
            return;
        }
        try {
            $user = $this->service->getOne($user_id);
            if ($user) {
                $this->respond($user);
            } else {
                $this->respondWithError(404, "User not found");
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }


    public function getAll()
    {
        try {
            $decoded = $this->checkForJwt();
            if (!$decoded) {
                return;
            }

            if ($decoded->data->role !== 'admin') {
                $this->respondWithError(403, "Unauthorized access. Admin role required.");
                return;
            }

            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; 
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0; 
    

            $users = $this->service->getAll($limit, $offset);
            $this->respond($users);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function refreshToken()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $refreshToken = $data['refreshToken'];

        try {
            $decoded = JWT::decode($refreshToken, new Key($this->jwtSecret, 'HS256'));
            $userId = $decoded->data->id;
            $user = $this->service->getUserById($userId);

            if (!$user) {
                $this->respondWithError(401, "Invalid user");
                return;
            }

            $tokenResponse = $this->generateJWT($user);
            $this->respond($tokenResponse);

        } catch (Exception $e) {
            $this->respondWithError(401, "Invalid token");
        }
    }
    function getTotalActiveUsers()
    {
        $decoded = $this->checkForJwt();
        if (!($decoded->data->role == "admin")) {
            $this->respondWithError(403, "Forbidden - You can only access analytics if admin.");
            return;
        }
        try {
            
            $activeusers = $this->service->getTotalActiveUsers();
            $this->respond($activeusers);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }


    }

    function getTotalTasks()
    {
        $decoded = $this->checkForJwt();
        if (!($decoded->data->role == "admin")) {
            $this->respondWithError(403, "Forbidden - You can only access analytics if admin.");
            return;
        }
        try {
            $totaltasks = $this->service->getTotalTasks();
            $this->respond($totaltasks);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

    }

    function getTotalCompletedTasks()
    {
        $decoded = $this->checkForJwt();
        if (!($decoded->data->role == "admin")) {
            $this->respondWithError(403, "Forbidden - You can only access analytics if admin.");
            return;
        }
        try {
            $totalcompleted = $this->service->getTotalCompletedTasks();
            $this->respond($totalcompleted);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

    }


    function getTotalTasksForUser($id)
    {
        $decoded = $this->checkForJwt();

        if ($decoded->data->id != $id) {
            $this->respondWithError(403, "Forbidden - You can only access your own account.");
            return;
        }

        try {
            $user = $this->service->getTotalTasksForUser($id);
            if ($user) {
                $this->respond($user);
            } else {
                $this->respondWithError(404, "User not found");
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

    }

    function getTotalCompletedTasksForUser($id)
    {

        $decoded = $this->checkForJwt();

        if ($decoded->data->id != $id) {
            $this->respondWithError(403, "Forbidden - You can only access your own account.");
            return;
        }

        try {
            $user = $this->service->getTotalCompletedTasksForUser($id);
            if ($user) {
                $this->respond($user);
            } else {
                $this->respondWithError(404, "User not found");
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

    }


    public function generateJWT($user)
    {
        $issuedAt = time();
        $accessExpire = $issuedAt + 5600; // 1 hour for access token
        $refreshExpire = $issuedAt + 1209600; // 2 weeks for refresh token

        $accessToken = JWT::encode([
            "iss" => 'localhost.com',
            "aud" => 'localhost.com',
            "iat" => $issuedAt,
            "nbf" => $issuedAt,
            "exp" => $accessExpire,
            "data" => [
                "id" => $user->user_id,
                "username" => $user->name,
                "email" => $user->email,
                "role" => $user->role
            ]
        ], $this->jwtSecret, 'HS256');

        $refreshToken = JWT::encode([
            "iss" => 'localhost.com',
            "aud" => 'localhost.com',
            "iat" => $issuedAt,
            "exp" => $refreshExpire,
            "data" => [
                "id" => $user->user_id
            ]
        ], $this->jwtSecret, 'HS256');

        return [
            "authToken" => $accessToken,
            "refreshToken" => $refreshToken,
            "expiresIn" => $accessExpire
        ];
    }


}
