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
	  $disposal_date=$_REQUEST['disposal_date'];
	   $disposal_date_new=$_REQUEST['disposal_date_new'];
	   
	   list($day1,$month1,$year1)=explode('/',$disposal_date);
$disposal_date_old=$year1.'-'.$month1.'-'.$day1;

list($day2,$month2,$year2)=explode('/',$disposal_date_new);
$disposal_date_latest=$year2.'-'.$month2.'-'.$day2;





$sql212=$db->prepare("select regis_date  from $schemas.case_detail  where filing_no=?  ");
   $sql212->bindParam(1, $filing_no, PDO::PARAM_STR);
  
   $sql212->execute();
   while ($row112 = $sql212->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
    $regis_date=$row112['regis_date'];	
}
if($regis_date > $disposal_date_latest)
{
	$msg = 'Disposal Date is lesser then Regis Date';

header("Location:./edit_disposal_date.php?msg=$msg");
}

 $update ="update $schemas.case_disposal set disposal_date ='$disposal_date_latest'  where filing_no='$filing_no' and disposal_date='$disposal_date_old' ";

$db->query($update) or die("not updated");




}
$msg = 'RECORD UPDATED  SUCESSFULLY .....';

header("Location:./edit_disposal_date.php?msg=$msg");
 
 



?>
