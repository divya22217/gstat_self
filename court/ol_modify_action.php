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
	$ol_id=$_REQUEST['role'];	 
    $old_ol_id=$_REQUEST['old_ol_id'];	

	//get new rd region
$ol_name_sql=$dbonline->prepare("select ol_name, ol_desc from e_master_ol where ol_id=?");
//print_r($ol_id);die('l');

$ol_name_sql->bindParam(1, $ol_id, PDO::PARAM_STR);
$ol_name_sql->execute();
	
while ($ol_name_sql_result = $ol_name_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $ol_name =$ol_name_sql_result['ol_name'];
   $ol_desc =$ol_name_sql_result['ol_desc'];
}
//print_r($rd_region);die('kk');


//get old rd region	   
	$old_ol_name_sql=$dbonline->prepare("select ol_name, ol_desc from e_master_ol where ol_id=?");
//print_r($old_ol_id);die('l');
	
$old_ol_name_sql->bindParam(1, $old_ol_id, PDO::PARAM_STR);
$old_ol_name_sql->execute();
	
while ($old_ol_name_sql_result = $old_ol_name_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $old_ol_name =$old_ol_name_sql_result['ol_name'];
   $old_ol_desc =$old_ol_name_sql_result['ol_desc'];
}
//print_r($old_rd_region);die('kk');




     $st = $db->prepare("insert into $schemas.ol_detail_his(filing_no,entry_date,user_id,ol_id,ol_name,ol_desc)
values(?,?,?,?,?,?)");

//print_r($st);
	
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $server_date, PDO::PARAM_STR);
$st->bindParam(3, $sessionUserType, PDO::PARAM_STR);
$st->bindParam(4, $old_ol_id, PDO::PARAM_STR);
$st->bindParam(5, $old_ol_name, PDO::PARAM_STR);
$st->bindParam(6, $old_ol_desc, PDO::PARAM_STR);

$st->execute();
 
 
 
 $update ="update $schemas.ol_detail set entry_date ='$server_date' ,user_id='$sessionUserType' ,ol_id='$ol_id',ol_name='$ol_name',ol_desc='$ol_desc' where filing_no='$filing_no' ";
 
	$db->query($update) or die("not updated");
	  $msg = 'RECORD UPDATED  SUCESSFULLY .....';
 header("Location:./ol_modify.php?msg=$msg");
 
}



?>
