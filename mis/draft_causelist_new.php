<?php
//demo values
//$list_date = '13/03/2019';
//$court_no = '1';
//$bench_nature = '2'; //division bench

//list($day,$month,$year)=explode('/',$list_date);
//$list_date_db=$year.'-'.$month.'-'.$day;


//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(E_ALL);
include '../db_inc2.php';
include("../db_inc1.php");
require_once('../SrcCauselist/Causelist.php');
if(isset($_SESSION['schema_name'])){
$schemas=htmlspecialchars($_SESSION['schema_name']);
}else{
	$schemas='delhi';
}
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); 
if(isset($_SESSION['schema_name'])){
$sessionUserType=htmlspecialchars($_SESSION['id']);
}
$schema_id = $_SESSION['schema_idccc'];
$list_date =$_REQUEST['next_list_date'];
list($day,$month,$year)=explode('/',$list_date);
$list_date_db=$year.'-'.$month.'-'.$day;
$court_no=$court_nono=$_REQUEST['court_no'];
$ctype =htmlspecialchars($_REQUEST['ctype']);
//$bench_nature=$_REQUEST["list_before"];  //bench nature
$title_causelist = 'NATIONAL COMPANY LAW TRIBUNAL';
$sql222="select * from mater_location_city where city_id='$schema_id'";
foreach($db->query($sql222) as $row)
	{	
          $city_name =$row['city_name'];
  }



$city_name=strtoupper($city_name);
$region_causelist = $city_name;
$type_causelist = 'D';
$list_flag = 1;    //cause list type(supp/ord)

if($ctype == 'draft'){
$table = 'case_allocation_temp';
$title = 'Draft Cause List';
}elseif($ctype == 'final'){
  $table = 'case_allocation';
  $title = 'Final Cause List';
}elseif($ctype == 'proceeding'){
  $table = 'case_allocation';
  $title = 'Proceedings';
}elseif($ctype == 'order'){
  $table = 'case_allocation';
  $title = 'Order Creation';
}

//get bench no
//$sql="select bench_no from $schemas.bench where court_no='$court_no' and from_list_date='$list_date_db' order by bench_no asc limit 1";
$sql="select b.bench_no from $schemas.bench b, $schemas.case_allocation_temp t where b.court_no='$court_no' and b.from_list_date='$list_date_db' and 
t.listing_date='$list_date_db' and t.court_no='$court_no' and b.bench_no=t.bench_no order by b.bench_no asc limit 1";
foreach($db->query($sql) as $row)
	{	
          $bench_noc =$row['bench_no'];
  }

$causelist = new Causelist();
$causelist->setCauselistTitle($title_causelist);

$causelist->setCauselistRegion($region_causelist);

$causelist->setCauselistType($type_causelist);

$courtnoc = $causelist->setCourtno($court_no);
$courtnoc = $causelist->getCourtno();

$causelist->setTable($table);
$tablename = $causelist->getTable();

$courtdatec = $causelist->setCourtdate($list_date);

$snoc = $causelist->setSno(1);

$corumc = $causelist->corum;

$pjudgename = $causelist->getpJudges($schemas, $db, $list_date_db, $bench_noc, $courtnoc, $table);
//print_r($pjudgename);
$presiding = $pjudgename[0]['presiding'];

$judgesname = $causelist->getallJudges($schemas, $db, $list_date_db, $bench_noc, $courtnoc, $presiding, $table);
//print_r($judgesname);
?>