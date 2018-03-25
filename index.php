<?php

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    //If required
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
    }

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }

    exit(0);
}

// Connect to database
$host = "127.0.0.1";
$dbname = "awd_final";
$user_service = "root";
$pass = "";

# MySQL with PDO_MYSQL
$db_handle = new PDO("mysql:host=$host;dbname=$dbname", $user_service, $pass);
$db_handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

include_once('users.php');

$request_method = $_SERVER["REQUEST_METHOD"];
$data = json_decode(file_get_contents("php://input"));
$user_service = new Users;

switch ($request_method) {
    case 'GET':
        // Retrieve Users
        if (!empty($_GET["user_id"])) {
            $user_id = $_GET["user_id"];
            $user_service->getUsers($user_id);
        } else {
            $user_service->getUsers();
        }

        break;
    case 'POST':
        // Insert users
        $user_service->saveUser($data);

        break;
    case 'PUT':
        $user_service->updateUser($data);

        break;
    case 'DELETE':
        // Delete users
        $user_service->deleteUser($data);

        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");

        break;
}
