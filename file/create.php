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
$fileObj = new File($db);

// name of file
$filename = $_FILES['file']['name'];

// destination of the file on the server
$destination = 'uploads/' . $filename;
$uploadDestination = '../uploads/' . $filename;

// the physical file on a temporary uploads directory on the server
$file = $_FILES['file']['tmp_name'];


if (
    !empty($_POST["name"]) &&
    !empty($_POST["bank_id"])
) {

    $fileObj->name = $_POST["name"];
    $fileObj->bank_id = $_POST["bank_id"];
    $fileObj->file_path = $destination;

    if (move_uploaded_file($file, $uploadDestination)) {
        if ($fileObj->createFile()) {
            http_response_code(201);
            echo json_encode(array("message" => "File was created."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create file."));
        }
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create file. Data is incomplete."));
}