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
	$new_case_type=$_REQUEST['case_type'];
	 $sql="update e_case_detail_local set case_type ='$new_case_type' where filing_no='$filing_no'";
	
$st1x =$db->prepare("update e_case_detail_local set case_type = ? where filing_no=? ");
$st1x->bindParam(1, $new_case_type, PDO::PARAM_STR);
$st1x->bindParam(2, $filing_no, PDO::PARAM_STR);

$st1x->execute();



 }
 $msg = 'Case Type Updated Successfully .....';

 header("Location:./change_case_type.php?msg=$msg");
 
 die();



?>
