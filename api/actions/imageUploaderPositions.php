<?php
$category_photo_path = 'C:\OSPanel\domains\GasparoDelivery.com\position_img/';

$limitBytes  = 1024 * 1024 * 5;
$limitWidth  = 1280;
$limitHeight = 768;


if (empty($_FILES['image']['tmp_name'])){
    http_response_code(400);
    echo json_encode(array("error" => "Необходимо загрузить фотографию создаваемой позиции"));
    exit();
}
$filePath  = $_FILES['image']['tmp_name'];
$errorCode = $_FILES['image']['error'];

// Проверим на ошибки
if ($errorCode !== UPLOAD_ERR_OK || !is_uploaded_file($filePath)) {

    // Массив с названиями ошибок
    $errorMessages = [
        UPLOAD_ERR_INI_SIZE   => 'Размер файла превысил значение upload_max_filesize в конфигурации PHP.',
        UPLOAD_ERR_FORM_SIZE  => 'Размер загружаемого файла превысил значение MAX_FILE_SIZE в HTML-форме.',
        UPLOAD_ERR_PARTIAL    => 'Загружаемый файл был получен только частично.',
        UPLOAD_ERR_NO_FILE    => 'Файл не был загружен.',
        UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка.',
        UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск.',
        UPLOAD_ERR_EXTENSION  => 'PHP-расширение остановило загрузку файла.',
    ];

    // Зададим неизвестную ошибку
    $unknownMessage = 'При загрузке файла произошла неизвестная ошибка.';

    // Если в массиве нет кода ошибки, скажем, что ошибка неизвестна
    $outputMessage = isset($errorMessages[$errorCode]) ? $errorMessages[$errorCode] : $unknownMessage;

    // Выведем название ошибки
    die($outputMessage);
}





// Создадим ресурс FileInfo

$fi = finfo_open(FILEINFO_MIME_TYPE);

// Получим MIME-тип
$mime = (string) finfo_file($fi, $filePath);
// Проверим ключевое слово image (image/jpeg, image/png и т. д.)
if (strpos($mime, 'image/jpeg') === false){
    http_response_code(400);
    echo json_encode(array("error" => "Можно загружать только изображения формата image/jpeg"));
    exit();
    } else {$image = getimagesize($filePath); }

if (filesize($filePath) > $limitBytes){
      http_response_code(400);
      echo json_encode(array("error" => "Размер изображения не должен превышать 5 Мбайт"));
      exit();
      }

if ($image[1] > $limitHeight){
       http_response_code(400);
       echo json_encode(array("error" => "Высота изображения не должна превышать 768 точек"));
       exit();
       }

if ($image[0] > $limitWidth){
       http_response_code(400);
       echo json_encode(array("error" => "Ширина изображения не должна превышать 1280 точек"));
       exit();
       }

// Сгенерируем новое имя файла на основе MD5-хеша
$name = md5_file($filePath);

// Сгенерируем расширение файла на основе типа картинки
$extension = image_type_to_extension($image[2]);

// Сократим .jpeg до .jpg
$format = str_replace('jpeg', 'jpg', $extension);

// Переместим картинку с новым именем и расширением в папку /pics
if (!move_uploaded_file($filePath, $category_photo_path . $name . $format)) {
    http_response_code(400);
    echo json_encode(array("error" => "При записи изображения на диск произошла ошибка"));
    exit();
} else { $fullFilePath = ($category_photo_path . $name . $format);}


?>