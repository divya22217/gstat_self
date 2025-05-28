<script type="text/javascript" language="javascript">
function DisableBackButton() {
window.history.forward()
}
DisableBackButton();
window.onload = DisableBackButton;
window.onpageshow = function(evt) { if (evt.persisted) DisableBackButton() }
window.onunload = function() { void (0) }
</script>
<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
include '../db_inc2.php';
session_start();

$_SESSION['user'];
$_SESSION['location'];
//$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
$cause_no = $_REQUEST['cause_no'];


 $cnt_len1=count($cause_no);


for($h=0;$h<$cnt_len1;$h++)
{
$scrutiny_corr=$scrutiny_corr.$cause_no[$h].",";
}
if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
echo "Access Problem....."; 
session_unset();     // unset $_SESSION variable for the run-time
session_destroy();
header("Location: ../login.php?aa=100");
die();
}
$form_status = htmlspecialchars($_REQUEST['form_status']);
$report_party_type = htmlspecialchars($_REQUEST['report_party_type']);

setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);

$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key=$_SESSION['csrf'];

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	die("#2E2E2Eirecting to login.php");
}

if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{	
// This code not use next time .......	Schema session create Hear....
	
	
$sessionUserType=htmlspecialchars($_SESSION['id']);
$curYear = htmlspecialchars(date("Y"));
$curMonth = htmlspecialchars(date("m"));
$curDay = htmlspecialchars(date("d"));
$cur_date = "$curYear-$curMonth-$curDay";
$cur_date1 ="$curDay/$curMonth/$curYear";

$link_scrutiny_idaccess='1';

$schemas=htmlspecialchars($_SESSION['schema_name']);
$entry_date = htmlspecialchars(date("F j, Y g:i a"));

$filing_no = htmlspecialchars($_REQUEST['filing_no']);
$ref_no_ia = htmlspecialchars($_REQUEST['ref_no_ia']);
$subdoctype= htmlspecialchars($_REQUEST['subdoctype']);

//$ia_id = htmlspecialchars($_REQUEST['ia_id']);
//$miscellaneous_ref_no_post=htmlspecialchars($_REQUEST['miscellaneous_ref_no_post']);
if($form_status=='I'){
	$ia_id=htmlspecialchars($_REQUEST['ia_id']);
	$ia_flag='TRUE';
}else{
   $ia_id = 0;
   $ia_flag='FALSE';
}

if($form_status=='C' || $form_status=='R'){
	$miscellaneous_ref_no_post=htmlspecialchars($_REQUEST['miscellaneous_ref_no_post']);
	$misc_flag='TRUE';
}else{
   $miscellaneous_ref_no_post = 0;
   $misc_flag='FALSE';
}




/*
$datte = date('Y-m-d h:i a', time());
$st1x =$dbonline->prepare("update e_case_detail set scrutiny_comp4 ='$datte' where filing_no=? ");
$st1x->bindParam(1, $filing_no, PDO::PARAM_STR);
$st1x->execute();
*/

$coulfil=strlen($filing_no);

if($coulfil !='16')
{
	print "Filing No Not Right";
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	header("Location: ../login.php?aa=100");
	die();	
}

$notification_date = $_REQUEST['notification_date'];

list($day,$month,$year)=explode('/',$notification_date);
$notification_date1=$year.'-'.$month.'-'.$day;

$aDate = explode("/", $notification_date);
$rgyear = htmlspecialchars($aDate[2]);

$comment = htmlspecialchars(htmlentities($_REQUEST['comment']));
// $sessionUserType=htmlspecialchars('100');

 $status1=$_REQUEST['test'];
 rtrim($status1,",");
 $status=explode(",",$status1);
 $searchby="";
 for ($i=0;$i<=count($status);$i++)
 {
 	if($status[$i]=='NO')
 	{
 		 $searchby=1;
 	}
 }
 
 if($searchby=="")
 {
 	 $searchby=2;
 }


 if($searchby == '2')   // scrutiny clear
{





    $mis_no_doc =	$miscellaneous_ref_no_post;
	
	
	


    /*-------------------------------------start of condition for fresh cases-------------------------------*/
    /*------------------------------------------------------------------------------------------------------*/

    if($form_status=="F")
    {
        try{
            $dbonline->beginTransaction();
            $db->beginTransaction();

        $ll='1';
		$sc_comp3='1';
$st1x =$dbonline->prepare("update e_case_detail  set scrutiny_comp3 =? where filing_no=? ");
$st1x->bindParam(1, $sc_comp3, PDO::PARAM_STR);
$st1x->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x->execute();
   //update scrutiny     	
$st1=$db->prepare("update $schemas.scrutiny set compliance_date =?,user_id=?,level_level=? where filing_no =? "); 
$st1->bindParam(1, $notification_date1, PDO::PARAM_STR);
$st1->bindParam(2, $sessionUserType, PDO::PARAM_STR);
$st1->bindParam(3, $ll, PDO::PARAM_STR);
$st1->bindParam(4, $filing_no, PDO::PARAM_STR);
$st1->execute();

//get case_type
$st1=$db->prepare("select case_type from  e_case_detail_local where filing_no =? ");
$st1->bindParam(1, $filing_no, PDO::PARAM_STR);
$st1->execute();
$case_type_edetail= $st1->fetchColumn();
if($case_type_edetail=='')
{
$case_type_edetail=0;
}
  $case_type=$case_type_edetail;


//inser data to objection details loop
  list($dd,$mm,$yy) = explode("/", $notification_date);
$noti = htmlspecialchars($yy."-".$mm."-".$dd);

$status1=$_REQUEST['test'];
rtrim($status1,",");
$status=explode(",",$status1);
$comment=$_REQUEST['comment'];
$code1=$_REQUEST['id_check'];

$len=htmlspecialchars(count($_REQUEST['id_check']));

for($i=0;$i<$len;$i++)
{

$code111=explode(",",$code1[$i]);
	
	if($code111[1]=='gen')
	{
			$code11=$code111[0];
			$aa='0';
	}
	if($code111[1]=='IBC1')
	{
			$code11=$code111[0];
			$aa='1';
	}
	
$status1=htmlspecialchars($status[$i]);
$comment1 = htmlspecialchars($comment[$i]);
$code11=htmlspecialchars(addslashes($code11));
$status1=htmlspecialchars(addslashes($status1));
$comment1 = htmlspecialchars(addslashes($comment1));

$adddef_sql = "insert into $schemas.objection_details (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,completed_flag,level_level,ia_id,miscellaneous_ref_no) values
(?,?,?,?,?,?,?,?,?,?,?,?)";
$sthaqq = $db->prepare($adddef_sql);

$ss='1';
$yes='Y';
$ia_id=null;

$sthaqq->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$yes,$ss,$ia_id,$mis_no_doc));

$adddef_sql1 = "insert into $schemas.objection_details_his (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,completed_flag,level_level,ia_id,miscellaneous_ref_no) values
(?,?,?,?,?,?,?,?,?,?,?,?)";
$sthaqq1 = $db->prepare($adddef_sql1);

$llp='1';
$yes='Y';

$sthaqq1->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$yes,$llp,$ia_id,$mis_no_doc));
}

//update scrutiny
$st21s =$db->prepare("update $schemas.scrutiny set objection_status=?,notification_date=?,defects=? where filing_no=? ");
$obj_st='N';
$def='N';
$st21s->bindParam(1, $obj_st, PDO::PARAM_STR);
$st21s->bindParam(2, $notification_date1, PDO::PARAM_STR);
$st21s->bindParam(3, $def, PDO::PARAM_STR);
$st21s->bindParam(4, $filing_no, PDO::PARAM_STR);
$st21s->execute();

$sccc='1';
$st1x =$db->prepare("update $schemas.scrutiny set scrutinu_comp = ? where filing_no=? ");
$st1x->bindParam(1, $sccc, PDO::PARAM_STR);
$st1x->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x->execute();

$ll='1';
$obj_st='N';
$def='N';
$ia_flag='FALSE';

