<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include "../db_inc1.php";

$schemas = htmlspecialchars($_SESSION['schema_name']);

function returnCase($db, $schemas, $filing_no, $cause_no, $status, $comment, $id_check)
{

    $st1 = $db->prepare("select case_type_nclat as case_type from  e_case_detail where filing_no =? ");
    $st1->bindParam(1, $filing_no, PDO::PARAM_STR);
    $st1->execute();
    $case_type_edetail = $st1->fetchColumn();
    $case_type = $case_type_edetail;
    $sessionUserType = htmlspecialchars($_SESSION['id']);
    $cnt_len1 = count($cause_no);
    $scrutiny_corr = '';
    for ($h = 0; $h < $cnt_len1; $h++) {
        $scrutiny_corr = $scrutiny_corr . $cause_no[$h] . ",";
    }
    $scrutiny_corr1 = rtrim($scrutiny_corr, ',');
    $status = explode(",", $status);
    $code1 = $id_check;
    $len = htmlspecialchars(count($id_check));
    for ($i = 0; $i < $len; $i++) {
        $code111 = explode(",", $code1[$i]);
        if ($code111[1] == 'gen') {
            $code11 = $code111[0];
            $aa = '0';
        }
        if ($code111[1] == 'IBC1') {
            $code11 = $code111[0];
            $aa = '1';
        }
        $status1 = htmlspecialchars($status[$i]);
        if ($comment[$i] != '') {
            $comment1 = htmlspecialchars($comment[$i]);
            $comment1 = htmlspecialchars(addslashes($comment1));
        }
        $code11 = htmlspecialchars(addslashes($code11));
        $status1 = htmlspecialchars(addslashes($status1));
        $ll = '2';
        $mis_ref = '0';

        $main_array = array(
            $comment1,
            $sessionUserType,
            $status1,
            $case_type,
            $aa,
            $ll,
            $scrutiny_corr1,
            $filing_no,
            $code11,
            $mis_ref
        );
        $datetime = date('Y-m-d H:i:s');
        $history_array = array(
            $filing_no,
            $comment1,
            $sessionUserType,
            $datetime,
            $status1,
            $case_type,
            $code11,
            $aa,
            $ll,
            $scrutiny_corr1
        );
        try { 

        
        $adddef_sql1 = $db->prepare("update $schemas.objection_details set comment_registrar=?,userid=?,entry_date=now(),status_registrar=?,case_type=?,objection_sub_code=?,level_level=?,scrutiny_correction=?, completion_date = now() where filing_no=? and objection_code=? and miscellaneous_ref_no=? and form_type IS NULL");
        $adddef_sql1->execute($main_array);
        $adddef_sql11 = $db->prepare("insert into $schemas.objection_details_his (filing_no,comment_registrar,userid,entry_date,status_registrar,case_type,objection_code,objection_sub_code,level_level,scrutiny_correction) values(?,?,?,?,?,?,?,?,?,?)");
        $adddef_sql11->execute($history_array);
        } catch(PDOException $ex) { 
         echo $ex;
        }
        $comment1 = "";
        
    }
}

if ($_POST['action'] == 'return_cases_sc') { 
    $filing_no = $_POST['filing_no'];
    $remark_return_cases = $_POST['remark_return_cases'];
    $is_return = 1;
    $cause_no = $_POST['cause_no'];
    $status = $_POST['status'];
    $comment = $_POST['comment'];
    $id_check = $_POST['id_check'];
   
    $boofficefound = 0;
    $rejected_from_scrutiny = 0;
    $defect_docs = null;
    try {
        returnCase($db, $schemas, $filing_no, $cause_no, $status, $comment, $id_check);

        $st1x = $db->prepare("update e_case_detail set remark_return_cases= ?,is_return = ?,scrutiny_level=0,is_defective=0,boofficefound= ?, rejected_from_scrutiny = ?, defect_docs =? where filing_no=? ");
        $st1x->bindParam(1, $remark_return_cases, PDO::PARAM_STR);
        $st1x->bindParam(2, $is_return, PDO::PARAM_STR);
        $st1x->bindParam(3, $boofficefound, PDO::PARAM_STR);
        $st1x->bindParam(4, $rejected_from_scrutiny, PDO::PARAM_STR);
        $st1x->bindParam(5, $defect_docs, PDO::PARAM_STR);
        $st1x->bindParam(6, $filing_no, PDO::PARAM_STR);
        if ($st1x->execute()) {
            $level_level = 2;
            $st1x_scrutiny = $db->prepare("update $schemas.scrutiny set level_level = ? where filing_no=? ");
            $st1x_scrutiny->bindParam(1, $level_level, PDO::PARAM_STR);
            $st1x_scrutiny->bindParam(2, $filing_no, PDO::PARAM_STR);
            if($st1x_scrutiny->execute()) {
                echo 1;
            }

        } 
    } catch (PDOException $ex) {
        echo $ex;
    }
} else if ($_POST['action'] == 'return_cases_timeline') {

    $filing_no = $_POST['filing_no'];
    $message = $_POST['message'];
    $created_by = $_SESSION['id'];
    $created_at = date('Y-m-d H:i:s');
    $created_role = $_SESSION['menuaccess_codeall'];
    $created_ip = '';
    $query = "INSERT INTO public.tbl_timeline_message(
	filing_no, message, created_by, created_at, created_role, created_ip)
	VALUES (?, ?, ?, ?, ?, ?)";
    $ins = $db->prepare($query);
    $ins->bindParam(1, $filing_no, PDO::PARAM_STR);
    $ins->bindParam(2, $message, PDO::PARAM_STR);
    $ins->bindParam(3, $created_by, PDO::PARAM_STR);
    $ins->bindParam(4, $created_at, PDO::PARAM_STR);
    $ins->bindParam(5, $created_role, PDO::PARAM_STR);
    $ins->bindParam(6, $created_ip, PDO::PARAM_STR);
    if ($ins->execute()) {
        echo 1;
    } else {
        echo 0;
    }
}
