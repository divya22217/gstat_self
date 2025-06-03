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
	   $confirmation_rp=$_REQUEST['confirmation_rp'];
	   $change_name_rp=$_REQUEST['change_name_rp'];
	   $change_name_irp=$_REQUEST['change_name_irp'];
	   $accp_rej=$_REQUEST['accp_rej'];
	   
	   
	   
	   if($dt_of_appointment!='')
	   {
	list($day1,$month1,$year1)=explode('/',$dt_of_appointment);
$dt_of_appointment=$year1.'-'.$month1.'-'.$day1;
	   }
if($confirmation_rp!='')
{
list($day2,$month2,$year2)=explode('/',$confirmation_rp);
$confirmation_rp=$year2.'-'.$month2.'-'.$day2;
}
if($change_name_irp!='')
{
list($day4,$month4,$year4)=explode('/',$change_name_irp);
$change_name_irp=$year4.'-'.$month4.'-'.$day4;
}
if($change_name_rp!='')
{
list($day3,$month3,$year3)=explode('/',$change_name_rp);
$change_name_rp=$year3.'-'.$month3.'-'.$day3;
}
if($accp_rej!='')
{
list($day5,$month5,$year5)=explode('/',$accp_rej);
$accp_rej=$year5.'-'.$month5.'-'.$day5;
}

if($date_of_removal=='')
{
	$date_of_removal='1111-11-11';
}
if($confirmation_rp=='')
{
	$confirmation_rp='1111-11-11';
}

if($dt_of_appointment=='')
{
	$dt_of_appointment='1111-11-11';
}
if($confirmation_rp=='')
{
	$confirmation_rp='1111-11-11';
}
if($change_name_irp=='')
{
	$change_name_irp='1111-11-11';
}
if($change_name_rp=='')
{
	$change_name_rp='1111-11-11';
}

if($accp_rej=='')
{
	$accp_rej='1111-11-11';
}



 }


     $sql_pr1="insert into $schemas.irp_detail_his select * from $schemas.irp_detail where filing_no='$filing_no'";

  
   $dbh->exec($sql_pr1);
 

 
  $update ="update $schemas.irp_detail set name ='$name' ,email='$email' ,role='$role',mobile='$mobile',entry_date='$server_date',user_id='$sessionUserType',appoinment='$dt_of_appointment',confirmation_rp='$confirmation_rp' ,change_irp_name='$change_name_irp',change_rp_name='$change_name_rp',acc_rej='$accp_rej',enrolment_no='$enrol_no' where filing_no='$filing_no' ";
 
	$db->query($update) or die("not updated");
	
	  $msg = 'RECORD UPDATED  SUCESSFULLY .....';
 header("Location:./rp_edit.php?msg=$msg");
 
 



?>
