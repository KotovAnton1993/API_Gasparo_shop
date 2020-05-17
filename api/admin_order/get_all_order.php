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
//include_once 'objects/user.php';
//include_once 'objects/position.php';


$page = json_decode(htmlspecialchars(strip_tags($_GET['page'])));
$pageNumber = 3;
$database = new Database();
$db = $database->getConnection();
$order = new Order($db);





$stmt_all =$order->getTotalOrderPages();
$num_all = $stmt_all->fetchColumn();
$totalPages = ceil($num_all/ $pageNumber);


if(isset($page) && is_numeric($page) && $page >0 && $totalPages >= $page){
    $fromPage = ($page - 1) * $pageNumber;
    $order -> fromPage = $fromPage;
    $order -> pageNumber = $pageNumber;

 } else {
    $page=1;
    $fromPage = ($page - 1) * $pageNumber;
    $order -> fromPage = $fromPage;
    $order -> pageNumber = $pageNumber;
    }

$stmt = $order->getAllOrders();
$num_order = $stmt->rowCount();
$data = array();

if ($num_order>0) {
    $order_arr = array();
    $order_arr ["data"]=array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    extract($row);

    $order_items=array(
        "id" => $id,
        "id_user" => $id_user,
        "product_list" => json_decode($product_list),
        "total" => $total,
        "status" => $status,
        "created" => $created,
        "updated" => $updated
    );
    array_push($order_arr ["data"], $order_items);
    }
$order_arr  += ['total_pages' => $totalPages];
$order_arr  += ['current_page' => $page];
$order_arr  += ['per_page' => $pageNumber];

    // устанавливаем код ответа
    http_response_code(200);
    echo json_encode($order_arr );
}
else {
    http_response_code(404);
    echo json_encode(array("error" => "something went wrong"));
    exit();
}

?>