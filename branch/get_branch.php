<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/branch.php';

$database = new Database();
$db = $database->getConnection();

$branch = new Branch($db);

$branch->id = isset($_GET['id']) ? $_GET['id'] : die();

$branch->fetchbranch();

if ($branch->branch_name != null) {

    $branch_arr = array(
        "bank_id" => $branch->bank_id,
        "branch_name" => $branch->branch_name,
        "address" => $branch->address
    );

    http_response_code(200);
    echo json_encode($branch_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Branch does not exist."));
}
?>