<?php

function log_sql_requests($db,$log,$pass,$secret) {
    if($db != false) {

        $result = $db->query("SELECT id,login,password FROM users WHERE login='$log' and password='$pass'");

        if($result->num_rows > 0) {

            $row = $result->fetch_assoc();

            if($row['login'] != "" && $row['password'] != "" && $row['id'] != "") {
                $token = "1234".":".md5("1234".":".$secret);

                setcookie("CSRF-TOKEN",$token,time() + 9900);

                return $row['id'];
            } else {
                $db->close();
                return false;
            }
        } else {
            $db->close();
            return false;
        }
    } else {
        return false;
    }
} 

function dialogs_requests($db,$ses_id) {
    if($db != false) {

        $result = $db->query("SELECT id,poluch_id,poluch_alias,poluch_ava,otpr_id,otpr_alias,otpr_ava,msg_otpr,date_min,date_day,status,text,text_id,main_text_id FROM dialogs WHERE (otpr_id='$ses_id' or poluch_id='$ses_id') ORDER BY date_day DESC LIMIT 15");

        if($result->num_rows > 0)  {

            $row = $result->fetch_assoc();

            $a = 0;
            
            try {
                    do {
                        if(strlen($row['text']) >= 22) {
                            $text = mb_substr($row['text'],0,22,'UTF-8');
                            $text = $text.'...';
                        }

                        if($row['otpr_id'] == $ses_id) {
                            $t = array($row['poluch_id'],$row['poluch_ava'],$row['poluch_alias']);
                        } else {
                            $t = array($row['otpr_id'],$row['otpr_ava'],$row['otpr_alias']);
                        }
                        
                        //можно избавиться от ненужных htmlspecialchars
                        $arr[$a] = array("n" => $row['id'],"id" => $t[0],"ava" => $t[1],"alias" => $t[2],"date_min" => $row['date_min'],"date_day" => $row['date_day'],"text" => $row['text'],"text_id" => $row['text_id'],"main_text_id" => $row['main_text_id'],"status" =>  $row['status'],);
                        $a++;
                    } while($row = $result->fetch_assoc());
            } catch(Exception $e) {
                exit($e->getMessage());
            }

            return json_encode($arr);
            //return $arr;

            //return array($result,$row,$ses_id);
        } else {
            $db->close();
            return false;
        }
    } else {
        return false;
    }
}

function msgs_requests($db,$ses_id,$sell) {

    if($db != false) {

        $result = $db->query("SELECT otpr_id,poluch_id,main_id FROM msgs WHERE (otpr_id='$ses_id' and poluch_id='$sell') or (poluch_id='$ses_id' and otpr_id='$sell') and status=0");

        if($result->num_rows > 0) {

            $row = $result->fetch_assoc();

            do {
                if($ses_id == $row['poluch_id']) {

                    $id = $row['main_id'];
                    //$db->query("UPDATE msgs SET status=1 WHERE (poluch_id='$ses_id' and otpr_id='$sell') and status=0 and main_id='$id'");
                    $db->query("UPDATE msgs SET status=1 WHERE main_id='$id'");

                    $db->query("UPDATE dialogs SET status=1 WHERE main_text_id='$id'");
                }
            } while($row = $result->fetch_assoc());

            //$db->query("UPDATE dialogs SET status=1 WHERE (otpr_id='$ses_id' and poluch_id='$sell') or (poluch_id='$ses_id' and otpr_id='$sell') and main_text_id");

            $result->close();
        } else { 
            $result->close();
        }
        
        //можно без desk,тогда можно будет не использовать transform в js,но будут проблемы с индексами (в случае не со строками)
        //$result3 = $db->query("SELECT id,main_id,text,file,otpr_id,otpr_alias,otpr_ava,date_min,date_day,status FROM msgs WHERE (otpr_id='$ses_id' and poluch_id='$sell') or (poluch_id='$ses_id' and otpr_id='$sell') ORDER BY id DESC LIMIT 30");

        $result3 = $db->query("SELECT id,main_id,text,file,otpr_id,otpr_alias,otpr_ava,date_min,date_day,status FROM msgs WHERE (otpr_id='$ses_id' and poluch_id='$sell') or (poluch_id='$ses_id' and otpr_id='$sell') ORDER BY id DESC LIMIT 30");
        
        if($result3->num_rows > 0) {

            $row3 = $result3->fetch_assoc();

            $a = 0;

            try {
                do {
                    /*if($row3['status'] == 0) {
                        $str = 'unread';
                    } else {
                        $str = 'readed';
                    }*/

                    $arr[++$a] = array('id' => $row3['id'],'main_id' => $row3['main_id'],'otpr_id' => $row3['otpr_id'],'ava' => $row3['otpr_ava'],'alias' => $row3['otpr_alias'],'text' => $row3['text'],'date' => $row3['date_min'],'date_day' => $row3['date_day'],'status' => $row3['status']);
                } while($row3 = $result3->fetch_assoc());

            } catch(Exception $e) {
                return $e->getMesage();
            }

            return json_encode($arr);

            //return array($result3,$row3);
        } else {
            $db->close();
            return false;
        }
    } else {
        return false;
    }
}

