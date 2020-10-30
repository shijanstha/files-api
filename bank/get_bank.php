<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/bank.php';

$database = new Database();
$db = $database->getConnection();

$bank = new Bank($db);

$bank->bank_id = isset($_GET['id']) ? $_GET['id'] : die();

$bank->fetchBank();

if ($bank->bank_name != null) {

    $bank_arr = array(
        "bank_id" => $bank->bank_id,
        "bank_name" => $bank->bank_name
    );

    http_response_code(200);
    echo json_encode($bank_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Bank does not exist."));
}
?>