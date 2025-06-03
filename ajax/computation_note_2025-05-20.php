<?php

header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
include_once('../custom/custom_function.php');
require "../vendor/autoload.php";
require_once('../object_storage/S3Service.php');
use Dompdf\Dompdf;
$dompdf = new Dompdf();

$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$username = $_SESSION['actual_username'];

//       ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);   


$server_date= date('d-m-Y'); //Returns IST 
if($server_date !='')
{
	list($day,$month,$year)=explode('-',$server_date);
	$entry_date=$year."-".$month."-".$day;
	
}

$schemas=htmlspecialchars($_SESSION['schema_name']);

$userid=$_SESSION['id'];
$username = $_SESSION['actual_username'];
$location_access = $_SESSION['location'];


setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);


if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
	$main_case_type = array(32,33,34);
	function insert_impugned_orders($db,$schemas,$filing_no,$impugned_order_dt,$limitation_computed_dt,$limitation_expires_dt){
		//echo "<pre>"; print_r($impugned_order_dt); die;
		$copy_old_data = save_impugned_order_history($db,$schemas,$filing_no);
		if($copy_old_data){
			$delete_old_record = delete_old_impugned_order($db,$schemas,$filing_no);
			if(!empty($impugned_order_dt[0]))
			{
				foreach($impugned_order_dt as $k=>$order_date){
					$impg_order_date = display_date($order_date,'dd/mm/yyyy');
					
					$limitation_computed_date = (isset($limitation_computed_dt[$k]) && !empty($limitation_computed_dt[$k]))?display_date($limitation_computed_dt[$k],'dd/mm/yyyy'):'9999-01-01';
					$limitation_expires_date = (isset($limitation_expires_dt[$k]) && !empty($limitation_expires_dt[$k]))?display_date($limitation_expires_dt[$k],'dd/mm/yyyy'):'9999-01-01';
					$query = "insert into $schemas.impugned_order_details (filing_no,impugned_order_dt,limitation_computed_dt,limitation_expires_dt) values (?,?,?,?)";
					$ins_note =$db->prepare($query);
					$ins_note->bindParam(1, $filing_no, PDO::PARAM_STR);
					$ins_note->bindParam(2, $impg_order_date, PDO::PARAM_STR);
					$ins_note->bindParam(3, $limitation_computed_date, PDO::PARAM_STR);
					$ins_note->bindParam(4, $limitation_expires_date, PDO::PARAM_STR);
					$res = $ins_note->execute();
				}
			}else{
				$impugned_order_dt = $limitation_computed_date = $limitation_expires_date = '9999-01-01';
				$query = "insert into $schemas.impugned_order_details (filing_no,impugned_order_dt,limitation_computed_dt,limitation_expires_dt) values (?,?,?,?)";
				$ins_note =$db->prepare($query);
				$ins_note->bindParam(1, $filing_no, PDO::PARAM_STR);
				$ins_note->bindParam(2, $impugned_order_dt, PDO::PARAM_STR);
				$ins_note->bindParam(3, $limitation_computed_date, PDO::PARAM_STR);
				$ins_note->bindParam(4, $limitation_expires_date, PDO::PARAM_STR);
				$res = $ins_note->execute();
			}
			return $res;
		}
	}
	function delete_old_impugned_order($db,$schemas,$filing_no){
		$delete_query = "delete from $schemas.impugned_order_details where filing_no = ?";
		$delete_query =$db->prepare($delete_query);
		$delete_query->bindParam(1, $filing_no, PDO::PARAM_STR);
		$res = $delete_query->execute();
		return $res;
	}
	
	function save_impugned_order_history($db,$schemas,$filing_no){
		$copy_query = "insert into $schemas.impugned_order_details_his (filing_no,impugned_order_dt,limitation_computed_dt,limitation_expires_dt) 
		select filing_no,impugned_order_dt,limitation_computed_dt,limitation_expires_dt from $schemas.impugned_order_details where filing_no = ?";
		$copy_note =$db->prepare($copy_query);
		$copy_note->bindParam(1, $filing_no, PDO::PARAM_STR);
		$res = $copy_note->execute();
		return $res;
	}
	
	function insert_comp_note($db,$schemas,$table,$user_id,$username,$filing_no,$case_type,$limitation_days,$caveat_filed,$delay_represent_remarks,$date_of_representation,$date_of_return,$intimation_defects_dt,$date_of_scrutiny,$presentation_dt,$delay_remarks,$all_ias,$caveat_remark,$delay_ia_efiling_date,$delay_ia_remarks,$mode){
		$one = 1;
		if(empty($limitation_days)){
			$limitation_days = 0;
		}
		$query = "insert into $schemas.$table (filing_no,case_type,user_id,username,limitation_days,caveat_filed,delay_represent_remarks,
				 date_of_representation,date_of_return,intimation_defects_dt,date_of_scrutiny,presentation_dt,delay_remarks,
				 connected_ias,in_registrar,caveat_remark,delay_ia_efiling_date,delay_ia_remarks) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$ins_note =$db->prepare($query);
		$ins_note->bindParam(1, $filing_no, PDO::PARAM_STR);
		$ins_note->bindParam(2, $case_type, PDO::PARAM_STR);
		$ins_note->bindParam(3, $user_id, PDO::PARAM_STR);
		$ins_note->bindParam(4, $username, PDO::PARAM_STR);
		$ins_note->bindParam(5, $limitation_days, PDO::PARAM_STR);
		$ins_note->bindParam(6, $caveat_filed, PDO::PARAM_STR);
		$ins_note->bindParam(7, $delay_represent_remarks, PDO::PARAM_STR);
		$ins_note->bindParam(8, $date_of_representation, PDO::PARAM_STR);
		$ins_note->bindParam(9, $date_of_return, PDO::PARAM_STR);
		$ins_note->bindParam(10, $intimation_defects_dt, PDO::PARAM_STR);
		$ins_note->bindParam(11, $date_of_scrutiny, PDO::PARAM_STR);
		$ins_note->bindParam(12, $presentation_dt, PDO::PARAM_STR);
		$ins_note->bindParam(13, $delay_remarks, PDO::PARAM_STR);
		$ins_note->bindParam(14, $all_ias, PDO::PARAM_STR);
		$ins_note->bindParam(15, $mode, PDO::PARAM_STR);
		$ins_note->bindParam(16, $caveat_remark, PDO::PARAM_STR);
		$ins_note->bindParam(17, $delay_ia_efiling_date, PDO::PARAM_STR);
		$ins_note->bindParam(18, $delay_ia_remarks, PDO::PARAM_STR);
		$res = $ins_note->execute();
		

		if($res && $mode != 0){
			$query = "update e_case_detail set summary_note = 2 where filing_no = ?";
			$save_note =$db->prepare($query);
			$save_note->bindParam(1, $filing_no, PDO::PARAM_STR);
			$res = $save_note->execute();
			return $res;
		}
	}
	
	function update_comp_note($db,$schemas,$table,$user_id,$username,$filing_no,$case_type,$limitation_days,$caveat_filed,$delay_represent_remarks,$date_of_representation,$date_of_return,$intimation_defects_dt,$date_of_scrutiny,$presentation_dt,$delay_remarks,$all_ias,$entry_date,$caveat_remark,$delay_ia_efiling_date,$delay_ia_remarks,$mode){
		$one = 1;
		if(empty($limitation_days)){
			$limitation_days = 0;
		}
		 $copy_query = "insert into $schemas.computational_note_his (filing_no,case_type,user_id,username,limitation_days,caveat_filed,delay_represent_remarks,
				 date_of_representation,date_of_return,intimation_defects_dt,date_of_scrutiny,presentation_dt,delay_remarks,
				 connected_ias,in_registrar,caveat_remark,delay_ia_efiling_date,delay_ia_remarks) select filing_no,case_type,user_id,username,limitation_days,caveat_filed,delay_represent_remarks,
				 date_of_representation,date_of_return,intimation_defects_dt,date_of_scrutiny,presentation_dt,delay_remarks,
				 connected_ias,in_registrar,caveat_remark,delay_ia_efiling_date,delay_ia_remarks from $schemas.computational_note where filing_no = ?";
		$copy_note =$db->prepare($copy_query);
		$copy_note->bindParam(1, $filing_no, PDO::PARAM_STR);
		$res = $copy_note->execute();
		
		if($res){
		
			$query = "update $schemas.$table set updated_by = ? , updated_by_username = ? ,limitation_days = ? , caveat_filed =?, delay_represent_remarks =?,
					 date_of_representation = ?,date_of_return=?,intimation_defects_dt = ?,date_of_scrutiny = ?,presentation_dt = ?,delay_remarks = ?,
					 connected_ias = ?,in_registrar = ?, updated_date = now(), caveat_remark= ?,delay_ia_efiling_date = ? , delay_ia_remarks = ?  where filing_no = ?";

			$ins_note =$db->prepare($query);
			$ins_note->bindParam(1, $user_id, PDO::PARAM_STR);
			$ins_note->bindParam(2, $username, PDO::PARAM_STR);
			$ins_note->bindParam(3, $limitation_days, PDO::PARAM_STR);
			$ins_note->bindParam(4, $caveat_filed, PDO::PARAM_STR);
			$ins_note->bindParam(5, $delay_represent_remarks, PDO::PARAM_STR);
			$ins_note->bindParam(6, $date_of_representation, PDO::PARAM_STR);
			$ins_note->bindParam(7, $date_of_return, PDO::PARAM_STR);
			$ins_note->bindParam(8, $intimation_defects_dt, PDO::PARAM_STR);
			$ins_note->bindParam(9, $date_of_scrutiny, PDO::PARAM_STR);
			$ins_note->bindParam(10, $presentation_dt, PDO::PARAM_STR);
			$ins_note->bindParam(11, $delay_remarks, PDO::PARAM_STR);
			$ins_note->bindParam(12, $all_ias, PDO::PARAM_STR);
			$ins_note->bindParam(13, $mode, PDO::PARAM_STR);
			$ins_note->bindParam(14, $caveat_remark, PDO::PARAM_STR);
			$ins_note->bindParam(15, $delay_ia_efiling_date, PDO::PARAM_STR);
			$ins_note->bindParam(16, $delay_ia_remarks, PDO::PARAM_STR);
			$ins_note->bindParam(17, $filing_no, PDO::PARAM_STR);
			$res = $ins_note->execute();
			
			if($res && $mode != 0){
				$query = "update e_case_detail set summary_note = 2 where filing_no = ?";
				$save_note =$db->prepare($query);
				$save_note->bindParam(1, $filing_no, PDO::PARAM_STR);
				$res = $save_note->execute();
				return $res;
			}
		}
	}
	
	function save_remark($db,$schemas,$filing_no,$remark,$userid,$username){
		$query = "insert into $schemas.computational_note_remark (filing_no,remarks,user_id,username) values (?,?,?,?)";
		$ins_note =$db->prepare($query);
		$ins_note->bindParam(1, $filing_no, PDO::PARAM_STR);
		$ins_note->bindParam(2, $remark, PDO::PARAM_STR);
		$ins_note->bindParam(3, $userid, PDO::PARAM_STR);
		$ins_note->bindParam(4, $username, PDO::PARAM_STR);
		$res = $ins_note->execute();
		return $res;
	}
	
	function approve_comp_note($db,$schemas,$filing_no,$is_approved,$in_registrar,$userid,$username,$entry_date){
		$copy_query = "insert into $schemas.computational_note_his (filing_no,case_type,user_id,username,impugned_order_dt,limitation_days,caveat_filed,delay_represent_remarks,
				 date_of_representation,date_of_return,intimation_defects_dt,date_of_scrutiny,presentation_dt,delay_remarks,limitation_expires_dt,limitation_computed_dt,
				 connected_ias,in_registrar) select filing_no,case_type,user_id,username,impugned_order_dt,limitation_days,caveat_filed,delay_represent_remarks,
				 date_of_representation,date_of_return,intimation_defects_dt,date_of_scrutiny,presentation_dt,delay_remarks,limitation_expires_dt,limitation_computed_dt,
				 connected_ias,in_registrar from $schemas.computational_note where filing_no = ?";
		$copy_note =$db->prepare($copy_query);
		$copy_note->bindParam(1, $filing_no, PDO::PARAM_STR);
		$res = $copy_note->execute();
		
		if($res){
		
			$query = "update $schemas.computational_note set updated_by = ? , updated_by_username = ? ,in_registrar = ?, is_approved = ?, updated_date = ? where filing_no = ?";
			$ins_note =$db->prepare($query);
			$ins_note->bindParam(1, $userid, PDO::PARAM_STR);
			$ins_note->bindParam(2, $username, PDO::PARAM_STR);
			$ins_note->bindParam(3, $in_registrar, PDO::PARAM_STR);
			$ins_note->bindParam(4, $is_approved, PDO::PARAM_STR);
			$ins_note->bindParam(5, $entry_date, PDO::PARAM_STR);
			$ins_note->bindParam(6, $filing_no, PDO::PARAM_STR);
			$res = $ins_note->execute();
			return $res;
		}
	}
	
	function update_scrutiny_is_approved($db,$schemas,$filing_no,$is_approved,$userid,$username,$entry_date,$listing_date,$court_no){
		$query = "update $schemas.scrutiny set updated_by = ? , is_computation_note_approved = ?, computation_note_approved_date = ?, updated_at = ?, first_listing_date = ? , first_court_no = ? where filing_no = ?";
		$ins_note =$db->prepare($query);
		$ins_note->bindParam(1, $userid, PDO::PARAM_STR);
		$ins_note->bindParam(2, $is_approved, PDO::PARAM_STR);
		$ins_note->bindParam(3, $entry_date, PDO::PARAM_STR);
		$ins_note->bindParam(4, $entry_date, PDO::PARAM_STR);
		$ins_note->bindParam(5, $listing_date, PDO::PARAM_STR);
		$ins_note->bindParam(6, $court_no, PDO::PARAM_STR);
		$ins_note->bindParam(7, $filing_no, PDO::PARAM_STR);
		$res = $ins_note->execute();
		return $res;
	}
	
	function get_last_reg_no($db,$schemas,$case_type,$case_year){
		$query = "select reg_no from $schemas.case_type_reg where case_type = ? and reg_year = ?";
		$ins_note =$db->prepare($query);
		$ins_note->bindParam(1, $case_type, PDO::PARAM_STR);
		$ins_note->bindParam(2, $case_year, PDO::PARAM_STR);
		$ins_note->execute();
		$reg_no = $ins_note->fetchColumn();
		return $reg_no;
	}
	
	function save_case_detail($db,$schemas,$filing_no,$get_case_detail,$pet_name,$res_name,$case_year,$reg_no,$regis_date,$userid,$location_id,$status='P'){
		$filing_no = $get_case_detail['filing_no'];
		$filing_year = $get_case_detail['case_year'];
		$dt_of_filing = date('Y-m-d',strtotime($get_case_detail['dt_of_filing']));
		$case_type = $get_case_detail['case_type_nclat'];
		$main_case_ia_no = $get_case_detail['filingnumberia'];
		$is_partially_defective = $get_case_detail['patially_defective'];
		$transfer_case_type = $get_case_detail['transfer_case_type'];
		$transfer_case_no = $get_case_detail['transfer_case_number'];
		$transfer_case_filing_no = $get_case_detail['transfer_nclat_filing_no'];
		$transfer_case_location = $get_case_detail['transfer_bench_location'];
		
		$tr_short = '';
		if(!empty($transfer_case_type)){
				if($transfer_case_type == '32'){
					$tr_short = 'Company';
				}else if($transfer_case_type == '33'){
					$tr_short = 'Ins.';
				}else if($transfer_case_type == '34'){
					$tr_short = 'Compt.';
				}else{
					$tr_short = '';
				}
		}
		
		$query_select = "select list_with_defect from $schemas.case_detail where filing_no = ?";
		$check_case =$db->prepare($query_select);
		$check_case->bindParam(1, $filing_no, PDO::PARAM_STR);
		$check_case->execute();
		$check_case = $check_case->fetchColumn();
		
		if($check_case == '1'){
			$query_select = "update $schemas.case_detail set case_no = ? , regis_date = now() , case_year = ? where filing_no = ?";
			$update_case =$db->prepare($query_select);
			$update_case->bindParam(1, $reg_no, PDO::PARAM_STR);
			$update_case->bindParam(2, $case_year, PDO::PARAM_STR);
			$update_case->bindParam(3, $filing_no, PDO::PARAM_STR);
			$res = $update_case->execute();
			return $res;
		}else{

		
		$query = "insert into $schemas.case_detail (filing_no,case_no,pet_name,res_name,loginid,status,regis_date,
		dt_of_filing,case_type,entry_date,case_year,filing_year,filing_no_old,location_code,main_case_ia_no,is_partially_defective,
		transfrred_case_filing_no,transfrred_case_location,transfrred_case_type,transfrred_case_type_short,transfrred_case_no)
		values (?,?,?,?,?,?,now(),?,?,now(),?,?,?,?,?,?,?,?,?,?,?)";
		$ins =$db->prepare($query);
		$ins->bindParam(1, $filing_no, PDO::PARAM_STR);
		$ins->bindParam(2, $reg_no, PDO::PARAM_STR);
		$ins->bindParam(3, $pet_name, PDO::PARAM_STR);
		$ins->bindParam(4, $res_name, PDO::PARAM_STR);
		$ins->bindParam(5, $userid, PDO::PARAM_STR);
		$ins->bindParam(6, $status, PDO::PARAM_STR);
		$ins->bindParam(7, $dt_of_filing, PDO::PARAM_STR);
		$ins->bindParam(8, $case_type, PDO::PARAM_STR);
		$ins->bindParam(9, $case_year, PDO::PARAM_STR);
		$ins->bindParam(10, $filing_year, PDO::PARAM_STR);
		$ins->bindParam(11, $filing_no, PDO::PARAM_STR);
		$ins->bindParam(12, $location_id, PDO::PARAM_STR);
		$ins->bindParam(13, $main_case_ia_no, PDO::PARAM_STR);
		$ins->bindParam(14, $is_partially_defective, PDO::PARAM_STR);
		$ins->bindParam(15, $transfer_case_filing_no, PDO::PARAM_STR);
		$ins->bindParam(16, $transfer_case_location, PDO::PARAM_STR);
		$ins->bindParam(17, $transfer_case_type, PDO::PARAM_STR);
		$ins->bindParam(18, $tr_short, PDO::PARAM_STR);
		$ins->bindParam(19, $transfer_case_no, PDO::PARAM_STR);
		$res = $ins->execute();
		return $res;
		}
	}
	
	function update_e_case_detail($db,$filing_no,$location_id){
		$one = 1;
		$query = "update e_case_detail set case_no_generated = ? where filing_no = ? and location_id = ?";
		$up =$db->prepare($query);
		$up->bindParam(1, $one, PDO::PARAM_STR);
		$up->bindParam(2, $filing_no, PDO::PARAM_STR);
		$up->bindParam(3, $location_id, PDO::PARAM_STR);
		$res = $up->execute();
		return $res;
	}

	function update_case_no_generation($db,$schemas,$filing_no,$aplpath){
		$query = "update $schemas.case_no_generation set is_case_no_generated = TRUE, case_no_generation_timestamp = now(), update_at = now(), apl_form_path = ? where filing_no = ? ";
		$up =$db->prepare($query);
		$up->bindParam(1, $aplpath, PDO::PARAM_STR);
		$up->bindParam(2, $filing_no, PDO::PARAM_STR);
		$res = $up->execute();
		return $res;
	}
	
	
	
	function update_reg_no($db,$schemas,$reg_no,$case_type,$case_year){
		$query = "update $schemas.case_type_reg set under_processing = 0, reg_no = ? where reg_year = ? and case_type = ?";
		$up =$db->prepare($query);
		$up->bindParam(1, $reg_no, PDO::PARAM_STR);
		$up->bindParam(2, $case_year, PDO::PARAM_STR);
		$up->bindParam(3, $case_type, PDO::PARAM_STR);
		$res = $up->execute();
		return $res;
	}
	
	function get_first_listing_by_registrar($db,$schemas,$filing_no){
		$query = "select first_listing_date,first_court_no from $schemas.scrutiny where filing_no = ?";
		$up =$db->prepare($query);
		$up->bindParam(1, $filing_no, PDO::PARAM_STR);
		$up->execute();
		$data = $up->fetchAll();
		if(!empty($data)){
			$data = array_shift($data);
		}
		return $data;
	} 
	
	function get_order_type($db,$impugned_order_date,$filing_no,$display=TRUE){
		$query = "select a.order_copy from e_master_order_copy as a left join e_case_detail_fees as b on CAST(b.order_type as integer)  = a.order_id where b.impugned_order_date = ? and b.filing_no = ? and a.display = ?";
		$up =$db->prepare($query);
		$up->bindParam(1, $impugned_order_date, PDO::PARAM_STR);
		$up->bindParam(2, $filing_no, PDO::PARAM_STR);
		$up->bindParam(3, $display, PDO::PARAM_STR);
		$up->execute();
		$data = $up->fetchColumn();
		return $data;
	}
	
	function check_no_of_revert($db,$schemas,$filing_no){
        $query = "select count(*) from $schemas.revert_case_logs where filing_no = ?";
		$ins_note =$db->prepare($query);
		$ins_note->bindParam(1, $filing_no, PDO::PARAM_STR);
		$ins_note->execute();
		$count = $ins_note->fetchColumn();
        return $count;
        
    }
	
	function filed_date($db,$filing_no){
    	 $query = "select dt_of_filing from e_case_detail where filing_no = ?";
		$filed_date_obj =$db->prepare($query);
		$filed_date_obj->bindParam(1, $filing_no, PDO::PARAM_STR);
		$filed_date_obj->execute();
		$dt_of_filing = $filed_date_obj->fetchColumn();
        return $dt_of_filing;
    }
	
	function get_refile_date($db,$schemas,$filing_no){
		$compliance_date=$db->prepare("select to_char(date(max(date)),'yyyy-mm-dd') from scrutiny_history where filing_no = ?");
		$compliance_date->bindParam(1, $filing_no, PDO::PARAM_STR);
		$compliance_date->execute();
		$compliance_date = $compliance_date->fetchColumn();
		return $compliance_date;
	}

	function callApiAsync($url) {
    //	$command = "curl -s $url > /dev/null &";
		//	exec($command);
//	$url = 'https://uat-efiling.gstat.gov.in/efiling/getdataapl02b.drt?filingNo=2025307201000212&schema=delhipb';
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Cookie: SERVERID=gst_bk_efile_91'
  ),
));
$response = curl_exec($curl);
curl_close($curl);
//echo $response;
	}

	function removePath($path) {
	    // Check if the file exists
	    if (file_exists($path)) {
	        // First delete the file
	        unlink($path);
	        
	        // Now remove empty folders
	        $folderPath = dirname($path); // Get the parent folder
	        
	        // Loop through parent directories to check and delete if empty
	        while ($folderPath !== '/') {
	            // If the folder is empty, delete it
	            if (is_dir($folderPath) && count(scandir($folderPath)) == 2) {
	                rmdir($folderPath);
	            } else {
	                break; // Stop if the folder is not empty
	            }
	            $folderPath = dirname($folderPath); // Move up one level in the directory tree
	        }
	    } else {
	        echo "File does not exist.";
	    }
	}

	function enable_scrutiny($db,$schemas,$filing_no){
		$query = "update e_case_detail set is_defective = 0, scrutiny_level = 0, scrutiny = 0, scrutiny_count = 0, place_of_supply_accepted = 2 where filing_no = ?";
		$query_ex=$db->prepare($query);
		$query_ex->bindParam(1, $filing_no, PDO::PARAM_STR);
		$query_ex->execute();

		$query_doc = "update document_upload set display=true,doc_level=null, scrutiny = 0  where  filing_no=? and (miscellaneous_ref_no IS NULL or miscellaneous_ref_no = '')";
		$query_doc_ex=$db->prepare($query_doc);
		$query_doc_ex->bindParam(1, $filing_no, PDO::PARAM_STR);
		$query_doc_ex->execute();
	}
		
	
