<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");
include("../db_inc1.php");

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

// At the top of the page we check to see whether the user is logged in or not
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
$curDay=htmlspecialchars(date("d"));
$curMonth=htmlspecialchars(date("m"));
$curYear=htmlspecialchars(date("Y"));
$cur_date = "$curDay/$curMonth/$curYear";
$curdate="$curYear-$curMonth-$curDay";
$wday = mktime(0,0,0,date("$curMonth"),date("d")-5,date("Y"));
$wday1= date("Y-m-d", $wday);
$remarks= $_REQUEST['remarks'];
$remarks = pg_escape_string($remarks);
$checkbox=$_REQUEST['checkbox'];
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); 

$schemas=htmlspecialchars($_SESSION['schema_name']);
$sessionUserType=htmlspecialchars($_SESSION['id']);
$filing_no=$_REQUEST['filing_no'];
$disposal_date=$_REQUEST['disposal_date'];
$disposal_nature=$_REQUEST['disposal_nature'];
$bench_no=$_REQUEST['bench_no'];

if($disposal_date !='')
{
	list($day,$month,$year)=explode('/',$disposal_date);
	 $disposal_date1=$year.'-'.$month.'-'.$day;
}

$sth1 = $db->prepare("select max(listing_date) as listing_date from $schemas.case_allocation_temp where filing_no=?");
	$sth1->bindParam(1, $filing_no, PDO::PARAM_STR);
	$sth1->execute();
while ($row = $sth1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
 $listing_date=htmlspecialchars($row['listing_date']);
}
/*
if($listing_date!='' and $disposal_date1!='')
{
if($disposal_date1 < $listing_date)
{
	?>
	
	
	<?php 
	
$msg= "DISPOSAL DATE NOT BE LESS THEN LISTING DATE";
header("Location:direct_disposed.php?msg=$msg");
die();
}
}
*/
$sth2 = $db->prepare("select * from $schemas.bench where bench_no=? and from_list_date=?");
	$sth2->bindParam(1, $bench_no, PDO::PARAM_STR);
	$sth2->bindParam(2, $disposal_date1, PDO::PARAM_STR);
	$sth2->execute();
while ($row2 = $sth2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
 $court_no=htmlspecialchars($row2['court_no']);
 $bench_nature=htmlspecialchars($row2['bench_nature']);
 //$bench_nature=htmlspecialchars($row2['bench_nature']);

}



$judge_code_insert='';
	$st= "select judge_code from $schemas.bench_judge where bench_no=? and from_list_date=? order by judge_code asc";
	$st=$db->prepare($st);
	$st->bindParam(1, $bench_no, PDO::PARAM_STR);
	$st->bindParam(2, $disposal_date1, PDO::PARAM_STR);
	$st->execute();
	while ($row_jud = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		if($judge_code_insert=='')
		{
		$judge_code_insert =$row_jud['judge_code'];
		}
		else
		{
		$judge_code_insert=$judge_code_insert.",".$row_jud['judge_code'];
		}
	}
	
	
	$sth3 = $db->prepare("select * from $schemas.case_detail where filing_no=?");
	$sth3->bindParam(1, $filing_no, PDO::PARAM_STR);
	$sth3->execute();
while ($row3 = $sth3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
 $case_type=htmlspecialchars($row3['case_type']);
  $case_no=htmlspecialchars($row3['case_no']);
   $case_year=htmlspecialchars($row3['case_year']);
   $location_code=htmlspecialchars($row3['location_code']);
	
}
	
	
	$remarks="DIRECT";
	
$st="insert into $schemas.case_disposal
	(filing_no,disposal_date,disposal_nature,court_no,bench_nature,bench_no,case_type,case_no,case_year,judge_code,remarks,entry_date,user_id)
	VALUES
	(?,?,?,?,?,?,?,?,?,?,?,?,?)";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $disposal_date1, PDO::PARAM_STR);
$st->bindParam(3, $disposal_nature, PDO::PARAM_STR);
$st->bindParam(4, $court_no, PDO::PARAM_INT);
$st->bindParam(5, $bench_nature, PDO::PARAM_INT);
$st->bindParam(6, $bench_no, PDO::PARAM_INT);

$st->bindParam(7, $case_type, PDO::PARAM_STR);
$st->bindParam(8, $case_no, PDO::PARAM_STR);
$st->bindParam(9, $case_year, PDO::PARAM_STR);
$st->bindParam(10, $judge_code_insert, PDO::PARAM_INT);

$st->bindParam(11, $remarks, PDO::PARAM_STR);
$st->bindParam(12, $server_date, PDO::PARAM_STR);
$st->bindParam(13, $sessionUserType, PDO::PARAM_INT);
$st->execute();


$statusx='D';
$std = "update $schemas.case_detail set status =? where filing_no=?";
	$std=$db->prepare($std);
	$std->bindParam(1, $statusx, PDO::PARAM_STR);
	$std->bindParam(2, $filing_no, PDO::PARAM_STR);
	$std->execute();

 $msg="Case is disposed successfully!!!";


	//$msghash1=$msg.'-'.$case_type.'-'.$case_year;
	//$msghash=base64_encode($msghash1);
	
header("Location:direct_disposed.php?msg=$msg");

}
