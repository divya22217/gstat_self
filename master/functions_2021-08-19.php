<?php


/* param : 1 . $schemas = schema_name
			2 . $db = db connection
			3 . $table = table name
			4 . $columns = like ("column_a,column_b"), if not passed than default *.
			5 . $condition  = "filing_no = ? AND listing_date = ?";
			6 . $condition_values = array('078545854585','03-04-2019');
*/
function get_data($schemas,$db,$table,$columns = '*',$condition = "",$codition_values = array())
{
	$query = "select $columns from $schemas.$table";
	if($condition != ""){
		$query = "select $columns from $schemas.$table where $condition";
	}
	
	$prm = $db->prepare($query);
	if($condition != ""){
		foreach($codition_values as $key=>$val){
			$pos = $key+1;
			$prm->bindParam(1, $val, PDO::PARAM_STR);
		}
	}
	$prm->execute();
	$data = $prm->fetchAll();
	return $data;
}


function generate_case_no($schemas,$db,$case_type,$case_year,$case_no,$location_code){
	$case_short_name = $db->prepare("select short_name from case_type where id=?");
	$case_short_name->bindParam(1, $case_type, PDO::PARAM_INT);
	$case_short_name->execute();
	$case_short_name = $case_short_name->fetchColumn();

	$city_name = $db->prepare("select short_name from $schemas.bench_location where city_id=?");
	$city_name->bindParam(1, $location_code, PDO::PARAM_INT);
	$city_name->execute();
	$city_name = $city_name->fetchColumn();	   
		   
	//$case_no=$case_short_name.' No. '.$case_no."($city_name)"."/".$case_year;
	$case_no=$case_short_name.' No. '.$case_no."/".$city_name."/".$case_year;
	return $case_no;
}

function get_main_case_filing_no($schemas,$db,$filing_no){
	
	//$main_case_filing_no = $db->prepare("select filing_no,case_type from $schemas.case_detail where filing_no = (select ia_ma_filing_no from $schemas.case_detail where filing_no=?)");
	$main_case_filing_no = $db->prepare("select main_case_ia_no from $schemas.case_detail where filing_no=?");
	$main_case_filing_no->bindParam(1, $filing_no, PDO::PARAM_INT);
	$main_case_filing_no->execute();
	//$main_case_filing_no = $main_case_filing_no->fetchAll();
	$main_case_filing_no = $main_case_filing_no->fetchColumn();
	/* $main_case_filing_no = array_shift($main_case_filing_no);
	if($main_case_filing_no['case_type'] == 1 || $main_case_filing_no['case_type'] == 4){
	return $main_case_filing_no['filing_no'];
	}else{
	get_main_case_filing_no($schemas,$db,$main_case_filing_no['filing_no']);
	} */
	return $main_case_filing_no;
}

function main_case_no($schemas,$db,$case_type,$filing_no){
	$main_case_no = '';
 	$main_case_filing_no = $db->prepare("select main_case_ia_no from $schemas.case_detail where filing_no=?");
	$main_case_filing_no->bindParam(1, $filing_no, PDO::PARAM_INT);
	$main_case_filing_no->execute();
	$main_case_filing_no = $main_case_filing_no->fetchColumn(); 
	if(!empty($main_case_filing_no)){
	$main_case_record = $db->prepare("select case_type,case_year,case_no,location_code from $schemas.case_detail where filing_no= ?");
	$main_case_record->bindParam(1, $main_case_filing_no, PDO::PARAM_INT);
	$main_case_record->execute();
	$main_case_record = $main_case_record->fetchAll();
	$main_case_record = array_shift($main_case_record);
	if(empty($main_case_record)){
		return $main_case_no;
	}
	$main_case_type = $main_case_record['case_type'];
	$main_case_year = $main_case_record['case_year'];
	$main_case_no = $main_case_record['case_no'];
	$location_code = $main_case_record['location_code'];
	
	$main_case_no = generate_case_no($schemas,$db,$main_case_type,$main_case_year,$main_case_no,$location_code);
	}
	
	return $main_case_no;
	
}

function connected_cases($schemas,$db,$filing_no)
{
	$text = '';
	$stQ = $db->prepare("select * from $schemas.connected_cases where filing_no = ? ");
	$stQ->bindParam(1, $filing_no, PDO::PARAM_STR);
	$stQ->execute();
	$connected_cases=$stQ->fetchAll();

	if(!empty($connected_cases)){
		$text .= "<br/> With";
		foreach($connected_cases as $key=>$conn_filing_no){
			$con_fil_no = $conn_filing_no['conn_filing_no'];
			$case_details="select case_type,case_no,case_year,location_code,bench_location from $schemas.case_detail where filing_no= ?";
			$case_details=$db->prepare($case_details);
			$case_details->bindParam(1, $con_fil_no, PDO::PARAM_STR);
			$case_details->execute();
			$case_details = $case_details->fetchAll();
			$case_details = array_shift($case_details);
			$connected_case_no=$case_details['case_no'];
			$connected_case_type=$case_details['case_type'];
			$connected_case_year=$case_details['case_year'];
			
			if($connected_case_type > 0)
			{
			$stQ = $db->prepare("select case_type_desc from case_type where id = ?");
			$stQ->bindParam(1, $connected_case_type, PDO::PARAM_STR);
			$stQ->execute();
			$connected_case_type_short_name=$stQ->fetchColumn();
			}
			
			$connected_case_no=ltrim($connected_case_no,0);
			
			 $CONNECTED_CASE_NO = htmlspecialchars(strtoupper($connected_case_type_short_name).' No. '.$connected_case_no.'/'.$connected_case_year);
			 $text .= "<br/>".$CONNECTED_CASE_NO;
			 
		}
	}
	return $text;
}

function ia_cases_of_main_cases($schemas,$db,$dbonline,$case_type,$filing_no,$type_of_filing)
{
	$cases = ia_ma_filing_numbers($schemas,$dbonline,$filing_no,$type_of_filing,$scrutiny_level = 5);
	$c = 0;
	if(!empty($cases)){
	foreach($cases as $key=>$case){
		$stQ = $db->prepare("select case_type,case_year,case_no,location_code from $schemas.case_detail where filing_no = ?");
		$stQ->bindParam(1, $case['filing_no_ia_ma'], PDO::PARAM_STR);
		$stQ->execute();
		$rec = $stQ->fetchAll();
		$rec = array_shift($rec);
		/* if($rec['status'] == 'D'){
			continue;
		} */
		if(!empty($rec)){
			if($c > 0){
				$comma_seperator = " , ";
			}else{
				$comma_seperator = "";
			}
			$c++;
			$ia_cases .= $comma_seperator.generate_case_no($schemas,$db,$rec['case_type'],$rec['case_year'],$rec['case_no'],$rec['location_code']);
			}
		}
		if(strlen($ia_cases) > 0){
			$ia_cases = '<br/>('.$ia_cases.')';
		}
		return $ia_cases;
	}else{
		$ia_cases = '';
		return $ia_cases;	
	}
}

function main_case_filing_no($schemas,$db,$filing_no){
	$main_case_filing_no = $db->prepare("select ia_ma_filing_no from $schemas.case_detail where filing_no=?");
	$main_case_filing_no->bindParam(1, $filing_no, PDO::PARAM_INT);
	$main_case_filing_no->execute();
	$main_case_filing_no = $main_case_filing_no->fetchColumn();
	return $main_case_filing_no;
}

function ia_ma_filing_numbers($schemas,$dbonline,$main_case_filing_no,$type_of_filing,$scrutiny_level)
{
	$query = "select filing_no_ia_ma from case_detail_ma_ia where filing_no = ? and type_of_filing = ? and filing_no_ia_ma is NOT NULL and filing_no_ia_ma != ''";
	if($scrutiny_level <= 2){
		$query = "select filing_no_ia_ma from case_detail_ma_ia where filing_no = ? and type_of_filing = ? and filing_no_ia_ma is NOT NULL and filing_no_ia_ma != '' and ia_scrutiny_level != ?";
	}
	$stQ = $dbonline->prepare($query);
	$stQ->bindParam(1, $main_case_filing_no, PDO::PARAM_STR);
	$stQ->bindParam(2, $type_of_filing, PDO::PARAM_STR);
	if($scrutiny_level <=  2){
		$stQ->bindParam(3, $scrutiny_level, PDO::PARAM_STR);
	}
	$stQ->execute();
	$cases=$stQ->fetchAll();
	return $cases;
}

function ia_ma_cases_of_main_cases($schemas,$db,$dbonline,$case_type,$filing_no,$main_case_filing_no,$type_of_filing)
{
	$cases = ia_ma_filing_numbers($schemas,$dbonline,$main_case_filing_no,$type_of_filing,$scrutiny_level = 5);
	$c = 0;
	$array = array();
	if(!empty($cases)){
	foreach($cases as $key=>$case){
		$stQ = $db->prepare("select case_type,case_year,case_no,location_code,status from $schemas.case_detail where filing_no = ?");
		$stQ->bindParam(1, $case['filing_no_ia_ma'], PDO::PARAM_STR);
		$stQ->execute();
		$rec = $stQ->fetchAll();
		$rec = array_shift($rec);
		if($rec['status'] == 'D'){
			continue;
		}
		$ia_cases = generate_case_no($schemas,$db,$rec['case_type'],$rec['case_year'],$rec['case_no'],$rec['location_code']);
		if($filing_no == $case['filing_no_ia_ma']){
			
		}else{
			$array['name'][] = $ia_cases;
			$array['filing_nos'][] = $case['filing_no_ia_ma'];
		}
		}
		return $array;
	}else{
		$ia_cases = '';
		return $ia_cases;	
	}
}

