
<?php

date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
session_start();

$_SESSION['user'];
$_SESSION['location'];
$leveladd = $_SESSION['level_level'];
$localadmin = $_SESSION['localadmin'];
$main_id = $_SESSION['main_id'];
$schemas = htmlspecialchars($_SESSION['schema_name']);
$userid = $_SESSION['id'];
date_default_timezone_set("Asia/Kolkata");
$server_date = date('d-m-Y'); //Returns IST 
if ($server_date != '') {
	list($day2, $month2, $year2) = explode('-', $server_date);
	$entry_date = $year2 . "-" . $month2 . "-" . $day2;
}


if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
	echo "Access Problem.....";
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	header("Location: ../login.php?aa=100");
}
if ($main_id == '9999' and $localadmin == '0') {
	/* if($_SESSION['menuaccess_codeall'] !='3')
			{
				session_unset();     // unset $_SESSION variable for the run-time
				session_destroy();
				echo "You Are Not Access This Page......";
				header("Location: ../login.php?aa=100");
				die();
			} */
}

setcookie("PHPSESSID", "", time() - 3600, "", "", TRUE, TRUE);


$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key = $_SESSION['csrf'];

// At the top of the page we check to see whether the user is logged in or not
if (empty($_SESSION['user']) and $_SESSION['location'] == '') {
	die("#2E2E2Eirecting to login.php");
}

