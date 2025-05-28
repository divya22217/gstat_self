<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
ob_start();
include("../db_inc1.php");
$schemas=htmlspecialchars($_SESSION['schema_name']);
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); //Returns IST 
$next_list_date=$_REQUEST['next_list_date'];
list($d,$m,$Y) =explode('/',$next_list_date);
 $list_date =$Y.'-'.$m.'-'.$d;
 $listt_date=$_REQUEST['lis_date'];
$userid=$_SESSION['id'];
 $bench_no=$_REQUEST['bench_no'];

 $checkbox=$_REQUEST['checkbox'];

$purpose_id=$_REQUEST['purpose_id'];

 $sql21="select * from $schemas.bench where from_list_date='$listt_date' and bench_no='$bench_no' ";
foreach($db->query($sql21) as $row21)
	{	
	  $b_nature =$row21['bench_nature'];
	 $c_no =$row21['court_no'];
	 
	
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
	
	
	
	
	
	
	
 $st2="update $schemas.case_allocation_temp set listing_date='$listt_date',purpose='$purpose_id',entry_date='$server_date',deal_cd='$userid',priority_serial='999',bench_nature='$b_nature',court_no='$c_no',bench_no='$bench_no',list_flag='1',listed='1',last_listing_date='$li_date',last_bench_no='$last_bench_no',last_court_no='$last_court_no',last_bench_nature='$last_bench_nature',last_purpose='$purpose',list_criteria='N',form_status='R' where filing_no='$filing_no' and (next_list_date='$listt_date' OR next_list_date='1111-11-11') and listing_date='$li_date' ";
	

 $sth1=$dbh->prepare($st2);
	 $sth1->execute();
	
}


		header("Location:./remain_listing.php?");
?>
