<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

$stmt = $user->authUser();
$num = $stmt->rowCount();

if (!empty($data->user_name) &&
    !empty($data->password)) {

    $user->user_name = $data->user_name;
    $user->password = $data->password;

    if($user->authUser()) {
        http_response_code(201);
        echo json_encode(array("message" => "Authentication Successful."));
    }
}