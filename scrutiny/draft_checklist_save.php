<?php
include '../db_inc2.php';
include("../db_inc1.php");
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); 
 $schemas=htmlspecialchars($_SESSION['schema_name']);
//for gen
$status = json_decode(stripslashes($_POST['status']));
$comment = json_decode(stripslashes($_POST['comment']));
$cause_no = json_decode(stripslashes($_POST['cause_no']));

//$comment = json_decode(stripslashes($_POST['comment']));

$cause_no_new = json_decode($_REQUEST['cause_no']);
$cause_no_new = array_shift($cause_no_new);
$implode_cause_no = implode(',',$cause_no_new);
$cause_no_count = (count($cause_no_new));

$access_user = $_SESSION['menuaccess_codeall'];

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */

$checklist = json_decode(stripslashes($_POST['checklist']));

//for others
$status_other = json_decode(stripslashes($_POST['statusother']));
$checklist_other = json_decode(stripslashes($_POST['id_checklist_other']));
$comment_other = json_decode(stripslashes($_POST['comment_other']));


$obj_sub_code = json_decode(stripslashes($_POST['obj_sub_code']));


$filing_no = $_POST['filing_no'];
 $scrutiny_level_value = json_decode(stripslashes($_POST['scrutiny_level_value']));


  $miscellaneous_ref_no_post = json_decode(stripslashes($_POST['miscellaneous_ref_no_post']));
 
  
  
  $subdoctype = json_decode(stripslashes($_POST['subdoctype']));


 $ccase = json_decode(stripslashes($_POST['c_case']));

if($ccase!='')
{
	
	$c_case=$ccase;
}
 $ccase = json_decode(stripslashes($_POST['ccase']));
if($ccase!='')
{
	
	$c_case=$ccase;
}


//$filing_no = 98787878787676769;
//$filing_no = sprintf("%d", $filing_no);
//$filing_no=number_format($filing_no,0,'','');
//$filing_no='0'.$filing_no;

$user_id = json_decode(stripslashes($_POST['user_id']));

  $length = sizeof($checklist);
$length_other = sizeof($checklist_other);

