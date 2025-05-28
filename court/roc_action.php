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

$roc_name_sql=$dbonline->prepare("select roc_name from e_master_roc where roc_id=?");

//print_r($roc_name_sql);

$roc_name_sql->bindParam(1, $roc_id, PDO::PARAM_STR);
$roc_name_sql->execute();
	
while ($roc_name_sql_result = $roc_name_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $roc_name =$roc_name_sql_result['roc_name'];
}


$sql2=$db->prepare("select * from  $schemas.roc_detail where filing_no=?");

$sql2->bindParam(1, $filing_no, PDO::PARAM_STR);

$sql2->execute();
	
while ($row2 = $sql2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $filing_no333 =$row2['filing_no'];
}

if($filing_no333!='')
{
$msg = 'RECORD ALREADY EXIST .....';

 header("Location:./roc.php?msg=$msg");
 exit();
}


$st = $db->prepare("insert into $schemas.roc_detail(filing_no,entry_date,user_id,roc_id,roc_name)
values(?,?,?,?,?)");

//print_r($st);
	
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $server_date, PDO::PARAM_STR);
$st->bindParam(3, $sessionUserType, PDO::PARAM_STR);
$st->bindParam(4, $roc_id, PDO::PARAM_STR);
$st->bindParam(5, $roc_name, PDO::PARAM_STR);

$st->execute();


 }
 $msg = 'RECORD ENTERED  SUCESSFULLY .....';

 header("Location:./roc.php?msg=$msg");
 
 



?>
