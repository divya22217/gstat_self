<?php

include("../db_inc1.php");
$schemas=htmlspecialchars($_SESSION['schema_name']);
date_default_timezone_set("Asia/Kolkata");
$filings =$_REQUEST['fil'];
$pri =$_REQUEST['pri'];

try{
	$db->beginTransaction(); 
	for($i=0;$i<count($filings);$i++)
	{
	$priority =$pri[$i];
	if($priority == ''){
		$priority = 999;
		}
	$filno1 =$filings[$i];
	$query="update $schemas.case_allocation_temp set priority_serial =? where filing_no =?";
	$update_alloc= $db->prepare($query);
	$update_alloc->bindParam(1, $priority, PDO::PARAM_STR);
	$update_alloc->bindParam(2, $filno1, PDO::PARAM_STR);
	$update_alloc->execute();
	}
	$db->commit();
	$response = array( 
		'status' => 1, 
		'message' => 'Priority updated' 
		);
	echo json_encode($response); die;
	}catch(Exception $e){
		$db->rollBack();
		$response = array( 
		'status' => 0, 
		'message' => 'some error occurred.' 
		);
		echo json_encode($response); die;
	}
die;

?>
