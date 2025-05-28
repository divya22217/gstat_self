<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");


date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); //Returns IST 
include("../db_inc1.php");
session_start();

$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];

if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
echo "Access Problem....."; 
session_unset();     // unset $_SESSION variable for the run-time
session_destroy();
header("Location: ../login.php?aa=100");
die();
}
/*
if($main_id =='9999' and $localadmin =='0')
{
		if($_SESSION['menuaccess_codeall'] !='2')
			{
				session_unset();     // unset $_SESSION variable for the run-time
				session_destroy();
				echo "You Are Not Access This Page......";
				header("Location: ../login.php?aa=100");
				die();
			}

}*/

setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);


$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key=$_SESSION['csrf'];

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	die("#2E2E2Eirecting to login.php");
}

if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
	
	
	
// This code not use next time .......	Schema session create Hear....
	
	
	$sessionUserType=htmlspecialchars($_SESSION['id']);



$curYear = htmlspecialchars(date("Y"));
$curMonth = htmlspecialchars(date("m"));
$curDay = htmlspecialchars(date("d"));
$cur_date = "$curYear-$curMonth-$curDay";
$cur_date1 ="$curDay/$curMonth/$curYear";


$link_scrutiny_idaccess='1';



$schemas=htmlspecialchars($_SESSION['schema_name']);
$entry_date = htmlspecialchars(date("F j, Y g:i a"));

/*
if( $_POST['form2'] != $_SESSION['form2_scruniny'])
{
	echo 'Invalid form submission';
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	//echo "You are not Valied User..... please login again";
	header("Location: ../login.php?aa=100");
	die();
}
if( $_POST['form2'] =='')
{
	echo 'Invalid form submission1';
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	//echo "You are not Valied User..... please login again";
	header("Location: ../login.php?aa=100");
	die();
}
*/



 $filing_no = htmlspecialchars($_REQUEST['filing_no']);

 $coulfil=strlen($filing_no);
/*
if($coulfil !='16')
{
	print "Filing No Not Right";
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	header("Location: ../login.php?aa=100");
	die();	
}

if (!is_numeric($filing_no))
{
	print "Filing No Not Empty";
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	header("Location: ../login.php?aa=100");
	die();

}
*/
$comment=$_REQUEST['comment'];

 $date=$_REQUEST['datetimepicker_mask'];

$hash1=explode(' ',$date);
 $date1=$hash1[0];
 $time=$hash1[1];
echo "</br>";

list($day,$month,$year)=explode('/',$date1);
  $notification_date1=$year.'-'.$month.'-'.$day;
 
 
  $notification_date_all=$_REQUEST['notification_date_all'];
 
 
  $adddef_sql = "insert into $schemas.regvarify_sevenday (filing_no,notify_date,
 notification_date,time,remarks,userid,entry_date) values
 ('$filing_no','$notification_date1','$notification_date_all','$time','$comment','$sessionUserType','$server_date')";
 //(?,?,?,?,?,?)";
 $sthaqq = $db->prepare($adddef_sql);
 $sthaqq->execute();
$sthaqq->execute(array($filing_no,$notification_date1,$notification_date_all,$time,$comment,$sessionUserType,$server_date));
/*
 $ll='0';
 $llpp='2';
 $st1=$db->prepare("update $schemas.scrutiny set level_level=?,varifyed_userid=? where filing_no =? ");
 $st1->bindParam(1, $ll, PDO::PARAM_STR);
 $st1->bindParam(2, $sessionUserType, PDO::PARAM_STR);
 $st1->bindParam(3, $filing_no, PDO::PARAM_STR);
 $st1->execute();
*/
  
 echo $message = 'Notification Send Successfully .....';
 $msg=base64_encode($filing_no);
 $msg1=htmlspecialchars($message.'-'.$msg);
 $hash=base64_encode($msg1);
 header("Location:./defect_cases.php?hash=$hash");
 unset($_SESSION['form2_scruniny']);
 die();
 
 }
 

 ?>