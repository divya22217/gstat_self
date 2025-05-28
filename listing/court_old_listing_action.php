<?php
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

$sessionUserType=htmlspecialchars($_SESSION['id']);
 $bench_no=$_REQUEST['bench_no'];
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

  $checkbox=$_REQUEST['checkbox'];
    $pr_ord=$_REQUEST['pr_ord'];
  //list($filing_no,$max_list_date)=explode("/",$checkbox[0]);
  //print_r($max_list_date);die();

 $purpose_code=$_REQUEST['purpose_id'];


  $sql2="select *  from $schemas.bench where from_list_date='$listt_date' and bench_no='$bench_no' ";

foreach($db->query($sql2) as $row2)
	{	
	   $court_no =$row2['court_no'];
	  $bench_nature =$row2['bench_nature'];
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
	  //$purpose1=$purpose_code[$i];
	  $filing_no=$checkbox[$i];
	  list($filing_no,$max_list_date) = explode("/",$filing_no);
	$purpose1=$purpose_code[$filing_no];
	
	try{
	 $sql_pr1="insert into $schemas.case_allocation_his_temp select * from $schemas.case_allocation_temp where next_list_date='$listt_date' and filing_no='$filing_no'";
   
    $sth1=$dbh->prepare($sql_pr1);
	 $sth1->execute();
	
	  $st3="update  $schemas.case_allocation_temp set bench_no='$bench_no',court_no='$court_no',listed='1',listing_date ='$listt_date',bench_nature='$bench_nature',purpose='$purpose1',pri_ord='$pr_ord',list_criteria='N',form_status='CO' where  filing_no='$filing_no' and next_list_date='$listt_date' and listing_date='$max_list_date'";
$dbh->query($st3);
		}
	catch(\Exception $e){
		//die('k');
		echo "Caught", $e->getMessage(),"\n";
		//die();
	}
	
 
	
}
$msg= "SUCCESSFULLY CASE LISTED";
$msghash1 =$msg."@".$listt_date;
$msghash=base64_encode($msghash1);
header("Location:./court_old_listing.php?msghash=$msghash");

		
?>
