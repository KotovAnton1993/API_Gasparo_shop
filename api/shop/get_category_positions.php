<?php
// требуемые заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// подключение к БД
// файлы, необходимые для подключения к базе данных
//include_once 'validate_token.php';
include_once '../config/database.php';
include_once '../objects/position.php';
$page = json_decode(htmlspecialchars(strip_tags($_GET['page'])));
$data = json_decode(file_get_contents("php://input"));
$pageNumber = 3;
// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();
$position = new Position($db);

    $position -> id = $data -> id;
    $position -> category = $data -> category;

$stmt_all =$position->getTotalPositionPagesUser();
$num_all = $stmt_all->fetchColumn();
$totalPages = ceil($num_all/ $pageNumber);

if(isset($page) && is_numeric($page) && $page >0 && $totalPages >= $page){
    $fromPage = ($page - 1) * $pageNumber;
    $position -> fromPage = $fromPage;
    $position -> pageNumber = $pageNumber;


 } else {
    $page=1;
    $fromPage = ($page - 1) * $pageNumber;
    $position -> fromPage = $fromPage;
    $position -> pageNumber = $pageNumber;
    }



$stmt = $position->getAllPositionsUser();
$num = $stmt->rowCount();
$data = array();

// создание позиции
if ($num>0) {
    $position_arr = array();
    $position_arr["data"]=array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    extract($row);

    $position_items=array(
        "id" => $id,
        "name" => $name,
        "description" => $description,
        "quantity" => $quantity,
        "price_first" => $price_first,
        "lust_update" => $lust_update,
        "image_item" => $image_item,
        "category" => $category,
        "status" => $status
    );
    array_push($position_arr["data"], $position_items);
    }
$position_arr += ['total_pages' => $totalPages];
$position_arr += ['current_page' => $page];
$position_arr += ['per_page' => $pageNumber];

    // устанавливаем код ответа
    http_response_code(200);
    echo json_encode($position_arr);
}
else {
    http_response_code(404);
    echo json_encode(array("error" => "something went wrong"));
    exit();
}

?>