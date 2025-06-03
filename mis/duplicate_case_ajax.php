<?php

 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL); 

header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include "../db_inc1.php";
require_once '../custom/custom_function.php';
$user_type = $_SESSION['menuaccess_codeall'];
$user_id=htmlspecialchars($_SESSION['id']);
 $schemas = htmlspecialchars($_SESSION['schema_name']);
 $location_code = $_SESSION['location'];

if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: ../login.php");
    die();
}
setcookie("PHPSESSID", "", time() - 3600, "", "", true, true);
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {
	
	function display_filing_no($filing_no_display){
      $lastFour =  substr($filing_no_display,-4);
      $lastFive = substr($filing_no_display,-9,-4);
      $left = substr($filing_no_display,-16,-9);
      return $dis_fil_no = $left.'/<b>'.$lastFive.'/'.$lastFour.'</b>';

  }
  
  function last_proceeding_info($db,$schema,$filing_no){
		$last_listing_info = $db->prepare("select * from $schema.case_proceeding where filing_no = ? order by listing_date desc limit 1");
		$last_listing_info->bindParam(1, $filing_no, PDO::PARAM_INT);
		$last_listing_info->execute();
		$last_listing_info = $last_listing_info->fetchAll();
		return $last_listing_info;
	}
  
  /* function display_date($date){
		return date('d/m/Y',strtotime($date));
	} */
	
  function remove_cis_case($schemas,$db,$filing_no,$case_type,$display,$user_id,$flag_type,$ipaddress=''){
		 $query = "select updateLogCIS('$schemas','$filing_no','$flag_type','$user_id','$ipaddress');";
		$select = $db->prepare($query);
		$res = $select->execute();
		return $res;
	}
	
	function soft_delete_efiling($db,$filing_no,$display){
		$query = "update e_case_detail set display = ? where filing_no = ?";
		$update = $db->prepare($query);
		$update->bindParam(1, $display, PDO::PARAM_STR);
		$update->bindParam(2, $filing_no, PDO::PARAM_STR);
		$update_res = $update->execute();
		return $update_res;
	}
	
	function check_case_no_exist_or_not($schemas,$db,$filing_no,$case_type_to_change,$case_year_to_change,$case_no_to_change,$bench_location_code_to_change){
		$query = "select count(*) as count from $schemas.case_detail where case_no = ? and case_year = ? and case_type = ? and location_code = ?";
		$select = $db->prepare($query);
		$select->bindParam(1, $case_no_to_change, PDO::PARAM_STR);
		$select->bindParam(2, $case_year_to_change, PDO::PARAM_STR);
		$select->bindParam(3, $case_type_to_change, PDO::PARAM_STR);
		$select->bindParam(4, $bench_location_code_to_change, PDO::PARAM_STR);
		$select->execute();
		$res = $select->fetchColumn();
		return $res;
	}
	
	function update_case_no($schemas,$db,$filing_no,$new_case_type,$new_case_year,$new_case_no,$new_bench_location_code,$old_case_type,$old_case_year,$old_case_no,$old_bench_location_code,$flag_type,$user_id,$ipaddress=''){
		$query = "select updateLogCIS('$schemas','$filing_no','$flag_type','$user_id','$ipaddress');";
		$select = $db->prepare($query);
		$res = $select->execute();
		
		$query = "update $schemas.case_detail set case_no = ? , case_type = ? , case_year = ? , location_code = ? where filing_no = ? and case_year = ? and case_type = ? and case_no = ? and location_code = ?";
		$update = $db->prepare($query);
		$update->bindParam(1, $new_case_no, PDO::PARAM_STR);
		$update->bindParam(2, $new_case_type, PDO::PARAM_STR);
		$update->bindParam(3, $new_case_year, PDO::PARAM_STR);
		$update->bindParam(4, $new_bench_location_code, PDO::PARAM_STR);
		$update->bindParam(5, $filing_no, PDO::PARAM_STR);
		$update->bindParam(6, $old_case_year, PDO::PARAM_STR);
		$update->bindParam(7, $old_case_type, PDO::PARAM_STR);
		$update->bindParam(8, $old_case_no, PDO::PARAM_STR);
		$update->bindParam(9, $old_bench_location_code, PDO::PARAM_STR);
		$res = $update->execute();
		return $res;
	}
  
  if($_POST){
  $data = $_POST;
  }else{
	echo "Unautherised access"; die;
  }
 $type = $data['type'];
  if($type == 'view_cases') {
	 $case_no = $data['case_no'];
	$case_year = $data['case_year'];
	$case_type = $data['case_type'];
	$location_code = $data['location_code'];
	$zone_loc = $data['schema'];
    $q = "select cd.filing_no,cd.dt_of_filing,cd.regis_date,cd.pet_name,cd.res_name,cd.case_no,cd.case_year,cd.case_type,cd.location_code,cd.status,
		ct.case_type_desc as case_type_name,bl.short_name from $zone_loc.case_detail as cd
		left join case_type as ct on ct.id = cd.case_type
		left join $zone_loc.bench_location as bl on bl.city_id = cd.location_code
		where cd.case_type = ? and cd.case_year = ? and cd.case_no = ? and cd.location_code = ?";
	$sub_data = $db->prepare($q);

	$sub_data->bindParam(1, $case_type, PDO::PARAM_STR);
	$sub_data->bindParam(2, $case_year, PDO::PARAM_STR);
	$sub_data->bindParam(3, $case_no, PDO::PARAM_STR);
	$sub_data->bindParam(4, $location_code, PDO::PARAM_STR);

	$sub_data->execute();
	$sub_data = $sub_data->fetchAll();
	
	if(!empty($sub_data)){ ?>
		<table id="case_details" class='table'>
		<thead>
			<tr>
				<th>Sr. No</th>
				<th>Filing No</th>
				<th>Case No</th>
				<th>Location</th>
				<th>Pet Name</th>
				<th>Res Name</th>
				<th>Filing Date</th>
				<th>Reg. Date</th>
				<th>Status</th>
				<!--<th>Last Court</th>
				<th>Last Listing</th>
				
				<th>Action</th>-->
			</tr>
		</thead>
		<tbody>
		<?php	foreach($sub_data as $k=>$dup_data){
					$listing_dates = last_proceeding_info($db,$zone_loc,$dup_data['filing_no']);
					if(!empty($listing_dates)){
						$listing_dates = array_shift($listing_dates);
						$get_court_no=$last_court_no = $listing_dates['court_no']; 
						$last_listing_date = $listing_dates['listing_date'];
						$show_last_listing_date = display_date($last_listing_date);
						$next_listing_date = $listing_dates['next_list_date'];
						if($next_listing_date == '1111-11-11'){
							$show_next_listing_date = '';
						}else{
						$show_next_listing_date = display_date($next_listing_date);
						}
					}else{
						$get_court_no=$last_court_no=$show_last_listing_date = $show_next_listing_date = '';
					}
							$k_plus = $k+1;
					$case_status = 	($dup_data['status'] == 'P')?'Pending':'Disposed';
					
					$sthr=$db->prepare("select unique_id_no from e_case_detail  where filing_no=? ");
					$sthr->bindParam(1, $filing_no, PDO::PARAM_STR);
					$sthr->execute();
					$fileupload = $sthr->fetchColumn();
					
					
					if(empty($get_court_no)){
						$get_court_no=$db->prepare("select court_no  from $zone_loc.case_allocation_temp where filing_no=? order by listing_date desc limit 1");
						 $get_court_no->bindParam(1, $filing_no, PDO::PARAM_STR);
						 $get_court_no->execute();
						 $get_court_no = $get_court_no->fetchColumn();
					 
					 }
					 if(empty($get_court_no)){
						$get_court_no = '';
					 }

					$snoc = 1;
					$courtnoc = $get_court_no;
					$case_no = $dup_data['case_no'];
					$petnamec = $dup_data['pet_name'];
					$resnamec = $dup_data['res_name'];
				?>
					<tr id='rm_case<?php echo $dup_data['filing_no']; ?>'>
						<td><?php echo $k_plus; ?></td>
						<td><?php echo display_filing_no($dup_data['filing_no']); ?></td>
						<td><?php echo $dup_data['case_type_name'].'/'.$dup_data['case_no'].'('.$dup_data['short_name'].')'.'/'.$dup_data['case_year']; ?></td>
						<td><?php echo $dup_data['short_name']; ?></td>
						<td><?php echo $dup_data['pet_name']; ?></td>
						<td><?php echo $dup_data['res_name']; ?></td>
						<td><?php echo date('d/m/Y',strtotime($dup_data['dt_of_filing'])); ?></td>
						<td><?php echo date('d/m/Y',strtotime($dup_data['regis_date'])); ?></td>
						<!--<td><?php echo $last_court_no; ?></td>
						<td><?php echo $show_last_listing_date; ?></td>-->
						<td><?php echo $case_status; ?></td>
						<!--<td><input type="submit" value="Remove" class="btn btn-danger btn-sm" id="delete_case<?php echo $dup_data['filing_no']; ?>" name="delete_case<?php echo $dup_data['filing_no']; ?>" onClick="remove_case('<?php echo $dup_data['filing_no']; ?>','<?php echo $dup_data['case_type']; ?>','<?php echo $dup_data['location_code']; ?>','<?php echo $zone_loc; ?>')">
						<br/><br/><input type="submit" value="Edit" class="btn btn-primary btn-sm" id="edit_case<?php echo $dup_data['filing_no']; ?>" name="edit_case<?php echo $dup_data['filing_no']; ?>" onClick="edit_case('<?php echo $dup_data['filing_no']; ?>','<?php echo $dup_data['case_type']; ?>','<?php echo $dup_data['location_code']; ?>','<?php echo $zone_loc; ?>')">
						<br/><br/><a onclick="OpenDMSForm('https://efiling.nclt.gov.in/dms-ecourt/ecourt-search-within-dms','<?php echo '' ?>','<?php echo $fileupload; ?>','<?php echo $courtnoc; ?>','<?php echo $case_no; ?>','','<?php echo $petnamec ?>','<?php echo $petnamec . "   "; ?>Vs.<?php echo "   " . $resnamec; ?>','P','vVl/Az1yGsjOAG18WDeScg==','!TZFIMZbTiUtqXMfARJ1DGgyNicWFwYkwTA0ip/Q8Wns=')"
										style="cursor: pointer">
						<font color="#900C3F" size="3"><button type='button' class='btn btn-sm btn-primary'>View Docs</button></a>
						</td>-->
					</tr>
				<?php	} ?>
		</tbody>
		</table>
	
<?php 	} ?>
	  <script>
    function OpenDMSForm(url, val1, val2, val3, val4, val5, val6, val7, val8, val9, val10) {
        document.getElementById("frm").action = url;
        document.getElementById("itemno1").value = val1;
        document.getElementById("applno1").value = val2;
        document.getElementById("courtno1").value = val3;
        document.getElementById("caseno1").value = val4;
        document.getElementById("casetype1").value = val5;
        document.getElementById("partyname1").value = val6;
        document.getElementById("title1").value = val7;
        document.getElementById("status1").value = val8;
        document.getElementById("j_key1").value = val9;
        document.getElementById("j_securityKey1").value = val10;
        document.getElementById("frm").submit();
    }

 </script>
 <form action="" method="POST" target="_blank" id="frm">
    <input type="hidden" id="itemno1" name="itemno"  value=""/>
    <input type="hidden" id="applno1" name="applno" value=""/>
    <input type="hidden" id="courtno1" name="courtno" value=""/>
    <input type="hidden" id="caseno1" name="caseno" value="" />
    <input type="hidden" id="casetype1" name="casetype" value=""/>
    <input type="hidden" id="partyname1" name="partyname" value="">
    <input type="hidden" id="title1"name="title" value="">
    <input type="hidden" id="status1" name="status" value="">	
    <input type="hidden" id="j_key1"name="j_key" value=""> 
    <input type="hidden" id="j_securityKey1" name="j_securityKey" value="">
   
</form> 
<?php
 die; }
 
 if($type == 'remove_case'){
			try{  
				$db->beginTransaction();
			$response = array( 
				'status' => 0, 
				'message' => 'some error occured' 
				);
			$filing_no = $data['filing_no'];
			$case_type = $data['case_type'];
			$location_code = $data['location_code'];
			$schema_name = $data['schema'];
			$remove_case_from_cis = remove_cis_case($schemas,$db,$filing_no,$case_type,$display='false',$user_id,$flag_type = 'D',$ipaddress='');
			if($remove_case_from_cis){
			$soft_delete_efiling = soft_delete_efiling($db,$filing_no,$display='false');
			if($soft_delete_efiling){
			$response = array( 
					'status' => 1, 
					'message' => 'Case Removed' 
					);
			} 
			}
			
			$db->commit();
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
		
		if($type == 'change_case_no_form'){
			$filing_no = $data['filing_no'];
			$case_type = $data['case_type'];
			$location_code = $data['location_code'];
			$zone_loc = $data['schema'];
			$q = "select cd.filing_no,cd.dt_of_filing,cd.regis_date,cd.pet_name,cd.res_name,cd.case_no,cd.case_year,cd.case_type,cd.location_code,cd.status 
				from $zone_loc.case_detail as cd
				where cd.case_type = ? and cd.filing_no = ? and cd.location_code = ?";
			$case_data = $db->prepare($q);

			$case_data->bindParam(1, $case_type, PDO::PARAM_STR);
			$case_data->bindParam(2, $filing_no, PDO::PARAM_STR);
			$case_data->bindParam(3, $location_code, PDO::PARAM_STR);
			$case_data->execute();
			$case_data = $case_data->fetchAll();
			if(!empty($case_data)){
				$case_data = array_shift($case_data); 
				$current_case_no = $case_data['case_no'];
				$current_case_year = $case_data['case_year'];
				$current_case_type = $case_data['case_type'];
				$current_location_code = $case_data['location_code'];
				$filing_no = $case_data['filing_no'];
				
				$bench_locations_query = "select * from $zone_loc.bench_location";
				$bench_location_data = $db->prepare($bench_locations_query);
				$bench_location_data->execute();
				$bench_location_data = $bench_location_data->fetchAll();
				
				$case_type_query = "select * from case_type where status = 't'";
				$case_type_data = $db->prepare($case_type_query);
				$case_type_data->execute();
				$case_type_data = $case_type_data->fetchAll();
				?>
				<form name="update_case_no_form" id="update_case_no_form" method="POST" action="duplicate_case_ajax.php">
				<table id="case_details" class='table'>
				<thead>
					<tr>
						<th>Bench</th>
						<th>Case Type</th>
						<th>Case No</th>
						<th>Case Year</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<input type='hidden' id="type" class="form-control" name='type' value="change_case_no" />
				<input type='hidden' id="filing_no_to_change" class="form-control" name='filing_no_to_change' value="<?php echo $filing_no; ?>" />
				<input type='hidden' id="old_case_no" class="form-control" name='old_case_no' value="<?php echo $current_case_no; ?>" />
				<input type='hidden' id="old_case_type" class="form-control" name='old_case_type' value="<?php echo $current_case_type; ?>" />
				<input type='hidden' id="old_case_year" class="form-control" name='old_case_year' value="<?php echo $current_case_year; ?>" />
				<input type='hidden' id="old_location_code" class="form-control" name='old_location_code' value="<?php echo $current_location_code; ?>" />
					<tr>
						<td>
							<select name='bench_location_code_to_change' id='bench_location_code_to_change'>
							<?php 
								foreach($bench_location_data as $bench) { ?>
									<option value='<?php echo $bench['bench_location_code']; ?>' <?php echo ($current_location_code == $bench['bench_location_code'])?'selected':''; ?> ><?php echo $bench['bench_location_name']; ?></option>
							<?php	} 
							?>
							</select>
						</td>
						<td>
							<select name='case_type_to_change' id='case_type_to_change'>
							<?php 
								foreach($case_type_data as $ct_data) { ?>
									<option value='<?php echo $ct_data['id']; ?>' <?php echo ($current_case_type == $ct_data['id'])?'selected':''; ?> ><?php echo $ct_data['case_type_desc']; ?></option>
							<?php	} 
							?>
							</select>
						</td>
						<td>
							<input type='text' id="case_no_to_change" class="form-control" name='case_no_to_change' value="<?php echo $current_case_no; ?>" onKeyPress="return number_validation(this.id,5)" />
						</td>
						<td>
							<select name='case_year_to_change' id='case_year_to_change'>
									<option value='2021' <?php echo ($current_case_year == '2021')?'selected':''; ?> >2021</option>
									<option value='2020' <?php echo ($current_case_year == '2020')?'selected':''; ?> >2020</option>
									<option value='2019' <?php echo ($current_case_year == '2019')?'selected':''; ?> >2019</option>
									<option value='2018' <?php echo ($current_case_year == '2018')?'selected':''; ?> >2018</option>
									<option value='2017' <?php echo ($current_case_year == '2017')?'selected':''; ?> >2017</option>
									<option value='2016' <?php echo ($current_case_year == '2016')?'selected':''; ?> >2016</option>
									<option value='2015' <?php echo ($current_case_year == '2015')?'selected':''; ?> >2015</option>
									<option value='2014' <?php echo ($current_case_year == '2014')?'selected':''; ?> >2014</option>
									<option value='2013' <?php echo ($current_case_year == '2013')?'selected':''; ?> >2013</option>
									<option value='2012' <?php echo ($current_case_year == '2012')?'selected':''; ?> >2012</option>
									<option value='2011' <?php echo ($current_case_year == '2011')?'selected':''; ?> >2011</option>
							</select>
						</td>
						<td>
							<input type="submit" value="Update" class="btn btn-primary btn-sm" id="update_case_no_button" name="update_case_no">
						</td>
					</tr>
				</tbody>
				</table>	
				</form>
				
		<?php	} else {
					echo "Something went wrong"; die;
			}
			die;
		}
		
		if($type == 'change_case_no'){
			try{  
				$db->beginTransaction();
			$filing_no = $data['filing_no_to_change'];
			$old_case_no = $data['old_case_no'];
			$old_case_type = $data['old_case_type'];
			$old_case_year = $data['old_case_year'];
			$old_location_code = $data['old_location_code'];
			$case_no_to_change = $data['case_no_to_change'];
			$case_year_to_change = $data['case_year_to_change'];
			$case_type_to_change = $data['case_type_to_change'];
			$bench_location_code_to_change = $data['bench_location_code_to_change'];
			
			$first_digit = $case_no_to_change[0];
			if($first_digit == 0){
			$response = array( 
				'status' => 0, 
				'message' => 'Case Number can not be start with zero' 
				);
				echo json_encode($response); die;
			//$case_no_to_change = ltrim($case_no_to_change, $case_no_to_change[0]); 
			}
			
			if(!is_numeric($case_no_to_change) || !is_numeric($case_year_to_change)){
				$response = array( 
				'status' => 0, 
				'message' => 'Case Number and case year must be numeric' 
				);
				echo json_encode($response); die;
			}
				
			$check_if_new_case_no_already_exist = check_case_no_exist_or_not($schemas,$db,$filing_no,$case_type_to_change,$case_year_to_change,$case_no_to_change,$bench_location_code_to_change);
			if($check_if_new_case_no_already_exist > 0){
				$response = array( 
					'status' => 0, 
					'message' => 'Case Number already exist' 
					);
			}else{
				$update_case_no = update_case_no($schemas,$db,$filing_no,$case_type_to_change,$case_year_to_change,$case_no_to_change,$bench_location_code_to_change,$old_case_type,$old_case_year,$old_case_no,$old_location_code,$flag_type="Case No Update",$user_id,$ipaddress='');
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
			$db->commit();
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
  
}
