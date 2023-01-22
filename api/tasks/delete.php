<?php

error_reporting(E_ALL);
ini_set('display_error', 1);

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


if(isset($data))
{
     // Deleting post from user input.

    if($task->delete_task($data->task_id))
    {
        echo json_encode(['message' => 'Task Deleted successfully']);
    }
}