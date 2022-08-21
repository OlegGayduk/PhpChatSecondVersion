<?php

//session_start();

ini_set("default_charset","UTF-8");

require_once("db.php");
require_once("sqlRequests.php");
require_once("sanitize.php");

if(isset($_POST)) {
    if(is_array(json_decode($_POST['numbers']))) {
    
            //if(value_sanitize($_GET['sell']) != false) {
    
                //$sell = value_sanitize($_GET['sell']);
    
                if(connection() != false) {
              
                    $db = connection();
           
                    $i = 0;
                    $numbers = array();
                    //$lastMsg = array();
    
                    try {
                        foreach(json_decode($_POST['numbers']) as $value) {
                            $numbers[$i++] = $value; 
                        }
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
    
                    //exit(value_sanitize($_POST['id']));
    
                    for($g = 0;$g <= count($numbers);$g++) {
                        update_msg_status($db,json_decode($_POST['numbers'])[$g]);
                    }
           
                    return true;
                    
                } else {
                    exit(0);
                }
            //} else {
                //exit(0);
            //}
    } else {
        exit("Ошибка!");
    }
} else {
	exit('Unable to connect to server! Try again later...');
}

?>