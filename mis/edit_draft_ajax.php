<?php 
     
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
include("../master/functions.php");
//include("../master/dbfunction.php");
if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$schemas=htmlspecialchars($_SESSION['schema_name']);
	$username = htmlspecialchars($_SESSION['user_actual_name']);
	$data = $_POST;
	$type = $data['type'];
	$filing_no = $data['filing_no'];
	$listing_date = $data['listing_date'];
	$bench_nature = $data['bench_nature'];
	$court_no = $data['court_no'];
	$list_flag = $data['list_flag'];
	$bench_no = $data['bench_no'];
	
	if($type == 'get_edit_data'){

?>	
	<div class="container">
		<form class="form-inline update_draft_form">
			<input type="hidden" name="type" value="update_draft_data">
			<input type="hidden" name="filing_no" value="<?php echo $filing_no; ?>">
			<input type="hidden" name="listing_date" value="<?php echo $listing_date; ?>">
			<input type="hidden" name="bench_nature" value="<?php echo $bench_nature; ?>">
			<input type="hidden" name="court_no" value="<?php echo $court_no; ?>">
			<input type="hidden" name="list_flag" value="<?php echo $list_flag; ?>">
			<input type="hidden" name="action" value="save">

		  <div class="form-group">
			<label for="pwd">Purpose:</label>
			<?php
			$display = "TRUE";
			$purpose = $db->prepare("select purpose_code,purpose_name from $schemas.master_purpose where display = ?");
			$purpose->bindParam(1, $display, PDO::PARAM_INT);
			$purpose->execute();
			$purpose = $purpose->fetchAll(); 
			
			$case_allocation_data = $db->prepare("select purpose,remarks from $schemas.case_allocation_temp where filing_no = ? and listing_date = ? and court_no = ?");
			$case_allocation_data->bindParam(1, $filing_no, PDO::PARAM_INT);
			$case_allocation_data->bindParam(2, $listing_date, PDO::PARAM_INT);
			$case_allocation_data->bindParam(3, $court_no, PDO::PARAM_INT);
			$case_allocation_data->execute();
			$case_allocation_data = $case_allocation_data->fetchAll();
			$case_allocation_data = array_shift($case_allocation_data);
	
			?>
			<select class="form-control" name="purpose" required id="purpose">
				<?php 
				foreach($purpose as $key=>$value){ ?>
					<option value="<?php echo $value['purpose_code'] ?> " <?php echo ($case_allocation_data['purpose'] == $value['purpose_code'])?'selected':''; ?> ><?php echo $value['purpose_name'] ?></option>"
				<?php }
				?>
			</select>
		  </div>
		  <div class="form-group">
			<label for="pwd">Remark:</label>
			<textarea cols="60" rows="2" class="form-control" name="remark" id="remark"> <?php echo $case_allocation_data['remarks']; ?> </textarea>
		  </div>
		  <button type="button" class="btn btn-success" name="submit" onClick="return submit_form();">Submit</button>
		</form>
	</div>
<?php 	
}

if($type == 'update_draft_data'){
	$purpose = $data['purpose'];
	$remark = $data['remark'];
	
	$his = $db->prepare("insert into $schemas.case_allocation_his_temp select * from $schemas.case_allocation_temp where filing_no = ? and listing_date = ? and court_no = ?");
	$his->bindParam(1, $filing_no, PDO::PARAM_INT);
	$his->bindParam(2, $listing_date, PDO::PARAM_INT);
	$his->bindParam(3, $court_no, PDO::PARAM_INT);
	$his->execute();
	
	
	$update = $db->prepare("update $schemas.case_allocation_temp set purpose = ? ,remarks = ? where filing_no = ? and listing_date = ? and court_no = ?");
	$update->bindParam(1, $purpose, PDO::PARAM_INT);
	$update->bindParam(2, $remark, PDO::PARAM_INT);
	$update->bindParam(3, $filing_no, PDO::PARAM_INT);
	$update->bindParam(4, $listing_date, PDO::PARAM_INT);
	$update->bindParam(5, $court_no, PDO::PARAM_INT);
	$res = $update->execute();

	if($res){
		echo $res;
	}else{
	echo "some problem occured"; die;
	}
	
}

