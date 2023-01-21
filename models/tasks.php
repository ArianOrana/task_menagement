<?php
error_reporting(E_ALL);
error_reporting(-1);
ini_set('error_reporting', E_ALL);

class Task {
    // Database data.
    private $connection;
    private $table = 'tasks';


    // Task Properties
    public $task_id;
    public $tasks_name;
    public $tasks_description;
    public $creation_time;


    public function __construct($db)
    {
        $this->connection = $db; 
    }


    // Get all list of tasks.


    public function readTasks()
    {
        // Query to get tasks data.


        $query = 'SELECT tasks.*  FROM '.$this->table.' ';

       // SELECT tasks.*, child_tasks.* FROM child_tasks JOIN tasks ON child_tasks.tasks_id = tasks.task_id WHERE child_tasks.tasks_id = tasks.task_id;


        $task = $this->connection->prepare($query);
        
        $task->execute();


        return $task;
    }


    // Get single task.


/*     public function read_single_task($task_id)
    {
        $this->task_id = $task_id;
        // Query to get tasks data.
        
        $query = 'SELECT 
            c.name as category,
            p.id,
            p.category_id,
            p.tasks_name,
            p.tasks_description,
            p.created_at
            FROM
            '.$this->table.' p LEFT JOIN
            category c 
            ON p.category_id = c.id
            WHERE p.id= ?
            LIMIT 0,1';
            
        $task = $this->connection->prepare($query);
        
        //$task->bindParam(9, $this->task_id);
        
        $task->execute([$this->task_id]);


        return $task;
       
    } */


    // Insert a new task.
    
    public function create_new_task($params)
    {
        try
        {
            $this->tasks_name       = $params['tasks_name'];
            $this->tasks_description = $params['tasks_description'];

    
            $query = 'INSERT INTO '. $this->table .' 
                SET
                tasks_name = :tasks_name,
                tasks_description = :tasks_description';
           
            $statement = $this->connection->prepare($query);
                    
            $statement->bindValue('tasks_name', $this->tasks_name);
            $statement->bindValue('tasks_description', $this->tasks_description);
            
            if($statement->execute())
            {
                return true;
            }
    
            return false;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }


    // Update a new task.
    
    public function update_new_task($params)
    {
        try
        {
            $this->task_id          = $params['task_id'];
            $this->tasks_name       = $params['tasks_name'];
            $this->tasks_description = $params['tasks_description'];
            
    
            $query = 'UPDATE '. $this->table .' 
                SET
                tasks_name = :tasks_name,
                tasks_description = :tasks_description
                WHERE task_id = :task_id';
           
            $statement = $this->connection->prepare($query);
            
            $statement->bindValue('task_id', $this->task_id);
            $statement->bindValue('tasks_name', $this->tasks_name);
            $statement->bindValue('tasks_description', $this->tasks_description);
            
            if($statement->execute())
            {
                return true;
            }
    
            return false;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    public function delete_task($task_id)
    {
        try
        {
              // Assigning values.

              $this->task_id = $task_id;

              // Query for updating existing record.

              $query = 'DELETE FROM '.$this->table.' 
                   WHERE task_id = :task_id';

              $post = $this->connection->prepare($query);

              $post->bindValue('task_id', $this->task_id);
              
              if($post->execute())
              {
                  return true;
              }

              return false;
        }
        catch(PDOExecption $e)
        {
            echo $e->getMessage();
        } 
    }
}