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
	  $listing_date=$_REQUEST['listing_date'];
	  $todays_status=$_REQUEST['todays_status'];
	  if($todays_status=='D')
{

	  $sql212=$db->prepare("select max(disposal_date) as disposal_date from $schemas.case_disposal  where filing_no=?  ");
   $sql212->bindParam(1, $filing_no, PDO::PARAM_STR);
  
   $sql212->execute();
   while ($row112 = $sql212->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
    $disposal_date=$row112['disposal_date'];	
}	
}

list($day1,$month1,$year1)=explode('/',$listing_date);
$listing_date=$year1.'-'.$month1.'-'.$day1;

 
 $sql_pr1="delete from $schemas.case_proceeding where filing_no='$filing_no' and listing_date='$listing_date'";

$dbh->exec($sql_pr1);


 $update ="update $schemas.case_allocation_temp set next_list_date =NULL  where filing_no='$filing_no' and listing_date='$listing_date' ";

$db->query($update) or die("not updated");

if($todays_status=='D')
{
  $sql_pr1="delete from $schemas.case_disposal where filing_no='$filing_no' and disposal_date='$disposal_date'";

$db->exec($sql_pr1);

  $update ="update $schemas.case_detail set status ='P'  where filing_no='$filing_no' ";
$db->query($update) or die("not updated");


}


}
$msg = 'RECORD UPDATED  SUCESSFULLY .....';

header("Location:./wrongly_proceeding.php?msg=$msg");
 
 



?>
