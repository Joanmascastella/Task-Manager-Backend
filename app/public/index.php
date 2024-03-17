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
$router->post('/register', 'UserController@register'); //done
$router->post('/login', 'UserController@login'); //done
$router->put('/user/update/(\d+)', 'UserController@update'); //done
$router->delete('/user/delete/(\d+)', 'UserController@delete'); //done
$router->get('/user', 'UserController@getAll'); //done
$router->get('/user/(\d+)', 'UserController@getOne'); //done 
$router->post('/refresh-token', 'UserController@refreshToken'); //done


// Task Management endpoints
$router->post('/tasks', 'TaskController@create'); //done
$router->get('/tasks', 'TaskController@getAll'); //done
$router->get('/tasks/(\d+)', 'TaskController@getOne'); // done
$router->put('/tasks/(\d+)', 'TaskController@update'); // done 
$router->delete('/tasks/(\d+)', 'TaskController@delete'); //done
$router->put('/tasks/(\d+)/(\w+)', 'TaskController@complete'); //done
$router->get('/share/task/(\d+)', 'TaskController@shareSingle'); // done maybe delete this feature

$router->put('/tasks/(\d+)/time', 'TaskController@updateTimeElapsed');

// List Management endpoints
$router->post('/lists', 'ListController@create'); // done
$router->get('/lists', 'ListController@getAll'); // done
$router->get('/lists/(\d+)', 'ListController@getOne'); // done
$router->put('/lists/(\d+)', 'ListController@update'); // done
$router->delete('/lists/(\d+)', 'ListController@delete'); // done
$router->post('/lists/(\d+)/tasks', 'ListController@addTask'); // done
$router->get('/share/list/(\d+)', 'ListController@share'); // done maybe delete this feature


// Admin Analytics endpoints
$router->get('/analytics/users', 'UserController@getTotalActiveUsers'); //done
$router->get('/analytics/tasks', 'UserController@getTotalTasks'); //done
$router->get('/analytics/tasks/completed', 'UserController@getTotalCompletedTasks'); //done


// userAnalytics endpoints
$router->get('/analytics/tasks/(\d+)', 'UserController@getTotalTasksForUser'); //done
$router->get('/analytics/tasks/completed/(\d+)', 'UserController@getTotalCompletedTasksForUser'); //done
 

// Run it!
$router->run();
?>