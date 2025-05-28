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

include '../custom/custom_function.php';
require "../vendor/autoload.php";
require_once('../object_storage/S3Service.php');
use Dompdf\Dompdf;
$dompdf = new Dompdf();

ini_set('max_execution_time', 0);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

function display_filing_no($filing_no_display){
      $lastFour =  substr($filing_no_display,-4);
      $lastFive = substr($filing_no_display,-9,-4);
      $left = substr($filing_no_display,-16,-9);
      return $dis_fil_no = $left.'/<b>'.$lastFive.'/'.$lastFour.'</b>';

  }
 
function defect_no($filing_no_display){
	$midFive = substr($filing_no_display,-9,-4);
	return $midFive;
}

function get_order_detail($db,$filing_no){
    $query = "select order_number,order_date from e_order_details where filing_no = ?";
    $query_ex = $db->prepare($query);
    $query_ex->bindParam(1,$filing_no,PDO::PARAM_STR);
    $query_ex->execute();
    $data = $query_ex->fetch();
    return $data;
}



$auto_manual = htmlspecialchars($_REQUEST['auto_manual']);

$form_status = htmlspecialchars($_REQUEST['form_status']);

$subdoctype= htmlspecialchars($_REQUEST['subdoctype']);
date_default_timezone_set("Asia/Kolkata");
require('./pdf-generator/fpdf.php'); 

 require('./pdf-generator/mc_table.php');

include("../db_inc1.php");
include '../db_inc2.php';

session_start();
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); 
list($yy9,$mm9,$dd9) = explode("-", $server_date);


$cause_no = $_REQUEST['cause_no'];


$cnt_len1=count($cause_no);


for($h=0;$h<$cnt_len1;$h++)
{
$scrutiny_corr=$scrutiny_corr.$cause_no[$h].",";
}
 $scrutiny_corr1= rtrim($scrutiny_corr,',');
 
  $scrutiny_corr1;
 rtrim($scrutiny_corr1,",");
  $refiled_doc=explode(",",$scrutiny_corr1);
  $refiled_case_doc="";
  for ($r=0;$r<=count($refiled_doc);$r++)
  {
  	if($refiled_doc[$r]==6)
  	{
  		$refiled_case_doc=1;
  	}
  }
  
 
$_SESSION['user'];
$location_access = $_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$username = $_SESSION['actual_username'];
$userid=$_SESSION['id'];
$report_party_type = htmlspecialchars($_REQUEST['report_party_type']);
if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
echo "Access Problem....."; 
session_unset();     // unset $_SESSION variable for the run-time
session_destroy();
header("Location: ../login.php?aa=100");
die();
}

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


  //$searchby = $_REQUEST['searchby'];
 
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

   $searchby;
 //$ia_id = htmlspecialchars($_REQUEST['ia_id']);
 if($form_status=='I'){
	 $ia_id=htmlspecialchars($_REQUEST['ia_id']);
	 $ia_flag='TRUE';
 }else{
	$ia_id = 0;
	$ia_flag='FALSE';
 }
 if($form_status=='C' || $form_status=='R'){
	if($form_status == 'R'){
		$getData=trim(base64_decode($_REQUEST['filing_no_next']));
		$getDataArray=explode('-',$getData);
		$_REQUEST['filing_no']=$getDataArray[0];
		$_REQUEST['miscellaneous_ref_no_post']=$getDataArray[2];
	}
	$miscellaneous_ref_no_post=htmlspecialchars($_REQUEST['miscellaneous_ref_no_post']);
	$misc_flag='TRUE';
}else{
   $miscellaneous_ref_no_post = 0;
   $misc_flag='FALSE';
}
if($form_status=='R')
{
	$form_type='R';
}

  
$filing_no = htmlspecialchars($_REQUEST['filing_no']);
$ref_no_ia = htmlspecialchars($_REQUEST['ref_no_ia']);
$coulfil=strlen($filing_no);

if($coulfil !='16')
{
	echo $filing_no;
	print "Filing No Not Right";
	//session_unset();     // unset $_SESSION variable for the run-time
	//session_destroy();
	//header("Location: ../login.php?aa=10");
	die();	
}

if (!is_numeric($filing_no))
{
	print "Filing No Not Empty";
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	header("Location: ../login.php?aa=100");
	die();

}

 $notification_date = $_REQUEST['notification_date'];


list($day,$month,$year)=explode('/',$notification_date);
   $notification_date1=$year.'-'.$month.'-'.$day;

 
  $comment = htmlspecialchars(htmlentities($_REQUEST['comment']));


/*-------------------------------------------------------------------------------------------------------------*/
/*-------------------------------------------------------------------------------------------------------------*/


