<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/bank.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

$bank = new Bank($db);

$data = json_decode(file_get_contents("php://input"));

$bank->bank_id = $data->bank_id;

$bank->bank_id = $data->bank_id;
$bank->bank_name = $data->bank_name;

if ($bank->update()) {
    http_response_code(200);
    echo json_encode(array("message" => "Bank name updated."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to update bank name."));
}
?>