function all_ia_ma($schemas,$db,$dbonline,$case_type,$filing_no,$type_of_filing,$case_proceeding){
	$main_case_filing_no = main_case_filing_no($schemas,$db,$filing_no);
	$all_ia_ma = ia_ma_cases_of_main_cases($schemas,$db,$dbonline,$case_type,$filing_no,$main_case_filing_no,$type_of_filing);

	if(is_array($all_ia_ma) && !empty($all_ia_ma)){
		$cases = ",";
		$len = count($all_ia_ma);
		foreach($all_ia_ma['name'] as $key=>$val){
			if(($key+1) == $len){
				$comma_seperator = '';
			}else{
				$comma_seperator = ',';
			}
			if($case_proceeding == 1){
				/* $table = 'case_allocation_temp';
				$columns = "filing_no,listing_date,purpose";
				$condition = "filing_no = ?";
				$condition_val = array($all_ia_ma['filing_nos'][$key]);
				$case_allocation_data = get_data($schemas,$db,$table,$columns,$condition,$condition_val);
				$cases = '<br/><a href="javascript:popsurety_pending_report('.htmlspecialchars($all_ia_ma['filing_nos'][$key]).');" style="text-decoration: none;" >'.$val.'</font></a>'.$comma_seperator; */
				$cases .= "<br/>".$val.$comma_seperator;
			}else{
				$cases .= "<br/>".$val.$comma_seperator;
			}
			}
		return $cases;
	}else{
	 return;
	}
}


function checkIA($schemas,$db,$dbonline,$filing_no,$type_of_filing){
	$msg = '';
	$scrutiny_not_completed = array();
	$case_not_disposed = array();
	$final_array = array();
	$ia_ma_filing_numbers = ia_ma_filing_numbers($schemas,$dbonline,$filing_no,$type_of_filing,$scrutiny_level = 2);
	if(!empty($ia_ma_filing_numbers)) {
		foreach($ia_ma_filing_numbers as $key=>$val){
			$scrutiny_not_completed[] = $val['filing_no_ia_ma'];
		}
		$scrutiny_not_completed_msg = implode(",",$scrutiny_not_completed);
		$final_array['scrutiny'] = $scrutiny_not_completed;
		$msg .= 'Scrutiny Not Completed of these filing numbers '.$scrutiny_not_completed_msg;
	}
	$ia_in_case_detail = ia_ma_in_case_detail($schemas,$db,$filing_no,$type_of_filing = '6',$status = 'P');
	if(!empty($ia_in_case_detail)) {
		foreach($ia_in_case_detail as $key=>$val){
			$case_not_disposed[] = $val['filing_no'];
		}	
		$case_not_disposed_msg = implode(",",$case_not_disposed);
		$final_array['pending'] = $case_not_disposed;
		$msg .= '<br/>Please dispose IA first '.$case_not_disposed_msg;
		$final_array['msg'] = $msg;
	}
	return $final_array;
}

function ia_ma_in_case_detail($schemas,$db,$filing_no,$type_of_filing,$status){
	$query = "select filing_no from $schemas.case_detail where ia_ma_filing_no = ? and status = ? and case_type = ?";
	$stQ = $db->prepare($query);
	$stQ->bindParam(1, $filing_no, PDO::PARAM_STR);
	$stQ->bindParam(2, $status, PDO::PARAM_STR);
	$stQ->bindParam(3, $type_of_filing, PDO::PARAM_STR);
	$stQ->execute();
	$cases=$stQ->fetchAll();
	return $cases;
}

function main_cases_child($schemas,$db,$filing_no,$status=""){
	$case_nos = all_child_case_no_generation($schemas,$db,$filing_no,$status);
	$case_no = '';
	if(!empty($case_nos)){
		$case_no = '<br/>( '.implode(',',$case_nos).' )';
	}
	return $case_no;
}
 
function all_child_case_no_generation($schemas,$db,$filing_no,$status){
	$cases = generate_case_no_new($schemas,$db,$filing_no,$status);
	$case_no = array();
	if(!empty($cases)){
		foreach($cases as $key=>$case){
			$case_no []=$case['case_type_short_name'].' NO '.$case['case_no']."/".$case['case_year'];
		}
	}
	return $case_no;
}


function generate_case_no_new($schema,$db,$filing_no,$status){
	if($status == ""){
		$query_text = '';
	}else{
	$query_text = 'and cd.status = ?';
	$status = strtoupper($status);
	}
	$query = "SELECT
			 cd.case_no,
			 cd.case_year,
			 cd.filing_no,
			 ct.short_name as case_type_short_name,
			 mlc.short_name
			FROM
			 $schema.case_detail as cd
			LEFT JOIN case_type as ct ON ct.id = cd.case_type
			LEFT JOIN $schema.bench_location as mlc ON mlc.city_id = cd.location_code
			where cd.filing_no in (select filing_no from $schema.case_detail where main_case_ia_no = ? order by case_type asc) $query_text";
	$stQ = $db->prepare($query);
	$stQ->bindParam(1, $filing_no, PDO::PARAM_STR);
	if($query_text != ""){
	$stQ->bindParam(2, $status, PDO::PARAM_STR);	
	}
	$stQ->execute();
	$cases=$stQ->fetchAll();
	return $cases;
}

/* function get_remarks($schemas,$db,$listing_date,$bench_nature,$court_no,$list_flag,$bench_no){
	$fetch_remarks = $db->prepare("select cr.*, mp.purpose_name from $schemas.causelist_remark as cr left join $schemas.master_purpose as mp on mp.purpose_code = cr.purpose where cr.listing_date = ? AND cr.bench_nature = ? AND cr.court_no = ? AND cr.list_flag = ? AND cr.bench_no= ?");
	$fetch_remarks->bindParam(1, $listing_date, PDO::PARAM_INT);
	$fetch_remarks->bindParam(2, $bench_nature, PDO::PARAM_INT);
	$fetch_remarks->bindParam(3, $court_no, PDO::PARAM_INT);
	$fetch_remarks->bindParam(4, $list_flag, PDO::PARAM_INT);
	$fetch_remarks->bindParam(5, $bench_no, PDO::PARAM_INT);
	$fetch_remarks->execute();
	$fetch_remarks = $fetch_remarks->fetchAll();
	return $fetch_remarks;
} */

function remark_text($fetch_remarks,$sr_no){
	$text = '';
	if(!empty($fetch_remarks)){
		// for php 5.5+
	/* if(array_search($sr_no, array_column($fetch_remarks, 'start_serial')) !== False) {
	$index = array_search($sr_no, array_column($fetch_remarks, 'start_serial')); */
	$res = array_map(function($element) {
	  return $element['start_serial'];
	}, $fetch_remarks);
	if(array_search($sr_no,$res) !== FALSE){
		$index = array_search($sr_no,$res);
	if($fetch_remarks[$index]['end_serial'] == 0){
		$symbol = '';
		$end = '';
		$case = 'CASE';
	}else{
	$diffrence = ($fetch_remarks[$index]['end_serial'] - $fetch_remarks[$index]['start_serial']);
	$symbol = ($diffrence > 1)?' TO ':' & ';
	$end = $fetch_remarks[$index]['end_serial'];
	$case = 'CASES';
	}
    $text = "<tr><td align='center' colspan='7'><font face='Verdana' size ='3'><b>
			$case AT SR. NO. ".$fetch_remarks[$index]['start_serial']." $symbol ".$end." WILL BE TAKEN-UP ON ". date('d.m.Y',strtotime($fetch_remarks[$index]['taken_up_date']))." ". strtoupper($fetch_remarks[$index]['purpose_name'])."
	</font>
	</td></tr>";
	}
	}
	return $text;
}

