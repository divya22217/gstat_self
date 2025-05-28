<?php

 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  
include("../db_inc1.php");
$schemas=htmlspecialchars($_SESSION['schema_name']);
$user_id=htmlspecialchars($_SESSION['id']);
$username = htmlspecialchars($_SESSION['user_actual_name']);
$location_id = $_SESSION['location'];
date_default_timezone_set("Asia/Kolkata");
include '../classes/Editcase.class.php';
$edit_Case_obj = new Editcase();
if(($_SESSION['user'])=='')
{
	echo "you Can't access this page";
}else
 {

	 function display_date($date){
		 if($date == '1111-11-11'){
			return '';
		 }
		 list($Y,$m,$d) =explode('-',$date);
		 $returned_date =$d.'/'.$m.'/'.$Y;
		 return $returned_date;
	 }
	 function get_case_detail($db,$schema,$case_no,$case_year,$case_type,$location_id){
		$case_detail = $db->prepare("select cd.filing_no,cd.status,cd.dt_of_filing,cd.case_no,cd.case_year,cd.case_type,
									cd.regis_date,cd.entry_date,cd.main_case_ia_no,
									s.notification_date,cn.entry_date,ecd.defect_date,cn.is_approved,cn.updated_date
									from $schema.case_detail as cd 
									left join $schema.scrutiny as s on s.filing_no = cd.filing_no
									left join $schema.computational_note as cn on cn.filing_no = cd.filing_no
									left join e_case_detail as ecd on ecd.filing_no = cd.filing_no
									where cd.case_type = ? and cd.case_no = ? and cd.case_year = ? and cd.location_code = ?");
		$case_detail->bindParam(1, $case_type, PDO::PARAM_INT);
		$case_detail->bindParam(2, $case_no, PDO::PARAM_INT);
		$case_detail->bindParam(3, $case_year, PDO::PARAM_INT);
		$case_detail->bindParam(4, $location_id, PDO::PARAM_INT);
		$case_detail->execute();
		$case_detail = $case_detail->fetchAll();
		return $case_detail;
	}
	
	function get_short_name($db,$table,$search_column_name,$condtion_column_name,$condtion_column_value){
		$short_name = $db->prepare("select $search_column_name from $table where $condtion_column_name = ? ");
		$short_name->bindParam(1, $condtion_column_value, PDO::PARAM_INT);
		$short_name->execute();
		$short_name = $short_name->fetchColumn();
		return $short_name;
	}
	
	function get_party($db,$filing_no,$party_flag,$party_serial_no=1){
		try {
		$query = "select name from e_cases_party where filing_no = ? and party_flag = ? and party_serial_no = ? limit 1";
		$party = $db->prepare($query);
		$party->bindParam(1, $filing_no, PDO::PARAM_STR);
		$party->bindParam(2, $party_flag, PDO::PARAM_STR);
		$party->bindParam(3, $party_serial_no, PDO::PARAM_STR);
		$party->execute();
		$name = $party->fetchColumn();
		return $name;
		} catch (PDOException $ex) {
			echo $ex;
		}
	}
	
	 function change_case_status($schemas,$db,$filing_no,$status='W',$entry_date,$user_id,$username,$action){
		 $blank = '';
		$query = "INSERT INTO $schemas.case_detail_log(filing_no, case_no, pet_name, pet_address, pet_email, pet_mobile,  pet_phone, pet_fax, petadvname, pet_pin,
					loginid, payment_reference_no,reference_no, schema_id, bench, sub_bench, pet_fathername, pet_occupation, pet_capacity, pet_description,
					res_name, res_address, res_pin,  res_mobile, res_phone, res_fathername, res_occupation, res_description,  res_capacity, res_adv_name, res_email,
					res_fax, amount_payment, from_document, to_document, section, prays, oa_ref_no, status, regis_date, scrutiny_completed, dt_of_filing,
					pet_type, res_type, case_type, entry_date, case_year, filing_year, e_reference_no, pet_adv, res_adv, pet_state, res_state, pet_district,
					res_district, pet_code, res_code, pet_adv_name, level_level, location_code, legal_aid, filing_no_new, filing_no_old, collength,
					ia_flag,main_case_ia_no,court_no,auto_manual,registrar_date,manual_court_no,manual_user_id,manual_date, backlog,is_partially_defective,
					partially_defect_free_date,alter_date, alter_login_id, ip_address, changes_type)
					select  filing_no, case_no, pet_name, pet_address, pet_email, pet_mobile,  pet_phone, pet_fax, petadvname, pet_pin,
					loginid, payment_reference_no,reference_no, schema_id, bench, sub_bench, pet_fathername, pet_occupation, pet_capacity, pet_description,
					res_name, res_address, res_pin,  res_mobile, res_phone, res_fathername, res_occupation, res_description, res_capacity, res_adv_name, res_email,
					res_fax, amount_payment, from_document, to_document, section, prays, oa_ref_no, status, regis_date, scrutiny_completed, dt_of_filing,
					pet_type, res_type, case_type, entry_date, case_year, filing_year, e_reference_no, pet_adv, res_adv, pet_state, res_state, pet_district,
					res_district, pet_code, res_code, pet_adv_name, level_level, location_code, legal_aid, filing_no_new, filing_no_old, collength,
					ia_flag,main_case_ia_no,court_no,auto_manual,registrar_date,manual_court_no,manual_user_id,manual_date, backlog,is_partially_defective,
					partially_defect_free_date, now(), ?, ?, ? from $schemas.case_detail where filing_no=?";
		$insert = $db->prepare($query);
		$insert->bindParam(1, $user_id, PDO::PARAM_STR);
		$insert->bindParam(2, $blank, PDO::PARAM_STR);
		$insert->bindParam(3, $action, PDO::PARAM_STR);
		$insert->bindParam(4, $filing_no, PDO::PARAM_STR);
		$res = $insert->execute();
		if($res){
			$query = "update $schemas.case_detail set status = ? where filing_no = ?";
			$update = $db->prepare($query);
			$update->bindParam(1, $status, PDO::PARAM_STR);
			$update->bindParam(2, $filing_no, PDO::PARAM_STR);
			$update_res = $update->execute();
			
			$query = "update e_case_detail set filing_new = ? where filing_no = ?";
			$up = $db->prepare($query);
			$up->bindParam(1, $status, PDO::PARAM_STR);
			$up->bindParam(2, $filing_no, PDO::PARAM_STR);
			$up_res = $up->execute();
		}
		return $up_res;
	} 
	 
	 $response = array( 
			'status' => 0, 
			'message' => 'Form submission failed, please try again.' 
		);
		$server_date= date('d-m-Y'); //Returns IST 
		if($server_date !='')
		{
			list($day,$month,$year)=explode('-',$server_date);
			 $entry_date=$year."-".$month."-".$day;	
		}
	$data = $_POST;
	$type = $data['type'];

	if($type == 'search_case'){
			$case_no = $data['case_no'];
			$case_year = $data['case_year'];
			$case_type = $data['case_type'];
			if(!is_numeric($case_no) || !is_numeric($case_year) || !is_numeric($case_type) ){
				echo "Invalid Input";
				die;
			}
			$case_detail = get_case_detail($db,$schemas,$case_no,$case_year,$case_type,$location_id);
			if(empty($case_detail)){
				$response = array( 'status' => 0, 'message' => 'Case Not Found');
				echo json_encode($response); die;
			}
			if(!empty($case_detail) && count($case_detail) > 1){
				$response = array( 'status' => 0, 'message' => 'Something went wrong');
				echo json_encode($response); die;
			}
			if(!empty($case_detail) && count($case_detail) == 1){
				$case_detail = array_shift($case_detail);
				/* if($case_detail['status'] == 'D'){
					$response = array( 'status' => 0, 'message' => 'Case is disposed');
					echo json_encode($response); die;
				}else{ */
					$filing_no = $case_detail['filing_no'];
					$case_no = $case_detail['case_no'];
					$case_year = $case_detail['case_year'];
					$case_type = $case_detail['case_type'];
					if(!empty($case_detail['main_case_ia_no'])){
						$party_filing_no = $case_detail['main_case_ia_no'];
					}else{
						$party_filing_no = $case_detail['filing_no'];
					}
					$pet_name = get_party($db,$party_filing_no,'P');
					$res_name = get_party($db,$party_filing_no,'R');
				?>
				 <input type="hidden" value="<?php echo $filing_no; ?>" id="filing_no" name="filing_no">
				 <input type="hidden" value="<?php echo $case_detail['regis_date']; ?>" id="regis_date" name="regis_date">
				 <input type="hidden" value="<?php echo $case_detail['main_case_ia_no']; ?>" id="main_case_filing_no" name="main_case_filing_no">
				 <table id="title" class="table table-hover table-bordered">
					<thead>
						<th>Diary No</th>
						<th>Case No</th>
						<th>Title</th>
						<th>Date Of Filing</th>
						<th>Last Defect Date</th>
						<th>Scrutiny Defect Free Date</th>
						<th>Computation Note Date</th>
						<th>Computation Note Approved Date</th>
						<th>Date Of Registration</th>
						<th>Status</th>
						<th>More Details</th>
						<th>Action</th>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $filing_no; ?></td>
							<td><?php echo get_short_name($db,'case_type','short_name','id',$case_type).'/'.$case_no.'('.get_short_name($db,'mater_location_city','short_name','city_id',$location_id).")/".$case_year; ?></td>
							<td><?php echo $pet_name."  VS  ".$res_name; ?></td>
							<td><?php echo (!empty($case_detail['dt_of_filing']))?date('d/m/Y',strtotime($case_detail['dt_of_filing'])):''; ?></td>
							<td><?php echo (!empty($case_detail['defect_date']))?date('d/m/Y',strtotime($case_detail['defect_date'])):''; ?></td>
							<td><?php echo (!empty($case_detail['notification_date']))?date('d/m/Y',strtotime($case_detail['notification_date'])):''; ?></td>
							<td><?php echo (!empty($case_detail['entry_date']))?date('d/m/Y',strtotime($case_detail['entry_date'])):''; ?></td>
							<td><?php
								if($case_detail['is_approved']){	
									echo (!empty($case_detail['updated_date']))?date('d/m/Y',strtotime($case_detail['updated_date'])):'';
								}
							?></td>
							<td><?php echo (!empty($case_detail['regis_date']))?date('d/m/Y',strtotime($case_detail['regis_date'])):''; ?></td>
							
							<td><?php echo ($case_detail['status'] == 'D')?'Disposed':(($case_detail['status'] == 'W')?'Wrongly Updated':'Pending'); ?></td>
							<td>
								<a  target="_blank" href="https://efiling.nclat.gov.in/previewCIS.drt?filingNo=<?php echo $case_detail['filing_no']; ?>"><font color="#900C3F" size="3">View </a>
							</td>
							<td>
							<?php if($case_detail['status'] == 'P') { ?>
								<button type="button" name="change_status" id="change_status" class="btn btn-sm btn-success" onClick="return update_status('<?php echo $case_detail['filing_no']; ?>');">Set As Wrongly Updated Case</button>
							<?php } ?>
							</td>
						</tr>
					</tbody>
				 </table>
				<?php 
				//}
			}
			die;
		}

		
		if($type == 'change_status'){
		$filing_no = $data['filing_no'];
		$change_status = change_case_status($schemas,$db,$filing_no,$status='W',$entry_date,$user_id,$username,'wrongly updated');
		if($change_status){
			$response = array( 
				'status' => 1, 
				'message' => 'Case set to wrongly updated' 
				);
		}else{
			$response = array( 
				'status' => 0, 
				'message' => 'some error occured' 
				);
		}
		echo json_encode($response); die;
	}
	
	
	echo json_encode($response); die;
 }

?>
