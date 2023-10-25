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
    $response_data = json_decode(file_get_contents('php://input'), true);
    // echo $response_data['name'];


    $name = htmlspecialchars(trim($response_data['name']));
    $email = htmlspecialchars(trim($response_data['email']));
    $password = password_hash(htmlspecialchars(trim($response_data['password'])), PASSWORD_BCRYPT);
    if (empty($response_data['name'])) {
        $data = [
            'status' => 201,
            'message' => "Name Is Required",
        ];
        echo json_encode($data);
    } elseif (!preg_match("/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$/", $response_data['name'])) {
        $data = [
            'status' => 201,
            'message' => "Name Is Invalid",
        ];
        echo json_encode($data);
    } elseif (empty($response_data['email'])) {
        $data = [
            'status' => 201,
            'message' => "Email Is Required",
        ];
    } elseif (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $response_data['email'])) {
        $data = [
            'status' => 201,
            'message' => "email Is Invalid",
        ];
        echo json_encode($data);
    } elseif (empty($response_data['password'])) {
        $data = [
            'status' => 201,
            'message' => "Password Is Required",
        ];
        echo json_encode($data);
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/", $response_data['password'])) {
        $data = [
            'status' => 201,
            'message' => "password Is Invalid",
        ];
        echo json_encode($data);
    } elseif (checkEmail($conn, $email)) {
        $data = [
            'status' => 201,
            'message' => "This user is already Exist",
        ];
        echo json_encode($data);
    } else {
        insertUser($conn, $name, $email, $password);
        $data = [
            'status' => 200,
            'message' => "user Inserted Successfully",
        ];
        echo json_encode($data);
    }
} else {
    $data = [
        'status' => 405,
        'message' => "Error On Request Type",
    ];
    echo json_encode($data);
}

function checkEmail($conn, $email)
{
    $selectUser = "SELECT * from `users` where `email` =  ?";
    $pre = mysqli_prepare($conn, $selectUser);
    mysqli_stmt_bind_param($pre, 's', $email);
    mysqli_stmt_execute($pre);
    $res = mysqli_stmt_get_result($pre);
    $allUsers = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $row_num = mysqli_num_rows($res);

    return ($row_num > 0) ? true : false;
}

function insertUser($conn, $name, $email, $password)
{
    $insertUser = "INSERT INTO `users`(name , email , password) values(?,?,?)";
    $pre = mysqli_prepare($conn, $insertUser);
    mysqli_stmt_bind_param($pre, 'sss', $name, $email, $password);
    mysqli_stmt_execute($pre);
}
