<?php

session_start();
ob_start();
include("../db_inc1.php");
include '../custom/custom_function.php';
$schemas=htmlspecialchars($_SESSION['schema_name']);
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); //Returns IST 
$next_list_date=$_REQUEST['next_list_date'];
list($d,$m,$Y) =explode('/',$next_list_date);
 $list_date =$Y.'-'.$m.'-'.$d;
 $listt_date=$_REQUEST['lis_date'];
 //$list_flag=$_REQUEST['b_type'];
//echo "<pre>"; print_r($_REQUEST); die;
$sessionUserType=htmlspecialchars($_SESSION['id']);
$username = $_SESSION['actual_username'];
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */

$checkbox=$_REQUEST['checkbox'];
 
$l=sizeof($checkbox);
 for($i=0;$i<$l;$i++)
 {
	  $notified_status = 1;
	  $filing_no=$checkbox[$i];
	  list($filing_no,$max_list_date) = explode("/",$filing_no);
	
	try{
	 $db->beginTransaction();
	 $st3="INSERT INTO $schemas.notified_cases (filing_no,old_listing_date,notified_date,user_id,user_name,notified_status) values (?,?,?,?,?,?)";
	 $sth1=$db->prepare($st3);
	 $sth1->bindParam(1, $filing_no, PDO::PARAM_STR);
	 $sth1->bindParam(2, $list_date, PDO::PARAM_STR);
	 $sth1->bindParam(3, $server_date, PDO::PARAM_STR);
	 $sth1->bindParam(4, $sessionUserType, PDO::PARAM_STR); 
	 $sth1->bindParam(5, $username, PDO::PARAM_STR);
	 $sth1->bindParam(6, $notified_status, PDO::PARAM_STR);
	 $sth1->execute();
	 
	 $subject="Notified case ".$filing_no ;
		$email_text1="Case with diary no ".$filing_no." and listing date :".$next_list_date." is notified for next hearing date. This is a computer generated message, Please do not  reply"  ;
  
		$msg555="Case with diary no ".$filing_no." and listing date :".$next_list_date." is notified for next hearing date.";
		
		$main_shoot = fn_sms($db, '12', $filename_sms='', $filing_no, $subject, $msg555, $email_text1,$save_path='');
	 $db->commit();
	 $msg= "Cases notified";
	$msghash1 =$msg."@".$list_date."@"."notify";
	$msghash=base64_encode($msghash1);
	header("Location:./old_case_list.php?msghash=$msghash");
	}
	catch(\Exception $e){
		$db->rollBack();
		 $msg= "Something went wrong!!";
	$msghash1 =$msg."@".$list_date."@"."notify";
	$msghash=base64_encode($msghash1);
	header("Location:./old_case_list.php?msghash=$msghash");
		
		//echo "Caught", $e->getMessage(),"\n";

	}
	
 
	
} 



?>