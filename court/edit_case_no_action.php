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
	
	
	 $filing_no=$_REQUEST['filing_no'];

	
	
	$schemas=htmlspecialchars($_SESSION['schema_name']);
	 $new_bench_type=$_REQUEST['new_bench_type'];
	$new_case_type=$_REQUEST['new_case_type'];
	$new_case_no=$_REQUEST['new_case_no'];
	$new_case_year=$_REQUEST['new_case_year'];
	
	
		
$sql2=$db->prepare("select * from  $schemas.case_detail where filing_no=?  ");

$sql2->bindParam(1, $filing_no, PDO::PARAM_STR);


$sql2->execute();
	
while ($row2 = $sql2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $old_case_type =$row2['case_type'];
   $old_case_no =$row2['case_no'];
   $old_case_year =$row2['case_year'];
   $old_bench_type =$row2['location_code'];
  	
}		
		
  $localIP = getHostByName(getHostName());
		  $timestamp = date("Y-m-d H:i:s");


$form_status='C';
  	
$st=$db->prepare("select count(*) as ct from $schemas.case_modify_track where filing_no =? ");
	$st->bindParam(1, $filing_no, PDO::PARAM_STR);
		$st->execute();
      while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
      {
		
        
     
           $cc_filing_no = htmlspecialchars($row['ct']);
		  
		  
	  }
	
	  if($cc_filing_no=='' || $cc_filing_no==0)
	  {
		  $cc_filing_no=1;
	  }
	  else
	  {
		  $cc_filing_no=$cc_filing_no+1;
	  }


$st = $db->prepare("insert into $schemas.case_modify_track(filing_no,user_id,ip_address,modified_at,
form_status,old_case_type,old_case_no,old_case_year,old_location_code,count_case)
values(?,?,?,?,?,?,?,?,?,?)");
	$display='1';
	$status='C';

$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $sessionUserType, PDO::PARAM_STR);
$st->bindParam(3, $localIP, PDO::PARAM_STR);
$st->bindParam(4, $timestamp, PDO::PARAM_STR);
$st->bindParam(5, $form_status, PDO::PARAM_STR);
$st->bindParam(6, $old_case_type, PDO::PARAM_STR);
$st->bindParam(7, $old_case_no, PDO::PARAM_STR);
$st->bindParam(8, $old_case_year, PDO::PARAM_STR);
$st->bindParam(9, $old_bench_type, PDO::PARAM_STR);
$st->bindParam(10, $cc_filing_no, PDO::PARAM_STR);

$st->execute();


$dis_pq=$db->prepare("update $schemas.case_detail set case_no=?,case_type=?,case_year=?,location_code=? where
			 filing_no=?" );

$dis_pq->bindParam(1, $new_case_no, PDO::PARAM_STR);
$dis_pq->bindParam(2, $new_case_type, PDO::PARAM_STR);
$dis_pq->bindParam(3, $new_case_year, PDO::PARAM_STR);
$dis_pq->bindParam(4, $new_bench_type, PDO::PARAM_STR);
$dis_pq->bindParam(5, $filing_no, PDO::PARAM_STR);
$dis_pq->execute();



 }
 
 
 $msg = 'Case No. Modified Successfully .....';

 header("Location:./edit_case_no.php?msg=$msg");
 
 die();



?>
