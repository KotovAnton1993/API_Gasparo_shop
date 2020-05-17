<?php
// требуемые заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// подключение к БД
// файлы, необходимые для подключения к базе данных
//include_once 'validate_token.php';
include_once '../config/database.php';
include_once '../objects/position.php';
include_once '../actions/imageUploaderPositions.php';

// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// создание объекта 'User'
$position = new Position($db);

// получаем данные
$data = json_decode($_POST["data"]);

// устанавливаем значения

$position -> name = $data -> name;
$position -> description = $data -> description;
$position-> quantity = $data-> quantity;
$position -> price_first = $data -> price_first;
$position-> category = $data -> category;
$position -> image_item = $fullFilePath;

// создание позиции
if (
    !empty($position -> name)&&
    !empty($position -> quantity)&&
    !empty($position -> price_first)&&
    !empty($position -> category)&&
    !empty($position -> image_item)&&
    $position->create()

) {
    // устанавливаем код ответа
    http_response_code(200);
    // покажем сообщение о том, что пользователь был создан
    echo json_encode(array("message" => "Позиция была создана"));
}

// сообщение, если не удаётся создать пользователя
else {
    // устанавливаем код ответа
    http_response_code(400);
    // покажем сообщение о том, что создать пользователя не удалось
    echo json_encode(array("message" => "Невозможно создать позицию"));
    exit();
}

?>