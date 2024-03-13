<?php

namespace Controllers;

require __DIR__ . '/../vendor/autoload.php';
use Exception;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class Controller
{

    protected $jwtSecret;

    function __construct()
    {
        $this->jwtSecret = 'f9b6d9af573c257bea415f6027e957762cbacc14f2f1c9f6b58a8e6eafaa17bf';
    }
    function checkForJwt() {
        // Check for token header
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $this->respondWithError(401, "No token provided");
            return null;
        }
    
        // Read JWT from header
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        $parts = explode(" ", $authHeader);
    
        // Verify if the Authorization header is properly formatted
        if (count($parts) !== 2 || $parts[0] !== 'Bearer') {
            $this->respondWithError(401, "Invalid token format");
            return null;
        }
    
        $jwt = $parts[1];
    
        try {
            // Decode the token
            $decoded = JWT::decode($jwt, new Key($this->jwtSecret, 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            $this->respondWithError(401, $e->getMessage());
            return null;
        }
    }

    function respond($data)
    {
        $this->respondWithCode(200, $data);
    }

    function respondWithError($httpcode, $message)
    {
        $data = array('errorMessage' => $message);
        $this->respondWithCode($httpcode, $data);
    }

    private function respondWithCode($httpcode, $data)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($httpcode);
        echo json_encode($data);
    }

    function createObjectFromPostedJson($className)
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        $object = new $className();
        foreach ($data as $key => $value) {
            if(is_object($value)) {
                continue;
            }
            $object->{$key} = $value;
        }
        return $object;
    }
}
