<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// файлы, необходимые для подключения к базе данных
include_once '../config/database.php';
include_once '../objects/user.php';


// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// создание объекта 'User'

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

$user -> id = $data -> id;
$user -> email = $data -> email;
if(
    !empty($user-> id)&&
    !empty($user-> email)&&
    $user->rejectUser()
)

{
    // устанавливаем код ответа
    http_response_code(200);
    // покажем сообщение о том, что пользователь был создан
    echo json_encode(array("success" => "User was rejected"));
    exit();
}

// сообщение, если не удаётся создать пользователя
else {
    // устанавливаем код ответа
    http_response_code(400);
    // покажем сообщение о том, что создать пользователя не удалось
    echo json_encode(array("error" => "something_went_wrong_approve_user"));
    exit();
}

?>