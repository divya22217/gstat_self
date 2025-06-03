<?php

class Editcase {
	
	public function nextListForm($filing_no,$court_no_post,$current_listing_date,$next_list_date){
		$fn = "'".$filing_no."'";
		$form = '<form class="form-inline">
				  <input type="hidden" value="'.$filing_no.'" name="filing_no_update_'.$filing_no.'" id="filing_no_update_'.$filing_no.'">
				  <input type="hidden" value="'.$court_no_post.'" name="court_no_'.$filing_no.'" id="court_no_'.$filing_no.'">				  
				  <input type="hidden" value="'.$current_listing_date.'" name="current_listing_date_'.$filing_no.'" id="current_listing_date_'.$filing_no.'">
				  <input type="hidden" value="'.$next_list_date.'" name="next_list_date_'.$filing_no.'" id="next_list_date_'.$filing_no.'">
				  <input type="email" class="form-control datepicker" placeholder="Enter Next List Date" readonly="readonly" size="16" autocomplete="off" maxlength="10" id="next_list_date_update_'.$filing_no.'" name=
				  "next_list_date_update_'.$filing_no.'">
				  </div>
				  <button type="button" class="btn btn-primary update_btn_'.filing_no.'" onClick="return updateNextListDate('.$fn.')">Submit</button>
				</form>';
				return $form;
	}
	
	public function get_data_by_filing_listing_court_detail($db,$schemas,$filing_no,$current_listing_date,$next_list_date,$court_no,$table){
		$data = $db->prepare("select * from $schemas.$table where filing_no = ? and listing_date = ?  and next_list_date = ? and court_no = ? order by entry_date desc limit 1");
		$data->bindParam(1, $filing_no, PDO::PARAM_STR);
		$data->bindParam(2, $current_listing_date, PDO::PARAM_STR);
		$data->bindParam(3, $next_list_date, PDO::PARAM_STR);
		$data->bindParam(4, $court_no, PDO::PARAM_STR);
		$data->execute();
		$data = $data->fetchAll();
		$data = array_shift($data);
		return $data;
	}
	
	public function update_date_allocation_proceeding($db,$schemas,$filing_no,$current_listing_date,$next_list_date,$court_no,$new_next_list_date,$table){
		$data = $db->prepare("update $schemas.$table set next_list_date = ? where filing_no = ? and listing_date = ?  and next_list_date = ? and court_no = ?");
		$data->bindParam(1, $new_next_list_date, PDO::PARAM_STR);
		$data->bindParam(2, $filing_no, PDO::PARAM_STR);
		$data->bindParam(3, $current_listing_date, PDO::PARAM_STR);
		$data->bindParam(4, $next_list_date, PDO::PARAM_STR);
		$data->bindParam(5, $court_no, PDO::PARAM_STR);
		$res = $data->execute();
		return $res;
	}
	
	public function update_date_allocation_temp($db,$schemas,$filing_no,$current_listing_date,$next_list_date,$court_no,$new_next_list_date,$table){
		$data = $db->prepare("update $schemas.$table set next_list_date = ? where filing_no = ?");
		$data->bindParam(1, $new_next_list_date, PDO::PARAM_STR);
		$data->bindParam(2, $filing_no, PDO::PARAM_STR);
		$res = $data->execute();
		return $res;
	}
	
