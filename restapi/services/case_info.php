<?php

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */ 

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here

// include database and object files
require_once '../config/database.php';
require_once '../config/auth.php';
require_once '../objects/casestatus.php';
require_once '../objects/global_functions.php';
$auth = new Auth();
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
$case_obj = new CaseStatus($db);
$global_obj = new GlobalFunctions($db);

$search_type = isset($_REQUEST['search_type'])?$_REQUEST['search_type']:'';
//$search_type=openssl_decrypt ($search_type, $ciphering, $decryption_key, $options, $decryption_iv);

if($search_type == 'initial') {
	$response = array('status'=>0,'msg'=>'success');
	$get_benchs = $global_obj->get_all_zone();
	$get_case_type_master = $global_obj->get_case_types();
	$response['benches'] = $get_benchs;
	$response['case_types'] = $get_case_type_master;
	http_response_code(200);
	echo json_encode($response,true);
	die;
}

if($search_type == 'get_order') {
        ini_set('max_execution_time', 3000);
        $path = $_POST['path'];
        if(!empty($path)){
        $path = urldecode($path);
        //echo $path; 
        $time = time();
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=$time.pdf");
        @readfile($path);
        die;
        }else{
        echo "not found";
        die;
        }
}

/* $basic_info = isset($_REQUEST['basic_info'])?$_REQUEST['basic_info']:'N';
$last_listing_info = isset($_REQUEST['last_listing_info'])?$_REQUEST['last_listing_info']:'N';
$scrutiny_status = isset($_REQUEST['scrutiny_status'])?$_REQUEST['scrutiny_status']:'N';
$listing_history = isset($_REQUEST['listing_history'])?$_REQUEST['listing_history']:'N';
$orders = isset($_REQUEST['orders'])?$_REQUEST['orders']:'N';
$child_cases = isset($_REQUEST['child_cases'])?$_REQUEST['child_cases']:'N';
$connected_cases = isset($_REQUEST['connected_cases'])?$_REQUEST['connected_cases']:'N'; */
$response = array('status'=>0,'msg'=>'success');
if($search_type == 'fn'){
	$filing_no = isset($_REQUEST['filing_no'])?$_REQUEST['filing_no']:'';
	if(!empty($filing_no)){
		$loc_code = substr($filing_no, 0, 2);
		if($loc_code == '07')
			$loc_code = 7;
		$schema = $global_obj->get_schema($column = 'state_id',$loc_code);
		$case_info = $case_obj->get_basic_info($schema,$type='fn',$filing_no);
		$case_info = array_shift($case_info); 
	}else{
		$return_array = array('status'=>0,'msg'=>'Filing number not exist'); 
		http_response_code(400);
		echo json_encode($return_array);
		die;
	}
}

if($search_type == 'get_case_info'){
	$case_no = isset($_REQUEST['case_no'])?$_REQUEST['case_no']:'';
	$case_year = isset($_REQUEST['case_year'])?$_REQUEST['case_year']:'';
	$case_type = isset($_REQUEST['case_type'])?$_REQUEST['case_type']:'';
	$location = isset($_REQUEST['location'])?$_REQUEST['location']:'';
	
	// Use openssl_decrypt() function to decrypt the data 
		/* $case_no=openssl_decrypt ($case_no, $ciphering,  
        $decryption_key, $options, $decryption_iv); 
		$case_year=openssl_decrypt ($case_year, $ciphering,  
        $decryption_key, $options, $decryption_iv);
		$case_type=openssl_decrypt ($case_type, $ciphering,  
        $decryption_key, $options, $decryption_iv);
		$location=openssl_decrypt ($location, $ciphering,  
        $decryption_key, $options, $decryption_iv); */
		
		
	if(!empty($case_no) && !empty($case_year) && !empty($case_type) && !empty($location)){
		$schema = $global_obj->get_schema($column = 'city_id',$location);
		$schema_details = $global_obj->get_schema_detail($column = 'city_id',$location);
		$case_info = $case_obj->get_basic_info($schema,$type='cn',$filing_no = '',$case_no,$case_year,$case_type);
		if(empty($case_info)){
			$return_array = array('status'=>101,'msg'=>'Case not found'); 
			http_response_code(404);
			echo json_encode($return_array);
			die;
		}
		if(count($case_info) > 1){
			$filing_nos = array_column($case_info, 'filing_no');
			$implode_fn = implode("','",$filing_nos);
			$implode_fn = "'".$implode_fn."'";
			
			$filing_no_to_show = $case_obj->get_latest_hearing_filing_no($schema,$implode_fn);
			if(!empty($filing_no_to_show)){
				foreach($case_info as $k=>$case_detail){
					if($case_detail['filing_no'] == $filing_no_to_show){
						$case_info = $case_detail;
						break;
					}
				}
			}else{
				$case_info = array_shift($case_info);
			}
			
			/* $return_array = array('status'=>101,'msg'=>'Case number duplicasy exist'); 
			http_response_code(200);
			echo json_encode($return_array);
			die */;
		}else{
			$case_info = array_shift($case_info); 
		}
	}else{
		$return_array = array('status'=>0,'msg'=>'Fill all details'); 
		http_response_code(400);
		echo json_encode($return_array);
		die;
	}
}

