<?php
session_start();
ob_start();
include("../db_inc1.php");
include '../db_inc2.php';
$schemas=htmlspecialchars($_SESSION['schema_name']);
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); //Returns IST 
$next_list_date=$_REQUEST['next_list_date'];
list($d,$m,$Y) =explode('/',$next_list_date);
 $list_date =$Y.'-'.$m.'-'.$d;
 $listt_date=$_REQUEST['lis_date'];
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$sessionUserType=htmlspecialchars($_SESSION['id']);

 $bench_no=$_REQUEST['bench_no'];

 $checkbox=$_REQUEST['checkbox'];

$purpose_id=$_REQUEST['purpose_id'];

 $sql21="select * from $schemas.bench where from_list_date='$listt_date' and bench_no='$bench_no' ";
foreach($db->query($sql21) as $row21)
	{	
	  $b_nature =$row21['bench_nature'];
	 $c_no =$row21['court_no'];
	// $id =$row21['id'];
	 $location_code =$row21['location_code'];
	
	}	
	
	 $sql="select max(priority_serial) as priority_serial from $schemas.case_allocation_temp where listing_date='$listt_date' ";
foreach($db->query($sql) as $row)
	{	
	  $priority_serial1 =$row['priority_serial'];
	}
	 
	if($priority_serial1=='' || $priority_serial1==0)
	{
		$priority_serial1=1;
	}
	else
	{
		$priority_serial1=$priority_serial1+1;
	}

 $l=sizeof($checkbox);
 for($i=0;$i<$l;$i++)
 {
	   $filing_no=$checkbox[$i];

	if($server_date !='')
{
	
	list($year,$month,$day)=explode('-',$server_date);
	  $reg_year_server=$year;	
}
$regis_date =$year.'-'.$month.'-'.$day;
$regis_date11 =$day.'/'.$month.'/'.$year;


$sql="select case_type from $schemas.case_detail where filing_no='$filing_no' ";
foreach($db->query($sql) as $row)
	{	
	  $case_type =$row['case_type'];
	}
/*
$newst6="select reg_no from $schemas.case_type_reg where case_type='$case_type' and reg_year='$reg_year_server' and location_code='$location_code' ";
$newst6=$db->prepare($newst6);
$newst6->execute();
$regis_no = $newst6->fetchColumn();
if($regis_no==0)
{
$regis_no=1;
}
else
{
$regis_no++;
}
*/
/*&
if($case_type!=)
{
	*/
	
	
	/*
$newst7 ="update $schemas.case_detail set case_no ='$regis_no',case_year ='$reg_year_server',location_code ='$location_code',regis_date='$regis_date' where filing_no='$filing_no' ";
$db->query($newst7) or die("case no not updated");
		
$newst8 ="update $schemas.case_type_reg set reg_no ='$regis_no'  where case_type='$case_type' and location_code='$location_code' and reg_year='$reg_year_server'";
$db->query($newst8) or die("filing counter not updated");


*/
/*}

else
{
}
*/	 
	 
	 $check_sql =$dbonline->prepare("select rep_code from e_more_representative where filing_no =? and party_flag='P' and party_serial_no='1'");
$check_sql->bindParam(1, $filing_no, PDO::PARAM_STR);
$check_sql->execute();
 $pet_adv_code= $check_sql->fetchColumn();
 

if($pet_adv_code=='')
{
	$pet_adv_code='0';
}

if($pet_adv_code >0)
{
$stqq12 = $dbonline->prepare("select rep_name from e_master_advocate where id=?");
$stqq12->bindParam(1, $pet_adv_code, PDO::PARAM_INT);
$stqq12->execute();
$pet_adv_name = $stqq12->fetchColumn();	

 
$stqq12 = $dbonline->prepare("select email from e_master_advocate where id=?");
$stqq12->bindParam(1, $pet_adv_code, PDO::PARAM_INT);
$stqq12->execute();
$pet_adv_email = $stqq12->fetchColumn();	
$stqq12 = $dbonline->prepare("select mobile from e_master_advocate where id=?");
$stqq12->bindParam(1, $pet_adv_code, PDO::PARAM_INT);
$stqq12->execute();
$pet_adv_mobile = $stqq12->fetchColumn();	
	
}
$adv_party='R';
$adv_party_serial='1';
$check_sql1 =$dbonline->prepare("select rep_code from e_more_representative where filing_no =? and party_flag=? and party_serial_no=?");
$check_sql1->bindParam(1, $filing_no, PDO::PARAM_STR);
$check_sql1->bindParam(2, $adv_party, PDO::PARAM_STR);
$check_sql1->bindParam(3, $adv_party_serial, PDO::PARAM_STR);
$check_sql1->execute();
$res_adv_code= $check_sql1->fetchColumn();
if($res_adv_code=='')
{
	$res_adv_code='0';
}
if($res_adv_code >0)
{
$stqq121 = $dbonline->prepare("select rep_name from e_master_advocate where id=?");
$stqq121->bindParam(1, $res_adv_code, PDO::PARAM_INT);
$stqq121->execute();
$res_adv_name = $stqq121->fetchColumn();	

 
$stqq122 = $dbonline->prepare("select email from e_master_advocate where id=?");
$stqq122->bindParam(1, $res_adv_code, PDO::PARAM_INT);
$stqq122->execute();
 $res_adv_email = $stqq122->fetchColumn();	
$stqq123 = $dbonline->prepare("select mobile from e_master_advocate where id=?");
$stqq123->bindParam(1, $res_adv_code, PDO::PARAM_INT);
$stqq123->execute();
$res_adv_mobile = $stqq123->fetchColumn();	
	
}
$pet_flag='P';
$pet_serial='1';
$sthr2=$dbonline->prepare("select email,mobile,name from e_cases_party where filing_no =? and party_flag=? and party_serial_no=? ");
  $sthr2->bindParam(1, $filing_no, PDO::PARAM_STR);
  $sthr2->bindParam(2, $pet_flag, PDO::PARAM_STR);
  $sthr2->bindParam(3, $pet_serial, PDO::PARAM_STR);
  $sthr2->execute();
  while ($row1 = $sthr2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  	$pet_email=$row1['email'];
	$pet_mobile=$row1['mobile'];
	$pet_name1=$row1['name'];
  }
$res_flag='R';
$res_serial='1';
  $sthr3=$dbonline->prepare("select email,mobile,name from e_cases_party where filing_no =? and party_flag=? and party_serial_no=?");
  $sthr3->bindParam(1, $filing_no, PDO::PARAM_STR);
  $sthr3->bindParam(2, $res_flag, PDO::PARAM_STR);
  $sthr3->bindParam(3, $res_serial, PDO::PARAM_STR);
  $sthr3->execute();
  while ($row3 = $sthr3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  	$res_email=$row3['email'];
	$res_mobile=$row3['mobile'];
	$res_name1=$row3['name'];
  }
/*
$lcode ="select short_name from $schemas.bench_location where bench_location_code ='$location_code'";
$lcode=$db->prepare($lcode);
$lcode->execute();
$lcodename = $lcode->fetchColumn();



$lcode1 ="select short_name from case_type where id ='$case_type'";
$lcode1=$db->prepare($lcode1);
$lcode1->execute();
$case_type_short_name = $lcode1->fetchColumn();

$CASE_NO22 = htmlspecialchars(strtoupper($case_type_short_name).'/'.$regis_no.'('.$lcodename.')'.$reg_year_server);

$subject="Your Case Number Generated under diary no ".$filing_no;
$email_text="Case Number Generated under diary no ".$filing_no." is ".$CASE_NO22." on date ".$regis_date11." This is a computer generated message, Please do not reply "  ;

$msg555="Case Number Generation under diary no ".$filing_no." is ".$CASE_NO22." on " .$regis_date11;
*/
/*
 $sql ="insert into sms(filing_no,case_number,msg,pet_adv_name,pet_adv_mob_no,pet_adv_email,pet_mobile,res_mobile,pet_name,res_name,res_adv_code,pet_adv_code,res_adv_name,res_adv_mob_no,subject,email_text,res_adv_email,pet_email,res_email,send_flag,entry_date,sms_flag) 
VALUES('$filing_no','$CASE_NO22','$msg555','$pet_adv_name','$pet_adv_mobile','$pet_adv_email','$pet_mobile','$res_mobile','$pet_name','$res_name','$res_adv_code','$pet_adv_code','$res_adv_name','$res_adv_mobile','$subject','$email_text','$res_adv_email','$pet_email','$res_email','0','$regis_date','G')";

$st = $dbonline->prepare($sql);
$st->execute(); 
	*/ 
	
	$std = $db->prepare("select * from $schemas.case_allocation_temp where filing_no=? and listing_date=?");
$std->bindParam(1, $filing_no, PDO::PARAM_INT);
$std->bindParam(2, $listt_date, PDO::PARAM_INT);
$std->execute();
$cnt=$std->rowCount();


if($cnt==0)
{

  $newst7 ="update $schemas.case_detail set res_occupation ='L',court_no='$c_no',location_code ='$location_code' where filing_no='$filing_no' ";

 $db->query($newst7) or die("case no not updated");   
	   
	     
	   
  $insert ="insert into $schemas.case_allocation_temp(filing_no,listing_date,purpose,entry_date,deal_cd,connected,priority_serial,bench_nature,bench_no,
		 court_no,list_flag,listed,list_criteria,form_status) values('$filing_no','$listt_date','$purpose_id','$server_date','$sessionUserType','N','999','$b_nature','$bench_no','$c_no','1','1','N','CU')";
$db->query($insert) or die("not inserted");
}
		
	/*	
	  $sql2="select * from $schemas.case_detail where filing_no='$filing_no' ";
foreach($db->query($sql2) as $row2)
	{	
	 $case_type =$row2['case_type'];
	 $case_no =$row2['case_no'];
	 $case_year =$row2['case_year'];
	
	}
	  $sql2="select * from $schemas.case_allocation_temp where filing_no='$filing_no' and listed='0' and entry_date <='$server_date' ";
	foreach($db->query($sql2) as $row2)
	{
	 $last_bench_no =$row2['bench_no'];	
	  $last_bench_nature =$row2['bench_nature'];	
	 $last_court_no =$row2['court_no'];		 
	 $case_type =$row2['case_type'];
	 $purpose =$row2['purpose'];
	 $li_date =$row2['listing_date'];
	 
	}	
	if($li_date=='')
	{
		$li_date='1111-11-11';
	}
	if($last_bench_no=='' || $last_court_no=='')
	{
	$last_bench_no='0';
	$last_court_no='0';
	}
	if($last_bench_nature=='' || $purpose=='')
	{	
   $last_bench_nature='0';
   $purpose='0';
	}
	*/
	  // $st2="update $schemas.case_allocation_temp set listing_date='$listt_date',purpose='$purpose_id',entry_date='$server_date',deal_cd='$userid',priority_serial='$priority_serial1',bench_nature='$b_nature',court_no='$c_no',bench_no='$bench_no',list_flag='1',listed='1' where filing_no='$filing_no' ";
	

// $sth1=$dbh->prepare($st2);
	 //$sth1->execute();
	 
	 
	
	
}
$msg= "SUCCESSFULLY CASE LISTED";
$msghash1 =$msg."@".$listt_date;
$msghash=base64_encode($msghash1);
header("Location:./court_unalloc_fresh.php?msghash=$msghash");

		
?>
