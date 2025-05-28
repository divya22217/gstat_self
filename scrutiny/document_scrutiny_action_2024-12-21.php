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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 

header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
include("../custom/custom_function.php");
 require "../vendor/autoload.php";
use Dompdf\Dompdf;
$dompdf = new Dompdf();


$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$username = $_SESSION['actual_username'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
$location_access = $_SESSION['location'];

if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
echo "Access Problem....."; 
session_unset();     // unset $_SESSION variable for the run-time
session_destroy();
header("Location: ../login.php?aa=100");
die();
}

//echo "<pre>"; print_r($_REQUEST); die;

 $form_status = htmlspecialchars($_REQUEST['form_status']);

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
	
	try	{
	$db->beginTransaction();  // begin transaction
	
// This code not use next time .......	Schema session create Hear....
	
	
$sessionUserType=htmlspecialchars($_SESSION['id']);


$curYear = htmlspecialchars(date("Y"));
$curMonth = htmlspecialchars(date("m"));
$curDay = htmlspecialchars(date("d"));
$cur_date = "$curYear-$curMonth-$curDay";
$cur_date1 ="$curDay/$curMonth/$curYear";


$schemas=htmlspecialchars($_SESSION['schema_name']);
$entry_date = htmlspecialchars(date("F j, Y g:i a"));

//$filing_no = htmlspecialchars($_REQUEST['filing_no']);
$docoded_hash = base64_decode(htmlspecialchars($_REQUEST['filing_no_next']));

list($miscellaneous_no,$filing_no,$hash) = explode("-",$docoded_hash);

$coulfil=strlen($filing_no);

/* if($coulfil !='16')
{
	print "Filing No Not Right";
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	header("Location: ../login.php?aa=100");
	die();	
} */

$notification_date = $_REQUEST['notification_date'];

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
 
 
$st1=$db->prepare("select case_type from $schemas.case_detail where filing_no =? ");
$st1->bindParam(1, $filing_no, PDO::PARAM_STR);
$st1->execute();
$case_type_edetail= $st1->fetchColumn();
$case_type=$case_type_edetail;
 

if($searchby == '2')
{
	


	$ll='11';

list($dd,$mm,$yy) = explode("/", $notification_date);
$noti = htmlspecialchars($yy."-".$mm."-".$dd);

$status1=$_REQUEST['test'];
rtrim($status1,",");
$status=explode(",",$status1);
$comment=$_REQUEST['comment'];
$code1=$_REQUEST['id_check'];

$len=htmlspecialchars(count($_REQUEST['id_check']));

$count_scrutiny = $db->prepare("select max(count_scrutiny) from $schemas.document_objection_details where filing_no =?");
  $count_scrutiny->bindParam(1, $filing_no, PDO::PARAM_STR);
 
  $count_scrutiny->execute();

  $count_scrutiny->execute();
  $count_scrutiny = $count_scrutiny->fetchColumn();
  if($count_scrutiny == "NULL" || $count_scrutiny == ""){
	$count_scrutiny = 1;
  }else{
  $count_scrutiny = $count_scrutiny+1;	
  }


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


  


$adddef_sql = "insert into $schemas.document_objection_details (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,completed_flag,level_level,count_scrutiny,scrutiny_type,miscellaneous_no,notification_date) values
(?,?,?,?,now(),?,?,?,?,?,?,?,?,now())";
$sthaqq = $db->prepare($adddef_sql);
 
$ss='11';
$yes='Y';

$scrutiny_type = 'D';




$sthaqq->execute(array($filing_no,$code11,$comment1,$sessionUserType,$status1,
$case_type,$aa,$yes,$ss,$count_scrutiny,$scrutiny_type,$miscellaneous_no));



$adddef_sql1 = "insert into $schemas.document_objection_details_his (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,completed_flag,level_level,scrutiny_type,miscellaneous_no,notification_date) values
(?,?,?,?,now(),?,?,?,?,?,?,?,now())";
$sthaqq1 = $db->prepare($adddef_sql1);


	$llp='11';
$yes='Y';


$sthaqq1->execute(array($filing_no,$code11,$comment1,$sessionUserType,$status1,
$case_type,$aa,$yes,$llp,$scrutiny_type,$miscellaneous_no));
}


$scrutiny_dc='0';
$scr_display='1';
//$scr='0';
//$disp='1';
$doc_level='11';



$obj_status='N';
$doc_level='11';

$st13=$db->prepare("insert into  $schemas.scrutiny_doc (filing_no,notification_date,user_id,objection_status,defects,level_level,miscellaneous_no)
		values (?,?,?,?,?,?,?) ");
$st13->bindParam(1, $filing_no, PDO::PARAM_STR);
$st13->bindParam(2, $notification_date1, PDO::PARAM_STR);
$st13->bindParam(3, $sessionUserType, PDO::PARAM_STR);
$st13->bindParam(4, $obj_status, PDO::PARAM_STR);
$st13->bindParam(5, $obj_status, PDO::PARAM_STR);
$st13->bindParam(6, $doc_level, PDO::PARAM_STR);
$st13->bindParam(7, $miscellaneous_no, PDO::PARAM_STR);
$st13->execute();

	$scrutiny_dc='0';
	$scrutiny_update = '1';
	$scr_display='1';
	$doc_level='1';
	$doc_flag = '1';

		$st1x111 =$db->prepare("update document_upload set doc_level=? where  miscellenous_no=? and scrutiny=? and display=? and doc_flag = ? ");
	$st1x111->bindParam(1, $doc_level, PDO::PARAM_STR);
	$st1x111->bindParam(2, $miscellaneous_no, PDO::PARAM_STR);
	$st1x111->bindParam(3, $scrutiny_dc, PDO::PARAM_STR);
	$st1x111->bindParam(4, $scr_display, PDO::PARAM_STR);
	$st1x111->bindParam(5, $doc_flag, PDO::PARAM_STR);
	$st1x111->execute();

// $get_case_info=$db->prepare("select case_type,filing_no,main_case_ia_no from $schemas.case_detail where filing_no=?");
// 	$get_case_info->bindParam(1, $filing_no, PDO::PARAM_STR);
// 	$get_case_info->execute();
// 	$get_case_info = $get_case_info->fetchAll();
// 	$get_case_info = array_shift($get_case_info);
// 	$scrutinized_filing_no = $get_case_info['filing_no'];
// 	$filing_no_ia_ma = $get_case_info['main_case_ia_no'];
	
// 	if($filing_no_ia_ma == ''){
// 		$final_filing_no = $scrutinized_filing_no;
// 	}else{
// 		$final_filing_no = $filing_no_ia_ma;
// 	}
//mail and SMS server END
echo $message = "Scrutiny Done without defect and frowarded to AR";
$msg=$filing_no;
//$msg1=htmlspecialchars($message.'||'.$msg);
$msg1=$message.'||'.$msg;
$hash=base64_encode($msg1);

$msg3=base64_encode($form_status);

list($Y,$m,$d) =explode('-',$cur_date);
$notification_date =$d.'/'.$m.'/'.$Y;

// $subject="Status of Documents filed under diary no ".$filing_no;

// $email_text="The document submitted under diary no ".$filing_no." with misc number ".$miscellaneous_no." are marked as defect free on date :" .$notification_date." This is a computer generated message, Please do not reply "  ;
// $msg555="The Document(s) submitted under diary no ".$filing_no." with misc number ".$miscellaneous_no." are marked as defect free on date :".$notification_date.".";


// $sdsdsds = fn_sms($db, '11', '', $final_filing_no, $subject, $msg555, $email_text);


}







 if($searchby == '1')
{ 


	$st1=$db->prepare("select case_type from $schemas.case_detail where filing_no =? ");
	$st1->bindParam(1, $filing_no, PDO::PARAM_STR);
	$st1->execute();
	$case_type_edetail= $st1->fetchColumn();
	$case_type=$case_type_edetail;
	

	$status1=$_REQUEST['test'];
	rtrim($status1,",");
	$status=explode(",",$status1);

	//$status=$_REQUEST['status'];
	$comment=$_REQUEST['comment'];
	$code1=$_REQUEST['id_check'];
	
	$iii=0;
	 $len=htmlspecialchars(count($_REQUEST['id_check']));
	 
	 $count_scrutiny = $db->prepare("select max(count_scrutiny) from $schemas.document_objection_details where filing_no =?");
  $count_scrutiny->bindParam(1, $filing_no, PDO::PARAM_STR);
 
  $count_scrutiny->execute();

  $count_scrutiny->execute();
  $count_scrutiny = $count_scrutiny->fetchColumn();
  if($count_scrutiny == "NULL" || $count_scrutiny == ""){
	$count_scrutiny = 1;
  }else{
  $count_scrutiny = $count_scrutiny+1;	
  }
	
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
	//}
	
	
	$scrutiny_type = 'D';	

		   $adddef_sql1 =$db->prepare("insert into $schemas.document_objection_details (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,level_level,count_scrutiny,scrutiny_type,miscellaneous_no,notification_date) values 
(?,?,?,?,now(),?,?,?,?,?,?,?,now())");

	$aas='11';

$adddef_sql1->execute(array($filing_no,$code11,$comment1,$sessionUserType,$status1,
$case_type,$aa,$aas,$count_scrutiny,$scrutiny_type,$miscellaneous_no));

	
		   $adddef_sql11 =$db->prepare("insert into $schemas.document_objection_details_his (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,level_level,scrutiny_type,miscellaneous_no,notification_date) values 
(?,?,?,?,now(),?,?,?,?,?,?,now())");


	$llps='11';

		 
	
$adddef_sql11->execute(array($filing_no,$code11,$comment1,$sessionUserType,$status1,
$case_type,$aa,$llps,$scrutiny_type,$miscellaneous_no));
$iii++;
$comment1="";
}
 	
 	

	$aaq='Y';
$kks='Y';
$ll='11';




	 $ll='11';
		   $obj_st='Y';
		   $def='Y';

	
		   
		   
$scrutiny_dc='0';
$scr_display='1';
$doc_level='11';
$doc_flag = 1;
$obj_status='Y';


$st13=$db->prepare("insert into  $schemas.scrutiny_doc (filing_no,notification_date,user_id,objection_status,defects,level_level,miscellaneous_no)
		values (?,?,?,?,?,?,?) ");
$st13->bindParam(1, $filing_no, PDO::PARAM_STR);
$st13->bindParam(2, $notification_date1, PDO::PARAM_STR);
$st13->bindParam(3, $sessionUserType, PDO::PARAM_STR);
$st13->bindParam(4, $obj_status, PDO::PARAM_STR);
$st13->bindParam(5, $obj_status, PDO::PARAM_STR);
$st13->bindParam(6, $doc_level, PDO::PARAM_STR);
$st13->bindParam(7, $miscellaneous_no, PDO::PARAM_STR);
$st13->execute();

	$scrutiny_dc='0';
	$scr_display='1';
	$scrutiny_update = '1';
	$scrutiny_display = '0';
	$doc_level='1';
	$doc_flag = 1;

		$st1x111 =$db->prepare("update document_upload set doc_level=? where  miscellenous_no=?  and scrutiny=? and display=? and doc_flag = ?");
	$st1x111->bindParam(1, $doc_level, PDO::PARAM_STR);
	$st1x111->bindParam(2, $miscellaneous_no, PDO::PARAM_STR);
	$st1x111->bindParam(3, $scrutiny_dc, PDO::PARAM_STR);
	$st1x111->bindParam(4, $scr_display, PDO::PARAM_STR);
	$st1x111->bindParam(5, $doc_flag, PDO::PARAM_STR);
	$st1x111->execute();
	

// $get_case_info=$db->prepare("select case_type,filing_no,main_case_ia_no from $schemas.case_detail where filing_no=?");
// 	$get_case_info->bindParam(1, $filing_no, PDO::PARAM_STR);
// 	$get_case_info->execute();
// 	$get_case_info = $get_case_info->fetchAll();
// 	$get_case_info = array_shift($get_case_info);
// 	$scrutinized_filing_no = $get_case_info['filing_no'];
// 	$filing_no_ia_ma = $get_case_info['main_case_ia_no'];
	
// 	if($filing_no_ia_ma == ''){
// 		$final_filing_no = $scrutinized_filing_no;
// 	}else{
// 		$final_filing_no = $filing_no_ia_ma;
// 	}

		   
$message = 'Scrutiny Done With Defect and forwarded to AR';
$msg=base64_encode($filing_no);
$msg1=$message.'||'.$msg;
$hash=base64_encode($msg1);

$msg3=base64_encode($form_status);

// $subject="Status of Documents submitted filed under diary no ".$filing_no ;
// $email_text1="The document(s) submitted under diary no ".$filing_no." with misc number ".$miscellaneous_no." are marked as defective on date :" .$notification_date." Kindly check the attachment for removing the defects raised. You are required to submit the corrected documents as soon as possible.
//   This is a computer generated message, Please do not  reply"  ;
  
// $msg555="The Document(s) submitted under diary no ".$filing_no." with misc number ".$miscellaneous_no." are marked as defective on date :".$notification_date.".";

// $sdsdsds = fn_sms($db, '8', $filename_sms, $final_filing_no, $subject, $msg555, $email_text1,$up_path);
	
	
}
$db->commit();

header("Location:../scrutiny/document_scrutiny.php?hash=$hash&hash2=$msg3");
	unset($_SESSION['form2_scruniny']);

}catch(Exception $e){
	$db->rollBack();
	echo $e;
	//header("Location:../scrutiny/document_scrutiny1.php");
}
}

?>
