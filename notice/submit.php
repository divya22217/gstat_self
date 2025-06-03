<?php
require_once('../includes/helper.php');
deny_direct_access();
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
include("../db_inc2.php");
include("../master/common.php");
if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: ../login.php");
    die();
}
$schema = $_SESSION['schema_name'];

$uploadedFile = '';
if (!empty($_FILES["file_upload"]["type"])) {
    $fileName = 'digital_sign_' . $_FILES['file_upload']['name'];
    $valid_extensions = array("PDF", "pdf");
    $temporary = explode(".", $_FILES["file_upload"]["name"]);
    $file_extension = end($temporary);
    if ((($_FILES["file_upload"]["type"] == "application/pdf")) && in_array($file_extension, $valid_extensions)) {
        $sourcePath = $_FILES['file_upload']['tmp_name'];
        $targetPath = $_SERVER['DOCUMENT_ROOT'] . '/commercialcourttelengana/notice/summon/' . date('Y') . '/' . $fileName;
        if (move_uploaded_file($sourcePath, $targetPath)) {
            $uploadedFile = $fileName;
            $to_party_idto_party_id = array();
            try {
                $query = "select to_party_id from $schema.notice_creation_details where id = '" . $_POST['filing_no'] . "' ";
                $query_prepare = $db->prepare($query);
                $query_prepare->execute();
                $to_party_idto_party_id = $query_prepare->fetchColumn();
            } catch (PDOException $ex) {
                echo $msg = 'Failed to run query' . $ex->getMessage();
            }
            try {
                $query = "update $schema.notice_creation_details set file_name = '" . $fileName . "', digital_sign_status = '1', user_id = '" . $_SESSION['id'] . "' where id = '" . $_POST['filing_no'] . "'";
                $query_insert = $db->prepare($query);
                if ($query_insert->execute() == '1') {

                    $mas_sucess = '';
                    if (!empty($to_party_idto_party_id)) {
                        $party_arra = explode(',', $to_party_idto_party_id);
                        if (!empty($party_arra) && is_array($party_arra)) {
                            foreach ($party_arra as $val_data) {
                                $query = "select filing_no,name,email,mobile from e_cases_party where id = '" . $val_data . "' and party_flag = 'R'";
                                $query_prepare = $dbo->prepare($query);
                                $query_prepare->execute();
                                $datatat = $query_prepare->fetchAll();
                                $mas_sucess .= 'Respondent name : ' . $datatat[0]['name'] . ' ( ' . $datatat[0]['email'] . ' ' . $datatat[0]['mobile'] . ') \n ';
                                $subject = 'Notice / Summon';
                                $message = $subject . ' issued in favour of you. Please check your mail id for details.';
                                $message_mail = 'Please find ' . $subject . ' issued as attatchment in favour of you against G.R. No ' . $datatat[0]['filing_no'];
                                $file_name_latest = $file_name . '.pdf';
                                fn_sms_summon($dbonline, '5', $datatat[0]['name'], $datatat[0]['email'], $datatat[0]['mobile'], $fileName, $datatat[0]['filing_no'], $subject, $message, $message_mail);
                            }
                        }
                    }
                    echo 'Notice / Summon sent to ' . $mas_sucess;
                }
            } catch (PDOException $ex) {
                echo $msg = '>Failed to run query' . $ex->getMessage();
            }
        }
    } else {
        echo 'Please upload only pdf file';
    }
}

?>
