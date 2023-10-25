<?php
include "./db.php";
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

//check if the method is post or not
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Decode JSON data into PHP array
    $userData = json_decode(file_get_contents('php://input'), true);
    //get user Data
    $userId = htmlspecialchars(trim($_GET['userId']));
    if (empty($userId)) {
        $data = [
            "status" => 201,
            "message" => "user Id is Required"
        ];
        echo json_encode($data);
    } else {
        $notes = getAllNotes($conn, $userId);
        $data = [
            "status" => 200,
            "notes" => $notes
        ];
        echo json_encode($data);
    }
}
function getAllNotes($conn, $userId)
{
    $selectNotes = "SELECT id ,text from notes where user_id = ?";
    $pre = mysqli_prepare($conn, $selectNotes);
    mysqli_stmt_bind_param($pre, 'i', $userId);
    mysqli_stmt_execute($pre);
    $res = mysqli_stmt_get_result($pre);
    $notes = mysqli_fetch_all($res, MYSQLI_ASSOC);
    return $notes;
}
