<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Credentials: true");

// get database connection
include_once '../config/database.php';

// instantiate product object
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

// initialize object
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->name) &&
    !empty($data->address) &&
    !empty($data->user_name) &&
    !empty($data->password) &&
    !empty($data->contact_no) &&
    !empty($data->bank_id) 
) {

    $user->name = $data->name;
    $user->address = $data->address;
    $user->user_name = $data->user_name;
    $user->password = $data->password;
    $user->contactno = $data->contact_no;
    $user->bank_id = $data->bank_id;

    if ($user->createUser()) {
        http_response_code(201);
        echo json_encode(array("message" => "User was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create user."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
}
?>