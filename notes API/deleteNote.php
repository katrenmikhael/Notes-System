<?php
include "./db.php";
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Methods: DELETE');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
//check if the method is post or not
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $noteData = json_decode(file_get_contents('php://input'), true);
    $noteId = htmlspecialchars(trim($noteData['noteId']));


    if (empty($noteId)) {
        $data = [
            "status" => 201,
            "message" => "note id is required",
        ];
        echo json_encode($data);
    } else {
        deleteNote($conn, $noteId);
        $data = [
            "status" => 200,
            "message" => "note deleted Successfully",
        ];
        echo json_encode($data);
    }
}
function deleteNote($conn, $noteId)
{
    $deleteNote = "DELETE from `notes` where id = ?";
    $pre = mysqli_prepare($conn, $deleteNote);
    mysqli_stmt_bind_param($pre, 'i', $noteId);
    mysqli_stmt_execute($pre);
}
