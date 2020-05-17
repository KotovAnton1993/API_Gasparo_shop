<?php
// требуемые заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// подключение к БД
// файлы, необходимые для подключения к базе данных
//include_once '../validate_token.php';
include_once '../config/database.php';
include_once '../objects/category.php';
include_once '../actions/imageUploader.php';
include_once 'ftp_login.php';


// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// создание объекта 'User'
$category = new Category($db);
// получаем данные
$data = json_decode($_POST["data"]);

// устанавливаем значения
$category -> category = $data -> category;
$category -> description = $data -> description;
$category -> image_category = $fullFilePath;



// создание позиции
if (
    !empty($category -> category)&&
    !empty($category -> image_category)&&
    $category->create()

) {
    // устанавливаем код ответа
    http_response_code(200);
    // покажем сообщение о том, что пользователь был создан
    echo json_encode(array("message" => "Категория была создана"));
}

// сообщение, если не удаётся создать пользователя
else {
    // устанавливаем код ответа
    http_response_code(400);
    // покажем сообщение о том, что создать пользователя не удалось
    echo json_encode(array("message" => "Невозможно создать категорию"));
    exit();
}

?>