<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
//include_once 'validate_token.php';
include_once '../config/database.php';
include_once '../objects/category.php';


$page = json_decode(htmlspecialchars(strip_tags($_GET['page'])));
$database = new Database();
$db = $database->getConnection();
$category = new Category($db);
$pageNumber = 10;


$stmt_all =$category->getTotalCategoryPagesUser();
$num_all = $stmt_all->fetchColumn();
$totalPages = ceil($num_all/ $pageNumber);


if(isset($page) && is_numeric($page) && $page >0 && $totalPages >= $page){
    $fromPage = ($page - 1) * $pageNumber;
    $category = new Category($db);
    $category -> fromPage = $fromPage;
    $category -> pageNumber = $pageNumber;

 } else {
    $page=1;
    $fromPage = ($page - 1) * $pageNumber;
    $category = new Category($db);
    $category -> fromPage = $fromPage;
    $category -> pageNumber = $pageNumber;
    }

$stmt = $category->getAllCategoryUser();
$num_category = $stmt->rowCount();
$data = array();

if ($num_category>0) {
    $category_arr = array();
    $category_arr["data"]=array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    extract($row);

    $category_items=array(
        "id" => $id,
        "category" => $category,
        "description" => $description,
        "image_category" => $image_category,
        "status" => $status
    );
    array_push($category_arr["data"], $category_items);
    }
$category_arr += ['total_pages' => $totalPages];
$category_arr += ['current_page' => $page];
$category_arr += ['per_page' => $pageNumber];

    // устанавливаем код ответа
    http_response_code(200);
    echo json_encode($category_arr);
}
else {
    http_response_code(404);
    echo json_encode(array("error" => "something went wrong"));
    exit();
}


?>