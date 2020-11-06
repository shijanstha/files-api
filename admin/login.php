<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/admin.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

$login = new Admin($db);

$login->user_name = $_POST["user_name"];
$login->password = $_POST["password"];

$stmt = $login->checkLogin();
$num = $stmt->rowCount();

if ($num > 0) {
    http_response_code(200);
    echo json_encode(array("message" => "Login Successful.", "login" => "ok"));
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Invalid username or password."));
}
