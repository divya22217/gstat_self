<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");
include "../db_inc1.php";
include '../db_inc2.php';
if (empty($_SESSION['user']) and $_SESSION['location'] == '') {
    die("Redirecting to login.php");
}
if (($_SESSION['user']) == '') {
    echo "you Can't access this page";
} else {



    // print_r($_REQUEST);
    // die;
    $curDay = htmlspecialchars(date("d"));
    $curMonth = htmlspecialchars(date("m"));
    $curYear = htmlspecialchars(date("Y"));
    $cur_date = "$curDay/$curMonth/$curYear";
    $curdate = "$curYear-$curMonth-$curDay";
    $regis_date = $_REQUEST['regis_date'];
    $filing_no = $_REQUEST['filing_no'];
    $case_no = $_REQUEST['case_no'];
    $case_year = $_REQUEST['case_year'];
    $court_no = $_REQUEST['court_no'];
	
    $bench_type = $_REQUEST['bench_type'];
    $case_type = $_REQUEST['case_type'];
    $schemas = htmlspecialchars($_SESSION['schema_name']);
    date_default_timezone_set("Asia/Kolkata");
    $server_date = date('d-m-Y'); //Returns IST
    $check_filing_no = "select filing_no,case_no,case_year from $schemas.case_detail where filing_no=?";
    $sql_query = $db->prepare($check_filing_no);
    $sql_query->bindParam(1, $filing_no, PDO::PARAM_STR);
    $sql_query->execute();
    $case_data = $sql_query->fetch();
    if ($case_data['filing_no'] != '') {
        if ($case_data['case_no'] != '' && $case_data['case_year'] != '') {
            echo '<h2 style="color:red;">You have given already case no on this diary no. Please take another dairy no.</h2>';
            die();
        }
        $location_code = " and location_code='$bench_type' and manual_court_no='$court_no'";
        if ($case_year > '2019' && ($bench_type =='1' or $bench_type =='2')) {
            $stqq = $db->prepare("select reg_no from $schemas.case_type_reg where reg_year=? and case_type=?");
            $stqq->bindParam(1, $case_year, PDO::PARAM_STR);
            $stqq->bindParam(2, $case_type, PDO::PARAM_STR);
            $stqq->execute();
            $reg_no = $stqq->fetchColumn();
            if ($case_no >= $reg_no) {
                echo '<h2 style="color:red;"> Your case no is greater than initilize case No</h2>';
                die();
            }
            $location_code = '';
        }
        if ($regis_date != '') {
            list($day, $month, $year) = explode('/', $regis_date);
            $reg_year = $year;
            $regis_date2 = $reg_year . '-' . $month . '-' . $day;
        }
	
        if ($case_year != $reg_year) {
            $msghash2 = "REGISTRATION YEAR AND CASE YEAR SHOULD BE EQUAL";
            echo '<h2 style="color:red;"> REGISTRATION YEAR AND CASE YEAR SHOULD BE EQUAL</h2>';
            die();
        }
        $get_ia_flag = "select ia_flag from $schemas.case_detail where filing_no ='$filing_no'";
        $get_ia_flag = $db->prepare($get_ia_flag);
        $get_ia_flag->execute();
        $ia_flag = $get_ia_flag->fetchColumn();
        if ($ia_flag == 1) {
            $iacasetype = '4';
            $st1 = "select * from $schemas.case_detail where case_no='$case_no'  and case_year='$case_year'  and case_type='$iacasetype'  ";
        } else {
            $st1 = "select * from $schemas.case_detail where case_type='$case_type' and case_no='$case_no'  and case_year='$case_year'  ";
        }
			
        $madate_sql = $db->prepare($st1);
        $madate_sql->execute();
		
	
        if ($madate_sql->rowCount() > 0) {
            echo '<h2 style="color:red;">CASE NO ALREADY EXISTS</h2>';
            die();
        }
        $db->beginTransaction();
        $case_no_update = false;
        $messgae = 'Something Wrong';
        $user_id=$_SESSION['id'];
        $manual_date = date('Y-m-d H:i:s');
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        try {
            // echo "INSERT INTO $schemas.case_manual_update(
            //     filing_no, case_no, case_year, bench_type, case_type, court_no, regis_date, created_by, created_on, ip)
            //     VALUES ('$filing_no','$case_no','$case_year','$bench_type','$case_type','$court_no',
            // '$regis_date2','$user_id','$manual_date','$ipAddress')";

            $insert_manual_data = $db->prepare("INSERT INTO $schemas.case_manual_update(
                filing_no, case_no, case_year, bench_type, case_type, court_no, regis_date, created_by, created_on, ip)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_manual_data->execute(array($filing_no,$case_no,$case_year,$bench_type,$case_type,$court_no,
            $regis_date2,$user_id,$manual_date,$ipAddress));
            $st2 = "update $schemas.case_detail set case_no=?,case_year=?,regis_date=?,location_code=?,manual_court_no=?,manual_user_id=?,manual_date=? where filing_no=?";
            $st2 = $db->prepare($st2);
            $st2->bindParam(1, $case_no, PDO::PARAM_STR);
            $st2->bindParam(2, $case_year, PDO::PARAM_STR);
            $st2->bindParam(3, $regis_date2, PDO::PARAM_STR);
            $st2->bindParam(4, $bench_type, PDO::PARAM_STR);
            $st2->bindParam(5, $court_no, PDO::PARAM_STR);
            $st2->bindParam(6, $user_id, PDO::PARAM_STR);
            $st2->bindParam(7, $manual_date, PDO::PARAM_STR);
            $st2->bindParam(8, $filing_no, PDO::PARAM_STR);
            $st2->execute();
            $msg = "Case is Successfully Submitted!!!";
            $msghash1 = $msg;
            $msghash = base64_encode($msghash1);
            $messgae =  '<h2 style="color:green;">'.$msghash1.'</h2>';
            $case_no_update = true;
        } catch (PDOException $th) {
            $case_no_update = false;
            $messgae =  $th;
        }
        echo $messgae;
        if($case_no_update) { 
            $db->commit();
        } else { 
            $db->rollBack();
        }
       
    } else {
        echo '<h2 style="color:green;">This diary no is not exit on CIS </h2>';
    }

}