function update_msgs_dates($db,$summ) {
    if($db != false) {
        $result = $db->query("SELECT date_min,date_day FROM msgs WHERE summ='$summ'");
        
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $a = 0;

            $currentDate = date('d.m.Y');

            do {
                if($row['date_day'] != $current_date) {
                    $current_date_day = date('d');
                    $date_day_from_base = $row['date_day'][0] + $row['date_day'][1];

                    if($current_date_date - $date_day_from_base == 1) {
                        $db->query("INSERT INTO msgs(date_min) VALUES ('Yesterday')");
                    } else {
                        $date_day = $row['date_day'];
                        $db->query("INSERT INTO msgs(date_min) VALUES ('$date_day')");
                    }                
                }
            } while($result->fetch_assoc());
        } else {
            return false;
        } 
    } else {
        return false;
    }
}

function msg_file_request($db,$ses_id,$ses_alias,$ses_ava,$sell,$summ,$text,$true_path,$dateMin,$dateDay) {
    if($db != false) {

        $arr = array();

        $db->query("INSERT INTO msgs(otpr_id,otpr_alias,otpr_ava,text,file,date_min,date_day,summ,status) VALUES ('$ses_id','$ses_alias','$ses_ava','$text','$true_path','$dateMin','$dateDay','$summ',0)");

        $result100 = $db->query("SELECT MAX(id) as id FROM msgs WHERE summ='$summ'");

        $row100 = $result100->fetch_assoc();

        $id = $row100['id'];
    
        $result = $db->query("SELECT otpr_id,otpr_alias,otpr_ava,text,file,date_min FROM msgs WHERE summ='$summ' and id='$id'");
    
        $row = $result->fetch_assoc();

        $arr[1] = array('id' => htmlspecialchars($id),'otpr_id' => htmlspecialchars($row['otpr_id']),'alias' => htmlspecialchars($row['otpr_alias']),'ava' => htmlspecialchars($row['otpr_ava']),'text' => htmlspecialchars($row['text']),'file' => htmlspecialchars($row['file']),'date' => htmlspecialchars($row['date_min']), 'status' => 'unread');

        return json_encode($arr);
    } else {
        return false;
    }
}

