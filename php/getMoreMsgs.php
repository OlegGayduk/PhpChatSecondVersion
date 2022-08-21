<?php

session_start();

ini_set('default_charset','UTF-8');

require_once("db.php");
require_once("sqlRequests.php");
//require_once("showMsgs.php");
require_once("sanitize.php");
//require_once("showMsgsCycl.php");

if(isset($_POST)) {
	
    if($_SESSION['id'] != false) {

    	$ses_id = $_SESSION['id'];

        $sell = value_sanitize($_GET['sell']);

    	if($sell != false) {

            $last_msg = value_sanitize($_POST['lastMsg']);

            if($last_msg != false) {

                $db = connection();

                if($db != false) {

                    $new_msgs = get_more_msgs($db,$last_msg,$ses_id,$sell);

                    if($new_msgs != false) {

                        exit($new_msgs);

                        /*if(msgs_cycl($new_msgs) == false) {
                            //exit("There is no more!");
                            exit(0);
                        }*/
                    } else {
                        //exit("There is no more!");
                        exit(0);
                    }

                    /*$new_msgs = get_more_msgs($db,$last_msg,$ses_id,$sell);

                    if($new_msgs != false) {

                        echo $new_msgs;

                    } else {
                        exit("There is no more!");
                        //exit(0);
                    }*/
                } else {
                    exit(0);
                }
            } else {
                exit(0);
            }
        } else {
        	exit(0);
        }
    } else {
    	exit("<span style='font: 14px/18px Arial,Helvetica,Verdana,sans-serif;position:absolute;left:0;right:0;text-align: center;top:50%;'>Фатальная ошибка,пройдите авторизацию повторно!</span>");
    }
} else {
	exit('Unable to connect to server! Try again later...');
}


?>