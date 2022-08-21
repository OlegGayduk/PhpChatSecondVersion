<?php
function connection() {
    $db = new mysqli('localhost', 'root', '','chat');
    //$db = new mysqli('localhost', 'Oleg_blog', 'rembo123','test');
        //$connection = mysqli_select_db('test',$db);

    if (mysqli_connect_errno()) {
       printf("Не удалось подключиться к базе данных. Код ошибки: %s\n",mysqli_connect_error());
       return false;
    } else {
       return $db;
    }	
}
?>