if($type == 'back_to_fresh_cases'){
	$response = array('status'=>0, 'message'=>'Some error Occurred');
	 try{
	$db->beginTransaction();  // begin transaction
	$is_proceeded = $db->prepare("select count(*) as count from $schemas.case_proceeding where filing_no = ?");
	$is_proceeded->bindParam(1, $filing_no, PDO::PARAM_INT);
	$is_proceeded->execute();
	$count = $is_proceeded->fetchColumn();
	if($count > 0){
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
			$update = $db->prepare("update $schemas.case_allocation_temp set listed = ? , listing_date = ? where filing_no = ? and listing_date = ? and court_no = ?");
			$update->bindParam(1, $listed, PDO::PARAM_INT);
			$update->bindParam(2, $last_listing_date, PDO::PARAM_INT);
			$update->bindParam(3, $filing_no, PDO::PARAM_INT);
			$update->bindParam(4, $listing_date, PDO::PARAM_INT);
			$update->bindParam(5, $court_no, PDO::PARAM_INT);
			$rr = $update->execute();
			
			if($rr){
				$response = array('status'=>1, 'message'=>'Case successfully removed and back to previous listing date');
			}
		}
		
		//$response = array('status'=>1, 'message'=>'This case can not be back to fresh cases listing, please give next date to this case');
	}else{
		$his = $db->prepare("insert into $schemas.case_allocation_his_temp select * from $schemas.case_allocation_temp where filing_no = ? and listing_date = ? and court_no = ?");
		$his->bindParam(1, $filing_no, PDO::PARAM_INT);
		$his->bindParam(2, $listing_date, PDO::PARAM_INT);
		$his->bindParam(3, $court_no, PDO::PARAM_INT);
		$res = $his->execute();
		if($res){
			$delete = $db->prepare("delete from $schemas.case_allocation_temp where filing_no = ? and listing_date = ? and court_no = ?");
			$delete->bindParam(1, $filing_no, PDO::PARAM_INT);
			$delete->bindParam(2, $listing_date, PDO::PARAM_INT);
			$delete->bindParam(3, $court_no, PDO::PARAM_INT);
			$delete->execute();
			
			$legal_aid = NULL;
			$update = $db->prepare("update $schemas.case_detail set legal_aid = ? where filing_no = ?");
			$update->bindParam(1, $legal_aid, PDO::PARAM_STR);
			$update->bindParam(2, $filing_no, PDO::PARAM_STR);
			$rr = $update->execute();
			if($rr){
				$response = array('status'=>1, 'message'=>'Case successfully back to fresh cases listing ');
			}
		}
		
	}
	$db->commit();
	 }catch(Exception $e){
		$db->rollBack();
	}
	echo json_encode($response); die;
	
}

