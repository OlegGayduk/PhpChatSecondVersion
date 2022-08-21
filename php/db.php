<?php
function connection() {
    $db = new mysqli('localhost', 'root', '','chat');

    if (mysqli_connect_errno()) {
       printf("Не удалось подключиться к базе данных. Код ошибки: %s\n",mysqli_connect_error());
       return false;
    } else {
       return $db;
    }	
}
?>