/******** UPDATE RE-FILING TIME PASS VIA REGISTRAR *********/
//echo '---'.strpos(strtolower(rtrim($_REQUEST['test'])),'no');
if(strpos(strtolower(rtrim($_REQUEST['test'])),'no') !== false){
    $flno=@htmlentities($_REQUEST['filing_no']);
    $mis_flno=@htmlentities($_REQUEST['miscellaneous_ref_no_post']);
    if(strlen($mis_flno) > 16) $flno=$mis_flno;

    //echo "select * from $schemas.regvarify_sevenday where filing_no='$flno'";
    $qr="select * from $schemas.regvarify_sevenday where filing_no=?";
    $verify_regvarify_sevenday=$db->prepare($qr);
    $verify_regvarify_sevenday->bindParam(1, $flno, PDO::PARAM_STR);
    $verify_regvarify_sevenday->execute();
    if($verify_regvarify_sevenday->rowCount() > 0){
        $ntfDate=date('Y-m-d',strtotime(strtr($_REQUEST['notification_date'],['/'=>'-'])));
        $ntfDate=date('Y-m-d', strtotime($ntfDate. ' + 7 days')); 
        $updQr="update $schemas.regvarify_sevenday set display='Y', allowed_count=allowed_count+1, defect_date=date(now()),allowed_refiling_date=? where filing_no=? and allowed_count < 10";
        //echo '**'.$updQr;
        $updRs=$db->prepare($updQr);
        $updRs->bindParam(1, $ntfDate, PDO::PARAM_STR);
        $updRs->bindParam(2, $flno, PDO::PARAM_STR);
        $updRs->execute();
        //echo '**'.$updQr;
        //exit();
    }
}
sleep(2);


