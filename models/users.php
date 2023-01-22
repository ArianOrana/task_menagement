<?php
class Users {
    // Database data.
    private $connection;
    private $table = 'users';


    // users Properties
    public $id;
    public $name;
    public $last_name;
    public $email;
    public $password;


    public function __construct($db)
    {
        $this->connection = $db; 
    }


// Handle POST requests
function registerUsers()
{
    // Get the input data
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $name = $request->name;
    $last_name = $request->last_name;
    $email = $request->email;
    $password = $request->password;

    // Validate and sanitize input
    if (empty($name) || empty($last_name) || empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(["error" => "All fields are required"]);
        return;
    }
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid email format"]);
        return;
    }
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (strlen($password) < 8) {
        http_response_code(400);
        echo json_encode(["error" => "Password must be at least 8 characters long"]);
        return;
    }
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/", $password)) {
        http_response_code(400);
        echo json_encode(["error" => "Password must contain at least one uppercase letter, one lowercase letter, and one number"]);
        return;
    }
        // Hash the password
        $password = password_hash($password, PASSWORD_DEFAULT);
        // check if email already exists
        $stmt = $GLOBALS['pdo']->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result) {
            http_response_code(400);
            echo json_encode(["error" => "Email already exists"]);
            return;
        }
        // insert user into the database
        $stmt = $GLOBALS['pdo']->prepare("INSERT INTO users (name, last_name, email, password) VALUES (:name, :last_name, :email, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        http_response_code(201);
        echo json_encode(["message" => "User registered successfully"]);
    }
    
    // Handle GET requests
    function selectUsers()
    {
        // Get the user ID from the query string
        $user_id = $_GET['id'];
        // Get the user data from the database
        $stmt = $GLOBALS['pdo']->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch();
        if (!$user) {
            http_response_code(404);
            echo json_encode(["error" => "User not found"]);
            return;
        }
        http_response_code(200);
        echo json_encode($user);
    }
    
    // Handle PUT requests
    function updateUsers()
{
    // Get the input data
    $putdata = json_decode(file_get_contents("php://input"), true);
    $user_id = $putdata['id'];
    $name = $putdata['name'];
    $last_name = $putdata['last_name'];
    $email = $putdata['email'];
    $password = $putdata['password'];

    // check if user exists
    $stmt = $GLOBALS['pdo']->prepare("SELECT id FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch();
    if (!$user) {
        http_response_code(404);
        echo json_encode(["error" => "User not found"]);
        return;
    }
    //check if email already exists
    $stmt = $GLOBALS['pdo']->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch();
    if ($result) {
        http_response_code(400);
        echo json_encode(["error" => "Email already exists"]);
        return;
    }
    // update user info in the database
    $stmt = $GLOBALS['pdo']->prepare("UPDATE users SET name = :name, last_name = :last_name, email = :email, password = :password WHERE id = :id");
    $stmt->bindParam(':id', $user_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $password);
    } else {
        $stmt->bindParam(':password', $password, PDO::PARAM_NULL);
    }
    $stmt->execute();
    http_response_code(200);
    echo json_encode(["message" => "User updated successfully"]);
}


}