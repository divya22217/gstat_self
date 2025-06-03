<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include "../db_inc1.php";
if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: ../login.php");
    die();
}
$schemas = $_SESSION['schema_name'];
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {
    if ($_REQUEST['action'] == 'set_cases_priority') {
        $listing_date = $_REQUEST['listing_date'];
        $bench_no = $_REQUEST['bench_no'];
		$court_no = $_REQUEST['court_no'];
		$flag = false;
        if (!empty($_REQUEST['data_arr']) && is_array($_REQUEST['data_arr'])) {
            foreach ($_REQUEST['data_arr'] as $value) {
                $filing_no = $value['filing_no'];
                $purpose = $value['purpose'];
                $priority_serial = $value['priority_set'];
                try {
                    $st1 = $db->prepare("update $schemas.case_allocation_temp set priority_serial = ? where listing_date=? and bench_no = ?
				and court_no =? and purpose = ? and filing_no = ? ");
                    $st1->bindParam(1, $priority_serial, PDO::PARAM_STR);
                    $st1->bindParam(2, $listing_date, PDO::PARAM_STR);
                    $st1->bindParam(3, $bench_no, PDO::PARAM_STR);
                    $st1->bindParam(4, $court_no, PDO::PARAM_STR);
                    $st1->bindParam(5, $purpose, PDO::PARAM_STR);
                    $st1->bindParam(6, $filing_no, PDO::PARAM_STR);
					$st1->execute();
					$flag = true;
                } catch (PDOException $ex) {
					echo $ex;
					$flag = true;
                }
            }
		}
		if($flag){
          echo 'Sucessfully Set Priority. Please check';
		} else { 
		 echo 'something Error';
		}

    }
}
