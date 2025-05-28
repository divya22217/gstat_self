
<?php
require_once("protractsql.php");
 $username = "postgres";
    $password = "redhat@123";
    //$host = "localhost";
     //$host = "10.247.166.150";
	  $host = "10.193.85.11";
    $dbname = "gstat";
    
    try
    {

        
        $db = new PDO("pgsql:host={$host};dbname={$dbname}", $username, $password );
        }
    catch(PDOException $ex)
    {

        die("Failed to connect to the database: " . $ex->getMessage());
    }
    

   $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
     if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
    {
        function undo_magic_quotes_gpc(&$array)
        {
            foreach($array as &$value)
            {
                if(is_array($value))
                {
                    undo_magic_quotes_gpc($value);
                }
                else
                {
                    $value = stripslashes($value);
                }
            }
        }
    
        undo_magic_quotes_gpc($_POST);
        undo_magic_quotes_gpc($_GET);
        undo_magic_quotes_gpc($_COOKIE);
    }

    header('Content-Type: text/html; charset=utf-8');

    
    session_start();
   
   
   ?>
   <?php

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
    // last request was more than 15 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();
echo " <script type='text/javascript'>if (top.location != self.location) top.location = './login.php?aa=100' </script>";
die();
   // destroy session data in storage
}
 $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp


?>


