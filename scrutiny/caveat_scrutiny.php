<?php 
 /* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */ 
include("../db_inc1.php");
include("../db_inc2.php");
include_once('../custom/custom_function.php');
$schemas=htmlspecialchars($_SESSION['schema_name']);
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d');
list($year,$month,$date) = explode('-',$server_date);
$notification_date = $date."/".$month."/".$year;
$user_id = htmlspecialchars($_SESSION['id']);
$location_id = $_SESSION['location'];
require "../vendor/autoload.php";
use Dompdf\Dompdf;
$dompdf = new Dompdf();

function scrutiny_details($db,$schemas,$filing_no){
	$caveate=$db->prepare("select * from $schemas.caveat_scrutiny where filing_no = ?");
	$caveate->bindParam(1, $filing_no, PDO::PARAM_STR);
	$caveate->execute();
	$caveate = $caveate->fetchAll();
	return $caveate;
}

function get_no_of_scrutiny_count($db,$schemas,$filing_no){
	$count_scrutiny=$db->prepare("select count_scruitny from $schemas.caveat_scrutiny where filing_no = ? order by id desc limit 1");
	$count_scrutiny->bindParam(1, $filing_no, PDO::PARAM_STR);
	$count_scrutiny->execute();
	$count_scrutiny = $count_scrutiny->fetchColumn();
	return $count_scrutiny;
}

function insert_into_scrutiny($db,$schemas,$table='caveat_scrutiny',$filing_no,$defect_status,$remark,$count_scrutiny,$server_date,$user_id,$level,$save_path){
	if($defect_status == 0){
		$defect_status = 'N';
	}else if($defect_status == 1){
		$defect_status = 'Y';
	}else{
		$defect_status = '';
	}
		$insert =$db->prepare("insert into $schemas.$table (filing_no,scrutiny_level,defect_free,remark,count_scruitny,notification_date,user_id,pdf_path) values
						(?,?,?,?,?,?,?,?)");

		$insert->bindParam(1, $filing_no, PDO::PARAM_STR);
		$insert->bindParam(2, $level, PDO::PARAM_STR);
		$insert->bindParam(3, $defect_status, PDO::PARAM_STR);
		$insert->bindParam(4, $remark, PDO::PARAM_STR);
		$insert->bindParam(5, $count_scrutiny, PDO::PARAM_STR);
		$insert->bindParam(6, $server_date, PDO::PARAM_STR);
		$insert->bindParam(7, $user_id, PDO::PARAM_STR);
		$insert->bindParam(8, $save_path, PDO::PARAM_STR);
		$res = $insert->execute();
		return $res;
}



function caveat_details($dbonline,$filing_no){
	$details=$dbonline->prepare("select * from e_cases_party where filing_no = ?");
	$details->bindParam(1, $filing_no, PDO::PARAM_STR);
	$details->execute();
	$details = $details->fetchAll();
	return $details;
}

function update_scrutiny($db,$schemas,$table,$filing_no,$count_scrutiny,$defect_status,$remark,$server_date,$user_id,$scrutiny_level,$save_path){
	if($defect_status == 0){
		$defect_status = 'N';
	}else if($defect_status == 1){
		$defect_status = 'Y';
	}else{
		$defect_status = '';
	}
	$update = $db->prepare("update $schemas.$table set scrutiny_level = ? , notification_date = ?, defect_free= ?, user_id = ?, pdf_path = ? where filing_no = ? and count_scruitny = ?");
	$update->bindParam(1, $scrutiny_level, PDO::PARAM_STR);
	$update->bindParam(2, $server_date, PDO::PARAM_STR);
	$update->bindParam(3, $defect_status, PDO::PARAM_STR);
	$update->bindParam(4, $user_id, PDO::PARAM_STR);
	$update->bindParam(5, $save_path, PDO::PARAM_STR);
	$update->bindParam(6, $filing_no, PDO::PARAM_STR);
	$update->bindParam(7, $count_scrutiny, PDO::PARAM_STR);
	$res = $update->execute();
	return $res;
}

function save_scrutiny_his($db,$schemas,$filing_no){
	$query = "insert into $schemas.caveat_scrutiny_his select * from $schemas.caveat_scrutiny where filing_no = ?";
		$ins =$db->prepare($query);
		$ins->bindParam(1, $filing_no, PDO::PARAM_STR);
		$res = $ins->execute();
		return $res;
}

function save_case_detail($db,$schemas,$filing_no,$get_case_detail,$pet_name,$res_name,$case_year,$reg_no,$regis_date,$userid,$location_id,$status='P'){
		$filing_no = $get_case_detail['filing_no'];
		$filing_year = $get_case_detail['case_year'];
		$dt_of_filing = $get_case_detail['dt_of_filing'];
		$case_type = $get_case_detail['case_type_nclat'];
		$main_case_ia_no = $get_case_detail['filingnumberia'];
		$is_partially_defective = $get_case_detail['patially_defective'];
		$query = "insert into $schemas.case_detail (filing_no,case_no,pet_name,res_name,loginid,status,regis_date,
		dt_of_filing,case_type,entry_date,case_year,filing_year,filing_no_old,location_code,main_case_ia_no,is_partially_defective)
		values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$ins =$db->prepare($query);
		$ins->bindParam(1, $filing_no, PDO::PARAM_STR);
		$ins->bindParam(2, $reg_no, PDO::PARAM_STR);
		$ins->bindParam(3, $pet_name, PDO::PARAM_STR);
		$ins->bindParam(4, $res_name, PDO::PARAM_STR);
		$ins->bindParam(5, $userid, PDO::PARAM_STR);
		$ins->bindParam(6, $status, PDO::PARAM_STR);
		$ins->bindParam(7, $regis_date, PDO::PARAM_STR);
		$ins->bindParam(8, $dt_of_filing, PDO::PARAM_STR);
		$ins->bindParam(9, $case_type, PDO::PARAM_STR);
		$ins->bindParam(10, $regis_date, PDO::PARAM_STR);
		$ins->bindParam(11, $case_year, PDO::PARAM_STR);
		$ins->bindParam(12, $filing_year, PDO::PARAM_STR);
		$ins->bindParam(13, $filing_no, PDO::PARAM_STR);
		$ins->bindParam(14, $location_id, PDO::PARAM_STR);
		$ins->bindParam(15, $main_case_ia_no, PDO::PARAM_STR);
		$ins->bindParam(16, $is_partially_defective, PDO::PARAM_STR);
		$res = $ins->execute();
		return $res;
	}
	
