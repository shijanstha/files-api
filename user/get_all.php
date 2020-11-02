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

// prepare product object
$user = new User($db);

// read the details of product to be edited
$stmt = $user->fetchAllUsers();
$num = $stmt->rowCount();

if ($num > 0) {

    $users_arr = array();
    $users_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $user_row = array(
            "id" => $id,
            "name" => $name,
            "address" => $address,
            "user_name" => $user_name,
            "password" => $password,
            "contactno" => $contactno,
            "bank_id" => $bank_id,
            "bank_name" => $bank_name
        );

        array_push($users_arr["records"], $user_row);
    }

    http_response_code(200);
    echo json_encode($users_arr);
} else {
    http_response_code(404);

    echo json_encode(
        array("message" => "No user found.")
    );
}
?>