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
    $loginData = json_decode(file_get_contents('php://input'), true);
    //get user Data
    $email = htmlspecialchars(trim($loginData['email']));
    $password = htmlspecialchars(trim($loginData['password']));
    if (empty($email)) {
        $data = [
            "status" => 201,
            "message" => "Email is Required"
        ];
        echo json_encode($data);
    } elseif (empty($password)) {
        $data = [
            "status" => 201,
            "message" => "Password is Required"
        ];
        echo json_encode($data);
    } elseif (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email)) {
        $data = [
            'status' => 201,
            'message' => "email Is Invalid",
        ];
        echo json_encode($data);
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/", $password)) {
        $data = [
            'status' => 201,
            'message' => "password Is Invalid",
        ];
        echo json_encode($data);
    } else {
        $passwordInfo = checkMail($conn, $email);
        if (!empty($passwordInfo['password'])) {
            if (Password_Verify($password, $passwordInfo['password'])) {
                $data = [
                    "status" => 200,
                    "userId" => $passwordInfo['id'],
                ];
                echo json_encode($data);
            } else {
                $data = [
                    "status" => 201,
                    "message" => "this is Wrong Password",
                ];
                echo json_encode($data);
            }
        } else {
            $data = [
                "status" => 201,
                "message" => "this is Wrong Email",
            ];
            echo json_encode($data);
        }
    }
} else {
    $data = [
        "status" => 405,
        "message" => "Error On Request Type",
    ];
    echo json_encode($data);
}

function checkMail($conn, $email)
{
    $selectPassword = "SELECT `id`,`password` from `users` where email = ?";
    $pre = mysqli_prepare($conn, $selectPassword);
    mysqli_stmt_bind_param($pre, 's', $email);
    mysqli_stmt_execute($pre);
    $res = mysqli_stmt_get_result($pre);
    $passwordInfo = mysqli_fetch_array($res, MYSQLI_ASSOC);
    return $passwordInfo;
}
