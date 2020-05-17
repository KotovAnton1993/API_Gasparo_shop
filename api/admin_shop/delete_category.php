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
include_once '../objects/category.php';


// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// создание объекта 'User'
$category = new Category($db);

// получаем данные
$data = json_decode(file_get_contents("php://input"));

// устанавливаем значения
$category -> id = $data -> id;
$category -> category = $data -> category;
// создание позиции
if (
    !empty($category -> id)&&
    !empty($category -> category)&&
    $category->delete()

) {
    // устанавливаем код ответа
    http_response_code(200);
    // покажем сообщение о том, что пользователь был создан
    echo json_encode(array("message" => "Категория была удалена"));
}

// сообщение, если не удаётся создать пользователя
else {
    // устанавливаем код ответа
    http_response_code(400);
    // покажем сообщение о том, что создать пользователя не удалось
    echo json_encode(array("message" => "Невозможно удалить  категорию"));
    exit();
}

?>