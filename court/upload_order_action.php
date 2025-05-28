<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
session_start();
ob_start();
include("../db_inc1.php");
include("../db_inc2.php");
include '../pdftotext/autoload.php';
require_once ("../qrcode/phpqrcode/qrlib.php");
require_once("../qrcode/vendor/autoload.php");
require_once ("../qrcode/helper.php");
require_once("../qrcode/pdftk/vendor/autoload.php");
use mikehaertl\pdftk\Pdf;
use setasign\Fpdi\Fpdi; 
$pdfobj = new Fpdi();
$parser = new \Smalot\PdfParser\Parser();
date_default_timezone_set("Asia/Kolkata");



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
	function get_order_text($parser,$path){
		
		try{	
		$order_pdf  = $parser->parseFile($path);
		$order_text = $order_pdf->getText();
		}
		catch(Exception $e){
			$order_text = 'NA';
		}finally{
			if($order_text == ''){
				$order_text = 'NA';
			}
		}
		return $order_text;
	}
	
  try{
	$db->beginTransaction();  // begin transaction
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";
	
	$server_date= date('Y-m-d'); //Returns IST 
	$ip=htmlspecialchars($_SERVER["REMOTE_ADDR"]);
	$case_no = (isset($_POST['case_no_order']) && $_POST['case_no_order'] != '')?$_POST['case_no_order']:0;
	$case_year = (isset($_POST['case_year_order']) && $_POST['case_year_order'] != '')?$_POST['case_year_order']:0;;
	$order_date = (isset($_POST['order_date']) && $_POST['order_date'] != '')?$_POST['order_date']:0;
	//$upload_date = (isset($_POST['upload_date']) && $_POST['upload_date'] != '')?$_POST['upload_date']:0;
	$court_no = (isset($_POST['court_no']) && $_POST['court_no'] != '')?$_POST['court_no']:0;
	$bench_nature = $_POST['bench_nature'];
	$bench_no = (isset($_POST['bench_no']) && $_POST['bench_no'] != '')?$_POST['bench_no']:0;
	$order_type = $_POST['order_type'];
	$author_by = $_POST['author_by'];
	
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
	//list($day,$month,$year)=explode('/',$upload_date);
	//$order_upload_date=$year."-".$month."-".$day;
	$order_upload_date=date('Y-m-d');
	$order_type_title = 'daily';
	$uploadDir = '/Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/orders/'.$filing_no.'/'.$order_type_title;
	$saveUploadDir = '/Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/orders/'.$filing_no.'/'.$order_type_title;
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
		
		  $restore_type = 5;
		  $is_case_restored=$db->prepare("select count(*) as restore_check from $schemas.restored_cases where filing_no=? and disposal_date < ? and restoration_type = ?");
		  $is_case_restored->bindParam(1, $filing_no, PDO::PARAM_STR);
		  $is_case_restored->bindParam(2, $listing_date, PDO::PARAM_STR);
		  $is_case_restored->bindParam(3, $restore_type, PDO::PARAM_STR);
		  $is_case_restored->execute();
		  $is_case_restored = $is_case_restored->fetchColumn();
		  
		  if(!$is_case_restored){
  
				$query =$db->prepare("select count(*) as count from $schemas.case_proceeding where filing_no = ? and listing_date = ?");
				$query->bindParam(1, $filing_no, PDO::PARAM_STR);
				$query->bindParam(2, $listing_date, PDO::PARAM_STR);
				$query->execute();
				$res = $query->fetchColumn();
				if($res == 0){
					$uploadStatus = 0;
					$msg = "Proceedig not completed for this listing date , please first complete proceeding";
					$hash = base64_encode($msg);
					header("Location:upload_order.php?hash=$hash");
					die($msg);
				}
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


		// code for qr code start
		
		$uploadDir_appended_qr_pdf = $uploadDir."/qrappendedpdf/";
		$filePath_appended_qr_pdf=$uploadDir_appended_qr_pdf.time().uniqid(rand()).'.pdf'; 
		$encoded_path = base64_encode($filePath_appended_qr_pdf);
		$domain = url();
		$qr_code_value = $domain."/gstat/order_view.php?path=".$encoded_path;
		

		$uploadDir_qr = $uploadDir_appended_qr_pdf."/qrimage/";
		$filePath_qr=$uploadDir_qr.time().uniqid(rand()).".png"; 
		if(!file_exists($uploadDir_qr)){
		mkdir($uploadDir_qr, 0777, true);
		} 
		$ecc = 'L';
		$pixel_Size = 1;
		$frame_Size = 1;


		ob_clean();
		if(!file_exists($uploadDir_appended_qr_pdf)){
			mkdir($uploadDir_appended_qr_pdf, 0777, true);
		}
		try{
			$generated_qr = QRcode::png($qr_code_value, $filePath_qr, $ecc, $pixel_Size, $frame_size);
		appendqrcode($pdfobj,$targetFilePath,$filePath_qr,$uploadDir_appended_qr_pdf,$filePath_appended_qr_pdf);
		}catch(Exception $e){
			
			$pdftk = new Pdf($targetFilePath);
			$uploadDir_qr_pdf = $uploadDir."/qrappendedpdf/qrpdf/";
			$filePath_qr_pdf=$uploadDir_qr_pdf.time().uniqid(rand()).".pdf"; 
			if(!file_exists($uploadDir_qr_pdf)){
			mkdir($uploadDir_qr_pdf, 0777, true);
			} 

			
			$pdf = new FPDF();
			$uploadDir_pdf_with_qr = $uploadDir."/qrappendedpdf/";
			$filePath_qpdf_with_qr=$uploadDir_pdf_with_qr.time().uniqid(rand()).'.pdf';
			//$filePath_qpdf_with_qr =$uploadDir_pdf_with_qr.time().uniqid(rand()).".pdf"; 
			$encoded_path = base64_encode($filePath_qpdf_with_qr);
			$qr_code_value = $domain."/gstat/order_view.php?path=".$encoded_path;
			$generated_qr = QRcode::png($qr_code_value, $filePath_qr, $ecc, $pixel_Size, $frame_size);
			appendqrcode_second($pdftk,$pdf,$filePath_qr,$filePath_qr_pdf,$filePath_qpdf_with_qr);
			$filePath_appended_qr_pdf = $filePath_qpdf_with_qr;
			$filePath_qr = $filePath_qr_pdf;
			
		} 


		//  code for qr code end




		$flag = 'Y';
		$case_detail = array_shift($case_detail);
		$filing_no = $case_detail['filing_no'];
		$order_text = get_order_text($parser,$saveTargetFilePath);
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
		user_id,flag,bench_nature,court_no,entry_date,order_type,pdf_path,filename,order_upload_date,judge_codes,upload_machine_ip,bench_no,order_html,author_by,qrcode_value,qrcode_img_path,qrcode_apended_order_path)
		values(?,?,?,?,?,?,now(),?,?,?,?,?,?,?,?,?,?,?,?)");
		
		$ins_order->bindParam(1, $filing_no, PDO::PARAM_STR);
		$ins_order->bindParam(2, $listing_date, PDO::PARAM_STR);
		$ins_order->bindParam(3, $sessionUserType, PDO::PARAM_STR);
		$ins_order->bindParam(4, $flag, PDO::PARAM_STR);
		$ins_order->bindParam(5, $bench_nature, PDO::PARAM_STR);
		$ins_order->bindParam(6, $court_no, PDO::PARAM_STR);
		//$ins_order->bindParam(7, $server_date, PDO::PARAM_STR);
		//$ins_order->bindParam(8, $get_bench_no, PDO::PARAM_STR);
		$ins_order->bindParam(7, $order_type, PDO::PARAM_STR);
		$ins_order->bindParam(8, $saveTargetFilePath, PDO::PARAM_STR);
		$ins_order->bindParam(9, $fileName, PDO::PARAM_STR);
		$ins_order->bindParam(10, $order_upload_date, PDO::PARAM_STR);
		$ins_order->bindParam(11, $coram, PDO::PARAM_STR);
		$ins_order->bindParam(12, $ip, PDO::PARAM_STR);
		$ins_order->bindParam(13, $bench_no, PDO::PARAM_STR);
		$ins_order->bindParam(14, $order_text, PDO::PARAM_STR);
		$ins_order->bindParam(15, $author_by, PDO::PARAM_STR);
		$ins_order->bindParam(16, $qr_code_value, PDO::PARAM_STR);
		$ins_order->bindParam(17, $filePath_qr, PDO::PARAM_STR);
		$ins_order->bindParam(18, $filePath_appended_qr_pdf, PDO::PARAM_STR);
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
					user_id,flag,bench_nature,court_no,entry_date,order_type,pdf_path,filename,order_upload_date,judge_codes,upload_machine_ip,bench_no,order_html,author_by,qrcode_value,qrcode_img_path,qrcode_apended_order_path)
					values(?,?,?,?,?,?,now(),?,?,?,?,?,?,?,?,?,?,?,?)");
					
					$ins_order->bindParam(1, $child_case_filing_no, PDO::PARAM_STR);
					$ins_order->bindParam(2, $listing_date, PDO::PARAM_STR);
					$ins_order->bindParam(3, $sessionUserType, PDO::PARAM_STR);
					$ins_order->bindParam(4, $flag, PDO::PARAM_STR);
					$ins_order->bindParam(5, $bench_nature, PDO::PARAM_STR);
					$ins_order->bindParam(6, $court_no, PDO::PARAM_STR);
					//$ins_order->bindParam(7, $server_date, PDO::PARAM_STR);
					//$ins_order->bindParam(8, $get_bench_no, PDO::PARAM_STR);
					$ins_order->bindParam(7, $order_type, PDO::PARAM_STR);
					$ins_order->bindParam(8, $saveTargetFilePath, PDO::PARAM_STR);
					$ins_order->bindParam(9, $fileName, PDO::PARAM_STR);
					$ins_order->bindParam(10, $order_upload_date, PDO::PARAM_STR);
					$ins_order->bindParam(11, $coram, PDO::PARAM_STR);
					$ins_order->bindParam(12, $ip, PDO::PARAM_STR);
					$ins_order->bindParam(13, $bench_no, PDO::PARAM_STR);
					$ins_order->bindParam(14, $order_text, PDO::PARAM_STR);
					$ins_order->bindParam(15, $author_by, PDO::PARAM_STR);
					$ins_order->bindParam(16, $qr_code_value, PDO::PARAM_STR);
					$ins_order->bindParam(17, $filePath_qr, PDO::PARAM_STR);
					$ins_order->bindParam(18, $filePath_appended_qr_pdf, PDO::PARAM_STR);
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
		print_r($e);
		$msg = "Some error occurred";
		$hash = base64_encode($msg);
		//header("Location:upload_order.php?hash=$hash");
	}
	die;

}