function get_connected_cases($schemas,$db,$filing_no){
	$stQ = $db->prepare("select a.*,cast(b.case_no as integer) as case_no ,b.case_year from $schemas.connected_cases as a
						left join $schemas.case_detail as b on b.filing_no = a.conn_filing_no
						where a.filing_no = ? and a.status = 'C' order by b.case_year,b.case_no asc");
	$stQ->bindParam(1, $filing_no, PDO::PARAM_STR);
	$stQ->execute();
	$connected_cases=$stQ->fetchAll();
	return $connected_cases;
}

function counsel_parties($schemas,$db,$dbonline,$case_type_array,$conn_case_type,$conn_filing_no,$party_flag,$listdate_entire,$bench_code1,$list_flag,$court_no){
	
	$text = '';
	$adv_list = get_advocate_list($db,$schemas,$conn_filing_no,$listdate_entire,$bench_code1,$court_no,$list_flag,$party_flag);
	if(!empty($adv_list)){
	foreach($adv_list as $k=>$val_adv){ 
		$text .= "<font face='Verdana' size ='2'>" ;
		if($val_adv['adv_type'] == 'R'){
	  $text .= strtoupper($val_adv['adv_name'])."  ".$val_adv['adv_type'].$val_adv['party_serial']." ".$val_adv['remarks'].'<br>';
		}else{
			$text .= strtoupper($val_adv['adv_name'])." ".$val_adv['remarks'].'<br>';
		}
	}
}else{
	 if(in_array($conn_case_type, $case_type_array)){
	$main_case_filing_no = get_main_case_filing_no($schemas,$db,$conn_filing_no);
}else{
	$main_case_filing_no = $conn_filing_no;
}
$st12=$dbonline->prepare("select a.rep_code,b.party_serial_no,b.party_flag,a.party_flag as pflag,a.remarks from e_more_representative as a left join e_cases_party as b on b.id = a.party_code
						where a.filing_no=? and a.party_flag=? and a.show_in_causelist='t'");
	$st12->bindParam(1, $conn_filing_no, PDO::PARAM_STR);
	$st12->bindParam(2, $party_flag, PDO::PARAM_STR);
	
	$st12->execute();
	while ($row12 = $st12->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		
	     $adv_id = htmlspecialchars($row12['rep_code']);
	    if(!empty(htmlspecialchars($row12['party_serial_no']))){
			if($row12['pflag'] == 'R'){
	     $party_sr_no = htmlspecialchars($row12['pflag']).htmlspecialchars($row12['party_serial_no']);
			}else{
				$party_sr_no = '';
			}
		 }else{
			 $party_sr_no = '';
		 }
	
	$stqq12 = $dbonline->prepare("select rep_name from e_master_advocate where id=?");
	$stqq12->bindParam(1, $adv_id, PDO::PARAM_INT);
	$stqq12->execute();
	  $pet_advname22 = $stqq12->fetchColumn();
	$text .= "<font face='Verdana' size ='2'>" ;
	  $text .= strtoupper($pet_advname22)."  ".$party_sr_no." ".$row12['remarks'].'<br>';
	}
	}	
	$text .= '</font>';
return $text;	
}

function counsel_parties_draft($schemas,$db,$dbonline,$case_type_array,$conn_case_type,$conn_filing_no,$party_flag){
	
	$text = '';
	 if(in_array($conn_case_type, $case_type_array)){
	$main_case_filing_no = get_main_case_filing_no($schemas,$db,$conn_filing_no);
}else{
	$main_case_filing_no = $conn_filing_no;
}
$st12=$dbonline->prepare("select a.rep_code,b.party_serial_no,b.party_flag,a.party_flag as pflag,a.remarks from e_more_representative as a left join e_cases_party as b on b.id = a.party_code
						where a.filing_no=? and a.party_flag=? and a.show_in_causelist='t'");
	$st12->bindParam(1, $conn_filing_no, PDO::PARAM_STR);
	$st12->bindParam(2, $party_flag, PDO::PARAM_STR);
	
	$st12->execute();
	while ($row12 = $st12->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		
	     $adv_id = htmlspecialchars($row12['rep_code']);
		if(!empty(htmlspecialchars($row12['party_serial_no']))){
			if($row12['pflag'] == 'R'){
	     $party_sr_no = htmlspecialchars($row12['pflag']).htmlspecialchars($row12['party_serial_no']);
			}else{
				$party_sr_no = '';
			}
		 }else{
			 $party_sr_no = '';
		 }
	
	$stqq12 = $dbonline->prepare("select rep_name from e_master_advocate where id=?");
	$stqq12->bindParam(1, $adv_id, PDO::PARAM_INT);
	$stqq12->execute();
	  $pet_advname22 = $stqq12->fetchColumn();
	$text .= "<font face='Verdana' size ='2'>" ;
	  $text .= strtoupper($pet_advname22)."  ".$party_sr_no." ".$row12['remarks'].'<br>';
	}
	
	$text .= '</font>';
return $text;	
}

function get_case_details($schemas,$db,$con_fil_no){
	$case_details="select cd.filing_no,cd.case_type,cd.case_no,cd.case_year,cd.location_code,cd.pet_name,cd.res_name,ct.short_name from $schemas.case_detail as cd left join case_type as ct on ct.id = cd.case_type where cd.filing_no= ?";
	$case_details=$db->prepare($case_details);
	$case_details->bindParam(1, $con_fil_no, PDO::PARAM_STR);
	$case_details->execute();
	$case_details = $case_details->fetchAll();
	if(!empty($case_details)){
		$case_details = array_shift($case_details);
	}
	return $case_details;
}

function connected_case_no($schemas,$db,$conected_case_record){
	$CONNECTED_CASE_NO = '';
	if(!empty($conected_case_record)){
	$connected_case_no=$conected_case_record['case_no'];
	$connected_case_type=$conected_case_record['case_type'];
	$connected_case_year=$conected_case_record['case_year'];
	$connected_location=$conected_case_record['location_code'];
	
	if($connected_case_type > 0)
	{
	$stQ = $db->prepare("select short_name from case_type where id = ?");
	$stQ->bindParam(1, $connected_case_type, PDO::PARAM_STR);
	$stQ->execute();
	$connected_case_type_short_name=$stQ->fetchColumn();
	}
	
	$stQ = $db->prepare("select short_name from $schemas.bench_location where city_id = ?");
	$stQ->bindParam(1, $connected_location, PDO::PARAM_STR);
	$stQ->execute();
	$connected_loc_short_name=$stQ->fetchColumn();
	
	$connected_case_no=ltrim($connected_case_no,0);
	
	 $CONNECTED_CASE_NO = htmlspecialchars(strtoupper($connected_case_type_short_name).' No. '.$connected_case_no.'/'.$connected_loc_short_name.'/'.$connected_case_year);
	}
	 return $CONNECTED_CASE_NO;
}


function get_case_type_name($db,$case_type_id){
	$get_caseType=$db->prepare("select short_name from case_type where id= ?");
	$get_caseType->bindParam(1, $case_type_id, PDO::PARAM_STR);
	$get_caseType->execute();
	$get_caseType = $get_caseType->fetchColumn();
	return $get_caseType;
}

function get_location_name($db,$location_id){
	$get_loc_name=$db->prepare("select short_name from mater_location_city where city_id= ?");
	$get_loc_name->bindParam(1, $location_id, PDO::PARAM_STR);
	$get_loc_name->execute();
	$get_loc_name = $get_loc_name->fetchColumn();
	return $get_loc_name;
}

function generate_dms_link($db,$dbonline,$schemas,$filing_no,$main_location_code){
	$dms_master = dms_master_data();
	$case_detail = get_case_details($schemas,$db,$filing_no);
	$case_year = $case_detail['case_year'];
	$case_no = $case_detail['case_no'];
	$case_type_id = $case_detail['case_type'];
	$case_type_short_name = get_case_type_name($db,$case_type_id);
	$loc_short_name = get_location_name($db,$main_location_code);
	$zonal_array = array("CZ","SZ","EZ","WZ");
	if($loc_short_name == 'PB'){
		$bench = "DL";
		$zone = $loc_short_name;
	}else{
		if(in_array($loc_short_name,$zonal_array)){
			$bench = $loc_short_name;
			$zone = 'ZB';
		}else{
			$zone = 'CB';
			$bench = 'SML';  // this is default becuase CIS doesnt't contain these type of location (or zones or bench)
		}
	}
$year_array = $dms_master['years'];
$case_type_arr = $dms_master['case_type'];
	  $appyear = array_search($case_year,$year_array);
	  $only_case_type =  array_map(function($element) {
									  return $element['short_name'];
									}, $case_type_arr);
    
	//$apptype = array_search($case_type_short_name,$only_case_type); 

	foreach($case_type_arr as $key=>$value){
		if($value['short_name'] == $case_type_short_name){
			$apptype = $value['id'];
		}
	}		

$link = "http://10.246.58.171/CauseListServlet?apptype=$apptype&appyear=$appyear&appno=$case_no&zone=$zone&bench=$bench";
return $link;


	
	
}

 function dms_master_data(){
	$case_type_array = array(array("id"=>1,"case_type"=>"APPEAL","short_name"=>"APPEAL"),
							array("id"=>2,"case_type"=>"ORIGINAL APPLICATION","short_name"=>"OA"),
							array("id"=>3,"case_type"=>"MISCELLANEOUS APPLICATION","short_name"=>"MA"),
							array("id"=>4,"case_type"=>"REVIEW APPLICATION","short_name"=>"RA"),
							array("id"=>5,"case_type"=>"EXECUTION APPLICATION","short_name"=>"EA"),
							array("id"=>6,"case_type"=>"INTERLOCUTORY APLLICATION","short_name"=>"IA"),
							array("id"=>7,"case_type"=>"CONTEMPT PETITION","short_name"=>"CP")
							);
							
	$year_array =array('1'=>'2010','2'=>'2011','3'=>'2012','4'=>'2013','5'=>'2014',
						'6'=>'2015','7'=>'2016','8'=>'2017','9'=>'2018','10'=>'2019',
						'11'=>'2020','12'=>'2021','13'=>'2022','14'=>'2023','15'=>'2024'
						);
						
	$zone_array = array(array('ID'=>"PB",'name'=>"PRINCIPAL"),
						array('ID'=>"ZB",'name'=>"ZONAL"),
						array('ID'=>"CB",'name'=>"CIRCUIT")
						);
						
	$bench_array['PB'] = array( array('ID'=>"DL",'name'=>"DELHI"));
	
	$bench_array['ZB'] = array( array('ID'=>"CZ",'name'=>"CENTRAL"),
								array('ID'=>"EZ",'name'=>"EASTERN"),
								array('ID'=>"WZ",'name'=>"WESTERN"),
								array('ID'=>"SZ",'name'=>"SOUTHERN")
								);
	$bench_array['CB'] = array( array('ID'=>"SML",'name'=>"SHIMLA BENCH"),
								array('ID'=>"SHL",'name'=>"SHILLONG BENCH"),
								array('ID'=>"JDH",'name'=>"JODHPUR BENCH"),
								array('ID'=>"KCH",'name'=>"KOCHI BENCH")
								);

	$final = array("case_type"=>$case_type_array,"years"=>$year_array,"zone"=>$zone_array,"benchs"=>$bench_array); 

	return $final;

}

function return_zone_name_for_causelist($location_id){
	$zone = '';
	if($location_id == 10){
		$zone = "PRINCIPAL BENCH";
	}
	if($location_id == 5){
		$zone = "CHENNAI BENCH";
	}
	return $zone;
}

function return_zone_addred_for_causelist($location_id){
	if($location_id == 1){
		$address = "FARIDKOT HOUSE, NEW DELHI";
	}
	if($location_id == 2){
		$address = "";
	}
	if($location_id == 3){
		$address = "SUCHANA BHAWAN, IIIrdFLOOR, ARERA HILLS";
	}
	if($location_id == 4){
		$address = "";
	}
	if($location_id == 5){
		$address = "";
	}
	return $address;
}


function generate_cause_list_html($db,$dbonline,$data,$court_no,$listing_date,$schemas){
	//echo "<pre>"; print_r($data); die;
	$html = '';
	//$data = array_shift($data);
	foreach($data as $key=>$value){
		if($key > 0){
			return $html;
			break;
		}
		$listbefore = $value['bench_nature'];
		$list_flag = $value['list_flag'];
		$location_code = $value['location_code'];
		$b_nature = $value['bench_nature'];
		$filing_no = $value['filing_no'];
		if($list_flag ==1)
		$causelisthead="DAILY CAUSE LIST";
		if($list_flag==2)
			$causelisthead ="SUPPLEMENTRY CAUSE LIST";
		if($list_flag==3)
			$causelisthead ="VACATION CAUSE LIST";
		
		
		if($listbefore > 0 and $court_no > 0 and ($listing_date !='--' OR $listing_date !=''))
		{
			

		$benchloop1 =$db->prepare( "select * from $schemas.bench b ,$schemas.bench_nature bn
		where b.from_list_date= ? and  b.bench_nature=? and bn.bench_code =? and
		b.bench_nature=bn.bench_code and b.court_no =? order by b.court_no, b.priority asc");
		$benchloop1->bindParam(1, $listing_date, PDO::PARAM_STR);
		$benchloop1->bindParam(2, $listbefore, PDO::PARAM_STR);
		$benchloop1->bindParam(3, $listbefore, PDO::PARAM_STR);
		$benchloop1->bindParam(4, $court_no, PDO::PARAM_STR);
		}


		if($listbefore > 0 and $court_no =='' and ($listdate_entire !='--' OR $listdate_entire !=''))
		{
		$benchloop1 =$db->prepare( "select * from $schemas.bench b ,$schemas.bench_nature bn where
		b.from_list_date=?	and b.bench_nature=? and bn.bench_code =? order by b.court_no, b.priority asc");

		$benchloop1->bindParam(1, $listing_date, PDO::PARAM_STR);
		$benchloop1->bindParam(2, $listbefore, PDO::PARAM_STR);
		$benchloop1->bindParam(3, $listbefore, PDO::PARAM_STR);

		}


		if($listbefore == 0 and $court_no > 0  and ($listing_date !='--' OR $listing_date !=''))
		{
		$benchloop1 =$db->prepare( "select * from $schemas.bench b ,$schemas.bench_nature bn
		where b.from_list_date=? and b.bench_nature=bn.bench_code and b.court_no =?
		order by b.court_no, b.priority asc");

		$benchloop1->bindParam(1, $listing_date, PDO::PARAM_STR);
		$benchloop1->bindParam(2, $court_no, PDO::PARAM_STR);
		}




		if($listbefore ==0 and $court_no =='' and ($listing_date !='--' OR $listing_date !=''))
		{
		$benchloop1 =$db->prepare(  "select * from $schemas.bench b ,$schemas.bench_nature bn where 
		b.from_list_date=? and b.bench_nature=bn.bench_code order by b.court_no,
		b.priority asc");

		$benchloop1->bindParam(1, $listing_date, PDO::PARAM_STR);
		}
		$benchloop1->execute();

		while ($row_loop1 = $benchloop1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		$bench_code12=$row_loop1['id'];
		$list_before = $row_loop1['bench_nature'];
		//$bench_no1 = $row_loop1['bench_no'];
		//$court_no= $row_loop1['court_no'];
		$from_time = $row_loop1['from_time'];
		$detail    =$row_loop1['detail'];
		$presiding_judge = $row_loop1['presiding'];
		$bench_code1 = $row_loop1['bench_no'];
		$b_nature = $row_loop1['bench_nature'];
		$curYear = date('Y');
		$curMonth = date('m');
		$curDay = date('d');
		$cur_date = "$curDay/$curMonth/$curYear";
		$curdate="$curYear-$curMonth-$curDay";
		$todatexx="$curYear-$curMonth-$curDay";

		$frmdate=$year.'-'.$month.'-'.$day;
		$listdate=date('l \t\h\e jS F Y', mktime(0, 0, 0, $month, $day, $year));
		}
		
		
		$html .= "<div class='container-fluid'>";
		$html .= "<table width='100%' border='0' cellpadding='1' cellspacing='3' align='center'>";
		$sql="select bench_no  from $schemas.bench where bench_nature='$listbefore' and court_no='$court_no' and from_list_date='$listing_date'";
		foreach($db->query($sql) as $row)
		{	
			$bench_code1 =$row['bench_no'];
			$fetch_remarks = get_remarks($schemas,$db,$listdate_entire,$listbefore,$court_no,$list_flag,$bench_code1);
			$sql_judge="select jm.judge_name,jm.judge_desg_code,jm.judge_code from $schemas.master_judge as jm,
			$schemas.bench as b where  b.from_list_date=? and b.bench_no=? and
			b.court_no=? and jm.judge_code=b.presiding";
			$sth101=$db->prepare($sql_judge);
			$sth101->bindParam(1, $listing_date, PDO::PARAM_STR);
			$sth101->bindParam(2, $bench_code1, PDO::PARAM_STR);
			$sth101->bindParam(3, $court_no, PDO::PARAM_STR);
			$sth101->execute();

			while ($j = $sth101->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
			{
				$judge_name=$j['judge_name'];
				$judge_desg_code=$j['judge_desg_code'];
				$presiding_code=$j['judge_code'];
				$jj = "select display from $schemas.bench_judge where display='true' and judge_code =?  and from_list_date=? and bench_no=? ";
				$jj=$db->prepare($jj);
				$jj->bindParam(1, $presiding_code, PDO::PARAM_STR);
				$jj->bindParam(2, $listing_date, PDO::PARAM_STR);
				$jj->bindParam(3, $bench_code1, PDO::PARAM_STR);
				$jj->execute();
				$jjj = $jj->fetchColumn();

				if($judge_desg_code > 0)
				{
					$sql_desg="select desg_name from $schemas.master_desg where desg_code=? and display=? ";
					$sth14=$db->prepare($sql_desg);
					$display='TRUE';
					$sth14->bindParam(1, $judge_desg_code, PDO::PARAM_STR);
					$sth14->bindParam(2, $display, PDO::PARAM_STR);
					$sth14->execute();
					$judge_desg=$sth14->fetchColumn();
				}
			}

			$case_exist=0;
			$case_count="select count(*) as count  from $schemas.case_allocation where listing_date='$listing_date'
			and bench_no='$bench_code1' and court_no='$court_no' and list_flag='$list_flag'";
			$sth15=$db->prepare($case_count);

			$sth15->execute();
			$case_exist=$sth15->fetchColumn();

			if($case_exist == '0' || $case_exist =='')
			{
				$sql_desg="select name from initilization";
				$sth14=$db->prepare($sql_desg);
				$sth14->execute();
				$name_ins=$sth14->fetchColumn();
				$html .= "<tr><td align='center'><font face='Verdana' color='red' size ='4'></font></td></tr>";
			}
			if($case_exist > 0 || $case_exist!='')
			{
				$m = 0;
				if($m==0)
				{
					$m++;
					$sql_desg="select name from initilization";
					$sth14=$db->prepare($sql_desg);
					$sth14->execute();
					$name_ins=$sth14->fetchColumn();
					
					$query ="select short_name from mater_location_city where city_id = ?";
					$main_city_name=$db->prepare($query);
					$main_city_name->bindParam(1, $location_code, PDO::PARAM_STR);
					$main_city_name->execute();
					$main_city_name = $main_city_name->fetchColumn();
					if($main_city_name == 'PB'){
					$main_city_name = "PRINCIPAL BENCH";
					}
					$html .= "<tr><td align='center'><b><font face='Verdana' size ='2'>".htmlspecialchars(strtoupper($name_ins.", ".$main_city_name))."</font></b></td></tr>";
				}
				if($case_exist>0)
				{
					$html .= "<tr><td align='center'><font face='Verdana' size ='2'>FARIDKOT HOUSE, NEW DELHI</font></td></tr><tr><td align='center'><font face='Verdana' size ='2' style='text-decoration:underline;'>CAUSE LIST OF COURT NO .".htmlspecialchars($court_no)."</font></td></tr><tr><td align='center'><font face='Verdana' size ='2'><b>".$causelisthead."</b></font></td></tr><tr><td align='right'><font face='Verdana' size ='2' style=''><b>";
					$sr_no=1;
					$stat="select * from $schemas.bench where  bench_nature ='$listbefore' and from_list_date='$listing_date' and  bench_no='$bench_code1'";
					$stat=$db->prepare("$stat");
					$stat->execute();
					while ($row = $stat->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
					{
						$from_time= $row['from_time'];
						$bench_remarks= $row['detail'];
						$bench_no= $row['bench_no'];
						$court_no =$row['court_no'];
						 $presiding=$row['presiding'];
						 $stat1="select *  from $schemas.master_judge where judge_code ='$presiding'";
						$stat1 = $db->prepare($stat1);
						$stat1->execute();
						$presiding1 = $stat1->fetch();
						//echo $gen=$presiding1['gen']."&nbsp";

						$listingDate = date('j<\s\up>S</\s\up> F, Y', strtotime($listing_date));
						$html .= "Date : $listingDate<br/>Time : $from_time</td></tr><tr><td align='left'><b>";
						$html .= $presiding1['hon_text']." ";
						$html .=  $presiding1['judge_name'];
						$stat1="select desg_name from $schemas.master_desg where desg_code =?";
						$stat1 = $db->prepare($stat1);
						$stat1->bindParam(1,$presiding1['judge_desg_code'], PDO::PARAM_STR);
						$stat1->execute();
						$html .= "<br/>".$stat1->fetchColumn();
					}
					$html .= "<br/><br/>";
					$stat2="select distinct(judge_code) as judge_code from $schemas.bench_judge where bench_nature='$listbefore' and from_list_date='$listing_date' and judge_code !='$presiding' and bench_no='$bench_code1'";
					$stat2=$db->prepare("$stat2");
					$stat2->execute();
					while ($row2 = $stat2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
					{

						$judge_code =$row2['judge_code'];
						$stat1="select judge_name,judge_desg_code,gen,hon_text from $schemas.master_judge where judge_code =?";
						$stat1 = $db->prepare($stat1);
						$stat1->bindParam(1,$judge_code, PDO::PARAM_STR);
						$stat1->execute();
						 $judge_data = $stat1->fetch();
						$gen = $judge_data['gen'];
						 $html .= $judge_data['hon_text'];

						  
						$html .= $gen." ".$judge_data['judge_name'];
						$desg_code = $judge_data['judge_desg_code'];
						$stat1="select desg_name from $schemas.master_desg where desg_code =?";
						$stat1 = $db->prepare($stat1);
						$stat1->bindParam(1,$desg_code, PDO::PARAM_STR);
						$stat1->execute();
						$html .= "<br/> ".$stat1->fetchColumn()."<br/><br/>";
					}
			$html .=	"<tr>
		<div class='table-responsive'>
		<table class='table table-bordered' style='background-color:#ffffff;'>
		<tr>
		<td width='10%' align='center' >
		<b>".strtoupper('s.no.')."</b>
		</td>
		<td width='20%' align='center' >
		<b>".strtoupper('case no.')."</b>
		</td>
		<td width='30%' align='center'>
		<b>".strtoupper("parties")."</b>
		</td>
		<td width='40%' align='center'>
		<b>".strtoupper('counsel for parties')."</b>
		</td>
		</tr>";
		}
		}
		if($b_nature > 0)
		{
		 $sql_purpose="select distinct(purpose), priority from $schemas.bench_purpose_priority where
		from_date='$listing_date' and  bench_no='$bench_code1'  and 
		bench_nature='$b_nature' order by priority ASC";
		$sth_j12=$db->prepare($sql_purpose);
		$sth_j12->execute();
		while ($row = $sth_j12->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		  $purpose_code=$row['purpose'];
		$serial_no =1;
		if($purpose_code > 0)
		{
		   $sql_count="select count(*) as count from $schemas.case_allocation where purpose='$purpose_code' and 
		listing_date='$listing_date' and bench_no='$bench_code1' and 
		court_no='$court_no' and list_flag = '$list_flag'";

		$sth1r=$db->prepare($sql_count);
		$sth1r->execute();
		  $count_filing=$sth1r->fetchColumn();
		}
		if($count_filing > 0)
		{
		$sql_purpose_name="select purpose_name from $schemas.master_purpose where 
		purpose_code='$purpose_code' ";
		$sth4x=$db->prepare($sql_purpose_name);
		//$sth4x->bindParam(1, $purpose_code, PDO::PARAM_STR);
		$sth4x->execute();
		$purpose_name=$sth4x->fetchColumn();
				//$html .= "<pre>"; print_r($purpose_name."klk");
		}
		if($purpose_name !='')
		{
		$html .= "<tr><td align='center' colspan='4'><font face='Verdana' size ='3'><b><br>".htmlspecialchars(ucwords(strtoupper($purpose_name)))."<br><br>";
		$purpose_name ='';
		$html .="</font></td></tr>";
		}
		$count=0;
		$countzz=1;
		 $sql_allocation="select a.filing_no ,a.remarks  from $schemas.case_allocation a,
		$schemas.case_detail d where d.status =? and  a.purpose=? and a.listing_date=?
		and a.bench_no=? and a.court_no=? and a.list_flag = ?  and
		a.filing_no=d.filing_no order by  a.priority_serial,d.case_no,d.case_year asc";
		
		$status='P';
		/* echo "select a.filing_no ,a.remarks  from $schemas.case_allocation_temp a,
		$schemas.case_detail d where d.status ='$status' and  a.purpose='$purpose_code' and a.listing_date='$listing_date'
		and a.bench_no='$bench_code1' and a.court_no='$court_no' and a.list_flag = $list_flag  and
		a.filing_no=d.filing_no order by  a.priority_serial,d.case_no,d.case_year asc";
		echo "<br/>"; */
		$sth_j12c=$db->prepare($sql_allocation);
		$sth_j12c->bindParam(1, $status, PDO::PARAM_STR);
		$sth_j12c->bindParam(2, $purpose_code, PDO::PARAM_STR);
		$sth_j12c->bindParam(3, $listing_date, PDO::PARAM_STR);
		//$sth_j12c->bindParam(4, $list_flag, PDO::PARAM_STR);
		$sth_j12c->bindParam(4, $bench_code1, PDO::PARAM_STR);
		$sth_j12c->bindParam(5, $court_no, PDO::PARAM_STR);
		$sth_j12c->bindParam(6, $list_flag, PDO::PARAM_STR);
		$sth_j12c->execute();
		while ($row1 = $sth_j12c->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
			
		 $filing_no=$row1['filing_no'];

		$hc_dc_caseno=$row1['hc_dc_caseno'];
		$remarkss=$row1['remarks'];

		 $sql_cd="select a.pet_type,a.location_code,a.res_type,a.legal_aid ,
		a.oa_ref_no,a.case_no, a.case_type,a.case_year, a.pet_name,a.res_name,a.pet_adv_name,
		a.res_adv_name,a.bench_location from $schemas.case_detail as a  where a.filing_no=?
		and status =? order by case_type,case_no ASC";
		/* echo "select a.pet_type,a.location_code,a.res_type,a.legal_aid ,
		a.oa_ref_no,a.case_no, a.case_type,a.case_year, a.pet_name,a.res_name,a.pet_adv_name,
		a.res_adv_name,a.bench_location from $schemas.case_detail as a  where a.filing_no=$filing_no
		and status ='$status' order by case_type,case_no ASC";
		echo "<br/>"; */
		$status='P';
		$sth_j12cc=$db->prepare($sql_cd);
		$sth_j12cc->bindParam(1, $filing_no, PDO::PARAM_STR);
		$sth_j12cc->bindParam(2, $status, PDO::PARAM_STR);
		$sth_j12cc->execute();

		while ($row2 = $sth_j12cc->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		$count++;
		$oa_ref_no=$row2['oa_ref_no'];

		if($oa_ref_no!='')
		{
		$ref_newst31="select case_type,case_no,case_year,location_code from $schemas.case_detail where filing_no='$oa_ref_no' order by case_type asc";
		$ref_newst31=$db->prepare($ref_newst31);
		$ref_newst31->execute();
		$ref_resultset1 = $ref_newst31->fetch();
		extract($ref_resultset1);

		$ref_lcode ="select short_name from $schemas.bench_location where bench_location_code ='$location_code'";
		$ref_lcode=$db->prepare($ref_lcode);
		$ref_lcode->execute();
		$ref_lcodename = $ref_lcode->fetchColumn();
		if($case_type > 0)
		{
		$ref_stQ = $db->prepare("select short_name from case_type where id = ?");
		$ref_stQ->bindParam(1, $case_type, PDO::PARAM_STR);
		$ref_stQ->execute();
		$ref_case_type_short_name=$ref_stQ->fetchColumn();
		}



		$ref_case_numaa = $case_no;
		$ref_case_year1aa = $case_year;
				$ref_case_num1aa=ltrim($case_numaa,0);
				
				$ref_CASE_NO = htmlspecialchars(strtoupper($ref_case_type_short_name).'/'.$ref_case_num1aa.'('.$ref_lcodename.')'.$ref_case_year1aa);


		}


		 $case_no=$row2['case_no'];
		 $case_type=$row2['case_type'];
		 $case_year=$row2['case_year'];
		$pet_name=$row2['pet_name'];
		$res_name=$row2['res_name'];
		//$location_code =$row2['location_code'];
		$location_code = $row2['bench_location'];


		$lcode ="select short_name from $schemas.bench_location where bench_location_code ='$location_code'";
		$lcode=$db->prepare($lcode);
		$lcode->execute();
		$lcodename = $lcode->fetchColumn();

		$noaddfilingno =$row2['filing_no'];
		
		if($case_type > 0)
		{
		$stQ = $db->prepare("select case_type_desc from case_type where id = ?");
		$stQ->bindParam(1, $case_type, PDO::PARAM_STR);
		$stQ->execute();
		$case_type_short_name=$stQ->fetchColumn();
		}



		$case_numaa = $case_no;
		$case_year1aa = $case_year;
				$case_num1aa=ltrim($case_numaa,0);
				
				 $CASE_NO = htmlspecialchars(strtoupper($case_type_short_name).' No. '.$case_num1aa.'/'.$case_year1aa);
				$html .= remark_text($fetch_remarks,$sr_no);

		$html .="<tr>
		<td width='10%' align='center' valign='top'><font size='2'>".htmlspecialchars($sr_no++).".</font></td>
		<td width='20%' align='center' valign='top'>
		<font size='2' >";
		
		   if($case_type == 6 || $case_type == 3 || $case_type == 2 || $case_type == 5){
				$table = 'case_detail_ma_ia';
				$column = 'filing_no_ia_ma';
			}else{
				$table = 'e_case_detail';
				$column = 'filing_no';
			}
		   
		   $stqq = $dbonline->prepare("select count(filing_no) from $table  where $column=?");
		   $stqq->bindParam(1, $filing_no, PDO::PARAM_INT);
		   $stqq->execute();
		   $filing_norevari = $stqq->fetchColumn();
		  
		   if($filing_norevari > '0')
		   {
		  $sthr=$dbonline->prepare("select * from e_case_detail  where filing_no=? ");
		  $sthr->bindParam(1, $filing_no, PDO::PARAM_STR);
		  $sthr->execute();
		  while ($rowa = $sthr->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		  {
			 $fileupload=$rowa['unique_id_no'];
		  } 
		  // } 
		 
		 $html .= "</font><font face='Verdana' size ='2'>";


		$serial_no=$sr_no-1;
					 
		$html .= "<font color='#900C3F' size='2'>".$CASE_NO."</font>";
				
		$case_type_array = array(2,3,5,6);
		if (in_array($case_type, $case_type_array)){
			$html .= "<br/> In <br/>";
			$html .= main_case_no($schemas,$db,$case_type,$filing_no);
		}
		
		$html .= main_cases_child($schemas,$db,$filing_no,$status = 'p');
		$all_connected_cases = get_connected_cases($schemas,$db,$filing_no);
		if(!empty($all_connected_cases)){
				$html .= '<br/><br/> WITH';
			}
		//$html .= connected_cases($schemas,$db,$filing_no);


		 }


		if($ref_CASE_NO!=''){
			$html .= "<br>In<br>".$ref_CASE_NO;
		}
		$html .="</font></td>";



		if($case_type=='14' || $case_type=='15')
		{
			$counter2=2;
				if($counter2=='2'){

			$html .="<td width='30%' valign='top'>
			<font face='Verdana' size ='2'>".$pet_name;

			$counter2 = $counter2 +1;
				}
			$dit="And";
			$party_ser='1';
			$part_flag='P';
			
		$st22=$dbonline->prepare("select * from e_cases_party where filing_no=? and party_serial_no!=? and party_flag=?");
							  $st22->bindParam(1, $filing_no, PDO::PARAM_STR);
							  $st22->bindParam(2, $party_ser, PDO::PARAM_STR);
							  $st22->bindParam(3, $part_flag, PDO::PARAM_STR);
							  $st22->execute();
		$counter = 1;
							  while ($row22= $st22->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
							  {    
								  $pet_party=$row22['name'];
								  if($counter == '1' && $counter2 == '2'){
									
		$html .="<font face='Verdana' size ='2'>".$pet_name." <br>".$dit."<br>".$pet_party."</font>";
								  $counter = $counter+1;
								  }else{
								  $html .="<font face='Verdana' size ='2'><br>".$dit."<br>".$pet_party."</font>";

									  
								  }							
							  }
		}
		else
		{
			
			$dit="Vs";
		$html .="<td align='center' width='30%' valign='top'>
		<font face='Verdana' size ='2'>".$pet_name." <br>".$dit."<br>".$res_name."
		</font>
		</td>";

			}
	

		$html .="<td width='40%' align='center' valign='top'>";
		
			if (in_array($case_type, $case_type_array)){
				$main_case_filing_no = get_main_case_filing_no($schemas,$db,$filing_no);
			}else{
				$main_case_filing_no = $filing_no;
			}

		$st12=$dbonline->prepare("select * from e_more_representative where filing_no=? and party_flag='P' and display='t'");
			$st12->bindParam(1, $main_case_filing_no, PDO::PARAM_STR);
			
			$st12->execute();
			while ($row12 = $st12->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
			{
				
				 $adv_id = htmlspecialchars($row12['rep_code']);
			
			
			$stqq12 = $dbonline->prepare("select rep_name from e_master_advocate where id=?");
			$stqq12->bindParam(1, $adv_id, PDO::PARAM_INT);
			$stqq12->execute();
			  $pet_advname22 = $stqq12->fetchColumn();

			$html .="<font face='Verdana' size ='2'>"; 
			  $html .= strtoupper($pet_advname22).'<br>';
			}	 
			$html .="--------------------<br>";

		$st121=$dbonline->prepare("select * from e_more_representative where filing_no=? and party_flag='R' and display='t'");
			$st121->bindParam(1, $filing_no, PDO::PARAM_STR);
			
			$st121->execute();
			while ($row121 = $st121->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
			{
				
				 $res_adv_id = htmlspecialchars($row121['rep_code']);
			
			
			$stqq121 = $dbonline->prepare("select rep_name from e_master_advocate where id=?");
			$stqq121->bindParam(1, $res_adv_id, PDO::PARAM_INT);
			$stqq121->execute();
			  $res_advname22 = $stqq121->fetchColumn();
			$html .="<font face='Verdana' size ='2'>";  
			  $html .= strtoupper($res_advname22).'<br>';
			}	 
		$html .="</font><br/><b>".$remarkss."</b></td></tr>";
		$all_connected_cases = get_connected_cases($schemas,$db,$filing_no);
		if(!empty($all_connected_cases)){
			
			foreach($all_connected_cases as $key=>$con_cases){
				$conected_case_record = get_case_details($schemas,$db,$con_cases['conn_filing_no']);
				$html .= "<tr><td width='10%' align='center' valign='top'><font face='Verdana' size ='2'>".$sr_no++."</font></td><td width='20%' align='center' valign='top'><font face='Verdana' size ='2'><a href='javascript::void();'>".connected_case_no($schemas,$db,$conected_case_record)."</a>".$with_text."</font></td><td width='30%' align='center' valign='top'><font face='Verdana' size ='2'>".$conected_case_record['pet_name']."<br/>VS</br>".$conected_case_record['res_name']."</font></td>";
				$html .=  "<td width='40%' align='center' valign='top'>".counsel_parties($schemas,$db,$dbonline,$case_type_array,$conected_case_record['case_type'],$con_cases['conn_filing_no'])."</td></tr>";

			}
		}
		}
		}
		}
		}
		}

	}
	$html .= "</table></div></table>";
	$html .= "</div>";
	return $html;
}

function proceeding_status($schemas,$db,$filing_no,$listdate_entire){
	$is_proceeded = $db->prepare("select count(*) as count from $schemas.case_proceeding where filing_no = ? and listing_date = ?");
	$is_proceeded->bindParam(1, $filing_no, PDO::PARAM_STR);
	$is_proceeded->bindParam(2, $listdate_entire, PDO::PARAM_STR);
	$is_proceeded->execute();
	$res = $is_proceeded->fetchColumn();
	return $res;
}

function order_status($schemas,$db,$filing_no,$listdate_entire){
	$order_date = $db->prepare("select flag from $schemas.order_daily where filing_no = ? and order_date = ?");
	$order_date->bindParam(1, $filing_no, PDO::PARAM_STR);
	$order_date->bindParam(2, $listdate_entire, PDO::PARAM_STR);
	$order_date->execute();
	$status = $order_date->fetchColumn();
	if($status == ''){
		$stat = "<b>Not Created</b>";
	}elseif($status == 'Y'){
		$stat = "<b style='color:green;'>Order Uploaded</b>";
	}elseif($status == 'N'){
		$stat = "<b style='color:red;'>Draft Order</b>";
	}else{
		$stat = '';
	}
	return $stat;
}

function all_child_cases($schemas,$db,$filing_no,$status){
	$cases = generate_case_no_new($schemas,$db,$filing_no,$status);
	return $cases;
}

function selected_child_cases($schemas,$db,$filing_no,$status,$listing_date,$bench_no,$list_flag,$court_no,$filing_no_listed){
	if(!empty($filing_no_listed)){
	$stq = $db->prepare("select vcc.filing_no,cast(cd.case_no as integer) as case_no,cd.case_type,cd.case_year,ct.short_name as case_type_short_name,mlc.short_name from $schemas.viewable_child_cases as vcc 
						LEFT JOIN $schemas.case_detail as cd on cd.filing_no = vcc.filing_no 
						LEFT JOIN case_type as ct on ct.id = cd.case_type 
						LEFT JOIN mater_location_city as mlc ON mlc.city_id = cd.location_code
						where vcc.parent_filing_no = ? and vcc.display = ? and vcc.listing_date = ? and vcc.bench_no = ? and vcc.list_flag = ? and vcc.court_no=? and vcc.listed_filing_no = ? order by cd.case_year,cd.case_no asc");
	}else{
		$stq = $db->prepare("select vcc.filing_no,cast(cd.case_no as integer) as case_no,cd.case_type,cd.case_year,ct.short_name as case_type_short_name,mlc.short_name from $schemas.viewable_child_cases as vcc 
						LEFT JOIN $schemas.case_detail as cd on cd.filing_no = vcc.filing_no 
						LEFT JOIN case_type as ct on ct.id = cd.case_type 
						LEFT JOIN mater_location_city as mlc ON mlc.city_id = cd.location_code
						where vcc.parent_filing_no = ? and vcc.display = ? and vcc.listing_date = ? and vcc.bench_no = ? and vcc.list_flag = ? and vcc.court_no=? order by cd.case_year,cd.case_no asc");
	}
	$stq->bindParam(1, $filing_no, PDO::PARAM_STR);
	$stq->bindParam(2, $status, PDO::PARAM_STR);
	$stq->bindParam(3, $listing_date, PDO::PARAM_STR);
	$stq->bindParam(4, $bench_no, PDO::PARAM_STR);
	$stq->bindParam(5, $list_flag, PDO::PARAM_STR);
	$stq->bindParam(6, $court_no, PDO::PARAM_STR);
	if(!empty($filing_no_listed)){
		$stq->bindParam(7, $filing_no_listed, PDO::PARAM_STR);
	}
	$stq->execute();
	$res = $stq->fetchAll();
	return $res;
}

function save_selected_child_cases_log($schemas,$db,$filing_no,$listing_date,$bench_no,$list_flag,$court_no){
	$stq = $db->prepare("insert into $schemas.viewable_child_cases_his (select * from $schemas.viewable_child_cases where parent_filing_no = ? and listing_date = ? and bench_no = ? and list_flag = ? and court_no = ?)");
	$stq->bindParam(1, $filing_no, PDO::PARAM_STR);
	$stq->bindParam(2, $listing_date, PDO::PARAM_STR);
	$stq->bindParam(3, $bench_no, PDO::PARAM_STR);
	$stq->bindParam(4, $list_flag, PDO::PARAM_STR);
	$stq->bindParam(5, $court_no, PDO::PARAM_STR);
	$res = $stq->execute();
	if($res){
	$delete = $db->prepare("delete from $schemas.viewable_child_cases where parent_filing_no = ? and listing_date = ? and bench_no = ? and list_flag = ? and court_no = ?");
	$delete->bindParam(1, $filing_no, PDO::PARAM_STR);
	$delete->bindParam(2, $listing_date, PDO::PARAM_STR);
	$delete->bindParam(3, $bench_no, PDO::PARAM_STR);
	$delete->bindParam(4, $list_flag, PDO::PARAM_STR);
	$delete->bindParam(5, $court_no, PDO::PARAM_STR);
	$res_delete = $delete->execute();
	return $res_delete;
	}
}
	
function insert_selected_child_cases($schemas,$db,$filing_no,$child_filing_no,$listing_date,$bench_no,$list_flag,$court_no,$bench_nature,$entry_date,$user_id,$display,$username,$listed_fn){
	$stq = $db->prepare("insert into $schemas.viewable_child_cases (filing_no,parent_filing_no,listing_date,court_no,bench_no,bench_nature,entry_date,user_id,display,username,list_flag,listed_filing_no) values (?,?,?,?,?,?,?,?,?,?,?,?)");
	$stq->bindParam(1, $child_filing_no, PDO::PARAM_STR);
	$stq->bindParam(2, $filing_no, PDO::PARAM_STR);
	$stq->bindParam(3, $listing_date, PDO::PARAM_STR);
	$stq->bindParam(4, $court_no, PDO::PARAM_STR);
	$stq->bindParam(5, $bench_no, PDO::PARAM_STR);
	$stq->bindParam(6, $bench_nature, PDO::PARAM_STR);
	$stq->bindParam(7, $entry_date, PDO::PARAM_STR);
	$stq->bindParam(8, $user_id, PDO::PARAM_STR);
	$stq->bindParam(9, $display, PDO::PARAM_STR);
	$stq->bindParam(10, $username, PDO::PARAM_STR);
	$stq->bindParam(11, $list_flag, PDO::PARAM_STR);
	$stq->bindParam(12, $listed_fn, PDO::PARAM_STR);
	$res = $stq->execute();
	
}

function show_selected_child_cases($schemas,$db,$filing_no,$status,$listing_date,$bench_no,$list_flag,$court_no,$filing_no_listed){
	$cases = selected_child_cases($schemas,$db,$filing_no,$status,$listing_date,$bench_no,$list_flag,$court_no,$filing_no_listed);
	$count_cases = count($cases);
	if($count_cases == 1 && $cases[0]['filing_no'] == 'NA'){
		return '';
	}
	foreach($cases as $key=>$value){
		$case_nos[] = generate_case_no_by_filing_no($schemas,$db,$value['filing_no']);
	}
	$case_no = '';
	if(!empty($case_nos)){
		$case_no = '<br/>( '.implode(',',$case_nos).' )';
	}
	return $case_no;
}


function generate_case_no_by_filing_no($schemas,$db,$filing_no){
	$query = "SELECT
			 coalesce(ct.short_name,'')||' No '|| coalesce(cd.case_no,'')||'/'||coalesce(bl.short_name,'')||'/'||coalesce(cd.case_year,'')
			FROM
			 $schemas.case_detail as cd
			LEFT JOIN case_type as ct ON ct.id = cd.case_type
			LEFT JOIN $schemas.bench_location as bl ON bl.city_id = cd.location_code
			where cd.filing_no = ?";
	$stQ = $db->prepare($query);
	$stQ->bindParam(1, $filing_no, PDO::PARAM_STR);
	$stQ->execute();
	$cases=$stQ->fetchColumn();
	return $cases;
}

function recursive_cases($schemas,$db,$case_type,$filing_no){
	$select = $db->prepare("select filing_no,main_case_ia_no,case_type from $schemas.case_detail where filing_no = ?");
	$select->bindParam(1, $filing_no, PDO::PARAM_STR);
	$select->execute();
	$res = $select->fetchAll();
	$res = array_shift($res);
	$res['main_case_ia_no'];
	if(!empty($res['main_case_ia_no'])){
		//echo "<br/> In <br/>";
		 $in_filing_no = main_case_no($schemas,$db,$case_type,$filing_no);
	}
	if(!empty($in_filing_no)){
		echo "<br/> In <br/>";
		echo $in_filing_no;
	}
	
	/* if(!empty($res['ia_ma_filing_no'])){
		echo "<br/> In <br/>";
		echo $in_filing_no = main_case_no($schemas,$db,$case_type,$filing_no);
		$new_filing_no = $res['ia_ma_filing_no'];
		$new_case_type = $res['case_type'];
		echo recursive_cases($schemas,$db,$new_case_type,$new_filing_no);
	}else{
		return;
	} */
}

function child_cases($schema,$db,$filing_no,$status){
	$select = $db->prepare("select cd.filing_no,cd.case_no,cd.case_type,cd.case_year,ct.short_name from $schema.case_detail as cd left join case_type as ct on ct.id = cd.case_type where cd.main_case_ia_no = ? and cd.status = ?");
	$select->bindParam(1, $filing_no, PDO::PARAM_STR);
	$select->bindParam(2, $status, PDO::PARAM_STR);
	$select->execute();
	$res = $select->fetchAll();
	return $res;
}

function save_log_and_delete($schemas,$db,$filing_no,$listing_date,$bench_no,$court_no,$list_flag){
	$stq = $db->prepare("insert into $schemas.final_child_and_connected_cases_his (select * from $schemas.final_child_and_connected_cases where parent_filing_no = ? and listing_date = ? and bench_no = ? and list_flag = ? and court_no = ?)");
	$stq->bindParam(1, $filing_no, PDO::PARAM_STR);
	$stq->bindParam(2, $listing_date, PDO::PARAM_STR);
	$stq->bindParam(3, $bench_no, PDO::PARAM_STR);
	$stq->bindParam(4, $list_flag, PDO::PARAM_STR);
	$stq->bindParam(5, $court_no, PDO::PARAM_STR);
	$res = $stq->execute();
	if($res){
	$delete = $db->prepare("delete from $schemas.final_child_and_connected_cases where parent_filing_no = ? and listing_date = ? and bench_no = ? and list_flag = ? and court_no = ?");
	$delete->bindParam(1, $filing_no, PDO::PARAM_STR);
	$delete->bindParam(2, $listing_date, PDO::PARAM_STR);
	$delete->bindParam(3, $bench_no, PDO::PARAM_STR);
	$delete->bindParam(4, $list_flag, PDO::PARAM_STR);
	$delete->bindParam(5, $court_no, PDO::PARAM_STR);
	$res_delete = $delete->execute();
	return $res_delete;
	}
}

function insert_final_child_cases($schemas,$db,$filing_no,$child_filing_no,$listing_date,$bench_no,$list_flag,$court_no,$bench_nature,$user_id,$username,$child_or_connected,$listed_filing_no,$pet_name='',$res_name=''){
	$stq = $db->prepare("insert into $schemas.final_child_and_connected_cases (filing_no,parent_filing_no,listing_date,court_no,bench_no,bench_nature,user_id,child_or_connected,username,list_flag,pet_name,res_name,listed_filing_no) values (?,?,?,?,?,?,?,?,?,?,?,?,?)");
	$stq->bindParam(1, $child_filing_no, PDO::PARAM_STR);
	$stq->bindParam(2, $filing_no, PDO::PARAM_STR);
	$stq->bindParam(3, $listing_date, PDO::PARAM_STR);
	$stq->bindParam(4, $court_no, PDO::PARAM_STR);
	$stq->bindParam(5, $bench_no, PDO::PARAM_STR);
	$stq->bindParam(6, $bench_nature, PDO::PARAM_STR);
	$stq->bindParam(7, $user_id, PDO::PARAM_STR);
	$stq->bindParam(8, $child_or_connected, PDO::PARAM_STR);
	$stq->bindParam(9, $username, PDO::PARAM_STR);
	$stq->bindParam(10, $list_flag, PDO::PARAM_STR);
	$stq->bindParam(11, $pet_name, PDO::PARAM_STR);
	$stq->bindParam(12, $res_name, PDO::PARAM_STR);
	$stq->bindParam(13, $listed_filing_no, PDO::PARAM_STR);
	$res = $stq->execute();
}

function show_selected_final_child_cases($schemas,$db,$filing_no,$display,$listing_date,$bench_no,$list_flag,$court_no,$child_or_connected,$listed_filing_no){
	$cases = selected_final_cases($schemas,$db,$filing_no,$display,$listing_date,$bench_no,$list_flag,$court_no,$child_or_connected,$listed_filing_no);
	foreach($cases as $key=>$value){
		$case_nos[] = generate_case_no_by_filing_no($schemas,$db,$value['filing_no']);
	}
	$case_no = '';
	if(!empty($case_nos)){
		$case_no = '<br/>( '.implode(',',$case_nos).' )';
	}
	return $case_no;
}

function selected_final_cases($schemas,$db,$filing_no,$display,$listing_date,$bench_no,$list_flag,$court_no,$child_or_connected,$listed_filing_no){
	if(!empty($listed_filing_no)){
	$stq = $db->prepare("select fcc.filing_no,cast(cd.case_no as integer) as case_no,cd.case_type,cd.case_year,ct.short_name as case_type_short_name,mlc.short_name,fcc.pet_name,fcc.res_name from $schemas.final_child_and_connected_cases as fcc 
						LEFT JOIN $schemas.case_detail as cd on cd.filing_no = fcc.filing_no 
						LEFT JOIN case_type as ct on ct.id = cd.case_type 
						LEFT JOIN mater_location_city as mlc ON mlc.city_id = cd.location_code
						where fcc.parent_filing_no = ? and fcc.display = ? and fcc.listing_date = ? and fcc.bench_no = ? and fcc.list_flag = ? and fcc.court_no=? and fcc.child_or_connected = ? and listed_filing_no = ? order by cd.case_year,cd.case_no asc");
	}else{
	$stq = $db->prepare("select fcc.filing_no,cast(cd.case_no as integer) as case_no,cd.case_type,cd.case_year,ct.short_name as case_type_short_name,mlc.short_name,fcc.pet_name,fcc.res_name from $schemas.final_child_and_connected_cases as fcc 
						LEFT JOIN $schemas.case_detail as cd on cd.filing_no = fcc.filing_no 
						LEFT JOIN case_type as ct on ct.id = cd.case_type 
						LEFT JOIN mater_location_city as mlc ON mlc.city_id = cd.location_code
						where fcc.parent_filing_no = ? and fcc.display = ? and fcc.listing_date = ? and fcc.bench_no = ? and fcc.list_flag = ? and fcc.court_no=? and fcc.child_or_connected = ? order by cd.case_year,cd.case_no asc");
	}
	$stq->bindParam(1, $filing_no, PDO::PARAM_STR);
	$stq->bindParam(2, $display, PDO::PARAM_STR);
	$stq->bindParam(3, $listing_date, PDO::PARAM_STR);
	$stq->bindParam(4, $bench_no, PDO::PARAM_STR);
	$stq->bindParam(5, $list_flag, PDO::PARAM_STR);
	$stq->bindParam(6, $court_no, PDO::PARAM_STR);
	$stq->bindParam(7, $child_or_connected, PDO::PARAM_STR);
	if(!empty($listed_filing_no)){
		$stq->bindParam(8, $listed_filing_no, PDO::PARAM_STR);
	}
	$stq->execute();
	$res = $stq->fetchAll();
	return $res;
}

function get_display_court_text($db,$schemas,$court_no){
	$select = $db->prepare("select causelist_court_text from $schemas.court where court_no = ?");
	$select->bindParam(1, $court_no, PDO::PARAM_STR);
	$select->execute();
	$res = $select->fetchColumn();
	return $res;
}


function get_causelist_remark($db,$schemas,$listdate_entire,$court_no_remark,$list_flag_remark){
	try {
	$query = "select remark,remark_footer from $schemas.causelist_remark where listing_date = ? and court_no = ? and list_flag = ?";
	$data = $db->prepare($query);
    $data->bindParam(1, $listdate_entire, PDO::PARAM_STR);
	$data->bindParam(2, $court_no_remark, PDO::PARAM_STR);
	$data->bindParam(3, $list_flag_remark, PDO::PARAM_STR);
    $data->execute();
	$data = $data->fetchAll();
	$data = array_shift($data);
	return $data;
	} catch (PDOException $ex) {
        echo $ex;
    }
}

function main_case_type(){
	$main_case_type = array(32,33,34);
	return $main_case_type;
}

function get_party($db,$filing_no,$party_flag,$party_serial_no){
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
	
function check_is_partially_defective($db,$schemas,$filing_no,$listdate_entire){
	try {
		$one = 1;
		$dummy_date = '9999-01-01';
	$query = "select is_partially_defective from $schemas.case_detail where filing_no = ? and is_partially_defective = ? and (partially_defect_free_date = ? or partially_defect_free_date > ?)";
	$party = $db->prepare($query);
    $party->bindParam(1, $filing_no, PDO::PARAM_STR);
	$party->bindParam(2, $one, PDO::PARAM_STR);
	$party->bindParam(3, $dummy_date, PDO::PARAM_STR);
	$party->bindParam(4, $listdate_entire, PDO::PARAM_STR);
    $party->execute();
	$name = $party->fetchColumn();
	return $name;
	} catch (PDOException $ex) {
        echo $ex;
    }
}

function get_bench_address($db,$schemas,$city_id){
	try {
	$query = "select bench_address from $schemas.bench_location where city_id = ?";
	$city_address = $db->prepare($query);
    $city_address->bindParam(1, $city_id, PDO::PARAM_STR);
    $city_address->execute();
	$city_address = $city_address->fetchColumn();
	return $city_address;
	} catch (PDOException $ex) {
        echo $ex;
    }
}

function get_bench_name(){
	
}

function generate_transfrerred_case_no_by_filing_no($schemas,$db,$filing_no,$transfrred_case_type){
	$query = "SELECT
			 coalesce('Old Appeal','')||' No. '|| coalesce(cd.case_no,'')||'/'||coalesce(bl.short_name,'')||'/'||coalesce(cd.case_year,'')
			FROM
			 $schemas.case_detail as cd
			LEFT JOIN case_type as ct ON ct.id = cd.case_type
			LEFT JOIN $schemas.bench_location as bl ON bl.city_id = cd.location_code
			where cd.filing_no = ?";
	$stQ = $db->prepare($query);
	$stQ->bindParam(1, $filing_no, PDO::PARAM_STR);
	$stQ->execute();
	$cases=$stQ->fetchColumn();
	return $cases;
}

function get_old_case_info($db,$filing_no){
	try {
	$query = "select transfer_nclat_filing_no,transfer_bench_location,transfer_case_number,transfer_case_type from e_case_detail where filing_no = ?";
	$transferred_case = $db->prepare($query);
    $transferred_case->bindParam(1, $filing_no, PDO::PARAM_STR);
    $transferred_case->execute();
	$transferred_case = $transferred_case->fetch();
	return $transferred_case;
	} catch (PDOException $ex) {
        echo $ex;
    }
}

function get_transferred_case_schema($db,$transferred_filing_no,$trsferred_case_schema_id){
try {
	$query = "select mlc.schema_name from e_master_bench as emb left join mater_location_city as mlc on mlc.city_id = emb.city_id
			where emb.e_master_bench_id = ?";
	$transferred_case = $db->prepare($query);
    $transferred_case->bindParam(1, $trsferred_case_schema_id, PDO::PARAM_STR);
    $transferred_case->execute();
	$transferred_case = $transferred_case->fetchColumn();
	return $transferred_case;
	} catch (PDOException $ex) {
        echo $ex;
    }
} 


function old_case_num($db,$schemas,$filing_no){
	$transferred_case_info = get_old_case_info($db,$filing_no);
	if(!empty($transferred_case_info)){
		$transferred_filing_no = $transferred_case_info['transfer_nclat_filing_no'];
		$trsferred_case_schema_id = $transferred_case_info['transfer_bench_location'];
		$transfer_case_type = $transferred_case_info['transfer_case_type'];
		if(!empty($transferred_filing_no)){
		if(!empty($trsferred_case_schema_id)){
			$transferred_case_schema = get_transferred_case_schema($db,$transferred_filing_no,$trsferred_case_schema_id);
			if(!empty($transferred_case_schema)){
				$case_no = generate_transfrerred_case_no_by_filing_no($transferred_case_schema,$db,$transferred_filing_no,$transfer_case_type);
				
				$case_no= ' ('.$case_no.')';
				return $case_no;
			}else{
				return '';
			} 
		}else{
			return '';
		}
		} else{
			return '';
		}
	}else{
		return '';
	}
}

function get_advocate_list($db,$schemas,$filing_no,$listdate_entire,$bench_code1,$court_no,$list_flag,$party_flag){
	try {
	$query = "select * from $schemas.causelist_advocate where filing_no = ? and listing_date = ? and court_no = ? and bench_no = ? and list_flag = ? and adv_type = ?";
	$adv_list = $db->prepare($query);
    $adv_list->bindParam(1, $filing_no, PDO::PARAM_STR);
    $adv_list->bindParam(2, $listdate_entire, PDO::PARAM_STR);
    $adv_list->bindParam(3, $court_no, PDO::PARAM_STR);
    $adv_list->bindParam(4, $bench_code1, PDO::PARAM_STR);
    $adv_list->bindParam(5, $list_flag, PDO::PARAM_STR);
    $adv_list->bindParam(6, $party_flag, PDO::PARAM_STR);
    $adv_list->execute();
	$adv_list = $adv_list->fetchAll();
	return $adv_list;
	} catch (PDOException $ex) {
        echo $ex;
    }
}

?>