$data = $_POST;
$type = $data['action'];
$filing_no = $data['filing_no'];
if($type == 'get_form'){
$case_type = $data['case_type'];
$url = $data['url'];	
$sn = 1;
//$connected_ia = get_defect_free_connected_IA($db,$filing_no,$location_access,$ia_case_type='35');
$act_impuged_order = act_impugned($db,$filing_no);

$get_computation_note = computation_note_details($db,$schemas,$filing_no);
$get_impugned_order_details = impugned_order_details($db,$schemas,$filing_no);
$refile_date = get_refile_date($db,$schemas,$filing_no);
if(!empty($refile_date)){
	$refiling_date = display_date($refile_date,'yy-mm-dd');
}else{
	$refiling_date = '';
}
//if(!empty($get_computation_note)){
	$get_computation_note = array_shift($get_computation_note);
	$limitation_days = (isset($get_computation_note['limitation_days']) && !empty($get_computation_note['limitation_days']))?$get_computation_note['limitation_days']:'';
	$caveat_filed = (isset($get_computation_note['caveat_filed']) && !empty($get_computation_note['caveat_filed']))?$get_computation_note['caveat_filed']:'';
	$delay_represent_remarks = (isset($get_computation_note['delay_represent_remarks']) && !empty($get_computation_note['delay_represent_remarks']))?$get_computation_note['delay_represent_remarks']:'';
	$date_of_representation = (isset($get_computation_note['date_of_representation']) && !empty($get_computation_note['date_of_representation']) && $get_computation_note['date_of_representation'] != '9999-01-01')?display_date($get_computation_note['date_of_representation'],'yy-mm-dd'):$refiling_date;
	$date_of_return = (isset($get_computation_note['date_of_return']) && !empty($get_computation_note['date_of_return']) && $get_computation_note['date_of_return'] != '9999-01-01')?display_date($get_computation_note['date_of_return'],'yy-mm-dd'):'';
	$intimation_defects_dt = (isset($get_computation_note['intimation_defects_dt']) && !empty($get_computation_note['intimation_defects_dt']) && $get_computation_note['intimation_defects_dt'] != '9999-01-01')?display_date($get_computation_note['intimation_defects_dt'],'yy-mm-dd'):'';
	$date_of_scrutiny = (isset($get_computation_note['date_of_scrutiny']) && !empty($get_computation_note['date_of_scrutiny']) && $get_computation_note['date_of_scrutiny'] != '9999-01-01')?display_date($get_computation_note['date_of_scrutiny'],'yy-mm-dd'):'';
	$presentation_dt = (isset($get_computation_note['presentation_dt']) && !empty($get_computation_note['presentation_dt']) && $get_computation_note['presentation_dt'] != '9999-01-01')?display_date($get_computation_note['presentation_dt'],'yy-mm-dd'):'';
	$delay_remarks = (isset($get_computation_note['delay_remarks']) && !empty($get_computation_note['delay_remarks']))?$get_computation_note['delay_remarks']:'';
	$connected_ias = (isset($get_computation_note['connected_ias']) && !empty($get_computation_note['connected_ias']))?explode(',',$get_computation_note['connected_ias']):'';
	$caveat_remark = (isset($get_computation_note['caveat_remark']) && !empty($get_computation_note['caveat_remark']))?$get_computation_note['caveat_remark']:'';
	$delay_ia_efiling_date = (isset($get_computation_note['delay_ia_efiling_date']) && !empty($get_computation_note['delay_ia_efiling_date']) && $get_computation_note['delay_ia_efiling_date'] != '9999-01-01')?display_date($get_computation_note['delay_ia_efiling_date'],'yy-mm-dd'):'';
	$delay_ia_remarks = (isset($get_computation_note['delay_ia_remarks']) && !empty($get_computation_note['delay_ia_remarks']))?$get_computation_note['delay_ia_remarks']:'';
	$entry_date_comp = (isset($get_computation_note['entry_date']) && !empty($get_computation_note['entry_date']))?$get_computation_note['entry_date']:'';
	if($date_of_scrutiny == ''){
		$scruitny_details = get_scrutiny_detail($db,$schemas,$column='notification_date',$filing_no);
		if(!empty($scruitny_details)){
			$scruitny_details = array_shift($scruitny_details);
			$date_of_scrutiny = display_date($scruitny_details['notification_date'],'yy-mm-dd');
		}
	
	$limitation_days = 90;
	
		
	//}
}

if(empty($delay_ia_efiling_date)){
	$delay_ia_efiling_date = filed_date($db,$filing_no);
	$delay_ia_efiling_date = display_date(date('Y-m-d',strtotime($delay_ia_efiling_date)),'yy-mm-dd');
}
	
	
if (!in_array($case_type, $main_case_type))
{
 $common_placeholder = 'NA';
}else{
$common_placeholder = '';
}
?>

<form class="form-horizontal" method='POST' id='submit_computation_note'>
    <div class='table-responsive'>
        <table class='table table-bordered'>
            <input type='hidden' name='url' id='url' value='<?php echo $url; ?>'>
            <input type='hidden' name='filing_no' id='filing_no' value='<?php echo $filing_no; ?>'>
            <input type='hidden' name='case_type' id='case_type' value='<?php echo $case_type; ?>'>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Limitation days</b></em></td>
                <td id='act'>
                    <label id='limitation_days'><?php echo $limitation_days; ?></label>
                </td>
            </tr>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Date Of impugned orders</td>
                <td>
                    <?php if(empty($get_impugned_order_details)) { 
						foreach($act_impuged_order as $k=>$order_details){
							$order_type = '';
							$snn = $k+1;
							$impugned_order_dt = (!empty($order_details['impugned_order_date']))?$order_details['impugned_order_date']:'';
							if($impugned_order_dt != ''){
							list($d,$m,$y) = explode('-',$impugned_order_dt);
							$impugned_order_dt = $d.'/'.$m.'/'.$y;
							}
							$order_type = get_order_type($db,$order_details['impugned_order_date'],$filing_no);
					?>
                    <input type='text' name='impugned_order_dt[]' id='impugned_order_dt_<?php echo $snn; ?>'
                        autocomplete='off' class='form-control datepicker impugned_order_dt' style="margin-bottom:5px;"
                        value='<?php echo $impugned_order_dt; ?>'><span><?php echo $order_type; ?></span>
                    <?php  } } else {
							foreach($get_impugned_order_details as $k=>$order_details){
								$order_type = '';
							$snn = $k+1;
							$impugned_order_dt = (!empty($order_details['impugned_order_dt']) && $order_details['impugned_order_dt'] != '9999-01-01')?display_date($order_details['impugned_order_dt'],'yy-mm-dd'):'';
							list($y,$m,$d) = explode('-',$order_details['impugned_order_dt']);
							$new_date = $d.'-'.$m.'-'.$y;
							$order_type = get_order_type($db,$new_date,$filing_no);
					?>
                    <input type='text' name='impugned_order_dt[]' id='impugned_order_dt_<?php echo $snn; ?>'
                        autocomplete='off' class='form-control datepicker impugned_order_dt' style="margin-bottom:5px;"
                        value='<?php echo $impugned_order_dt; ?>'><span><?php echo $order_type; ?></span>
                    <?php  }
						}?>
                </td>
            </tr>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Date from which limitation computed</td>
                <td>
                    <?php if(empty($get_impugned_order_details)) {
						foreach($act_impuged_order as $k=>$order_details){
							$snn = $k+1;
							$impugned_order_dt = (!empty($order_details['impugned_order_date'])  && $order_details['impugned_order_date'] != '9999-01-01')?display_date($order_details['impugned_order_date'],'yy-mm-dd'):'';
							if($impugned_order_dt != ''){
								$limitation_computed_dt = date('d/m/Y', strtotime($impugned_order_dt . ' +1 day'));
							}
							if (!in_array($case_type, $main_case_type))
							{
							 $limitation_computed_dt = '';
							}
					?>
                    <input type='text' name='limitation_computed_dt[]' id='limitation_computed_dt_<?php echo $snn; ?>'
                        autocomplete='off' class='form-control datepicker limitation_computed_dt'
                        style="margin-bottom:5px;" value='<?php echo $limitation_computed_dt; ?>'>
                    <?php  } } else {
							foreach($get_impugned_order_details as $k=>$order_details){
							$snn = $k+1;
							$limitation_computed_dt = (!empty($order_details['limitation_computed_dt'])  && $order_details['limitation_computed_dt'] != '9999-01-01')?display_date($order_details['limitation_computed_dt'],'yy-mm-dd'):'';
					?>
                    <input type='text' name='limitation_computed_dt[]' id='limitation_computed_dt_<?php echo $snn; ?>'
                        autocomplete='off' class='form-control datepicker limitation_computed_dt'
                        style="margin-bottom:5px;" value='<?php echo $limitation_computed_dt; ?>'>
                    <?php  }
						}?>
                </td>
            </tr>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Date on which limitation expires/expired</td>
                <td>
                    <?php if(empty($get_impugned_order_details)) { 
						foreach($act_impuged_order as $k=>$order_details){
							$snn = $k+1;
							$impugned_order_dt = (!empty($order_details['impugned_order_date'])  && $order_details['impugned_order_date'] != '9999-01-01')?display_date($order_details['impugned_order_date'],'yy-mm-dd'):'';
							if($impugned_order_dt != ''){
								$limit_days = $limitation_days;
								$limitation_expires_dt = date('d/m/Y', strtotime($impugned_order_dt . ' +'.$limit_days.' day'));
								//$limitation_expires_dt =  date('d/m/Y', strtotime($limitation_computed_dt . ' +'.$limitation_days.' day'));
							}
							if (!in_array($case_type, $main_case_type))
							{
							 $limitation_expires_dt = '';
							}
					?>
                    <input type='text' name='limitation_expires_dt[]' id='limitation_expires_dt_<?php echo $snn; ?>'
                        autocomplete='off' class='form-control datepicker limitation_expires_dt'
                        style="margin-bottom:5px;" value='<?php echo $limitation_expires_dt; ?>'>
                    <?php  } } else {
							foreach($get_impugned_order_details as $k=>$order_details){
							$snn = $k+1;
							$limitation_expires_dt = (!empty($order_details['limitation_expires_dt'])  && $order_details['limitation_expires_dt'] != '9999-01-01')?display_date($order_details['limitation_expires_dt'],'yy-mm-dd'):'';
					?>
                    <input type='text' name='limitation_expires_dt[]' id='limitation_expires_dt_<?php echo $snn; ?>'
                        autocomplete='off' class='form-control datepicker limitation_expires_dt'
                        style="margin-bottom:5px;" value='<?php echo $limitation_expires_dt; ?>'>
                    <?php  }
						}?>
                </td>
            </tr>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Delay, if any or any remarks</td>
                <td><input type='text' name='delay_remarks' id='delay_remarks' class='form-control' autocomplete='off'
                        value='<?php echo $delay_remarks; ?>'></td>
            </tr>


            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Date of efiling </td>
                <td><input type='text' name='delay_ia_efiling_date' id='delay_ia_efiling_date'
                        class='form-control datepicker' disabled="disabled" autocomplete='off'
                        value='<?php echo $delay_ia_efiling_date; ?>'></td>
            </tr>

            <tr>
                <td><?php echo $sn++; ?></td>
                <td>If any remarks in e-filing</td>
                <td><input type='text' name='delay_ia_remarks' id='delay_ia_remarks' class='form-control'
                        autocomplete='off' value='<?php echo $delay_ia_remarks; ?>'></td>
            </tr>

            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Date of presentation (Hardcopy, if any)</td>
                <td><input type='text' name='presentation_dt' placeholder='<?php echo $common_placeholder; ?>'
                        id='presentation_dt' class='form-control datepicker' autocomplete='off'
                        value='<?php echo $presentation_dt; ?>'></td>
            </tr>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Date of scrutiny</td>
                <td><input type='text' name='date_of_scrutiny' id='date_of_scrutiny' class='form-control datepicker'
                        autocomplete='off' value='<?php echo $date_of_scrutiny; ?>'></td>
            </tr>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Date of intimation of defects/ Date of return</td>
                <td><input type='text' name='intimation_defects_dt' placeholder='<?php echo $common_placeholder; ?>'
                        id='intimation_defects_dt' class='form-control datepicker' autocomplete='off'
                        value='<?php echo $intimation_defects_dt; ?>'></td>
            </tr>
            <?php if(!empty($entry_date_comp) && $entry_date_comp <= '2023-07-12'){ ?>
            <tr>
                <td><?php echo $entry_date_comp.$sn++; ?></td>
                <td>Date of return</td>
                <td><input type='text' name='date_of_return' placeholder='' id='date_of_return'
                        class='form-control datepicker' autocomplete='off' value='<?php echo $date_of_return; ?>'></td>
            </tr>
            <?php } ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Date of re-filing</td>
                <td><input type='text' name='date_of_representation' placeholder='<?php echo $common_placeholder; ?>'
                        id='date_of_representation' class='form-control datepicker' autocomplete='off'
                        value='<?php echo $date_of_representation; ?>'></td>
            </tr>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Delay in any re-presentation, if any or any remarks.</td>
                <td><input type='text' name='delay_represent_remarks' placeholder='<?php echo $common_placeholder; ?>'
                        id='delay_represent_remarks' class='form-control' autocomplete='off'
                        value='<?php echo $delay_represent_remarks; ?>'></td>
            </tr>

            <tr>
                <td colspan='3' align='center'>
                    <button type='button' class='btn btn-sm btn-success' name='comp_note_btn'
                        id='submit_computation_note_btn'>Generate</button>
                    <button type='button' class='btn btn-sm btn-success' name='comp_note_draft_btn'
                        id='submit_computation_draft_note_btn'>Save as draft</button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>

                </td>
            </tr>
        </table>
    </div>
</form>

<?php }

