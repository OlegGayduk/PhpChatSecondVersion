<?php

session_start();

ini_set("default_charset","UTF-8");

require_once("db.php");
require_once("sqlRequests.php");
//require_once("showMsgsCycl.php");
require_once("sanitize.php");

$get_sell = value_sanitize($_GET['sell']);

if($get_sell != false) {

    if($_SESSION['id'] != false) {
    
        $ses_id = $_SESSION['id'];

        $db = connection();
    
        if($db != false) { 

            //if(update_msgs_dates($db,$summ) != false) {

                $msgs = msgs_requests($db,$ses_id,$get_sell);
        
                if($msgs != false) {

                    exit($msgs);
        
                    /*if(msgs_cycl($mess_sql_requests_res) == false) {
                        exit("<span class='chat-non-selected'>Please,check your connection to the internet!</span>");
                    }*/
        
                } else {
                    //exit("<span class='msgs-col-error'>The chat is empty.</span>");

                    exit(0);
                }
            //} else {
               // exit("<span class='chat-non-selected'>Ошибка!</span>");
            //}
        } else {
            //exit("<span class='chat-non-selected'>Please,check your connection to the internet..</span>");
            exit(0);
        }
    } else {
        //exit("<span class='chat-non-selected'>Fatal error!Please go through the authorization again!</span>");
        exit(0);
    } 
} else {
    //exit("<span class='msgs-col-error'>Choose the dialog to begin chat</span>");
    exit(0);
}



?>