$filing_no = $case_info['filing_no'];
$case_status = $case_info['status'];
if(!empty($filing_no)){
$petitioner = $case_obj->get_party($filing_no,$party_flag='P',$type='one',$party_serial_no = 1);
$petitioner = array_shift($petitioner);
$respondent = $case_obj->get_party($filing_no,$party_flag='R',$type='one',$party_serial_no = 1);
$respondents = $case_obj->get_party($filing_no,$party_flag='R',$type='all');
$petitioners = $case_obj->get_party($filing_no,$party_flag='P',$type='all');
$respondent = array_shift($respondent);
$case_info['pet_name'] = $petitioner['name'];
$case_info['pet_mobile'] = 	$petitioner['mobile'];
$case_info['pet_email'] = 	$petitioner['email'];
$case_info['res_name'] = $respondent['name'];
$case_info['res_mobile'] = 	$respondent['mobile'];
$case_info['res_email'] = 	$respondent['email'];	
$case_info['all_petitioners'] = $petitioners;	
$case_info['all_respondents'] = $respondents;	
$response['basic_info'] = $case_info;
$status = 'Pending';
if($case_status == 'D'){
	$status = 'Disposed';
	$disposed_data = $case_obj->get_disposed_date($schema,$filing_no);

	if(!empty($disposed_data)){
		$disposed_data = array_shift($disposed_data);
		$response['disposed_data'] = $disposed_data;
		$disposal_date = $disposed_data['disposal_date'];
		$disposal_nature = $disposed_data['action_name'];
	}else{
		$disposal_date = $disposal_nature = '';
	}
}else{
	$disposal_date = $disposal_nature = '';
}

//if(!empty($last_listing_info) && $last_listing_info == 'Y'){
	$first_listing_info = $case_obj->get_listing_info($schema,$filing_no,$type = 'first');
	$first_listing_info = array_shift($first_listing_info);
	$response['first_listing_info'] = $first_listing_info;
//}

	$first_order = $case_obj->orders($schema,$filing_no,$case_status,$flag = 'Y','first');
	$response['first_order'] = $first_order;

//if(!empty($listing_history) && $listing_history == 'Y'){
	$listing_history = $case_obj->get_listing_info($schema,$filing_no);
	
	$adv_detail = $case_obj->get_adv_details($filing_no);
	if(!empty($adv_detail)){
		$adv_detail = array_shift($adv_detail);
		$pet_adv_name = $adv_detail['petadvname'];
		$pet_adv_mobile = $adv_detail['petadvmobile'];
		$res_adv_name = $adv_detail['resadvname'];
		$res_adv_mobile = $adv_detail['resadvmobile'];
		$section = $adv_detail['maincase_section'];
	}else{
		$pet_adv_name = $pet_adv_mobile = $res_adv_name = $res_adv_mobile = '';
	}
	
	
	$response['listing_history'] = $listing_history;
	$listing_array = array();
	foreach($listing_history as $k=>$listing){
		$listing_array[$k]['judgename'] = (!empty($listing['judge_name']))?"Hon'ble ".$listing['judge_name']:'';
		$listing_array[$k]['hearing_date'] = $listing['listing_date'];
		$listing_array[$k]['action_type'] = $listing['action_type']; 
		$listing_array[$k]['purpose_of_listing'] = $listing['purpose_name'];
		/* $listing_array[$k]['Next_date_of_hearing'] = ($listing['next_list_date']!='1111-11-11')?$listing['next_list_date']:'';
		$listing_array[$k]['officer_attended_the_hearing'] = '';
		$listing_array[$k]['details_of_hearing'] = $listing['remarks'];
		$listing_array[$k]['hearing_date'] = $listing['listing_date'];
		
		$listing_array[$k]['Petitioner_advocate'] = $pet_adv_name;
		$listing_array[$k]['Petitioner_advocate_mobile'] = $pet_adv_mobile;
		$listing_array[$k]['Respondent_advocate_name'] = $res_adv_name;
		$listing_array[$k]['Respondent_advocate_mobile'] = $res_adv_mobile; */
	}
//}
	
//if(!empty($orders) && $orders == 'Y'){
	$all_orders_judgement = $case_obj->orders($schema,$filing_no,$case_status,$flag = 'Y');
	/* if($case_status == 'D'){
		$judgements = $case_obj->judgements($schema,$filing_no,$display = 't');
		$all_orders_judgement = array_merge($all_orders_judgement,$judgements);
	} */
	$response['orders'] = $all_orders_judgement;
//}

//if(!empty($child_cases) && $child_cases == 'Y'){
	/* $child_cases = $case_obj->get_child_cases($schema,$filing_no);
	$response['child_cases'] = $child_cases; */
//}

//if(!empty($connected_cases) && $connected_cases == 'Y'){
	/* $connected_cases_list = $case_obj->get_connected_cases($schema,$filing_no);
	$response['connected_cases'] = $connected_cases_list; */
//}
$last_listing_info = $case_obj->last_listing_info($schema,$filing_no);
if(!empty($last_listing_info)){
	$last_listing_info = array_shift($last_listing_info);
$next_list_date = ($last_listing_info['next_list_date'] != '' && $last_listing_info['next_list_date'] != null && $last_listing_info['next_list_date'] != '1111-11-11')?$last_listing_info['next_list_date']:'';
$next_purpose = $last_listing_info['next_purpose'];
$bench_name = '';
if($last_listing_info['bench_nature']!=''){
	$bn = $db->prepare("select bench_name from $schema.bench_nature where bench_code=?");
	$bn->bindParam(1, $last_listing_info['bench_nature'], PDO::PARAM_STR);
	$bn->execute();
	$bench_name= $bn->fetchColumn();
}
}else{
	$next_list_date =$bench_name =$next_purpose =  '';
}
$diryno=substr_replace($case_info['filing_no'] ,"",-4);
$diryyear= substr($case_info['filing_no'], -4);
$array = array(
			"status" => "success",
			"message" => array(
				"case_type_code" => $case_info['case_type'],
				"case_type" => $case_info['case_type_short_name'],
				"caseno" => $case_info['case_no'],
				"caseyear" => $case_info['case_year'],
				"filing_no" => $case_info['filing_no'],
				"diaryno" => $diryno,
				"diaryyear" => $diryyear,
				"pet_name" => $petitioner['name'],
				"pet_email" => $petitioner['email'],
				"pet_mobile" => $petitioner['mobile'],
				"regis_date" => $case_info['regis_date'],
				"res_name" => $respondent['name'],
				"res_email" => $respondent['email'],
				"res_mobile" => $respondent['mobile'],
				"dt_of_filing" => $case_info['dt_of_filing'],
				"pet_adv" => $pet_adv_name,
				"pet_adv_mobile" => $pet_adv_mobile,
				"res_adv" => $res_adv_name,
				"res_adv_mobile" => $res_adv_mobile,
				"pend_disp" => $status,
				"state" => $schema_details['state_name'],
				"name_of_bench" => $case_info['bench_location_name'],
				"name_circuit_bench" => $case_info['bench_location_name'],
				"case_sub_cat" => array([
						"cat_name" => " "
				 ]),
				 "first_listing_date" => $case_obj->first_listing_date($schema,$filing_no),
				 "last_listing_date" => $case_obj->last_listing_date($schema,$filing_no),
				"next_hearing" =>array([
					"nextdt" => $next_list_date,
					"purpose_name" =>$last_listing_info['next_purpose'],
					"bench_name" => $bench_name,
				]),
				"pet_extra_party" => array(),
				"res_extra_party" => array(),
				"pet_add_advocate" => array(),
				"res_add_advocate" =>  array([
						"adv_name" => " "
				]),
				"act_section" =>  $section,
				"historyofcasehearing" =>$listing_array,
				"daily_order" =>$all_orders_judgement,
			   "disposal_details" =>array([
					"disposal_date" => $disposal_date,
					"disposal_name" => $disposal_nature
				 ]),
			),
	);









 $response['status'] = 200;
http_response_code(200);
echo json_encode($array);
die;
}else{
	http_response_code(404);
	$return_array = array('status'=>0,'msg'=>'Case not found'); 
	echo json_encode($response);
	die;
}
