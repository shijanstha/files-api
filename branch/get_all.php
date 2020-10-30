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

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$branch = new Branch($db);

$branch->bank_id = isset($_GET['bank_id']) ? $_GET['bank_id'] : die();
// read the details of product to be edited
$stmt = $branch->getAllBranchesForBank();
$num = $stmt->rowCount();

if ($num > 0) {

    $branchs_arr = array();
    $branchs_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $branch_row = array(
            "id" => $id,
            "branch_name" => $branch_name,
            "bank_id" => $bank_id,
            "address" => $address
        );

        array_push($branchs_arr["records"], $branch_row);
    }

    http_response_code(200);
    echo json_encode($branchs_arr);

} else {
    http_response_code(404);

    echo json_encode(
        array("message" => "No branch found.")
    );
}
?>