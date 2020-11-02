<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$user->id = isset($_GET['id']) ? $_GET['id'] : die();

$user->fetchUser();

if ($user->user_name != null) {
    // create array
    $user_arr = array(
        "id" => $user->id,
        "name" => $user->name,
        "address" => $user->address,
        "user_name" => $user->user_name,
        "password" => $user->password,
        "contactno" => $user->contactno,
        "bank_id" => $user->bank_id,
        "bank_name" => $user->bank_name
    );

    http_response_code(200);
    echo json_encode($user_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "User does not exist."));
}
?>