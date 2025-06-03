
<?php

 $username = "postgres";
    $password = "redhat";
   // $host = "10.247.205.253";
     $host = "10.247.166.150";
    $dbname = "newncltdb";
	//$host = "localhost";
    
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
<script type="text/javascript">
  document.onkeydown=function(e) {
    e=e||window.event;
    if (e.keyCode === 116 ) {
      e.keyCode = 0;
      alert("This action is not allowed");
      if(e.preventDefault)e.preventDefault();
      else e.returnValue = false;
      return false;
    }
  }
</script>


