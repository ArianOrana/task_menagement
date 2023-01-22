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

if(isset($_GET['task_id']))
{
$data = $task->read_single_task($_GET['task_id']);

if($data->rowCount())
{
$tasks = $data->fetch(PDO::FETCH_OBJ);
echo json_encode($tasks);
}
else
{
echo json_encode(['message' => ' No task data found']);
}
}

