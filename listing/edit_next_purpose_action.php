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
	  $listing_date=$_REQUEST['new_listing_date'];
	   
	   $purpose=$_REQUEST['purpose_new'];






 $update ="update $schemas.case_proceeding set next_list_purpose ='$purpose'  where filing_no='$filing_no' and next_list_date='$listing_date' ";

$db->query($update) or die("not updated");





$msg = 'RECORD UPDATED  SUCESSFULLY .....';

header("Location:./edit_next_purpose.php?msg=$msg");
 
 

}

?>
