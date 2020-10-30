<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

include_once '../objects/branch.php';

$database = new Database();
$db = $database->getConnection();

$branch = new Branch($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

if (
!empty($data->branch_name)
) {

    $branch->branch_name = $data->branch_name;
    $branch->bank_id = $data->bank_id;
    $branch->address = $data->address;

    if ($branch->createbranch()) {

        http_response_code(201);
        echo json_encode(array("message" => "Branch was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create branch."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create branch. Data is incomplete."));
}
?>