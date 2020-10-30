<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/file.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

$fileObj = new File($db);

$destination = "";
$uploadDestination = "";

$fileObj->id = $_POST["id"];

if ($_FILES['file']['name'] != null) {

    // name of file
    $filename = $_FILES['file']['name'];
    // destination of the file on the server
    $destination = 'uploads/' . $filename;
    $uploadDestination = '../uploads/' . $filename;
} else {
    $fileObj->fetchFile();
    $destination = $fileObj->file_path;
}

// the physical file on a temporary uploads directory on the server
$file = $_FILES['file']['tmp_name'];

$fileObj->name = $_POST["name"];
$fileObj->bank_id = $_POST["bank_id"];
$fileObj->branch_id = $_POST["branch_id"];
$fileObj->file_path = $destination;

if ($file != null) {
    if (move_uploaded_file($file, $uploadDestination)) {
        if ($fileObj->update()) {
            http_response_code(200);
            echo json_encode(array("message" => "File Detail updated."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update file detail."));
        }
    }
} else {
    if ($fileObj->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "File detail updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update file detail."));
    }
}