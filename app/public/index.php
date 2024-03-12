<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . '/../vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();

$router->setNamespace('Controllers');

// User Management endpoints
$router->post('/register', 'UserController@register');
$router->post('/login', 'UserController@login');
$router->put('/user/update', 'UserController@update');
$router->delete('/user/delete', 'UserController@delete');

// Task Management endpoints
$router->post('/tasks', 'TaskController@create');
$router->get('/tasks', 'TaskController@getAll');
$router->get('/tasks/(\d+)', 'TaskController@getOne');
$router->put('/tasks/(\d+)', 'TaskController@update');
$router->delete('/tasks/(\d+)', 'TaskController@delete');
$router->post('/tasks/(\d+)/complete', 'TaskController@complete');

// Activity and Goals endpoints
$router->get('/user/daily-goal', 'ActivityController@getDailyGoal');
$router->put('/user/daily-goal', 'ActivityController@updateDailyGoal');
$router->get('/user/streak', 'ActivityController@getStreak');
$router->get('/user/activity-log', 'ActivityController@getActivityLog');

// Run it!
$router->run();
?>
