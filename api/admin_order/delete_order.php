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
include_once '../objects/order.php';
include_once '../objects/user.php';
include_once '../objects/position.php';
$database = new Database();
$db = $database->getConnection();
$data = json_decode(file_get_contents("php://input"));




//проверка существования юзера
$order = new Order($db);
$order -> id_order = $data -> id_order;
$order -> id_user = $data-> id_user;

$user = new User($db);
$user -> id_user = $data-> id_user;
$user -> email = $data -> email;


if(
!empty($order-> id_order)&&
!empty($order-> id_user)&&
!empty($user-> id_user)&&
!empty($user-> email)&&
$user->userFind()&&

$order->deleteOrder()
){
    http_response_code(200);
    echo json_encode(array("success" => "заказ был удалён"));
    exit();
} else{
        http_response_code(400);
        echo json_encode(array("error" => "что-то пошло не так"));
        exit();
}


?>