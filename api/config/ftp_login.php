<?php

$ftp_server = "gasparodelivery.kl.com.ua";
$ftp_user = "antonkotov";
$ftp_pass = "He881uudsui2";

// установить соединение или выйти
$conn_id = ftp_connect($ftp_server) or die("Не удалось установить соединение с $ftp_server");

// попытка входа
if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    echo "Произведен вход на $ftp_server под именем $ftp_user\n";
} else {
    echo "Не удалось войти под именем $ftp_user\n";
}

// закрыть соединение
ftp_close($conn_id);
?>