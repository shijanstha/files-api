<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

include_once '../objects/file.php';

$database = new Database();
$db = $database->getConnection();

// initialize object
$file = new File($db);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->name) &&
    !empty($data->size) &&
    !empty($data->file_path) &&
    !empty($data->bank_id)
) {

    $file->name = $data->name;
    $file->size = $data->size;
    $file->file_path = $data->file_path;
    $file->bank_id = $data->bank_id;

    if ($file->creatFile()) {
        http_response_code(201);
        echo json_encode(array("message" => "File was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create file."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create file. Data is incomplete."));
}
?>