<?php
include "./db.php";
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Methods: PUT');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

//check if the method is post or not
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $noteData = json_decode(file_get_contents('php://input'), true);
    $noteText = htmlspecialchars(trim($noteData['text']));
    $noteId = htmlspecialchars(trim($noteData['noteId']));
    if (empty($noteText)) {
        $data = [
            "status" => 201,
            "message" => "Text is Empty"
        ];
        echo json_encode($data);
    } elseif (empty($noteId)) {
        $data = [
            "status" => 201,
            "message" => "note id is required"
        ];
        echo json_encode($data);
    } elseif (!checkChangeInNote($conn, $noteText, $noteId)) {
        $data = [
            "status" => 201,
            "message" => "note not change"
        ];
        echo json_encode($data);
    } else {
        updateNote($conn, $noteText, $noteId);
        $data = [
            "status" => 200,
            "message" => "Note is Updated Successfully"
        ];
        echo json_encode($data);
    }
}
function checkChangeInNote($conn, $noteText, $noteId)
{
    $selectText = "SELECT text from `notes` where id = ?";
    $pre = mysqli_prepare($conn, $selectText);
    mysqli_stmt_bind_param($pre, 'i', $noteId);
    mysqli_stmt_execute($pre);
    $res = mysqli_stmt_get_result($pre);
    $fetchNote = mysqli_fetch_array($res, MYSQLI_ASSOC);
    if ($fetchNote['text'] == $noteText) {
        return false;
    } else {
        return true;
    }
}
function updateNote($conn, $noteText, $noteId)
{
    $updateNote = "UPDATE notes set text = ? where id = ?";
    $pre = mysqli_prepare($conn, $updateNote);
    mysqli_stmt_bind_param($pre, 'si', $noteText, $noteId);
    mysqli_stmt_execute($pre);
}