if($type == 'final_causelist'){
	$data = $_POST;
	$listing_date = $data['listing_date'];
	$bench_nature = $data['bench_nature'];
	$court_no = $data['court_no'];
	$list_flag = $data['list_flag'];
	$response = array('status'=>0, 'message'=>'Some error Occurred');
	try{
	$db->beginTransaction();  // begin transaction
	$sql_pr2="delete from $schemas.case_allocation  where listing_date=? and bench_nature = ? and court_no = ? and list_flag = ?";
	$sth=$db->prepare($sql_pr2);
	$sth->bindParam(1, $listing_date, PDO::PARAM_STR);
	$sth->bindParam(2, $bench_nature, PDO::PARAM_STR);
	$sth->bindParam(3, $court_no, PDO::PARAM_STR);
	$sth->bindParam(4, $list_flag, PDO::PARAM_STR);
	$sth->execute();
	
	$sql_pr1="insert into $schemas.case_allocation select * from $schemas.case_allocation_temp where listing_date=? and bench_nature = ? and court_no = ? and list_flag = ?";
    $sth2=$db->prepare($sql_pr1);
	$sth2->bindParam(1, $listing_date, PDO::PARAM_STR);
	$sth2->bindParam(2, $bench_nature, PDO::PARAM_STR);
	$sth2->bindParam(3, $court_no, PDO::PARAM_STR);
	$sth2->bindParam(4, $list_flag, PDO::PARAM_STR);
	$sth2->execute();
	
	$sql="select filing_no from $schemas.case_allocation where listing_date=? and bench_nature = ? and court_no = ? and list_flag = ?";
	$email=$db->prepare($sql);
	$email->bindParam(1, $listing_date, PDO::PARAM_STR);
	$email->bindParam(2, $bench_nature, PDO::PARAM_STR);
	$email->bindParam(3, $court_no, PDO::PARAM_STR);
	$email->bindParam(4, $list_flag, PDO::PARAM_STR);
	$email->execute();
	$res = $email->fetchAll();
   foreach($res as $f)
   {
	    $filing_no =$f['filing_no'];
	   
	   //send sms
	  /* SMS  Level 3 case number*/
	  
	  
	  
		$check_sql =$db->prepare("select rep_code from e_more_representative where filing_no =? and party_flag='P' and party_serial_no='1'");
		$check_sql->bindParam(1, $filing_no, PDO::PARAM_STR);
		$check_sql->execute();
		 $pet_adv_code= $check_sql->fetchColumn();


		if($pet_adv_code=='')
		{
			$pet_adv_code='0';
		}
		if($pet_adv_code >0)
		{
		$stqq12 = $db->prepare("select rep_name from e_master_advocate where id=?");
		$stqq12->bindParam(1, $pet_adv_code, PDO::PARAM_INT);
		$stqq12->execute();
		$pet_adv_name = $stqq12->fetchColumn();	

		 
		$stqq11 = $db->prepare("select email from e_master_advocate where id=?");
		$stqq11->bindParam(1, $pet_adv_code, PDO::PARAM_INT);
		$stqq11->execute();
		$pet_adv_email = $stqq11->fetchColumn();
			
		$stqq23 = $db->prepare("select mobile from e_master_advocate where id=?");
		$stqq23->bindParam(1, $pet_adv_code, PDO::PARAM_INT);
		$stqq23->execute();
		$pet_adv_mobile = $stqq23->fetchColumn();	

			
		}

		 $adv_party='R';
		$adv_party_serial='1';
		$check_sql1 =$db->prepare("select rep_code from e_more_representative where filing_no =? and party_flag=? and party_serial_no=?");
		$check_sql1->bindParam(1, $filing_no, PDO::PARAM_STR);
		$check_sql1->bindParam(2, $adv_party, PDO::PARAM_STR);
		$check_sql1->bindParam(3, $adv_party_serial, PDO::PARAM_STR);
		$check_sql1->execute();
		$res_adv_code= $check_sql1->fetchColumn();
		if($res_adv_code=='')
		{
			$res_adv_code='0';
		}
		if($res_adv_code >0)
		{
		$stqq121 = $db->prepare("select rep_name from e_master_advocate where id=?");
		$stqq121->bindParam(1, $res_adv_code, PDO::PARAM_INT);
		$stqq121->execute();
		$res_adv_name = $stqq121->fetchColumn();	

		 
		$stqq122 = $db->prepare("select email from e_master_advocate where id=?");
		$stqq122->bindParam(1, $res_adv_code, PDO::PARAM_INT);
		$stqq122->execute();
		 $res_adv_email = $stqq122->fetchColumn();	
		$stqq123 = $db->prepare("select mobile from e_master_advocate where id=?");
		$stqq123->bindParam(1, $res_adv_code, PDO::PARAM_INT);
		$stqq123->execute();
		$res_adv_mobile = $stqq123->fetchColumn();	

			
		}

		  $sql2="select * from $schemas.case_detail where filing_no='$filing_no'";
		   foreach($db->query($sql2) as $f2)
		   {
			   $case_case_type =$f2['case_type'];
			   $case_case_no =$f2['case_no'];
			   $case_case_year =$f2['case_year'];
			  $case_case_location =$f2['bench_location'];
			  $pet_name = $f2['pet_name'];
			  $res_name = $f2['res_name'];
		   }
		   
		   
		   
		   
		   
		   
		 $lcode ="select short_name from $schemas.bench_location where bench_location_code ='$case_case_location'";
		$lcode=$db->prepare($lcode);
		$lcode->execute();
		$lcodename = $lcode->fetchColumn(); 


		 $lcode1 ="select bench_location_name from $schemas.bench_location where bench_location_code ='$case_case_location'";
		$lcode1=$db->prepare($lcode1);
		$lcode1->execute();
		$sms_lcodename = $lcode1->fetchColumn(); 

		if($case_case_type > 0)
		{
		$stQ = $db->prepare("select short_name from case_type where id = ?");
		$stQ->bindParam(1, $case_case_type, PDO::PARAM_STR);
		$stQ->execute();
		$case_type_short_name=$stQ->fetchColumn();
		}
		  $case_numaa = $case_case_no;
		$case_year1aa = $case_case_year;
				$case_num1aa=ltrim($case_numaa,0); 
		   
		$CASE_NO = htmlspecialchars(strtoupper($case_type_short_name).'/'.$case_num1aa.'('.$lcodename.')'.$case_year1aa); 
		  
		$subject="Application/Petition registered with Case No:".$CASE_NO;


		$sql21="select * from $schemas.case_allocation_temp where filing_no='$filing_no' and listing_date='$listing_date'";
		   foreach($db->query($sql21) as $f21)
		   {
			   $sms_court_no =$f21['court_no'];
			   
		   }



			   
			   list($Y,$m,$d) =explode('-',$listing_date);
		$date_new1 =$d.'/'.$m.'/'.$Y;
			   
			  //$email_text="Your Application/Petition at NGT ".$lcodename." with Filing Number:".$filing_no." is registered with Case Number:".$CASE_NO."and will be listed on date:".$date_new1." This is a Computer Generated message, Please do not reply";
		//$msg555="Your application/Petition at NGT ".$lcodename." with Filing No.:".$filing_no."is Registered with Case No.".$CASE_NO." and will be listed on date:".$date_new1;

		 $email_text="Case titled ".$pet_name." Vs ".$res_name." is filed at NGT (".$sms_lcodename.") and alloted case Number :".$lcodename."/".$case_type_short_name."/".$case_num1aa."/".$case_year1aa." will be listed on date :".$date_new1." before the Bench( Court No:".$sms_court_no.") This is a computer generated message, Please do not reply";
		$sms_detail=$lcodename."/".$case_type_short_name."/".$case_num1aa."/".$case_year1aa."[".$pet_name." Vs ".$res_name."]";
		  $msg555="Case Number:".$sms_detail." to be listed on date: ".$date_new1." before (court No:".$sms_court_no.")" ;
		$sdsdsds = fn_sms($db, '6', '', $filing_no, $subject, $msg555, $email_text);
		  /* echo $email_text; 

		 $sql ="insert into sms(filing_no,case_number,msg,pet_adv_name,pet_adv_mob_no,pet_adv_email,pet_mobile,res_mobile,pet_name,res_name,res_adv_code,pet_adv_code,res_adv_name,res_adv_mob_no,subject,email_text,res_adv_email,pet_email,res_email,send_flag,entry_date,sms_flag,listing_date) 
		VALUES('$filing_no','$CASE_NO','$msg555','$pet_adv_name','$pet_adv_mobile','$pet_adv_email','$pet_mobile','$res_mobile','$pet_name','$res_name','$res_adv_code','$pet_adv_code','$res_adv_name','$res_adv_mobile','$subject','$email_text','$res_adv_email','$pet_email','$res_email','0','$server_date','L','$date_new')";

		$st = $db->prepare($sql);
		$st->execute();    */
			   
		   }

	$db->commit();
	$response = array("status"=>1,"message"=>"Cause list finalized");
	}catch(Exception $e){
		$db->rollBack();
	}
	echo json_encode($response); die;
}

