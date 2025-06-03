<?php
 
/*  ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */ 
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
		$case_detail = $db->prepare("select filing_no,status,dt_of_filing,pet_name,res_name,case_no,case_year,case_type,regis_date,ia_ma_filing_no from $schema.case_detail where case_type = ? and case_no = ? and case_year = ? and location_code = ?");
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
	if($type == 'update_next_list_date'){
		$filing_no = $data['filing_no'];
		$court_no = $data['court_no'];
		$remark = (isset($data['remark']))?$data['remark']:'';
		$next_list_date = $data['next_list_date'];
		$current_listing_date = $data['current_listing_date'];
		$new_next_list_date = $data['new_next_list_date'];
		if($new_next_list_date == ''){
			$response['message'] = "Please enter next list date";
		}else{
			try{
			$db->beginTransaction();
			list($day,$month,$year)=explode('/',$new_next_list_date);
			$new_next_list_date=$year.'-'.$month.'-'.$day;
			$get_data = $edit_Case_obj->get_data_by_filing_listing_court_detail($db,$schemas,$filing_no,$current_listing_date,$next_list_date,$court_no,'case_proceeding');
			if(!empty($get_data)){
				$prevoius_user_id = $get_data['user_id'];
				$prevoius_entry_date = $get_data['entry_date'];
				//$old_remark = $get_data['remarks'];
				
				$save_allocation_proceeding_logs = $edit_Case_obj->save_log($db,$schemas,$filing_no,$current_listing_date,$next_list_date,$court_no,$prevoius_entry_date,$prevoius_user_id,$entry_date,$user_id,$remark,'case_proceeding_allocation_logs'); 
				$allocation_temp_details = $edit_Case_obj->get_data_by_filing_listing_court_detail($db,$schemas,$filing_no,$current_listing_date,$next_list_date,$court_no,'case_allocation_temp');
				//echo "<pre>"; print_r($allocation_temp_details); die;
				if(!empty($allocation_temp_details)){
					$temp_current_listing_date = $allocation_temp_details['listing_date'];
					$temp_next_list_date = $allocation_temp_details['next_list_date'];
					$temp_court_no = $allocation_temp_details['court_no'];
					$temp_prevoius_user_id = $allocation_temp_details['deal_cd'];
					$temp_prevoius_entry_date = $allocation_temp_details['entry_date'];
					//$temp_old_remark = $allocation_temp_details['remarks'];
				}
				$save_allocation_temp_logs = $edit_Case_obj->save_log($db,$schemas,$filing_no,$temp_current_listing_date,$temp_next_list_date,$temp_court_no,$temp_prevoius_entry_date,$temp_prevoius_user_id,$entry_date,$user_id,$remark,'case_allocation_temp_logs'); 
				$update_next_list_date_proceeding = $edit_Case_obj->update_date_allocation_proceeding($db,$schemas,$filing_no,$current_listing_date,$next_list_date,$court_no,$new_next_list_date,'case_proceeding');
				$update_next_list_date_allocation = $edit_Case_obj->update_date_allocation_proceeding($db,$schemas,$filing_no,$current_listing_date,$next_list_date,$court_no,$new_next_list_date,'case_allocation');
				$update_next_list_date_allocation_temp = $edit_Case_obj->update_date_allocation_temp($db,$schemas,$filing_no,$current_listing_date,$next_list_date,$court_no,$new_next_list_date,'case_allocation_temp');
			}
			$db->commit();
			$response = array( 
				'status' => 1, 
				'message' => 'Next List date updated' 
				);
			}catch(Exception $e){
				$db->rollBack();
				$response = array( 
				'status' => 0, 
				'message' => 'some error occurred.' 
				);
			}
		echo json_encode($response); die;
		}
	}
	
	if($type == 'proceeding_info'){
		$filing_no = $data['filing_no'];
		$listing_date = $data['listing_date'];
		$court_no = $data['court_no'];
		$case_detail = $edit_Case_obj->case_detail($schemas,$db,$filing_no,$columns='filing_no,case_no,case_type,case_year,status');
		$case_detail = array();
		$listing_data = $edit_Case_obj->get_proceeding_by_listing_date($schemas,$db,$filing_no,$listing_date,$order_by_column = 'entry_date desc',$limit = 1);
		$orders = $edit_Case_obj->get_orders($schemas,$db,$filing_no,$listing_date,$flag = 'Y',$order_by_column = 'entry_date desc');
		$judgements = $edit_Case_obj->get_judgement($schemas,$db,$filing_no,$listing_date,$display='t',$order_by_column = 'entry_date desc'); ?>
		<div><strong>Please delete record serial wise to revert a case proceeding in a proper manner.<br/>
					1 . First delete wrong order , if order is not wrong then skip.<br/>
					2 . Then delete wrong judgement , if judgement is not wrong then skip.<br/>
					3 . At last delete wrong proceeding.</strong></div>
		<?php if(!empty($orders)){ ?>
			<center><h4>1 . Daily Orders</h4></center>
			<table class="table table-bordered table-hovered table-striped">
				  <thead>
					<tr>
					  <th scope="col">Order Date</th>
					  <th scope="col">Upload Order Date</th>
					  <th scope="col">View Order</th>
					  <th scope="col">Deletion Reason</th>
					  <th scope="col">Action</th>
					</tr>
				  </thead>
				  <tbody id="order_data">
				  <?php foreach($orders as $key=>$order) { ?>
					<tr id="order_<?php echo $order['item_no']; ?>">
					  <td scope="col"><?php echo display_date($order['order_date']); ?></td>
					   <td scope="col"><?php echo (!empty($order['order_upload_date']) && $order['order_upload_date'] != '9999-01-01')?display_date($order['order_upload_date']):''; ?></td>
					  <td scope="col"><a href="../<?php echo $order['pdf_path']; ?>" target="_blank"><button type="button" class="btn btn-sm btn-success">View</button></a></td>
					  <td scope="col">
						<textarea placeholder = 'reason of detetion' id='reason_<?php echo $order['item_no']; ?>' value='' class='form-control' col='20' rows='1'></textarea>
					  </td>
					  <td scope="col"><button type="button" class="btn btn-danger" onClick="return delete_order('<?php echo $filing_no; ?>','<?php echo $order['item_no']; ?>');">Delete</button></td>	
					</tr>
				  <?php } ?>
				  </tbody>
			 </table>
		<?php }
	
		if(!empty($judgements)){ 
		?>
			<center><h4>2 . Judgement</h4></center>
			<table class="table table-bordered table-hovered table-striped">
				  <thead>
					<tr>
					  <th scope="col">Judgement Date</th>
					  <th scope="col">Judgement Order Date</th>
					  <th scope="col">View Judgement</th>
					  <th scope="col">Deletion Reason</th>
					  <th scope="col">Action</th>
					</tr>
				  </thead>
				  <tbody id="order_data">
				  <?php foreach($judgements as $key=>$judgement) { ?>
					<tr id="judgement__<?php echo $judgement['order_id']; ?>">
					  <td scope="col"><?php echo display_date($judgement['date_of_order']); ?></td>
					  <td scope="col"><?php echo (!empty($judgement['upload_date']) && $judgement['upload_date'] != '9999-01-01')?display_date($judgement['upload_date']):''; ?></td>
					  <td scope="col"><a href="../<?php echo $judgement['path']; ?>" target="_blank"><button type="button" class="btn btn-sm btn-success">View</button></a></td>
					  <td scope="col">
						<textarea placeholder = 'reason of detetion' id='judgementdelete_<?php echo $judgement['order_id']; ?>' value='' class='form-control' col='20' rows='1'></textarea>
					  </td>
					  <td scope="col"><button type="button" class="btn btn-danger" onClick="return delete_judgement('<?php echo $filing_no; ?>','<?php echo $judgement['order_id']; ?>');">Delete</button></td>	
					</tr>
				  <?php } ?>
				  </tbody>
			 </table>
		<?php }
		if(!empty($case_detail)){ ?>
			<center><h4>3. Case Status</h4></center>
			<table class="table table-bordered table-hovered table-striped">
				  <thead>
					<tr>
					  <th scope="col">Status</th>
					  <th scope="col">Action</th>
					</tr>
				  </thead>
				  <tbody id="case_status">
					<tr>
					  <td scope="col" id="status"><?php echo ($case_detail['status'] == 'D')?'Disposed':'Pending'; ?></td>
					  <td scope="col"><button type="button" class="btn btn-primary" onClick="return change_status('<?php echo $filing_no; ?>','P');">Pending</button>
									  <button type="button" class="btn btn-danger" onClick="return change_status('<?php echo $filing_no; ?>','D');">Dispose</button>
									  <button type="button" class="btn btn-warning" onClick="return change_status('<?php echo $filing_no; ?>','X');">Partial Dispose</button>
					  </td>	
					</tr>
				  </tbody>
			 </table>
		<?php }
		
		if(!empty($listing_data)){ ?>
			<center><h4>3 . Listing Information</h4></center>
			<table class="table table-bordered table-hovered table-striped">
				  <thead>
					<tr>
					  <th scope="col">Listing Date</th>
					  <th scope="col">Next Listing Date</th>
					  <th scope="col">Action</th>
					</tr>
				  </thead>
				  <tbody id="listing_data">
					<tr>
					  <td scope="col"><?php echo display_date($listing_data['listing_date']); ?></td>
					  <td scope="col"><?php echo display_date($listing_data['next_list_date']); ?></td>
					  <td scope="col"><button type="button" class="btn btn-danger" onClick="return delete_proceeding('<?php echo $filing_no; ?>','<?php echo $listing_date; ?>','<?php echo $court_no; ?>')">Delete</button></td>
					</tr>
				  </tbody>
			 </table>
		<?php }
		die;
	}
	
	if($type == 'delete_proceeding'){
		$filing_no = $data['filing_no'];
		$listing_date = $data['listing_date'];
		$court_no = $data['court_no'];
		try{
	$db->beginTransaction();  // begin transaction
	
	$max_listing_date = $db->prepare("select max(listing_date) as max_listing_date from delhi.case_proceeding where filing_no = ?");
	$max_listing_date->bindParam(1, $filing_no, PDO::PARAM_INT);
	$max_listing_date->execute();
	$max_listing_date = $max_listing_date->fetchColumn();
	if($listing_date < $max_listing_date){
		$dlt_proceeding = $edit_Case_obj->delete_proceeding($schemas,$db,$filing_no,$listing_date,$entry_date,$user_id,$username);
		//$dlt_disosal = $edit_Case_obj->delete_disposal($schemas,$db,$filing_no,$listing_date,$entry_date,$user_id,$username);
		if($dlt_proceeding){
			$response = array( 
				'status' => 1, 
				'message' => 'Proceeding Deleted' 
				);
		}else{
			$response = array( 
				'status' => 0, 
				'message' => 'some error occured' 
				);
		}
	
	}else{
	$is_proceeded = $db->prepare("select count(*) as count from $schemas.case_proceeding where filing_no = ?");
	$is_proceeded->bindParam(1, $filing_no, PDO::PARAM_INT);
	$is_proceeded->execute();
	$count = $is_proceeded->fetchColumn();
	if($count > 1){
	
		$is_case_listed_properely = $db->prepare("select count(*) as count from $schemas.case_allocation where filing_no = ? and listing_date = ?");
		$is_case_listed_properely->bindParam(1, $filing_no, PDO::PARAM_INT);
		$is_case_listed_properely->bindParam(2, $listing_date, PDO::PARAM_INT);
		$is_case_listed_properely->execute();
		$is_properly_listed = $is_case_listed_properely->fetchColumn();
		if($is_properly_listed > 0) {
		$his = $db->prepare("insert into $schemas.case_allocation_his_temp select * from $schemas.case_allocation_temp where filing_no = ? and listing_date = ?");
		$his->bindParam(1, $filing_no, PDO::PARAM_INT);
		$his->bindParam(2, $listing_date, PDO::PARAM_INT);
		//$his->bindParam(3, $court_no, PDO::PARAM_INT);
		$res = $his->execute();
		if($res){
			
			$last_listing_date = $db->prepare("select listing_date from $schemas.case_proceeding where filing_no = ? order by listing_date desc limit 1");
			$last_listing_date->bindParam(1, $filing_no, PDO::PARAM_INT);
			$last_listing_date->execute();
			$last_listing_date = $last_listing_date->fetchColumn();
			
			$listed = 0;
			$update = $db->prepare("update $schemas.case_allocation_temp set listed = ? , listing_date = ?, next_list_date = ? where filing_no = ? and listing_date = ?");
			$update->bindParam(1, $listed, PDO::PARAM_INT);
			$update->bindParam(2, $last_listing_date, PDO::PARAM_INT);
			$update->bindParam(3, $last_listing_date, PDO::PARAM_INT);
			$update->bindParam(4, $filing_no, PDO::PARAM_INT);
			$update->bindParam(5, $listing_date, PDO::PARAM_INT);
			//$update->bindParam(6, $court_no, PDO::PARAM_INT);
			$rr = $update->execute();
		}
		}else{
			$last_listing_date_form_history = $db->prepare("select listing_date,next_list_date from $schemas.case_proceeding where filing_no = ? and listing_date < ? order by listing_date desc limit 1");
			$last_listing_date_form_history->bindParam(1, $filing_no, PDO::PARAM_INT);
			$last_listing_date_form_history->bindParam(2, $listing_date, PDO::PARAM_INT);
			$last_listing_date_form_history->execute();
			$last_listing_date_form_history = $last_listing_date_form_history->fetchAll();
			if(!empty($last_listing_date_form_history)){
				$last_listing_date_form_history = array_shift($last_listing_date_form_history);
				$last_listing_date = $last_listing_date_form_history['listing_date'];
				$next_listing_date = $last_listing_date_form_history['next_list_date'];
				$his = $db->prepare("insert into $schemas.case_allocation_his_temp select * from $schemas.case_allocation_temp where filing_no = ? and listing_date = ?");
				$his->bindParam(1, $filing_no, PDO::PARAM_INT);
				$his->bindParam(2, $listing_date, PDO::PARAM_INT);
				//$his->bindParam(3, $court_no, PDO::PARAM_INT);
				$res = $his->execute();
				$listed = 0;
				$update = $db->prepare("update $schemas.case_allocation_temp set listed = ? , listing_date = ?, next_list_date = ? where filing_no = ?");
				$update->bindParam(1, $listed, PDO::PARAM_INT);
				$update->bindParam(2, $last_listing_date, PDO::PARAM_INT);
				$update->bindParam(3, $next_listing_date, PDO::PARAM_INT);
				$update->bindParam(4, $filing_no, PDO::PARAM_INT);
				//$update->bindParam(5, $listing_date, PDO::PARAM_INT);
				//$update->bindParam(6, $court_no, PDO::PARAM_INT);
				$rr = $update->execute();
			}
		}
		
		//$response = array('status'=>1, 'message'=>'This case can not be back to fresh cases listing, please give next date to this case');
	}else{
		$is_case_listed_properely = $db->prepare("select count(*) as count from $schemas.case_allocation where filing_no = ? and listing_date = ?");
		$is_case_listed_properely->bindParam(1, $filing_no, PDO::PARAM_INT);
		$is_case_listed_properely->bindParam(2, $listing_date, PDO::PARAM_INT);
		$is_case_listed_properely->execute();
		$is_properly_listed = $is_case_listed_properely->fetchColumn();
		if($is_properly_listed > 0){
		$his = $db->prepare("insert into $schemas.case_allocation_his_temp select * from $schemas.case_allocation_temp where filing_no = ? and listing_date = ? and court_no = ?");
		$his->bindParam(1, $filing_no, PDO::PARAM_INT);
		$his->bindParam(2, $listing_date, PDO::PARAM_INT);
		$his->bindParam(3, $court_no, PDO::PARAM_INT);
		$res = $his->execute();
		if($res){
			
			$last_listing_date = $db->prepare("select listing_date from $schemas.case_proceeding where filing_no = ? order by listing_date desc limit 1");
			$last_listing_date->bindParam(1, $filing_no, PDO::PARAM_INT);
			$last_listing_date->execute();
			$last_listing_date = $last_listing_date->fetchColumn();
			
			$listed = 0;
			$update = $db->prepare("update $schemas.case_allocation_temp set listed = ? , listing_date = ?, next_list_date = ? where filing_no = ? and listing_date = ? and court_no = ?");
			$update->bindParam(1, $listed, PDO::PARAM_INT);
			$update->bindParam(2, $last_listing_date, PDO::PARAM_INT);
			$update->bindParam(3, $last_listing_date, PDO::PARAM_INT);
			$update->bindParam(4, $filing_no, PDO::PARAM_INT);
			$update->bindParam(5, $listing_date, PDO::PARAM_INT);
			$update->bindParam(6, $court_no, PDO::PARAM_INT);
			$rr = $update->execute();
		}
	}else{
		$his = $db->prepare("insert into $schemas.case_allocation_his_temp select * from $schemas.case_allocation_temp where filing_no = ?");
		$his->bindParam(1, $filing_no, PDO::PARAM_INT);
		/* $his->bindParam(2, $listing_date, PDO::PARAM_INT);
		$his->bindParam(3, $court_no, PDO::PARAM_INT); */
		$res = $his->execute();
		if($res){
			$delete = $db->prepare("delete from $schemas.case_allocation_temp where filing_no = ?");
			$delete->bindParam(1, $filing_no, PDO::PARAM_INT);
			/* $delete->bindParam(2, $listing_date, PDO::PARAM_INT);
			$delete->bindParam(3, $court_no, PDO::PARAM_INT); */
			$delete->execute();
			
			$legal_aid = NULL;
			$update = $db->prepare("update $schemas.case_detail set legal_aid = ? where filing_no = ?");
			$update->bindParam(1, $legal_aid, PDO::PARAM_STR);
			$update->bindParam(2, $filing_no, PDO::PARAM_STR);
			$rr = $update->execute();
		}
	}
		
	}
	if($rr){
		$staus = 'P';
		$dlt_proceeding = $edit_Case_obj->delete_proceeding($schemas,$db,$filing_no,$listing_date,$entry_date,$user_id,$username);
		//$dlt_disosal = $edit_Case_obj->delete_disposal($schemas,$db,$filing_no,$listing_date,$entry_date,$user_id,$username);
		$update = $db->prepare("update $schemas.case_detail set status = ? where filing_no = ?");
		$update->bindParam(1, $staus, PDO::PARAM_STR);
		$update->bindParam(2, $filing_no, PDO::PARAM_STR);
		$rr = $update->execute();
		if($dlt_proceeding){
			$response = array( 
				'status' => 1, 
				'message' => 'Proceeding Deleted' 
				);
		}else{
			$response = array( 
				'status' => 0, 
				'message' => 'some error occured' 
				);
		}
	}
	}
	$db->commit();
	 }catch(Exception $e){
		$db->rollBack();
		$response = array( 
				'status' => 0, 
				'message' => 'some error occured' 
				);
		}
	echo json_encode($response); die;
	}
		
	
	if($type == 'delete_order'){
		$filing_no = $data['filing_no'];
		$item_no = $data['item_no'];
		$reason = trim($data['reason']);
		$dlt_order = $edit_Case_obj->delete_order($schemas,$db,$filing_no,$item_no,$entry_date,$user_id,$username,$reason);
		if($dlt_order){
			$response = array( 
				'status' => 1, 
				'message' => 'Order Deleted' 
				);
		}else{
			$response = array( 
				'status' => 0, 
				'message' => 'some error occured' 
				);
		}
		echo json_encode($response); die;
	}
	
	if($type == 'delete_judgement'){
		$filing_no = $data['filing_no'];
		$order_id = $data['order_id'];
		$reason = trim($data['reason']);
		$dlt_judgement = $edit_Case_obj->delete_judgement($schemas,$db,$filing_no,$order_id,$entry_date,$user_id,$username,$reason);
		if($dlt_judgement){
			$response = array( 
				'status' => 1, 
				'message' => 'Judgement Deleted' 
				);
		}else{
			$response = array( 
				'status' => 0, 
				'message' => 'some error occured' 
				);
		}
		echo json_encode($response); die;
	}
	
	if($type == 'search_case'){
			$case_no = $data['case_no'];
			$case_year = $data['case_year'];
			$case_type = $data['case_type'];
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
				?>
				 <input type="hidden" value="<?php echo $filing_no; ?>" id="filing_no" name="filing_no">
				 <input type="hidden" value="<?php echo $case_detail['regis_date']; ?>" id="regis_date" name="regis_date">
				 <input type="hidden" value="<?php echo $case_detail['ia_ma_filing_no']; ?>" id="main_case_filing_no" name="main_case_filing_no">
				 <table id="title" class="table table-hover table-bordered">
					<thead>
						<th>Diary No</th>
						<th>Case No</th>
						<th>Title</th>
						<th>Date Of Filing</th>
						<th>Status</th>
						<th>Is This Your Case</th>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $filing_no; ?></td>
							<td><?php echo get_short_name($db,'case_type','short_name','id',$case_type).'/'.$case_no.'('.get_short_name($db,'mater_location_city','short_name','city_id',$location_id).")/".$case_year; ?></td>
							<td><?php echo $case_detail['pet_name']."  VS  ".$case_detail['res_name']; ?></td>
							<td><?php echo display_date($case_detail['dt_of_filing']); ?></td>
							<td><?php echo ($case_detail['status'] == 'D')?'Disposed':'Pending'; ?></td>
							<td><input type="checkbox" name="is_case" id="is_case" onClick="get_basic_info(this);" style="background-color:#ccc;"/></td>
						</tr>
					</tbody>
				 </table>
				<?php 
				//}
			}
			die;
		}
		
		if($type == 'edit_options'){
			//echo "<pre>"; print_r($data); die;
			if(!empty($data['filing_no'])){ ?>
						<div class="row">
							<div class="col-sm-4 col-lg-4">
								<div class="form-group">
								<label>Change To : </label>
								<select class="form-control" name='case_change_status' id="case_change_status">
									<option value='P'>Pending</option>
									<option value='D'>Dispose</option>
									<option value='X'>Partial Dispose</option>
								</select>
								</div>
								<br/>
								<br/><br/>
								<div class="form-group">
								<label>Order Reference / Other Reason : </label>
								<input type='text' required id='reason' value='' name='reason' class='form-control'/>
								  <button type="button" name="change_status" id="change_status" class="btn btn-sm btn-success" onClick="return update_status('<?php echo $data['filing_no']; ?>');">Change Status</button>
								</div>
							</div>
							<div class="col-sm-4 col-lg-4">
								<div class="form-group">
								 
								</div>
							</div>
							<div class="col-sm-4 col-lg-4">
								<div class="form-group">
								  
								</div>
							</div>
						</div>
				
		<?php	}
		die;
		}
		
		if($type == 'change_status'){
		$filing_no = $data['filing_no'];
		$status = $data['status'];
		$reason = trim($data['reason']);
		if(empty($reason)){
			$response = array( 
				'status' => 0, 
				'message' => 'Reason can not be empty' 
				);
			echo json_encode($response); die;
		}
		$change_status = $edit_Case_obj->change_case_status($schemas,$db,$filing_no,$status,$entry_date,$user_id,$username,$reason);
		if($change_status){
			$response = array( 
				'status' => 1, 
				'message' => 'Case status changes' 
				);
		}else{
			$response = array( 
				'status' => 0, 
				'message' => 'some error occured' 
				);
		}
		echo json_encode($response); die;
	}
	
	//  for case updatation and deletion
	
	if($type == 'search_cases'){
			$case_no = $data['case_no'];
			$case_year = $data['case_year'];
			$case_type = $data['case_type'];
			$case_details = get_case_detail($db,$schemas,$case_no,$case_year,$case_type,$location_id);
			if(empty($case_details)){
				$response = array( 'status' => 0, 'message' => 'Case Not Found');
				echo json_encode($response); die;
			}
			if(!empty($case_details)){
				?>
				 <table id="title" class="table table-hover table-bordered">
					<thead>
						<th>Diary No</th>
						<th>Case No</th>
						<th>Title</th>
						<th>Date Of Filing</th>
						<th>Status</th>
						<th>Action</th>
					</thead>
					<tbody>
					<?php 
						foreach($case_details as $k=>$case_detail){
							$filing_no = $case_detail['filing_no'];
							$case_no = $case_detail['case_no'];
							$case_year = $case_detail['case_year'];
							$case_type = $case_detail['case_type'];
							$case_num = get_short_name($db,'case_type','short_name','id',$case_type).'/'.$case_no.'('.get_short_name($db,'mater_location_city','short_name','city_id',$location_id).")/".$case_year;
					?>
						<tr id='rm_case<?php echo $filing_no; ?>'>
							<td><?php echo $filing_no; ?></td>
							<td><?php echo $case_num; ?></td>
							<td><?php echo $case_detail['pet_name']."  VS  ".$case_detail['res_name']; ?></td>
							<td><?php echo display_date($case_detail['dt_of_filing']); ?></td>
							<td><?php echo ($case_detail['status'] == 'D')?'Disposed':'Pending'; ?></td>
							<td>
								<input type="submit" value="Remove" class="btn btn-danger btn-sm" id="delete_case<?php echo $filing_no; ?>" name="delete_case<?php echo $filing_no; ?>" onClick="remove_case('<?php echo $filing_no; ?>','<?php echo $case_type; ?>')">
								<input type="submit" value="Change Case Number" class="btn btn-primary btn-sm" id="change_case_no<?php echo $filing_no; ?>" name="change_case_no<?php echo $filing_no; ?>" onClick="change_case_number('<?php echo $filing_no; ?>','<?php echo $case_num; ?>','<?php echo $case_no; ?>','<?php echo $case_year; ?>','<?php echo $case_type; ?>')">
							</td>
						</tr>
						<?php } ?>
					</tbody>
				 </table>
				<?php 
			}
			die;
		}
		
		if($type == 'remove_case'){
			$filing_no = $data['filing_no'];
			$case_type = $data['case_type'];
			$case_type_array = array(2,3,5,6,7);
			$remove_case = $edit_Case_obj->remove_case($schemas,$db,$dbo,$filing_no,$case_type,$case_type_array,$display='false',$user_id,$flag_type = 'D',$ipaddress='');
			if($remove_case){
				$response = array( 
					'status' => 1, 
					'message' => 'Case Removed' 
					);
			}else{
				$response = array( 
					'status' => 0, 
					'message' => 'some error occured' 
					);
			}
			echo json_encode($response); die;
		}
		
		if($type == 'change_case_no'){
			$filing_no = $data['filing_no'];
			$new_case_no = $data['inputValue'];
			$case_no = $data['case_no'];
			$case_type = $data['case_type'];
			$case_year = $data['case_year'];
			if($case_year > '2019'){
				$first_digit = $new_case_no[0];
				if($first_digit == 0){
					$new_case_no = ltrim($new_case_no, $new_case_no[0]); 
				}
			}
			$check_if_new_case_no_already_exist = $edit_Case_obj->check_case_no_exist_or_not($schemas,$db,$filing_no,$case_type,$case_year,$new_case_no);
			if($check_if_new_case_no_already_exist > 0){
				$response = array( 
					'status' => 0, 
					'message' => 'Case Number already exist' 
					);
			}else{
				$update_case_no = $edit_Case_obj->update_case_no($schemas,$db,$filing_no,$case_type,$case_year,$new_case_no,$flag_type='Case no updation',$user_id,$ipaddress='');
				if($update_case_no){
					$response = array( 
					'status' => 1, 
					'message' => 'Case Number Updated' 
					);
				}else{
					$response = array( 
					'status' => 0, 
					'message' => 'Something went wrong!!' 
					);
				}
			}
			echo json_encode($response); die;
		}
		
		if($type == 'all_proceedings'){
			$filing_no = $data['filing_no'];
			$proceedings = $edit_Case_obj->get_all_proceeding($schemas,$db,$filing_no); 
			
			?>
			<div class='table-responsive'>
			<table class='table table-bordered table-striped table-hover'>
				<th>S No.</th>
				<th>Case No.</th>
				<th>Listing Date</th>
				<th>Listing Purpose</th>
				<th>Next Listing/Disposed date</th>

			<?php
			foreach($proceedings as $k=>$proceeding){ ?>
			<tr>
				<td><?php echo $k+1; ?></td>
				<td><?php echo $proceeding['case_type_desc'].'/'.$proceeding['case_no'].'/'.$proceeding['case_year']; ?></td>
				<td><?php echo ($proceeding['listing_date'] != '1111-11-11' && $proceeding['listing_date'] != '9999-09-09')?htmlspecialchars(date('d/m/Y',strtotime($proceeding['listing_date']))):''; ?></td>
				<td><?php echo $proceeding['purpose_name'] ?></td>
				<td><?php
					if($proceeding['todays_status'] == 'P'){
					echo htmlspecialchars(date('d/m/Y',strtotime($proceeding['next_list_date'])));
					}else if ($proceeding['todays_status'] == 'D'){
						$disposal_date = $db->prepare("select disposal_date from $schemas.case_disposal where filing_no=? order by id desc limit 1");
						$disposal_date->bindParam(1, $filing_no, PDO::PARAM_STR);
						$disposal_date->execute();
						$disposal_date = $disposal_date->fetchColumn();
					 echo htmlspecialchars(date('d/m/Y',strtotime($disposal_date)));
					}else{
					echo 'Report Awaited Till ';
						 $report_awaited_date = $db->prepare("select disposal_date from $schemas.case_disposal where filing_no=? order by id desc limit 1");
						$report_awaited_date->bindParam(1, $filing_no, PDO::PARAM_STR);
						$report_awaited_date->execute();
						$report_awaited_date = $report_awaited_date->fetchColumn();
						echo "<b>".date('d/m/Y',strtotime($report_awaited_date))."</b>";
					}
				?></td>
			</tr>
			
			<?php 	}
				echo "</table></div>";
			die;
			}
		
		// end case updatation and deletion
	
	echo json_encode($response); die;
 }

?>
