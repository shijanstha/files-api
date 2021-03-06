<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/file.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

$file = new File($db);

$file->id = isset($_GET['id']) ? $_GET['id'] : die();

$row = $file->fetchFile();

if ($file->file_path != null) {
    $file_arr = array(
        "id" => $file->id,
        "name" => $file->name,
        "file_path" => $file->file_path,
        "bank_id" => $file->bank_id,
        "upload_date" => $file->upload_date,
        "bank_name" => $row['bank_name']
    );

    http_response_code(200);
    echo json_encode($file_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "File does not exist."));
}