if($type == 'set_child_cases'){ 
$listed_fn = $data['listed_fn'];
?>
		<div class="container">
		<form class="save_child_cases">
			<input type="hidden" name="type" value="save_child_cases">
			<input type="hidden" name="filing_no" value="<?php echo $filing_no; ?>">
			<input type="hidden" name="listing_date" value="<?php echo $listing_date; ?>">
			<input type="hidden" name="bench_nature" value="<?php echo $bench_nature; ?>">
			<input type="hidden" name="court_no" value="<?php echo $court_no; ?>">
			<input type="hidden" name="list_flag" value="<?php echo $list_flag; ?>">
			<input type="hidden" name="bench_no" value="<?php echo $bench_no; ?>">
			<input type="hidden" name="listed_fn" value="<?php echo $listed_fn; ?>">
			<!--<div>Uncheck to remove case</div>-->
		  
			<?php
			$cases = all_child_cases($schemas,$db,$filing_no,'P');
			$selected_child_cases = selected_child_cases($schemas,$db,$filing_no,1,$listing_date,$bench_no,$list_flag,$court_no,'');
			if(!empty($selected_child_cases)){
			$selected_child_cases_array = array_map(function($element) {
							  return $element['filing_no'];
							}, $selected_child_cases);
				if(!empty($cases)){
					echo "<div class='form-group'>";
				foreach($cases as $key=>$case){
				?>
					<input type='checkbox' name="child_cases[]" <?php echo (in_array($case['filing_no'],$selected_child_cases_array))?'checked':''; ?> value='<?php echo $case['filing_no']; ?>'> <?php echo $case['case_type_short_name'].' NO '.$case['case_no']."/".$case['case_year']; ?><br/>
			<?php } ?> 
				 </div>
		  <button type="button" class="btn btn-success" name="submit" onClick="return submit_form2();">Submit</button>
			<?php }} else{
				if(!empty($cases)){
					echo "<div class='form-group'>";
				foreach($cases as $key=>$case){
				?>
					<input type='checkbox' name="child_cases[]" value='<?php echo $case['filing_no']; ?>'> <?php echo $case['case_type_short_name'].' NO '.$case['case_no']."/".$case['case_year']; ?><br/>
			<?php } ?>
			 </div>
		  <button type="button" class="btn btn-success" name="submit" onClick="return submit_form2();">Submit</button>
				<?php }	}				//echo "<pre>";print_r($selected_child_cases_array);
				?>
		</form>
	</div>
<?php  }

