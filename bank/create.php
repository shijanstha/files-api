<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

include_once '../objects/bank.php';

$database = new Database();
$db = $database->getConnection();

$bank = new Bank($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

if (
!empty($data->bank_name)
) {

    $bank->bank_name = $data->bank_name;

    if ($bank->createBank()) {

        http_response_code(201);
        echo json_encode(array("message" => "Bank was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create bank."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create bank. Data is incomplete."));
}
?>