<?php

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d');
include("../db_inc1.php");
include '../db_inc2.php';

$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
$sessionUserType=htmlspecialchars($_SESSION['id']);

$location_access=$_SESSION['location'];


if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
echo "Access Problem....."; 
header("Location: ./login.php");
die();
}


if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
	
	$_SESSION['qqcc'] = rand();
    $hash=$_SESSION['qqcc'];
	
	function get_data($db,$dbonline,$schemas,$location_access,$user_type,$localadmin,$search_type,$status,$na,$hash,$c_case,$data1,$data2){
		$get_cases = get_cases($db,$dbonline,$schemas,$location_access,$user_type,$localadmin,$search_type,$status,$na,$c_case,$data1,$data2);
		$html = '';
		if(!empty($get_cases) && $c_case == 1){
				$count = 1;
			foreach($get_cases as $key=>$cases){
				$filing_no = htmlspecialchars($cases['filing_no']);
				if($cases['scrutiny_comp3'] == 2){
					$tr_back_color = "#f8c6bf";
					$check_days_gap = days_gap($db,$dbonline,$schemas,$filing_no);
					if($check_days_gap == 'continue'){
						continue;
					}else{
						$number_of_docs = get_number_of_docs($dbonline,$column='filing_no',$filing_no,$scrutiny = 0,$display = 't');
						if($number_of_docs > 0){
						$onlclick = "";
						$text_rejected = "Scrutiny";
						$class = "label-success";
					  }else{
						$text_rejected = "Returned";
						$onlclick = "onclick='return false;'";
						$class = "label-danger";
					  }				  
					}
				}else{
				$onlclick = "";
				$text_rejected = "Scrutiny";
				$class = "label-success";
				$tr_back_color='#BDFCC9';
				}
				
				$dt_of_filing = htmlspecialchars($cases['dt_of_filing']);
				list($year,$month,$day)=explode('-',$dt_of_filing);
				$filing_date_all=$day.'/'.$month.'/'.$year;
				$filing_date_all = ($filing_date_all =='11/11/1111' || $filing_date_all =='//') ?'':htmlspecialchars($filing_date_all);
				$pet_party_detail = party_details($dbonline,$party_flag = 'P',$party_serial = 1,$filing_no);
				$res_party_detail = party_details($dbonline,$party_flag = 'R',$party_serial = 1,$filing_no);
				$pet_name = $pet_party_detail['name'];
				$res_name = $res_party_detail['name'];
				$number_of_docs = get_number_of_docs($dbonline,$column='filing_no',$filing_no,$scrutiny = 0,$display = 't');
				$total_fees = get_fees($dbonline,$filing_no);
				$filing_nosend=$filing_no.'-'.$hash;
				$filing_no_send=base64_encode($filing_nosend);
				$html .=  show_html($filing_no,$tr_back_color,$count,$filing_date_all,$number_of_docs,$pet_name,$res_name,$total_fees,$filing_no_send,$c_case,$class,$text_rejected,$onlclick);	
				$count++;
			}
		}
		if(!empty($get_cases) && $c_case == 3){
			foreach($get_cases as $key=>$cases){
				$ia_id =$cases['id'];
				$ia_filing_no =$cases['filing_no_ia_ma'];	
				$main_filing_no =$cases['filing_no'];
				$ia_party_flag = $cases['party_in_case_flag'];
				$party_id = $cases['party_id'];
				$type_of_filing = $cases['type_of_filing'];
				$misc_filing_no = $cases['misc_filing_no'];
				$ia_dt_of_filing = $cases['filed_date'];
				$display_date = date('d/m/Y', strtotime($ia_dt_of_filing));
				$count = $key+1;
				if(!empty($ia_filing_no) || $ia_filing_no != ''){
					$get_main_case_details = main_case_details($db,$schemas,$main_filing_no);
					if(!empty($get_main_case_details)){
						$ia_main_case_type=htmlspecialchars($get_main_case_details['case_type']);
						$ia_main_case_no=htmlspecialchars($get_main_case_details['case_no']);
						$ia_main_case_year=htmlspecialchars($get_main_case_details['case_year']);
						$ia_main_location_code=htmlspecialchars($get_main_case_details['location_code']);
						$case_type_full_name = get_name($db,$table='case_type',$column='case_type_desc',$search_column_name='id',$ia_main_case_type);
						$location_short_name = get_name($db,$table='mater_location_city',$column='short_name',$search_column_name='city_id',$ia_main_location_code);
						$ia_main_case_no_final=$case_type_full_name.'/'.$ia_main_case_no.'('.$location_short_name.')'.$ia_main_case_year;
						$pet_party_detail = party_details($dbonline,$party_flag = 'P',$party_serial = 1,$main_filing_no);
						$res_party_detail = party_details($dbonline,$party_flag = 'R',$party_serial = 1,$main_filing_no);
						$pet_name = $pet_party_detail['name'];
						$res_name = $res_party_detail['name'];
						$party_name = get_party_name($dbonline,$main_filing_no,$ia_filing_no,$party_id,$ia_party_flag);
						$number_of_docs = get_number_of_docs($dbonline,$column='filing_no_ia_ma',$ia_filing_no,$scrutiny = 0,$display = 't');
						$party_type = ($ia_party_flag == '1')?'Existing Party':(($ia_party_flag =='2')?'Intervention/Impleadment':''); 
				        $type_of_filing_text = ($type_of_filing == 1)?'MA ('.$party_type.')':(($type_of_filing ==2)?'IA ('.$party_type.')':'');
						$filing_nosend=$main_filing_no.'-'.$hash.'-'.$c_case.'-'.$ia_filing_no.'-'.$ia_id.'-'.$type_of_filing;
						$filing_no_send=base64_encode($filing_nosend); 
					}else{
						continue;
					}
				}else{
					continue;
				}
				$html .=  show_ia_html($ia_filing_no,$display_date,$tr_back_color="#BDFCC9",$count,$number_of_docs,$pet_name,$res_name,$filing_no_send,$c_case,$ia_main_case_no_final,$party_name,$type_of_filing_text,$ia_id);	
			}
		}
		echo $html; die;
	}
	
	function days_gap($db,$dbonline,$schemas,$filing_no){
		$obj_status='NO';
		$st21=$db->prepare("select distinct(entry_dt) from $schemas.objection_details where filing_no=? and status=?");
		$st21->bindParam(1, $filing_no, PDO::PARAM_STR);
		$st21->bindParam(2, $obj_status, PDO::PARAM_STR);
		$st21->execute();
		$entry_date = $st21->fetchColumn();
		$obj_entry_date= htmlspecialchars($entry_date);

		$scr = 0;
		$dis = 1;
		$ss=$dbonline->prepare("select max(document_filed_date) as document_filed_date  from document_upload where filing_no= ?  and scrutiny= ? and display= ? ");
		$ss->bindParam(1, $filing_no, PDO::PARAM_STR);
		$ss->bindParam(2, $scr, PDO::PARAM_STR);
		$ss->bindParam(3, $dis, PDO::PARAM_STR);
		$ss->execute();
		$uploaded_date = $ss->fetchColumn();
		$uploaded_date= htmlspecialchars($uploaded_date);
		$d1=$uploaded_date; //25-11-2-17
		 
		if($d1=='')
		{
			$server_date= date('Y-m-d');
			$d3=$server_date;
			$d2=$obj_entry_date;
			$datetime1 = new DateTime($d2);
			$datetime2 = new DateTime($d3);
			$interval = $datetime1->diff($datetime2);
			$day_interval1= $interval->format('%R%a');
			$day_interval2= 0;
		}
		else
		{
			$d2=$obj_entry_date;
			$datetime1 = new DateTime($d2);
			$datetime2 = new DateTime($d1);
			$interval = $datetime1->diff($datetime2);
			$day_interval2= $interval->format('%R%a');
			$day_interval1= 0;
		 }
		 
		 if($day_interval1 >7 || $day_interval2 > 7) 
		 {
			  $etgt="update  e_case_detail_local set scrutiny_comp3='3' where filing_no = ?";
			  $stgt=$db->prepare($etgt);
			  $stgt->bindParam(1, $filing_no, PDO::PARAM_STR);
			  $stgt->execute();

			  $scmp3 = 3;
			  $disp = 0;
			  $scrt = 1;
			  $etgt="update  e_case_detail set scrutiny_comp3= ?,scrutiny= ?,display= ? where filing_no = ?";
			  $stgt=$dbonline->prepare($etgt);
			  $stgt->bindParam(1, $scmp3, PDO::PARAM_STR);
			  $stgt->bindParam(2, $scrt, PDO::PARAM_STR);
			  $stgt->bindParam(3, $disp, PDO::PARAM_STR);
			  $stgt->bindParam(4, $filing_no, PDO::PARAM_STR);
			  $stgt->execute();

			  $lvl = 3;
			  $etgt1="update  $schemas.scrutiny set level_level= ? where filing_no = ?";
			  $stgt1=$db->prepare($etgt1);
			  $stgt1->bindParam(1, $lvl, PDO::PARAM_STR);
			  $stgt1->bindParam(2, $filing_no, PDO::PARAM_STR);
			  $stgt1->execute();
			  
			  return 'continue';
		 }else{
		  return 'ok';
		 }
	}
	
	function get_party_name($dbonline,$main_filing_no,$ia_filing_no,$party_id,$party_flag){
		if($party_flag == 1){
			$party_name=$dbonline->prepare("select name from e_cases_party where filing_no=? and id=?");
			$party_name->bindParam(1, $main_filing_no, PDO::PARAM_STR);
			$party_name->bindParam(2, $party_id, PDO::PARAM_STR);
			$party_name->execute();
			return $party_name->fetchColumn();
		}else if($party_flag == 2){
			$party_name=$dbonline->prepare("select name from e_cases_party_ia_ma where filing_no_ia_ma=?");
			$party_name->bindParam(1, $ia_filing_no, PDO::PARAM_STR);
			$party_name->execute();
			return $party_name->fetchColumn();
		}
		//return $party_name->fetchColumn();
	}
	
	function get_name($db,$table,$column,$search_column_name,$search_column_value){
		$full_name = $db->prepare("select $column from $table where $search_column_name = ?");
		$full_name->bindParam(1, $search_column_value, PDO::PARAM_STR);
	    $full_name->execute();
        $full_name=$full_name->fetchColumn();
		return $full_name;		 
	}
	
	function main_case_details($db,$schemas,$main_filing_no){
		$case_details=$db->prepare("select case_no,filing_no,case_year,location_code,case_type from $schemas.case_detail where filing_no = ? ");
		$case_details->bindParam(1, $main_filing_no, PDO::PARAM_STR);
		$case_details->execute();
		$case_details = $case_details->fetchAll();
		$case_details = array_shift($case_details);
		return $case_details;
	}
	
	function show_html($filing_no,$tr_back_color,$count,$filing_date,$number_of_docs,$pet_name,$res_name,$total_fees,$filing_no_send,$c_case,$class,$text_rejected,$onlclick){
		$html = '<tr style="background-color: '.$tr_back_color.';">';
		$html .= '<td>'. htmlspecialchars($count).'</td>';
		$html .= '<td>'.$filing_date.'</td>';
		$html .= '<td>'.htmlspecialchars($filing_no).'<br><span style="color:red">(No.of Docs - '.$number_of_docs.')</span></td>';
		$html .= '<td>'.htmlspecialchars_decode(strtoupper($pet_name)).'&nbsp;<font color="blue" size="2"><br> Vs. <br> </font>&nbsp;'.htmlspecialchars_decode(strtoupper($res_name)).'</td>';
		$html .= '<td><div class="sparkbar" data-color="#00a65a" data-height="20">'.$total_fees.'</div></td>';
		$html .= '<td><h3><span '.$onlclick.'class="label '.$class.'"><a style="color: #FFFFFF;" href="./scrutiny/user_scrutiny1.php?ccase='.htmlspecialchars($c_case).'&filing_no_next='.htmlspecialchars($filing_no_send).'">'.$text_rejected.'</a></span></h3></td>';
		$html .= '</tr>';
		return $html;
	}
	
	function show_ia_html($ia_filing_no,$filing_date,$tr_back_color,$count,$number_of_docs,$pet_name,$res_name,$filing_no_send,$c_case,$ia_main_case_no_final,$ia_main_name,$type_of_filing_text,$ia_id){
		$html = '<tr style="background-color: '.$tr_back_color.';">';
		$html .= '<td>'. htmlspecialchars($count).'</td>';
		$html .= '<td>'.$filing_date.'</td>';
		$html .= '<td>'.htmlspecialchars($ia_filing_no).'<br><span style="color:red">(No.of Docs - '.$number_of_docs.')</span></td>';
		$html .= '<td>'.$ia_main_case_no_final.'</td>';
		$html .= '<td>'.htmlspecialchars_decode(strtoupper($pet_name)).'&nbsp;<font color="blue" size="2"><br> Vs. <br> </font>&nbsp;'.htmlspecialchars_decode(strtoupper($res_name)).'</td>';
		$html .= '<td>'.$ia_main_name.'</td>';
		$html .= '<td>'.$type_of_filing_text.'</td>';
		$html .= '<td><h3><span class="label label-success">
                    <a style="color:#ffffff;" href="./scrutiny/ia_scrutiny.php?ccase='. htmlspecialchars($c_case).'&ia_id='.htmlspecialchars($ia_id).'&filing_no_next='.htmlspecialchars($filing_no_send).'">Scrutiny</a>
                    </span></h3></td>';
		$html .= '</tr>';
		return $html;
	}
	
	function get_cases($db,$dbonline,$schemas,$location_access,$user_type,$localadmin,$search_type,$status,$na,$c_case,$data1,$data2){
		if($c_case == 1){
			$query = "select filing_no,dt_of_filing,amount,scrutiny_comp3 from e_case_detail where location_id='$location_access' and payment_accept='$status' and filing_no !='$na' and  scrutiny_comp1 is NULL and scrutiny_comp2 ='1' and (scrutiny_comp3 is NULL or scrutiny_comp3='2')";
			if($search_type == '1'){
			 $where = " and dt_of_filing between '$data1' and '$data2'";
			}
			if($search_type == '2'){
			 $where = " and filing_no = '$data1'";
			}
			$query .= $where;
			$query .= " order by dt_of_filing desc";
		}
		else if($c_case == 3){
			$query = "select filing_no,id,filing_no_ia_ma,party_in_case_flag,
							party_id,type_of_filing,misc_filing_no,filed_date from case_detail_ma_ia 
							where payment_status='TRUE' and ia_scrutiny_level=0 and filing_no_ia_ma IS NOT NULL 
							and filing_no_ia_ma!=''";
			if($search_type == '1'){
			 $where = " and filed_date::timestamp::date between '$data1' and '$data2'";
			}
			if($search_type == '2'){
			 $where = " and filing_no_ia_ma = '$data1'";
			}
			$query .= $where;
			$query .= " order by filed_date desc";
		}  else{
			return '';
		}
		$cases=$dbonline->prepare($query);
		$cases->execute();
		$res = $cases->fetchAll();
		return $res;
	}
	
	function party_details($dbonline,$party_flag,$party_serial,$filing_no){
		$get_party_details=$dbonline->prepare("select name,id from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
		$get_party_details->bindParam(1, $filing_no, PDO::PARAM_STR);
		$get_party_details->bindParam(2, $party_flag, PDO::PARAM_STR);
		$get_party_details->bindParam(3, $party_serial, PDO::PARAM_STR);
		$get_party_details->execute();
		$get_party_details = $get_party_details->fetchAll();
		$get_party_details = array_shift($get_party_details);
		return $get_party_details;
	}
	
	function get_number_of_docs($dbonline,$column_name,$filing_no,$scrutiny,$display){
		$no_of_docs=$dbonline->prepare("select count(*) from document_upload where $column_name=? and scrutiny=? and display=?");
		$no_of_docs->bindParam(1, $filing_no, PDO::PARAM_STR);
		$no_of_docs->bindParam(2, $scrutiny, PDO::PARAM_STR);
		$no_of_docs->bindParam(3, $display, PDO::PARAM_STR);
		$no_of_docs->execute();
		$no_of_docs = $no_of_docs->fetchColumn();
		return $no_of_docs;
	}
	
	function get_fees($dbonline,$filing_no){
		 /*  $total_fees=$dbonline->prepare("select total_fees from e_case_detail_fees where filing_no=? ");
		  $total_fees->bindParam(1, $filing_no, PDO::PARAM_STR);
		  $total_fees->execute();
		  return $total_fees->fetchColumn(); */
		$st2=$dbonline->prepare("select * from e_case_detail_fees where filing_no=? ");
		$st2->bindParam(1, $filing_no, PDO::PARAM_STR);
		$st2->execute();
		$i=0;$r='';
		while ($row2= $st2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
			$E_sec_id=$row2['sec_id'];

			if($E_sec_id > '0')
			{
				$st3=$dbonline->prepare("select * from master_section_act where id=? ");
				$st3->bindParam(1, $E_sec_id, PDO::PARAM_STR);
				$st3->execute();

				while ($row3 = $st3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
				{
					$E_add_sec_id=$row3['section_companies'];
					$r.=$E_add_sec_id.',';


				}
			}
		}
		return rtrim($r,',');
	}
	
	$search_type = $_POST['search_type'];
	$user_type = $_POST['user_type'];
	$selected_case_type = $_POST['selected_case_type'];
	$data_to_search = $_POST['data_to_send'];
	if($search_type == '1'){
		$explode_data = explode('||',$data_to_search);
		$from = $explode_data[0];
		$to = $explode_data[1];
		list($from_day,$from_month,$from_year) = explode("/",$from);
		list($to_day,$to_month,$to_year) = explode("/",$to);
		$from = $from_year."-".$from_month."-".$from_day;
		$to = $to_year."-".$to_month."-".$to_day;
		$status ='Y';
		$na ='NA';
		echo get_data($db,$dbonline,$schemas,$location_access,$user_type,$localadmin,$search_type,$status,$na,$hash,$selected_case_type,$from,$to); die;
	}else if($search_type == '2'){
		$search_filing_no = $data_to_search;
		$status ='Y';
		$na ='NA';
		echo get_data($db,$dbonline,$schemas,$location_access,$user_type,$localadmin,$search_type,$status,$na,$hash,$selected_case_type,$search_filing_no,$extra=''); die;
	}else{
		echo "error"; die;
	}
	
	
	
	
}

?>