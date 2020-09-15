<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

$user->id = $data->id;

$user->fname = $data->fname;
$user->lname = $data->lname;
$user->user_name = $data->user_name;
$user->password = $data->password;
$user->contactno = $data->contactno;
$user->bank_id = $data->bank_id;
$user->branch_id = $data->branch_id;
$user->role = $data->role;

if ($user->update()) {
    http_response_code(200);
    echo json_encode(array("message" => "User Detail updated."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to update user detail."));
}
?>