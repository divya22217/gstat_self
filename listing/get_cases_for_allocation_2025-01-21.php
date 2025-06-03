<?php
  
include("../db_inc1.php");
include '../custom/custom_function.php';
//if we remove db_inc2 then it stops working
$bench_no='';


$schemas=htmlspecialchars($_SESSION['schema_name']);
$user_court = $_SESSION['user_court'];
$location_code = $_SESSION['location'];
date_default_timezone_set("Asia/Kolkata");

if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
	echo "Access Problem.....";
	header("Location: ../login.php");
	die();
}

if($_SESSION['user'] !='' && $_SESSION['location'] !='')
{
	$appeals = main_case_type();
	function generate_case_no($schemas,$db,$case_type,$case_year,$case_no,$location_code){
	$case_num = '';
	$case_short_name = $db->prepare("select short_name from case_type where id=?");
	$case_short_name->bindParam(1, $case_type, PDO::PARAM_INT);
	$case_short_name->execute();
	$case_short_name = $case_short_name->fetchColumn();

	$city_name = $db->prepare("select short_name from mater_location_city where city_id=?");
	$city_name->bindParam(1, $location_code, PDO::PARAM_INT);
	$city_name->execute();
	$city_name = $city_name->fetchColumn();	 

	if(!empty($case_type)){
		   
	$case_num=$case_short_name."/".$case_no."/".$city_name."/".$case_year;
	}
	return $case_num;
	}

	function main_case_filing_no($schemas,$db,$filing_no){
		$main_case_filing_no = $db->prepare("select main_case_ia_no from $schemas.case_detail where filing_no=?");
		$main_case_filing_no->bindParam(1, $filing_no, PDO::PARAM_INT);
		$main_case_filing_no->execute();
		$main_case_filing_no = $main_case_filing_no->fetchColumn();
		return $main_case_filing_no;
	}

	function display_date_format($date){
		list($year,$month,$day)=explode('-',$date);
		$converted_date=$day.'/'.$month.'/'.$year;
		return $converted_date;
	}


	function main_case_no($schemas,$db,$case_type,$filing_no){
		$case_type_array = array(2,3,4,5,6,7,8);
		if (in_array($case_type, $case_type_array)){
			
			$main_case_filing_no = main_case_filing_no($schemas,$db,$filing_no); 
			$main_case_record = $db->prepare("select case_type,case_year,case_no,location_code from $schemas.case_detail where filing_no=?");
			$main_case_record->bindParam(1, $main_case_filing_no, PDO::PARAM_INT);
			$main_case_record->execute();
			$main_case_record = $main_case_record->fetchAll();
			$main_case_record = array_shift($main_case_record);
			
			$main_case_type = $main_case_record['case_type'];
			$main_case_year = $main_case_record['case_year'];
			$main_case_no = $main_case_record['case_no'];
			$location_code = $main_case_record['location_code'];
			
			$main_case_no = generate_case_no($schemas,$db,$main_case_type,$main_case_year,$main_case_no,$location_code);
			
			/* $next_list_date = $db->prepare("select next_list_date from $schemas.case_allocation where filing_no=? order by id desc limit 1");
			$next_list_date->bindParam(1, $main_case_filing_no, PDO::PARAM_INT);
			$next_list_date->execute();
			$next_list_date = $next_list_date->fetchColumn();
			
			if($next_list_date && $next_list_date != ''){
				$main_case_no .= "/$next_list_date";
			} */
			
		}else{
			$main_case_no = '';
		}
		return $main_case_no;
	}

	function main_case_next_list_date($schemas,$db,$case_type,$filing_no){
		$main_case_next_listing_date = '';
		if($case_type == 6){
			$main_case_filing_no = main_case_filing_no($schemas,$db,$filing_no); 
			$main_case_record = $db->prepare("select next_list_date from $schemas.case_allocation where filing_no=? order by id desc limit 1");
			$main_case_record->bindParam(1, $main_case_filing_no, PDO::PARAM_INT);
			$main_case_record->execute();
			$main_case_next_listing_date = $main_case_record->fetchColumn();
			if($main_case_next_listing_date){
				$main_case_next_listing_date = display_date_format($main_case_next_listing_date);
				return " (".$main_case_next_listing_date.")";
			}	
		}
		return $main_case_next_listing_date;
	}
	
	function get_display_court_text($db,$schemas,$court_no){
	$select = $db->prepare("select causelist_court_text from $schemas.court where court_no = ?");
	$select->bindParam(1, $court_no, PDO::PARAM_STR);
	$select->execute();
	$res = $select->fetchColumn();
	return $res;
	}
	
	function main_case_court_no_from_note($db,$schemas,$main_case_number){
	$select = $db->prepare("select first_court_no from $schemas.scrutiny where filing_no = ?");
	$select->bindParam(1, $main_case_number, PDO::PARAM_STR);
	$select->execute();
	$res = $select->fetchColumn();
	return $res;
	}

	function main_case_court_no_from_allocation($db,$schemas,$main_case_number){
		$select = $db->prepare("select court_no from $schemas.case_allocation_temp where filing_no = ? limit 1");
		$select->bindParam(1, $main_case_number, PDO::PARAM_STR);
		$select->execute();
		$res = $select->fetchColumn();
		return $res;
	}

	$data = $_REQUEST;
	$type = $data['type'];
	if (isset($_REQUEST["page"])) { $page  = $_REQUEST["page"]; } else { $page=1; };  
	if (isset($_REQUEST["limit"])) { $limit  = $_REQUEST["limit"]; } else { $limit=20; }; 
	$start_from = ($page-1) * $limit; 
	if($type == 'by_court_and_listing_date' || $type == 'reset_cases'){
		$listing_date = $data['listing_date'];
		$court_no = $data['court_no'];
		/* if($listing_date == '' || $court_no == ''){
			echo "<tr><td colspan='8'> please select Court number and enter listing date</td></tr>";
			die;
		} */
		$count = 0;
		if(isset($listing_date) && !empty($listing_date)){
		list($day,$month,$year)=explode('/',$listing_date);
		$court_date_new=$year.'-'.$month.'-'.$day;
		}else{
			$court_date_new = '';
		}
		$additional_query = '';
		if($court_no != '' && ($court_date_new != '' )){
			$additional_query = " and s.first_listing_date = '$court_date_new' and s.first_court_no = '$court_no'";
		}
		if($court_no != '' && $court_date_new == '' ){
			$additional_query = " and s.first_court_no = '$court_no'";
		}
		if($court_no == '' && $court_date_new != '' ){
			$additional_query = " and s.first_listing_date = '$court_date_new'";
		}

		if($_SESSION['menuaccess_codeall'] == 11)
			$user_court_query = '  ';
		else
			$user_court_query = " and ecd.court = '$user_court'";
		
		$query = "select a.filing_no,a.case_no,a.case_type,a.case_year,a.pet_name,a.res_name,a.dt_of_filing,a.location_code,a.regis_date,a.main_case_ia_no,a.list_with_defect,
					s.first_listing_date,s.first_court_no,ecd.patially_defective from $schemas.case_detail as a inner join $schemas.scrutiny as s on s.filing_no = a.filing_no
					inner join e_case_detail as ecd on ecd.filing_no = a.filing_no
					where (a.case_no is NOT NULL OR a.case_no != '') and (a.case_year is NOT NULL OR a.case_year != '') and (a.case_type is NOT NULL  and a.case_type != 60) and 
					(a.location_code is NOT NULL) and  (a.legal_aid IS NULL OR a.legal_aid = 'NULL') and a.status = 'P' $additional_query $user_court_query order by a.case_no::integer,a.case_type,a.regis_date desc  limit $limit offset $start_from";
		//echo $query;
		$sql1=$db->prepare($query);
		$sql1->execute();
		$res = $sql1->fetchAll();
	}	
	
	if($type == 'all_cases'){
		$count = $start_from;
		$query = "select a.filing_no,a.case_no,a.case_type,a.case_year,a.pet_name,a.res_name,a.dt_of_filing,a.location_code,date(a.regis_date) as regis_date,a.main_case_ia_no, a.list_with_defect,
					s.first_listing_date,s.first_court_no,ecd.patially_defective from $schemas.case_detail as a left join $schemas.scrutiny as s on s.filing_no = a.filing_no
					inner join e_case_detail as ecd on ecd.filing_no = a.filing_no
					where (a.case_no is NOT NULL OR a.case_no != '') and (a.case_year is NOT NULL OR a.case_year != '') and (a.case_type is NOT NULL  and a.case_type != 60) and 
					(a.location_code is NOT NULL) and  (a.legal_aid IS NULL OR a.legal_aid = 'NULL') and a.status = 'P' $user_court_query order by a.case_no::integer,a.case_type,a.regis_date desc limit $limit offset $start_from";
		//echo $query;
		$sql1=$db->prepare($query);
		$sql1->execute();
		$res = $sql1->fetchAll();
		
	}
	
	if($type == 'case_no_search'){
		$search_case_no = $data['search_case_no'];
		$search_case_type = $data['search_case_type'];
		$search_case_year = $data['search_case_year'];
		$count = $start_from;
		$query = "select a.filing_no,a.case_no,a.case_type,a.case_year,a.pet_name,a.res_name,a.dt_of_filing,a.location_code,date(a.regis_date)  as regis_date,a.main_case_ia_no, a.list_with_defect,
					s.first_listing_date,s.first_court_no,ecd.patially_defective from $schemas.case_detail as a left join $schemas.scrutiny as s on s.filing_no = a.filing_no
					inner join e_case_detail as ecd on ecd.filing_no = a.filing_no
					where (a.case_no is NOT NULL OR a.case_no != '') and (a.case_year is NOT NULL OR a.case_year != '') and (a.case_type is NOT NULL  and a.case_type != 60) and 
					(a.location_code is NOT NULL) and  (a.legal_aid IS NULL OR a.legal_aid = 'NULL') and a.status = 'P'  and a.case_type = ? and a.case_year = ? and a.case_no = ? and a.location_code = ? $user_court_query order by a.regis_date desc limit $limit offset $start_from";
		$sql1=$db->prepare($query);
		$sql1->bindParam(1, $search_case_type, PDO::PARAM_INT);
		$sql1->bindParam(2, $search_case_year, PDO::PARAM_STR);
		$sql1->bindParam(3, $search_case_no, PDO::PARAM_STR);
		$sql1->bindParam(4, $location_code, PDO::PARAM_STR);
		$sql1->execute();
		$res = $sql1->fetchAll();
		
	}
	
	$total_records = count($res);
	if($type == 'all_cases' || $type == 'by_court_and_listing_date' || $type == 'reset_cases' || $type == 'case_no_search') {
		if(!empty($res)){
		foreach ($res as $k=>$row1)
		{
		  $filing_no =$row1['filing_no'];
		  $main_case_number = $direct_parent_filing_no = $row1['main_case_ia_no'];
		  $filing_date =$row1['dt_of_filing'];
		  $regis_date =$row1['regis_date'];
		  $case_type =$row1['case_type'];
		  if (in_array($case_type, $appeals)){
			$show_party_filing_no = htmlspecialchars($filing_no);
			}else{
				$show_party_filing_no = htmlspecialchars($direct_parent_filing_no);
				if(empty($show_party_filing_no))
					$show_party_filing_no = htmlspecialchars($filing_no);
			}
		  $pet_name =get_party($db,$show_party_filing_no,'P',1);
		  $pet_name=strtoupper($pet_name);
		  $res_name =get_party($db,$show_party_filing_no,'R',1);
		  $res_name=strtoupper($res_name);
		  $location_code = $row1['location_code'];
		  $case_no = $row1['case_no'];
		  $case_year = $row1['case_year'];
		  $for_listing = $row1['first_listing_date'];
		  $for_court = $row1['first_court_no'];
		  $is_partially_defective = $row1['patially_defective'];
		  if($is_partially_defective == 1)
			  $d_searis = 'D';
		  else
			  $d_searis = '';

			$count++;

		$list_with_defect = $row1['list_with_defect'];
		if(empty($regis_date) && $list_with_defect == '1')
			$lwd = 'LWD';
		else
			$lwd = '';

		
		?>
		<tr>
			<td><?php echo $count;?></td>

			<td><input class="checkbox cases" name="checkbox[]" type="checkbox" id="checkbox_<?php echo $filing_no; ?>" value="<?php echo $filing_no; ?>" ></td>
			<td><?php echo $filing_no; ?></td>
			<?php if($lwd == 'LWD') { ?>
				<td><?php echo $filing_no."  <em style='color:red;'>".$lwd."</em>";?></td>
			<?php } else { ?>
				<td><?php echo generate_case_no($schemas,$db,$case_type,$case_year,$case_no,$location_code); ?></td>
			<?php } ?>								
			<td><?php echo main_case_no($schemas,$db,$case_type,$filing_no);
				//echo main_case_next_list_date($schemas,$db,$case_type,$filing_no);
				?>
				
			</td>								

			<td><?php echo $pet_name.' Vs. '.$res_name;?>
			</td>
			<td>
				<?php
				if($filing_date!='')
				{ echo display_date_format($filing_date); }?>
			</td>
			<td>
				<?php
				if($regis_date!='')
				{ echo display_date_format($regis_date); }?>
			</td>
			<td>
				<?php
				$court_no_of_main_case = '';
				if(!empty($main_case_number)){
					$get_from_proceeding = main_case_court_no_from_allocation($db,$schemas,$main_case_number);
					if(!empty($get_from_proceeding)){
						$court_no_of_main_case = $get_from_proceeding;
					}else{
					$main_case_court_no = main_case_court_no_from_note($db,$schemas,$main_case_number);
					if(!empty($main_case_court_no)){
						$court_no_of_main_case = $main_case_court_no;
					}
					}
					if(!empty($court_no_of_main_case)){
						echo get_display_court_text($db,$schemas,$court_no_of_main_case);
					}
				}
				?>
			</td>
			
			<td>
				<textarea name="case_remark[<?php echo $filing_no ?>]" id="case_remark" class="form-control" rows='1' cols='10'></textarea>
			</td>
			<!--<td id="list_date_<?php echo $filing_no; ?>">
			List Date : <input class='datepicker' name="reg_list_date_<?php echo $filing_no; ?>" type="text" autocomplete="off" id="reg_list_date_<?php echo $filing_no; ?>" value="" >
			<br/>Court : <select class='form-control' name="reg_court_no_<?php echo $filing_no; ?>" id='reg_court_no_<?php echo $filing_no; ?>'>
						<option value="">Select</option>
						<?php
						$display='Y';
						$st= $db->prepare("select * from $schemas.court where court_no = $user_court order by court_no asc");
						$st->execute();
						while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
						{
						$reg_court_no=$row['court_no'];
						?>
						<option value="<?php echo htmlspecialchars($reg_court_no);?>" >
						<?php echo htmlspecialchars($row['display_court_text']);?>
						</option>
						<?php
						}

						?>
						</select>
				<input type="button" class="btn btn-sm btn-success" value="Save" onClick="save_reg_listing_date('<?php echo $filing_no; ?>')">
			</td>-->
		</tr>


		<?php
		
		}}
		else{
		echo "<tr><td colspan='11'><center><font style='color:red'>No Records Found</font></center></td></tr>";
		}
		
		$html = '';
		if(!empty($total_records)):for($i=1; $i<=$total_records	; $i++):  
					if($i == 1):
					$html .="<li class='active'  id=".$i."><a href='get_cases_for_allocation.php?page=".$i."'>". $i."</a></li>";
					 else:
					$html .="<li id=".$i."><a href='get_cases_for_allocation.php?page=".$i."'>".$i."</a></li>";
				 endif;       
		 endfor;endif; ?>
		 
		 <script>
		var html = "<?php echo $html; ?>";
		var item_per_page = '<?php echo $limit; ?>';
		var total_items = $("#all_records").val();
		var current_page_no = $(".active .current").html();
		var showing_to = current_page_no*item_per_page;
		var showing_from = ((current_page_no-1)*item_per_page)+1;
		if(total_items <= showing_to){
			showing_to = total_items;
		}
		if(total_items == 0){
			showing_from = 0;
		}
		$("#total_rec").html("Showing "+showing_from+" to "+showing_to+" of "+total_items+" entries");
		//$(".pagination").html(html);
		
		
	
		</script>
		
		<?php
		
		}
		
	if($type == 'save_reg_listing_date'){
		$filing_no = $data['filing_no'];
		$reg_listing_date = $data['reg_listing_date'];
		list($d,$m,$y) = explode('/',$reg_listing_date);
		$reg_listing_date = $y.'-'.$m.'-'.$d;
		$reg_court_no = $data['reg_court_no'];
		try{
		$db->beginTransaction();  // begin transaction
		$st="insert into $schemas.scrutiny_his (filing_no,first_listing_date,first_court_no) select filing_no,first_listing_date,first_court_no from $schemas.scrutiny where filing_no=?";
		$st=$db->prepare($st);
		$st->bindParam(1, $filing_no, PDO::PARAM_STR);
		$st->execute();

		$sql="update $schemas.scrutiny set first_listing_date=? , first_court_no =? , updated_at = now() where filing_no = ?";
		$update=$db->prepare($sql);
		$update->bindParam(1, $reg_listing_date, PDO::PARAM_STR);
		$update->bindParam(2, $reg_court_no, PDO::PARAM_STR);
		$update->bindParam(3, $filing_no, PDO::PARAM_STR);
		$update = $update->execute();

		
		$db->commit();
		echo $res_p;
	}catch(Exception $e){
				$db->rollBack();
				$response = array( 
				'status' => 0, 
				'message' => 'some error occurred.' 
				);
			}
	}
	}
	


?>
