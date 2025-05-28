<?php

header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");

      /*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */ 


$server_date= date('d-m-Y'); //Returns IST 
if($server_date !='')
{
	list($day,$month,$year)=explode('-',$server_date);
	$entry_date=$year."-".$month."-".$day;
	
}

$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
$username = $_SESSION['actual_username'];
$location_access = $_SESSION['location'];


setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);


if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{

    function get_child_in_note($db,$schemas,$filing_no){
        $in_registrar = 1;
        $query = "select connected_ias from $schemas.computational_note where filing_no = ? and in_registrar = ?";
		$ins_note =$db->prepare($query);
		$ins_note->bindParam(1, $filing_no, PDO::PARAM_STR);
		$ins_note->bindParam(2, $in_registrar, PDO::PARAM_STR);
		$ins_note->execute();
		$conected_ias = $ins_note->fetchColumn();
        return $conected_ias;

    }

    function check_no_of_revert($db,$schemas,$filing_no){
        $query = "select count(*) as count from $schemas.revert_case_logs where filing_no = ?";
		$ins_note =$db->prepare($query);
		$ins_note->bindParam(1, $filing_no, PDO::PARAM_STR);
		$ins_note->execute();
		$count = $ins_note->fetchColumn();
        return $count;
        
    }

    function save_note_history($db,$schemas,$filing_no){
        $query = "insert into $schemas.computational_note_his (select * from $schemas.computational_note where filing_no = ?)";
		$delete =$db->prepare($query);
		$delete->bindParam(1, $filing_no, PDO::PARAM_STR);
		$res = $delete->execute();
        return $res;
    }

    function delete_note($db,$schemas,$filing_no){
        $query = "delete from $schemas.computational_note where filing_no = ?";
		$delete =$db->prepare($query);
		$delete->bindParam(1, $filing_no, PDO::PARAM_STR);
		$res = $delete->execute();
        return $res;
    }

    function save_impguned_order_history($db,$schemas,$filing_no){
        $query = "insert into $schemas.impugned_order_details_his (select * from $schemas.impugned_order_details where filing_no = ?)";
		$delete =$db->prepare($query);
		$delete->bindParam(1, $filing_no, PDO::PARAM_STR);
		$res = $delete->execute();
        return $res;
    }

    function delete_impguned_order($db,$schemas,$filing_no){
        $query = "delete from $schemas.impugned_order_details where filing_no = ?";
		$delete =$db->prepare($query);
		$delete->bindParam(1, $filing_no, PDO::PARAM_STR);
		$res = $delete->execute();
        return $res;
    }

    function update_document_upload($db,$schemas,$filing_no,$scrutiny,$display,$is_deleted){
        $query = "update document_upload set scrutiny = ? where filing_no = ? and display = ? and is_deleted = ?";
		$update =$db->prepare($query);
		$update->bindParam(1, $scrutiny, PDO::PARAM_STR);
        $update->bindParam(2, $filing_no, PDO::PARAM_STR);
        $update->bindParam(3, $display, PDO::PARAM_BOOL);
        $update->bindParam(4, $is_deleted, PDO::PARAM_BOOL);
		$res = $update->execute();
        return $res;
    }

    function update_e_case_detail($db,$schemas,$filing_no,$scrutiny){
        $query = "update e_case_detail set scrutiny = ?, scrutiny_level = ? where filing_no = ?";
		$update =$db->prepare($query);
		$update->bindParam(1, $scrutiny, PDO::PARAM_STR);
        $update->bindParam(2, $scrutiny, PDO::PARAM_STR);
        $update->bindParam(3, $filing_no, PDO::PARAM_STR);
		$res = $update->execute();
        return $res;
    }

    function save_logs($db,$schemas,$filing_no,$userid,$username,$revert_type){
        $query = "insert into $schemas.revert_case_logs (filing_no,user_id,username,revert_type) values (?,?,?,?)";
        $insert =$db->prepare($query);
		$insert->bindParam(1, $filing_no, PDO::PARAM_STR);
        $insert->bindParam(2, $userid, PDO::PARAM_STR);
        $insert->bindParam(3, $username, PDO::PARAM_STR);
        $insert->bindParam(4, $revert_type, PDO::PARAM_STR);
		$res = $insert->execute();
        return $res;
    }

    function remove_from_case_no_generation($db,$schemas,$filing_no){
        $zero = 0;
        $query = "update $schemas.scrutiny set is_computation_note_approved = ?  where filing_no = ?";
		$update =$db->prepare($query);
		$update->bindParam(1, $zero, PDO::PARAM_STR);
        $update->bindParam(2, $filing_no, PDO::PARAM_STR);
		$res = $update->execute();
        return $res;
    }

    $filing_no = $_POST['filing_no'];
    $action_type = $_POST['action_type'];

    $check_no_of_revert = check_no_of_revert($db,$schemas,$filing_no);

    if($check_no_of_revert > 0){
        $response = array( 
            'status' => 0, 
            'message' => 'Case is alreay reverted once, can not revert again' 
            );
            echo json_encode($response); die;
    }

    if($action_type != 2){
        $get_child_in_note = get_child_in_note($db,$schemas,$filing_no);

        if(!empty($get_child_in_note)){
            $all_ias = explode(',',$get_child_in_note);
        }
    }

    try{	  
		$db->beginTransaction();

        $scrutiny = 0;
        $display = true;
        $is_deleted = false;
        if($action_type == 2){
            $delete_from_case_no_generation = remove_from_case_no_generation($db,$schemas,$filing_no);
            $revert_type = 'CNG';
        }else{
            $revert_type = 'CN';
        } 
       $save_note_history = save_note_history($db,$schemas,$filing_no);
       $delete_note = delete_note($db,$schemas,$filing_no);
       $save_impguned_order_history = save_impguned_order_history($db,$schemas,$filing_no);
       $delete_impguned_order = delete_impguned_order($db,$schemas,$filing_no);
       $update_document_upload = update_document_upload($db,$schemas,$filing_no,$scrutiny,$display,$is_deleted);
       $update_e_case_detail = update_e_case_detail($db,$schemas,$filing_no,$scrutiny);
       $save_logs = save_logs($db,$schemas,$filing_no,$userid,$username,$revert_type);

       if($action_type != 2){
            if(!empty($get_child_in_note)){
                foreach($all_ias as $k=>$fn){
                    $save_note_history = save_note_history($db,$schemas,$fn);
                    $delete_note = delete_note($db,$schemas,$fn);
                    $save_impguned_order_history = save_impguned_order_history($db,$schemas,$fn);
                    $delete_impguned_order = delete_impguned_order($db,$schemas,$fn);
                    $update_document_upload = update_document_upload($db,$schemas,$fn,$scrutiny,$display,$is_deleted);
                    $update_e_case_detail = update_e_case_detail($db,$schemas,$fn,$scrutiny);
                    $save_logs = save_logs($db,$schemas,$fn,$userid,$username,$revert_type);
                }
            }
        }
        $db->commit();
        $response = array( 
            'status' => 1, 
            'message' => 'case reverted to scrutiny' 
            );
            echo json_encode($response); die;
    } catch(Exception $e){
		$db->rollBack();
        print_r($e);
		$response = array( 
		'status' => 0, 
		'message' => 'some error occurred.' 
		);
		echo json_encode($response); die;
	}


}

?>