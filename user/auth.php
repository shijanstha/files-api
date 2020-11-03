<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->user_name) &&
    !empty($data->password)
) {

    $user->user_name = $data->user_name;
    $user->password = $data->password;

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
    }
}