if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {

	try {
		$db->beginTransaction();  // begin transaction

		// This code not use next time .......	Schema session create Hear....
		function  check_int($userInput)
		{
			if (filter_var($userInput, FILTER_VALIDATE_INT) !== false) {
				// The input is a valid integer
				$number = intval($userInput);
				return $number;
			} else {
				// Invalid integer input
				echo "Invalid number.";
				die;
			}
		}
		function validateAssociativeArray($array)
		{
			foreach ($array as $key => $value) {
				if (!is_numeric($key)) {
					echo "Invalid key type";
					die;
				}
				if (!is_numeric($value)) {
					echo "Invalid value type";
					die;
				}
			}
			return $array;
		}
		$sessionUserType = htmlspecialchars($_SESSION['id']);
		$curYear = htmlspecialchars(date("Y"));
		$curMonth = htmlspecialchars(date("m"));
		$curDay = htmlspecialchars(date("d"));
		$cur_date = "$curYear-$curMonth-$curDay";
		$cur_date1 = "$curDay/$curMonth/$curYear";

		$link_scrutiny_idaccess = '1';

		$b_id = check_int($_POST['bench_id']);

		//print_r($_REQUEST);die('total');

		$list_date = $_REQUEST['from_list_date'];

		//echo $from_list_date; die;

		$entry_date = htmlspecialchars(date("F j, Y g:i a"));
		$schemas = htmlspecialchars($_SESSION['schema_name']);

		/* list($month,$day,$year)=explode('/',$from_list_date);
$list_date=$year.'-'.$month.'-'.$day; */

		//echo $list_date; die;

		$bench_nature = check_int($_POST['bench_code']);

		$judge = $_REQUEST['judge'];

		$court_no = check_int($_REQUEST['court_no']);

		$presiding1 = $judge[0];
		$bench_no = check_int($_REQUEST['bench_no']);
		for ($i = 0; $i <= count($judge); $i++) {
			if ($presiding == $i) {
				$presiding1 = $judge[$i];
			}
		}

		//$presiding1=9;

		$detail = $_REQUEST['details'];
		$limit_case = check_int($_REQUEST['limit_case']);
		$bench_location = check_int($_REQUEST['bench_location']);
		$bench_remarks = $_REQUEST['bench_remarks'];
		$custom_text = $_REQUEST['custom_text'];
		$judge_count = $_REQUEST['judge_count'];
		$vdo_cnfr_lnk = $_REQUEST['vdo_cnfr_lnk'];
		$meet_pwd = $_REQUEST['meet_pwd'];
		/*$sql_max="select max(bench_no) from $schemas.bench where from_list_date = ? ";

$sql_max = $db->prepare($sql_max);
$sql_max->bindParam(1, $list_date, PDO::PARAM_STR);
$sql_max->execute();
$max_bench = $sql_max->fetchColumn();
if($max_bench=='' || $max_bench=='0')
	{
	$max_bench=1;
	}
	else
	{
		$max_bench=$max_bench+1;
	}*/

		$priority = 0;
		//$presiding=0;
		/* 
if($bench_nature==1)
	{
	$presiding=1;
	}
 */
		//$bench_sql =$db->prepare()
	
		$query = "insert into $schemas.bench_his (bench_nature,
		bench_no,court_no,from_list_date,to_list_date,presiding,entry_date,deal_cd,from_time,to_time,detail,priority,id,location_code,limit_case,list_flag,available_quota,custom_text,vdo_cnfr_lnk,meet_pwd) select bench_nature,
		bench_no,court_no,from_list_date,to_list_date,presiding,entry_date,deal_cd,from_time,to_time,detail,priority,id,location_code,limit_case,list_flag,available_quota,custom_text,vdo_cnfr_lnk,meet_pwd from $schemas.bench where from_list_date = ? and bench_no = ?";
		$insert = $db->prepare($query);
		$insert->bindParam(1, $list_date, PDO::PARAM_STR);
		$insert->bindParam(2, $bench_no, PDO::PARAM_STR);
		$res = $insert->execute();
		if ($res) {

			//$updatebenchsql = $db->prepare("update $schemas.bench set bench_nature=?,court_no=?,presiding=?,entry_date=?,detail=?,limit_case=?,location_code=?,available_quota=? where id=?");
			$updatebenchsql = $db->prepare("update $schemas.bench set bench_nature=?,court_no=?,presiding=?,entry_date=?,detail=?,limit_case=?,location_code=?,available_quota=?,from_list_date=?,to_list_date=?,from_time=?,to_time=?,custom_text=?,vdo_cnfr_lnk=?,meet_pwd=? where id=?");
			$updatebenchsql->bindParam(1, $bench_nature, PDO::PARAM_STR);
			$updatebenchsql->bindParam(2, $court_no, PDO::PARAM_STR);
			$updatebenchsql->bindParam(3, $presiding1, PDO::PARAM_STR);
			$updatebenchsql->bindParam(4, $entry_date, PDO::PARAM_STR);
			$updatebenchsql->bindParam(5, $bench_remarks, PDO::PARAM_STR);
			$updatebenchsql->bindParam(6, $limit_case, PDO::PARAM_STR);
			$updatebenchsql->bindParam(7, $bench_location, PDO::PARAM_STR);
			$updatebenchsql->bindParam(8, $limit_case, PDO::PARAM_STR);
			$updatebenchsql->bindParam(9, $list_date, PDO::PARAM_STR);
			$updatebenchsql->bindParam(10, $list_date, PDO::PARAM_STR);
			$updatebenchsql->bindParam(11, $detail, PDO::PARAM_STR);
			$updatebenchsql->bindParam(12, $detail, PDO::PARAM_STR);
			$updatebenchsql->bindParam(13, $custom_text, PDO::PARAM_STR);
			$updatebenchsql->bindParam(14, $vdo_cnfr_lnk, PDO::PARAM_STR);
			$updatebenchsql->bindParam(15, $meet_pwd, PDO::PARAM_STR);
			$updatebenchsql->bindParam(16, $b_id, PDO::PARAM_STR);
			$updatebenchsql->execute();
		}


		$query = "insert into $schemas.bench_judge_his (select * from $schemas.bench_judge where from_list_date = ? and bench_no = ?)";
		$insert = $db->prepare($query);
		$insert->bindParam(1, $list_date, PDO::PARAM_STR);
		$insert->bindParam(2, $bench_no, PDO::PARAM_STR);
		$res = $insert->execute();
		if ($res) {

			$query = "delete from $schemas.bench_judge where from_list_date = ? and bench_no = ?";
			$delete = $db->prepare($query);
			$delete->bindParam(1, $list_date, PDO::PARAM_STR);
			$delete->bindParam(2, $bench_no, PDO::PARAM_STR);
			$delete_res = $delete->execute();
			if ($delete_res) {
				//print_r(count($judge));die('k');
				for ($i = 0; $i <= count($judge); $i++) {
					$judge_code = $judge[$i];
					if ($judge_code > 0) {
						$judge_code_old1 = $judge_code_old[$i];
						$bench_sql1 = $db->prepare("insert into $schemas.bench_judge (bench_no,judge_code,from_list_date,from_time,to_list_date,to_time,entry_date,deal_cd,bench_nature,court_no) values
(?,?,?,?,?,?,?,?,?,?)");
						$bench_sql1->execute(array(
							$bench_no, $judge_code, $list_date, $detail,
							$list_date, $detail, $entry_date, $sessionUserType, $bench_nature, $court_no
						));
					}
				}
			}
		}
		$purpose_priority = validateAssociativeArray($_REQUEST['purpose_priority']);
		$purpose_code = validateAssociativeArray(($_REQUEST['purpose_code']));
		$len = htmlspecialchars(count($_REQUEST['purpose_code']));

		$query = "insert into $schemas.bench_purpose_priority_his (select * from $schemas.bench_purpose_priority where from_date = ? and bench_no = ?)";
		$insert = $db->prepare($query);
		$insert->bindParam(1, $list_date, PDO::PARAM_STR);
		$insert->bindParam(2, $bench_no, PDO::PARAM_STR);
		$res = $insert->execute();
		if ($res) {

			$query = "delete from $schemas.bench_purpose_priority where from_date = ? and bench_no = ?";
			$delete = $db->prepare($query);
			$delete->bindParam(1, $list_date, PDO::PARAM_STR);
			$delete->bindParam(2, $bench_no, PDO::PARAM_STR);
			$delete_res = $delete->execute();
			if ($delete_res) {
				for ($i = 0; $i < $len; $i++) {

					$purpose = htmlspecialchars($purpose_code[$i]);
					$purpose = htmlspecialchars(addslashes($purpose));

					$purpose_priority1 = htmlspecialchars($purpose_priority[$i]);
					$purpose_priority1 = htmlspecialchars(addslashes($purpose_priority1));


					$bench_sql2 = $db->prepare("insert into $schemas.bench_purpose_priority (from_date,to_date,from_time,to_time,court_no,purpose,priority,deal_cd,entry_date,bench_nature,bench_no) values
(?,?,?,?,?,?,?,?,?,?,?)");


					$bench_sql2->execute(array(
						$list_date, $list_date, $detail, $detail,
						$court_no, $purpose, $purpose_priority1, $sessionUserType, $entry_date, $bench_nature, $bench_no
					));
				}
			}
		}
?>

<?php
		$db->commit();
		$message = 'Bench Modification successfully done...';
		header("Location:./bench_composition_delete.php?msg=$message");
	} catch (Exception $e) {
		echo $e->getMessage(); 
		echo "something went wrong";
		$db->rollBack();
		$message = 'Some error occured';
		die;
		header("Location:./bench_composition_delete.php?msg=$message");
	}
}
?>
