<?php

ini_set("default_charset","UTF-8");

function msgs_cycl($mess_sql_requests_res) {
    
    if($mess_sql_requests_res != false) {
                
        list($result,$row) = $mess_sql_requests_res;
    
        if($row != false && $result != false) {

            //$unreadCount = 0;

            //$a = 0;
            $a = 31;
            $arr = array();

            try {

                do {

                    $a--;

                    if($row['status'] == 0) {
                        $arr[$a] = array('id' => htmlspecialchars($row['id']),'main_id' => htmlspecialchars($row['main_id']),'otpr_id' => htmlspecialchars($row['otpr_id']),'ava' => htmlspecialchars($row['otpr_ava']), 'alias' => htmlspecialchars($row['otpr_alias']),'text' => htmlspecialchars($row['text']),'date' => htmlspecialchars($row['date_min']),'date_day' => htmlspecialchars($row['date_day']),'status' => 'unread');
                    } else {
                        $arr[$a] = array('id' => htmlspecialchars($row['id']),'main_id' => htmlspecialchars($row['main_id']),'otpr_id' => htmlspecialchars($row['otpr_id']),'ava' => htmlspecialchars($row['otpr_ava']), 'alias' => htmlspecialchars($row['otpr_alias']),'text' => htmlspecialchars($row['text']),'date' => htmlspecialchars($row['date_min']),'date_day' => htmlspecialchars($row['date_day']),'status' => 'readed');
                    }
                } while($row = $result->fetch_assoc());

            } catch(Exception $e) {
                return $e->getMesage();
            }

            //echo json_encode($arr);

            
            exit(json_encode($arr));


            //return true;

        } else {
            return false;
        }
    } else {
        return false;
    }
}


?>