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
include_once '../objects/user.php';

$page = json_decode(htmlspecialchars(strip_tags($_GET['page'])));
$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$pageNumber = 5;



$stmt_all =$user->getTotalUsersPages();
$num = $stmt_all->fetchColumn();
$totalPages = ceil($num/ $pageNumber);

//пагинация
if(isset($page) && is_numeric($page) && $page >0 && $totalPages >= $page){
    $fromPage = ($page - 1) * $pageNumber;
    $user = new User($db);
    $user -> fromPage = $fromPage;
    $user -> pageNumber = $pageNumber;
 } else {
    $page=1;
    $fromPage = ($page - 1) * $pageNumber;
    $user = new User($db);
    $user -> fromPage = $fromPage;
    $user -> pageNumber = $pageNumber;
    }

$stmt = $user->getAllUsers();
$num_user = $stmt->rowCount();
$data = array();

// создание позиции
if ($num_user>0) {
    $user_arr = array();
    $user_arr["data"]=array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    extract($row);

    $user_items=array(
        "id" => $id,
        "organization" => $organization,
        "firstname" => $firstname,
        "lastname" => $lastname,
        "email" => $email,
        "phone" => $phone,
        "rights" => $rights,
        "updated" => $updated,
        "created" => $created
    );
    array_push($user_arr["data"], $user_items);
    }
$user_arr += ['total_pages' => $totalPages];
$user_arr += ['current_page' => $page];
$user_arr += ['per_page' => $pageNumber];
    // устанавливаем код ответа
    http_response_code(200);
    echo json_encode($user_arr);
}
else {
    http_response_code(404);
    echo json_encode(array("error" => "something went wrong"));
    exit();
}

?>