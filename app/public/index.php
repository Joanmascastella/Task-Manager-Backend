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
$router->put('/user/update/(\d+)', 'UserController@update');
$router->delete('/user/delete/(\d+)', 'UserController@delete');
$router->get('/user', 'UserController@getAll');
$router->get('/user/(\d+)', 'UserController@getOne');
$router->post('/refresh-token', 'UserController@refreshToken');


// Task Management endpoints
$router->post('/tasks', 'TaskController@create');
$router->get('/tasks', 'TaskController@getAll');
$router->get('/tasks/(\d+)', 'TaskController@getOne');
$router->put('/tasks/(\d+)', 'TaskController@update');
$router->delete('/tasks/(\d+)', 'TaskController@delete');
$router->post('/tasks/(\d+)/complete', 'TaskController@complete');
$router->get('/share/task/(\d+)', 'TaskController@shareSingle');
$router->put('/tasks/(\d+)/time', 'TaskController@updateTimeElapsed');

// List Management endpoints
$router->post('/lists', 'ListController@create');
$router->get('/lists', 'ListController@getAll');
$router->get('/lists/(\d+)', 'ListController@getOne');
$router->put('/lists/(\d+)', 'ListController@update');
$router->delete('/lists/(\d+)', 'ListController@delete');
$router->post('/lists/(\d+)/tasks', 'ListController@addTask');
$router->get('/share/list/(\d+)', 'ListController@share');


// Admin Analytics endpoints
$router->get('/analytics/users', 'UserController@getTotalActiveUsers');
$router->get('/analytics/tasks', 'UserController@getTotalTasks');
$router->get('/analytics/tasks/completed', 'UserController@getTotalCompletedTasks');


// userAnalytics endpoints
$router->get('/analytics/tasks/(\d+)', 'UserController@getTotalTasksForUser');
$router->get('/analytics/tasks/completed/(\d+)', 'UserController@getTotalCompletedTasksForUser');


// Run it!
$router->run();
?>