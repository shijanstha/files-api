<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if (
    !empty($_POST["user_name"]) &&
    !empty($_POST["password"])
) {

    $user->user_name = $_POST["user_name"];
    $user->password = $_POST["password"];

    $user->authUser();

    if ($user->id != null) {
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
        echo json_encode(['message' => 'Authentication Successful.', 'data' => $user_arr]);
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'Authentication Failed.']);
    }
}
