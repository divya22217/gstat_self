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
	//die('jjj');
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	$schemas=htmlspecialchars($_SESSION['schema_name']);
	$filing_no=$_REQUEST['filing_no'];
	$ol_id=$_REQUEST['role'];
	//die($ol_id);

$ol_name_sql=$dbonline->prepare("select ol_name, ol_desc from e_master_ol where ol_id=?");

//print_r($ol_name_sql);

$ol_name_sql->bindParam(1, $ol_id, PDO::PARAM_STR);
$ol_name_sql->execute();
	
while ($ol_name_sql_result = $ol_name_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $ol_name =$ol_name_sql_result['ol_name'];
   $ol_desc =$ol_name_sql_result['ol_desc'];
}


$sql2=$db->prepare("select * from  $schemas.ol_detail where filing_no=?");

$sql2->bindParam(1, $filing_no, PDO::PARAM_STR);

$sql2->execute();
	
while ($row2 = $sql2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $filing_no333 =$row2['filing_no'];
}

if($filing_no333!='')
{
$msg = 'RECORD ALREADY EXIST .....';

 header("Location:./ol.php?msg=$msg");
 exit();
}


$st = $db->prepare("insert into $schemas.ol_detail(filing_no,entry_date,user_id,ol_id,ol_name,ol_desc)
values(?,?,?,?,?,?)");

//print_r($st);
	
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $server_date, PDO::PARAM_STR);
$st->bindParam(3, $sessionUserType, PDO::PARAM_STR);
$st->bindParam(4, $ol_id, PDO::PARAM_STR);
$st->bindParam(5, $ol_name, PDO::PARAM_STR);
$st->bindParam(6, $ol_desc, PDO::PARAM_STR);

$st->execute();


 }
 $msg = 'RECORD ENTERED  SUCESSFULLY .....';

 header("Location:./ol.php?msg=$msg");
 
 



?>

