<?php

session_start();

if(isset($_SESSION['id'])) {
	header('Location:php/mess.php');
} else {
	header('Location:php/login.html');
}

/*if(session_status() != PHP_SESSION_ACTIVE) {
	session_start();
    header('Location:php/login.html');
} else {
	header('Location:php/mess.php');
}*/


?>
