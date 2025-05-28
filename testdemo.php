<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");


date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
session_start();

$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];

$st1x_scrutiny = $db->prepare("update $schemas.scrutiny set level_level = ? where filing_no=? ");
$st1x_scrutiny->bindParam(1, $level_level, PDO::PARAM_STR);
$st1x_scrutiny->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x_scrutiny->execute();




?>