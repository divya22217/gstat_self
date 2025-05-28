<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");


$filing_no = $_POST['filing_no'];
$list_date = $_POST['list_date'];
$next_list_date = $_POST['next_list_date'];
$schemas = 'delhi';

$update ="update $schemas.case_allocation_temp set next_list_date ='$next_list_date' where filing_no='$filing_no' and listing_date = '$list_date' ";

	$db->query($update) or die("not updated");
?>