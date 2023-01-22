<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Method: USER');

session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}
require_once('../../config/db.php');
require_once('../../models/users.php');

// get the raw input data
$raw_input = file_get_contents('php://input');

// check for json errors
$data = json_decode($raw_input, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    // handle json error
    die("Invalid json format");
}

// read the input data
$email = $data['email'];
$password = $data['password'];

// validate the email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('Invalid email');
}

// create a new user
$user = new User();

// login
if ($user->login($email, $password)) {
    $_SESSION['message'] = "Logged in successfully";
    header("Location: dashboard.php");
} else {
    $_SESSION['message'] = "Invalid email or password";
    header("Location: login.php");
}
