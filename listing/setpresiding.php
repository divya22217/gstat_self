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

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $judge_code = $_POST['action'];
	$judgenamesql = $db->prepare("select judge_name, judge_desg_code from $schemas.master_judge where judge_code='$judge_code' and display='TRUE'");
    //echo $judgenamesql;
	$judgenamesql->execute();
	$judgenamesql_result = $judgenamesql->fetch(PDO::FETCH_OBJ);
    $judge_name = $judgenamesql_result->judge_name;
	//echo $judge_name;
	$judge_desg_code = $judgenamesql_result->judge_desg_code;
	
	
	$judgedesgsql = $db->prepare("select desg_name from $schemas.master_desg where desg_code='$judge_desg_code'");
    //echo $judgenamesql;
	$judgedesgsql->execute();
	$judgedesgsql_result = $judgedesgsql->fetch(PDO::FETCH_OBJ);
    $judge_desg = $judgedesgsql_result->desg_name;
	//echo $judge_desg;
	
	echo $judge_name.' '.$judge_desg;
    }
}
?>