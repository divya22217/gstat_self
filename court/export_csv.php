<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
include("../db_inc1.php");
$filename = "report_".time().".csv";
$fp = fopen('php://output', 'w');
$header = array('Sr.No.','Filing No','Case No','Status');
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

fputcsv($fp, array('','Cases Report','',''));
fputcsv($fp, $header);	
	

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
function generate_case_no($db,$schemas,$main_filing_no){
	$query = "select a.case_no,a.case_year,b.short_name from $schemas.case_detail as a left join case_type as b on b.id = a.case_type where a.filing_no = ?";
	$get_data = $db->prepare($query);
	$get_data->bindParam(1, $main_filing_no, PDO::PARAM_INT);
	$get_data->execute();
	$res = $get_data->fetchAll();
	$res = array_shift($res);
	if(!empty($res)){
	$case_no = "<br/>In<br/>";
	$case_no .= $res['short_name']."/".$res['case_no']."/".$res['case_year'];
	}else{
	$case_no = '';
	}
	return $case_no;
}
 function display_filing_no($filing_no_display){
      $lastFour =  substr($filing_no_display,-4);
      $lastFive = substr($filing_no_display,-9,-4);
      $left = substr($filing_no_display,-16,-9);
      return $dis_fil_no = $left.'/'.$lastFive.'/'.$lastFour;

  }
ini_set('memory_limit','1024M');
ini_set('max_execution_time', 300);  //300 seconds = 5 minutes
$schemas=htmlspecialchars($_SESSION['schema_name']);
$case_type_array = array(35,36,37,38,39);
if (isset($_REQUEST["case_type"])) { $search_case_type  = $_REQUEST["case_type"]; } else { $search_case_type=''; };  
if (isset($_REQUEST["case_no"])) { $search_case_number  = $_REQUEST["case_no"]; } else { $search_case_number=''; };  
if (isset($_REQUEST["case_year"])) { $search_case_year  = $_REQUEST["case_year"]; } else { $search_case_year=''; };
if (isset($_REQUEST["selected_case_type"])) { $backlog_flag  = $_REQUEST["selected_case_type"]; } else { $backlog_flag=1; };   
$query_part = "a.filing_no,a.case_no,a.case_type,a.case_year,a.pet_name,a.res_name,a.dt_of_filing,a.location_code,a.status,a.main_case_ia_no,
						b.next_list_date,b.listing_date";
$order_by = " and a.case_no != '' order by cast(a.case_year as integer),cast(a.case_no as integer)";
if($search_case_number == '' && $search_case_type == '' && $search_case_year == ''){
	$query = "select $query_part from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag $order_by";
}
if($search_case_number == '' && $search_case_type != '' && $search_case_year == ''){
	$query = "select $query_part from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_type= '$search_case_type' $order_by";
}
if($search_case_number == '' && $search_case_type != '' && $search_case_year != ''){
	$query = "select $query_part from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_type= '$search_case_type' AND a.case_year = '$search_case_year' $order_by";
}
if($search_case_number == '' && $search_case_type == '' && $search_case_year != ''){
	$query = "select $query_part from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_year = '$search_case_year'  $order_by";
}
if($search_case_number != '' && $search_case_type == '' && $search_case_year == ''){
	$query = "select $query_part from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_no = '$search_case_number'  $order_by";
}
if($search_case_number != '' && $search_case_type != '' && $search_case_year == ''){
	$query = "select $query_part from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and case_type= '$search_case_type' and a.case_no = '$search_case_number'  $order_by";
}
if($search_case_number != '' && $search_case_type != '' && $search_case_year != ''){
	$query = "select $query_part from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_type= '$search_case_type' AND a.case_year = '$search_case_year' and a.case_no = '$search_case_number'  $order_by";
}
if($search_case_number != '' && $search_case_type == '' && $search_case_year != ''){
	$query = "select $query_part from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_year= '$search_case_year' AND a.case_no = '$search_case_number'  $order_by";
}

	$sql1=$db->prepare($query);
	$sql1->execute();
	$result = $sql1->fetchAll();
	$count = 0;	
	$case_type_array = array(35,36,37,38,39);
foreach($result as $key=>$row1)
	{
	  $filing_no =$row1['filing_no'];
	  $filing_date =$row1['dt_of_filing'];
	  $case_type =$row1['case_type'];
	if(in_array($case_type,$case_type_array)){
		$main_case_no = $row1['main_case_ia_no'];
	}else {
		$main_case_no = $row1['filing_no'];
	}
	  $pet_name =get_party($db,$main_case_no,'P','1');
	  $pet_name=strtoupper($pet_name);
	  $res_name =get_party($db,$main_case_no,'R','1');
	  $res_name=strtoupper($res_name);
	  $location_code = $row1['location_code'];
	  $case_no = $row1['case_no'];
	  $case_year = $row1['case_year'];
	$stqq1234 = $db->prepare("select short_name from case_type where id=?");
	$stqq1234->bindParam(1, $case_type, PDO::PARAM_INT);
	$stqq1234->execute();
	$case_short_name = $stqq1234->fetchColumn();
	$count++; 
	$display_filing_no = display_filing_no($filing_no);
	$case_no_view = "$case_short_name/$case_no/$case_year";
	$listing_date = ($row1['listing_date'] != '')?date('d/m/Y',strtotime($row1['listing_date'])):'';
	$next_list_date = ($row1['next_list_date'] != '')?date('d/m/Y',strtotime($row1['next_list_date'])):'';
	$status = ($row1['status'] == 'P')?'Pending':'Disposed';
	$cause_title = $pet_name.' VS '.$res_name;
	$array = array($count,$display_filing_no,$case_no_view,$status);
	fputcsv($fp, $array);
	} 
	exit;
/* $output = $dompdf->output();
$path = "../casedoc/caveat/$filename"; */

?>