<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

date_default_timezone_set("Asia/Kolkata");

$server_date= date('d-m-Y'); //Returns IST 

if($server_date !='')
{
	list($day,$month,$year)=explode('-',$server_date);
	 $reg_year_server=$year;
	
}

include("../db_inc1.php");
session_start();

$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];

if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
echo "Access Problem....."; 
header("Location: ./login.php");
die();
}


setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);


$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key=$_SESSION['csrf'];

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	//die("#2E2E2Eirecting to login.php");
}

?>
<html>

<body>
<table border='1'>

<tr>
<td colspan="4">
<b><U>
Cases Successfully alloted to different benches
</u>
</b>
</td>
</tr>


<tr>
<td>
Serial No.
</td>
<td>
Diary No
</td>
<td>
Case Type
</td>
<td>
Case No
</td>
<td>
Case Year
</td>
<td>
Party Name
</td>
<td>
Court
</td>
<td>
Listing Date
</td>

</tr>




<?php
$serial_no =1;	
	
	
// This code not use next time .......	Schema session create Hear....
	
	
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	$location_access=$_SESSION['location'];

$curYear = htmlspecialchars(date("Y"));
$curMonth = htmlspecialchars(date("m"));
$curDay = htmlspecialchars(date("d"));
$cur_date = "$curYear-$curMonth-$curDay";
$cur_date1 ="$curDay/$curMonth/$curYear";
$schemas=htmlspecialchars($_SESSION['schema_name']);
$entry_date = htmlspecialchars(date("F j, Y g:i a"));
$link_scrutiny_idaccess='1';

//$filing_no_send=$_REQUEST['filing_no'];
		 $notification_date=$_REQUEST['notification_date'];

		$level_level=2;

$st="truncate $schemas.allocation_bench_wise";
$db->query($st);
$sql="select bench_no,limit_case,bench_nature,court_no,id,location_code from $schemas.bench where from_list_date='$notification_date'";
foreach($db->query($sql) as $row)
	{	
		$location_code =$row['location_code'];
	$bench_no =$row['bench_no'];
        $case_limit =$row['limit_case'];
	$bench_nature =$row['bench_nature'];
	$court_no       =$row['court_no'];
	$id =$row['id'];
    $sql1=$db->prepare("select count(*) as count from $schemas.case_allocation_temp where listing_date = '$notification_date' and bench_no='$bench_no'");
    $sql1->execute();
    $counter = $sql1->fetchColumn();
  $case_limit =$case_limit-$counter;
    $sql2="insert into $schemas.allocation_bench_wise(listing_date,bench_no,counter,case_limit,bench_nature,court_no,id,location_code) values('$notification_date','$bench_no','0','$case_limit','$bench_nature','$court_no','$id','$location_code')";
    $db->query($sql2) or die("not inserted");
	}
    
	$level_level=2; 
$sql="select filing_no from $schemas.case_detail where level_level= '2' ";

	$scrutinysql=$db->prepare($sql);
   // $scrutinysql->bindParam(1, $level_level, PDO::PARAM_STR);
     $scrutinysql->execute();
     while ($row = $scrutinysql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
          {     
          echo $fresh_filing_no = htmlspecialchars($row['filing_no']);
  $sql111=$db->prepare("select  min(counter) as counter  from $schemas.allocation_bench_wise where listing_date='$notification_date'");
       $sql111->execute();
      echo $counter = $sql111->fetchColumn();
      
	   
   	   $sql111=$db->prepare("select  min(id) as counterid  from $schemas.allocation_bench_wise where counter='$counter' and listing_date='$notification_date' and case_limit>0");
       $sql111->execute();
       $counterid = $sql111->fetchColumn();

      $bench_detail="select bench_nature,bench_no,court_no,id ,counter,case_limit  from $schemas.allocation_bench_wise where id='$counterid' and listing_date='$notification_date' ";
	  $bench_detail=$db->prepare($bench_detail);	
       $bench_detail->execute();
         while ($row1 = $bench_detail->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
			     {
                                     $bench_no =$row1['bench_no'];
				      $bench_nature =$row1['bench_nature'];
					 $court_no =$row1['court_no'];
					 $id            =$row1['id'];
					    $counter =$row1['counter'];
					    $case_limit =$row1['case_limit'];


if($counter<$case_limit)
{
      $counter=$counter +1;
echo "hello";

		 echo $update ="update $schemas.allocation_bench_wise set counter ='$counter'  where id='$id'";
     $db->query($update) or die("not updated");
	 /*$sqlw="select case_type,pet_name,res_name from $schemas.case_detail where filing_no='$fresh_filing_no'";
foreach($db->query($sqlw) as $roww)
	{	
	$case_type =$roww['case_type'];
 	$pet_name =$roww['pet_name'];
	$res_name =$roww['res_name'];
	$party_name=$pet_name. "VS" .$res_name;
	}*/





}
}
}