//insert scrutiny_his
$st13=$db->prepare("insert into  $schemas.scrutiny_his (filing_no,defects,notification_date,user_id,level_level)
		values (?,?,?,?,?) ");
$st13->bindParam(1, $filing_no, PDO::PARAM_STR);
$st13->bindParam(2, $def, PDO::PARAM_STR);
$st13->bindParam(3, $notification_date1, PDO::PARAM_STR);
$st13->bindParam(4, $sessionUserType, PDO::PARAM_STR);
$st13->bindParam(5, $ll, PDO::PARAM_STR);
$st13->execute();

$scrutiny_dc='0';
$scr_display='1';
$doc_level='1';

//update document_upload 
$st1x111 =$dbonline->prepare("update document_upload set doc_level=? where  filing_no=? and scrutiny=? and display=? and miscellaneous_ref_no IS NULL and party_type NOT IN (select party_flag from e_master_govt_body)");

$st1x111->bindParam(1, $doc_level, PDO::PARAM_STR);
//$st1x111->bindParam(1, $scrutiny_dc, PDO::PARAM_STR);
//$st1x111->bindParam(2, $scr_display, PDO::PARAM_STR);
$st1x111->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x111->bindParam(3, $scrutiny_dc, PDO::PARAM_STR);
$st1x111->bindParam(4, $scr_display, PDO::PARAM_STR);
$st1x111->execute();

   /*------------------------------start of common code--------------------------------------------------*/

    //code for updating draft values
    $status_ins = 'CM0';    //scrutiny done by sc
    $display = 'FALSE';
    $level_level = 0;
    $update_draft_display=$db->prepare("update $schemas.draft_objection_details set status=?,updated=?,display=? where filing_no=? and level_level=? and miscellaneous_ref_no IS NULL");
                $update_draft_display->bindParam(1, $status_ins, PDO::PARAM_STR);
                $update_draft_display->bindParam(2, $server_date, PDO::PARAM_STR);
                $update_draft_display->bindParam(3, $display, PDO::PARAM_STR);
                $update_draft_display->bindParam(4, $filing_no, PDO::PARAM_STR);
                $update_draft_display->bindParam(5, $level_level, PDO::PARAM_STR);
                $update_draft_display->execute();
                
                $dbonline->commit();
                $db->commit();
    
    echo $message = 'Scrutiny Done Successfully and Forwarded Without Defect To Asst./Dy. Registrar';
    $msg=base64_encode($filing_no);
    $msg1=htmlspecialchars($message.'-'.$msg);
    $hash=base64_encode($msg1);
    
    $msg3=base64_encode($form_status);
    
    //$hash2=base64_encode($msg3);
    
    header("Location:../index.php?hash=$hash&hash2=$msg3");
    unset($_SESSION['form2_scruniny']);
    
    die();
    /*------------------------------------end of common code-------------------------------------------------*/
} catch (PDOException $e) {
    $dbonline->rollBack();
    $db->rollBack();
    echo $e->getMessage();
        //throw $e;
    
        echo $message = 'There is some problem while doing scrutiny...';
        $msg=base64_encode($filing_no);
        $msg1=htmlspecialchars($message.'-'.$msg);
        $hash=base64_encode($msg1);
        
        $msg3=base64_encode($form_status);
        
        //$hash2=base64_encode($msg3);
        
        header("Location:../index.php?hash=$hash&hash2=$msg3");
        unset($_SESSION['form2_scruniny']);
        die();

}
    }

    /*-------------------------------------end of condition for fresh cases-------------------------------*/
    /*----------------------------------------------------------------------------------------------------*/


    /*-------------------------------------start of condition for doc cases-------------------------------*/
    /*----------------------------------------------------------------------------------------------------*/


    if($form_status=="C")
    {
        try{
            $dbonline->beginTransaction();
            $db->beginTransaction();

        $ll='11';
		


        //get case_type
        $st1=$dbonline->prepare("select case_type from  e_case_detail where filing_no =? ");
        $st1->bindParam(1, $filing_no, PDO::PARAM_STR);
        $st1->execute();
        $case_type_edetail= $st1->fetchColumn();
        if($case_type_edetail=='')
        {
         $case_type_edetail=0;
        }
         $case_type=$case_type_edetail;

//insert into objection details
list($dd,$mm,$yy) = explode("/", $notification_date);
$noti = htmlspecialchars($yy."-".$mm."-".$dd);

$status1=$_REQUEST['test'];
rtrim($status1,",");
$status=explode(",",$status1);
$comment=$_REQUEST['comment'];
$code1=$_REQUEST['id_check'];

$len=htmlspecialchars(count($_REQUEST['id_check']));

for($i=0;$i<$len;$i++)
{

$code111=explode(",",$code1[$i]);
	
	if($code111[1]=='gen')
	{
			$code11=$code111[0];
			$aa='0';
	}
	if($code111[1]=='IBC1')
	{
			$code11=$code111[0];
			$aa='1';
	}
	
$status1=htmlspecialchars($status[$i]);
$comment1 = htmlspecialchars($comment[$i]);

$code11=htmlspecialchars(addslashes($code11));
$status1=htmlspecialchars(addslashes($status1));
$comment1 = htmlspecialchars(addslashes($comment1));





$adddef_sql = "insert into $schemas.objection_details (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,completed_flag,level_level,ia_id,miscellaneous_ref_no) values
(?,?,?,?,?,?,?,?,?,?,?,?)";
$sthaqq = $db->prepare($adddef_sql);

$ss='11';
$yes='Y';

$sthaqq->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$yes,$ss,$ia_id,$mis_no_doc));


$adddef_sql1 = "insert into $schemas.objection_details_his (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,completed_flag,level_level,ia_id,miscellaneous_ref_no) values
(?,?,?,?,?,?,?,?,?,?,?,?)";
$sthaqq1 = $db->prepare($adddef_sql1);

$llp='11';
$yes='Y';

$sthaqq1->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$yes,$llp,$ia_id,$mis_no_doc));
}

$ll='11';//unused
$obj_st='N';//unused
$def='N';//unused

$scrutiny_dc='0';
$scr_display='1';
$doc_level='11';
//update document_upload


	$st1x111 =$dbonline->prepare("update document_upload set doc_level=? where  filing_no=? and scrutiny=? and display=? and miscellaneous_ref_no=? and  party_type NOT IN (select party_flag from e_master_govt_body)");

$st1x111->bindParam(1, $doc_level, PDO::PARAM_STR);
//$st1x111->bindParam(2, $scr_display, PDO::PARAM_STR);
$st1x111->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x111->bindParam(3, $scrutiny_dc, PDO::PARAM_STR);
$st1x111->bindParam(4, $scr_display, PDO::PARAM_STR);
$st1x111->bindParam(5, $mis_no_doc, PDO::PARAM_STR);
$st1x111->execute();

$obj_status='N';
$doc_level='11';