function msg_send_request_full($db,$main_id,$sell,$ses_id,$ses_alias,$ses_ava,$text,$dateMin,$dateDay) {
    if($db != false) {

        $db->query("INSERT INTO msgs(main_id,poluch_id,otpr_id,text,date_min,date_day,status,file,otpr_ava,otpr_alias) VALUES ('$main_id','$sell','$ses_id','$text','$dateMin','$dateDay',0,0,0,0)");

        $result = $db->query("SELECT id FROM msgs WHERE (main_id='$main_id') and ((otpr_id='$ses_id' and poluch_id='$sell') or (poluch_id='$ses_id' and otpr_id='$sell'))");

        if($result->num_rows > 0) {

            $row = $result->fetch_assoc();

            //$arr[1] = array('id' => htmlspecialchars($row['id']),'main_id' => htmlspecialchars($main_id),'otpr_id' => htmlspecialchars($ses_id),'text' => htmlspecialchars($text),'date' => htmlspecialchars($dateMin), 'date_day' => htmlspecialchars($dateDay),'status' => 'unread');

            //return json_encode($arr);
            //return $arr;
            return array('id' => $row['id'],'main_id' => $main_id,'otpr_id' => $ses_id,'text' => $text,'date' => $dateMin, 'date_day' => $dateDay,'status' => 0);
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function msg_dialog_send($db,$id,$ses_id,$main_id,$sell,$text,$dateMin,$dateDay) {
    if($db != false) {
        $result = $db->query("SELECT * FROM dialogs WHERE (otpr_id='$ses_id' and poluch_id='$sell') or (poluch_id='$ses_id' and otpr_id='$sell')");

        if($result->num_rows > 0) {
            $db->query("UPDATE dialogs SET text='$text',date_min='$dateMin',date_day='$dateDay',text_id='$id',main_text_id='$main_id',status=0 WHERE (otpr_id='$ses_id' and poluch_id='$sell') or (poluch_id='$ses_id' and otpr_id='$sell')");

            //return true;
            return false;
        } else {

            $result2 = $db->query("SELECT alias,ava,online FROM users WHERE id='$sell'");
            $result3 = $db->query("SELECT alias,ava,online FROM users WHERE id='$ses_id'");

            if($result2->num_rows > 0 && $result3->num_rows > 0) {

                $row = $result2->fetch_assoc();
                $row2 = $result3->fetch_assoc();

                $alias = $row['alias'];
                $ava = $row['ava'];
                $status = $row['online'];

                $otpr_alias = $row2['alias'];
                $otpr_ava = $row2['ava'];
                $otpr_status = $row2['online'];

                $db->query("INSERT INTO dialogs(msg_otpr,otpr_id,otpr_alias,otpr_ava,otpr_status,poluch_id,poluch_alias,poluch_ava,poluch_status,date_min,date_day,status,text,text_id,main_text_id) VALUES ('$ses_id','$ses_id','$otpr_alias','$otpr_ava','$otpr_status','online','$sell','$alias','$ava',0,'$dateMin','$dateDay',0,'$text','$id','$main_id')");

                if(strlen($text) >= 22) {
                    $text = mb_substr($row['text'],0,22,'UTF-8');
                    $text = $text.'...';
                }
                 
                //$arr[1] = array("n" => htmlspecialchars($id),"id" => htmlspecialchars($sell),"ava" => htmlspecialchars($ava),"alias" => htmlspecialchars($alias),"date_min" => htmlspecialchars($dateMin),"date_day" => htmlspecialchars($dateDay),"text" => htmlspecialchars($text),"text_id" => htmlspecialchars($id),"main_text_id" => htmlspecialchars($main_id),);

                return array("n" => $id,"id" => $sell,"ava" => $ava,"alias" => $alias,"date_min" => $dateMin,"date_day" => $dateDay,"text" => $text,"text_id" => $id,"main_text_id" => $main_id,);
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
}

function get_more_msgs($db,$last_msg,$ses_id,$sell) {
    if($db != false) {

        $result = $db->query("SELECT id,main_id,otpr_id,text,file,otpr_alias,otpr_ava,date_min,date_day,status FROM msgs WHERE (id < '$last_msg') and ((otpr_id='$ses_id' and poluch_id='$sell') or (poluch_id='$ses_id' and otpr_id='$sell')) ORDER BY id DESC LIMIT 30");

        if($result->num_rows > 0) {

            $row = $result->fetch_assoc();

            $a = 0;

            do {
                /*if($row['status'] == 0) {
                    $str = 'unread';
                } else {
                    $str = 'readed';
                }*/

                $arr[++$a] = array('id' => $row['id'],'main_id' => $row['main_id'],'otpr_id' => $row['otpr_id'],'ava' => $row['otpr_ava'], 'alias' => $row['otpr_alias'],'text' => $row['text'],'date' => $row['date_min'],'date_day' => $row['date_day'],'status' => $row['status'],);

            } while($row = $result->fetch_assoc());

            return json_encode($arr);

            //return array($result,$row);

        } else {
            return false;
        }
    } else {
        return false;
    }
}

function search_dialogs($db,$searchText,$ses_id) {
    if($db != false) {

        $result = $db->query("SELECT id,otpr_id,poluch_id,otpr_alias,date_min,date_day,text FROM msgs WHERE text LIKE '%$searchText'");

        if($result->num_rows > 0) {

        	$row = $result->fetch_assoc();

        	$a = 0;
            $arr = array();

            do {

                if(strlen($row['text']) >= 22) {
                    $text = mb_substr($row['text'],0,22,'UTF-8');
                    $text = $text."...";
                }

                if($row['otpr_id'] == $ses_id) {
                    $t = array($row['poluch_id'],$row['poluch_ava']);
                } else {
                    $t = array($row['otpr_id'],$row['otpr_ava']);
                }

                $alias = get_search_alias($db,$t[0]);

                if($alias != false) {
                    $arr[++$a] = array("n" => $row['id'],"id" => $t[0],"ava" => $t[1],"alias" => $alias,"date_min" => $row['date_min'],"date_day" => $row['date_day'],"text" => $text.'...',);
                } else {
                    return false;
                }

            } while($row = $result->fetch_assoc());

            return json_encode($arr);

        } else {
            return false;
        }
    } else {
        return false;
    }
}

function get_search_alias($db,$id) {
    if($db != false) {
        
        $result = $db->query("SELECT poluch_alias FROM dialogs WHERE poluch_id='$id'");

        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
  
            return $row['poluch_alias'];
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function delete_msgs($db,$msg,$sell) {
    if($db != false) {

        $db->query("DELETE FROM msgs WHERE main_id='$msg'");

        return;
    } else {
        return false;
    }
}

function update_dialog_after_delete($db,$ses_id,$sell) {

    if($db != false) {

        $result = $db->query("SELECT * FROM msgs WHERE (otpr_id='$ses_id' and poluch_id='$sell') or (poluch_id='$ses_id' and otpr_id='$sell') ORDER BY id DESC LIMIT 1");

        if($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            
            $id = $row['id'];
            $main_id = $row['main_id'];
            $date_min = $row['date_min'];
            $date_day = $row['date_day'];
            $text = $row['text'];
            $status = $row['status'];

            $db->query("UPDATE dialogs SET text_id='$id',main_text_id='$main_id',date_min='$date_min',date_day='$date_day',text='$text',status='$status' WHERE (otpr_id='$ses_id' and poluch_id='$sell') or (poluch_id='$ses_id' and otpr_id='$sell')");

            return;
            //return false;
        } else {
            $db->query("UPDATE dialogs SET text_id=0,main_text_id=0,date_min=0,date_day=0,text='The chat is empty.',status=1 WHERE (otpr_id='$ses_id' and poluch_id='$sell') or (poluch_id='$ses_id' and otpr_id='$sell')");
            
            return;
            //return true;

            //return false;
        }
    } else {
        return false;
    }
}

function update_msg_status($db,$number) {

    if($db != false) {
        //$db->query("UPDATE msgs SET status='readed' WHERE main_id='$number'");
        $db->query("UPDATE msgs SET status=1 WHERE main_id='$number'");

        $db->query("UPDATE dialogs SET status=1 WHERE main_text_id='$number'");

        return;
    } else {
        return false;
    }
}

function check_msgs_updates($db,$ses_id,$id) {

    if($db != false) {

        $result = $db->query("SELECT otpr_id,poluch_id,status FROM msgs WHERE main_id='$id'");

        if($result->num_rows > 0) {

            $row = $result->fetch_assoc();

            if($row['status'] == 1) {
                //return $id;
                if($row['otpr_id'] == $ses_id) {
                    return array($row['poluch_id'],$id);
                } else {
                    return array($row['otpr_id'],$id);
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function check_new_msgs($db,$ses_id,$sell,$endMsg,$locationSearch) {

    if($db != false) {

        //$result = $db->query("SELECT id,main_id,text,file,otpr_id,otpr_alias,otpr_ava,date_min,date_day,status FROM msgs WHERE ('$endMsg' < id) and (poluch_id='$ses_id' and otpr_id='$sell')");

        $result = $db->query("SELECT id,main_id,text,file,otpr_id,otpr_alias,otpr_ava,date_min,date_day,status FROM msgs WHERE (id > $endMsg) and (poluch_id='$ses_id' and otpr_id='$sell')");

        if($result->num_rows > 0) {

            $row = $result->fetch_assoc();

            $a = 0;

            do {
                if($locationSearch == 'true') {

                    $id = $row['main_id'];

                    $db->query("UPDATE msgs SET status=1 WHERE main_id='$id'");

                    $db->query("UPDATE dialogs SET status=1 WHERE main_text_id='$id'");

                    $arr[++$a] = array('id' => $row['id'],'main_id' => $id,'otpr_id' => $row['otpr_id'],'ava' => $row['otpr_ava'], 'alias' => $row['otpr_alias'],'text' => $row['text'],'date' => $row['date_min'],'date_day' => $row['date_day'],'status' => 1);
                } else {

                    /*if($row['status'] == 0) {
                        $str = 'unread';
                    } else {
                        $str = 'readed';
                    }*/

                    $arr[++$a] = array('id' => $row['id'],'main_id' => $row['main_id'],'otpr_id' => $row['otpr_id'],'ava' => $row['otpr_ava'], 'alias' => $row['otpr_alias'],'text' => $row['text'],'date' => $row['date_min'],'date_day' => $row['date_day'],'status' => $row['status']);

                }
            } while($row = $result->fetch_assoc());

            return $arr;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function check_new_dialogs($db,$ses_id,$last_dialog) {

    if($db != false) {

        $result = $db->query("SELECT * FROM dialogs WHERE (id > $last_dialog) and poluch_id='$ses_id'");

        if($result->num_rows > 0) {

            $row = $result->fetch_assoc();

            if($row['otpr_id'] == $ses_id) {
                $sell = $row['poluch_id'];
            } else {
                $sell = $row['otpr_id'];
            }

            $result2 = $db->query("SELECT alias,ava,online FROM users WHERE id='$sell'");

            if($result2->num_rows > 0) {

                $row2 = $result2->fetch_assoc();

                $alias = $row2['alias'];
                $ava = $row2['ava'];
                $status = $row2['online'];

                if(strlen($row['text']) >= 22) {
                    $text = mb_substr($row['text'],0,22,'UTF-8');
                    $text = $text.'...';
                }

                $t = 0;
                
                do {
                    $arr[++$t] = array("n" => $row['id'],"id" => $sell,"ava" => $ava,"alias" => $alias,"date_min" => $row['date_min'],"date_day" => $row['date_day'],"text" => $row['text'],"text_id" => $row['text_id'],"main_text_id" => $row['main_text_id'],);
                } while($row = $result->fetch_assoc());

                return $arr;
            } else {
                return false;
            }

            //return array($result,$row,$ses_id);
        } else {
            return false;
        }
    } else {
        return false;
    }
}

?>