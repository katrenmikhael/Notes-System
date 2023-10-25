<?php
include "./db.php";
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

//check if the method is post or not
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decode JSON data into PHP array
    $noteData = json_decode(file_get_contents('php://input'), true);
    $noteText = htmlspecialchars(trim($noteData['text']));
    $userId = htmlspecialchars(trim($noteData['userId']));

    if (empty($noteText)) {
        $data = [
            "status" => 201,
            "message" => "the Note text Required"
        ];
        echo json_encode($data);
    } elseif (empty($userId)) {
        $data = [
            "status" => 201,
            "message" => "the Id User is Required"
        ];
        echo json_encode($data);
    } else {
        addNote($conn, $noteText, $userId);
        $data = [
            "status" => 200,
            "message" => "Note Isnserted Successfully"
        ];
        echo json_encode($data);
    }
}

function addNote($conn, $noteText, $userId)
{
    $addNote = "INSERT INTO `notes` (text, user_id) values (?,?)";
    $pre = mysqli_prepare($conn, $addNote);
    mysqli_stmt_bind_param($pre, 'si', $noteText, $userId);
    mysqli_stmt_execute($pre);
    $res = mysqli_stmt_get_result($pre);
}
