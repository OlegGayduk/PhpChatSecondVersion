<?php

session_start();

ini_set("default_charset","UTF-8");

require_once("db.php"); 
require_once("sanitize.php");
require_once("sqlRequests.php"); // file with main inspections and sql requests of all site5

if(isset($_POST)) { // checks the global post array

    $log = value_sanitize($_POST['login']);

	if($log != false) { // sanitizes of first (login) global post variable

        $pass = value_sanitize($_POST['pass']);

	    if($pass != false) { // sanitizes of second (pass) global post variable
           
            $db = connection();

            if($db != false) { 

                $_SESSION['secret'] = "eirens"; 

                $id = log_sql_requests($db,$log,$pass,$_SESSION['secret']); // assigns returning variables from function with sql requests to some another variables 
    
                if($id != false) { // makes sql requests into database and uses first and second global post variables as arguments 

                    //$token = "1234".":".md5("1234".":".$_SESSION['secret']);
                    //$token = "1234".":".md5($_SESSION['secret']);

                    //exit($token);
                    
                    if(isset($id) && $id != "") { // additional inspection,which checks our variables one more time for greater security

                        $_SESSION['id'] = $id;

                        //sqlRequests::auto_requests($db,$id);

                        echo "true"; // if all actions will return true,we will return an identifier

                        exit();

                    } else {
                        // if additional inspection will return false,we will show an error
                        exit('Fatal error!');
                    }
                } else {
                 // if 'logSqlRequests' function will return false,we will show an error
                    exit('Login and Pass are incorrect!');
                }
            } else {
                exit('Check your connection to the internet!');
            } 
        } else {
        	 // if 'sanitizePassValue' function will return false,we will show an error
            exit('Fill the all fields!');
        }
    } else {
    	 // if 'sanitizeLogValue' function will return false,we will show an error
        exit('Fill the all fields!');
    }
} else {
	 // if the global array 'post' does not exist,we will show an error
	exit('Fatal error!');
}

?>