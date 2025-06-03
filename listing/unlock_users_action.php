<?php
session_start();
ob_start();
include("../db_inc1.php");
include '../db_inc2.php';
$schemas=htmlspecialchars($_SESSION['schema_name']);
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); //Returns IST 
//$next_list_date=$_REQUEST['next_list_date'];
//list($d,$m,$Y) =explode('/',$next_list_date);
 //$list_date =$Y.'-'.$m.'-'.$d;
 //$listt_date=$_REQUEST['lis_date'];

$sessionUserType=htmlspecialchars($_SESSION['id']);

 $uid=$_REQUEST['uid'];

 

	

  $newst7 ="update log_attempt set failed='1',lock='Y' where id='$uid' ";


 $db->query($newst7) or die("case no not updated");   
	   
	     
	

$msg= "Suceesfully Unlock the User";
//$msghash1 =$msg."@".$listt_date;

$msghash1 =$msg;
$msghash=base64_encode($msghash1);
header("Location:./unlock_users.php?msghash=$msghash");


?>