if($type == 'save_note'){
	try{	  
		$db->beginTransaction();
		$case_type = $data['case_type'];
		$filing_no = $data['filing_no'];
		$impugned_order_dt = (isset($data['impugned_order_dt']) && (!empty($data['impugned_order_dt']) || !empty($data['impugned_order_dt'][0])))?$data['impugned_order_dt']:'';
		$limitation_computed_dt = (isset($data['limitation_computed_dt']) && !empty($data['limitation_computed_dt']))?$data['limitation_computed_dt']:'';
		$limitation_expires_dt = (isset($data['limitation_expires_dt']) && !empty($data['limitation_expires_dt']))?$data['limitation_expires_dt']:'';
		$limitation_days = (isset($data['limitation_days']) && !empty($data['limitation_days']))?$data['limitation_days']:'';
		$caveat_filed = (isset($data['caveat_filed']) && !empty($data['caveat_filed']))?$data['caveat_filed']:0;
		$delay_represent_remarks = (isset($data['delay_represent_remarks']) && !empty($data['delay_represent_remarks']))?$data['delay_represent_remarks']:'';
		$date_of_representation = (isset($data['date_of_representation']) && !empty($data['date_of_representation']))?display_date($data['date_of_representation'],'dd/mm/yy'):'9999-01-01';
		$date_of_return = (isset($data['date_of_return']) && !empty($data['date_of_return']))?display_date($data['date_of_return'],'dd/mm/yy'):'9999-01-01';
		$intimation_defects_dt = (isset($data['intimation_defects_dt']) && !empty($data['intimation_defects_dt']))?display_date($data['intimation_defects_dt'],'dd/mm/yy'):'9999-01-01';
		$date_of_scrutiny = (isset($data['date_of_scrutiny']) && !empty($data['date_of_scrutiny']))?display_date($data['date_of_scrutiny'],'dd/mm/yy'):'9999-01-01';
		$presentation_dt = (isset($data['presentation_dt']) && !empty($data['presentation_dt']))?display_date($data['presentation_dt'],'dd/mm/yy'):'9999-01-01';
		$delay_remarks = (isset($data['delay_remarks']) && !empty($data['delay_remarks']))?$data['delay_remarks']:'';
		$connected_ias = (isset($data['ias']) && !empty($data['ias']))?$data['ias']:'';
		$caveat_remark = (isset($data['caveat_remark']) && !empty($data['caveat_remark']))?$data['caveat_remark']:'';
		$delay_ia_efiling_date = (isset($data['delay_ia_efiling_date']) && !empty($data['delay_ia_efiling_date']))?display_date($data['delay_ia_efiling_date'],'dd/mm/yy'):'9999-01-01';
		$delay_ia_remarks = (isset($data['delay_ia_remarks']) && !empty($data['delay_ia_remarks']))?$data['delay_ia_remarks']:'';
		$save_draft = $data['save_draft'];
		if(!empty($connected_ias))
			$all_ias = implode(',',$connected_ias);
		else
			$all_ias = '';
		
		//echo "<pre>"; print_r($impugned_order_dt); 
		
		/* if($limitation_days == ''){
			$response = array( 
				'status' => 0, 
				'message' => 'Limitation days can not be empty' 
				);
				echo json_encode($response); die;
		} */
		$scruitny_details = get_scrutiny_detail($db,$schemas,$column='defects',$filing_no);
		// if(!empty($scruitny_details)){
		// 	$scruitny_details = array_shift($scruitny_details);
		// 	$defects = $scruitny_details['defects'];
		// 	if($defects == 'Y'){
		// 		$response = array( 
		// 		'status' => 0, 
		// 		'message' => 'case is defective' 
		// 		);
		// 		echo json_encode($response); die;
		// 	}
		// }
		$get_computation_note = computation_note_details($db,$schemas,$filing_no);
		if(empty($get_computation_note)){
			$ins_impugned_order = insert_impugned_orders($db,$schemas,$filing_no,$impugned_order_dt,$limitation_computed_dt,$limitation_expires_dt);
			$ins = insert_comp_note($db,$schemas,$table='computational_note',$userid,$username,$filing_no,$case_type,$limitation_days,$caveat_filed,$delay_represent_remarks,$date_of_representation,$date_of_return,$intimation_defects_dt,$date_of_scrutiny,$presentation_dt,$delay_remarks,$all_ias,$caveat_remark,$delay_ia_efiling_date,$delay_ia_remarks,$save_draft);
			$ins_his = insert_comp_note($db,$schemas,$table='computational_note_his',$userid,$username,$filing_no,$case_type,$limitation_days,$caveat_filed,$delay_represent_remarks,$date_of_representation,$date_of_return,$intimation_defects_dt,$date_of_scrutiny,$presentation_dt,$delay_remarks,$all_ias,$caveat_remark,$delay_ia_efiling_date,$delay_ia_remarks,$save_draft);
		
		}else{
			$up_impugned_order = insert_impugned_orders($db,$schemas,$filing_no,$impugned_order_dt,$limitation_computed_dt,$limitation_expires_dt);
			$update = update_comp_note($db,$schemas,$table='computational_note',$userid,$username,$filing_no,$case_type,$limitation_days,$caveat_filed,$delay_represent_remarks,$date_of_representation,$date_of_return,$intimation_defects_dt,$date_of_scrutiny,$presentation_dt,$delay_remarks,$all_ias,$entry_date,$caveat_remark,$delay_ia_efiling_date,$delay_ia_remarks,$save_draft);
		}
		$db->commit();
		if($save_draft == 0)
			$msg_txt = 'Summary note saved as draft';
		else
			$msg_txt ='Summary note generated and case forwarded for case no generation';
		$response = array( 
		'status' => 1, 
		'message' =>  $msg_txt
		);
		echo json_encode($response); die;
	} catch(Exception $e){
		echo $e;
		$db->rollBack();
		$response = array( 
		'status' => 0, 
		'message' => 'some error occurred.' 
		);
		echo json_encode($response); die;
	}
	
	
}

