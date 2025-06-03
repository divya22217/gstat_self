<?php
session_start();
ob_start();
include("../db_inc1.php");
$schemas = htmlspecialchars($_SESSION['schema_name']);
date_default_timezone_set("Asia/Kolkata");
$server_date = date('Y-m-d'); //Returns IST 
$next_list_date = $_REQUEST['next_list_date'];
list($d, $m, $Y) = explode('/', $next_list_date);
$list_date = $Y . '-' . $m . '-' . $d;
$listt_date = $_REQUEST['list_date'];
list($d, $m, $Y) = explode('/', $listt_date);
$listt_date = $Y . '-' . $m . '-' . $d;

/*  ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */

$sessionUserType = htmlspecialchars($_SESSION['id']);

$bench_no = $_REQUEST['bench_no'];

$checkbox = $_REQUEST['checkbox'];

$purpose_id = $_REQUEST['purpose_id'];

//$list_flag = $_REQUEST['b_type'];
$sql21 = "select * from $schemas.bench where from_list_date='$listt_date' and bench_no='$bench_no' ";
foreach ($db->query($sql21) as $row21) {
	$b_nature = $row21['bench_nature'];
	$c_no = $row21['court_no'];
	// $id =$row21['id'];
	$location_code = $row21['location_code'];
	$list_flag = $row21['list_flag'];
}

$sql = "select max(priority_serial) as priority_serial from $schemas.case_allocation_temp where listing_date='$listt_date' ";
foreach ($db->query($sql) as $row) {
	$priority_serial1 = $row['priority_serial'];
}

if ($priority_serial1 == '' || $priority_serial1 == 0) {
	$priority_serial1 = 1;
} else {
	$priority_serial1 = $priority_serial1 + 1;
}

$recused_filing_no = '';
$is_found_recused = 0;
$bench_judges = "select string_agg(cast(judge_code as varchar),',') as judge_codes from $schemas.bench_judge where from_list_date = ? and bench_no = ?";
$bench_judges = $db->prepare($bench_judges);
$bench_judges->bindParam(1, $listt_date, PDO::PARAM_STR);
$bench_judges->bindParam(2, $bench_no, PDO::PARAM_STR);
try {
	$bench_judges->execute();
} catch (PDOException $ex) {
	die('invalid query 101');
}
$coram = $bench_judges->fetchColumn();
$coram_array = explode(',', $coram);

$l = sizeof($checkbox);
$total_recused = 0;
try	{
		$db->beginTransaction();
		for ($i = 0; $i < $l; $i++) {
		$filing_no = $checkbox[$i];
		$case_remark = $_POST['case_remark'][$filing_no];

		if ($server_date != '') {

			list($year, $month, $day) = explode('-', $server_date);
			$reg_year_server = $year;
		}
		$regis_date = $year . '-' . $month . '-' . $day;
		$regis_date11 = $day . '/' . $month . '/' . $year;

		$connected = 'N';
		$is_listed = 1;



		$is_deleted = 0;
		$recused_judges = "select judge_code from $schemas.recused_case_judge where filing_no = ? and is_deleted= ?";
		$recused_judges = $db->prepare($recused_judges);
		$recused_judges->bindParam(1, $filing_no, PDO::PARAM_STR);
		$recused_judges->bindParam(2, $is_deleted, PDO::PARAM_STR);
		try {
			$recused_judges->execute();
		} catch (PDOException $ex) {
			die('invalid query 102');
		}
		$recused_judges = $recused_judges->fetchAll();

		if (!empty($recused_judges)) {
			$find_in_recused = 0;
			foreach ($recused_judges as $key => $recuse_judge) {
				if (in_array($recuse_judge['judge_code'], $coram_array)) {
					$is_found_recused = 1;
					$total_recused = $total_recused + 1;
					$find_in_recused = 1;
					if ($recused_filing_no != '') {
						$recused_filing_no .= ',' . $filing_no;
					} else {
						$recused_filing_no .= $filing_no;
					}
					break;
				}
			}
			if ($find_in_recused) {
				continue;
			}
		}

		$l_w_d = 1;
		$list_with_defect_query = "select list_with_defect from $schemas.case_detail where filing_no = ? and list_with_defect = ? and regis_date is null";
		$list_with_defect_data = $db->prepare($list_with_defect_query);
		$list_with_defect_data->bindParam(1, $filing_no, PDO::PARAM_STR);
		$list_with_defect_data->bindParam(2, $l_w_d, PDO::PARAM_STR);
		try {
			$list_with_defect_data->execute();
		} catch (PDOException $ex) {
			die('invalid query 103');
		}
		$list_with_defect_data = $list_with_defect_data->fetchColumn();

		if (!empty($list_with_defect_data)) {
			$list_with_defect = 1;
		} else {
			$list_with_defect = 0;
		}

		

			$insert = $db->prepare("insert into $schemas.case_allocation_temp(filing_no,listing_date,purpose,entry_date,deal_cd,connected,priority_serial,bench_nature,bench_no,
				 court_no,list_flag,listed,remarks,list_with_defect) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$insert->bindParam(1, $filing_no, PDO::PARAM_STR);
			$insert->bindParam(2, $listt_date, PDO::PARAM_STR);
			$insert->bindParam(3, $purpose_id, PDO::PARAM_STR);
			$insert->bindParam(4, $server_date, PDO::PARAM_STR);
			$insert->bindParam(5, $sessionUserType, PDO::PARAM_STR);
			$insert->bindParam(6, $connected, PDO::PARAM_STR);
			$insert->bindParam(7, $priority_serial1, PDO::PARAM_STR);
			$insert->bindParam(8, $b_nature, PDO::PARAM_STR);
			$insert->bindParam(9, $bench_no, PDO::PARAM_STR);
			$insert->bindParam(10, $c_no, PDO::PARAM_STR);
			$insert->bindParam(11, $list_flag, PDO::PARAM_STR);
			$insert->bindParam(12, $is_listed, PDO::PARAM_STR);
			$insert->bindParam(13, $case_remark, PDO::PARAM_STR);
			$insert->bindParam(14, $list_with_defect, PDO::PARAM_STR);
			try {
				$insert->execute();
			} catch (PDOException $ex) {
				die('invalid query 104');
			}

			$priority_serial1++;

			$newst7 = "update $schemas.case_detail set legal_aid ='A' where filing_no='$filing_no' ";

			$db->query($newst7) or die("case no not updated");

			$update_court = "update e_case_detail set court ='A' where filing_no='$filing_no' ";

			$update_court = $db->prepare("update e_case_detail set court = ? where filing_no= ? ");
			$update_court->bindParam(1, $c_no, PDO::PARAM_STR);
			$update_court->bindParam(2, $filing_no, PDO::PARAM_STR);
			$update_court->execute();
		
	}
	$db->commit();
}catch(Exception $e){
		$db->rollBack();
		$msghash = "Something went wrong";
		header("Location:./allocation.php?msghash=$msghash");
	}
if ($total_recused == '0') {
	$msg = "ALL CASES LISTED SUCCESSFULLY";
} else {
	$total_listed = ($l - $total_recused);
	$msg = "$total_listed/$l CASES LISTED SUCCESSFULLY, Following $total_recused Case(s) couldn't be listed due to the Judge(s) in the bench having recused himself from the case:  $recused_filing_no ";
}
$msghash1 = $msg . "@" . $listt_date;
$msghash = base64_encode($msghash1);
header("Location:./allocation.php?msghash=$msghash");
