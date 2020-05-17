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
include_once 'config/database.php';
include_once 'objects/order.php';
include_once 'objects/user.php';
include_once 'objects/position.php';
$database = new Database();
$db = $database->getConnection();
$data = json_decode(file_get_contents("php://input"));

$order = new Order($db);
$order -> id_user = $data-> id_user;

$stmt = $order-> getUserOrders();
$num= $stmt->rowCount();

if($num>0){
    $orders_arr = array();
    $orders_arr['response']=array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
     extract($row);
//print_r ($row);

    $orders_items = array(
        "id" => $id,
        "product_list" => $product_list = json_decode($product_list),
        "total" => $total,
        "status" => $status,
        "created" => $created
    );
    array_push($orders_arr["response"], $orders_items);
    }
    http_response_code(200);
    echo json_encode($orders_arr);
} else{
    http_response_code(400);
    echo json_encode(array("error" => "wrong set information"));
    exit();
}

?>