try	{
	$db->beginTransaction(); 

for($i=0;$i<$length;$i++)
{
	
 $comment_ins=$comment[$i];
	
   
    $status_ins=$status[$i];
    $checklist_ins=$checklist[$i];
    if($scrutiny_level_value == 0){
    $level_level=0; //sc
    }else if($scrutiny_level_value == 1){
    $level_level=1;
    }
    $display='TRUE';
    $obj_sub_code_for_gen=0;
    
    //check record exist or not
	
	if($c_case!=4)
	{
	
    $check_record_exist = $db->prepare("select filing_no from $schemas.draft_objection_details where filing_no=? and objection_code=? and objection_sub_code=? and user_id=?");
          $check_record_exist->bindParam(1, $filing_no, PDO::PARAM_STR);
          $check_record_exist->bindParam(2, $checklist_ins, PDO::PARAM_STR);
          $check_record_exist->bindParam(3, $obj_sub_code_for_gen, PDO::PARAM_STR);
          $check_record_exist->bindParam(4, $user_id, PDO::PARAM_STR);
          $check_record_exist->execute();
          $filing_no_for_chk= $check_record_exist->fetchColumn();

		  
          if($filing_no_for_chk!=''){
             $update_draft_checklist=$db->prepare("update $schemas.draft_objection_details set status=?,updated=?,display=?,comment=?,scrutiny_correction=? where filing_no=? and objection_code=? and objection_sub_code=? and user_id=?");
            $update_draft_checklist->bindParam(1, $status_ins, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(2, $server_date, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(3, $display, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(4, $comment_ins, PDO::PARAM_STR);
			$update_draft_checklist->bindParam(5, $implode_cause_no, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(6, $filing_no, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(7, $checklist_ins, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(8, $obj_sub_code_for_gen, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(9, $user_id, PDO::PARAM_STR);
            $update_draft_checklist->execute();
          }else{
			  
			

           		 
           $insert_draft_checklist=$db->prepare("insert into $schemas.draft_objection_details(filing_no,user_id,status,objection_code,objection_sub_code,level_level,entry_date,display,comment,scrutiny_correction) values(?,?,?,?,?,?,?,?,?,?)");
		        $insert_draft_checklist->bindParam(1, $filing_no, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(2, $user_id, PDO::PARAM_STR);
		        $insert_draft_checklist->bindParam(3, $status_ins, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(4, $checklist_ins, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(5, $obj_sub_code_for_gen, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(6, $level_level, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(7, $server_date, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(8, $display, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(9, $comment_ins, PDO::PARAM_STR);
			$insert_draft_checklist->bindParam(10, $implode_cause_no, PDO::PARAM_STR);
            $insert_draft_checklist->execute();
}

		    
	}
	if($c_case==4 )
	{
		if($subdoctype=='17')
		{
			$form_type='R';
		}
		if($subdoctype=='33')
		{
			$form_type='O';
		}
		//$form_type='R';
	 $check_record_exist = $db->prepare("select filing_no from $schemas.draft_objection_details where filing_no=? and objection_code=? and objection_sub_code=? and user_id=? and miscellaneous_ref_no=? and form_type=? ");
          $check_record_exist->bindParam(1, $filing_no, PDO::PARAM_STR);
          $check_record_exist->bindParam(2, $checklist_ins, PDO::PARAM_STR);
          $check_record_exist->bindParam(3, $obj_sub_code_for_gen, PDO::PARAM_STR);
          $check_record_exist->bindParam(4, $user_id, PDO::PARAM_STR);
		  $check_record_exist->bindParam(5, $miscellaneous_ref_no_post, PDO::PARAM_STR);
		  $check_record_exist->bindParam(6, $form_type, PDO::PARAM_STR);
          $check_record_exist->execute();
          $filing_no_for_chk= $check_record_exist->fetchColumn();

          if($filing_no_for_chk!=''){
            $update_draft_checklist=$db->prepare("update $schemas.draft_objection_details set status=?,updated=?,display=?,comment=? where filing_no=? and objection_code=? and objection_sub_code=? and user_id=? and miscellaneous_ref_no=? and form_type=?");
            $update_draft_checklist->bindParam(1, $status_ins, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(2, $server_date, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(3, $display, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(4, $comment_ins, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(5, $filing_no, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(6, $checklist_ins, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(7, $obj_sub_code_for_gen, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(8, $user_id, PDO::PARAM_STR);
			  $update_draft_checklist->bindParam(9, $miscellaneous_ref_no_post, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(10, $form_type, PDO::PARAM_STR);
            $update_draft_checklist->execute();
          }else{
			  
			 
           $insert_draft_checklist=$db->prepare("insert into $schemas.draft_objection_details(filing_no,user_id,status,objection_code,objection_sub_code,level_level,entry_date,display,comment,miscellaneous_ref_no,form_type) values(?,?,?,?,?,?,?,?,?,?,?)");
		        $insert_draft_checklist->bindParam(1, $filing_no, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(2, $user_id, PDO::PARAM_STR);
		        $insert_draft_checklist->bindParam(3, $status_ins, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(4, $checklist_ins, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(5, $obj_sub_code_for_gen, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(6, $level_level, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(7, $server_date, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(8, $display, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(9, $comment_ins, PDO::PARAM_STR);
			 $insert_draft_checklist->bindParam(10, $miscellaneous_ref_no_post, PDO::PARAM_STR);
			  $insert_draft_checklist->bindParam(11, $form_type, PDO::PARAM_STR);
            $insert_draft_checklist->execute();	
		
		
	
	}

}

}


if($obj_sub_code==1){
	
  for($i=0;$i<$length_other;$i++)
{
  $comment_other_ins=$comment_other[$i];

    $status_ins=$status_other[$i];
    $checklist_ins=$checklist_other[$i];
    if($scrutiny_level_value == 0){
    $level_level=0; //sc
    }else if($scrutiny_level_value == 1){
    $level_level=1;
    }
    $display='TRUE';
    $obj_sub_code_for_other=1;
    
    //check record exist or not
	if($c_case!=4 )
		{
    $check_record_exist = $db->prepare("select filing_no from $schemas.draft_objection_details where filing_no=? and objection_code=? and objection_sub_code=? and user_id=?");
          $check_record_exist->bindParam(1, $filing_no, PDO::PARAM_STR);
          $check_record_exist->bindParam(2, $checklist_ins, PDO::PARAM_STR);
          $check_record_exist->bindParam(3, $obj_sub_code_for_other, PDO::PARAM_STR);
	        $check_record_exist->bindParam(4, $user_id, PDO::PARAM_STR);
          $check_record_exist->execute();
          $filing_no_for_chk= $check_record_exist->fetchColumn();

        
          if($filing_no_for_chk!=''){
            $update_draft_checklist=$db->prepare("update $schemas.draft_objection_details set status=?,updated=?,display=?,comment=?,scrutiny_correction=? where filing_no=? and objection_code=? and objection_sub_code=? and user_id=?");
            $update_draft_checklist->bindParam(1, $status_ins, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(2, $server_date, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(3, $display, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(4, $comment_other_ins, PDO::PARAM_STR);
			$update_draft_checklist->bindParam(5, $implode_cause_no, PDO::PARAM_STR);
			
            $update_draft_checklist->bindParam(6, $filing_no, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(7, $checklist_ins, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(8, $obj_sub_code_for_other, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(9, $user_id, PDO::PARAM_STR);
            $update_draft_checklist->execute();
          }else{
           $insert_draft_checklist=$db->prepare("insert into $schemas.draft_objection_details(filing_no,user_id,status,objection_code,objection_sub_code,level_level,entry_date,display,comment,scrutiny_correction) values(?,?,?,?,?,?,?,?,?,?)");
		        $insert_draft_checklist->bindParam(1, $filing_no, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(2, $user_id, PDO::PARAM_STR);
		        $insert_draft_checklist->bindParam(3, $status_ins, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(4, $checklist_ins, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(5, $obj_sub_code_for_other, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(6, $level_level, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(7, $server_date, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(8, $display, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(9, $comment_other_ins, PDO::PARAM_STR);
			$insert_draft_checklist->bindParam(10, $implode_cause_no, PDO::PARAM_STR);
            $insert_draft_checklist->execute();
}
}

if($c_case==4 )
		{
			
			if($subdoctype=='17')
		{
			$form_type='R';
		}
		if($subdoctype=='33')
		{
			$form_type='O';
		}
		

		
    $check_record_exist = $db->prepare("select filing_no from $schemas.draft_objection_details where filing_no=? and objection_code=? and objection_sub_code=? and user_id=? and miscellaneous_ref_no=? and form_type=?");
          $check_record_exist->bindParam(1, $filing_no, PDO::PARAM_STR);
          $check_record_exist->bindParam(2, $checklist_ins, PDO::PARAM_STR);
          $check_record_exist->bindParam(3, $obj_sub_code_for_other, PDO::PARAM_STR);
	        $check_record_exist->bindParam(4, $user_id, PDO::PARAM_STR);
			 $check_record_exist->bindParam(5, $miscellaneous_ref_no_post, PDO::PARAM_STR);
			  $check_record_exist->bindParam(6, $form_type, PDO::PARAM_STR);
          $check_record_exist->execute();
           $filing_no_for_chk= $check_record_exist->fetchColumn();


          if($filing_no_for_chk!=''){
            $update_draft_checklist=$db->prepare("update $schemas.draft_objection_details set status=?,updated=?,display=?,comment=? where filing_no=? and objection_code=? and objection_sub_code=? and user_id=? and miscellaneous_ref_no=? and form_type=?");
            $update_draft_checklist->bindParam(1, $status_ins, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(2, $server_date, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(3, $display, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(4, $comment_other_ins, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(5, $filing_no, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(6, $checklist_ins, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(7, $obj_sub_code_for_other, PDO::PARAM_STR);
            $update_draft_checklist->bindParam(8, $user_id, PDO::PARAM_STR);
			 $update_draft_checklist->bindParam(9, $miscellaneous_ref_no_post, PDO::PARAM_STR);
			  $update_draft_checklist->bindParam(10, $form_type, PDO::PARAM_STR);
            $update_draft_checklist->execute();
          }else{
           $insert_draft_checklist=$db->prepare("insert into $schemas.draft_objection_details(filing_no,user_id,status,objection_code,objection_sub_code,level_level,entry_date,display,comment,miscellaneous_ref_no,form_type) values(?,?,?,?,?,?,?,?,?,?,?)");
		        $insert_draft_checklist->bindParam(1, $filing_no, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(2, $user_id, PDO::PARAM_STR);
		        $insert_draft_checklist->bindParam(3, $status_ins, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(4, $checklist_ins, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(5, $obj_sub_code_for_other, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(6, $level_level, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(7, $server_date, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(8, $display, PDO::PARAM_STR);
            $insert_draft_checklist->bindParam(9, $comment_other_ins, PDO::PARAM_STR);
			 $insert_draft_checklist->bindParam(10, $miscellaneous_ref_no_post, PDO::PARAM_STR);
			  $insert_draft_checklist->bindParam(11, $form_type, PDO::PARAM_STR);
            $insert_draft_checklist->execute();
}
}
}



}
$db->commit();
echo "Success! Response Saved As Draft";
die;
}catch(Exception $e){
	$db->rollBack();
  echo $e->getMessage();
	echo "Some error occured";
	die;
}
?>
