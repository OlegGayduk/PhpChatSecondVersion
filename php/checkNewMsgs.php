<?php

session_start();

require_once("db.php");
require_once("sqlRequests.php");
require_once("showDialogsCycl.php");  
//require_once("sanitize.php");

if(isset($_POST['locationSearch'])) {

    if(isset($_POST['data'])) {

	    $receivedData = $_POST['data'];

	    if($_SESSION['id'] != false) {

		    $ses_id = $_SESSION['id'];

	        $db = connection();
    
	        if($db != false) {

	            $i = 0;
	            $t = 0;
                //$j = 0;

                $data = array();
                $newData = array();

                $unreadedMsgs = array();
                $updatedMsgs = array();

                $result = array();
            
                try {
                    foreach(json_decode($_POST['data']) as $value) {
                        //$data[$i++][$u++] = $value; 
                        $data[$i++] = $value;
                    }
                
                    if(isset($_POST['unreadedMsgs'])) {
                        $i = 0;
                 
                        foreach(json_decode($_POST['unreadedMsgs']) as $value) {
                            $unreadedMsgs[$i++] = $value;
                        }
                        
                        for($y = 0;$y < count($unreadedMsgs);$y++) {
                            for($m = 0;$m < count($unreadedMsgs[$y]);$m++) {
                                list($result[0],$result[1]) = check_msgs_updates($db,$ses_id,$unreadedMsgs[$y][$m]);
                                //if($result != false) $updatedMsgs[$t++] = $result;
                                //if($result != false) $updatedMsgs[$t++] = array("".$t."" => array("id" => "".$result[0]."","updatedMsgs" => $result[1],));
                                if($result[0] != false) $updatedMsgs[$t++] = array("id" => "".$result[0]."","updatedMsgs" => array($result[1]),);
                            }
                        }

                        $t = 0;
                    }
                } catch(Exception $e) {
                    exit($e->getMessage());
                }
        
                for($u = 0;$u < count($data);$u++) {
            	    $result2 = check_new_msgs($db,$ses_id,$data[$u][1],$data[$u][2],$_POST['locationSearch']);
                    //$result2 = check_new_msgs($db,$ses_id,$_POST['location'],$data[$u][1],$_POST['locationSearch']);
            	    if($result2 != false) $newData[$t++] = $result2;
                }

                //$arr[1] = array('msg' => $newData,'dialog' => $dialog);

                $dialog = check_new_dialogs($db,$ses_id,$_POST['lastDialog']);

                //$arr[1] = array('msg' => $newData,'dialog' => $dialog,'updatedMsgs' => $updatedMsgs);

                exit(json_encode(array('msg' => $newData,'dialog' => $dialog,'updatedMsgs' => $updatedMsgs)));
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

?>