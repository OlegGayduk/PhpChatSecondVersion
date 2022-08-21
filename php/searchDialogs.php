<?php

session_start();

ini_set('default_charset','UTF-8');

require_once("db.php");
require_once("sqlRequests.php");
//require_once("showMsgs.php");
require_once("sanitize.php");

if(isset($_POST)) {

    //echo "error";

    //exit("Error");

    if(value_sanitize($_POST['token']) != false) {

        //exit(hash_equals("1234".":".md5("1234".":".$_SESSION['secret']),"23234".":".md5("242424".":".$_SESSION['secret'])));

        if(hash_equals("1234".":".md5("1234".":".$_SESSION['secret']),$_POST['token'].":".md5($_POST['token'].":".$_SESSION['secret']))) {

            //exit("error");
	
            if($_SESSION['id'] != false) {

            	$ses_id = $_SESSION['id'];

                $searchText = value_sanitize($_POST['searchText']);

                if($searchText != false) {

                    //exit($searchText);
                    $db = connection();

                    if($db != false) {

                        $searchValue = search_dialogs($db,$searchText,$ses_id);

                        if($searchValue != false) {
                            //$searchValue = array();
                            //exit("Error");

                            echo $searchValue;
                        } else {
                            exit(0);
                        }
                    } else {
                        exit("Error 2");
                    }
                } else {
                    exit("Error 3");
                }
            } else {
            	exit("<span style='font: 14px/18px Arial,Helvetica,Verdana,sans-serif;position:absolute;left:0;right:0;text-align: center;top:50%;'>Фатальная ошибка,пройдите авторизацию повторно!</span>");
            }
        } else {
            exit(0);
        }
    } else {
        exit(0);
    }
} else {
	exit('Unable to connect to server! Try again later...');
}

?>