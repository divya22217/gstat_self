<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//session_start();
ob_start();
include("../db_inc1.php");
$schemas=htmlspecialchars($_SESSION['schema_name']);

$next_list_date=$_REQUEST['next_list_date'];
$next_list_date1=$_REQUEST['next_list_date1'];

$bench_no=$_REQUEST['bench_no'];
$bench_code_new=$_REQUEST['bench_no_new'];

$checkbox= $_REQUEST['checkbox'];
$purpose_code= $_REQUEST['purpose_code'];


list($day,$month,$year)=explode('/',$next_list_date);
$date_new=$year.'-'.$month.'-'.$day;
list($dayn,$monthn,$yearn)=explode('/',$next_list_date1);
$date_newn=$yearn.'-'.$monthn.'-'.$dayn;

$st11=$dbh->prepare("select court_no,bench_nature from $schemas.bench where bench_no=? and from_list_date=?");
$st11->execute(array($bench_code_new,$date_newn));
$new_bech_data = $st11->fetch();

$court_no_transfer = $new_bech_data['court_no'];
$new_bench_nature = $new_bech_data['bench_nature'];

foreach($checkbox as $key => $filing_no)
{

$dbh->query("insert into $schemas.case_allocation_his_temp(select * from $schemas.case_allocation_temp where filing_no='$filing_no')");

$st3="update  $schemas.case_allocation_temp set bench_no='$bench_code_new',court_no='$court_no_transfer',listing_date ='$date_newn',bench_nature='$new_bench_nature',purpose='$purpose_code[$key]',listed='1' where bench_no='$bench_no'and listing_date ='$date_new' and filing_no='$filing_no'";
//print_r($st3);die('ll');
$dbh->query($st3);
}	
$msg= "SUCCESSFULLY TRANSFERED";
header("Location:transfer_bench.php?msg=Successfully Transfered");
 
?>
