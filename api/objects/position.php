<?php

class Position{
private $conn;
private $table_name='items';
private $database='gasparodatabase';


public $id;
public $name;
public $description;
public $quantity;
public $price_first;
public $lust_update;
public $created;
public $category;


public function __construct($db){
    $this->conn = $db;
}

function create(){
    $query_find = "SELECT name FROM " . $this-> table_name ."
                   WHERE name = :name
                   ";
                   $stmt_find = $this->conn->prepare($query_find);

                   $this-> name=htmlspecialchars(strip_tags($this-> name));
                   $stmt_find->bindParam(':name', $this-> name);
                   $stmt_find->execute();
                   $num_find=$stmt_find->rowCount();
                   if($num_find >0){
                   http_response_code(400);
                   echo json_encode(array("error"=>"позиция с таким названием уже существует в другой категории"));
                   exit();
                   }

    $query = "INSERT INTO " .$this->table_name . "
        SET
            name = :name,
            description = :description,
            quantity = :quantity,
            price_first  = :price_first,
            category = :category,
            image_item = :image_item,
            status = 0
            ";


    $stmt = $this-> conn ->prepare($query);

    $this-> name = htmlspecialchars(strip_tags($this-> name));
    $this-> description = htmlspecialchars(strip_tags($this-> description));
    $this-> quantity = htmlspecialchars(strip_tags($this-> quantity));
    $this-> price_first  = htmlspecialchars(strip_tags($this-> price_first));
    $this-> category = htmlspecialchars(strip_tags($this-> category));
    $this-> image_item = htmlspecialchars(strip_tags($this-> image_item));


    $stmt->bindParam(':name', $this-> name);
    $stmt->bindParam(':description', $this-> description);
    $stmt->bindParam(':quantity', $this-> quantity);
    $stmt->bindParam(':price_first', $this-> price_first);
    $stmt->bindParam(':category', $this-> category);
    $stmt->bindParam(':image_item', $this-> image_item);


    if($stmt->execute()){
        return true;
    }return false;
}


function delete(){
             $query = "SELECT id
                                    FROM " . $this-> table_name ."
                                    WHERE id= ?
                                    LIMIT 0,1";

                                    $stmt = $this->conn->prepare( $query );
                                    $this-> id=htmlspecialchars(strip_tags($this-> id));
                                    $stmt->bindParam(1, $this-> id);
                                    $stmt->execute();
                                    $num = $stmt->rowCount();
                       if($num>0){
                       $query_delete = "DELETE FROM $this->database . $this->table_name WHERE  id= :id";
                       $stmt_delete = $this->conn->prepare( $query_delete );
                       $this-> id=htmlspecialchars(strip_tags($this-> id));
                       $stmt_delete->bindParam(':id', $this-> id);
                       $stmt_delete->execute();
                            if($stmt_delete->execute()){
                            return true;}
                            return false;}
              return false;
            }

function update(){
    //ищем позицию, которую в будущем изменим
    $query_find = "SELECT id
                                    FROM " . $this-> table_name ."
                                    WHERE id= :id
                                    ";
                                    $stmt_find = $this->conn->prepare( $query_find );
                                    $this-> id=htmlspecialchars(strip_tags($this-> id));
                                    $stmt_find->bindParam(':id', $this-> id);
                                    $stmt_find->execute();
                                    $num_find = $stmt_find->rowCount();
                      if($num_find==0){
                       http_response_code(400);
                       echo json_encode(array("error"=>"данной позиции не существует"));
                       exit();
                       }

                       $query_twin_finding = "SELECT name FROM " . $this-> table_name ."
                                              WHERE name = :name AND id != :id
                                              ";
                                              $stmt_twin_finding = $this->conn->prepare($query_twin_finding);
                                              $this-> id= htmlspecialchars(strip_tags($this-> id));
                                              $this-> name= htmlspecialchars(strip_tags($this-> name));
                                              $stmt_twin_finding->bindParam(':name', $this-> name);
                                              $stmt_twin_finding->bindParam(':id', $this-> id);
                                              $stmt_twin_finding ->execute();
                                              $num_twin_finding = $stmt_twin_finding->rowCount();
                       if($num_twin_finding >0){
                       http_response_code(400);
                       echo json_encode(array("error" =>"позиция с таким названием уже существует"));
                       exit();
                       }

    $query = "UPDATE ". $this->table_name ."
              Set
              `name`= :name,
              `description` = :description,
              `quantity` = :quantity,
              `price_first` = :price_first,
              image_item = :image_item,
              `lust_update` = CURRENT_TIMESTAMP,
              `category` = :category
              WHERE `id` = :id";

    $stmt = $this-> conn->prepare($query);

    $this-> id = htmlspecialchars(strip_tags($this-> id));
    $this-> name = htmlspecialchars(strip_tags($this-> name));
    $this-> description = htmlspecialchars(strip_tags($this-> description));
    $this-> quantity = htmlspecialchars(strip_tags($this-> quantity));
    $this-> price_first = htmlspecialchars(strip_tags($this-> price_first));
    $this-> category = htmlspecialchars(strip_tags($this-> category));
    $this-> image_item = htmlspecialchars(strip_tags($this-> image_item));

    $stmt->bindParam(':id', $this-> id);
    $stmt->bindParam(':name', $this-> name);
    $stmt->bindParam(':description', $this-> description);
    $stmt->bindParam(':quantity', $this-> quantity);
    $stmt->bindParam(':price_first', $this-> price_first);
    $stmt->bindParam(':category', $this-> category);
    $stmt->bindParam(':image_item', $this-> image_item);

    if($stmt->execute()){
    return true;
    }return false;

    }