	public function save_log($db,$schemas,$filing_no,$current_listing_date,$next_list_date,$court_no,$prevoius_entry_date,$prevoius_user_id,$entry_date,$user_id,$old_remark,$table){
		$ins_obj = $db->prepare("insert into $schemas.$table (filing_no,listing_date,next_list_date,court_no,modified_by,modified_date,previous_entry_date,previous_user_id,remarks)
									values (?,?,?,?,?,?,?,?,?)");
		$ins_obj->bindParam(1, $filing_no, PDO::PARAM_STR);
		$ins_obj->bindParam(2, $current_listing_date, PDO::PARAM_STR);
		$ins_obj->bindParam(3, $next_list_date, PDO::PARAM_STR);
		$ins_obj->bindParam(4, $court_no, PDO::PARAM_STR);
		$ins_obj->bindParam(5, $user_id, PDO::PARAM_STR);
		$ins_obj->bindParam(6, $entry_date, PDO::PARAM_STR);
		$ins_obj->bindParam(7, $prevoius_entry_date, PDO::PARAM_STR);
		$ins_obj->bindParam(8, $prevoius_user_id, PDO::PARAM_STR);
		$ins_obj->bindParam(9, $old_remark, PDO::PARAM_STR);
		$res = $ins_obj->execute();
		return $res;
	}
	
	public function changeStatus($db,$schema,$filing_no,$current_status,$new_status){
	
	}
	
	public function changeNextListDate($db,$schema,$filing_no,$court_no,$current_list_date,$next_list_date){
		/* generate_log($db,$schema,$filing_no,$court_no,$current_list_date,$next_list_date){
			
		} */
		
	}
	
	public function changeDateForDraftCauseList($db,$schema,$filing_no,$court_no,$current_list_date,$next_list_date){
	
	}
	
	function get_proceeding_by_listing_date($schemas,$db,$filing_no,$listing_date,$order_by_column = '',$limit = ''){
		$query = "select * from $schemas.case_proceeding where filing_no = ? and listing_date = ?";
		if($order_by_column != ''){
			$query .= " order by $order_by_column";
		}
		if($limit != ''){
			$query .= " limit $limit";
		}
		$data = $db->prepare($query);
		$data->bindParam(1, $filing_no, PDO::PARAM_STR);
		$data->bindParam(2, $listing_date, PDO::PARAM_STR);
		$data->execute();
		$data = $data->fetchAll();
		if($limit == 1){
		 $data = array_shift($data);
		}
		return $data;
	}
	
	function get_orders($schemas,$db,$filing_no,$listing_date,$display,$order_by_column = '',$limit = ''){
		$query = "select filing_no,order_date,order_upload_date,flag,pdf_path,entry_date,item_no from $schemas.order_daily where filing_no = ? and order_date = ? and order_type in ('S','D') and flag = ?";
		if($order_by_column != ''){
			$query .= " order by $order_by_column";
		}
		if($limit != ''){
			$query .= " limit $limit";
		}
		$data = $db->prepare($query);
		$data->bindParam(1, $filing_no, PDO::PARAM_STR);
		$data->bindParam(2, $listing_date, PDO::PARAM_STR);
		$data->bindParam(3, $display, PDO::PARAM_STR);
		$data->execute();
		$data = $data->fetchAll();
		if($limit == 1){
		 $data = array_shift($data);
		}
		return $data;
	}
	
	function get_judgement($schemas,$db,$filing_no,$listing_date,$display,$order_by_column = '',$limit = ''){
		$flag = 'Y';
		$query = "select filing_no,order_date,order_upload_date,flag,pdf_path,entry_date,item_no,item_no from $schemas.order_daily where filing_no = ? and order_date = ? and order_type in ('I','F') and flag = ?";
		if($order_by_column != ''){
			$query .= " order by $order_by_column";
		}
		if($limit != ''){
			$query .= " limit $limit";
		}
		$data = $db->prepare($query);
		$data->bindParam(1, $filing_no, PDO::PARAM_STR);
		$data->bindParam(2, $listing_date, PDO::PARAM_STR);
		$data->bindParam(3, $flag, PDO::PARAM_STR);
		$data->execute();
		$data = $data->fetchAll();
		if($limit == 1){
		 $data = array_shift($data);
		}
		return $data;
	}
	
	function case_detail($schemas,$db,$filing_no,$columns='*'){
		$query = "select $columns from $schemas.case_detail where filing_no = ?";
		$data = $db->prepare($query);
		$data->bindParam(1, $filing_no, PDO::PARAM_STR);
		$data->execute();
		$data = $data->fetchAll();
		$data = array_shift($data);
		return $data;
	}
	
	function delete_proceeding($schemas,$db,$filing_no,$listing_date,$updated_date,$updated_by,$username){
		$query = "update $schemas.case_proceeding set updated_by_username = ? , updated_date = ? , updated_by = ? where filing_no = ? and listing_date = ?";
		$update = $db->prepare($query);
		$update->bindParam(1, $username, PDO::PARAM_STR);
		$update->bindParam(2, $updated_date, PDO::PARAM_STR);
		$update->bindParam(3, $updated_by, PDO::PARAM_STR);
		$update->bindParam(4, $filing_no, PDO::PARAM_STR);
		$update->bindParam(5, $listing_date, PDO::PARAM_STR);
		$update_res = $update->execute();
		
		if($update_res){
			
		$query = "insert into $schemas.case_proceeding_his (select * from $schemas.case_proceeding where filing_no = ? and listing_date = ?)";
		$insert = $db->prepare($query);
		$insert->bindParam(1, $filing_no, PDO::PARAM_STR);
		$insert->bindParam(2, $listing_date, PDO::PARAM_STR);
		$res = $insert->execute();
		if($res){
			$query = "delete from $schemas.case_proceeding where filing_no = ? and listing_date = ?";
			$delete = $db->prepare($query);
			$delete->bindParam(1, $filing_no, PDO::PARAM_STR);
			$delete->bindParam(2, $listing_date, PDO::PARAM_STR);
			$delete_res = $delete->execute();
		}
		return $delete_res;
		}
	}
	
	function delete_order($schemas,$db,$filing_no,$item_no,$updated_date,$updated_by,$username){
		$query = "insert into $schemas.order_daily_his (select * from $schemas.order_daily where filing_no = ? and item_no = ?)";
		$insert = $db->prepare($query);
		$insert->bindParam(1, $filing_no, PDO::PARAM_STR);
		$insert->bindParam(2, $item_no, PDO::PARAM_STR);
		$res = $insert->execute();
		if($res){
			$query = "delete from $schemas.order_daily where filing_no = ? and item_no = ?";
			$delete = $db->prepare($query);
			$delete->bindParam(1, $filing_no, PDO::PARAM_STR);
			$delete->bindParam(2, $item_no, PDO::PARAM_STR);
			$dlt_res = $delete->execute();
			
			$query = "update $schemas.order_daily_his set updated_by_username = ? , updated_date = now() , updated_by = ? where filing_no = ? and item_no = ?";
			$update = $db->prepare($query);
			$update->bindParam(1, $username, PDO::PARAM_STR);
			$update->bindParam(2, $updated_by, PDO::PARAM_STR);
			$update->bindParam(3, $filing_no, PDO::PARAM_STR);
			$update->bindParam(4, $item_no, PDO::PARAM_STR);
			$update_res = $update->execute();
			
			return $update_res;
		}
	}
	
	function delete_judgement($schemas,$db,$filing_no,$order_id,$updated_date,$updated_by,$username){
		$query = "insert into $schemas.order_detail_his (select * from $schemas.order_detail where filing_no = ? and order_id = ?)";
		$insert = $db->prepare($query);
		$insert->bindParam(1, $filing_no, PDO::PARAM_STR);
		$insert->bindParam(2, $order_id, PDO::PARAM_STR);
		$res = $insert->execute();
		if($res){
			
			$query = "delete from $schemas.order_detail where filing_no = ? and order_id = ?";
			$delete = $db->prepare($query);
			$delete->bindParam(1, $filing_no, PDO::PARAM_STR);
			$delete->bindParam(2, $order_id, PDO::PARAM_STR);
			$dlt_res = $delete->execute();
			
			$query = "update $schemas.order_detail_his set updated_by_username = ? , updated_date = ? , updated_by = ? where filing_no = ? and order_id = ?";
			$update = $db->prepare($query);
			$update->bindParam(1, $username, PDO::PARAM_STR);
			$update->bindParam(2, $updated_date, PDO::PARAM_STR);
			$update->bindParam(3, $updated_by, PDO::PARAM_STR);
			$update->bindParam(4, $filing_no, PDO::PARAM_STR);
			$update->bindParam(5, $order_id, PDO::PARAM_STR);
			$update_res = $update->execute();
			return $update_res;
		}
	}
	
	/* function change_status($schemas,$db,$filing_no,$order_id){
		$query = "insert into $schemas.case_proceeding_his (select * from $schemas.case_proceeding where filing_no = ? and listing_date = ?)";
		$insert = $db->prepare($query);
		$insert->bindParam(1, $filing_no, PDO::PARAM_STR);
		$insert->bindParam(2, $listing_date, PDO::PARAM_STR);
		$res = $insert->execute();
		if($res){
			$query = "delete from $schemas.case_proceeding where filing_no = ? and listing_date = ?";
			$delete = $db->prepare($query);
			$delete->bindParam(1, $filing_no, PDO::PARAM_STR);
			$delete->bindParam(2, $listing_date, PDO::PARAM_STR);
			$delete_res = $delete->execute();
		}
		return $delete_res;
	} */
	
	function change_case_status($schemas,$db,$filing_no,$status,$entry_date,$user_id,$username,$action = ''){
		//$action = "status changed to $status";
		$query = "insert into $schemas.case_detail_log (filing_no,case_no,case_year,case_type,status,entry_date,regis_date,dt_of_filing,ia_ma_filing_no,alter_date,alter_login_id,changes_type) (select filing_no,case_no,case_year,case_type,status,entry_date,regis_date,dt_of_filing,ia_ma_filing_no,?,?,? from $schemas.case_detail where filing_no = ?)";
		$insert = $db->prepare($query);
		$insert->bindParam(1, $entry_date, PDO::PARAM_STR);
		$insert->bindParam(2, $user_id, PDO::PARAM_STR);
		$insert->bindParam(3, $action, PDO::PARAM_STR);
		$insert->bindParam(4, $filing_no, PDO::PARAM_STR);
		$res = $insert->execute();
		if($res){
			$query = "update $schemas.case_detail set status = ? where filing_no = ?";
			$update = $db->prepare($query);
			$update->bindParam(1, $status, PDO::PARAM_STR);
			$update->bindParam(2, $filing_no, PDO::PARAM_STR);
			$update_res = $update->execute();
		}
		return $update_res;
	}
	
	
function change_order_status($schemas,$db,$filing_no,$item_no,$updated_date,$updated_by,$username,$new_status){
		$query = "insert into $schemas.order_daily_his (select * from $schemas.order_daily where filing_no = ? and item_no = ?)";
		$insert = $db->prepare($query);
		$insert->bindParam(1, $filing_no, PDO::PARAM_STR);
		$insert->bindParam(2, $item_no, PDO::PARAM_STR);
		$res = $insert->execute();
		if($res){	
			$query = "update $schemas.order_daily set updated_by_username = ? , updated_date = now() , updated_by = ?, order_type = ? where filing_no = ? and item_no = ?";
			$update = $db->prepare($query);
			$update->bindParam(1, $username, PDO::PARAM_STR);
			$update->bindParam(2, $updated_by, PDO::PARAM_STR);
			$update->bindParam(3, $new_status, PDO::PARAM_STR);
			$update->bindParam(4, $filing_no, PDO::PARAM_STR);
			$update->bindParam(5, $item_no, PDO::PARAM_STR);
			$update_res = $update->execute();
			
			return $update_res;
		}
	}
	
	function delete_disposal($schemas,$db,$filing_no,$listing_date,$updated_date,$updated_by,$username){
		$query = "update $schemas.case_disposal set updated_by_user_name = ? , updated_at = ? , updated_by = ? where filing_no = ?";
		$update = $db->prepare($query);
		$update->bindParam(1, $username, PDO::PARAM_STR);
		$update->bindParam(2, $updated_date, PDO::PARAM_STR);
		$update->bindParam(3, $updated_by, PDO::PARAM_STR);
		$update->bindParam(4, $filing_no, PDO::PARAM_STR);
		$update_res = $update->execute();
		
		if($update_res){
			
		$query = "insert into $schemas.case_disposal_his (select * from $schemas.case_disposal where filing_no = ?)";
		$insert = $db->prepare($query);
		$insert->bindParam(1, $filing_no, PDO::PARAM_STR);
		$res = $insert->execute();
		if($res){
			$query = "delete from $schemas.case_disposal where filing_no = ?";
			$delete = $db->prepare($query);
			$delete->bindParam(1, $filing_no, PDO::PARAM_STR);
			$delete_res = $delete->execute();
		}
		return $delete_res;
		}
	}
	
	//  for case updatation and deletion
	
	function remove_case($schemas,$db,$dbonline,$filing_no,$case_type,$case_type_array,$display,$user_id,$flag_type,$ipaddress=''){
		$query = "select updateLogCIS('$filing_no','$flag_type','$user_id','$ipaddress');";
		$select = $db->prepare($query);
		$res = $select->execute();
		if(in_array($case_type,$case_type_array)){
			$table = 'case_detail_ma_ia';
			$column = 'filing_no_ia_ma';
		}else{
			$table = 'e_case_detail';
			$column = 'filing_no';
		}
		$query = "update $table set display = ? where $column = ?";
		$update = $dbonline->prepare($query);
		$update->bindParam(1, $display, PDO::PARAM_STR);
		$update->bindParam(2, $filing_no, PDO::PARAM_STR);
		$update_res = $update->execute();
		return true;
	}
	
	function check_case_no_exist_or_not($schema,$db,$filing_no,$case_type,$case_year,$new_case_no){
		$query = "select count(*) as count from $schema.case_detail where case_no = ? and case_year = ? and case_type = ?";
		$select = $db->prepare($query);
		$select->bindParam(1, $new_case_no, PDO::PARAM_STR);
		$select->bindParam(2, $case_year, PDO::PARAM_STR);
		$select->bindParam(3, $case_type, PDO::PARAM_STR);
		$select->execute();
		$res = $select->fetchColumn();
		return $res;
	}
	
	function update_case_no($schema,$db,$filing_no,$case_type,$case_year,$new_case_no,$flag_type,$user_id,$ipaddress=''){
		$query = "select updateLogCIS('$filing_no','$flag_type','$user_id','$ipaddress');";
		$select = $db->prepare($query);
		$res = $select->execute();
		
		$query = "update $schema.case_detail set case_no = ? where filing_no = ? and case_year = ? and case_type = ?";
		$update = $db->prepare($query);
		$update->bindParam(1, $new_case_no, PDO::PARAM_STR);
		$update->bindParam(2, $filing_no, PDO::PARAM_STR);
		$update->bindParam(3, $case_year, PDO::PARAM_STR);
		$update->bindParam(4, $case_type, PDO::PARAM_STR);
		$res = $update->execute();
		return $res;
	}
	
	function get_all_proceeding($schemas,$db,$filing_no){
		$query = "select cp.*,cd.case_no, cd.case_year, cd.status, ct.case_type_desc,cd.pet_name,cd.res_name,mp.purpose_name from 
					$schemas.case_proceeding as cp 
					left join $schemas.case_detail as cd on cd.filing_no = cp.filing_no
					left join case_type as ct on ct.id = cd.case_type
					left join $schemas.master_purpose as mp on mp.purpose_code = cp.purpose
					where cp.filing_no = ? order by cp.listing_date desc";
		$data = $db->prepare($query);
		$data->bindParam(1, $filing_no, PDO::PARAM_STR);
		$data->execute();
		$data = $data->fetchAll();
		return $data;
	}
	
	// end case updatation and deletion
	
}


?>