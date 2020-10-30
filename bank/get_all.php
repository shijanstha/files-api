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

// get database connection
$database = new Database();
$db = $database->getConnection();

$bank = new Bank($db);

$stmt = $bank->fetchAllBanks();
$num = $stmt->rowCount();

if ($num > 0) {

    $banks_arr = array();
    $banks_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $bank_row = array(
            "id" => $bank_id,
            "bank_name" => $bank_name
        );

        array_push($banks_arr["records"], $bank_row);
    }

    http_response_code(200);
    echo json_encode($banks_arr);

} else {
    http_response_code(404);

    echo json_encode(
        array("message" => "No bank found.")
    );
}
?>