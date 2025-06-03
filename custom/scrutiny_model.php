<?php

error_reporting(E_ALL);
error_reporting(-1);
ini_set('error_reporting', E_ALL);
/** Url not allow for direct access */
if (!isset($_SERVER['HTTP_REFERER'])) {
    // redirect them to your desired location
    header('location: ../login.php');
    exit;
}

date_default_timezone_set("Asia/Kolkata");
require_once("../db_inc1.php");
if (isset($_POST['method']) && $_POST['method'] != '') {
    $method = $_POST['method'];
    $response = '';

    switch ($method) {
        case "assignToMyself":
            extract($_POST);
            $response = assignCaseFileToScrutinyUser($cis_user_id, $court, $filling_no);
            break;
        case "getScrutinyDelay":
            extract($_POST);
            $response = getScrutinyDelay($court);
            break;
        default:
            break;
    }
    $response = mb_convert_encoding($response, 'UTF-8', 'UTF-8');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    die();
} else {
    header('location: ../login.php');
    exit;
}

function assignCaseFileToScrutinyUser($cis_user_id, $court, $filling_no)
{
    global $db;
    $filling_no = explode("-", base64_decode($filling_no))[0];
    $scrutiny_assign_date = date('Y-m-d H:i:s');
    
    //echo $filling_no;die   
    try {
        $sql = "UPDATE e_case_detail set cis_user_id=?, court=? ,scrutiny_assign_date=? WHERE filing_no=? and filing_no!='NA'";
        $stm = $db->prepare($sql);
      
        $stm->bindParam(1, $cis_user_id, PDO::PARAM_STR);
        $stm->bindParam(2, $court, PDO::PARAM_STR);
        $stm->bindParam(3, $scrutiny_assign_date, PDO::PARAM_STR);
        $stm->bindParam(4, $filling_no, PDO::PARAM_STR);
       
        if ($stm->execute()) {
            $reponse = ["status" => "200", "msg" => 'Assigned Successfully'];
        } else {
            $err = $stm->errorCode();
            $reponse = ["status" => "202", "msg" => $err];
        }
    } catch (PDOException $e) {
        $err = $e->getMessage();
        $reponse = ["status" => "202", "msg" => $err];
    }

    return $reponse;
}
function getScrutinyDelay($court){
    global $db;
    try {
        $sql = "SELECT filing_no,dt_of_filing,case_type,case_title,case_type_nclat,filingnumberia,subjectia,casetypeiacontempt,contempt_case_year,contempt_case_no,cis_user_id,court,
                ct.case_type_desc
                FROM public.e_case_detail c
                LEFT JOIN public.case_type ct ON ct.id=c.case_type
                WHERE c.court=?";
        $stm = $db->prepare($sql);
        $stm->bindParam(1, $court, PDO::PARAM_STR);
        if ($stm->execute()) {
            $reponse = ["status" => "200", "msg" => 'Assigned Successfully'];
        } else {
            $err = $stm->errorCode();
            $reponse = ["status" => "202", "msg" => $err];
        }
    } catch (PDOException $e) {
        $err = $e->getMessage();
        $reponse = ["status" => "202", "msg" => $err];
    }

    return $reponse;
}