if($type == 'save_child_cases'){
	$filing_no = $data['filing_no'];
	$listing_date = $data['listing_date'];
	$bench_nature = $data['bench_nature'];
	$court_no = $data['court_no'];
	$list_flag = $data['list_flag'];
	$bench_no = $data['bench_no'];
	$listed_fn = $data['listed_fn'];
	$save_log = save_selected_child_cases_log($schemas,$db,$filing_no,$listing_date,$bench_no,$list_flag,$court_no);
	if($save_log){
		foreach($data['child_cases'] as $key=>$child_filing_no){
			$insert_cases = insert_selected_child_cases($schemas,$db,$filing_no,$child_filing_no,$listing_date,$bench_no,$list_flag,$court_no,$bench_nature,$cur_date,$sessionUserType,$display='1',$username,$listed_fn);
		}
	}
	die;
}

if($type == 'show_advocates'){ 
?>
		<div class="container">
		<form class="save_advocates">
			<input type="hidden" name="type" value="save_advocates">
			<input type="hidden" name="filing_no" value="<?php echo $filing_no; ?>">
			<input type="hidden" name="listing_date" value="<?php echo $listing_date; ?>">
			<input type="hidden" name="bench_nature" value="<?php echo $bench_nature; ?>">
			<input type="hidden" name="court_no" value="<?php echo $court_no; ?>">
			<input type="hidden" name="list_flag" value="<?php echo $list_flag; ?>">
			<input type="hidden" name="bench_no" value="<?php echo $bench_no; ?>">
			<!--<div>Uncheck to remove case</div>-->
		  
			<?php
			$pet_flag = 'P';
			$advocate_list = $db->prepare("select a.id,a.rep_code,a.party_flag,b.rep_name,a.remarks,a.show_in_causelist from e_more_representative as a 
				inner join e_master_advocate as b on b.id = a.rep_code where a.filing_no = ? and a.party_flag = ?
				UNION ALL
				select a.id,a.rep_code,a.party_flag,b.rep_name,a.remarks,a.show_in_causelist from e_more_representative_gst_nodal as a 
				inner join e_master_advocate as b on b.id = a.rep_code where a.filing_no = ? and a.party_flag = ?");
			$advocate_list->bindParam(1, $filing_no, PDO::PARAM_STR);
			$advocate_list->bindParam(2, $pet_flag, PDO::PARAM_STR);
			$advocate_list->bindParam(3, $filing_no, PDO::PARAM_STR);
			$advocate_list->bindParam(4, $pet_flag, PDO::PARAM_STR);
			$advocate_list->execute();
			$pet_advocate_list = $advocate_list->fetchAll();
			
			$res_flag = 'R';
			$advocate_list = $db->prepare("select a.id,a.rep_code,a.party_flag,b.rep_name,a.remarks,a.show_in_causelist from e_more_representative as a 
				inner join e_master_advocate as b on b.id = a.rep_code where a.filing_no = ? and a.party_flag = ?
				UNION ALL
				select a.id,a.rep_code,a.party_flag,b.rep_name,a.remarks,a.show_in_causelist from e_more_representative_gst_nodal as a 
				inner join e_master_advocate as b on b.id = a.rep_code where a.filing_no = ? and a.party_flag = ?");
			$advocate_list->bindParam(1, $filing_no, PDO::PARAM_STR);
			$advocate_list->bindParam(2, $res_flag, PDO::PARAM_STR);
			$advocate_list->bindParam(3, $filing_no, PDO::PARAM_STR);
			$advocate_list->bindParam(4, $res_flag, PDO::PARAM_STR);
			$advocate_list->execute();
			$res_advocate_list = $advocate_list->fetchAll();
			echo "Authorized representative Of petitioners <br/>";
			if(!empty($pet_advocate_list)){
					echo "<div class='form-group'>";
				foreach($pet_advocate_list as $key=>$pet_advocates){
				?>
					<input type='checkbox' name="pet_advs[]" <?php echo ($pet_advocates['show_in_causelist'])?'checked':''; ?> value='<?php echo $pet_advocates['id']; ?>'> <?php echo $pet_advocates['rep_name']; ?> <input type='text' value='<?php echo $pet_advocates['remarks']; ?>' class="form_control" name='remark_<?php echo $pet_advocates['id']; ?>'><br/>
			<?php } ?> 
				 </div>
		  
			<?php } 
			echo "Authorized representative Of respondents <br/>";
			if(!empty($res_advocate_list)){
					echo "<div class='form-group'>";
				foreach($res_advocate_list as $key=>$res_advocates){
				?>
					<input type='checkbox' name="res_advs[]" <?php echo ($res_advocates['show_in_causelist'])?'checked':''; ?> value='<?php echo $res_advocates['id']; ?>'> <?php echo $res_advocates['rep_name']; ?> <input type='text' value='<?php echo $res_advocates['remarks']; ?>' class="form_control" name='remark_<?php echo $res_advocates['id']; ?>'><br/>
			<?php } ?> 
				 </div>
		  
			<?php } 			//echo "<pre>";print_r($selected_child_cases_array);
				?>
				<button type="button" class="btn btn-success" name="submit" onClick="return submit_form3();">Submit</button>
		</form>
	</div>
<?php  }


if($type == 'save_advocates'){
	try{
	$db->beginTransaction();
	$filing_no = $data['filing_no'];
	$listing_date = $data['listing_date'];
	$bench_nature = $data['bench_nature'];
	$court_no = $data['court_no'];
	$list_flag = $data['list_flag'];
	$bench_no = $data['bench_no'];
	$pet_advocates_to_show = $data['pet_advs'];
	$res_advocates_to_show = $data['res_advs'];
	$res_flag = 'R';
	$pet_flag = 'P';
	$true = TRUE;
	$false = false;
	if(!empty($pet_advocates_to_show)){
		$pet_adv_ids = implode(",",$pet_advocates_to_show);
		$show_pet_adv = $db->prepare("update e_more_representative set show_in_causelist = ? where filing_no = ? and party_flag = ? and id in ($pet_adv_ids)");
		$show_pet_adv->bindParam(1, $true, PDO::PARAM_BOOL);
		$show_pet_adv->bindParam(2, $filing_no, PDO::PARAM_STR);
		$show_pet_adv->bindParam(3, $pet_flag, PDO::PARAM_STR);
		$show_pet_adv->execute();

		$show_pet_adv = $db->prepare("update e_more_representative_gst_nodal set show_in_causelist = ? where filing_no = ? and party_flag = ? and id in ($pet_adv_ids)");
		$show_pet_adv->bindParam(1, $true, PDO::PARAM_BOOL);
		$show_pet_adv->bindParam(2, $filing_no, PDO::PARAM_STR);
		$show_pet_adv->bindParam(3, $pet_flag, PDO::PARAM_STR);
		$show_pet_adv->execute();
		
		$hide_pet_adv = $db->prepare("update e_more_representative set show_in_causelist = ? where filing_no = ? and party_flag = ? and id not in ($pet_adv_ids)");
		$hide_pet_adv->bindParam(1, $false, PDO::PARAM_BOOL);
		$hide_pet_adv->bindParam(2, $filing_no, PDO::PARAM_STR);
		$hide_pet_adv->bindParam(3, $pet_flag, PDO::PARAM_STR);
		$hide_pet_adv->execute();

		$hide_pet_adv = $db->prepare("update e_more_representative_gst_nodal set show_in_causelist = ? where filing_no = ? and party_flag = ? and id not in ($pet_adv_ids)");
		$hide_pet_adv->bindParam(1, $false, PDO::PARAM_BOOL);
		$hide_pet_adv->bindParam(2, $filing_no, PDO::PARAM_STR);
		$hide_pet_adv->bindParam(3, $pet_flag, PDO::PARAM_STR);
		$hide_pet_adv->execute();
		
		foreach($pet_advocates_to_show as $k=>$adv){
			echo $adv;
			$remark = $data['remark_'.$adv];
			$up_pet_adv = $db->prepare("update e_more_representative set remarks = ? where filing_no = ? and party_flag = ? and id = ?");
			$up_pet_adv->bindParam(1, $remark, PDO::PARAM_BOOL);
			$up_pet_adv->bindParam(2, $filing_no, PDO::PARAM_STR);
			$up_pet_adv->bindParam(3, $pet_flag, PDO::PARAM_STR);
			$up_pet_adv->bindParam(4, $adv, PDO::PARAM_STR);
			$up_pet_adv->execute();

			$up_pet_adv = $db->prepare("update e_more_representative_gst_nodal set remarks = ? where filing_no = ? and party_flag = ? and id = ?");
			$up_pet_adv->bindParam(1, $remark, PDO::PARAM_BOOL);
			$up_pet_adv->bindParam(2, $filing_no, PDO::PARAM_STR);
			$up_pet_adv->bindParam(3, $pet_flag, PDO::PARAM_STR);
			$up_pet_adv->bindParam(4, $adv, PDO::PARAM_STR);
			$up_pet_adv->execute();
		}
	}else{
		$hide_all_pet_adv = $db->prepare("update e_more_representative set show_in_causelist = ? where filing_no = ? and party_flag = ?");
		$hide_all_pet_adv->bindParam(1, $false, PDO::PARAM_BOOL);
		$hide_all_pet_adv->bindParam(2, $filing_no, PDO::PARAM_STR);
		$hide_all_pet_adv->bindParam(3, $pet_flag, PDO::PARAM_STR);
		$hide_all_pet_adv->execute();

		$hide_all_pet_adv = $db->prepare("update e_more_representative_gst_nodal set show_in_causelist = ? where filing_no = ? and party_flag = ?");
		$hide_all_pet_adv->bindParam(1, $false, PDO::PARAM_BOOL);
		$hide_all_pet_adv->bindParam(2, $filing_no, PDO::PARAM_STR);
		$hide_all_pet_adv->bindParam(3, $pet_flag, PDO::PARAM_STR);
		$hide_all_pet_adv->execute();
	}
	if(!empty($res_advocates_to_show)){
		$res_adv_ids = implode(",",$res_advocates_to_show);
		$show_res_adv = $db->prepare("update e_more_representative set show_in_causelist = ? where filing_no = ? and party_flag = ? and id in ($res_adv_ids)");
		$show_res_adv->bindParam(1, $true, PDO::PARAM_BOOL);
		$show_res_adv->bindParam(2, $filing_no, PDO::PARAM_STR);
		$show_res_adv->bindParam(3, $pet_flag, PDO::PARAM_STR);
		$show_res_adv->execute();

		$show_res_adv = $db->prepare("update e_more_representative_gst_nodal set show_in_causelist = ? where filing_no = ? and party_flag = ? and id in ($res_adv_ids)");
		$show_res_adv->bindParam(1, $true, PDO::PARAM_BOOL);
		$show_res_adv->bindParam(2, $filing_no, PDO::PARAM_STR);
		$show_res_adv->bindParam(3, $pet_flag, PDO::PARAM_STR);
		$show_res_adv->execute();
		
		$hide_res_adv = $db->prepare("update e_more_representative set show_in_causelist = ? where filing_no = ? and party_flag = ? and id not in ($res_adv_ids)");
		$hide_res_adv->bindParam(1, $false, PDO::PARAM_BOOL);
		$hide_res_adv->bindParam(2, $filing_no, PDO::PARAM_STR);
		$hide_res_adv->bindParam(3, $res_flag, PDO::PARAM_STR);
		$hide_res_adv->execute();

		$hide_res_adv = $db->prepare("update e_more_representative_gst_nodal set show_in_causelist = ? where filing_no = ? and party_flag = ? and id not in ($res_adv_ids)");
		$hide_res_adv->bindParam(1, $false, PDO::PARAM_BOOL);
		$hide_res_adv->bindParam(2, $filing_no, PDO::PARAM_STR);
		$hide_res_adv->bindParam(3, $res_flag, PDO::PARAM_STR);
		$hide_res_adv->execute();
		
		foreach($res_advocates_to_show as $k=>$adv){
			echo $adv;
			$remark = $data['remark_'.$adv];
			$up_res_adv = $db->prepare("update e_more_representative set remarks = ? where filing_no = ? and party_flag = ? and id = ?");
			$up_res_adv->bindParam(1, $remark, PDO::PARAM_BOOL);
			$up_res_adv->bindParam(2, $filing_no, PDO::PARAM_STR);
			$up_res_adv->bindParam(3, $res_flag, PDO::PARAM_STR);
			$up_res_adv->bindParam(4, $adv, PDO::PARAM_STR);
			$up_res_adv->execute();

			$up_res_adv = $db->prepare("update e_more_representative_gst_nodal set remarks = ? where filing_no = ? and party_flag = ? and id = ?");
			$up_res_adv->bindParam(1, $remark, PDO::PARAM_BOOL);
			$up_res_adv->bindParam(2, $filing_no, PDO::PARAM_STR);
			$up_res_adv->bindParam(3, $res_flag, PDO::PARAM_STR);
			$up_res_adv->bindParam(4, $adv, PDO::PARAM_STR);
			$up_res_adv->execute();
		}
	}else{
		$hide_all_res_adv = $db->prepare("update e_more_representative set show_in_causelist = ? where filing_no = ? and party_flag = ?");
		$hide_all_res_adv->bindParam(1, $false, PDO::PARAM_BOOL);
		$hide_all_res_adv->bindParam(2, $filing_no, PDO::PARAM_STR);
		$hide_all_res_adv->bindParam(3, $res_flag, PDO::PARAM_STR);
		$hide_all_res_adv->execute();

		$hide_all_res_adv = $db->prepare("update e_more_representative_gst_nodal set show_in_causelist = ? where filing_no = ? and party_flag = ?");
		$hide_all_res_adv->bindParam(1, $false, PDO::PARAM_BOOL);
		$hide_all_res_adv->bindParam(2, $filing_no, PDO::PARAM_STR);
		$hide_all_res_adv->bindParam(3, $res_flag, PDO::PARAM_STR);
		$hide_all_res_adv->execute();
	}
	$db->commit();
	
	}catch(Exception $e){
		$db->rollBack();
	}
	
	die;
}

}else{
echo "access problem"; die;
}

?>

<script>
function submit_form(){
	var form_data = $("form.update_draft_form").serialize();
	$.ajax({
            type: "POST",
            url: "edit_draft_ajax.php",
            data: form_data,
            success: function (data) {
				if(data){
					alert("Data Updated");
					$("#edit_draft_cause_list_model").modal("hide");
					//location.reload(true);
				}else{
				alert("some error occured");
				}
            },
            error: function (textStatus, errorThrown) {
               alert("error");
            }

        });
}

function submit_form2(){
	var form_data = $("form.save_child_cases").serialize();
	$.ajax({
            type: "POST",
            url: "edit_draft_ajax.php",
            data: form_data,
            success: function (data) {
				if(data){
					alert("Data Updated");
					$("#edit_draft_cause_list_model").modal("hide");
					location.reload(true);
				}else{
				alert("some error occured");
				}
            },
            error: function (textStatus, errorThrown) {
               alert("error");
            }

        });
}


function submit_form3(){
	var form_data = $("form.save_advocates").serialize();
	$.ajax({
            type: "POST",
            url: "edit_draft_ajax.php",
            data: form_data,
            success: function (data) {
				if(data){
					alert("Data Updated");
					$("#edit_draft_cause_list_model").modal("hide");
					location.reload(true);
				}else{
				alert("some error occured");
				}
            },
            error: function (textStatus, errorThrown) {
               alert("error");
            }

        });
}
</script>
