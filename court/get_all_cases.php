<?php

include("../db_inc1.php");
$schemas=htmlspecialchars($_SESSION['schema_name']);
if (isset($_POST["case_type"])) { $search_case_type  = $_POST["case_type"]; } else { $search_case_type=''; };  
if (isset($_POST["case_no"])) { $search_case_number  = $_POST["case_no"]; } else { $search_case_number=''; };  
if (isset($_POST["case_year"])) { $search_case_year  = $_POST["case_year"]; } else { $search_case_year=''; };
if (isset($_POST["selected_case_type"])) { $backlog_flag  = $_POST["selected_case_type"]; } else { $backlog_flag=1; };   
if (!is_numeric($search_case_number) && !empty($search_case_number)) {
	echo "Invalid Input";
	die();
	}
  if (!is_numeric($search_case_year) && !empty($search_case_year)) {
	echo "Invalid Input";
		die();
	}
	if (!is_numeric($search_case_type) && !empty($search_case_type)) {
		echo "Invalid Input";
		die();
	}

if($search_case_number == '' && $search_case_type == '' && $search_case_year == ''){
	$query = "select count(*) as count from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_no != '' ";
}
if($search_case_number == '' && $search_case_type != '' && $search_case_year == ''){
	$query = "select count(*) as count from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_no != ''  and a.case_type= '$search_case_type' ";
}
if($search_case_number == '' && $search_case_type != '' && $search_case_year != ''){
	$query = "select count(*) as count from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_no != ''  and a.case_type= '$search_case_type' AND a.case_year = '$search_case_year' ";
}
if($search_case_number == '' && $search_case_type == '' && $search_case_year != ''){
	$query = "select count(*) as count from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_no != ''  and a.case_year = '$search_case_year' ";
}
if($search_case_number != '' && $search_case_type == '' && $search_case_year == ''){
	$query = "select count(*) as count from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_no != ''  and a.case_no = '$search_case_number' ";
}
if($search_case_number != '' && $search_case_type != '' && $search_case_year == ''){
	$query = "select count(*) as count from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_no != ''  and case_type= '$search_case_type' and a.case_no = '$search_case_number' ";
}
if($search_case_number != '' && $search_case_type != '' && $search_case_year != ''){
	$query = "select count(*) as count from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_no != ''  and a.case_type= '$search_case_type' AND a.case_year = '$search_case_year' and a.case_no = '$search_case_number' ";
}
if($search_case_number != '' && $search_case_type == '' && $search_case_year != ''){
	$query = "select count(*) as count from $schemas.case_detail as a  left join $schemas.case_allocation_temp as b on b.filing_no = a.filing_no
						where a.backlog = $backlog_flag and a.case_no != ''  and a.case_year= '$search_case_year' AND a.case_no = '$search_case_number' ";
}
	
	$sql1=$db->prepare($query);
	$sql1->execute();
	echo $sql1->fetchColumn();
?>