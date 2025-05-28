<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include("../db_inc1.php");
session_start();
$judge =htmlentities($_REQUEST['judge']);

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
	header("Location: ../login.php");
	die();
}
setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);
$benchlocation =htmlentities($_REQUEST['bench_location']);
if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";
	$link_scrutiny_idaccess='1';
	
	print_r($_REQUEST);
	$bench_no = htmlentities(htmlspecialchars($_REQUEST['bench_no']));
	$listing_date = htmlentities(htmlspecialchars($_REQUEST['next_list_date']));
	list($ly,$lm,$ld) =explode("/",$listing_date);
	$listing_date = $ld.'-'.$lm.'-'.$ly;		
		
		         $bench_details_sql = "select court_no,bench_nature from $schemas.bench where from_list_date=? and bench_no=? ";
	             $bench_details_sql_res = $db->prepare($bench_details_sql);
	             $bench_details_sql_res->bindParam(1, $listing_date, PDO::PARAM_STR);
				 $bench_details_sql_res->bindParam(2, $bench_no, PDO::PARAM_STR);
	             $bench_details_sql_res->execute();
	             while ($row1 =$bench_details_sql_res->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
            {
				$court_no = htmlspecialchars($row1['court_no']);
				$bench_nature = htmlspecialchars($row1['bench_nature']);
			}
			
			
			
			     echo $distinct_purpose_sql = "select distinct(purpose) from $schemas.case_allocation_temp where listing_date=? and bench_no=?";
	             $distinct_purpose_sql_res = $db->prepare($distinct_purpose_sql);
	             $distinct_purpose_sql_res->bindParam(1, $listing_date, PDO::PARAM_STR);
				 $distinct_purpose_sql_res->bindParam(2, $bench_no, PDO::PARAM_STR);
	             $distinct_purpose_sql_res->execute();
	             while ($row2 =$distinct_purpose_sql_res->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
            {
				 $purpose = htmlspecialchars($row2['purpose']);
				 $priority = '1';
				
				
				 $bench_details_sql2 = "select * from $schemas.bench_purpose_priority where from_date=? and bench_no=? and purpose= '$purpose'";
	             $bench_details_sql2_res = $db->prepare($bench_details_sql2);
	             $bench_details_sql2_res->bindParam(1, $listing_date, PDO::PARAM_STR);
				 $bench_details_sql2_res->bindParam(2, $bench_no, PDO::PARAM_STR);
	             $bench_details_sql2_res->execute();
				 if($bench_details_sql2_res->rowCount()==0){
				 
				 
				 
	             /*while ($row2 =$bench_details_sql2_res->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
            {
				$purpose2 = htmlspecialchars($row2['purpose']);
				if($purpose2 == $purpose){
					break;
				}else{*/
									$purpose_insert_sql =$db->prepare("insert into $schemas.bench_purpose_priority(from_date,
to_date,court_no,purpose,priority,deal_cd,entry_date,bench_nature,bench_no) values
(?,?,?,?,?,?,?,?,?)");

$purpose_insert_sql->bindParam(1, $listing_date, PDO::PARAM_STR);
$purpose_insert_sql->bindParam(2, $listing_date, PDO::PARAM_STR);
$purpose_insert_sql->bindParam(3, $court_no, PDO::PARAM_STR);
$purpose_insert_sql->bindParam(4, $purpose, PDO::PARAM_STR);
$purpose_insert_sql->bindParam(5, $priority, PDO::PARAM_STR);
$purpose_insert_sql->bindParam(6, $sessionUserType, PDO::PARAM_STR);
$purpose_insert_sql->bindParam(7, $entry_date, PDO::PARAM_STR);
$purpose_insert_sql->bindParam(8, $bench_nature, PDO::PARAM_STR);
$purpose_insert_sql->bindParam(9, $bench_no, PDO::PARAM_STR);

$purpose_insert_sql->execute();
				}
			//}
			
			}
			$message='Purpose Priority Successfully Added';
		header("Location:./add_purpose_priority.php?msg=$message");
 }