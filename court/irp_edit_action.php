<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
 $server_date= date('Y-m-d');
 
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
echo "you Can't access this page";
}
else
{
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	
	$schemas=htmlspecialchars($_SESSION['schema_name']);
	 $filing_no=$_REQUEST['filing_no'];
	  $name=$_REQUEST['name'];
	  $email=$_REQUEST['email_irp'];
	  $mobile=$_REQUEST['mobile'];
	  $enrol_no=$_REQUEST['enrol_no'];
	   $role=$_REQUEST['role'];
	   $dt_of_appointment=$_REQUEST['dt_of_appointment'];
	   
  
list($day1,$month1,$year1)=explode('/',$dt_of_appointment);
$dt_of_appointment=$year1.'-'.$month1.'-'.$day1;

 }
$sql_pr1="insert into $schemas.irp_detail_his select * from $schemas.irp_detail where filing_no='$filing_no'";
$dbh->exec($sql_pr1);
$update ="update $schemas.irp_detail set name ='$name' ,email='$email' ,mobile='$mobile',entry_date='$server_date',user_id='$sessionUserType',appoinment='$dt_of_appointment',enrolment_no='$enrol_no'  where filing_no='$filing_no' ";
$db->query($update) or die("not updated");
$msg = 'RECORD UPDATED  SUCESSFULLY .....';
header("Location:./irp_edit.php?msg=$msg");
 
 



?>
