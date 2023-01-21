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

$data = $task->readTasks();

// If there is tasks in database.

if($data->rowCount())
{
    $tasks = [];

    // re-aggrange the tasks data.

    while($row = $data->fetch(PDO::FETCH_OBJ))
    {
        $tasks[$row->task_id] = [
            'task_id' => $row->task_id,
            'tasks_name' =>  $row->tasks_name,
            'tasks_description' =>  $row->tasks_description,
            'creation_time' =>  $row->creation_time,
        ];
    }

    echo json_encode($tasks);


}
else
{
    echo json_encode(['message' => ' No tasks found']);
}