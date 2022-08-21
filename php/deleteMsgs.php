<?php

session_start();

ini_set("default_charset","UTF-8");

require_once("db.php");
require_once("sqlRequests.php");
require_once("sanitize.php");

if(isset($_POST)) {

    $ses_id = $_SESSION['id'];

    if($ses_id != false) {
    //if(value_sanitize($_POST['token']) != false) {

        //exit(hash_equals("1234".":".md5("1234".":".$_SESSION['secret']),"23234".":".md5("242424".":".$_SESSION['secret'])));

        //if(hash_equals("1234".":".md5("1234".":".$_SESSION['secret']),$_POST['token'].":".md5($_POST['token'].":".$_SESSION['secret']))) {
    
            if(is_array(json_decode($_POST['msgs']))) {
                
                //if($_SESSION['id'] != false) {
        
                    //$ses_id = $_SESSION['id'];

                    $sell = value_sanitize($_GET['sell']);
        
                    if($sell != false) {

                        $db = connection();
        
                        if($db != false) {
                   
                            $i = 0;
                            $msgs = array();
                            $lastMsg = array();
                            $msgsLength = 0;
        
                            try {
                                foreach(json_decode($_POST['msgs']) as $value) {
                                    $msgs[$i++] = $value; 
                                }
                            } catch(Exception $e) {
                                echo $e->getMessage();
                            }
        
                            //exit(value_sanitize($_POST['id']));
        
                            /*if(value_sanitize($_POST['lastMsgText']) != false && value_sanitize($_POST['lastMsgDateDay']) != false && value_sanitize($_POST['id']) != false) {
                                dialog_last_msg_update($db,value_sanitize($_POST['lastMsgText']),value_sanitize($_POST['lastMsgDateDay']),value_sanitize($_POST['id']),$sell);
                            }*/
                            
                            $msgsLength = count($msgs);

                            if($msgsLength > 1) {
                                for($g = 0;$g <= count($msgs);$g++) {
                                    delete_msgs($db,json_decode($_POST['msgs'])[$g],$sell);
                                }
                            } else {
                                for($g = 0;$g < count($msgs);$g++) {
                                    delete_msgs($db,json_decode($_POST['msgs'])[$g],$sell);
                                }
                            }

                            exit(update_dialog_after_delete($db,$ses_id,$sell));
                   
                            //return true;
                            
                        } else {
                            exit(0);
                        }
                    } else {
                        exit(0);
                    }
                //} else {
                  //exit(0);
                //}
            } else {
                exit("Ошибка!");
            }
        //} else {
            //exit(0);
        //}
    //} else {
      //exit(0);
    //} 
    } else {
        exit(0);
    }
} else {
	exit('Unable to connect to server! Try again later...');
}

?>