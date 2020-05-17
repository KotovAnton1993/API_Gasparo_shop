<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$category_photo_path = 'C:\OSPanel\domains\GasparoDelivery.com\category_img/';
// файлы, необходимые для подключения к базе данных
include_once '../config/database.php';
include_once '../objects/category.php';
include_once '../actions/imageUploader.php';



// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// создание объекта 'User'

$category = new Category($db);

$data = json_decode($_POST["data"]);

$category -> id = $data -> id;
$category -> category = $data -> category;
$category -> description = $data -> description;
$category -> image_category = $fullFilePath;

if(
    !empty($category-> id)&&
    !empty($category-> category)&&
    !empty($category-> description)&&
    !empty($category -> image_category)&&
    $category->update()
)

{
    // устанавливаем код ответа
    http_response_code(200);
    // покажем сообщение о том, что пользователь был создан
    echo json_encode(array("message" => "Категория изменена"));
}

// сообщение, если не удаётся создать пользователя
else {
    // устанавливаем код ответа
    http_response_code(400);
    // покажем сообщение о том, что создать пользователя не удалось
    echo json_encode(array("message" => "Невозможно изменить  категорию"));
    exit();
}

?>