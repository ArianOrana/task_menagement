<?php
require_once('../../config/db.php');
class User {
    public $id;
    public $name;
    public $last_name;
    public $email;
    public $password;

    // register a new user
    public function register() {
        $database = new Database();
        $connection = $database->connect();
        try {
            // insert the data into the users table
            $query = "INSERT INTO users (name, last_name, email, password) VALUES (:name, :last_name, :email, :password)";
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':last_name', $this->last_name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }

    public function login($email, $password) {
        $database = new Database();
        $connection = $database->connect();
        try {
            // select the user from the users table
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch();
            if ($user === false) {
                return false;
            }
            // verify the password
            if (password_verify($password, $user['password'])) {
                // start a new session
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['logged_in'] = true;
                return true;
            } else {
                return false;
            }
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    
public function update($id, $name, $last_name, $email) {
    $database = new Database();


        $connection = $database->connect();
        try {
            // update the user in the users table
            $query = "UPDATE users SET name = :name, last_name = :last_name, email = :email WHERE id = :id";
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':email', $email);
            
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
}
?>