//insert into scrutiny_doc
$st13=$db->prepare("insert into  $schemas.scrutiny_doc (filing_no,notification_date,user_id,objection_status,defects,level_level,miscellaneous_ref_no)
		values (?,?,?,?,?,?,?) ");
$st13->bindParam(1, $filing_no, PDO::PARAM_STR);
$st13->bindParam(2, $notification_date1, PDO::PARAM_STR);
$st13->bindParam(3, $sessionUserType, PDO::PARAM_STR);
$st13->bindParam(4, $obj_status, PDO::PARAM_STR);
$st13->bindParam(5, $obj_status, PDO::PARAM_STR);
$st13->bindParam(6, $doc_level, PDO::PARAM_STR);
$st13->bindParam(7, $mis_no_doc, PDO::PARAM_STR);
$st13->execute();

/*--------------------------start of common code for draft---------------------------------------------*/

//code for updating draft values
$status_ins = 'CM0';    //scrutiny done by sc
$display = 'FALSE';
$level_level = 0;
$update_draft_display=$db->prepare("update $schemas.draft_objection_details set status=?,updated=?,display=? where filing_no=? and level_level=? and miscellaneous_ref_no=?");
            $update_draft_display->bindParam(1, $status_ins, PDO::PARAM_STR);
            $update_draft_display->bindParam(2, $server_date, PDO::PARAM_STR);
            $update_draft_display->bindParam(3, $display, PDO::PARAM_STR);
            $update_draft_display->bindParam(4, $filing_no, PDO::PARAM_STR);
            $update_draft_display->bindParam(5, $level_level, PDO::PARAM_STR);
			 $update_draft_display->bindParam(6, $mis_no_doc, PDO::PARAM_STR);
            $update_draft_display->execute();

            $dbonline->commit();
            $db->commit();


echo $message = 'Scrutiny Done Successfully and Forwarded Without Defect To Asst./Dy. Registrar';
$msg=base64_encode($filing_no);
$msg1=htmlspecialchars($message.'-'.$msg);
$hash=base64_encode($msg1);

$msg3=base64_encode($form_status);

//$hash2=base64_encode($msg3);

header("Location:../index.php?hash=$hash&hash2=$msg3");
unset($_SESSION['form2_scruniny']);

die();

} catch (PDOException $e) {
    $dbonline->rollBack();
    $db->rollBack();
    echo $e->getMessage();
        //throw $e;

        echo $message = 'There is some problem while doing scrutiny...';
        $msg=base64_encode($filing_no);
        $msg1=htmlspecialchars($message.'-'.$msg);
        $hash=base64_encode($msg1);
        
        $msg3=base64_encode($form_status);
        
        //$hash2=base64_encode($msg3);
        
        header("Location:../index.php?hash=$hash&hash2=$msg3");
        unset($_SESSION['form2_scruniny']);
        
        die();
}   

/*--------------------------end of common code for draft---------------------------------------------*/

        }
    /*-------------------------------------end of condition for doc cases-------------------------------*/
    /*----------------------------------------------------------------------------------------------------*/


	
	
	
	  if($form_status=="R")
    {
        try{
            $dbonline->beginTransaction();
            $db->beginTransaction();

        $ll='4';
		//$form_type='R';
if($subdoctype=='17')
{
	$form_type='R';
}
if($subdoctype=='33')
{
	$form_type='O';
}

        //get case_type
        $st1=$dbonline->prepare("select case_type from  e_case_detail where filing_no =? ");
        $st1->bindParam(1, $filing_no, PDO::PARAM_STR);
        $st1->execute();
        $case_type_edetail= $st1->fetchColumn();
        if($case_type_edetail=='')
        {
         $case_type_edetail=0;
        }
         $case_type=$case_type_edetail;

//insert into objection details
list($dd,$mm,$yy) = explode("/", $notification_date);
$noti = htmlspecialchars($yy."-".$mm."-".$dd);

$status1=$_REQUEST['test'];
rtrim($status1,",");
$status=explode(",",$status1);
$comment=$_REQUEST['comment'];
$code1=$_REQUEST['id_check'];

$len=htmlspecialchars(count($_REQUEST['id_check']));

for($i=0;$i<$len;$i++)
{

$code111=explode(",",$code1[$i]);
	
	if($code111[1]=='gen')
	{
			$code11=$code111[0];
			$aa='0';
	}
	if($code111[1]=='IBC1')
	{
			$code11=$code111[0];
			$aa='1';
	}
	
$status1=htmlspecialchars($status[$i]);
$comment1 = htmlspecialchars($comment[$i]);

$code11=htmlspecialchars(addslashes($code11));
$status1=htmlspecialchars(addslashes($status1));
$comment1 = htmlspecialchars(addslashes($comment1));





$adddef_sql = "insert into $schemas.objection_details (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,completed_flag,level_level,ia_id,miscellaneous_ref_no,form_type) values
(?,?,?,?,?,?,?,?,?,?,?,?,?)";
$sthaqq = $db->prepare($adddef_sql);

$ss='4';
$yes='Y';

$sthaqq->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$yes,$ss,$ia_id,$mis_no_doc,$form_type));


$adddef_sql1 = "insert into $schemas.objection_details_his (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,completed_flag,level_level,ia_id,miscellaneous_ref_no,form_type) values
(?,?,?,?,?,?,?,?,?,?,?,?,?)";
$sthaqq1 = $db->prepare($adddef_sql1);

$llp='4';
$yes='Y';

$sthaqq1->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$yes,$llp,$ia_id,$mis_no_doc,$form_type));
}

$ll='4';//unused
$obj_st='N';//unused
$def='N';//unused

$scrutiny_dc='0';
$scr_display='1';
$doc_level='4';
//update document_upload
	$st1x111 =$dbonline->prepare("update document_upload set doc_level=? where  filing_no=? and scrutiny=? and display=? and miscellaneous_ref_no=? and subdoctype=? and party_type IN (select party_flag from e_master_govt_body)");

$st1x111->bindParam(1, $doc_level, PDO::PARAM_STR);
//$st1x111->bindParam(2, $scr_display, PDO::PARAM_STR);
$st1x111->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x111->bindParam(3, $scrutiny_dc, PDO::PARAM_STR);
$st1x111->bindParam(4, $scr_display, PDO::PARAM_STR);
$st1x111->bindParam(5, $mis_no_doc, PDO::PARAM_STR);
$st1x111->bindParam(6, $subdoctype, PDO::PARAM_STR);


$st1x111->execute();

$obj_status='N';
$doc_level='4';
//$form_type='R';
//insert into scrutiny_doc
$st13=$db->prepare("insert into  $schemas.scrutiny_doc (filing_no,notification_date,user_id,objection_status,defects,level_level,miscellaneous_ref_no,form_type)
		values (?,?,?,?,?,?,?,?) ");
$st13->bindParam(1, $filing_no, PDO::PARAM_STR);
$st13->bindParam(2, $notification_date1, PDO::PARAM_STR);
$st13->bindParam(3, $sessionUserType, PDO::PARAM_STR);
$st13->bindParam(4, $obj_status, PDO::PARAM_STR);
$st13->bindParam(5, $obj_status, PDO::PARAM_STR);
$st13->bindParam(6, $doc_level, PDO::PARAM_STR);
$st13->bindParam(7, $mis_no_doc, PDO::PARAM_STR);
$st13->bindParam(8, $form_type, PDO::PARAM_STR);
$st13->execute();

/*--------------------------start of common code for draft---------------------------------------------*/

//code for updating draft values
$status_ins = 'CM0';    //scrutiny done by sc
$display = 'FALSE';
$level_level = 0;
$update_draft_display=$db->prepare("update $schemas.draft_objection_details set status=?,updated=?,display=? where filing_no=? and level_level=? and miscellaneous_ref_no=? and form_type=?");
            $update_draft_display->bindParam(1, $status_ins, PDO::PARAM_STR);
            $update_draft_display->bindParam(2, $server_date, PDO::PARAM_STR);
            $update_draft_display->bindParam(3, $display, PDO::PARAM_STR);
            $update_draft_display->bindParam(4, $filing_no, PDO::PARAM_STR);
            $update_draft_display->bindParam(5, $level_level, PDO::PARAM_STR);
			$update_draft_display->bindParam(6, $miscellaneous_ref_no_post, PDO::PARAM_STR);
			$update_draft_display->bindParam(7, $form_type, PDO::PARAM_STR);
            $update_draft_display->execute();

            $dbonline->commit();
            $db->commit();


echo $message = 'Scrutiny Done Successfully and Forwarded Without Defect To Asst./Dy. Registrar';
$msg=base64_encode($filing_no);
$msg1=htmlspecialchars($message.'-'.$msg);
$hash=base64_encode($msg1);

$msg3=base64_encode($form_status);

//$hash2=base64_encode($msg3);

header("Location:../index.php?hash=$hash&hash2=$msg3");
unset($_SESSION['form2_scruniny']);

die();

} catch (PDOException $e) {
    $dbonline->rollBack();
    $db->rollBack();
    echo $e->getMessage();
        //throw $e;

        echo $message = 'There is some problem while doing scrutiny...';
        $msg=base64_encode($filing_no);
        $msg1=htmlspecialchars($message.'-'.$msg);
        $hash=base64_encode($msg1);
        
        $msg3=base64_encode($form_status);
        
        //$hash2=base64_encode($msg3);
        
        header("Location:../index.php?hash=$hash&hash2=$msg3");
        unset($_SESSION['form2_scruniny']);
        
        die();
}   