if($type == 'show_note'){
	$get_impugned_order_details = impugned_order_details($db,$schemas,$filing_no);
	$get_computation_note = computation_note_details($db,$schemas,$filing_no);
	$url = $data['url'];
	if(!empty($get_computation_note)){
		$sn = 1;
		$get_computation_note = array_shift($get_computation_note);
		$case_type = $get_computation_note['case_type'];
		$connected_ias = $get_computation_note['connected_ias']; 
		$case_detail = get_case_detail($db,$location_access,$filing_no);
		$listing_court_info = get_first_listing_by_registrar($db,$schemas,$filing_no);
		if(!empty($case_detail)){
			$text = '';
			$case_detail = array_shift($case_detail);
			$tr_short = '';
			if($case_type == '40'){
				$old_case_info = get_old_case_info($db,$filing_no);
				if(!empty($old_case_info)){
					if(!empty($old_case_info)){
						$transfer_case_type = $old_case_info['transfer_case_type'];
						if($transfer_case_type == '32'){
							$tr_short = ' (Company)';
						}else if($transfer_case_type == '33'){
							$tr_short = ' (Ins.)';
						}else if($transfer_case_type == '34'){
							$tr_short = ' (Compt.)';
						}else{
							$tr_short = '';
						}
					}
				}
			}
			if($case_type == '35'){
				$text = "  (".$case_detail['subject_name'].")";
			}
		}
		if (!in_array($case_type, $main_case_type))
			$common_value = 'NA';
		else
			$common_value = '';
				
		$city_query = "select city_name from mater_location_city where city_id = ? ";
		$ins_note =$db->prepare($city_query);
		$ins_note->bindParam(1, $location_access, PDO::PARAM_STR);
		$ins_note->execute();
		$city_name = $ins_note->fetchColumn();
		
		$case_no_query = "select case_no,case_year from $schemas.case_detail where filing_no = ? ";
		$case_no =$db->prepare($case_no_query);
		$case_no->bindParam(1, $filing_no, PDO::PARAM_STR);
		$case_no->execute();
		$get_case_no = $case_no->fetchAll();
		
		if(!empty($get_case_no)) {
			$get_case_no = array_shift($get_case_no);
			$show_main_case_no_in_note = " No.".$get_case_no['case_no']."/".$get_case_no['case_year'];
		} else {
			$show_main_case_no_in_note=" No. ______/".substr($filing_no,-4,4);
		}

		?>
<a href="javascript:void(0);" onClick="return printDiv('printDiv')">Print</a>
<div id="printDiv">
    <div class="table-responsive">
        <table class="table">
            <tr>
                <td colspan='3' align='center'><?php echo strtoupper('GSTAT');?></td>
            </tr>
            <tr>
                <td colspan='3' align='center'><?php echo strtoupper("$city_name");?></td>
            </tr>
            <tr>
                <td colspan='3' align='center'>
                    <?php echo $case_detail['case_type_desc_cis'].$tr_short.  $show_main_case_no_in_note."   , Filing No : ".$filing_no.$text; ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="table-responsive">
        <table class='table table-bordered'>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Date Of impugned orders</td>
                <td><?php 
					foreach($get_impugned_order_details as $key=>$data){
						$get_order_type = '';
						list($y,$m,$d) = explode('-',$data['impugned_order_dt']);
						$new_date = $d.'-'.$m.'-'.$y;
						$get_order_type = get_order_type($db,$new_date,$filing_no);
					echo ($key+1).') '.display_date($data['impugned_order_dt'],'dd.mm.yy').'    <em style=color:red;>'.$get_order_type."</em><br/>"; 
					}
					?>
                </td>
            </tr>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Date from which limitation computed</td>

                <td>
                    <?php 
					if($get_computation_note['limitation_days'] == '90'){
						foreach($get_impugned_order_details as $key=>$data){
							$limit_compute = ($data['limitation_computed_dt'] != '9999-01-01')?display_date($data['limitation_computed_dt'],'dd.mm.yy'):'NA';
						echo ($key+1).') '.$limit_compute."<br/>"; 
						}
					}
					?>
                </td>

            </tr>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Date on which limitation expires/expired</td>

                <td>
                    <?php 
					if($get_computation_note['limitation_days'] == '90'){
						foreach($get_impugned_order_details as $key=>$data){
							$limit_expires = ($data['limitation_expires_dt'] != '9999-01-01')?display_date($data['limitation_expires_dt'],'dd.mm.yy'):'NA';
						echo ($key+1).') '.$limit_expires."<br/>"; 
						}
					}
					?>
                </td>

            </tr>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Delay, if any or any remarks</td>
                <td><?php echo $get_computation_note['delay_remarks']; ?></td>
            </tr>

            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Date of efiling</td>
                <td><?php echo display_date($get_computation_note['delay_ia_efiling_date'],'dd.mm.yy'); ?></td>
            </tr>


            <tr>
                <td><?php echo $sn++; ?></td>
                <td>If any remarks in e-filing</td>
                <td><?php echo $get_computation_note['delay_ia_remarks']; ?></td>
            </tr>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Date of presentation (Hardcopy, if any)</td>
                <td><?php echo (!empty($get_computation_note['presentation_dt']) && $get_computation_note['presentation_dt'] != '9999-01-01')?display_date($get_computation_note['presentation_dt'],'dd.mm.yy'):$common_value; ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Date of scrutiny</td>
                <td><?php echo display_date($get_computation_note['date_of_scrutiny'],'dd.mm.yy'); ?></td>
            </tr>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Date of intimation of defects/ Date of return</td>
                <td><?php echo (!empty($get_computation_note['intimation_defects_dt']) && $get_computation_note['intimation_defects_dt'] != '9999-01-01')?display_date($get_computation_note['intimation_defects_dt'],'dd.mm.yy'):$common_value; ?>
                </td>
            </tr>
            <?php if(!empty($get_computation_note['entry_date']) && $get_computation_note['entry_date'] <= '2023-07-12') { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Date of return</td>
                <td><?php echo (!empty($get_computation_note['date_of_return']) && $get_computation_note['date_of_return'] != '9999-01-01')?display_date($get_computation_note['date_of_return'],'dd.mm.yy'):$common_value; ?>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Date of re-filing</td>
                <td><?php echo (!empty($get_computation_note['date_of_representation']) && $get_computation_note['date_of_representation'] != '9999-01-01')?display_date($get_computation_note['date_of_representation'],'dd.mm.yy'):$common_value; ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>Delay in any re-presentation, if any or any remarks.</td>
                <td><?php echo (!empty($get_computation_note['delay_represent_remarks']))?$get_computation_note['delay_represent_remarks']:$common_value; ?>
                </td>
            </tr>

            <tr>

            <tr style="text-align:right;">
                <td>For Listing</td>
                <td>
                    <?php 
							echo (!empty($listing_court_info['first_listing_date']) && $listing_court_info['first_listing_date'] != '9999-01-01')?display_date($listing_court_info['first_listing_date'],'yy-mm-dd'):'';
						?>
                </td>
                <td>
                    <?php echo $get_computation_note['username']; ?>
                </td>
            </tr>


            <?php } ?>
        </table>

    </div>
</div>
<?php }

if($type == 'get_remark_form'){
	$case_type = $data['case_type'];
	$url = $data['url'];
	$remarks = get_remarks($db,$schemas,$filing_no);
	if(!empty($remarks)){ ?>
<div class="table-responsive">
    <table class='table table-bordered'>
        <tr>
            <td colspan='2' align='center'><b>View Remarks</b></td>
        </tr>
        <?php
					foreach($remarks as $k=>$remark){
						echo "<tr>";
						echo "<td>".display_date($remark['enrty_date'],'yy-mm-dd')."</td>";
						echo "<td>$remark[remarks]</td>";
						echo "</tr>";
					}
				?>
    </table>
</div>
<?php	} ?>
<div class="table-responsive">
    <form method='POST'>
        <input type='hidden' name='url' id='url' value='<?php echo $url; ?>'>
        <input type='hidden' name='filing_no' id='filing_no' value='<?php echo $filing_no; ?>'>
        <input type='hidden' name='case_type' id='case_type' value='<?php echo $case_type; ?>'>
        <table class="table table-bordered">
            <tr>
                <td>Remark</td>
                <td>
                    <textarea name='remark' id='remark' cols='40' rows='60'></textarea>
                </td>
            </tr>
            <tr>
                <td>Approve/Disapprove (if approved than case is ready for case number generation or if disapprove than
                    case will return to scrutiny clerk for correction remarked by you)</td>
                <td>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="is_approved" value='1'>Yes
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="is_approved" value='0'>No
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan='2' align='center'>
                    <button type='button' class='btn btn-sm btn-success' name='remark_btn' id='remark_btn'>Save</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </td>
            </tr>
        </table>
    </form>
</div>
<?php  die; }

if($type == 'save_remark'){
	$remark = trim($data['remark']);
	$is_approved = (isset($data['is_approved']) && $data['is_approved'] != '')?$data['is_approved']:0;
	$is_case_no_generate = (isset($data['is_generate_case_no']) && $data['is_generate_case_no'] != '')?$data['is_generate_case_no']:0;
	$is_delay_in_refiling = (isset($data['is_delay_in_refiling']) && $data['is_delay_in_refiling'] != '')?$data['is_delay_in_refiling']:0;
	//echo "<pre>"; print_r($data); die;
	try{
	$db->beginTransaction(); 
	$get_computation_note = computation_note_details($db,$schemas,$filing_no);
	$get_computation_note = array_shift($get_computation_note);
	$connected_ias = $get_computation_note['connected_ias']; 
	
	if(!empty($remark)){
		$ins_remark = save_remark($db,$schemas,$filing_no,$remark,$userid,$username);
	}
	$update_comp_note = approve_comp_note($db,$schemas,$filing_no,$is_approved,$in_registrar = 0,$userid,$username,$entry_date);
	 if(!empty($connected_ias)){ 
		$conn_ias = explode(',',$connected_ias);
		foreach($conn_ias as $k=>$ia_filing_no){
			$update_comp_note = approve_comp_note($db,$schemas,$ia_filing_no,$is_approved,$in_registrar=0,$userid,$username,$entry_date);
		}
	}
	if($is_approved == '1'){
		$case_no_list = '';
		$listing_date = (!empty($data['listing_date']))?display_date($data['listing_date'],'dd/mm/yy'):'9999-01-01';
		$court_no = (!empty($data['court_no']))?$data['court_no']:0;
		if($is_delay_in_refiling == '1'){
			$listing_date_main_case = '9999-01-01';
			$court_no_main_case = 0;
		}else{
			$listing_date_main_case = $listing_date;
			$court_no_main_case = $court_no;
		}
		
		$update_scruitny = update_scrutiny_is_approved($db,$schemas,$filing_no,$is_approved,$userid,$username,$entry_date,$listing_date_main_case,$court_no_main_case);
		if($is_case_no_generate == '1'){
			$get_case_detail = get_completed_case_detail($db,$location_access,$filing_no);
			if(!empty($get_case_detail)){
				$get_case_detail = array_shift($get_case_detail);
				$pet_name = get_party($db,$filing_no,$party_flag='P',$party_serial_no='1');
				$res_name = get_party($db,$filing_no,$party_flag='R',$party_serial_no='1');
				$case_year = date('Y');
				$last_reg_no = get_last_reg_no($db,$schemas,$get_case_detail['case_type_nclat'],$case_year);
				$reg_no = $last_reg_no+1;
				$regis_date = $entry_date;
				$save_into_case_detail = save_case_detail($db,$schemas,$filing_no,$get_case_detail,$pet_name,$res_name,$case_year,$reg_no,$regis_date,$userid,$location_access);
				$update_ecase_detail = update_e_case_detail($db,$filing_no,$location_access);
				$update_reg_no = update_reg_no($db,$schemas,$reg_no,$get_case_detail['case_type_nclat'],$case_year);
				
				$case_num=get_case_no($db,$schemas,$filing_no,'sms');
				$case_no_list .= 'case no of '.$filing_no.' is : '.$case_num;

				$subject="Status of Documents filed under diary no ".$filing_no;

				$email_text="The document(s) submitted under diary no ".$filing_no." and case no ".$case_num." are marked as defect free . This is a computer generated message, Please do not reply "  ;
				$msg="The Document(s) submitted under diary no ".$filing_no." and case no ".$case_num." are marked as defect free.";
				$send_mail = fn_sms($db, '1', '', $filing_no, $subject, $msg, $email_text);
				
			}
		}
		if(!empty($connected_ias)){
			foreach($conn_ias as $k=>$ia_filing_no){
				$update_scruitny = update_scrutiny_is_approved($db,$schemas,$ia_filing_no,$is_approved,$userid,$username,$entry_date,$listing_date,$court_no);
				if($is_case_no_generate == '1'){
					$get_case_detail = get_completed_case_detail($db,$location_access,$ia_filing_no);
					if(!empty($get_case_detail)){
						$get_case_detail = array_shift($get_case_detail);
						$pet_name = get_party($db,$ia_filing_no,$party_flag='P',$party_serial_no='1');
						$res_name = get_party($db,$ia_filing_no,$party_flag='R',$party_serial_no='1');
						$case_year = date('Y');
						$last_reg_no = get_last_reg_no($db,$schemas,$get_case_detail['case_type_nclat'],$case_year);
						$reg_no = $last_reg_no+1;
						$regis_date = $entry_date;
						$save_into_case_detail = save_case_detail($db,$schemas,$ia_filing_no,$get_case_detail,$pet_name,$res_name,$case_year,$reg_no,$regis_date,$userid,$location_access);
						$update_ecase_detail = update_e_case_detail($db,$ia_filing_no,$location_access);
						$update_reg_no = update_reg_no($db,$schemas,$reg_no,$get_case_detail['case_type_nclat'],$case_year);
						
						$case_num=get_case_no($db,$schemas,$ia_filing_no,'sms');
						$case_no_list .= ', case no of '.$ia_filing_no.' is : '.$case_num;

						$subject="Status of Documents filed under diary no ".$ia_filing_no;

						$email_text="The document(s) submitted under diary no ".$ia_filing_no." and case no ".$case_num." are marked as defect free . This is a computer generated message, Please do not reply "  ;
						$msg="The Document(s) submitted under diary no ".$ia_filing_no." and case no ".$case_num." are marked as defect free.";
						$send_mail = fn_sms($db, '1', '', $ia_filing_no, $subject, $msg, $email_text);
						
					}
				}
			}
		}
	}
	$db->commit();
	$response = array( 
	"status" => 1, 
	"message" => "Updated Successfully : $case_no_list" 
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
}

	if($type == 'generate_case_no'){
	try{
		$case_year = date('Y');
		$case_type_desc=$db->prepare("select case_type_nclat from e_case_detail where filing_no=? ");
		$case_type_desc->bindParam(1, $filing_no, PDO::PARAM_STR);
		$case_type_desc->execute();
		$case_type = $case_type_desc->fetchColumn();
		$upr= $db->prepare("select under_processing from $schemas.case_type_reg where reg_year=? and case_type=?");
		 $upr->bindParam(1, $case_year, PDO::PARAM_STR);
		 $upr->bindParam(2, $case_type, PDO::PARAM_STR);
		 $upr->execute();
		 $is_under_process = $upr->fetchColumn();
		 if($is_under_process == '1'){
			$response = array( 
				"status" => 1, 
				"message" => "Another request is in process"
				);
				echo json_encode($response); die;
		 }
		 else{
			$one = 1;
			$upr_up= $db->prepare("update $schemas.case_type_reg set under_processing = ? where reg_year=? and case_type=?");
			$upr_up->bindParam(1, $one, PDO::PARAM_STR);
			$upr_up->bindParam(2, $case_year, PDO::PARAM_STR);
			$upr_up->bindParam(3, $case_type, PDO::PARAM_STR);
			$upr_up->execute();
		 }
		$db->beginTransaction(); 
		$get_case_detail = get_completed_case_detail($db,$location_access,$filing_no);
		if(!empty($get_case_detail)){
			$get_case_detail = array_shift($get_case_detail);
			$pet_name = get_party($db,$filing_no,$party_flag='P',$party_serial_no='1');
			$res_name = get_party($db,$filing_no,$party_flag='R',$party_serial_no='1');
			$case_year = date('Y');
			$bench_detail = get_bench_name($db,$location_access);
			$bench_name = $bench_detail['city_name'];
			$state_name = $bench_detail['state_name'];
			$last_reg_no = get_last_reg_no($db,$schemas,$get_case_detail['case_type_nclat'],$case_year);
			$reg_no = $last_reg_no+1;
			$regis_date = $entry_date;
			$save_into_case_detail = save_case_detail($db,$schemas,$filing_no,$get_case_detail,$pet_name,$res_name,$case_year,$reg_no,$regis_date,$userid,$location_access);
			$update_ecase_detail = update_e_case_detail($db,$filing_no,$location_access);
			$update_reg_no = update_reg_no($db,$schemas,$reg_no,$get_case_detail['case_type_nclat'],$case_year);
			
			$case_num=get_case_no($db,$schemas,$filing_no,'sms');

			$subject="Status of Documents filed under diary no ".$filing_no;

			// apl-02 form start

                    $query = "select ecd.e_reference_no,ecd.dt_of_filing::timestamp::date as filing_date, esu.name as filed_by from e_case_detail as ecd 
                    left join loginmodel as lm on lm.loginid = ecd.loginid
                    left join e_sign_up as esu on esu.loginidgenerated = lm.loginidgenerated 
                    where ecd.filing_no = ?";
                    $filing_data_query = $db->prepare($query);
                    $filing_data_query->bindParam(1, $filing_no, PDO::PARAM_STR);
                    $filing_data_query->execute();
                    $filing_data = $filing_data_query->fetch();
                    if(!empty($filing_data)){
                        $e_reference_no = $filing_data['e_reference_no'];
                        $filing_date = date('d/m/Y',strtotime($filing_data['filing_date']));
                        $filed_by = $filing_data['filed_by'];
                    }

                    $query = "select gst_number,applent_name,crn_number ,order_number from e_order_details where filing_no = ?";
                    $gst_detail = $db->prepare($query);
                    $gst_detail->bindParam(1,$filing_no,PDO::PARAM_STR);
                    $gst_detail->execute();
                    $gst_data = $gst_detail->fetch();
                    $gst_no = $gst_data['gst_number'];
                    $applicant_name = $gst_data['applent_name'];
                    $crn_number = $gst_data['crn_number'];
                    $order_number = $gst_data['order_number'];

                    $query = "select nclt_txn_id,txn_amount from txn_details where filing_no = ? order by created_at limit 1";
                    $transaction_query = $db->prepare($query);
                    $transaction_query->bindParam(1,$filing_no,PDO::PARAM_STR);
                    $transaction_query->execute();
                    $transaction_data = $transaction_query->fetch();
                    $txn_id = $transaction_data['nclt_txn_id'];
                    $txn_amount = $transaction_data['txn_amount'];
                


               //$pdf_html = '<div style="text-align:center;"><p>Form GST APL-02A</p><p>Acknowledgment for submission of Appeal/Application</p><p>'. $applicant_name. ' '.$gst_no.' '. $filing_date.'</p><p><b class="text-success">Your appeal has been successfully filed against :</b><span>'.$crn_number.'</span></p></div><table style="border:1px solid #000; border-collapse:collapse; margin:20px auto;" cellpadding="5"><tbody><tr><td style="border:1px solid #000;"><b>GSTIN/Temporary ID/UIN/ENR - </b></td><td style="border:1px solid #000;"><span>'. $gst_no.'</span></td></tr><tr><td style="border:1px solid #000;"><b>Date of filing - </b></td><td style="border:1px solid #000;"><span>'.$filing_date.'</span></td></tr><tr><td style="border:1px solid #000;"><b>Name of the person filing the appeal - </b></td><td style="border:1px solid #000;"><span>'.$filed_by.'</span></td></tr></tbody></table><div style="text-align:center;"><p><b>Final Acknowledgement</b></p><p>Your Appeal/Application has been successfully filed against '.$crn_number.' dated '.$filing_date.'</p><p><b>Date of acceptance : '.date('d/m/Y',strtotime($regis_date)) .'</b></p></div> <table style="width:100%"><tr><td><b>Date of appearance: </b></td><td style="text-align:right"><b>Time: '.date('h:i A') .'</b></td></tr><tr><td><b>Court Number: '.$_SESSION[user_court] .'</b></td><td style="text-align:right"><b>Bench: '.$becnh_name .'</b></td></tr></table>';				
					

               $pdf_html = '<div style="text-align:center;"><p>Form GST APL-02 Part B</p><p><b>Final Acknowledgement for registration of Appeal/Application</b></p><p>Your Appeal/Application has been successfully filed/registered against '.$crn_number.' dated '.$filing_date.'</p></div><table style="width:100%"><tr><td colspan="2"><b>GSTIN/Temporary ID/UIN/ENR : '.$gst_no.'</b></td></tr><tr><td colspan="2"><b>Case Registration Number : '.$case_num.'</b></td></tr><tr><td colspan="2"><b>Date of acceptance : '.date('d/m/Y',strtotime($regis_date)) .'</b></td></tr><tr><td><b>Date of appearance: </b></td><td style="text-align:right"><b>Time: '.date('h:i A') .'</b></td></tr><tr><td><b>Court Number: </b></td><td style="text-align:right"><b>Bench: '.$bench_name .'</b></td></tr></table>';
         


             	$time = time();				
				$pdf_file_name=$filing_no."-apl02A-".$time;
			  $filename=$pdf_file_name.".pdf";
			  $filename_sms = $pdf_file_name;
			 $dompdf->loadHtml($pdf_html);
			  $dompdf->setPaper('A4');
			  $dompdf->render();
			  $outputff = $dompdf->output();
			  $upload_dir = "/Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/apl02A/$filing_no";
			  $pp_path = $upload_dir."/$filename";
			  $save_path = $upload_dir."/$filename";
			  if (!file_exists($save_path)) {
					mkdir($upload_dir, 0777, true);
	                   	}
			 $save_file = file_put_contents($pp_path, $outputff);
			  
			  $s3Service = new S3Service();
			  $save_apl = $s3Service->uploadDynamicFile($outputff, $save_path);

			  if(!$save_apl)
			  {
				$db->rollBack();
				$zero = 0;
				$upr_up= $db->prepare("update $schemas.case_type_reg set under_processing = ? where reg_year=? and case_type=?");
				$upr_up->bindParam(1, $zero, PDO::PARAM_STR);
				$upr_up->bindParam(2, $case_year, PDO::PARAM_STR);
				$upr_up->bindParam(3, $case_type, PDO::PARAM_STR);
				$upr_up->execute();
				$response = array( 
				'status' => 0, 
				'message' => 'some error occurred in saving apl-02' 
				);
				echo json_encode($response); die;
			  }

			  $update_case_no_generation = update_case_no_generation($db,$schemas,$filing_no,$save_path);


			  $pdfpath = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/gstat/scrutiny/readpdf_file.php?path='. $save_path;

			$mail_message =  '.  <a download="download" href="' . $pdfpath . '" target = "_blank"> Click here </a>';

			//apl-02 from end

			// $email_text="The document(s) submitted under diary no ".$filing_no." and case no ".$case_num." are marked as defect free . This is a computer generated message, Please do not reply "  ;
			// $msg="The Document(s) submitted under diary no ".$filing_no." and case no ".$case_num." are marked as defect free.";
			
			$email_text="Your appeal/application has been successfully registered in ".$bench_name." ".$state_name." and the case number of your application is ".$case_num.". This is a computer-generated e-mail, please do not reply."  ;
			$msg="Your appeal/application has been successfully registered in ".$bench_name." ".$state_name." and the case number of your application is ".$case_num.". This is a computer-generated message, please do not reply. GSTAT-GSTN";
			$send_mail = fn_sms($db, '1', $pp_path, $filing_no, $subject, $msg, $email_text);

			$pdf_hash = hash_file('sha256', $pp_path);

			$apl_form= $db->prepare("update e_case_detail set apl_02b_form_path = ?, apl_02b_accept_reject = 1, pdf_hash = ?  where filing_no = ?");
				$apl_form->bindParam(1, $pp_path, PDO::PARAM_STR);
				$apl_form->bindParam(2, $pdf_hash, PDO::PARAM_STR);
				$apl_form->bindParam(3, $filing_no, PDO::PARAM_STR);
				$apl_form->execute();

			$today_date = date('Y-m-d');
			$doctype = 8;
			$subdoctype = 178;
			$docum_type = "APL02_APPROVED";
			$party_name = "apl02";
			$doc_level = 9;
			$save_doc = save_document_uplaod($db,$doctype,$pp_path,$userid,$subdoctype,$e_reference_no,$filename,$docum_type,'',$filename,$filing_no,true,1,$party_name,$today_date,'A');	
			removePath($pp_path);		
		}
		if($send_mail){
			$response = array( 
		"status" => 1, 
		"message" => "Case number generated : $case_num"
		);
		}
		$db->commit();
		$url = "http://10.193.85.11/efiling/getdataapl02b.drt?filingNo=$filing_no&schema=$schemas";
		callApiAsync($url);	
		echo json_encode($response); die;
	}catch(Exception $e){
		echo $e->getMessage();
		$db->rollBack();
		removePath($pp_path);
		$zero = 0;
		$upr_up= $db->prepare("update $schemas.case_type_reg set under_processing = ? where reg_year=? and case_type=?");
		$upr_up->bindParam(1, $zero, PDO::PARAM_STR);
		$upr_up->bindParam(2, $case_year, PDO::PARAM_STR);
		$upr_up->bindParam(3, $case_type, PDO::PARAM_STR);
		$upr_up->execute();
		$response = array( 
		'status' => 0, 
		'message' => 'some error occurred.' 
		);
		echo json_encode($response); die;
	}
}

if($type == 'doc_list'){
	$url = $data['pdf_rul'];
	$miscellenous_no = $data['miscellenous_no'];
	$scrutiny = 0;
	$display = 1;
	$st=$db->prepare("select *  from document_upload where filing_no=? and miscellenous_no = ? and scrutiny=? and display=? and doc_flag = ? and miscellaneous_ref_no is not null ");
	 $st->bindParam(1, $filing_no, PDO::PARAM_STR);
	 $st->bindParam(2, $miscellenous_no, PDO::PARAM_STR);
	 $st->bindParam(3, $scrutiny, PDO::PARAM_STR);
	 $st->bindParam(4, $display, PDO::PARAM_STR);
	 $st->bindParam(5, $display, PDO::PARAM_STR);
	 $st->execute();
	 $res = $st->fetchAll(); ?>
<div class='table-responsive'>
    <table class='table table-bordered'>
        <tr>
            <?php if(!empty($res)){
		
		foreach($res as $k=>$rowa)
		{
		    $fil_no=$rowa['filing_no'];
			$sub_doc_type=$rowa['subdoctype'];
			$document_filed_date=$rowa['document_filed_date'];
          	$path =$rowa['fileupload'];    
			$returnfilename =$rowa['returnfilename']; 
			 
			list($returnfilename,$ext)=explode('.',$returnfilename);
			$returnfilename1=$returnfilename;		 

			$stqq = $db->prepare("select e_document_name from e_document_type  where e_document_type=?");
            $stqq->bindParam(1, $sub_doc_type, PDO::PARAM_INT);
            $stqq->execute();
			 $e_document_name_print = $stqq->fetchColumn(); ?>
            <td>
                <?php
				//$path_latest = $rowa['filing_no'] . '**' . $rowa['filename'] . '**' . $rowa['fileupload'] . '**' . $rowa['documentuploadmodelid']; ?>


                <a href="javascript:void(0)"
                    onClick="return viewpdf_new('<?php echo urlencode($path); ?>','<?php echo $url; ?>');"
                    style="cursor: pointer">
                    <font color="#900C3F" size="3">
                        &nbsp;&nbsp;
                        &nbsp;&nbsp;<?php echo $e_document_name_print; ?>
                </a>

            </td>
            <?php	} ?>
        </tr>
    </table>

    <?php	 }  ?>
    <div id='view_pdf' height="500px;">

    </div>
</div>
<?php  }


if($type == 'register_with_defect'){
		try{
			$db->beginTransaction(); 
			$filing_no = $data['filing_no'];
			$case_type = $data['case_type'];
			$get_case_detail = get_completed_case_detail($db,$location_access,$filing_no);
			if(!empty($get_case_detail)){
				$get_case_detail = array_shift($get_case_detail);
				$pet_name = get_party($db,$filing_no,$party_flag='P',$party_serial_no='1');
				$res_name = get_party($db,$filing_no,$party_flag='R',$party_serial_no='1');
				$case_year = date('Y');
				$regis_date = $entry_date;
				$extract_reg_no = substr($filing_no, 10, 6);
				$extract_reg_no = 'D'.$extract_reg_no;
				$datetime = date("d-m-Y h:i:s a");
				
				$save_into_case_detail = save_defective_case_detail($db,$schemas,$filing_no,$get_case_detail,$pet_name,$res_name,$case_year,$extract_reg_no,$regis_date,$userid,$location_access);
				$update_ecase_detail = update_defective_e_case_detail($db,$filing_no,$location_access);
		

				$subject="Case forwarded for listing , diary no ".$filing_no;

				$email_text="Your case with provisional acknowledgement no ".$filing_no." has been marked as list with defect on ".$datetime." . This is a computer-generated message, please do not reply. GSTAT-GSTN "  ;
				
				$msg = "Your case with provisional acknowledgement no ".$filing_no." has been marked as list with defect on ".$datetime." . This is a computer-generated message, please do not reply. GSTAT-GSTN";

				$send_mail = fn_sms($db, '14', '', $filing_no, $subject, $msg, $email_text);
				
			}
			if($send_mail){
				$response = array( 
			"status" => 1, 
			"message" => "Case forwarded for listing, diary no : $filing_no"
			);
			}
			$db->commit();
			
			echo json_encode($response); die;
		}catch(Exception $e){ echo $e;  die;
			$db->rollBack();
			$response = array( 
			'status' => 0, 
			'message' => 'some error occurred.' 
			);
			echo json_encode($response); die;
		}
	}

	if($type == 'reject_case'){
		try{
			$filing_no = $data['filing_no'];
			$case_type = $data['case_type'];
			$reject_type = $data['type'];
			$db->beginTransaction(); 
			$get_case_detail = get_completed_case_detail($db,$location_access,$filing_no);
			if(!empty($get_case_detail)){
				
				$update_ecase_detail = reject_case($db,$filing_no,$reject_type);
				$update_eorder_detail = allow_arn_for_filing($db,$filing_no);
		
				$response = array( 
				"status" => 1, 
				"message" => "Case rejected, diary no : $filing_no"
				);
				
				
			}
			
			$db->commit();
			
			echo json_encode($response); die;
		}catch(Exception $e){ echo $e;  die;
			$db->rollBack();
			$response = array( 
			'status' => 0, 
			'message' => 'some error occurred.' 
			);
			echo json_encode($response); die;
		}
	}

	if($type == 'enable_scrutiny'){
		try{
			$filing_no = $data['filing_no'];
			$db->beginTransaction(); 
			$get_case_detail = get_completed_case_detail($db,$location_access,$filing_no);
			if(!empty($get_case_detail)){
				
				$update_ecase_detail = enable_scrutiny($db,$schemas,$filing_no);
				
				$response = array( 
				"status" => 1, 
				"message" => "Case forwarded to scrutiny reporter, diary no : $filing_no"
				);
				
				
			}
			
			$db->commit();
			
			echo json_encode($response); die;
		}catch(Exception $e){ echo $e;  die;
			$db->rollBack();
			$response = array( 
			'status' => 0, 
			'message' => 'some error occurred.' 
			);
			echo json_encode($response); die;
		}
	}

?>

<?php }else{
	die('can not access');
}


?>
