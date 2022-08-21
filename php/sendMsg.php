<?php

session_start();

ini_set("default_charset","UTF-8");        

require_once("db.php");
require_once("sqlRequests.php");
require_once("sanitize.php");

if(isset($_POST)) {

    if(value_sanitize($_POST['token']) != false) {

        if(hash_equals("1234".":".md5("1234".":".$_SESSION['secret']),$_POST['token'].":".md5($_POST['token'].":".$_SESSION['secret']))) {
        
            $ses_id = $_SESSION['id'];

            if($ses_id != false) {

                $text = value_sanitize($_POST['text']);

                if($text != false) {

                    $sell = value_sanitize($_GET['sell']);

                    if($sell != false) {

                        $dateMin = value_sanitize($_POST['dateMin']);

                        if($dateMin!= false) {

                            $dateDay = value_sanitize($_POST['dateDay']);

                            if($dateDay != false) {

                                $main_id = value_sanitize($_POST['mainId']);

                                if($main_id != false) {

                                    $db = connection();
                                        
                                    if($db != false) { 

                                        $msg = msg_send_request_full($db,$main_id,$sell,$ses_id,0,0,$text,$dateMin,$dateDay);
                                        $dialog = msg_dialog_send($db,$msg['id'],$ses_id,$main_id,$sell,$text,$dateMin,$dateDay);

                                        //if($dialog == true) {
                                            //$arr[1] = array('msg' => json_encode($msg),'dialog' => json_encode($dialog));
                                            //$arr[1] = array('msg' => $msg,'dialog' => $dialog);
                                            echo json_encode(array('msg' => $msg,'dialog' => $dialog));
                                        //} else {
                                            //echo json_encode($msg);
                                        //}
            
                                        //echo $val;
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
                            exit(0);
                        }
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
            exit(0);
        }
    } else {
        exit(0);
    }
} else {
    exit("Unable to connect to server! Try again later...");
}

?>