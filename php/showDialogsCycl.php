<?php

function dialogs_cycl($mess_sql_requests_res) {
    
    if($mess_sql_requests_res != false) {
                
        list($result,$row,$ses_id) = $mess_sql_requests_res;

        if($row != false && $result != false) {

            $a = 0;
            $arr = array();
            
            try {
                do {
                    if(strlen($row['text']) >= 22) {
                        $text = mb_substr($row['text'],0,22,'UTF-8');
                        $text = $text.'...';
                    }

                    if($row['status'] == 1) {
                        if($row['otpr_id'] == $ses_id) {
                            $arr[++$a] = array("n" => htmlspecialchars($row['id']),"id" => htmlspecialchars($row['poluch_id']),"ava" => htmlspecialchars($row['poluch_ava']),"alias" => htmlspecialchars($row['poluch_alias']),"date_min" => htmlspecialchars($row['date_min']),"date_day" => htmlspecialchars($row['date_day']),"text" => htmlspecialchars($row['text']),"text_id" => htmlspecialchars($row['text_id']),"main_text_id" => htmlspecialchars($row['main_text_id']),"status" =>  htmlspecialchars($row['status']),);

                        } else {
                            $arr[++$a] = array("n" => htmlspecialchars($row['id']),"id" => htmlspecialchars($row['otpr_id']),"ava" => htmlspecialchars($row['otpr_ava']),"alias" => htmlspecialchars($row['otpr_alias']),"date_min" => htmlspecialchars($row['date_min']),"date_day" => htmlspecialchars($row['date_day']),"text" => htmlspecialchars($row['text']),"text_id" => htmlspecialchars($row['text_id']),"status" =>  htmlspecialchars($row['status']),);
                        }
                    } else {
                        if($row['otpr_id'] == $ses_id) {
                            $arr[++$a] = array("n" => htmlspecialchars($row['id']),"id" => htmlspecialchars($row['poluch_id']),"ava" => htmlspecialchars($row['poluch_ava']),"alias" => htmlspecialchars($row['poluch_alias']),"date_min" => htmlspecialchars($row['date_min']),"date_day" => htmlspecialchars($row['date_day']),"text" => htmlspecialchars($row['text']),"text_id" => htmlspecialchars($row['text_id']),"main_text_id" => htmlspecialchars($row['main_text_id']),"status" =>  htmlspecialchars($row['status']),);

                        } else {
                            $arr[++$a] = array("n" => htmlspecialchars($row['id']),"id" => htmlspecialchars($row['otpr_id']),"ava" => htmlspecialchars($row['otpr_ava']),"alias" => htmlspecialchars($row['otpr_alias']),"date_min" => htmlspecialchars($row['date_min']),"date_day" => htmlspecialchars($row['date_day']),"text" => htmlspecialchars($row['text']),"text_id" => htmlspecialchars($row['text_id']),"main_text_id" => htmlspecialchars($row['main_text_id']),"status" =>  htmlspecialchars($row['status']),);
                        }
                    }
                } while($row = $result->fetch_assoc());
            } catch(Exception $e) {
                exit($e->getMessage());
            }

            echo json_encode($arr);

            return true;

        } else {
            return false;
            //exit();
        }
    } else {
        return false;
        //exit();
    }
}

?>