/*--------------------------end of common code for draft---------------------------------------------*/

        }
    /*-------------------------------------end of condition for doc cases-------------------------------*/
    /*----------------------------------------------------------------------------------------------------*/

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

     /*-------------------------------------start of condition for ia-------------------------------*/
    /*----------------------------------------------------------------------------------------------------*/

    if($form_status=="I")
    {

        try{
            $dbonline->beginTransaction();
            $db->beginTransaction();
        
            $ll='111';

        $obj_status='N';
$doc_level='111';
$scrutiny ='1';
//echo "ii";




$get_scrutiny_val=$db->prepare("select level_level from $schemas.scrutiny_ia where  filing_no=? and ia_id=? ");
	$get_scrutiny_val->bindParam(1, $filing_no, PDO::PARAM_STR);
	$get_scrutiny_val->bindParam(2, $ia_id, PDO::PARAM_STR);
	$get_scrutiny_val->execute();
	$scrutiny_cheker= $get_scrutiny_val->fetchColumn();

    //update or insert into scrutiny_ia
if($scrutiny_cheker == '222'){	
//echo "i";
		$st13=$db->prepare("update $schemas.scrutiny_ia set notification_date=?,user_id=?,objection_status=?,defects=?,level_level=? where
	filing_no=? and ia_id=?");
$st13->bindParam(1, $notification_date1, PDO::PARAM_STR);
$st13->bindParam(2, $sessionUserType, PDO::PARAM_STR);
$st13->bindParam(3, $obj_status, PDO::PARAM_STR);
$st13->bindParam(4, $obj_status, PDO::PARAM_STR);
$st13->bindParam(5, $doc_level, PDO::PARAM_STR);
$st13->bindParam(6, $filing_no, PDO::PARAM_STR);
$st13->bindParam(7, $ia_id, PDO::PARAM_STR);
$st13->execute();
}else{
	$st13=$db->prepare("insert into  $schemas.scrutiny_ia (filing_no,notification_date,user_id,objection_status,defects,level_level,ia_id)
		values (?,?,?,?,?,?,?) ");
$st13->bindParam(1, $filing_no, PDO::PARAM_STR);
$st13->bindParam(2, $notification_date1, PDO::PARAM_STR);
$st13->bindParam(3, $sessionUserType, PDO::PARAM_STR);
$st13->bindParam(4, $obj_status, PDO::PARAM_STR);
$st13->bindParam(5, $obj_status, PDO::PARAM_STR);
$st13->bindParam(6, $doc_level, PDO::PARAM_STR);
$st13->bindParam(7, $ia_id, PDO::PARAM_STR);
$st13->execute();
}
//set scrutiny done i.e, scrutiny=1 in e_ia_details

$update_ia_details =$dbonline->prepare("update e_ia_details set scrutiny=? where  filing_no=? and ia_id=? ");
$update_ia_details->bindParam(1, $scrutiny, PDO::PARAM_STR);
$update_ia_details->bindParam(2, $filing_no, PDO::PARAM_STR);
$update_ia_details->bindParam(3, $ia_id, PDO::PARAM_STR);
$update_ia_details->execute();
    
//get case_type	
$st1=$db->prepare("select case_type from  e_case_detail_local where filing_no =? ");
$st1->bindParam(1, $filing_no, PDO::PARAM_STR);
$st1->execute();
$case_type_edetail= $st1->fetchColumn();
if($case_type_edetail=='')
{
$case_type_edetail=0;
}
$case_type=$case_type_edetail;

//insert into objection_details
list($dd,$mm,$yy) = explode("/", $notification_date);
$noti = htmlspecialchars($yy."-".$mm."-".$dd);

$status1=$_REQUEST['test'];
rtrim($status1,",");
$status=explode(",",$status1);
$comment=$_REQUEST['comment'];
$code1=$_REQUEST['id_check'];

$len=htmlspecialchars(count($_REQUEST['id_check']));

for($i=0;$i<$len;$i++)
{
$code111=explode(",",$code1[$i]);
	
	if($code111[1]=='gen')
	{
			$code11=$code111[0];
			$aa='0';
	}
	if($code111[1]=='IBC1')
	{
			$code11=$code111[0];
			$aa='1';
	}
	
$status1=htmlspecialchars($status[$i]);
$comment1 = htmlspecialchars($comment[$i]);

$code11=htmlspecialchars(addslashes($code11));
$status1=htmlspecialchars(addslashes($status1));
$comment1 = htmlspecialchars(addslashes($comment1));





$adddef_sql = "insert into $schemas.objection_details (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,completed_flag,level_level,ia_id,miscellaneous_ref_no) values
(?,?,?,?,?,?,?,?,?,?,?,?)";
$sthaqq = $db->prepare($adddef_sql);

//$mis_no_doc = NULL;
if($form_status=="F")
{
	$ss='1';
$yes='Y';
}
if($form_status=="C")
{
	$ss='11';
$yes='Y';
}
if($form_status=="I")
{
	$ss='111';
$yes='Y';
$ia_flag = 'TRUE';
}else{
$ia_flag = 'FALSE';	
}

//var_dump($ia_id);

if($ia_id=='')
{
	$ia_id=0;
}
$sthaqq->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$yes,$ss,$ia_id,$mis_no_doc));



$adddef_sql1 = "insert into $schemas.objection_details_his (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,completed_flag,level_level,ia_id,miscellaneous_ref_no) values
(?,?,?,?,?,?,?,?,?,?,?,?)";
$sthaqq1 = $db->prepare($adddef_sql1);

if($form_status=="F")
{
	$llp='1';
$yes='Y';
}
if($form_status=="C")
{
	$llp='11';
$yes='Y';
}
if($form_status=="I")
{
	$llp='111';
$yes='Y';
}
if($ia_id==''){
	$ia_id=0;
}

$sthaqq1->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$yes,$llp,$ia_id,$mis_no_doc));
}


//update document upload
/*$scrutiny_dc='0';
$scr_display='1';
$doc_level='111';

$st1x111 =$dbonline->prepare("update document_upload set doc_level=? where  filing_no=? and scrutiny=? and display=? and  party_type NOT IN (select party_flag from e_master_govt_body) ");
$st1x111->bindParam(1, $doc_level, PDO::PARAM_STR);
//$st1x111->bindParam(1, $scrutiny_dc, PDO::PARAM_STR);
//$st1x111->bindParam(2, $scr_display, PDO::PARAM_STR);
$st1x111->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x111->bindParam(3, $scrutiny_dc, PDO::PARAM_STR);
$st1x111->bindParam(4, $scr_display, PDO::PARAM_STR);
$st1x111->execute();*/


$ll='111';//unused
$obj_st='N';//unused
$def='N';//unused

/*--------------------------start of common code for draft---------------------------------------------*/

//code for updating draft values
$status_ins = 'CM0';    //scrutiny done by sc
$display = 'FALSE';
$level_level = 0;
$update_draft_display=$db->prepare("update $schemas.draft_objection_details set status=?,updated=?,display=? where filing_no=? and level_level=? and form_type IS NULL");
            $update_draft_display->bindParam(1, $status_ins, PDO::PARAM_STR);
            $update_draft_display->bindParam(2, $server_date, PDO::PARAM_STR);
            $update_draft_display->bindParam(3, $display, PDO::PARAM_STR);
            $update_draft_display->bindParam(4, $filing_no, PDO::PARAM_STR);
            $update_draft_display->bindParam(5, $level_level, PDO::PARAM_STR);
            $update_draft_display->execute();

            $dbonline->commit();
            $db->commit();

