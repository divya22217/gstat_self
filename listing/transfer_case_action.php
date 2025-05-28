<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  
include("../db_inc1.php");
//if we remove db_inc2 then it stops working
include("../db_inc2.php");
$bench_no='';

$schemas=htmlspecialchars($_SESSION['schema_name']);
date_default_timezone_set("Asia/Kolkata");

if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
	echo "Access Problem.....";
	header("Location: ../login.php");
	die();
}

if($_SESSION['user'] !='' && $_SESSION['location'] !='' && (isset($_POST['listing_date'])))
{
	try{
	$db->beginTransaction(); 
	$data = $_POST;
	$listing_date = $data['lis_date'];
	$bench_no = $data['bench_no'];
	$transfer_bench = $data['bench_no_transfer'];
	$cases_to_transfer = $data['selected_cases'];
	$user_id=htmlspecialchars($_SESSION['id']);
	$username = $_SESSION['user_actual_name'];
	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";
	if($bench_no == $transfer_bench){
		$response = array( 
			'status' => 0, 
			'message' => 'Can not transfer to the same bench' 
			);
		echo json_encode($response); die;
	}
	/* $query="select max(listing_date) from $schemas.case_allocation_temp where listing_date < ?";
	$last_listing_date= $db->prepare($query);
	$last_listing_date->bindParam(1, $cur_date, PDO::PARAM_STR);
	$last_listing_date->execute();
	$last_listing_date = $last_listing_date->fetchColumn();
	if($last_listing_date != $listing_date){
		$response = array( 
			'status' => 0, 
			'message' => 'Can not transfer cases of old bench' 
			);
		echo json_encode($response); die;
	} */
	
	$query="select court_no,list_flag,bench_nature from $schemas.bench where from_list_date = ? and bench_no = ?";
	$bench_data= $db->prepare($query);
	$bench_data->bindParam(1, $listing_date, PDO::PARAM_STR);
	$bench_data->bindParam(2, $bench_no, PDO::PARAM_STR);
	$bench_data->execute();
	$bench_data = $bench_data->fetchAll();
	if(!empty($bench_data) && sizeof($bench_data) == 1){
	$bench_data = array_shift($bench_data);
	$previous_court_no = $bench_data['court_no'];
	$previous_list_flag = $bench_data['list_flag'];
	$previous_bench_nature = $bench_data['bench_nature'];
	$query="select court_no,list_flag,bench_nature from $schemas.bench where from_list_date = ? and bench_no = ?";
	$transfer_bench_data= $db->prepare($query);
	$transfer_bench_data->bindParam(1, $listing_date, PDO::PARAM_STR);
	$transfer_bench_data->bindParam(2, $transfer_bench, PDO::PARAM_STR);
	$transfer_bench_data->execute();
	$transfer_bench_data = $transfer_bench_data->fetchAll();
	if(!empty($transfer_bench_data) && sizeof($transfer_bench_data) == 1){
		$transfer_bench_data = array_shift($transfer_bench_data);
		$new_court_no = $transfer_bench_data['court_no'];
		$new_list_flag = $transfer_bench_data['list_flag'];
		$new_bench_nature = $transfer_bench_data['bench_nature'];
		foreach($cases_to_transfer as $key=>$filing_no){
			$query="insert into $schemas.transfer_cases (listing_date,filing_no,previous_court_no,new_court_no,previous_bench_no,new_bench_no,previous_list_flag,new_list_flag,user_id,username,previous_bench_nature,new_bench_nature) 
				values (?,?,?,?,?,?,?,?,?,?,?,?)";
			$insert= $db->prepare($query);
			$insert->bindParam(1, $listing_date, PDO::PARAM_STR);
			$insert->bindParam(2, $filing_no, PDO::PARAM_STR);
			$insert->bindParam(3, $previous_court_no, PDO::PARAM_STR);
			$insert->bindParam(4, $new_court_no, PDO::PARAM_STR);
			$insert->bindParam(5, $bench_no, PDO::PARAM_STR);
			$insert->bindParam(6, $transfer_bench, PDO::PARAM_STR);
			$insert->bindParam(7, $previous_list_flag, PDO::PARAM_STR);
			$insert->bindParam(8, $new_list_flag, PDO::PARAM_STR);
			$insert->bindParam(9, $user_id, PDO::PARAM_STR);
			$insert->bindParam(10, $username, PDO::PARAM_STR);
			$insert->bindParam(11, $previous_bench_nature, PDO::PARAM_STR);
			$insert->bindParam(12, $new_bench_nature, PDO::PARAM_STR);
			$res = $insert->execute();
			if($res){
				$query="update $schemas.case_allocation_temp set court_no = ? , bench_no = ?, bench_nature = ?, list_flag = ? where listing_date = ? and bench_no = ? and filing_no = ?";
				$update_alloc_temp= $db->prepare($query);
				$update_alloc_temp->bindParam(1, $new_court_no, PDO::PARAM_STR);
				$update_alloc_temp->bindParam(2, $transfer_bench, PDO::PARAM_STR);
				$update_alloc_temp->bindParam(3, $new_bench_nature, PDO::PARAM_STR);
				$update_alloc_temp->bindParam(4, $new_list_flag, PDO::PARAM_STR);
				$update_alloc_temp->bindParam(5, $listing_date, PDO::PARAM_STR);
				$update_alloc_temp->bindParam(6, $bench_no, PDO::PARAM_STR);
				$update_alloc_temp->bindParam(7, $filing_no, PDO::PARAM_STR);
				$update_alloc_temp->execute();
				
				$query="update $schemas.case_allocation set court_no = ? , bench_no = ?, bench_nature = ?, list_flag = ? where listing_date = ? and bench_no = ? and filing_no = ?";
				$update_alloc= $db->prepare($query);
				$update_alloc->bindParam(1, $new_court_no, PDO::PARAM_STR);
				$update_alloc->bindParam(2, $transfer_bench, PDO::PARAM_STR);
				$update_alloc->bindParam(3, $new_bench_nature, PDO::PARAM_STR);
				$update_alloc->bindParam(4, $new_list_flag, PDO::PARAM_STR);
				$update_alloc->bindParam(5, $listing_date, PDO::PARAM_STR);
				$update_alloc->bindParam(6, $bench_no, PDO::PARAM_STR);
				$update_alloc->bindParam(7, $filing_no, PDO::PARAM_STR);
				$update_alloc->execute();
				
			}
		}
	}else{
		$response = array( 
			'status' => 0, 
			'message' => 'Some went wrong with bench to transfer' 
			);
		echo json_encode($response); die;
	}
	}else{
		$response = array( 
			'status' => 0, 
			'message' => 'Some went with previous bench' 
			);
		echo json_encode($response); die;
	}
	$db->commit();
	$response = array( 
		'status' => 1, 
		'message' => 'Cases transferred' 
		);
	echo json_encode($response); die;
	}catch(Exception $e){
		$db->rollBack();
		$response = array( 
		'status' => 0, 
		'message' => 'some error occurred.' 
		);
	}
}

?>
