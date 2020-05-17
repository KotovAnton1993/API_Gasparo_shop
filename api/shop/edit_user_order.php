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
$user = new User($db);
$user -> id_user = $data-> id_user;
$user -> email = $data -> email;
if (
    !empty($user -> id_user)&&
    !empty($user -> email)&&
    $user->userFind()
    ){
    $position = new Position($db);
    $array=(array)$data->contents;
    foreach ($array as $key=> $value){
            $position -> id_item = $value-> id_item;
            $position -> name = $value-> name;
            $position -> quantity = $value-> quantity;
            $position -> price_first = $value-> price_first;
            $position -> category = $value-> category;
            //очистка пробелов
            $position -> quantity_buy= str_replace(" ","", $value-> quantity_buy);
     if(
            !empty($position-> id_item)&&
            !empty($position-> name)&&
            !empty($position-> quantity)&&
            !empty($position-> price_first)&&
            !empty($position-> category)&&
            !empty($position-> quantity_buy)&&
            //проверка что число целое
            intval($position-> quantity_buy)&&

            $position->findPositions()
            ){
             continue;


            } else{
            http_response_code(400);
            echo json_encode(array("error" => "введённые товары имеют неверные данные"));
            exit();
            }

    }
    } else{
    http_response_code(401);
    echo json_encode(array("error" => "Wrong set information"));
    exit();
          }
//проверка jhlthf
$order = new Order($db);
$order -> id_user = $data-> id_user;
$order -> id_order = $data-> id_order;
$test = json_encode($data->contents);
$order -> product_list = $test;
if($order->findWaitingOrder()){
//создание ордера

foreach ($array as $value){
        $total =$total + $value->quantity_buy * $value->price_first;
        $order-> total =$total;
        }
if(
$order->updateOrder()){
http_response_code(200);
    echo json_encode(array("success" => "заказ был успешно обновлен"));
    exit();
}
http_response_code(401);
    echo json_encode(array("error" => "something_went_wrong"));
    exit();
//создание позиций ордера (перевести список заказов в строку и вставить в бд)
} else {
http_response_code(400);
echo json_encode(array("error" => "заказ уже был принят в работу"));
exit();
}
?>