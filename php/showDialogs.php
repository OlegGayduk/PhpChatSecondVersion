<?php

session_start();

ini_set("default_charset","UTF-8"); 

require_once("db.php");
require_once("sqlRequests.php");
//require_once("showDialogsCycl.php");  

if($_SESSION['id'] != false) {

    $ses_id = $_SESSION['id'];

    $db = connection();

    if($db != false) { 

        $dialogs_sql_requests_res = dialogs_requests($db,$ses_id);

        //exit($mess_sql_requests_res);

        if($dialogs_sql_requests_res != false) {

            exit($dialogs_sql_requests_res);
    
            /*if(dialogs_cycl($dialogs_sql_requests_res) == false) {
                //exit(0);
                //exit(dialogs_cycl($mess_sql_requests_res));
                exit(0);
            } */
        } else {
            //exit("Hello");
            //exit($mess_sql_requests_res);
            //exit("<span class='empty-dialogs'>You haven't got any dialogs yet</span>");
            exit(0);
        }
    } else {
        exit(0);
    }
} else {
    exit(0);
}


?>