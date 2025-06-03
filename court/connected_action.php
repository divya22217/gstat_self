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
	$item_no = $_REQUEST['item_no'];
	$schemas=htmlspecialchars($_SESSION['schema_name']);
	$filing_no=$_REQUEST['filing_no'];
	$con_filing_no=$_REQUEST['con_filing_no'];


$st = $db->prepare("insert into $schemas.connected_cases(filing_no,conn_filing_no,status,
display,today_date,user_id,modify_date)
values(?,?,?,?,?,?,?)");
	$display='1';
	$status='C';

$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $con_filing_no, PDO::PARAM_STR);
$st->bindParam(3, $status, PDO::PARAM_STR);
$st->bindParam(4, $display, PDO::PARAM_STR);
$st->bindParam(5, $server_date, PDO::PARAM_STR);
$st->bindParam(6, $sessionUserType, PDO::PARAM_STR);

$st->bindParam(7, $server_date, PDO::PARAM_STR);

$st->execute();


 }
 $msg = 'Case Connected Successfully .....';

 header("Location:./connected_cases.php?msg=$msg");
 
 die();



?>
