<?php

Header('Access-Control-Allow-Origin: *');
Header('Content-Type: application/json');
Header('Access-Control-Allow-Method: USER');

// include the necessary files
require_once('../../config/db.php');
require_once('../../models/users.php');

$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
$last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

// validate the email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('Invalid email');
}

// hash the password
$password = password_hash($password, PASSWORD_DEFAULT);

// create a new user
$user = new User();
$user->name = $name;
$user->last_name = $last_name;
$user->email = $email;
$user->password = $password;

// register the user
if ($user->register()) {
    echo json_encode(['message' => 'user added successfully']);
} else {
    $_SESSION['message'] = "Registration failed. Please try again";

}

?>
