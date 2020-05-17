<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// файлы, необходимые для подключения к базе данных
include_once '../config/database.php';
include_once '../objects/position.php';


// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// создание объекта 'User'

$position = new Position($db);

$data = json_decode(file_get_contents("php://input"));

$position -> id = $data -> id;
$position -> category = $data -> category;
$position -> status = $data -> status;
if(
    !empty($position-> id)&&
    !empty($position-> category)&&
    $position->findPositionStatus()&&
    $position->activatePosition()
)

{
    // устанавливаем код ответа
    http_response_code(200);
    // покажем сообщение о том, что пользователь был создан
    echo json_encode(array("success" => "Position_was_activated"));
    exit();
}

// сообщение, если не удаётся создать пользователя
else {
    // устанавливаем код ответа
    http_response_code(400);
    // покажем сообщение о том, что создать пользователя не удалось
    echo json_encode(array("error" => "something_went_wrong_activate_position"));
    exit();
}

?>