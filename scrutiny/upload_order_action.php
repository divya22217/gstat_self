<?php 
session_start();
ob_start();
include("../db_inc1.php");
include("../db_inc2.php");
date_default_timezone_set("Asia/Kolkata");


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 

$schemas=htmlspecialchars($_SESSION['schema_name']);
$location_name = strtoupper($schemas);

if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
	echo "Access Problem.....";
	header("Location: ../login.php");
	die();
}
setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);
if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
  try{
	$db->beginTransaction();  // begin transaction
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";
	
	$server_date= date('Y-m-d'); //Returns IST 
	
	$case_no = (isset($_POST['case_no_order']) && $_POST['case_no_order'] != '')?$_POST['case_no_order']:0;
	$case_year = (isset($_POST['case_year_order']) && $_POST['case_year_order'] != '')?$_POST['case_year_order']:0;;
	$order_date = (isset($_POST['order_date']) && $_POST['order_date'] != '')?$_POST['order_date']:0;
	$upload_date = (isset($_POST['upload_date']) && $_POST['upload_date'] != '')?$_POST['upload_date']:0;
	$court_no = (isset($_POST['court_no']) && $_POST['court_no'] != '')?$_POST['court_no']:0;
	$bench_nature = $_POST['bench_nature'];
	$bench_no = (isset($_POST['bench_no']) && $_POST['bench_no'] != '')?$_POST['bench_no']:0;
	$order_type = $_POST['order_type'];
	
	$watermark = (isset($_POST['watermark']) && $_POST['watermark'] != '')?$_POST['watermark']:1;
	$case_type = (isset($_POST['case_type_order']) && $_POST['case_type_order'] != '')?$_POST['case_type_order']:0;
	if($case_type == 0 || $case_no == 0 || $case_year == 0 || $order_date == 0 || $court_no == 0){
		$msg = "Please fill case type,case no,case year,listing date,court no and upload order";
		$hash = base64_encode($msg);
		header("Location:upload_order.php?hash=$hash");
		die($msg);
		
	}
	list($day,$month,$year)=explode('/',$order_date);
	$listing_date=$year."-".$month."-".$day;
	list($day,$month,$year)=explode('/',$upload_date);
	//$order_upload_date=$year."-".$month."-".$day;
	$order_upload_date='9999-01-01';
	$order_type_title = 'daily';
	$uploadDir = '/NCLAT_Documents/CIS_Documents/casedoc/orders/'.$location_name.'/'.$listing_date.'/courts/'.$court_no.'/'.$order_type_title;
	$saveUploadDir = '/NCLAT_Documents/CIS_Documents/casedoc/orders/'.$location_name.'/'.$listing_date.'/courts/'.$court_no.'/'.$order_type_title;
	$uploadStatus = 1;
	// server side validation
		$query =$db->prepare("select filing_no,status from $schemas.case_detail where case_no = ? and case_year = ? and case_type = ?");
		$query->bindParam(1, $case_no, PDO::PARAM_STR);
		$query->bindParam(2, $case_year, PDO::PARAM_STR);
		$query->bindParam(3, $case_type, PDO::PARAM_STR);
		$query->execute();
		$case_detail = $query->fetchAll();
		if(empty($case_detail)){
			$uploadStatus = 0;
			$msg = "Case Not Found";
		}
		if(!empty($case_detail) && count($case_detail) > 1){
			$uploadStatus = 0;
			$msg = "Something went wrong";
		}else if(!empty($_FILES["upload_order"]["name"])){ 
		$filing_no = $case_detail[0]['filing_no'];
		$query =$db->prepare("select count(*) as count from $schemas.order_daily where filing_no = ? and order_date = ? and order_type = ?");
		$query->bindParam(1, $filing_no, PDO::PARAM_STR);
		$query->bindParam(2, $listing_date, PDO::PARAM_STR);
		$query->bindParam(3, $order_type, PDO::PARAM_STR);
		$query->execute();
		$res = $query->fetchColumn();
		if($res > 0){
			$uploadStatus = 0;
			$msg = "Order already uploaded";
			$hash = base64_encode($msg);
			header("Location:upload_order.php?hash=$hash");
			die($msg);
		}
		// File path config 
		$fileName = basename($_FILES["upload_order"]["name"]); 
		$uniquesavename=time().uniqid(rand()).'.pdf';
		$targetFilePath = $uploadDir .'/'. $uniquesavename; 
		$acttual_file = $uploadDir .'/'. $fileName; 
		$saveTargetFilePath = $saveUploadDir.'/'. $uniquesavename; 

		$fileType = pathinfo($acttual_file, PATHINFO_EXTENSION); 
		 
		// Allow certain file formats 
		$allowTypes = array('pdf'); 
		if(in_array($fileType, $allowTypes)){ 
			// Upload file to the server 
			if (!file_exists($uploadDir)) {
				mkdir($uploadDir, 0777, true);
			}
			if(move_uploaded_file($_FILES["upload_order"]["tmp_name"], $targetFilePath)){ 
				$uploadedFile = $fileName; 
			}else{ 
				$uploadStatus = 0; 
				$msg = 'Sorry, there was an error uploading your file.'; 
			} 
		}else{ 
			$uploadStatus = 0; 
			$msg = 'Sorry, only PDF files are allowed to upload.'; 
		} 
	}else{
		$uploadStatus = 0;
		$msg = 'Please upload order'; 
	}
	
	if($uploadStatus == 1){
		$flag = 'Y';
		$case_detail = array_shift($case_detail);
		$filing_no = $case_detail['filing_no'];
		
		if($bench_no != 0){
		$bench_judges="select string_agg(cast(judge_code as varchar),',') as judge_codes from $schemas.bench_judge where from_list_date = ? and bench_no = ?";
		$bench_judges = $db->prepare($bench_judges);
		$bench_judges->bindParam(1, $listing_date, PDO::PARAM_STR);
		$bench_judges->bindParam(2, $bench_no, PDO::PARAM_STR);
		$bench_judges->execute();
		$coram = $bench_judges->fetchColumn();
		}else{
		$coram = '';
		}
		
		$ins_order =$db->prepare("insert into $schemas.order_daily(filing_no,order_date,
		user_id,flag,bench_nature,court_no,entry_date,order_type,pdf_path,filename,order_upload_date,judge_codes)
		values(?,?,?,?,?,?,?,?,?,?,?,?)");
		
		$ins_order->bindParam(1, $filing_no, PDO::PARAM_STR);
		$ins_order->bindParam(2, $listing_date, PDO::PARAM_STR);
		$ins_order->bindParam(3, $sessionUserType, PDO::PARAM_STR);
		$ins_order->bindParam(4, $flag, PDO::PARAM_STR);
		$ins_order->bindParam(5, $bench_nature, PDO::PARAM_STR);
		$ins_order->bindParam(6, $court_no, PDO::PARAM_STR);
		$ins_order->bindParam(7, $server_date, PDO::PARAM_STR);
		//$ins_order->bindParam(8, $get_bench_no, PDO::PARAM_STR);
		$ins_order->bindParam(8, $order_type, PDO::PARAM_STR);
		$ins_order->bindParam(9, $saveTargetFilePath, PDO::PARAM_STR);
		$ins_order->bindParam(10, $fileName, PDO::PARAM_STR);
		$ins_order->bindParam(11, $order_upload_date, PDO::PARAM_STR);
		$ins_order->bindParam(12, $coram, PDO::PARAM_STR);
		$res = $ins_order->execute();
		
		if(!empty($_POST['child_cases'])){
			foreach($_POST['child_cases'] as $key=>$child_case_filing_no){
				$query =$db->prepare("select count(*) as count from $schemas.order_daily where filing_no = ? and order_date = ? and order_type = ?");
				$query->bindParam(1, $child_case_filing_no, PDO::PARAM_STR);
				$query->bindParam(2, $listing_date, PDO::PARAM_STR);
				$query->bindParam(3, $order_type, PDO::PARAM_STR);
				$query->execute();
				$res = $query->fetchColumn();
				if($res > 0){
					continue;
				}else{
					$ins_order =$db->prepare("insert into $schemas.order_daily(filing_no,order_date,
					user_id,flag,bench_nature,court_no,entry_date,order_type,pdf_path,filename,order_upload_date,judge_codes)
					values(?,?,?,?,?,?,?,?,?,?,?,?)");
					
					$ins_order->bindParam(1, $child_case_filing_no, PDO::PARAM_STR);
					$ins_order->bindParam(2, $listing_date, PDO::PARAM_STR);
					$ins_order->bindParam(3, $sessionUserType, PDO::PARAM_STR);
					$ins_order->bindParam(4, $flag, PDO::PARAM_STR);
					$ins_order->bindParam(5, $bench_nature, PDO::PARAM_STR);
					$ins_order->bindParam(6, $court_no, PDO::PARAM_STR);
					$ins_order->bindParam(7, $server_date, PDO::PARAM_STR);
					//$ins_order->bindParam(8, $get_bench_no, PDO::PARAM_STR);
					$ins_order->bindParam(8, $order_type, PDO::PARAM_STR);
					$ins_order->bindParam(9, $saveTargetFilePath, PDO::PARAM_STR);
					$ins_order->bindParam(10, $fileName, PDO::PARAM_STR);
					$ins_order->bindParam(11, $order_upload_date, PDO::PARAM_STR);
					$ins_order->bindParam(12, $coram, PDO::PARAM_STR);
					$res = $ins_order->execute();
				}
			}
		}
		
		if($res){
			$msg = "Order uploaded";
		}
	}
	$hash = base64_encode($msg);
	$db->commit();
	header("Location:upload_order.php?hash=$hash");
	die;
	 }catch(Exception $e){
		$msg = "Some error occurred";
		$hash = base64_encode($msg);
		header("Location:upload_order.php?hash=$hash");
	}
	die;

}
