<?php
   ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL); 

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here

// include database and object files
require_once '../config/database.php';
require_once '../config/auth_nclt_mapping.php';
require_once '../objects/nclt_mapping_case_status.php';
require_once '../objects/nclt_mapping_api_functions.php';
$auth = new AuthNcltMapping();
$is_auth = $auth->token_auth();
//$is_auth = $auth->basic_auth();


// Store the cipher method 
$ciphering = "AES-128-CTR"; 
  
// Use OpenSSl Encryption method 
$iv_length = openssl_cipher_iv_length($ciphering); 
$options = 0; 
// Non-NULL Initialization Vector for decryption 
$decryption_iv = '1234567891011121'; 
  
// Store the decryption key 
$decryption_key = "encrypt"; 

$encryption_iv = '1234567891011121'; 
  
// Store the encryption key 
$encryption_key = "encrypt"; 

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
$case_obj = new NcltMappingCaseStatus($db,$filing_no_length=16,$backlog = 0,$wrong_status = 'W',$blank = '',$pet_party_flag = 'P',$res_party_flag = 'R',$party_serial_no = 1);

$global_obj = new NcltMappingApi($db);

$search_type = isset($_POST['search_type'])?htmlspecialchars(htmlentities($_POST['search_type'])):'';
$wrongly_case_status = 'W';


if($search_type == 'get_nclt_cases'){
	$response = array('status'=>200,'msg'=>'success');
	$data_set_first = $case_obj->get_registred_cases($db,'delhi');
	$data_set_second = $case_obj->get_registred_cases($db,'chennai');
	$data = array_merge($data_set_first,$data_set_second);
	$response['data'] = $data;
	$response['msg'] = 'success';
	http_response_code(200);
	echo json_encode($response,true);
	die;

}

if($search_type == 'update_mapped_status'){
	$response = array('status'=>200,'msg'=>'success');
	$cases = isset($_POST['cases'])?$_POST['cases']:array();
	$cases = json_decode($cases);
	foreach($cases as $k=>$case){
		$schema = $case->schema;
		$filing_no = $case->filing_no;
		$update_fetched_status = $case_obj->update_fetched_status($db,$filing_no,$schema);
	}
	http_response_code(200);
	echo json_encode($response,true);
	die;

}

if($search_type == 'get_nclt_updated_case'){
	$response = array('status'=>200,'msg'=>'success');
	$cases = isset($_POST['cases'])?$_POST['cases']:array();
	$cases = json_decode($cases);

	foreach($cases as $k=>$case){
		$filing_no = isset($case->filing_no)?htmlspecialchars(htmlentities($case->filing_no)):'';
		$schema = isset($case->schema)?htmlspecialchars(htmlentities($case->schema)):'';
		$case_detail = $case_obj->get_cases_filing_no_wise($db,$schema,$filing_no);
		if(!empty($case_detail)){
			$dispose_data = $last_proceeding_detail = array();
			$data[$filing_no]['status'] = $case_detail['status'];
			$last_proceeding_detail = $case_obj->get_hearing_details($db,$schema,$filing_no,$type='last');
       		 }

			if($case_detail['status'] == 'D') 
					$dispose_data = $case_obj->get_dispose_details($db,$schema,$filing_no);

			
			$data[$filing_no]['court_no'] = (isset($last_proceeding_detail['court_no']) && !empty($last_proceeding_detail['court_no']))?$last_proceeding_detail['court_no']:'';
			$data[$filing_no]['hearing_date'] = (isset($last_proceeding_detail['hearing_date']) && !empty($last_proceeding_detail['hearing_date']))?$last_proceeding_detail['hearing_date']:'';
			$data[$filing_no]['stage_of_case'] = (isset($last_proceeding_detail['stage_of_case']) && !empty($last_proceeding_detail['stage_of_case']))?$last_proceeding_detail['stage_of_case']:'';
			$data[$filing_no]['bench_no'] = (isset($last_proceeding_detail['bench_no']) && !empty($last_proceeding_detail['bench_no']))?$last_proceeding_detail['bench_no']:'';
			$data[$filing_no]['action_type'] = (isset($last_proceeding_detail['action_type']) && !empty($last_proceeding_detail['action_type']))?$last_proceeding_detail['action_type']:'';
			$data[$filing_no]['pdf_path'] = (isset($last_proceeding_detail['pdf_path']) && !empty($last_proceeding_detail['pdf_path']))?$last_proceeding_detail['pdf_path']:'';
			$data[$filing_no]['next_hearing_date'] = (isset($last_proceeding_detail['next_hearing_date']) && !empty($last_proceeding_detail['next_hearing_date']))?$last_proceeding_detail['next_hearing_date']:'';
			$data[$filing_no]['disposal_date'] = (isset($dispose_data['disposal_date']) && !empty($dispose_data['disposal_date']))?$dispose_data['disposal_date']:'';
			$data[$filing_no]['disposal_nature'] = (isset($dispose_data['disposal_nature']) && !empty($dispose_data['disposal_nature']))?$dispose_data['disposal_nature']:'';

		}
	
	$response['data'] = $data;
	http_response_code(200);
	echo json_encode($response,true);
	die;
}


if($search_type == 'mapping'){
	$response = array('status'=>200,'msg'=>'success');
	$nclt_case_no = isset($_POST['case_no'])?htmlspecialchars(htmlentities($_POST['case_no'])):'';
	$nclt_case_type = isset($_POST['case_type'])?htmlspecialchars(htmlentities($_POST['case_type'])):'';
	$nclt_case_year = isset($_POST['case_year'])?htmlspecialchars(htmlentities($_POST['case_year'])):'';
	$nclt_filing_no = isset($_POST['filing_no'])?htmlspecialchars(htmlentities($_POST['filing_no'])):'';
	$e_case_detail = $case_obj->get_case_info_by_nclt($db,$schema,$nclt_case_type,$nclt_case_no,$nclt_case_year,$nclt_filing_no);
	if(!empty($e_case_detail)){
		$schema = $global_obj->get_schema('city_id',$e_case_detail['location_id']);
		$case_detail = $case_obj->get_cases_filing_no_wise($db,$schema,$e_case_detail['filing_no']);
		if(!empty($case_detail)){
			$data['case_detail'] = $case_detail;
			$last_proceeding_detail = $case_obj->get_hearing_details($db,$schema,$case_detail['filing_no'],$type='last');
			$data['last_proceeding'] = $last_proceeding_detail;
			$response['data'] = $data;
			http_response_code(200);
			echo json_encode($response,true);
			die;
		}else{
			$response['data'] = '';
			$response['msg'] = 'Case not registred';
			http_response_code(200);
			echo json_encode($response,true);
			die;
		}
	}else{
		$response['data'] = '';
		$response['msg'] = 'Case not found';
		http_response_code(200);
		echo json_encode($response,true);
		die;
	}
	

}

$response = array('status'=>500,'msg'=>'something went wrong');
http_response_code(500);
echo json_encode($response,true);
die;