echo $message = 'Scrutiny Done Successfully and Forwarded Without Defect To Asst./Dy. Registrar';
$msg=base64_encode($filing_no);
$msg1=htmlspecialchars($message.'-'.$msg);
$hash=base64_encode($msg1);

$msg3=base64_encode($form_status);

//$hash2=base64_encode($msg3);

header("Location:../index.php?hash=$hash&hash2=$msg3");
unset($_SESSION['form2_scruniny']);

die();
} catch (PDOException $e) {
    $dbonline->rollBack();
    $db->rollBack();
    echo $e->getMessage();
        //throw $e;
        echo $message = 'There is some problem while doing scrutiny...';
$msg=base64_encode($filing_no);
$msg1=htmlspecialchars($message.'-'.$msg);
$hash=base64_encode($msg1);

$msg3=base64_encode($form_status);

//$hash2=base64_encode($msg3);

//header("Location:../index.php?hash=$hash&hash2=$msg3");
unset($_SESSION['form2_scruniny']);

die();
}
/*--------------------------end of common code for draft---------------------------------------------*/
}

    /*-------------------------------------end of condition for ia-------------------------------*/
    /*----------------------------------------------------------------------------------------------------*/

}
/*------------------------------------------------------------------------------------------------------*/
/*------------------------------------------------------------------------------------------------------*/
/*------------------------------------------------------------------------------------------------------*/
if($searchby == '1')     //not clear scrutiny
{
    $mis_no_doc =	$miscellaneous_ref_no_post;

	/*$st1=$db->prepare("select count(filing_no) from  $schemas.case_detail where filing_no =? ");
	$st1->bindParam(1, $filing_no, PDO::PARAM_STR);
	$st1->execute();
    $ccount_filing_no= $st1->fetchColumn(); UNUSED*/
 
/*-------------------------------------start of condition for fresh-------------------------------*/
/*--------------------------------------------------------------------------------------------*/   
    if($form_status=="F")
{
    try{
        $dbonline->beginTransaction();
        $db->beginTransaction();
		
		$st1x =$dbonline->prepare("update e_case_detail  set scrutiny_comp3 ='1' where filing_no=? ");
$st1x->bindParam(1, $filing_no, PDO::PARAM_STR);
$st1x->execute();

    //get case type
    $st1=$dbonline->prepare("select case_type from  e_case_detail where filing_no =? ");
$st1->bindParam(1, $filing_no, PDO::PARAM_STR);
$st1->execute();
$case_type_edetail= $st1->fetchColumn();
$case_type=$case_type_edetail;

/*----------------------start of common objection detail loop--------------------------------------*/
$status1=$_REQUEST['test'];
rtrim($status1,",");
$status=explode(",",$status1);

//$status=$_REQUEST['status'];
$comment=$_REQUEST['comment'];
$code1=$_REQUEST['id_check'];

$iii=0;
$len=htmlspecialchars(count($_REQUEST['id_check']));

for($i=0;$i<$len;$i++)
{    
$code111=explode(",",$code1[$i]);

if($code111[1]=='gen')
{
        $code11=$code111[0];
        $aa='0';
}
if($code111[1]=='IBC1')
{
        $code11=$code111[0];
        $aa='1';
}

     
     $status1=htmlspecialchars($status[$iii]);

     $comment1 = htmlspecialchars($comment[$iii]);

      $code11=htmlspecialchars(addslashes($code11));
      $status1=htmlspecialchars(addslashes($status1));
      $comment1 = htmlspecialchars(addslashes($comment1));
      $aas='1';
	  
	  $scrutiny_corr1= rtrim($scrutiny_corr,',');
	  

         $adddef_sql1 =$db->prepare("insert into $schemas.objection_details (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,level_level,ia_flag,ia_id,miscellaneous_ref_no,scrutiny_correction) values 
(?,?,?,?,?,?,?,?,?,?,?,?,?)");
      
$adddef_sql1->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$aas,$ia_flag,$ia_id,$mis_no_doc,$scrutiny_corr1));


       
      
$adddef_sql11 =$db->prepare("insert into $schemas.objection_details_his (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,level_level,ia_flag,ia_id,miscellaneous_ref_no,scrutiny_correction) values 
(?,?,?,?,?,?,?,?,?,?,?,?,?)");

$llps='1';

$adddef_sql11->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$llps,$ia_flag,$ia_id,$mis_no_doc,$scrutiny_corr1));
//echo "here";
$iii++;
$comment1="";
 }
/*end of common objection detail loop*/

$aaq='Y';
$kks='Y';
$ll='1';

//update scrutiny
$st13 =$db->prepare("update $schemas.scrutiny set objection_status=?,
			notification_date=?
	,defects=?,user_id=?,level_level=? where filing_no=?");
		  
		   $st13->bindParam(1, $kks, PDO::PARAM_STR);
		   $st13->bindParam(2, $notification_date1, PDO::PARAM_STR);
		   $st13->bindParam(3, $aaq, PDO::PARAM_STR);
		   $st13->bindParam(4, $sessionUserType, PDO::PARAM_STR);
		   $st13->bindParam(5, $ll, PDO::PARAM_STR);
		   //$st13->bindParam(6, $ia_flag, PDO::PARAM_STR);
		   //$st13->bindParam(7, $ref_no_ia, PDO::PARAM_STR);
		   //$st13->bindParam(8, $ia_id, PDO::PARAM_STR);
		   $st13->bindParam(6, $filing_no, PDO::PARAM_STR);
           $st13->execute();       

           $st13=$db->prepare("insert into  $schemas.scrutiny_his (filing_no,defects,notification_date,user_id,level_level)
           values (?,?,?,?,?) ");
           $st13->bindParam(1, $filing_no, PDO::PARAM_STR);
           $st13->bindParam(2, $aaq, PDO::PARAM_STR);
           $st13->bindParam(3, $notification_date1, PDO::PARAM_STR);
           $st13->bindParam(4, $sessionUserType, PDO::PARAM_STR);
           $st13->bindParam(5, $ll, PDO::PARAM_STR);
          //$st13->bindParam(6, $ia_flag, PDO::PARAM_STR);
          //$st13->bindParam(7, $ref_no_ia, PDO::PARAM_STR); 
           $st13->execute();  
           
//update document_upload------------------------------------------------------------------------------
           $scrutiny_dc='0';
$scr_display='1';
$doc_level='1';

	$st1x111 =$dbonline->prepare("update document_upload set doc_level=? where  filing_no=? and scrutiny=? and display=? and miscellaneous_ref_no IS NULL ");

//$st1x111->bindParam(1, $scrutiny_dc, PDO::PARAM_STR);
//$st1x111->bindParam(1, $scr_display, PDO::PARAM_STR);
$st1x111->bindParam(1, $doc_level, PDO::PARAM_STR);
$st1x111->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x111->bindParam(3, $scrutiny_dc, PDO::PARAM_STR);
$st1x111->bindParam(4, $scr_display, PDO::PARAM_STR);

$st1x111->execute();
   

/*--------------------------start of common code for draft---------------------------------------------*/
//code for updating draft values
$status_ins = 'CM0';    //scrutiny done by sc
$display = 'FALSE';
$level_level = 0;
$update_draft_display=$db->prepare("update $schemas.draft_objection_details set status=?,updated=?,display=? where filing_no=? and level_level=? and miscellaneous_ref_no IS NULL ");
            $update_draft_display->bindParam(1, $status_ins, PDO::PARAM_STR);
            $update_draft_display->bindParam(2, $server_date, PDO::PARAM_STR);
            $update_draft_display->bindParam(3, $display, PDO::PARAM_STR);
            $update_draft_display->bindParam(4, $filing_no, PDO::PARAM_STR);
            $update_draft_display->bindParam(5, $level_level, PDO::PARAM_STR);
            $update_draft_display->execute();

            $dbonline->commit();
                $db->commit();

echo $message = 'Scrutiny Done Successfully and Forwarded With Defect To Asst./Dy. Registrar';
$msg=base64_encode($filing_no);
$msg1=htmlspecialchars($message.'-'.$msg);
$hash=base64_encode($msg1);

$msg3=base64_encode($form_status);

//$hash2=base64_encode($msg3);



 header("Location:../index.php?hash=$hash&hash2=$msg3");
	unset($_SESSION['form2_scruniny']);
   die();
} catch (PDOException $e) {
    $dbonline->rollBack();
    $db->rollBack();
    echo $e->getMessage();
        //throw $e;
    
        echo $message = 'There is some problem while doing scrutiny...';
        $msg=base64_encode($filing_no);
        $msg1=htmlspecialchars($message.'-'.$msg);
        $hash=base64_encode($msg1);
        
        $msg3=base64_encode($form_status);
        
        //$hash2=base64_encode($msg3);
        
        header("Location:../index.php?hash=$hash&hash2=$msg3");
        unset($_SESSION['form2_scruniny']);
        die();

}
/*--------------------------end of common code for draft---------------------------------------------*/
}
/*-------------------------------------end of condition for fresh-------------------------------*/
/*--------------------------------------------------------------------------------------------*/   


/*-------------------------------------start of condition for doc-------------------------------*/
/*--------------------------------------------------------------------------------------------*/   
if($form_status=="C"){
    try{
        $dbonline->beginTransaction();
        $db->beginTransaction();

    //get case_type
    $st1=$db->prepare("select case_type from $schemas.case_detail where filing_no =? ");
	$st1->bindParam(1, $filing_no, PDO::PARAM_STR);
	$st1->execute();
	$case_type_edetail= $st1->fetchColumn();
    $case_type=$case_type_edetail;

    /*----------------------start of common objection detail loop--------------------------------------*/
$status1=$_REQUEST['test'];
rtrim($status1,",");
$status=explode(",",$status1);

//$status=$_REQUEST['status'];
$comment=$_REQUEST['comment'];
$code1=$_REQUEST['id_check'];

$iii=0;
$len=htmlspecialchars(count($_REQUEST['id_check']));

for($i=0;$i<$len;$i++)
{    
$code111=explode(",",$code1[$i]);

if($code111[1]=='gen')
{
        $code11=$code111[0];
        $aa='0';
}
if($code111[1]=='IBC1')
{
        $code11=$code111[0];
        $aa='1';
}

     
     $status1=htmlspecialchars($status[$iii]);

     $comment1 = htmlspecialchars($comment[$iii]);

      $code11=htmlspecialchars(addslashes($code11));
      $status1=htmlspecialchars(addslashes($status1));
      $comment1 = htmlspecialchars(addslashes($comment1));
    
       $adddef_sql1 =$db->prepare("insert into $schemas.objection_details (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,level_level,ia_flag,ia_id,miscellaneous_ref_no) values 
(?,?,?,?,?,?,?,?,?,?,?,?)");

$aas='11';
      
$adddef_sql1->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$aas,$ia_flag,$ia_id,$mis_no_doc));
//echo "here";

       $adddef_sql11 =$db->prepare("insert into $schemas.objection_details_his (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,level_level,ia_flag,ia_id,miscellaneous_ref_no) values 
(?,?,?,?,?,?,?,?,?,?,?,?)");

$llps='11';

$adddef_sql11->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$llps,$ia_flag,$ia_id,$mis_no_doc));
//echo "here";
$iii++;
$comment1="";
 }
/*end of common objection detail loop*/
    
    $aaq='Y';
    $kks='Y';
    $ll='11';
    //update scrutiny
	
	
	// changes 
	

$st13 =$db->prepare("update $schemas.scrutiny set objection_status=?,
notification_date=?
,defects=?,user_id=?,level_level=? where filing_no=?");

$st13->bindParam(1, $kks, PDO::PARAM_STR);
$st13->bindParam(2, $notification_date1, PDO::PARAM_STR);
$st13->bindParam(3, $aaq, PDO::PARAM_STR);
$st13->bindParam(4, $sessionUserType, PDO::PARAM_STR);
$st13->bindParam(5, $ll, PDO::PARAM_STR);
//$st13->bindParam(6, $ia_flag, PDO::PARAM_STR);
//$st13->bindParam(7, $ref_no_ia, PDO::PARAM_STR);
//$st13->bindParam(8, $ia_id, PDO::PARAM_STR);
$st13->bindParam(6, $filing_no, PDO::PARAM_STR);
$st13->execute();       

$st13=$db->prepare("insert into  $schemas.scrutiny_his (filing_no,defects,notification_date,user_id,level_level)
values (?,?,?,?,?) ");
$st13->bindParam(1, $filing_no, PDO::PARAM_STR);
$st13->bindParam(2, $aaq, PDO::PARAM_STR);
$st13->bindParam(3, $notification_date1, PDO::PARAM_STR);
$st13->bindParam(4, $sessionUserType, PDO::PARAM_STR);
$st13->bindParam(5, $ll, PDO::PARAM_STR);
//$st13->bindParam(6, $ia_flag, PDO::PARAM_STR);
//$st13->bindParam(7, $ref_no_ia, PDO::PARAM_STR); 
$st13->execute();  

//update document_upload
$scrutiny_dc='0';
$scr_display='1';
$doc_level='11';

$st1x111 =$dbonline->prepare("update document_upload set doc_level=? where  filing_no=? and scrutiny=? and display=? and 	miscellaneous_ref_no=? and party_type NOT IN (select party_flag from e_master_govt_body) ");
//$st1x111->bindParam(1, $scrutiny_dc, PDO::PARAM_STR);
//$st1x111->bindParam(1, $scr_display, PDO::PARAM_STR);
$st1x111->bindParam(1, $doc_level, PDO::PARAM_STR);
$st1x111->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x111->bindParam(3, $scrutiny_dc, PDO::PARAM_STR);
$st1x111->bindParam(4, $scr_display, PDO::PARAM_STR);
$st1x111->bindParam(5, $mis_no_doc, PDO::PARAM_STR);
$st1x111->execute();

//insert scrutiny_doc
$obj_status='Y';
$doc_level='11';
$st13=$db->prepare("insert into  $schemas.scrutiny_doc (filing_no,notification_date,user_id,objection_status,defects,level_level,miscellaneous_ref_no)
		values (?,?,?,?,?,?,?) ");
$st13->bindParam(1, $filing_no, PDO::PARAM_STR);
$st13->bindParam(2, $notification_date1, PDO::PARAM_STR);
$st13->bindParam(3, $sessionUserType, PDO::PARAM_STR);
$st13->bindParam(4, $obj_status, PDO::PARAM_STR);
$st13->bindParam(5, $obj_status, PDO::PARAM_STR);
$st13->bindParam(6, $doc_level, PDO::PARAM_STR);
$st13->bindParam(7, $mis_no_doc, PDO::PARAM_STR);
$st13->execute();

/*--------------------------start of common code for draft---------------------------------------------*/
//code for updating draft values
$status_ins = 'CM0';    //scrutiny done by sc
$display = 'FALSE';
$level_level = 0;
$update_draft_display=$db->prepare("update $schemas.draft_objection_details set status=?,updated=?,display=? where filing_no=? and level_level=? and miscellaneous_ref_no=? and form_type IS NULL");
            $update_draft_display->bindParam(1, $status_ins, PDO::PARAM_STR);
            $update_draft_display->bindParam(2, $server_date, PDO::PARAM_STR);
            $update_draft_display->bindParam(3, $display, PDO::PARAM_STR);
            $update_draft_display->bindParam(4, $filing_no, PDO::PARAM_STR);
            $update_draft_display->bindParam(5, $level_level, PDO::PARAM_STR);
			$update_draft_display->bindParam(6, $mis_no_doc, PDO::PARAM_STR);
            $update_draft_display->execute();

            $dbonline->commit();
                $db->commit();

echo $message = 'Scrutiny Done Successfully and Forwarded With Defect To Asst./Dy. Registrar';
$msg=base64_encode($filing_no);
$msg1=htmlspecialchars($message.'-'.$msg);
$hash=base64_encode($msg1);

$msg3=base64_encode($form_status);

//$hash2=base64_encode($msg3);



header("Location:../index.php?hash=$hash&hash2=$msg3");
	unset($_SESSION['form2_scruniny']);
   die(); 
}catch (PDOException $e) {
    $dbonline->rollBack();
    $db->rollBack();
    echo $e->getMessage();
        //throw $e;
    
        echo $message = 'There is some problem while doing scrutiny...';
        $msg=base64_encode($filing_no);
        $msg1=htmlspecialchars($message.'-'.$msg);
        $hash=base64_encode($msg1);
        
        $msg3=base64_encode($form_status);
        
        //$hash2=base64_encode($msg3);
        
        header("Location:../index.php?hash=$hash&hash2=$msg3");
        unset($_SESSION['form2_scruniny']);
        die();

}
/*--------------------------stop of common code for draft---------------------------------------------*/
}
/*-------------------------------------end of condition for doc-------------------------------*/
/*--------------------------------------------------------------------------------------------*/   




if($form_status=="R"){
    try{
        $dbonline->beginTransaction();
        $db->beginTransaction();
		
		if($subdoctype=='17')
{
	$form_type='R';
}
if($subdoctype=='33')
{
	$form_type='O';
}
//$form_type='R';
    //get case_type
    $st1=$db->prepare("select case_type from $schemas.case_detail where filing_no =? ");
	$st1->bindParam(1, $filing_no, PDO::PARAM_STR);
	$st1->execute();
	$case_type_edetail= $st1->fetchColumn();
    $case_type=$case_type_edetail;

    /*----------------------start of common objection detail loop--------------------------------------*/
$status1=$_REQUEST['test'];
rtrim($status1,",");
$status=explode(",",$status1);

//$status=$_REQUEST['status'];
$comment=$_REQUEST['comment'];
$code1=$_REQUEST['id_check'];

$iii=0;
$len=htmlspecialchars(count($_REQUEST['id_check']));

for($i=0;$i<$len;$i++)
{    
$code111=explode(",",$code1[$i]);

if($code111[1]=='gen')
{
        $code11=$code111[0];
        $aa='0';
}
if($code111[1]=='IBC1')
{
        $code11=$code111[0];
        $aa='1';
}

     
     $status1=htmlspecialchars($status[$iii]);

     $comment1 = htmlspecialchars($comment[$iii]);

      $code11=htmlspecialchars(addslashes($code11));
      $status1=htmlspecialchars(addslashes($status1));
      $comment1 = htmlspecialchars(addslashes($comment1));
    
       $adddef_sql1 =$db->prepare("insert into $schemas.objection_details (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,level_level,ia_flag,ia_id,miscellaneous_ref_no,form_type) values 
(?,?,?,?,?,?,?,?,?,?,?,?,?)");

$aas='4';
      
$adddef_sql1->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$aas,$ia_flag,$ia_id,$mis_no_doc,$form_type));

       $adddef_sql11 =$db->prepare("insert into $schemas.objection_details_his (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,level_level,ia_flag,ia_id,miscellaneous_ref_no,form_type) values 
(?,?,?,?,?,?,?,?,?,?,?,?,?)");

$llps='4';

$adddef_sql11->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$llps,$ia_flag,$ia_id,$mis_no_doc,$form_type));
//echo "here";



$iii++;
$comment1="";
 }
/*end of common objection detail loop*/
    
 

//update document_upload
$scrutiny_dc='0';
$scr_display='1';
$doc_level='4';

$st1x111 =$dbonline->prepare("update document_upload set doc_level=? where  filing_no=? and scrutiny=? and display=? and miscellaneous_ref_no=? and subdoctype=? and party_type IN (select party_flag from e_master_govt_body)");
//$st1x111->bindParam(1, $scrutiny_dc, PDO::PARAM_STR);
//$st1x111->bindParam(1, $scr_display, PDO::PARAM_STR);
$st1x111->bindParam(1, $doc_level, PDO::PARAM_STR);
$st1x111->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x111->bindParam(3, $scrutiny_dc, PDO::PARAM_STR);
$st1x111->bindParam(4, $scr_display, PDO::PARAM_STR);
$st1x111->bindParam(5, $mis_no_doc, PDO::PARAM_STR);
$st1x111->bindParam(6, $subdoctype, PDO::PARAM_STR);
$st1x111->execute();

//insert scrutiny_doc
$obj_status='Y';
$doc_level='4';
$st13=$db->prepare("insert into  $schemas.scrutiny_doc (filing_no,notification_date,user_id,objection_status,defects,level_level,miscellaneous_ref_no,form_type)
		values (?,?,?,?,?,?,?,?) ");
$st13->bindParam(1, $filing_no, PDO::PARAM_STR);
$st13->bindParam(2, $notification_date1, PDO::PARAM_STR);
$st13->bindParam(3, $sessionUserType, PDO::PARAM_STR);
$st13->bindParam(4, $obj_status, PDO::PARAM_STR);
$st13->bindParam(5, $obj_status, PDO::PARAM_STR);
$st13->bindParam(6, $doc_level, PDO::PARAM_STR);
$st13->bindParam(7, $mis_no_doc, PDO::PARAM_STR);
$st13->bindParam(8, $form_type, PDO::PARAM_STR);
$st13->execute();

/*--------------------------start of common code for draft---------------------------------------------*/
//code for updating draft values
$status_ins = 'CM0';    //scrutiny done by sc
$display = 'FALSE';
$level_level = 0;
$update_draft_display=$db->prepare("update $schemas.draft_objection_details set status=?,updated=?,display=? where filing_no=? and level_level=? and miscellaneous_ref_no=? and form_type=?");
            $update_draft_display->bindParam(1, $status_ins, PDO::PARAM_STR);
            $update_draft_display->bindParam(2, $server_date, PDO::PARAM_STR);
            $update_draft_display->bindParam(3, $display, PDO::PARAM_STR);
            $update_draft_display->bindParam(4, $filing_no, PDO::PARAM_STR);
            $update_draft_display->bindParam(5, $level_level, PDO::PARAM_STR);
			 $update_draft_display->bindParam(6, $miscellaneous_ref_no_post, PDO::PARAM_STR);
			  $update_draft_display->bindParam(7, $form_type, PDO::PARAM_STR);
            $update_draft_display->execute();

            $dbonline->commit();
                $db->commit();

echo $message = 'Scrutiny Done Successfully and Forwarded With Defect To Asst./Dy. Registrar';
$msg=base64_encode($filing_no);
$msg1=htmlspecialchars($message.'-'.$msg);
$hash=base64_encode($msg1);

$msg3=base64_encode($form_status);

//$hash2=base64_encode($msg3);



header("Location:../index.php?hash=$hash&hash2=$msg3");
	unset($_SESSION['form2_scruniny']);
   die(); 
}catch (PDOException $e) {
    $dbonline->rollBack();
    $db->rollBack();
    echo $e->getMessage();
        //throw $e;
    
        echo $message = 'There is some problem while doing scrutiny...';
        $msg=base64_encode($filing_no);
        $msg1=htmlspecialchars($message.'-'.$msg);
        $hash=base64_encode($msg1);
        
        $msg3=base64_encode($form_status);
        
        //$hash2=base64_encode($msg3);
        
        header("Location:../index.php?hash=$hash&hash2=$msg3");
        unset($_SESSION['form2_scruniny']);
        die();

}
/*--------------------------stop of common code for draft---------------------------------------------*/
}
/*-------------------------------------end of condition for doc-------------------------------*/
/*--------------------























/*-------------------------------------start of condition for ia-------------------------------*/
/*--------------------------------------------------------------------------------------------*/ 
if($form_status=='I')
{
    try{
        $dbonline->beginTransaction();
        $db->beginTransaction();

//get case_type
$st1=$dbonline->prepare("select case_type from  e_case_detail where filing_no =? ");
$st1->bindParam(1, $filing_no, PDO::PARAM_STR);
$st1->execute();
$case_type_edetail= $st1->fetchColumn();
$case_type=$case_type_edetail;

 /*----------------------start of common objection detail loop--------------------------------------*/
 $status1=$_REQUEST['test'];
 rtrim($status1,",");
 $status=explode(",",$status1);
 
 //$status=$_REQUEST['status'];
 $comment=$_REQUEST['comment'];
 $code1=$_REQUEST['id_check'];
 
 $iii=0;
 $len=htmlspecialchars(count($_REQUEST['id_check']));
 
 for($i=0;$i<$len;$i++)
 {    
 $code111=explode(",",$code1[$i]);
 
 if($code111[1]=='gen')
 {
         $code11=$code111[0];
         $aa='0';
 }
 if($code111[1]=='IBC1')
 {
         $code11=$code111[0];
         $aa='1';
 }
 
      
      $status1=htmlspecialchars($status[$iii]);
 
      $comment1 = htmlspecialchars($comment[$iii]);
 
       $code11=htmlspecialchars(addslashes($code11));
       $status1=htmlspecialchars(addslashes($status1));
       $comment1 = htmlspecialchars(addslashes($comment1));
     
        $adddef_sql1 =$db->prepare("insert into $schemas.objection_details (filing_no,objection_code,
 comments,userid,entry_dt,status,case_type,objection_sub_code,level_level,ia_flag,ia_id,miscellaneous_ref_no) values 
 (?,?,?,?,?,?,?,?,?,?,?,?)");
 
 $aas='111';
       
 $adddef_sql1->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
 $case_type,$aa,$aas,$ia_flag,$ia_id,$mis_no_doc));
 //echo "here";
 
        $adddef_sql11 =$db->prepare("insert into $schemas.objection_details_his (filing_no,objection_code,
 comments,userid,entry_dt,status,case_type,objection_sub_code,level_level,ia_flag,ia_id,miscellaneous_ref_no) values 
 (?,?,?,?,?,?,?,?,?,?,?,?)");
 
 $llps='111';
 
 $adddef_sql11->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
 $case_type,$aa,$llps,$ia_flag,$ia_id,$mis_no_doc));
 //echo "here";
 $iii++;
 $comment1="";
  }
 /*end of common objection detail loop*/

