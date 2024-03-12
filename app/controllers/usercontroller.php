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
        $user = $this->service->checkUsernamePassword($postedUser->email, $postedUser->password);
    
        if (!$user) {
            $this->respondWithError(401, "Invalid Login");
            return;
        }
    
        $tokenResponse = $this->generateJWT($user);
        $this->respond($tokenResponse);
    }
    

    public function update($id)
    {
        try {
            $user = $this->createObjectFromPostedJson("Models\\User");
            $user->user_id = $id;
            $user = $this->service->update($user);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond($user);
    }

    public function delete($id)
    {
        try {
            $this->service->delete($id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond(['success' => true]);
    }




    public function getOne($user_id)
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

            $users = $this->service->getAll();
            $this->respond($users);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }



    function generateJWT($user)
    {
        $issuedAt = time();
        $notbefore = $issuedAt;
        $expire = $issuedAt + 600;
        $issuer = 'localhost.com';
        $audience = 'localhost.com';

        $payload = array(
            "iss" => $issuer,
            "aud" => $audience,
            "iat" => $issuedAt,
            "nbf" => $notbefore,
            "exp" => $expire,
            "data" => array(
                "id" => $user->id,
                "username" => $user->username,
                "email" => $user->email,
                "role" => $user->role
            )
        );

        $jwt = JWT::encode($payload, $this->jwtSecret, 'HS256');

        return array(
            "message" => "Successful login.",
            "jwt" => $jwt,
            "username" => $user->username,
            "expireAt" => $expire
        );
    }
}
