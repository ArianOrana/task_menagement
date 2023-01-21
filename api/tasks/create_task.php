<?php

error_reporting(E_ALL);
error_reporting(-1);
ini_set('error_reporting', E_ALL);
// Headers

Header('Access-Control-Allow-Origin: *');
Header('Content-Type: application/json');
Header('Access-Control-Allow-Method: TASK');

// Including required files.
include_once('../../config/db.php');
include_once('../../models/tasks.php');

// Connecting with database.

$database = new Database;
$db =  $database->connect();

$task = new Task($db);
$data = json_decode(file_get_contents("php://input"));
if(count($_POST)){
    

    // Creating new task.

    $params = [
        'tasks_name' => $_POST['tasks_name'],
        'tasks_description' => $_POST['tasks_description'],
    ];

    if($task->create_new_task($params))
    {
        echo json_encode(['message' => 'Task added successfully']);
    }
}
else if(isset($data))
{
     // Creating new task.

     $params = [
        'tasks_name' => $data->tasks_name,
        'tasks_description' => $data->tasks_description,
    ];

    if($task->create_new_task($params))
    {
        echo json_encode(['message' => 'Task added successfully']);
    }
}