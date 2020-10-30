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

$stmt = $file->fetchAllFiles();
$num = $stmt->rowCount();

if ($num > 0) {

    $files_arr = array();
    $files_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $file_row = array(
            "id" => $id,
            "name" => $name,
            "file_path" => $file_path,
            "bank_id" => $bank_id,
            "bank_name" => $bank_name,
            "branch_id" => $branch_id,
            "branch_name" => $branch_name,
        );

        array_push($files_arr["records"], $file_row);
    }
    http_response_code(200);
    echo json_encode($files_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No user found.")
    );
}
?>