function get_cav_case_detail($db,$location_id,$filing_no){
	$query = "select * from e_case_detail where filing_no = ? and location_id = ?";
	
	try{
	$data = $db->prepare($query);
    $data->bindParam(1, $filing_no, PDO::PARAM_STR);
	$data->bindParam(2, $location_id, PDO::PARAM_STR);
    $data->execute();
	$data = $data->fetchAll();
	return $data;
	} catch (PDOException $ex) {
        echo $ex;
    }	
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

function update_reg_no($db,$schemas,$reg_no,$case_type,$case_year){
		$query = "update $schemas.case_type_reg set reg_no = ? where reg_year = ? and case_type = ?";
		$up =$db->prepare($query);
		$up->bindParam(1, $reg_no, PDO::PARAM_STR);
		$up->bindParam(2, $case_year, PDO::PARAM_STR);
		$up->bindParam(3, $case_type, PDO::PARAM_STR);
		$res = $up->execute();
		return $res;
	}

$data = $_POST;
$type = $data['type'];
$filing_no = $data['filing_no'];
$defect_status = '';
$remark = '';
$get_scrutiny_details = scrutiny_details($db,$schemas,$filing_no);
$caveat_details = caveat_details($dbonline,$filing_no);
foreach($caveat_details as $k=>$cav){
	if($k == 0)
		$comma = '';
	else
		$comma = ' ,';
	$all_caveator = $comma.$cav['name'];
}
/*$scrutiny_flag = $caveat_details['scrutiny'];
if(!empty($get_scrutiny_details)){
	$get_scrutiny_details = array_shift($get_scrutiny_details);
	$check_defect_status = $get_scrutiny_details['defect_free'];
	$remark = $get_scrutiny_details['remark'];
} */
if($type == 'get_caveat_scruitny'){ ?>
	<div style="margin:20px;">
	<div class="row">
		<label>Select</label>
		<label class="radio-inline">
		  <input type="radio" class="defect_status_check" name="defect_status_check" value='0'>Defective
		</label>
		<label class="radio-inline">
		  <input type="radio" class="defect_status_check" name="defect_status_check" value='1'>Defect Free
		</label>
	</div>
	<div class="row">
		<label>Remark</label>
		<textarea name="remark" id="remark" class="form-control" rows="10" cols="30"><?php echo $remark; ?></textarea>
	</div>
	<center>
		<button type="button" id="scrutiny_btn" class="btn btn-sm btn-success" onClick="return submit_scrutiny('<?php echo $filing_no; ?>')">Submit</button>
	</center>
	</div>
	
<?php }

if($type == 'submit_scrutiny'){

	try	{
		$db->beginTransaction();  // begin transaction
		$dbonline->beginTransaction();
		$defect_status = $data['defect_status'];
		$remark = $data['remark'];
		$count_scrutiny = get_no_of_scrutiny_count($db,$schemas,$filing_no);
		$pre_c_scrutiny = $count_scrutiny;
		$count_scrutiny = $count_scrutiny+1;
		$scrutiny_level = 1;
		$one = 1;
		
		
			if($defect_status == 0){
				$type = '10';
				$case_no_generated = 0;
				$scrutiny_corr1 = '6';
				$is_defective = 1;
				$scrutiny = 1;
				$display = 0;
				$text = 'with defect';
				$defective_html = "<html>
									<table>
										<tbody>
											<tr>
												<td colspan='2'><h2>Caveat Scrutiny Defects Raised</h2></td>
											</tr
											<tr>
												<td><h3>Diary No :</h3></td>
												<td><h3>$filing_no</h3></td>
											</tr>
											<tr>
												<td><h3>Caveator :</h3></td>
												<td><h3>$all_caveator</h3></td>
											</tr>
											<tr>
												<td><h3>Date Of Defect :</h3></td>
												<td><h3>$notification_date</h3></td>
											</tr>
											<tr>
												<td><h3>Defect :</h3></td>
												<td><h3>$remark</h3></td>
											</tr>
										</tbody>
									</table>
									</html>";
				$filename = $filing_no."-".$pre_c_scrutiny.time().".pdf";
				$dompdf->loadHtml($defective_html);
				$dompdf->setPaper('A4');
				$dompdf->render();
				$output = $dompdf->output();
				
				$pdfpath = "/NCLAT_Documents/CIS_Documents/casedoc/defects/$filename";
				$save_path = "/NCLAT_Documents/CIS_Documents/casedoc/defects/$filename";
				file_put_contents($pdfpath, $output);
				$st1x =$db->prepare("update e_case_detail  set scrutiny_level = ? , is_defective = ?, scrutiny=?, defect_date = ?, scrutiny_status = ?, remodify_date = ?, case_no_generated =? where filing_no=? ");
				$st1x->bindParam(1, $one, PDO::PARAM_STR);
				$st1x->bindParam(2, $is_defective, PDO::PARAM_STR);
				$st1x->bindParam(3, $one, PDO::PARAM_STR);
				$st1x->bindParam(4, $server_date, PDO::PARAM_STR);
				$st1x->bindParam(5, $scrutiny_corr1, PDO::PARAM_STR);
				$st1x->bindParam(6, $server_date, PDO::PARAM_STR);
				$st1x->bindParam(7, $case_no_generated, PDO::PARAM_STR);
				$st1x->bindParam(8, $filing_no, PDO::PARAM_STR);
				$st1x->execute();
				$subject="Status of caveat diary no ".$filing_no ;
				$email_text="The document(s) submitted under caveat diary no ".$filing_no." are marked as defective on date :" .$notification_date." Kindly check the attachment for removing the defects raised. You are required to submit the corrected documents as soon as possible. This is a computer generated message, Please do not reply" ;
				$message=" The Document(s) submitted under diary no ".$filing_no." are marked as defective on date :".$notification_date." For more information kindly check mail on registered email address.";
				$response = array( 
				'status' => 1, 
				'message' => "Scrutiny Completed $text" 
				);
			}
			if($defect_status == 1){
				$type = '9';
				$case_no_generated = 1;
				$is_defective = 0;
				$scrutiny_corr1 = '';
				$scrutiny = 1;
				$display = 1;
				$text = 'without defect';
				$case_year = date('Y');
				$get_case_detail = get_cav_case_detail($db,$location_id,$filing_no);
				$get_case_detail = array_shift($get_case_detail);
				$last_reg_no = get_last_reg_no($db,$schemas,$get_case_detail['case_type_nclat'],$case_year);
				$reg_no = $last_reg_no+1;
				$save_into_case_detail = save_case_detail($db,$schemas,$filing_no,$get_case_detail,$pet_name='',$res_name='',$case_year,$reg_no,$server_date,$user_id,$location_id);
				$update_reg_no = update_reg_no($db,$schemas,$reg_no,$get_case_detail['case_type_nclat'],$case_year);
				$subject="Status of Caveat diary no ".$filing_no;
				$case_num = "CAVEAT/".$reg_no."/".$case_year;
				$st1x =$db->prepare("update e_case_detail  set scrutiny_level = ? , is_defective = ?, scrutiny=?, scrutiny_status = ?,  case_no_generated =? where filing_no=? ");
				$st1x->bindParam(1, $one, PDO::PARAM_STR);
				$st1x->bindParam(2, $is_defective, PDO::PARAM_STR);
				$st1x->bindParam(3, $one, PDO::PARAM_STR);
				$st1x->bindParam(4, $scrutiny_corr1, PDO::PARAM_STR);
				$st1x->bindParam(5, $case_no_generated, PDO::PARAM_STR);
				$st1x->bindParam(6, $filing_no, PDO::PARAM_STR);
				$st1x->execute();
				
				$pdfpath = '';
				$filename = '';
				$save_path = '';
				$email_text="The document(s) submitted under caveat diary no ".$filing_no." and case no ".$case_num." are marked as defect free on date :" .$notification_date." This is a computer generated message, Please do not reply "  ;
				$message="The Document(s) submitted under diary no ".$filing_no." and case no ".$case_num." are marked as defect free.";
				$response = array( 
				'status' => 1, 
				'message' => "Scrutiny Completed $text. and Caveat No is $case_num" 
				);
			}
			
			if(!empty($get_scrutiny_details)){
			save_scrutiny_his($db,$schemas,$filing_no);
			update_scrutiny($db,$schemas,$table='caveat_scrutiny',$filing_no,$pre_c_scrutiny,$defect_status,$remark,$server_date,$user_id,$scrutiny_level,$save_path);
			}else{
				insert_into_scrutiny($db,$schemas,$table='caveat_scrutiny',$filing_no,$defect_status,$remark,$count_scrutiny,$server_date,$user_id,$scrutiny_level,$save_path);
			}
			
			$scrutiny_dc='0';
			$scr_display='1';
			
			$st1x111 =$db->prepare("update document_upload set doc_level=?, scrutiny = ? , display = ? where  filing_no=? and scrutiny=? and display=? and (miscellaneous_ref_no IS NULL or miscellaneous_ref_no = '')");
			$st1x111->bindParam(1, $one, PDO::PARAM_STR);
			$st1x111->bindParam(2, $one, PDO::PARAM_STR);
			$st1x111->bindParam(3, $display, PDO::PARAM_STR);
			$st1x111->bindParam(4, $filing_no, PDO::PARAM_STR);
			$st1x111->bindParam(5, $scrutiny_dc, PDO::PARAM_STR);
			$st1x111->bindParam(6, $scr_display, PDO::PARAM_STR);
			$st1x111->execute();
						
			$send_mail = fn_sms($db, $type, $filename, $filing_no, $subject, $message, $email_text,$save_path);
			
		$db->commit();
		$dbonline->commit();
		echo json_encode($response); die;

		
	}catch(Exception $e){
		$db->rollBack();
		$dbonline->rollBack();
		$response = array( 
		'status' => 0, 
		'message' => 'some error occurred.' 
		);
		die;
	}
}


?>
