<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
date_default_timezone_set("Asia/Kolkata");
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
	$editor_data = $_POST[ 'editor1' ];
	$order_of_tribunal = $editor_data;
		//$order_of_tribunal=$_REQUEST['order_of_tribunal'];
	//$order_of_tribunal=$_REQUEST['order_of_tribunal'];
	$filing_no=$_REQUEST['filing_no'];
	$schemas=htmlspecialchars($_SESSION['schema_name']);
$order_date1= $_REQUEST['order_date1'];	
	
$adv= $_REQUEST['adv'];

$advcount = count($adv);

 $advidp= $_REQUEST['advid'];

  $advidcount = count($advidp);
  
   $advidr= $_REQUEST['advid1'];

  $advidcountr = count($advidr);


$name= $_REQUEST['name'];
 $namecount = count($name);
	
$adv1= $_REQUEST['adv1'];
$advcount1 = count($adv1);	

$rname= $_REQUEST['rname'];
$rnamecount = count($rname);	
if($advcount>0)
{
$m=1;	
for($k1=0;$k1<$advidcount;$k1++)
{	

 $advid =$advidp[$k1];
 $advname =$adv[$k1];

$st1=$db->prepare("update $schemas.order_daily_advocate set advocate=? where filing_no=? and order_date=?  and id=?  and advocate_type='P'");
$st1->bindParam(1, $advname, PDO::PARAM_STR);
$st1->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1->bindParam(3, $order_date1, PDO::PARAM_STR);
$st1->bindParam(4, $advid, PDO::PARAM_STR);


$st1->execute();

$m++;
}
}

if($advcount1>0)
{
	
	
$m1=0;	
for($p=0;$p<$advidcountr;$p++)
{
		
 $advidresp =$advidr[$p];
 $advnameresp =$adv1[$p];


 
$st2=$db->prepare("update $schemas.order_daily_advocate set advocate=? where filing_no=? and order_date=? and  id=? and advocate_type='R'");
$st2->bindParam(1, $advnameresp, PDO::PARAM_STR);
$st2->bindParam(2, $filing_no, PDO::PARAM_STR);
$st2->bindParam(3, $order_date1, PDO::PARAM_STR);
$st2->bindParam(4, $advidresp, PDO::PARAM_STR);

$st2->execute();
$m1++;
}
}

if($namecount>0)
{
for($m=0;$m<$namecount;$m++)
{

$party_type='P';
$st4=$db-> prepare("insert into $schemas.order_daily_advocate(filing_no,order_date,advocate,advocate_type,adv_serial,entry_date,user_id)   
values(?,?,?,?,?,?,?)");
$st4->bindParam(1, $filing_no, PDO::PARAM_STR);
$st4->bindParam(2, $order_date1, PDO::PARAM_STR);
$st4->bindParam(3, $name[$m], PDO::PARAM_STR);
$st4->bindParam(4, $party_type, PDO::PARAM_STR);
$st4->bindParam(5, $m, PDO::PARAM_STR);
$st4->bindParam(6, $server_date, PDO::PARAM_STR);
$st4->bindParam(7, $sessionUserType, PDO::PARAM_STR);

$st4->execute();
}
}	

if($rnamecount>0)
{
for($n=0;$n<$rnamecount;$n++)
{

$party_type1='R';
$st5=$db-> prepare("insert into $schemas.order_daily_advocate(filing_no,order_date,advocate,advocate_type,adv_serial,entry_date,user_id)   
values(?,?,?,?,?,?,?)");
$st5->bindParam(1, $filing_no, PDO::PARAM_STR);
$st5->bindParam(2, $order_date1, PDO::PARAM_STR);
$st5->bindParam(3, $rname[$n], PDO::PARAM_STR);
$st5->bindParam(4, $party_type1, PDO::PARAM_STR);
$st5->bindParam(5, $n, PDO::PARAM_STR);
$st5->bindParam(6, $server_date, PDO::PARAM_STR);
$st5->bindParam(7, $sessionUserType, PDO::PARAM_STR);

$st5->execute();
}	
}
	
	


$st=$db->prepare("update $schemas.order_daily set order_tribunal=? where filing_no=?");

$st->execute(array($order_of_tribunal,$filing_no));
   $msg="Draft Order Successfully modified";
 
}
header("Location:daily_order_modify.php?msg=$msg");



?>
