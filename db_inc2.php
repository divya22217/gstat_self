<?php
//require_once("url_validation1.php");
require_once("protractsql.php");
$username1 = "postgres";
$password1 = "redhat@123";
$host1 = "10.193.85.11";
//$host1 = "10.247.205.253"; 
//$host1="localhost";

$dbname1 = "gstat";
try{
$dbonline = new PDO("pgsql:host={$host1};dbname={$dbname1}", $username1, $password1 );
$dbonline->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbonline->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
catch(PDOException $ex){
	die('not connected');


}

//require_once "url_validation.php";

?>
