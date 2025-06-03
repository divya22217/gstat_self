<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");

include '../db_inc2.php';

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
	$rd_id=$_REQUEST['role'];	 
    $old_rd_id=$_REQUEST['old_rd_id'];	

	//get new rd region
$rd_name_sql=$dbonline->prepare("select rd_region from e_master_rd where rd_id=?");

//print_r($roc_name_sql);die('l');

$rd_name_sql->bindParam(1, $rd_id, PDO::PARAM_STR);
$rd_name_sql->execute();
	
while ($rd_name_sql_result = $rd_name_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $rd_region =$rd_name_sql_result['rd_region'];
}
//print_r($rd_region);die('kk');


//get old rd region	   
	$old_rd_region_sql=$dbonline->prepare("select rd_region from e_master_rd where rd_id=?");

$old_rd_region_sql->bindParam(1, $old_rd_id, PDO::PARAM_STR);
$old_rd_region_sql->execute();
	
while ($old_rd_region_sql_result = $old_rd_region_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $old_rd_region =$old_rd_region_sql_result['rd_region'];
}
//print_r($old_rd_region);die('kk');




     $st = $db->prepare("insert into $schemas.rd_detail_his(filing_no,entry_date,user_id,rd_id,rd_region)
values(?,?,?,?,?)");

//print_r($st);
	
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $server_date, PDO::PARAM_STR);
$st->bindParam(3, $sessionUserType, PDO::PARAM_STR);
$st->bindParam(4, $old_rd_id, PDO::PARAM_STR);
$st->bindParam(5, $old_rd_region, PDO::PARAM_STR);

$st->execute();
 
 
 
 $update ="update $schemas.rd_detail set entry_date ='$server_date' ,user_id='$sessionUserType' ,rd_id='$rd_id',rd_region='$rd_region' where filing_no='$filing_no' ";
 
	$db->query($update) or die("not updated");
	  $msg = 'RECORD UPDATED  SUCESSFULLY .....';
 header("Location:./rd_modify.php?msg=$msg");
 
}



?>
