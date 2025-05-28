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
	$roc_id=$_REQUEST['role'];	 
    $old_roc_id=$_REQUEST['old_roc_id'];	

	//get new roc name
$roc_name_sql=$dbonline->prepare("select roc_name from e_master_roc where roc_id=?");

//print_r($roc_name_sql);die('l');

$roc_name_sql->bindParam(1, $roc_id, PDO::PARAM_STR);
$roc_name_sql->execute();
	
while ($roc_name_sql_result = $roc_name_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $roc_name =$roc_name_sql_result['roc_name'];
}
//print_r($roc_name);die('kk');


//get old roc name	   
	$old_roc_name_sql=$dbonline->prepare("select roc_name from e_master_roc where roc_id=?");

$old_roc_name_sql->bindParam(1, $old_roc_id, PDO::PARAM_STR);
$old_roc_name_sql->execute();
	
while ($old_roc_name_sql_result = $old_roc_name_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $old_roc_name =$old_roc_name_sql_result['roc_name'];
}
//print_r($old_roc_name);die('kk');




     $st = $db->prepare("insert into $schemas.roc_detail_his(filing_no,entry_date,user_id,roc_id,roc_name)
values(?,?,?,?,?)");

//print_r($st);
	
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $server_date, PDO::PARAM_STR);
$st->bindParam(3, $sessionUserType, PDO::PARAM_STR);
$st->bindParam(4, $old_roc_id, PDO::PARAM_STR);
$st->bindParam(5, $old_roc_name, PDO::PARAM_STR);

$st->execute();
 
 
 
 $update ="update $schemas.roc_detail set entry_date ='$server_date' ,user_id='$sessionUserType' ,roc_id='$roc_id',roc_name='$roc_name' where filing_no='$filing_no' ";
 
	$db->query($update) or die("not updated");
	  $msg = 'RECORD UPDATED  SUCESSFULLY .....';
 header("Location:./roc_modify.php?msg=$msg");
 
}



?>