//master loop scrutiny clear
if($searchby == '2') //
{

list($dd,$mm,$yy) = explode("/", $notification_date);
$noti = htmlspecialchars($yy."-".$mm."-".$dd);

$status1=$_REQUEST['test'];
rtrim($status1,",");
$status=explode(",",$status1);

$comment=$_REQUEST['comment'];
$code1=$_REQUEST['id_check'];

$len=htmlspecialchars(count($_REQUEST['id_check']));
$docs_defect = null;

if($form_status=="F")
{
$ef_sc='0';
$ef_dis='1';
$doc_level='2';
$doc_sc='1';
}




if($form_status=="F"){

    try{
      
        $db->beginTransaction();
    //update doc_upload
	
$st12x =$db->prepare("update document_upload set scrutiny=?,doc_level=? where filing_no=? and scrutiny=? and display=? and (miscellaneous_ref_no IS NULL or miscellaneous_ref_no = '')");
$st12x->bindParam(1, $doc_sc, PDO::PARAM_STR);
$st12x->bindParam(2, $doc_level, PDO::PARAM_STR);
$st12x->bindParam(3, $filing_no, PDO::PARAM_STR);
$st12x->bindParam(4, $ef_sc, PDO::PARAM_STR);
$st12x->bindParam(5, $ef_dis, PDO::PARAM_STR);
$st12x->execute();

$sccc='1';
$obj_st='N';
$def='N';
$ll='2';
//update scrutiny
$st1=$db->prepare("update $schemas.scrutiny set compliance_date =?,level_level=?,varifyed_userid=?,
					objection_status = ?, defects = ? , scrutinu_comp = ? , updated_at = now(), updated_by = ? where filing_no =? ");
$st1->bindParam(1, $notification_date1, PDO::PARAM_STR);
$st1->bindParam(2, $ll, PDO::PARAM_STR);
$st1->bindParam(3, $sessionUserType, PDO::PARAM_STR);
$st1->bindParam(4, $obj_st, PDO::PARAM_STR);
$st1->bindParam(5, $def, PDO::PARAM_STR);
$st1->bindParam(6, $sccc, PDO::PARAM_STR);
$st1->bindParam(7, $sessionUserType, PDO::PARAM_STR);
$st1->bindParam(8, $filing_no, PDO::PARAM_STR);
$st1->execute();


$obj_st='N';
$def='N';


$st13=$db->prepare("insert into  $schemas.scrutiny_his (filing_no,defects,notification_date,user_id,level_level)
		values (?,?,?,?,?) ");
$st13->bindParam(1, $filing_no, PDO::PARAM_STR);
$st13->bindParam(2, $def, PDO::PARAM_STR);
$st13->bindParam(3, $notification_date1, PDO::PARAM_STR);
$st13->bindParam(4, $sessionUserType, PDO::PARAM_STR);
$st13->bindParam(5, $ll, PDO::PARAM_STR);
$st13->execute();


//update e_case_detail
$sccc='1';
$st1x =$db->prepare("update e_case_detail set scrutiny = 1,scrutiny_level=2,is_defective=0,defect_docs=? where filing_no=? ");
$st1x->bindParam(1, $docs_defect, PDO::PARAM_STR);
$st1x->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x->execute();


		/* Start of common Loop for objection details entry */
/*-----------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------*/
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
	if($code111[1]=='newcasetype')
	{
			 $code11=$code111[0];
			$aa='2';
	}
	if($code111[1]=='IBC1')
	{
			$code11=$code111[0];
			$aa='1';
	}
	
$status1=htmlspecialchars($status[$i]);
if($comment[$i]!="")

{
	$comment1 = htmlspecialchars($comment[$i]);
}
//$comment1 = htmlspecialchars($comment[$i]);

$code11=htmlspecialchars(addslashes($code11));
$status1=htmlspecialchars(addslashes($status1));
$comment1 = htmlspecialchars(addslashes($comment1));

$yes='Y';
$ll='2';

$adddef_sql = "update $schemas.objection_details set comment_registrar=?,userid=?,entry_date=?,status_registrar=?,"
        . "objection_sub_code=?,completed_flag=?,level_level=? where filing_no=? and objection_code=? and miscellaneous_ref_no=? and form_type IS NULL";
$sthaqq = $db->prepare($adddef_sql);
$sthaqq->bindParam(1, $comment1, PDO::PARAM_STR);
$sthaqq->bindParam(2, $sessionUserType, PDO::PARAM_STR);
$sthaqq->bindParam(3, $notification_date1, PDO::PARAM_STR);
$sthaqq->bindParam(4, $status1, PDO::PARAM_STR);
$sthaqq->bindParam(5, $aa, PDO::PARAM_STR);
$sthaqq->bindParam(6, $yes, PDO::PARAM_STR);
$sthaqq->bindParam(7, $ll, PDO::PARAM_STR);
$sthaqq->bindParam(8, $filing_no, PDO::PARAM_STR);
$sthaqq->bindParam(9, $code11, PDO::PARAM_STR);
$sthaqq->bindParam(10, $miscellaneous_ref_no_post, PDO::PARAM_STR);
$sthaqq->execute();


$adddef_sql = "insert into $schemas.objection_details_his (filing_no,objection_code,
comment_registrar,userid,entry_date,status,objection_sub_code,completed_flag,level_level,miscellaneous_ref_no) values(?,?,?,?,?,?,?,?,?,?)";
$sthaqq = $db->prepare($adddef_sql);
$sthaqq = $db->prepare($adddef_sql);
$sthaqq->bindParam(1, $filing_no, PDO::PARAM_STR);
$sthaqq->bindParam(2, $code11, PDO::PARAM_STR);
$sthaqq->bindParam(3, $comment1, PDO::PARAM_STR);
$sthaqq->bindParam(4, $sessionUserType, PDO::PARAM_STR);
$sthaqq->bindParam(5, $notification_date1, PDO::PARAM_STR);
$sthaqq->bindParam(6, $status1, PDO::PARAM_STR);
$sthaqq->bindParam(7, $aa, PDO::PARAM_STR);
$sthaqq->bindParam(8, $yes, PDO::PARAM_STR);
$sthaqq->bindParam(9, $ll, PDO::PARAM_STR);
$sthaqq->bindParam(10, $miscellaneous_ref_no_post, PDO::PARAM_STR);
$sthaqq->execute();

$comment1="";
}
	   
//code for updating draft values
$status_ins = 'CM1';    //scrutiny done by ar
$display = 'FALSE';
$level_level = 1;       //ar level

$update_draft_display=$db->prepare("update $schemas.draft_objection_details set status=?,updated=?,display=? where filing_no=? and level_level=? and miscellaneous_ref_no IS NULL");
            $update_draft_display->bindParam(1, $status_ins, PDO::PARAM_STR);
            $update_draft_display->bindParam(2, $server_date, PDO::PARAM_STR);
            $update_draft_display->bindParam(3, $display, PDO::PARAM_STR);
            $update_draft_display->bindParam(4, $filing_no, PDO::PARAM_STR);
            $update_draft_display->bindParam(5, $level_level, PDO::PARAM_STR);
            $update_draft_display->execute();

$save_fn=$db->prepare("insert into $schemas.case_no_generation (filing_no) values (?)");
$save_fn->bindParam(1, $filing_no, PDO::PARAM_STR);
$save_fn->execute();

	
$db->commit();


echo $message = "Scrutiny Compeleted without defect and forwarded to case number generation";
$msg=base64_encode($filing_no);
$msg1=htmlspecialchars($message.'-'.$msg);
$hash=base64_encode($msg1);
$msg3=base64_encode($form_status);

//$hash2=base64_encode($msg3);

header("Location:../index.php?hash=$hash&hash2=$msg3");
//unset($_SESSION['form2_scruniny']);
die();

    }catch ( PDOException $e) {
        $db->rollBack();
	
$message = 'There is some problem while doing scrutiny...<br>1. '.$e->getMessage();
$msg=base64_encode($filing_no);
$msg1=htmlspecialchars($message.'-'.$msg);
$hash=base64_encode($msg1);
$msg3=base64_encode($form_status);

$msg4=base64_encode($e);

header("Location:../index.php?hash=$hash&hash2=$msg3");
die;

    }
}

/*End of Fresh Condition*/
          
}



