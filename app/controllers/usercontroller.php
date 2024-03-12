<?php

namespace Controllers;

use Exception;
use Models\User;
use Services\UserService;
use \Firebase\JWT\JWT;

class UserController extends Controller
{
    private $service;
    private $jwtSecret;

    function __construct()
    {
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

        $user = $this->createObjectFromPostedJson("Models\\User");
        $user = $this->service->checkUsernamePassword($user->email, $user->password_hash);

        if(!$user){
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

    function generateJWT($user){
        $payload = array (
            "iss" => $issuer,
            "aud" => $audience, 
            "iat" => $issuedAt,
            "nbf" => $notbefore,
            "exp" => $expire,
            "data" => array (
                "id" => $user->id,
                "username" => $user->username,
                "email" => $user->email
            ));

        )
        $jwt = JWT::encode ($payload, $secret_key, 'HS256');

        return array(
            "message" => "succesful login",
            "jwt" => $jwt,
            "username" => $user->username,
            "expiredAt" => $expire
        )
    }
}
