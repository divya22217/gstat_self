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

date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
session_start();

$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];

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

include '../db_inc2.php';


$schemas=htmlspecialchars($_SESSION['schema_name']);
$entry_date = htmlspecialchars(date("F j, Y g:i a"));



$filing_no = htmlspecialchars($_REQUEST['filing_no']);

$coulfil=strlen($filing_no);

if($coulfil !='16')
{
	print "Filing No Not Right";
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	header("Location: ../login.php?aa=100");
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

if($searchby == '2')
{
	
	$ll='1';
$st1=$db->prepare("update $schemas.scrutiny set compliance_date =?,user_id=?,level_level=? where filing_no =? "); 
$st1->bindParam(1, $notification_date1, PDO::PARAM_STR);
$st1->bindParam(2, $sessionUserType, PDO::PARAM_STR);
$st1->bindParam(3, $ll, PDO::PARAM_STR);
$st1->bindParam(4, $filing_no, PDO::PARAM_STR);
$st1->execute();

$st1=$db->prepare("select case_type from  e_case_detail_local where filing_no =? ");
$st1->bindParam(1, $filing_no, PDO::PARAM_STR);
$st1->execute();
$case_type_edetail= $st1->fetchColumn();

$case_type=$case_type_edetail;

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


$adddef_sql = "update $schemas.objection_details set comments=?,userid=?,entry_dt=?,status=?,"
        . "case_type=?,objection_sub_code=?,completed_flag=?,level_level=? where filing_no=? and objection_code=?";
$sthaqq = $db->prepare($adddef_sql);
//$aa='0';
$yes='N';
$ll='1';
$sthaqq->execute(array($comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$yes,$ll,$filing_no,$code11));


$adddef_sql = "insert into $schemas.objection_details_his (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,completed_flag,level_level) values
(?,?,?,?,?,?,?,?,?,?)";
$sthaqq = $db->prepare($adddef_sql);
//$aa='0';
$yes='N';
$ll='1';
$sthaqq->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$yes,$ll));
$comment1="";
}



	$st21s =$db->prepare("update $schemas.scrutiny set objection_status=?,notification_date=?,defects=? where filing_no=? ");
	$obj_st='N';
	$def='N';
$st21s->bindParam(1, $obj_st, PDO::PARAM_STR);
$st21s->bindParam(2, $notification_date1, PDO::PARAM_STR);
$st21s->bindParam(3, $def, PDO::PARAM_STR);
$st21s->bindParam(4, $filing_no, PDO::PARAM_STR);
$st21s->execute();



//done ................



 $sccc='1';
$st1x =$db->prepare("update e_case_detail set scrutiny_comp = ? where filing_no=? ");
$st1x->bindParam(1, $sccc, PDO::PARAM_STR);
$st1x->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x->execute();

$sccc='1';
$st1x =$db->prepare("update e_case_detail_local set scrutiny_comp = ? where filing_no=? ");
$st1x->bindParam(1, $sccc, PDO::PARAM_STR);
$st1x->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x->execute();


$sccc='1';
$st1x =$db->prepare("update $schemas.scrutiny set scrutinu_comp = ? where filing_no=? ");
$st1x->bindParam(1, $sccc, PDO::PARAM_STR);
$st1x->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x->execute();

$ll='0';
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


echo $message = 'Scrutiny Done Successfully Without Defect And Forword To Asst./Dy. Registrar';
$msg=base64_encode($filing_no);
$msg1=htmlspecialchars($message.'-'.$msg);
$hash=base64_encode($msg1);

header("Location:./defect_cases_more.php?hash=$hash");
unset($_SESSION['form2_scruniny']);

die();
}


 if($searchby == '1')
{ 
	
	$st1=$db->prepare("select count(filing_no) from  $schemas.case_detail where filing_no =? ");
	$st1->bindParam(1, $filing_no, PDO::PARAM_STR);
	$st1->execute();
	$ccount_filing_no= $st1->fetchColumn();
	
	
	
	$st1=$db->prepare("select case_type from e_case_detail_local where filing_no =? ");
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
		
$adddef_sql = "update $schemas.objection_details set comments=?,userid=?,entry_dt=?,status=?,"
        . "case_type=?,objection_sub_code=?,completed_flag=?,level_level=? where filing_no=? and objection_code=?";
$sthaqq = $db->prepare($adddef_sql);
//$aa='0';
$yes='N';
$ll='1';
$sthaqq->execute(array($comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$yes,$ll,$filing_no,$code11));

$adddef_sql = "insert into $schemas.objection_details_his (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,completed_flag,level_level) values
(?,?,?,?,?,?,?,?,?,?)";
$sthaqq = $db->prepare($adddef_sql);
//$aa='0';
$yes='N';
$ll='1';
$sthaqq->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$yes,$ll));
$comment1="";
 	}
 	

$aaq='Y';
$kks='Y';
$ll='1';


	$st13 =$db->prepare("update $schemas.scrutiny set objection_status=?,
			notification_date=?
	,defects=?,user_id=?,level_level=? where filing_no=?");
		  
		   $st13->bindParam(1, $kks, PDO::PARAM_STR);
		   $st13->bindParam(2, $notification_date1, PDO::PARAM_STR);
		   $st13->bindParam(3, $aaq, PDO::PARAM_STR);
		   $st13->bindParam(4, $sessionUserType, PDO::PARAM_STR);
		   $st13->bindParam(5, $ll, PDO::PARAM_STR);
		   $st13->bindParam(6, $filing_no, PDO::PARAM_STR);
		   $st13->execute();
//$st21c->execute(array($kks,$notification_date1,$aaq,$sessionUserType,$ll,$filing_no));
	
		   $ll='0';
		   $obj_st='Y';
		   $def='Y';
		   $st13=$db->prepare("insert into  $schemas.scrutiny_his (filing_no,defects,notification_date,user_id,level_level)
		   		values (?,?,?,?,?) ");
		   $st13->bindParam(1, $filing_no, PDO::PARAM_STR);
		   $st13->bindParam(2, $def, PDO::PARAM_STR);
		   $st13->bindParam(3, $notification_date1, PDO::PARAM_STR);
		   $st13->bindParam(4, $sessionUserType, PDO::PARAM_STR);
		   $st13->bindParam(5, $ll, PDO::PARAM_STR);
		   $st13->execute();


echo $message = ' Scrutiny Done Successfully With Defect !!!!! ';
$msg=base64_encode($filing_no);
$msg1=htmlspecialchars($message.'-'.$msg);
$hash=base64_encode($msg1);
header("Location:./defect_cases_more.php?hash=$hash");
unset($_SESSION['form2_scruniny']);
die();     	
	
}
echo 'Invalid Entry.......';
header("Location:../index.php");
}
?>
