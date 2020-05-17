<?php
class Order{
private $conn;
private $table_one='orders';
private $table_two= 'users_table';
private $table_three= 'items';
private $table_four='order_items';
private $database= 'gasparodatabase';


public function __construct($db){
    $this->conn = $db;
}



function createOrder(){
    $create_order = "INSERT INTO ".$this-> table_one."
    SET id_user = :id_user, product_list = :product_list, total= :total, status = 'waiting', created= CURRENT_TIMESTAMP;
    ";
    $stmt_create_order=$this-> conn->prepare($create_order);
    $this-> id_user= htmlspecialchars(strip_tags($this-> id_user));

    $stmt_create_order->bindParam(':id_user', $this-> id_user);
    $stmt_create_order->bindParam(':product_list', $this-> product_list, PDO::PARAM_STR);
    $stmt_create_order->bindParam(':total', $this-> total);


    if(!$stmt_create_order->execute()){
    http_response_code(401);
    echo json_encode(array("error" => "something"));
    exit();
}  else {return true;}
}




public function findOrder(){
    $find_order = "SELECT id FROM ".$this-> table_one."
                    WHERE id = :id_order";
    $stmt=$this-> conn-> prepare($find_order);
    $this-> id_order = htmlspecialchars(strip_tags($this-> id_order));
    $stmt->bindParam(':id_order', $this-> id_order);
    $stmt->execute();
    $num = $stmt->rowCount();
    if($num>0){
    return true;
    }else {return false;}
}

public function cancelOrder(){
    $cancel_order = "UPDATE ".$this-> table_one." SET status = 'canceled', updated = CURRENT_TIMESTAMP WHERE id = :id_order AND status= 'waiting'";
    $stmt=$this-> conn->prepare($cancel_order);
    $this-> id_order= htmlspecialchars(strip_tags($this-> id_order));
    $stmt->bindParam(':id_order', $this-> id_order);
    $stmt->execute();
    $num = $stmt->rowCount();

    if($num >0 ){
    return true;} else {
    http_response_code(400);
    echo json_encode(array("error" => "order wasn't found"));
    exit();
    }
}

public function getUserOrders(){
    $getOrders = "SELECT id, id_user, product_list, total, status, created FROM ".$this-> table_one."
                  WHERE id_user= :id_user";
                  $stmt= $this->conn->prepare($getOrders);
                  $this-> id_user = htmlspecialchars(strip_tags($this-> id_user));
                  $stmt->bindParam(':id_user', $this-> id_user);

                  if($stmt->execute()){
                  return $stmt;
                  } else {
                  http_response_code(400);
                  echo json_encode(array("error"=>"something_went_wrong"));
                  exit();
                  }

}

public function updateOrder(){
    $updateOrder = "UPDATE ".$this-> table_one." SET product_list = :product_list, total = :total, updated = CURRENT_TIMESTAMP WHERE id = :id_order AND id_user = :id_user";
        $stmt = $this-> conn -> prepare($updateOrder);
        $this-> id_order = htmlspecialchars(strip_tags($this-> id_order));
        $this-> id_user = htmlspecialchars(strip_tags($this-> id_user));

        $stmt->bindParam(':id_order', $this-> id_order);
        $stmt->bindParam('id_user', $this-> id_user);
        $stmt->bindParam(':total', $this-> total);
        $stmt->bindParam(':product_list', $this-> product_list, PDO::PARAM_STR);
        if($stmt->execute()){
        return true;
        } else{
        http_response_code(400);
        echo json_encode(array("error"=>"заказ уже принят в работу"));
        exit();
        }
}
//подтверждение заказа
public function approveOrder(){
    $updateOrder = "UPDATE ".$this-> table_one." SET status= 'approved', status_update = CURRENT_TIMESTAMP WHERE id = :id_order AND id_user = :id_user AND status= 'waiting'";
        $stmt = $this-> conn -> prepare($updateOrder);
        $this-> id_order = htmlspecialchars(strip_tags($this-> id_order));
        $this-> id_user = htmlspecialchars(strip_tags($this-> id_user));

        $stmt->bindParam(':id_order', $this-> id_order);
        $stmt->bindParam('id_user', $this-> id_user);
        if($stmt->execute()){
        return true;
        } else{
        http_response_code(400);
        echo json_encode(array("error"=>"заказ был отменён пользователем"));
        exit();
        }
}
//отказ заказа
public function rejectOrder(){
    $updateOrder = "UPDATE ".$this-> table_one." SET status= 'rejected', status_update = CURRENT_TIMESTAMP WHERE id = :id_order AND id_user = :id_user AND status= 'waiting'";
        $stmt = $this-> conn -> prepare($updateOrder);
        $this-> id_order = htmlspecialchars(strip_tags($this-> id_order));
        $this-> id_user = htmlspecialchars(strip_tags($this-> id_user));

        $stmt->bindParam(':id_order', $this-> id_order);
        $stmt->bindParam('id_user', $this-> id_user);
        if($stmt->execute()){
        return true;
        } else{
        http_response_code(400);
        echo json_encode(array("error"=>"заказ был отменён пользователем"));
        exit();
        }
}

//удаление заказа =)
public function deleteOrder(){
    $updateOrder = "UPDATE ".$this-> table_one." SET status= 'deleted', status_update = CURRENT_TIMESTAMP WHERE id = :id_order AND id_user = :id_user";
        $stmt = $this-> conn -> prepare($updateOrder);
        $this-> id_order = htmlspecialchars(strip_tags($this-> id_order));
        $this-> id_user = htmlspecialchars(strip_tags($this-> id_user));

        $stmt->bindParam(':id_order', $this-> id_order);
        $stmt->bindParam('id_user', $this-> id_user);
        if($stmt->execute()){
        return true;
        } else{
        http_response_code(400);
        echo json_encode(array("error"=>"заказ был отменён пользователем"));
        exit();
        }
}


public function findWaitingOrder(){
    $find_order = "SELECT id FROM ".$this-> table_one."
                    WHERE id = :id_order AND status = 'waiting'";
    $stmt=$this-> conn-> prepare($find_order);
    $this-> id_order = htmlspecialchars(strip_tags($this-> id_order));
    $stmt->bindParam(':id_order', $this-> id_order);
    $stmt->execute();
    $num = $stmt->rowCount();
    if($num>0){
    return true;
    }else {
    http_response_code(400);
    echo json_encode(array("error"=>"order wasn't founded_waiting"));
    exit();
    }
}

public function findApproveOrder(){
    $find_order = "SELECT id FROM ".$this-> table_one."
                    WHERE id = :id_order AND status = 'approve'";
    $stmt=$this-> conn-> prepare($find_order);
    $this-> id_order = htmlspecialchars(strip_tags($this-> id_order));
    $stmt->bindParam(':id_order', $this-> id_order);
    $stmt->execute();
    $num = $stmt->rowCount();
    if($num>0){
    return true;
    }else {
    http_response_code(400);
    echo json_encode(array("error"=>"order wasn't founded_approve"));
    exit();
    }
}
//поиск всех approved ордеров
function getTotalApprovedOrderPages () {
    $getAll = "SELECT COUNT(*)
                FROM ".$this-> table_one ."
                LEFT JOIN users_table
                ON orders.id_user = users_table.id
                WHERE status = 'approved' AND rights !=3";
    $stmt_all = $this-> conn -> prepare ($getAll);

    if($stmt_all -> execute()) {
       return $stmt_all;
    } else {
        http_response_code(400);
        echo json_encode(array("error" => "something went wrong_get_total"));
        exit();
    }
}

public function getAllApprovedOrders(){
    $query = "SELECT *
            FROM ".$this-> table_one."
            LEFT JOIN users_table
            ON orders.id_user = users_table.id
            WHERE status = 'approved' AND rights !=3
            LIMIT ".$this->fromPage.", ".$this->pageNumber."";
           $stmt = $this-> conn -> prepare($query);
             $stmt -> execute();
             $num = $stmt->rowCount();

             if($num>0){
             return $stmt;
             }
             else {
                     http_response_code(400);
                     echo json_encode(array("error" => "категорий не существует"));
                     exit();}
}
//поиск всех rejected ордеров
function getTotalRejectedOrderPages () {
    $getAll = "SELECT COUNT(*)
                FROM ".$this-> table_one ."
                LEFT JOIN users_table
                ON orders.id_user = users_table.id
                WHERE status = 'rejected' AND rights !=3";
    $stmt_all = $this-> conn -> prepare ($getAll);

    if($stmt_all -> execute()) {
       return $stmt_all;
    } else {
        http_response_code(400);
        echo json_encode(array("error" => "something went wrong_get_total"));
        exit();
    }
}

public function getAllRejectedOrders(){
    $query = "SELECT *
            FROM ".$this-> table_one."
            LEFT JOIN users_table
            ON orders.id_user = users_table.id
            WHERE status = 'rejected' AND rights !=3
            LIMIT ".$this->fromPage.", ".$this->pageNumber."";
           $stmt = $this-> conn -> prepare($query);
             $stmt -> execute();
             $num = $stmt->rowCount();

             if($num>0){
             return $stmt;
             }
             else {
                     http_response_code(400);
                     echo json_encode(array("error" => "категорий не существует"));
                     exit();}
}
//поиск всех waiting ордеров
function getTotalWaitingOrderPages () {
    $getAll = "SELECT COUNT(*)
    FROM ".$this-> table_one ."
    LEFT JOIN users_table
    ON orders.id_user = users_table.id
    WHERE status = 'waiting' AND rights !=3";
    $stmt_all = $this-> conn -> prepare ($getAll);

    if($stmt_all -> execute()) {
       return $stmt_all;
    } else {
        http_response_code(400);
        echo json_encode(array("error" => "something went wrong_get_total"));
        exit();
    }
}

public function getAllWaitingOrders(){
    $query = "SELECT *
            FROM ".$this-> table_one."
            LEFT JOIN users_table
            ON orders.id_user = users_table.id
            WHERE status = 'waiting' AND rights !=3
            LIMIT ".$this->fromPage.", ".$this->pageNumber."";
           $stmt = $this-> conn -> prepare($query);
             $stmt -> execute();
             $num = $stmt->rowCount();

             if($num>0){
             return $stmt;
             }
             else {
                     http_response_code(400);
                     echo json_encode(array("error" => "категорий не существует"));
                     exit();}
}

//поиск всех canceled ордеров
function getTotalCanceledOrderPages () {
    $getAll = "SELECT COUNT(*)
                FROM ".$this-> table_one ."
                LEFT JOIN users_table
                ON orders.id_user = users_table.id
                WHERE status = 'canceled' AND rights !=3
    ";
    $stmt_all = $this-> conn -> prepare ($getAll);

    if($stmt_all -> execute()) {
       return $stmt_all;
    } else {
        http_response_code(400);
        echo json_encode(array("error" => "something went wrong_get_total"));
        exit();
    }
}

public function getAllCanceledOrders(){
    $query = "SELECT *
                FROM ".$this-> table_one."
                LEFT JOIN users_table
                ON orders.id_user = users_table.id
                WHERE status = 'canceled' AND rights !=3
                LIMIT ".$this->fromPage.", ".$this->pageNumber."";
           $stmt = $this-> conn -> prepare($query);
             $stmt -> execute();
             $num = $stmt->rowCount();

             if($num>0){
             return $stmt;
             }
             else {
                     http_response_code(400);
                     echo json_encode(array("error" => "категорий не существует"));
                     exit();}
}

function getTotalOrderPages () {
    $getAll = "SELECT COUNT(*)
    FROM ".$this-> table_one ."
    LEFT JOIN users_table
    ON orders.id_user = users_table.id
    WHERE status != 'deleted' AND rights !=3
    ";
    $stmt_all = $this-> conn -> prepare ($getAll);

    if($stmt_all -> execute()) {
       return $stmt_all;
    } else {
        http_response_code(400);
        echo json_encode(array("error" => "something went wrong_get_total"));
        exit();
    }
    }

public function getAllOrders(){
    $query = "SELECT *
              FROM ".$this-> table_one."
              LEFT JOIN users_table
              ON orders.id_user = users_table.id
              WHERE status != 'deleted' AND rights !=3
              LIMIT ".$this->fromPage.", ".$this->pageNumber."";
           $stmt = $this-> conn -> prepare($query);
             $stmt -> execute();
             $num = $stmt->rowCount();

             if($num>0){
             return $stmt;
             }
             else {
                     http_response_code(400);
                     echo json_encode(array("error" => "категорий не существует"));
                     exit();}
}




}
?>