//insert or update scrutiny_ia
$obj_status='Y';
$doc_level='111';
$scrutiny ='1';
//echo "ii";
$get_scrutiny_val=$db->prepare("select level_level from $schemas.scrutiny_ia where  filing_no=? and ia_id=? ");
	$get_scrutiny_val->bindParam(1, $filing_no, PDO::PARAM_STR);
	$get_scrutiny_val->bindParam(2, $ia_id, PDO::PARAM_STR);
	$get_scrutiny_val->execute();
	$scrutiny_cheker= $get_scrutiny_val->fetchColumn();

if($scrutiny_cheker == '222'){	
//echo "i";
		$st13=$db->prepare("update $schemas.scrutiny_ia set notification_date=?,user_id=?,objection_status=?,defects=?,level_level=? where
	filing_no=? and ia_id=?");
$st13->bindParam(1, $notification_date1, PDO::PARAM_STR);
$st13->bindParam(2, $sessionUserType, PDO::PARAM_STR);
$st13->bindParam(3, $obj_status, PDO::PARAM_STR);
$st13->bindParam(4, $obj_status, PDO::PARAM_STR);
$st13->bindParam(5, $doc_level, PDO::PARAM_STR);
$st13->bindParam(6, $filing_no, PDO::PARAM_STR);
$st13->bindParam(7, $ia_id, PDO::PARAM_STR);
$st13->execute();
echo "111";
}else{
	$st13=$db->prepare("insert into  $schemas.scrutiny_ia (filing_no,notification_date,user_id,objection_status,defects,level_level,ia_id)
		values (?,?,?,?,?,?,?) ");
$st13->bindParam(1, $filing_no, PDO::PARAM_STR);
$st13->bindParam(2, $notification_date1, PDO::PARAM_STR);
$st13->bindParam(3, $sessionUserType, PDO::PARAM_STR);
$st13->bindParam(4, $obj_status, PDO::PARAM_STR);
$st13->bindParam(5, $obj_status, PDO::PARAM_STR);
$st13->bindParam(6, $doc_level, PDO::PARAM_STR);
$st13->bindParam(7, $ia_id, PDO::PARAM_STR);
$st13->execute();
}

//set scrutiny done i.e, scrutiny=1 in e_ia_details
$update_ia_details =$dbonline->prepare("update e_ia_details set scrutiny=? where  filing_no=? and ia_id=? ");
$update_ia_details->bindParam(1, $scrutiny, PDO::PARAM_STR);
$update_ia_details->bindParam(2, $filing_no, PDO::PARAM_STR);
$update_ia_details->bindParam(3, $ia_id, PDO::PARAM_STR);
$update_ia_details->execute();


/*$scrutiny_dc='0';
$scr_display='1';
$doc_level='111';
//echo $rr = "update document_upload set doc_level='$doc_level' where  filing_no='$filing_no' and scrutiny='$scrutiny_dc' and display='$scr_display' ";
	$st1x111 =$dbonline->prepare("update document_upload set doc_level=? where  filing_no=? and scrutiny=? and display=? ");

//$st1x111->bindParam(1, $scrutiny_dc, PDO::PARAM_STR);
//$st1x111->bindParam(1, $scr_display, PDO::PARAM_STR);
$st1x111->bindParam(1, $doc_level, PDO::PARAM_STR);
$st1x111->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x111->bindParam(3, $scrutiny_dc, PDO::PARAM_STR);
$st1x111->bindParam(4, $scr_display, PDO::PARAM_STR);

$st1x111->execute(); IS FILLING NO PRESENT IN DOC_UPLOAD FOR IA*/

/*--------------------------start of common code for draft---------------------------------------------*/
//code for updating draft values
$status_ins = 'CM0';    //scrutiny done by sc
$display = 'FALSE';
$level_level = 0;
$update_draft_display=$db->prepare("update $schemas.draft_objection_details set status=?,updated=?,display=? where filing_no=? and level_level=? and miscellaneous_ref_no IS NULL and form_type IS NULL");
            $update_draft_display->bindParam(1, $status_ins, PDO::PARAM_STR);
            $update_draft_display->bindParam(2, $server_date, PDO::PARAM_STR);
            $update_draft_display->bindParam(3, $display, PDO::PARAM_STR);
            $update_draft_display->bindParam(4, $filing_no, PDO::PARAM_STR);
            $update_draft_display->bindParam(5, $level_level, PDO::PARAM_STR);
            $update_draft_display->execute();

            $dbonline->commit();
                $db->commit();

echo $message = 'Scrutiny Done Successfully and Forwarded With Defect To Asst./Dy. Registrar';
$msg=base64_encode($filing_no);
$msg1=htmlspecialchars($message.'-'.$msg);
$hash=base64_encode($msg1);

$msg3=base64_encode($form_status);

//$hash2=base64_encode($msg3);



header("Location:../index.php?hash=$hash&hash2=$msg3");
	unset($_SESSION['form2_scruniny']);
   die(); 
}catch (PDOException $e) {
    $dbonline->rollBack();
    $db->rollBack();
    echo $e->getMessage();
        //throw $e;
    
        echo $message = 'There is some problem while doing scrutiny...';
        $msg=base64_encode($filing_no);
        $msg1=htmlspecialchars($message.'-'.$msg);
        $hash=base64_encode($msg1);
        
        $msg3=base64_encode($form_status);
        
        //$hash2=base64_encode($msg3);
        
        header("Location:../index.php?hash=$hash&hash2=$msg3");
        unset($_SESSION['form2_scruniny']);
        die();

}
/*--------------------------stop of common code for draft---------------------------------------------*/

}

/*-------------------------------------end of condition for ia-------------------------------*/
/*--------------------------------------------------------------------------------------------*/ 
}


echo 'Invalid Entry.......';
header("Location:../index.php");
}
?>