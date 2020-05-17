<?php

class Category{
private $conn;
private $master_table = 'category';
private $database = 'gasparodatabase';
private $position_table = 'items';

public $id;
public $category;
public $description;

public function __construct($db){
    $this->conn = $db;
}



function create(){
             $query_find = "SELECT category
                                    FROM " . $this-> master_table ."
                                    WHERE category= :category
                                    ";
                                    $stmt_find = $this->conn->prepare( $query_find );
                                    $this-> category=htmlspecialchars(strip_tags($this-> category));

                                    $stmt_find->bindParam(':category', $this-> category);
                                    $stmt_find->execute();
                                    $num_find = $stmt_find->rowCount();
                       if($num_find==0){

             $query = "INSERT INTO " .$this->master_table . "
                               SET
                                   category = :category,
                                   description = :description,
                                   status = 0,
                                   image_category = :image_category
                                   ";
                           //для созданной таблицы
                           $stmt = $this-> conn ->prepare($query);

                           $this-> category = htmlspecialchars(strip_tags($this-> category));
                           $this-> description = htmlspecialchars(strip_tags($this-> description));
                           $this-> image_category = htmlspecialchars(strip_tags($this-> image_category));

                           $stmt->bindParam(':category', $this-> category);
                           $stmt->bindParam(':description', $this-> description);
                           $stmt->bindParam(':image_category', $this-> image_category);

                           //для внесения в список всех таблиц
                           if($stmt->execute()){
                              return true;
                             }return false;
                       return true;}
                       else{
                        http_response_code (400);
                        echo json_encode(array("error" => "категория с таким именем уже существует"));
                        exit();
                        }
                       }



function delete(){
             //поиск существующих позиций в категориях
             $query_find_positions = "SELECT category
                                      FROM " . $this-> position_table ."
                                      WHERE category= :id
                                      ";
                                      $stmt_find_positions = $this->conn->prepare( $query_find_positions);
                                      $this-> id=htmlspecialchars(strip_tags($this-> id));

                                      $stmt_find_positions->bindParam(':id', $this-> id);
                                      $stmt_find_positions->execute();
                                      $num_find_positions= $stmt_find_positions->rowCount();
             if($num_find_positions>0){
                http_response_code(400);
                echo json_encode(array("error"=>"категория содержит существующие позиции"));
                exit();
             }

             $query_find_id = "SELECT id
                               FROM " . $this-> master_table ."
                               WHERE id = :id
                               ";

                               $stmt_find_id = $this-> conn->prepare($query_find_id);
                               $this-> id=htmlspecialchars(strip_tags($this-> id));
                               $stmt_find_id->bindParam(':id', $this-> id);
                               $stmt_find_id->execute();
                               $num_find_id = $stmt_find_id->rowCount();
             if($num_find_id == 0){
                http_response_code(400);
                echo json_encode(array("error"=>"данной категории не существует"));
                exit();
             }
             //поиск существующих идентичных категорий
             $query = "SELECT id, category
                                    FROM " . $this-> master_table ."
                                    WHERE id= :id AND category= :category
                                    ";

                                    $stmt = $this->conn->prepare( $query );
                                    $this-> id=htmlspecialchars(strip_tags($this-> id));
                                    $this-> category=htmlspecialchars(strip_tags($this-> category));

                                    $stmt->bindParam(':id', $this-> id);
                                    $stmt->bindParam(':category', $this-> category);

                                    $stmt->execute();
                                    $num = $stmt->rowCount();
                       if($num>0){
                       //если нет подобных записей - то удаляем
                       $query_delete = "DELETE FROM $this->database . $this->master_table WHERE  id= :id";
                       $stmt_delete = $this->conn->prepare( $query_delete );
                       $this-> id=htmlspecialchars(strip_tags($this-> id));
                       $stmt_delete->bindParam(':id', $this-> id);
                       $stmt_delete->execute();
                            if($stmt_delete->execute()){
                            return true;}
                            return false;}
}


function update(){

    //нужно сделать поиск для подобных записей
    $query_find_id = "SELECT category FROM " . $this-> master_table ."
                                       WHERE id= :id
                                       ";
                                       $stmt_find_id = $this-> conn->prepare($query_find_id);

                                       $this-> id= htmlspecialchars(strip_tags($this-> id));
                                       $stmt_find_id->bindParam(':id', $this-> id);
                                       $stmt_find_id->execute();
                                       $num_find_id= $stmt_find_id->rowCount();

                                       if($num_find_id ==0){
                                       http_response_code(400);
                                       echo json_encode(array("error"=>"категории с таким номером не существует"));
                                       exit();
                                       }

    $query_find = "SELECT category FROM " . $this-> master_table ."
                    WHERE category= :category AND id != :id
                    ";
                    $stmt_find = $this-> conn->prepare($query_find);
                    $this-> id= htmlspecialchars(strip_tags($this-> id));
                    $this-> category= htmlspecialchars(strip_tags($this-> category));
                    $stmt_find->bindParam(':category', $this-> category);
                    $stmt_find->bindParam(':id', $this-> id);
                    $stmt_find->execute();
                    $num_find= $stmt_find->rowCount();

                    if($num_find >0){
                    http_response_code(400);
                    echo json_encode(array("error"=>"категория с таким именем уже существует"));
                    exit();
                    }

    $query = "UPDATE ". $this->master_table ."
              Set
              category = :category,
              description = :description,
              image_category = :image_category,
              updated = CURRENT_TIMESTAMP
              WHERE id = :id";

    $stmt = $this-> conn->prepare($query);

    $this-> id = htmlspecialchars(strip_tags($this-> id));
    $this-> category = htmlspecialchars(strip_tags($this-> category));
    $this-> description = htmlspecialchars(strip_tags($this-> description));
    $this-> image_category = htmlspecialchars(strip_tags($this-> image_category));

    $stmt->bindParam(':id', $this-> id);
    $stmt->bindParam(':category', $this-> category);
    $stmt->bindParam(':description', $this-> description);
    $stmt->bindParam(':image_category', $this-> image_category);

    if($stmt->execute()){
    return true;
    }return false;
}

function getTotalCategoryPages () {
    $getAll = "SELECT COUNT(*) FROM ".$this-> master_table ."";
    $stmt_all = $this-> conn -> prepare ($getAll);

    if($stmt_all -> execute()) {
       return $stmt_all;
    } else {
        http_response_code(400);
        echo json_encode(array("error" => "something went wrong_get_total"));
        exit();
    }
    }

function getAllCategory(){
             $query =  "SELECT id, category, description, image_category, status FROM " . $this-> master_table ." LIMIT ".$this->fromPage.", ".$this->pageNumber."";
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

function findCategoryStatus(){
    $query_find_category = "SELECT id, category, status FROM ". $this-> master_table." WHERE id = :id AND category = :category AND status = :status";
    $stmt_find_category = $this-> conn -> prepare($query_find_category);

    $this-> id = htmlspecialchars(strip_tags($this-> id));
    $this-> category = htmlspecialchars(strip_tags($this-> category));
    $this-> status = htmlspecialchars(strip_tags($this-> status));

    $stmt_find_category->bindParam(':id', $this-> id);
    $stmt_find_category->bindParam(':category', $this-> category);
    $stmt_find_category->bindParam(':status', $this -> status);
    $stmt_find_category ->execute();
    $num = $stmt_find_category->rowCount();
    if ($num > 0){
        return true;

    } else {
         http_response_code(400);
         echo json_encode(array("error" => "category_not_found"));
         exit();
    }
}

function lockCategory(){
    $query_block_category = "UPDATE ".$this-> master_table." SET status = 0, status_update = CURRENT_TIMESTAMP  WHERE id = :id AND category = :category AND status= :status";
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

function activateCategory(){
    $query_block_category = "UPDATE ".$this-> master_table." SET status = 1, status_update = CURRENT_TIMESTAMP WHERE id = :id AND category = :category AND status= :status";
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
    echo json_encode(array("error" => "can't_activate_category"));
    exit();
}

//USER

function getAllCategoryUser(){
             $query =  "SELECT id, category, description, image_category, status
                         FROM " . $this-> master_table ."
                         WHERE status = 1
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

function getTotalCategoryPagesUser () {
    $getAll = "SELECT COUNT(*) FROM ".$this-> master_table ." WHERE status = 1";
    $stmt_all = $this-> conn -> prepare ($getAll);

    if($stmt_all -> execute()) {
       return $stmt_all;
    } else {
        http_response_code(400);
        echo json_encode(array("error" => "something went wrong_get_total"));
        exit();
    }
    }






}
?>