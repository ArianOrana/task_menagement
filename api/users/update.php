<?php

Header('Access-Control-Allow-Origin: *');
Header('Content-Type: application/json');
Header('Access-Control-Allow-Method: USER');

// include the necessary files
require_once('../../config/db.php');
require_once('../../models/users.php');
session_start();

// check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// get the input data
$id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
$last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

// validation
if (empty($name) || empty($last_name) || empty($email)) {
    $_SESSION['message'] = "All fields are required";
    header("Location: update.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['message'] = "Invalid email format";
    header("Location: update.php");
    exit;
}

// create a new user
$user = new User();

// update the user
if ($user->update($id, $name, $last_name, $email)) {
    $_SESSION['message'] = "User updated successfully";
    header("Location: dashboard.php");
    exit;
} else {
    $_SESSION['message'] = "Error updating user";
    header("Location: update.php");
    exit;
}
?>