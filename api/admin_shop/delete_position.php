<?php
// требуемые заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// подключение к БД
// файлы, необходимые для подключения к базе данных
include_once '../config/database.php';
include_once '../objects/position.php';
//include_once 'validate_token.php';

// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// создание объекта 'User'
$position = new Position($db);

// получаем данные
$data = json_decode(file_get_contents("php://input"));

// устанавливаем значения
$position -> id = $data -> id;
$position -> name = $data -> name;
$position -> category = $data -> category;


// создание пользователя
if (
    !empty($position-> id)&&
    !empty($position-> name)&&
    !empty($position-> category)&&
    $position->delete()

) {
    // устанавливаем код ответа
    http_response_code(200);
    // покажем сообщение о том, что пользователь был создан
    echo json_encode(array("message" => "позиция удалена"));
}

// сообщение, если не удаётся создать пользователя
else {
    // устанавливаем код ответа
    http_response_code(400);
    // покажем сообщение о том, что создать пользователя не удалось
    echo json_encode(array("message" => "Ошибка удаления позиции"));
}

?>