if($searchby == '1')  //not clear abcc
{ 
	/* -------------------------Start of Fresh Case Condition------------------------------ */

	if($form_status == "F"){
        try{
        	$s3Service = new S3Service();
        	$st1=$db->prepare("select scrutiny_count from  e_case_detail where filing_no =? ");
			$st1->bindParam(1, $filing_no, PDO::PARAM_STR);
			$st1->execute();
			$scrutiny_count= $st1->fetchColumn();

        	$db->beginTransaction();
			//get case type
		$st1=$db->prepare("select case_type_nclat,boofficefound,supply_disputed_questions,refile_count,allow_refiling,rejected_from_scrutiny,scrutiny_count from  e_case_detail where filing_no =?");
		$st1->bindParam(1, $filing_no, PDO::PARAM_STR);
		$st1->execute();
		$filed_detail= $st1->fetch();
		$case_type=$filed_detail['case_type_nclat'];
		$boofficefound=$filed_detail['boofficefound'];
		$docs_defect = (isset($_POST['docs']))?implode(',', $_POST['docs']):null;
		$user_scrutiny_count = $filed_detail['scrutiny_count'];
		$refile_count = $filed_detail['refile_count'];
		$supply_disputed_questions = $filed_detail['supply_disputed_questions'];

		//echo "<pre>"; print_r($docs_defect);

		/*------------------ Start of common Loop for objection details entry -------------------*/

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
		
			 if($comment[$i]!='')
		
			 {
			 $comment1 = htmlspecialchars($comment[$i]);
		$comment1 = htmlspecialchars(addslashes($comment1));
			 }
			 
				$code11=htmlspecialchars(addslashes($code11));
				$status1=htmlspecialchars(addslashes($status1));
				
				
				
		
		

				
	$ll='2';
	$mis_ref='0';

	
			   $adddef_sql1 =$db->prepare("update $schemas.objection_details set comment_registrar=?,userid=?,entry_date=now(),status_registrar=?,objection_sub_code=?,level_level=?,scrutiny_correction=?, completion_date = now() where filing_no=? and objection_code=? and miscellaneous_ref_no=? and form_type IS NULL");
	
			  $adddef_sql1->execute(array($comment1,$sessionUserType,$status1,$aa,$ll,$scrutiny_corr1,$filing_no,$code11,$mis_ref));	   		 
	
	
			   $adddef_sql1 =$db->prepare("insert into $schemas.objection_details_his (filing_no,comment_registrar,userid,entry_date,status_registrar,objection_code,objection_sub_code,level_level,scrutiny_correction) values(?,?,?,now(),?,?,?,?,?)");
					  
	$adddef_sql1->execute(array($filing_no,$comment1,$sessionUserType,$status1,$code11,$aa,$ll,$scrutiny_corr1));	
	$comment1="";
	
		 }
		 
	
		 /*------------------ End of common Loop for objection details entry -------------------*/

		 $aaq='Y';
         $kks='Y';
		 $ll='2';
		 //update scrutiny
		 
		 $st13 =$db->prepare("update $schemas.scrutiny set objection_status=?,compliance_date=?,defects=?,level_level=?,updated_at = now(), updated_by = ?, varifyed_userid = ? where filing_no=?");
		   $st13->bindParam(1, $kks, PDO::PARAM_STR);
		   $st13->bindParam(2, $notification_date1, PDO::PARAM_STR);
		   $st13->bindParam(3, $aaq, PDO::PARAM_STR);
		   $st13->bindParam(4, $ll, PDO::PARAM_STR);
		   $st13->bindParam(5, $sessionUserType, PDO::PARAM_STR);
		   $st13->bindParam(6, $sessionUserType, PDO::PARAM_STR);
		   $st13->bindParam(7, $filing_no, PDO::PARAM_STR);
		   $st13->execute();
//$st21c->execute(array($kks,$notification_date1,$aaq,$sessionUserType,$ll,$filing_no));

$st13=$db->prepare("insert into  $schemas.scrutiny_his (filing_no,defects,notification_date,user_id,level_level)
		   		values (?,?,?,?,?) ");
		   $st13->bindParam(1, $filing_no, PDO::PARAM_STR);
		   $st13->bindParam(2, $aaq, PDO::PARAM_STR);
		   $st13->bindParam(3, $notification_date1, PDO::PARAM_STR);
		   $st13->bindParam(4, $sessionUserType, PDO::PARAM_STR);
		   $st13->bindParam(5, $ll, PDO::PARAM_STR);
		   $st13->execute();


		   //-----------------------update document_upload------------------------------------------
		   $scr_display='0';
		   $scr_doc='0';
		   $scr_dis='1';
		   $doc_level='2';
		   
			   $st1x111 =$db->prepare("update document_upload set display=?,doc_level=?, scrutiny = 1  where  filing_no=? and scrutiny=? and display=? and (miscellaneous_ref_no IS NULL or miscellaneous_ref_no = '') ");
		   $st1x111->bindParam(1, $scr_display, PDO::PARAM_STR);
		   $st1x111->bindParam(2, $doc_level, PDO::PARAM_STR);
		   $st1x111->bindParam(3, $filing_no, PDO::PARAM_STR);
		   $st1x111->bindParam(4, $scr_doc, PDO::PARAM_STR);
		   $st1x111->bindParam(5, $scr_dis, PDO::PARAM_STR);		   
		   $st1x111->execute();
//------------------------update e_case_detail----------------------------------------------------

$query = "WITH RECURSIVE working_days AS (
    SELECT current_date::date AS check_date,
           0 AS working_day_count  
    UNION ALL
    SELECT (check_date + INTERVAL '1 day')::DATE, 
           working_day_count + CASE 
                                  WHEN (check_date + INTERVAL '1 day') IN (SELECT holiday_date FROM $schemas.holidays)
                                  THEN 0 
                                  ELSE 1
                                END
    FROM working_days
    WHERE working_day_count < 21  
)
SELECT TO_CHAR(check_date, 'DD/MM/YYYY')
FROM working_days
WHERE working_day_count = 21
LIMIT 1;
";
$next_date_query = $db->prepare($query);
$next_date_query->execute();
$next_date = $next_date_query->fetchColumn();

$allow_refiling_date = str_replace('/', '-', $next_date);
$allow_refiling_date = date('Y-m-d', strtotime($allow_refiling_date));


   $sccc='2';
   $reject_case_query = " ,  allow_refiling = 1, allow_refiling_date = '$allow_refiling_date' ";
   if($boofficefound == '1'){
   	$reject_case_query = " , rejected_from_scrutiny = 1, allow_refiling = 0 ";	
   }

if(!in_array(6,$cause_no)){
	$docs_defect = null;
}
$st1x =$db->prepare("update e_case_detail set scrutiny = 1, scrutiny_level = 2, is_defective = 1, defect_date = now(), scrutiny_status = ? , remodify_date = now(),  scrutiny_count = scrutiny_count+1, defect_docs = ? $reject_case_query where filing_no=? ");
$st1x->bindParam(1, $scrutiny_corr1, PDO::PARAM_STR);
$st1x->bindParam(2, $docs_defect, PDO::PARAM_STR);
$st1x->bindParam(3, $filing_no, PDO::PARAM_STR);
$st1x->execute();

//---------------mail and SMS server start------------------------------
//code for updating draft values
$status_ins = 'CM1';    //scrutiny done by ar
$display = 'FALSE';
$level_level = 1;       //ar level
$update_draft_display=$db->prepare("update $schemas.draft_objection_details set status=?,updated=?,display=? where filing_no=? and level_level=?");
            $update_draft_display->bindParam(1, $status_ins, PDO::PARAM_STR);
            $update_draft_display->bindParam(2, $server_date, PDO::PARAM_STR);
            $update_draft_display->bindParam(3, $display, PDO::PARAM_STR);
            $update_draft_display->bindParam(4, $filing_no, PDO::PARAM_STR);
            $update_draft_display->bindParam(5, $level_level, PDO::PARAM_STR);
            //$update_draft_display->execute();
 $no_of_days = 21;
 $sms_type = 2;
$listed_with_defect = 0;
 if($scrutiny_count >= 1){
 	$listed_with_defect = 1;
 	$query_select = "select list_with_defect from $schemas.case_detail where filing_no = ?";
	$check_case =$db->prepare($query_select);
	$check_case->bindParam(1, $filing_no, PDO::PARAM_STR);
	$check_case->execute();
	$check_case = $check_case->fetchColumn();
	if($check_case != '1'){

		if($user_scrutiny_count == '1' && $refile_count == '1' && $supply_disputed_questions == '2'){
			$email_text_footer = "";
			$heading = "list with defects";
			$up_scr=$db->prepare("update e_case_detail set allow_refiling=0, allow_refiling_date = null where filing_no = ?");
			$up_scr->bindParam(1, $filing_no, PDO::PARAM_STR);
			$up_scr->execute();
		}else if($user_scrutiny_count == '1' && $refile_count == '1' && $supply_disputed_questions == '1'){
			$email_text_footer = "";
			$heading = "list with defects";
			$up_scr=$db->prepare("update e_case_detail set allow_refiling=0, allow_refiling_date = null where filing_no = ?");
			$up_scr->bindParam(1, $filing_no, PDO::PARAM_STR);
			$up_scr->execute();
		}else{

	 	$get_case_detail = get_completed_case_detail($db,$location_access,$filing_no);
	 	$get_case_detail = array_shift($get_case_detail);
	 	$pet_name = get_party($db,$filing_no,$party_flag='P',$party_serial_no='1');
		$res_name = get_party($db,$filing_no,$party_flag='R',$party_serial_no='1');
		$case_year = date('Y');
		$regis_date = $entry_date;
		$extract_reg_no = substr($filing_no, 10, 6);
		$extract_reg_no = 'D'.$extract_reg_no;
		if($get_case_detail['list_with_defect'] != '1'){		
			$save_into_case_detail = save_defective_case_detail($db,$schemas,$filing_no,$get_case_detail,$pet_name,$res_name,$case_year,$extract_reg_no,$regis_date,$userid,$location_access);
		}
		$update_ecase_detail = update_defective_e_case_detail($db,$filing_no,$location_access);

		$email_text_footer = "";
		$heading = "list with defects";
		$sms_type = 14;
		}
	}
 }
// defective pdf and mail  start
list($Y,$m,$d) =explode('-',$cur_date);
$notification_date =$d.'/'.$m.'/'.$Y;
$datetime = date("d-m-Y h:i:s a");
	$subject="Status of Documents submitted filed under diary no ".$filing_no ;
 // $email_text1="The document(s) submitted under diary no ".$filing_no." are marked as ".$heading." on date :" .$notification_date.$email_text_footer." 
 //  This is a computer generated message, Please do not  reply"  ;
  
 //  $msg555=" The Document(s) submitted under diary no ".$filing_no." are marked as ".$heading." on date :".$notification_date." For more information kindly check mail on registered email address.";
if($listed_with_defect == '0'){
  $email_text1 = "The provisional acknowledgement no ".$filing_no." is marked as defective on date: ".$datetime.". Please find the list of defects as attached. The defect/s can be also be viewed in GSTAT login. Kindly remove the defect/s within:".$no_of_days." days and re-file the case.";
  $msg555 = "The provisional acknowledgement no ".$filing_no." is marked as defective on: ".$datetime.". Please find the list of defects as attached. The defect/s can be also be viewed in GSTAT login. Kindly remove the defect/s within:".$no_of_days." days and re-file the case. GSTAT-GSTN";
}else{
  	$email_text1="Your case with provisional acknowledgement no ".$filing_no." has been marked as list with defect on ".$datetime." . This is a computer-generated message, please do not reply. GSTAT-GSTN "  ;
				
	$msg555 = "Your case with provisional acknowledgement no ".$filing_no." has been marked as list with defect on ".$datetime." . This is a computer-generated message, please do not reply. GSTAT-GSTN";
}

  $pet_name = get_party($db,$filing_no,$party_flag='P',$party_serial_no='1');
  $res_name = get_party($db,$filing_no,$party_flag='R',$party_serial_no='1');
  $petitioner_info = get_party_completed_info($db,$filing_no,$party_flag='P',$party_serial_no='1');
  $case_type_detail = get_case_type_detail($db,$case_type);
  $case_type_detail = array_shift($case_type_detail);
  $display_scrutiny_date = display_date($cur_date,'dd.mm.yy');
 $display_fn = display_filing_no($filing_no);
 $defect_sheet_no = defect_no($filing_no);
 $bench_data = get_bench_name($db,$location_access);
 $bench_name = $bench_data['short_name'];
 $srno = $defect_sheet_no.'/'.'GSTAT/'.$bench_name.'/'.$curYear;
 $order_detail = get_order_detail($db,$filing_no);
        
 $order_no = $order_detail['order_number'];
 $order_date = (!empty($order_detail['order_date']))?$order_detail['order_date']:'';


 $defective_html = '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tribunal</title>
</head>

<body
    style="font-size:16px; font-family: Arial, Helvetica, sans-serif; line-height: 1.2; padding: 10px 40px; margin: 0;">
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p style="text-align:center; line-height: 0.5; font-size: 22px;"><b>GSTAT</b></p>
    <p style="text-align:center; line-height: 0.5; font-size: 22px;"><b>'.$_SESSION['bench_name'].'</b></p>
    <p>&nbsp;</p>
    <hr style="border-top: 1px solid black;">
    <div>
        <table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align:top; text-align:left; color:red;">
                        Sl. No. ' . $srno . '
                    </td>
                    <td style="vertical-align: top; text-align:right; color:red;">
                        Dated ' . date('d/m/Y') . '
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="text-align:center; line-height: 1.0; font-size: 18px;"><u><b>Notice</b></u></p>

        <p style="text-align:center; line-height: 1.0; font-size: 22px;margin-bottom: 0px;margin-top: 10px;;"><b> Filing No. ' . $filing_no . '</b></p>
        <table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; text-align:left;">
                        <p style="line-height: 1.2; font-size: 19px;"><b>' . $pet_name . '</b></p>
                    </td>
                    <td style="vertical-align: top; text-align: right;">
                        <p style="line-height: 0.5; font-size: 14px; margin-bottom: 10px;">... Appellant/Applicant</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p style="text-align:center; line-height: 1.0;">VS</p>
        <table style="line-height: 1.2; margin-bottom: 10px; margin-top: 10px; width: 100%;">
            <tbody>
                <tr>
                    <td style="vertical-align: top; text-align: left;">
                        <p style="line-height: 1.2; font-size: 19px;"><b>' . $res_name . '</b></p>
                    </td>
                    <td style="vertical-align: top; text-align: right;">
                        <p style="line-height: 0.5; font-size: 14px; margin-bottom: 10px;">..........Respondent</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="text-align: justify; margin-top: 30px;">
            <p style="font-size: 18px; line-height: 1.4;"><b>To,<br> ' . $pet_name . '</b></p>
            <p style="line-height: 1.4;">Subject- Appeal before Goods and services Tax Appellate Tribunal constituted
                under section 109 of the Central Goods and Services Tax Act, 2017 filed against the order no
                '.$order_no.' dated '.$order_date.' passed by Appellate/Revisional Authority under Section 107/108 of
                the Act-Regarding </p>
            <p> You are hereby informed that upon scrutiny of the above noted appeal/application filed by you in terms of
                rule ____ of the Goods and Services Tax Appellate Tribunal (Procedure) Rules, 2024, the following
                defects have been noted: -</p>
            <p style="color:red;text-align: center;">Defect List</p>
            <table style="border-collapse: collapse; border: 2px solid black;">';
    $sr = 1;
	$cm = 0;
	for($i=0;$i<$len;$i++)
	{  
		$status1=htmlspecialchars($status[$i]);
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
			 $comment1 = htmlspecialchars($comment[$cm]);
	//		  $comment1 = htmlspecialchars(addslashes($comment1));
			  $aas='1';
		if(trim($status1 == 'NO')){
			if($case_type == '1'){
				$query = "select check_list from check_list_local where id = ?";
			}else{
				$query = "select check_list from check_list_local_ia where id = ?";
			}
			$check_list=$db->prepare($query);
			$check_list->bindParam(1, $code11, PDO::PARAM_STR);
			$check_list->execute();
			$check_list_point = $check_list->fetchColumn();
		$defective_html .= '<tr style="border: 1px solid black;">
                    <td style="border: 1px solid black; padding:5px;">'.$sr.'.</td>
                    <td style="border: 1px solid black; padding:5px;">'.$check_list_point.'.</td>
                    <td style="border: 1px solid black; padding:5px;">'.$comment1.'</td>
                </tr>';
		$sr++;
		}
		$comment1 = '';
		$cm++;
	}
    $defective_html .= '</table><p>The aforesaid defects have also been communicated to you on the copy/link sent to you on your email/phone.</p>';

    if($listed_with_defect == '0'){

        $defective_html .=  '<p>You are hereby directed to remove the said defects and re-submit the said appeal/application on the portal within 21 days of the date of this notice/on or before '.$next_date.', failing which the said appeal/application is liable to be rejected</p>';
            }
        $defective_html .= '<table style="line-height: 1.2; margin-bottom: 15px; margin-top: 50px; width: 100%;">
            <tbody>
                <tr> 
                    <td style="vertical-align: top; text-align: right;">
                        <p style="line-height: 1.4;"><b>Registrar/ Add Registrar/ Joint Registrar :
                                '.$_SESSION['user_actual_name'].'<br><span
                                    style="white-space: nowrap; font-size: 14px;">GSTAT,</span><br>
                                '.$_SESSION['bench_name'].' </b></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>';


	$time = time();				
	$pdf_file_name=$filing_no."-".$time;
  $filename=$pdf_file_name.".pdf";
  $filename_sms = $pdf_file_name;
 $dompdf->loadHtml($defective_html);
  $dompdf->setPaper('A4');
  $dompdf->render();
  $outputff = $dompdf->output();
  $upload_dir = "Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/defects/$filing_no";
  $pp_path = $upload_dir."/$filename";
  $save_path = $upload_dir."/$filename";
 //  if (!file_exists($save_path)) {
	// 	mkdir($upload_dir, 0777, true);
	// }
 //  $save_apl = file_put_contents($pp_path, $outputff);

  if($boofficefound == '1'){
  	// apl 02A part B start
	$crn_detail = get_crn_detail($db,$filing_no);
	$crn_number = $crn_detail['crn_number'];
	$filed_date = $crn_detail['filed_date'];
	$order_number = $crn_detail['order_number'];
	$e_reference_no = $crn_detail['e_reference_no'];

	$pdf_html = '<div style="text-align:center;"><p>Form GST APL-02A Part B</p><p><b>Final Acknowledgement for registration of Appeal/Application</b></p><p>Your Appeal/application filed vide provisional acknowledgment reference number '.$filing_no.' dated '.date('d/m/Y',strtotime($filed_date)).' has been Case Deferred due to Wrong Jurisdiction</p></div><table style="width:100%"><tr><td><b>Date of Deferred:  '.date('d/m/Y').'</b></td><td style="text-align:right"><b>AR/JR/DR/R<br/>GSTAT: '.$bench_data['city_name'] .' Bench</b></td></tr></table>';

			$dompdf2 = new Dompdf();
	     	$time = time();				
			$pdf_file_name=$filing_no."-apl02A-".$time;
		  $filename=$pdf_file_name.".pdf";
		  $filename_sms = $pdf_file_name;
		 $dompdf2->loadHtml($pdf_html);
		  $dompdf2->setPaper('A4');
		  $dompdf2->render();
		  $outputff = $dompdf2->output();
		  $upload_dir = "Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/apl02A/$filing_no";
		  $pp_path = $upload_dir."/$filename";
		  $save_path = $upload_dir."/$filename";
		  if (!file_exists($save_path)) {
				mkdir($upload_dir, 0777, true);
			}
		  $save_file = file_put_contents($pp_path, $outputff);
		  $save_apl = $s3Service->uploadDynamicFile($outputff, $save_path);
		  
		  if(!$save_apl)
		  {
			$db->rollBack();
			$message = 'There are some problem in saving apl02A part B';
			$msg=base64_encode($filing_no);
			$msg1=htmlspecialchars($message.'-'.$msg);
			$hash=base64_encode($msg1);
			$msg3=base64_encode($form_status);
			header("Location:../index.php?hash=$hash&hash2=$msg3");
			die;
		  }
		  $pdf_hash = hash_file('sha256', $pp_path);
		$apl_form= $db->prepare("update e_case_detail set apl_02b_form_path = ?, apl_02b_accept_reject = 4, pdf_hash = ?,accepted_rejected_at = now()  where filing_no = ?");
		$apl_form->bindParam(1, $pp_path, PDO::PARAM_STR);
		$apl_form->bindParam(2, $pdf_hash, PDO::PARAM_STR);
		$apl_form->bindParam(3, $filing_no, PDO::PARAM_STR);
		$apl_form->execute();

		$today_date = date('Y-m-d');
		$doctype = 8;
		$subdoctype = 178;
		$docum_type = "APL02_REJECTED";
		$party_name = "apl02";
		$doc_level = 9;
		$save_doc = save_document_uplaod($db,$doctype,$pp_path,$sessionUserType,$subdoctype,$e_reference_no,$filename,$docum_type,'',$filename,$filing_no,true,1,$party_name,$list_date,'A');
		unlink($pp_path);
		$url = "http://10.193.85.11/efiling/getdataapl02b.drt?filingNo=$filing_no&schema=$schemas";
		callApiAsync($url);

   		// apl 02A part B end
  }
  
  
  $save_apl = $s3Service->uploadDynamicFile($outputff, $save_path);
 
  if(!$save_apl)
  {
	$db->rollBack();
	$message = 'There are some problem in saving defect sheet';
	$msg=base64_encode($filing_no);
	$msg1=htmlspecialchars($message.'-'.$msg);
	$hash=base64_encode($msg1);
	$msg3=base64_encode($form_status);
	header("Location:../index.php?hash=$hash&hash2=$msg3");
	die;
  }

 $up_scr=$db->prepare("update $schemas.scrutiny set defect_pdf_path=? where filing_no = ?");
	$up_scr->bindParam(1, $save_path, PDO::PARAM_STR);
	$up_scr->bindParam(2, $filing_no, PDO::PARAM_STR);
	$up_scr->execute();
	$mail_shoot = fn_sms($db, $sms_type, $save_path, $filing_no, $subject, $msg555, $email_text1,$save_path);


//mail and SMS server END

$db->commit();


 

echo $message = 'Scrutiny Done Successfully With Defect.....';
$msg=base64_encode($filing_no);
$msg1=htmlspecialchars($message.'-'.$msg);
$hash=base64_encode($msg1);

$msg3=base64_encode($form_status);




header("Location:../index.php?hash=$hash&hash2=$msg3");
unset($_SESSION['form2_scruniny']);
die(); 
}catch (PDOException $e) {
	$db->rollBack();
	
	$e->getMessage();
		//throw $e;
	
	$message = 'There is some problem while doing scrutiny...<br>. '.$e->getMessage();
	$msg=base64_encode($filing_no);
	$msg1=htmlspecialchars($message.'-'.$msg);
	$hash=base64_encode($msg1);
	$msg3=base64_encode($form_status);

	echo $message;
	
	//$hash2=base64_encode($msg3);
	
	//header("Location:../index.php?hash=$hash&hash2=$msg3");
	//unset($_SESSION['form2_scruniny']);
	die();
	}

	}
	/*---------------------------- End of Fresh Case Condition -------------------------------*/

}
echo 'Invalid Entry.......';
header("Location:../index.php");
}
?>
