<?php
class User {
    // подключение к БД таблице "users"
    private $conn;
    private $table_name = "users_table";
    // свойства объекта
    public $id;
    public $organization;
    public $firstname;
    public $lastname;
    public $email;
    public $password;
    public $phone;
    // конструктор класса User
    public function __construct($db) {
        $this->conn = $db;
    }


    // Создание нового пользователя
    function create() {
        // Вставляем запрос
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    organization = :organization,
                    email = :email,
                    password = :password,
                    phone =:phone,
                    rights = 0";
        // подготовка запроса
        $stmt = $this->conn->prepare($query);
        // инъекция
        $this-> organization = htmlspecialchars(strip_tags($this-> organization));
        $this-> email  = htmlspecialchars(strip_tags($this-> email));
        $this-> password = htmlspecialchars(strip_tags($this-> password));
        $this-> phone = htmlspecialchars(strip_tags($this-> phone));
        // привязываем значения
        $stmt->bindParam(':organization', $this-> organization);
        $stmt->bindParam(':email', $this-> email);
        $stmt->bindParam(':phone', $this-> phone);
        // для защиты пароля
        // хешируем пароль перед сохранением в базу данных
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
        // Выполняем запрос
        // Если выполнение успешно, то информация о пользователе будет сохранена в базе данных
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    function emailExists(){

        // запрос, чтобы проверить, существует ли электронная почта
        $query = "SELECT id, email, password, firstname, lastname
                FROM " . $this->table_name . "
                WHERE email = ?
                LIMIT 0,1";

        $stmt = $this->conn->prepare( $query );
        $this-> email=htmlspecialchars(strip_tags($this-> email));

        $stmt->bindParam(1, $this-> email);
        $stmt->execute();
        $num = $stmt->rowCount();

        if($num>0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // присвоим значения свойствам объекта
                $this-> id = $row['id'];
                $this-> firstname = $row['firstname'];
                $this-> lastname = $row['lastname'];
                $this-> password = $row['password'];
            return true;
        }
        // вернём 'false', если адрес электронной почты не существует в базе данных
        return  false;
    }

    // обновить запись пользователя
    public function update(){
        // Если в HTML-форме был введен пароль (необходимо обновить пароль)
        $password_set=!empty($this->password) ? ", password = :password" : "";
        // если не введен пароль - не обновлять пароль
        $query = "UPDATE " . $this->table_name . "
                SET
                    organization = :organization,
                    firstname = :firstname,
                    lastname = :lastname,
                    email = :email,
                    phone = :phone
                    {$password_set}
                WHERE id = :id";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // инъекция (очистка)
        $this->organization=htmlspecialchars(strip_tags($this->organization));
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));

        // привязываем значения с HTML формы
        $stmt->bindParam(':organization', $this->organization);
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);

        // метод password_hash () для защиты пароля пользователя в базе данных
        if(!empty($this->password)){
            $this->password=htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }

        // уникальный идентификатор записи для редактирования
        $stmt->bindParam(':id', $this->id);

        // Если выполнение успешно, то информация о пользователе будет сохранена в базе данных
        if($stmt->execute()) {
            return true;
        }   return false;
    }

//проверка юзера для создания ордера
function userFind(){
    //проверка пользователя
    $find_user = "SELECT id FROM " . $this-> table_name ."
              WHERE id = :id_user AND email = :email";
              $stmt= $this->conn->prepare($find_user);
              $this-> id_user= htmlspecialchars(strip_tags($this-> id_user));
              $this-> email=htmlspecialchars(strip_tags($this-> email));
              $stmt->bindParam(':id_user', $this-> id_user);
              $stmt->bindParam(':email', $this-> email);
              $stmt->execute();
              $num= $stmt->rowCount();
              if($num==0){
              http_response_code(401);
              echo json_encode(array("error" => "пользователь не найден в системе"));
              exit();
              } return true;
              }


function getAllUsers () {
    $getAllUsers = "SELECT id, organization, firstname, lastname, email, phone, rights, updated, created FROM ".$this-> table_name ."";
        $stmt = $this-> conn -> prepare ($getAllUsers);
        $stmt -> execute();
        $num = $stmt->rowCount();
        if($num >0) {
            return $stmt;
        } else{
        http_response_code(400);
        echo json_encode(array("error" => "something_went_wrong"));
        exit();
        }
}













}
?>









