<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

date_default_timezone_set("Asia/Kolkata");
require("db_inc1.php");

session_start();
$_SESSION['user'];
$_SESSION['location'];

// At the top of the page we check to see whether the user is logged in or not
if($_SESSION['user']=='' and $_SESSION['location']=='')
{

	die("Redirecting to login.php");
}

else {
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // put your code here
		function getIPAddress() {  
            //whether ip is from the share internet  
            if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
                        $ip = $_SERVER['HTTP_CLIENT_IP'];  
                }  
            //whether ip is from the proxy  
            elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
                        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
            }  
        //whether ip is from the remote address  
            else{  
                    $ip = $_SERVER['REMOTE_ADDR'];  
            }  
            return $ip;  
        }  
        $client_ip = getIPAddress();
        $uname=$_SESSION['user'];
        $ipaddress=htmlspecialchars($_SERVER["REMOTE_ADDR"]);
        $yy= date("Y/m/d h:i:s");
        $at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status,client_ip) values (?,?,?,?,?,?)");
        $xx='Logout USER For other Login';
        $x='Logout';
        $at->bindParam(1, $uname, PDO::PARAM_STR);
        $at->bindParam(2, $ipaddress, PDO::PARAM_STR);
        $at->bindParam(3, $yy, PDO::PARAM_STR);
        $at->bindParam(4, $xx, PDO::PARAM_STR);
        $at->bindParam(5, $x, PDO::PARAM_STR);
        $at->bindParam(6, $client_ip, PDO::PARAM_STR);
        $at->execute();
        
    // First we execute our common code to connection to the database and start the session
   // require("db_inc1.php");
    
    // We remove the user's data from the session
    unset($_SESSION['user']);
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();
   // $y=isset(base64_encode($_REQUEST['y']));
        // We redirect them to the login page
  echo 'You Are Logout Successfully.....';
  header("Location: index.php");
    die();
        
        ?>
    </body>
</html>
<?php } ?>