    //выборка позиций по введённой категории
    function getCategoryPositions(){
                $query = "SELECT id, name,  quantity, description, price_first, lust_update, created FROM " . $this-> table_name ."
                          WHERE category = :id";
                          $stmt = $this-> conn ->prepare ($query);
                          $this-> id = htmlspecialchars(strip_tags($this-> id));
                          $stmt->bindParam(':id', $this-> id);
                          $stmt-> execute();
                          return $stmt;
                        }


function findPositions(){
    $find_item = "SELECT id, name, quantity, price_first, category FROM " . $this-> table_name ."
     WHERE id = :id_item AND name = :name AND quantity= :quantity AND price_first = :price_first AND category = :category";
    $stmt_find_item= $this-> conn->prepare($find_item);
    $this-> id_item= htmlspecialchars(strip_tags($this-> id_item));
    $this-> name= htmlspecialchars(strip_tags($this-> name));
    $this-> quantity= htmlspecialchars(strip_tags($this-> quantity));
    $this-> price_first= htmlspecialchars(strip_tags($this-> price_first));
    $this-> category= htmlspecialchars(strip_tags($this-> category));
    $stmt_find_item ->bindParam(':id_item', $this-> id_item);
    $stmt_find_item ->bindParam(':name', $this-> name);
    $stmt_find_item ->bindParam(':quantity', $this-> quantity);
    $stmt_find_item ->bindParam(':price_first', $this-> price_first);
    $stmt_find_item ->bindParam(':category', $this-> category);
    $stmt_find_item->execute();
    $num_find_item= $stmt_find_item->rowCount();

    if($num_find_item==0){
              http_response_code(401);
              echo json_encode(array("error" => "product_does_not_exist_in_the_system"));
              exit();
    } else{return true;}


}



function getTotalPositionPages () {
    $getAll = "SELECT COUNT(*) FROM ".$this-> table_name ." WHERE category = :category";
    $stmt_all = $this-> conn -> prepare ($getAll);
    $this-> id = htmlspecialchars(strip_tags($this-> id));
    $stmt_all ->bindParam(':category', $this-> id);

    if($stmt_all -> execute()) {
       return $stmt_all;
    } else {
        http_response_code(400);
        echo json_encode(array("error" => "something went wrong_get_total"));
        exit();
    }
    }

function getAllPositions(){
             $query =  "SELECT id, name, description, quantity, price_first,  image_item, lust_update, category, status
             FROM " . $this-> table_name ."
             WHERE category= :category
             LIMIT ".$this->fromPage.", ".$this->pageNumber."

             ";
             $stmt = $this-> conn -> prepare($query);
             $this-> id = htmlspecialchars(strip_tags($this-> id));
             $stmt ->bindParam(':category', $this-> id);
             $stmt -> execute();
             $num = $stmt->rowCount();

             if($num>0){
             return $stmt;
             }
             else {
                     http_response_code(400);
                     echo json_encode(array("error" => "позиций не найдено"));
                     exit();}
}


function findPositionStatus(){
    $query_find_position = "SELECT id, category, status FROM ". $this-> table_name." WHERE id = :id AND category = :category AND status = :status";
    $stmt_find_position = $this-> conn -> prepare($query_find_position);

    $this-> id = htmlspecialchars(strip_tags($this-> id));
    $this-> category = htmlspecialchars(strip_tags($this-> category));
    $this-> status = htmlspecialchars(strip_tags($this-> status));

    $stmt_find_position->bindParam(':id', $this-> id);
    $stmt_find_position->bindParam(':category', $this-> category);
    $stmt_find_position->bindParam(':status', $this -> status);
    $stmt_find_position ->execute();
    $num = $stmt_find_position->rowCount();
    if ($num > 0){
        return true;

    } else {
         http_response_code(400);
         echo json_encode(array("error" => "position_not_found"));
         exit();
    }
}


function lockPosition(){
    $query_block_category = "UPDATE ".$this-> table_name." SET status = 0, status_update = CURRENT_TIMESTAMP  WHERE id = :id AND category = :category AND status= :status";
    $stmt_block_category = $this->conn -> prepare($query_block_category);

    $this-> id = htmlspecialchars(strip_tags($this-> id));
    $this-> category = htmlspecialchars(strip_tags($this-> category));
    $this-> status = htmlspecialchars(strip_tags($this-> status));

    $stmt_block_category->bindParam(':id', $this-> id);
    $stmt_block_category->bindParam(':category', $this-> category);
    $stmt_block_category->bindParam(':status', $this -> status);

    if ($stmt_block_category ->execute()){
        return true;
    }
    http_response_code(400);
    echo json_encode(array("error" => "can't_lock_category"));
    exit();
}


function activatePosition(){
    $query = "UPDATE ".$this-> table_name." SET status = 1, status_update = CURRENT_TIMESTAMP WHERE id = :id AND category = :category AND status= :status";
    $stmt = $this->conn -> prepare($query);

    $this-> id = htmlspecialchars(strip_tags($this-> id));
    $this-> category = htmlspecialchars(strip_tags($this-> category));
    $this-> status = htmlspecialchars(strip_tags($this-> status));

    $stmt->bindParam(':id', $this-> id);
    $stmt->bindParam(':category', $this-> category);
    $stmt->bindParam(':status', $this -> status);

    if ($stmt ->execute()){
        return true;
    }
    http_response_code(400);
    echo json_encode(array("error" => "can't_activate_position"));
    exit();
}

//USER

function getTotalPositionPagesUser () {
    $getAll = "SELECT COUNT(*) FROM ".$this-> table_name ." WHERE category = :category AND status = 1";
    $stmt_all = $this-> conn -> prepare ($getAll);
    $this-> id = htmlspecialchars(strip_tags($this-> id));
    $stmt_all ->bindParam(':category', $this-> id);

    if($stmt_all -> execute()) {
       return $stmt_all;
    } else {
        http_response_code(400);
        echo json_encode(array("error" => "something went wrong_get_total"));
        exit();
    }
    }

function getAllPositionsUser(){
             $query =  "SELECT id, name, description, quantity, price_first,  image_item, lust_update, category, status
             FROM " . $this-> table_name ."
             WHERE category= :category AND status = 1
             LIMIT ".$this->fromPage.", ".$this->pageNumber."

             ";
             $stmt = $this-> conn -> prepare($query);
             $this-> id = htmlspecialchars(strip_tags($this-> id));
             $stmt ->bindParam(':category', $this-> id);
             $stmt -> execute();
             $num = $stmt->rowCount();

             if($num>0){
             return $stmt;
             }
             else {
                     http_response_code(400);
                     echo json_encode(array("error" => "позиций не найдено"));
                     exit();}
}






}
?>