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

$form_status = htmlspecialchars($_REQUEST['form_status']);

date_default_timezone_set("Asia/Kolkata");
include("../custom/custom_function.php");
require_once('../object_storage/S3Service.php');
 require "../vendor/autoload.php";
 
use Dompdf\Dompdf;
$dompdf = new Dompdf();
 

include("../db_inc1.php");


session_start();
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); 
$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
$datetime = date("d-m-Y h:i:s a");
$username = $_SESSION['actual_username'];
$days = 7;

if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
echo "Access Problem....."; 
session_unset();     // unset $_SESSION variable for the run-time
session_destroy();
header("Location: ../login.php?aa=100");
die();
}
/*
if($main_id =='9999' and $localadmin =='0')
{
		if($_SESSION['menuaccess_codeall'] !='2')
			{
				session_unset();     // unset $_SESSION variable for the run-time
				session_destroy();
				echo "You Are Not Access This Page......";
				header("Location: ../login.php?aa=100");
				die();
			}

}
*/
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
  
// $filing_no = htmlspecialchars($_REQUEST['filing_no']);
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

if (!is_numeric($filing_no))
{
	print "Filing No Not Empty";
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	header("Location: ../login.php?aa=100");
	die();

}

 $notification_date = $_REQUEST['notification_date'];

$notification_date1=date('Y-m-d h:i:s');
list($day,$month,$year)=explode('/',$notification_date);
  $notification_date2=$year.'-'.$month.'-'.$day;

 
  $comment = htmlspecialchars(htmlentities($_REQUEST['comment']));


if($searchby == '2')
{
	

	$doc_sc='1';

	$ef_sc='0';
$ef_dis='1';

$doc_level='2';
$doc_flag = 1;
$docs_defect = null;
//$doc_level_user='1';


$st12x =$db->prepare("update document_upload set scrutiny=?,doc_level=?, defect_docs = ? where miscellenous_no=? and scrutiny=? and display=? and doc_flag = ?");
$st12x->bindParam(1, $doc_sc, PDO::PARAM_STR);
$st12x->bindParam(2, $doc_level, PDO::PARAM_STR);
$st12x->bindParam(3, $docs_defect, PDO::PARAM_STR);
$st12x->bindParam(4, $miscellaneous_no, PDO::PARAM_STR);
$st12x->bindParam(5, $ef_sc, PDO::PARAM_STR);
$st12x->bindParam(6, $ef_dis, PDO::PARAM_STR);
$st12x->bindParam(7, $doc_flag, PDO::PARAM_STR);
//$st12x->bindParam(4, $ef_dis, PDO::PARAM_STR);
$st12x->execute();



$st1=$db->prepare("select a.case_type,a.case_no,a.case_year,c.short_name as loc_short_name,b.short_name from $schemas.case_detail as a 
		left join case_type as b on b.id = a.case_type
		left join mater_location_city as c on c.city_id = a.location_code
		where a.filing_no =? ");
	$st1->bindParam(1, $filing_no, PDO::PARAM_STR);
	$st1->execute();
	$case_info= $st1->fetch();
	$case_type=$case_info->case_type;
	$case_number = $case_info->short_name."/".$case_info->case_no."/".$case_info->loc_short_name."/".$case_info->case_year;

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
	
	
$status1=htmlspecialchars($status[$i]);
if($comment[$i]!="")

{
	$comment1 = htmlspecialchars($comment[$i]);
}
//$comment1 = htmlspecialchars($comment[$i]);

$code11=htmlspecialchars(addslashes($code11));
$status1=htmlspecialchars(addslashes($status1));
$comment1 = htmlspecialchars(addslashes($comment1));

$adddef_sql = "update $schemas.document_objection_details set comment_registrar=?,userid=?,entry_dt=now(),status_registrar=?,"
        . "case_type=?,objection_sub_code=?,completed_flag=?,level_level=?,completion_date = now() where miscellaneous_no=? and objection_code=?";

$sthaqq = $db->prepare($adddef_sql);
//$aa='0';


$yes='Y';

$sthaqq->execute(array($comment1,$sessionUserType,$status1,
$case_type,$aa,$yes,$doc_level,$miscellaneous_no,$code11));

$adddef_sql = "insert into $schemas.document_objection_details_his (filing_no,objection_code,
comment_registrar,userid,entry_dt,status_registrar,case_type,objection_sub_code,completed_flag,level_level,miscellaneous_no,completion_date) values
(?,?,?,?,now(),?,?,?,?,?,?,now())";
$sthaqq = $db->prepare($adddef_sql);
//$aa='0';


$yes='Y';

$sthaqq->execute(array($filing_no,$code11,$comment1,$sessionUserType,$status1,
$case_type,$aa,$yes,$doc_level,$miscellaneous_no));
$comment1="";
}

	$ll='22';
	$obj_st='N';
	$def='N';
	$sccc='1';

$st1=$db->prepare("update $schemas.scrutiny_doc set compliance_date =?,level_level=?,varifyed_userid=?,objection_status=?,defects=?,scrutinu_comp=?, updated_at = now(), scrutiny_count=scrutiny_count+1 where miscellaneous_no =? ");
$st1->bindParam(1, $notification_date1, PDO::PARAM_STR);
$st1->bindParam(2, $doc_level, PDO::PARAM_STR);
$st1->bindParam(3, $sessionUserType, PDO::PARAM_STR);
$st1->bindParam(4, $obj_st, PDO::PARAM_STR);
$st1->bindParam(5, $def, PDO::PARAM_STR);
$st1->bindParam(6, $sccc, PDO::PARAM_STR);
$st1->bindParam(7, $miscellaneous_no, PDO::PARAM_STR);
$st1->execute();



/* $doc_scrutiny_group='1';
$st1x =$db->prepare("update $schemas.case_allocated_group set document_scrutiny	 = ? where filing_no=? ");
$st1x->bindParam(1, $doc_scrutiny_group, PDO::PARAM_STR);
$st1x->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x->execute(); */


$obj_st='N';
$def='N';


$st13=$db->prepare("insert into  $schemas.scrutiny_doc_his (filing_no,defects,notification_date,user_id,level_level,miscellaneous_no,entry_date)
		values (?,?,?,?,?,?,now()) ");
$st13->bindParam(1, $filing_no, PDO::PARAM_STR);
$st13->bindParam(2, $def, PDO::PARAM_STR);
$st13->bindParam(3, $notification_date1, PDO::PARAM_STR);
$st13->bindParam(4, $sessionUserType, PDO::PARAM_STR);
$st13->bindParam(5, $doc_level, PDO::PARAM_STR);
$st13->bindParam(6, $miscellaneous_no, PDO::PARAM_STR);
$st13->execute();

$get_case_info=$db->prepare("select case_type,filing_no,main_case_ia_no from $schemas.case_detail where filing_no=?");
$get_case_info->bindParam(1, $filing_no, PDO::PARAM_STR);
$get_case_info->execute();
$get_case_info = $get_case_info->fetchAll();
$get_case_info = array_shift($get_case_info);
$scrutinized_filing_no = $get_case_info['filing_no'];
$filing_no_ia_ma = $get_case_info['main_case_ia_no'];

if($filing_no_ia_ma == ''){
	$final_filing_no = $scrutinized_filing_no;
}else{
	$final_filing_no = $filing_no_ia_ma;
}


//mail and SMS server END
echo $message = "Scrutiny Done without defect";
$msg=$filing_no;
//$msg1=htmlspecialchars($message.'||'.$msg);
$msg1=$message.'||'.$msg;
$hash=base64_encode($msg1);

$msg3=base64_encode($form_status);

list($Y,$m,$d) =explode('-',$cur_date);
$notification_date =$d.'/'.$m.'/'.$Y;

$subject="Status of Documents filed under diary no ".$filing_no;

// $email_text="The document submitted under diary no ".$filing_no." with misc number ".$miscellaneous_no." are marked as defect free on date :" .$notification_date." This is a computer generated message, Please do not reply "  ;
// $msg555="The Document(s) submitted under diary no ".$filing_no." with misc number ".$miscellaneous_no." are marked as defect free on date :".$notification_date.".";


$email_text="The ".$miscellaneous_no." is marked as defect free on date: ".$datetime." and the case number is ".$case_number.". This is a computer-generated message, please do not reply. GSTAT-GSTN" ;
$msg555="The ".$miscellaneous_no." is marked as defect free on date: ".$datetime." and the case number is ".$case_number.". This is a computer-generated message, please do not reply. GSTAT-GSTN";


$sdsdsds = fn_sms($db, '11', '', $final_filing_no, $subject, $msg555, $email_text);

//$hash2=base64_encode($msg3);


/* SMS  Level 1*/





//header("Location:../scrutiny/document_scrutiny.php?hash=$hash&hash2=$msg3");
//unset($_SESSION['form2_scruniny']);

//die();
}


 if($searchby == '1')
{

	$obj_tabs=$_REQUEST['cause_no'];
	$obj_tabs = implode(',', $obj_tabs);

	$st1=$db->prepare("select scrutiny_count from  $schemas.scrutiny_doc where filing_no =? and miscellaneous_no = ?");
			$st1->bindParam(1, $filing_no, PDO::PARAM_STR);
			$st1->bindParam(2, $miscellaneous_no, PDO::PARAM_STR);
			$st1->execute();
			$scrutiny_count= $st1->fetchColumn(); 	

	
	$st1=$db->prepare("select a.case_type,a.case_no,a.case_year,c.short_name as loc_short_name,b.short_name from $schemas.case_detail as a 
		left join case_type as b on b.id = a.case_type
		left join mater_location_city as c on c.city_id = a.location_code
		where a.filing_no =? ");
	$st1->bindParam(1, $filing_no, PDO::PARAM_STR);
	$st1->execute();
	$case_info= $st1->fetch();
	$case_type=$case_info->case_type;
	$case_number = $case_info->short_name."/".$case_info->case_no."/".$case_info->loc_short_name."/".$case_info->case_year;
	
	$status1=$_REQUEST['test'];
	rtrim($status1,",");
	$status=explode(",",$status1);
	$comment=$_REQUEST['comment'];
	$code1=$_REQUEST['id_check'];
	$docs_defect = (isset($_POST['docs']))?implode(',', $_POST['docs']):null;
	
	
	
	  $len=htmlspecialchars(count($_REQUEST['id_check']));
	  
	  $count_scrutiny = $db->prepare("select max(count_scrutiny) from $schemas.document_objection_details where filing_no =? and miscellaneous_no = ?");
  $count_scrutiny->bindParam(1, $filing_no, PDO::PARAM_STR);
  $count_scrutiny->bindParam(2, $miscellaneous_no, PDO::PARAM_STR);
  $count_scrutiny->execute();

  $count_scrutiny->execute();
  $count_scrutiny = $count_scrutiny->fetchColumn();
  /* if($count_scrutiny == "NULL"){
	$count_scrutiny = 1;
  }else{
  $count_scrutiny = $count_scrutiny+1;	
  } */
  
  $rej_count = $db->prepare("select max(rej_count) from $schemas.document_objection_details where filing_no =?  and miscellaneous_no = ?");
  $rej_count->bindParam(1, $filing_no, PDO::PARAM_STR);
  $rej_count->bindParam(2, $miscellaneous_no, PDO::PARAM_STR);
  $rej_count->execute();

  $rej_count->execute();
  $rej_count = $rej_count->fetchColumn();
  if($rej_count == "NULL"){
	$rej_count = 1;
  }else{
  $rej_count = $rej_count+1;	
  }
	$comment1 = ''; 
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
		 if($comment[$i]!="")
	
		 {
		  $comment1 = htmlspecialchars($comment[$i]);
		
		 }
			$code11=htmlspecialchars(addslashes($code11));
			$status1=htmlspecialchars(addslashes($status1));
			echo $comment1 = htmlspecialchars(addslashes($comment1));
	   
		   
$scrutiny_dc='0';
$display_dc='1';
$scr_display='0';

$doc_level='2';
$doc_flag = 1;
//$doc_level_user='1';

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

if($count_scrutiny >= 1)
	$refiling_query = ", allow_refiling = 0";
else
	$refiling_query = ", allow_refiling = 1, allow_refiling_date = '$allow_refiling_date' ";

	$st1x111 =$db->prepare("update document_upload set scrutiny = ? , display=? ,doc_level=?, defect_docs = ?, obj_tabs = ? $refiling_query where  miscellenous_no=? and scrutiny=? and display=? and doc_flag = ?");

//$st1x111->bindParam(1, $scrutiny_dc, PDO::PARAM_STR);
$st1x111->bindParam(1, $display_dc, PDO::PARAM_STR);
$st1x111->bindParam(2, $scr_display, PDO::PARAM_STR);
$st1x111->bindParam(3, $doc_level, PDO::PARAM_STR);
$st1x111->bindParam(4, $docs_defect, PDO::PARAM_STR);
$st1x111->bindParam(5, $obj_tabs, PDO::PARAM_STR);
$st1x111->bindParam(6, $miscellaneous_no, PDO::PARAM_STR);
$st1x111->bindParam(7, $scrutiny_dc, PDO::PARAM_STR);
$st1x111->bindParam(8, $display_dc, PDO::PARAM_STR);
$st1x111->bindParam(9, $doc_flag, PDO::PARAM_STR);

$st1x111->execute();


	
			
	


		   $adddef_sql1 =$db->prepare("update $schemas.document_objection_details set comment_registrar=?,userid=?,entry_dt=now(),"
                           . "status_registrar=?,case_type=?,objection_sub_code=?,level_level=?,rej_count=?, completion_date = now() where miscellaneous_no=? and objection_code=? and count_scrutiny=?");
		  // $aa='0';
		   
$adddef_sql1->execute(array($comment1,$sessionUserType,$status1,
$case_type,$aa,$doc_level,$rej_count,$miscellaneous_no,$code11,$count_scrutiny));



		   $adddef_sql1 =$db->prepare("insert into $schemas.document_objection_details_his (filing_no,objection_code,
comment_registrar,userid,entry_dt,status_registrar,case_type,objection_sub_code,level_level,miscellaneous_no,completion_date) values 
(?,?,?,?,now(),?,?,?,?,?,now())");
		  // $aa='0';
		   
		   
$adddef_sql1->execute(array($filing_no,$code11,$comment1,$sessionUserType,$status1,
$case_type,$aa,$doc_level,$miscellaneous_no));

$comment1="";

 	}
 


$aaq='Y';
$kks='Y';
	

  	       $st13 =$db->prepare("update $schemas.scrutiny_doc set objection_status=?,compliance_date=?,defects=?,varifyed_userid=?,level_level=?, updated_at = now() , scrutiny_count=scrutiny_count+1, obj_tabs = ? where miscellaneous_no=?");
		   $st13->bindParam(1, $kks, PDO::PARAM_STR);
		   $st13->bindParam(2, $notification_date1, PDO::PARAM_STR);
		   $st13->bindParam(3, $aaq, PDO::PARAM_STR);
		   $st13->bindParam(4, $sessionUserType, PDO::PARAM_STR);
		   $st13->bindParam(5, $doc_level, PDO::PARAM_STR);
		   $st13->bindParam(6, $obj_tabs, PDO::PARAM_STR);
		   $st13->bindParam(7, $miscellaneous_no, PDO::PARAM_STR);
		   $st13->execute();
//$st21c->execute(array($kks,$notification_date1,$aaq,$sessionUserType,$ll,$filing_no));
	

		   $obj_st='Y';
		   $def='Y';
	
	
		   
		   $st13=$db->prepare("insert into  $schemas.scrutiny_doc_his (filing_no,defects,notification_date,user_id,level_level,miscellaneous_no,entry_date,obj_tabs)
		   		values (?,?,?,?,?,?,now(),?) ");
		   $st13->bindParam(1, $filing_no, PDO::PARAM_STR);
		   $st13->bindParam(2, $def, PDO::PARAM_STR);
		   $st13->bindParam(3, $notification_date1, PDO::PARAM_STR);
		   $st13->bindParam(4, $sessionUserType, PDO::PARAM_STR);
		   $st13->bindParam(5, $doc_level, PDO::PARAM_STR);
		   $st13->bindParam(6, $miscellaneous_no, PDO::PARAM_STR);
		   $st13->bindParam(7, $obj_tabs, PDO::PARAM_STR);
		   $st13->execute();
		   
$get_case_info=$db->prepare("select case_type,filing_no,main_case_ia_no from $schemas.case_detail where filing_no=?");
$get_case_info->bindParam(1, $filing_no, PDO::PARAM_STR);
$get_case_info->execute();
$get_case_info = $get_case_info->fetchAll();
$get_case_info = array_shift($get_case_info);
$scrutinized_filing_no = $get_case_info['filing_no'];
$filing_no_ia_ma = $get_case_info['main_case_ia_no'];

if($filing_no_ia_ma == ''){
	$final_filing_no = $scrutinized_filing_no;
}else{
	$final_filing_no = $filing_no_ia_ma;
}		   
			   
$sccc='2';

$pet_flag='P';
$pet_serial='1';
$sthr2=$db->prepare("select email,mobile,name from e_cases_party where filing_no =? and party_flag=? and party_serial_no=? ");
  $sthr2->bindParam(1, $final_filing_no, PDO::PARAM_STR);
  $sthr2->bindParam(2, $pet_flag, PDO::PARAM_STR);
  $sthr2->bindParam(3, $pet_serial, PDO::PARAM_STR);
  $sthr2->execute();
  while ($row1 = $sthr2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  	$pet_email=$row1['email'];
	$pet_mobile=$row1['mobile'];
	$pet_name1=$row1['name'];
  }

$res_flag='R';
$res_serial='1';
  $sthr3=$db->prepare("select email,mobile,name from e_cases_party where filing_no =? and party_flag=? and party_serial_no=?");
  $sthr3->bindParam(1, $final_filing_no, PDO::PARAM_STR);
  $sthr3->bindParam(2, $res_flag, PDO::PARAM_STR);
  $sthr3->bindParam(3, $res_serial, PDO::PARAM_STR);
  $sthr3->execute();
  
  
   while ($row3 = $sthr3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  	$res_email=$row3['email'];
	$res_mobile=$row3['mobile'];
	$res_name1=$row3['name'];
  }

 $serial11=1;
$obj_status='NO';
$obj_level='22';
$count_scrutiny = $db->prepare("select max(count_scrutiny) from $schemas.document_objection_details where filing_no =?");
  $count_scrutiny->bindParam(1, $filing_no, PDO::PARAM_STR);
 
  $count_scrutiny->execute();

  $count_scrutiny->execute();
  $count_scrutiny = $count_scrutiny->fetchColumn();
  
  $city_query = "select city_name from mater_location_city where city_id = ? ";
$ins_note =$db->prepare($city_query);
$ins_note->bindParam(1, $location_access, PDO::PARAM_STR);
$ins_note->execute();
$city_name = $ins_note->fetchColumn();
  
  list($Y,$m,$d) =explode('-',$cur_date);
$notification_date =$d.'/'.$m.'/'.$Y;
$petitioner_info = get_party_completed_info($db,$filing_no,$party_flag='P',$party_serial_no='1');
$case_type_detail = get_case_type_detail($db,$get_case_info['case_type']);
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
    <p style="text-align:center; line-height: 0.5; font-size: 22px;"><b>'.$bench_name.'</b></p>
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
		  $comment1 = htmlspecialchars(addslashes($comment1));
		  $aas='1';
	if(trim($comment1 != '')){
	$defective_html .= '<tr style="border: 1px solid black;">
                    <td style="border: 1px solid black; padding:5px;">'.$sr.'.</td>
                    <td style="border: 1px solid black; padding:5px;">'.$comment1.'</td>
                </tr>';
	$sr++;
	}
	$comment1 = '';
	$cm++;
	}

	$defective_html .= '</table><p>The aforesaid defects have also been communicated to you on the copy/link sent to you on your email/phone.</p>';

	if($scrutiny_count >= '1'){
		$defective_html .=  '<p>You are hereby directed to remove the said defects and re-submit the said appeal/application on the portal within 7 days of the date of this notice/on or before '.$next_date.', failing which the said appeal/application is liable to be rejected</p>';
	}

	$defective_html .= '</div>
        <table style="line-height: 1.2; margin-bottom: 15px; margin-top: 50px; width: 100%;">
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
  $rep_miscellaneous_no = str_replace("/","-",$miscellaneous_no);
  $pdf_file_name=$rep_miscellaneous_no."-".$time;;
  $filename=$pdf_file_name.".pdf";
  $filename_sms = $pdf_file_name;
   $dompdf->loadHtml($defective_html);
  $dompdf->setPaper('A4');
  $dompdf->render();
  $outputff = $dompdf->output();
  $upload_dir = "Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/defects/$filing_no";
  $pp_path = $upload_dir."/$filename";
  $up_path = $upload_dir."/$filename";
 //  if (!file_exists($save_path)) {
	// 	mkdir($upload_dir, 0777, true);
	// }
 //  $save_apl = file_put_contents($pp_path, $outputff);
  
  $s3Service = new S3Service();
  $save_apl = $s3Service->uploadDynamicFile($outputff, $pp_path);
  if(!$save_apl)
  {
	$db->rollBack();
	$message = 'There are some problem in saving defect sheet';
	$msg=base64_encode($filing_no);
	$msg1=$message.'||'.$msg;
	$hash=base64_encode($msg1);

	$msg3=base64_encode($form_status);
	header("Location:../scrutiny/document_scrutiny.php?hash=$hash&hash2=$msg3");
	die;
  }

 $message = 'Scrutiny Done With Defect';
$msg=base64_encode($filing_no);
$msg1=$message.'||'.$msg;
$hash=base64_encode($msg1);

$msg3=base64_encode($form_status);

$subject="Status of Documents submitted filed under diary no ".$filing_no ;
// $email_text1="The document(s) submitted under diary no ".$filing_no." with misc number ".$miscellaneous_no." are marked as defective on date :" .$notification_date." Kindly check the attachment for removing the defects raised. You are required to submit the corrected documents as soon as possible.
//   This is a computer generated message, Please do not  reply"  ;
  
// $msg555="The Document(s) submitted under diary no ".$filing_no." with misc number ".$miscellaneous_no." are marked as defective on date :".$notification_date.".";

$msg555 = "The provisional acknowledgement no ".$miscellaneous_no." is marked as defective on date: ".$datetime.". The defect/s can be viewed in registered email or GSTAT login. Kindly remove the defect/s within: ".$days." days and re-file the case. GSTAT-GSTN";

$email_text1 = "The provisional acknowledgement no ".$miscellaneous_no." is marked as defective on date: ".$datetime.". Please find the list of defects as attached. The defect/s can be also be viewed in GSTAT login. Kindly remove the defect/s within: ".$days." days and re-file the case. GSTAT-GSTN";



$up_scr=$db->prepare("update $schemas.scrutiny_doc set defect_pdf_path=? where filing_no = ? and miscellaneous_no = ?");
$up_scr->bindParam(1, $up_path, PDO::PARAM_STR);
$up_scr->bindParam(2, $filing_no, PDO::PARAM_STR);
$up_scr->bindParam(3, $miscellaneous_no, PDO::PARAM_STR);
$up_scr->execute();

	
$sdsdsds = fn_sms($db, '8', $up_path, $final_filing_no, $subject, $msg555, $email_text1,$up_path);

}
$db->commit();


header("Location:../scrutiny/document_scrutiny.php?hash=$hash&hash2=$msg3");
unset($_SESSION['form2_scruniny']);
}catch(Exception $e){
	$db->rollBack();
	echo $e->getMessage(); 
	$message = 'Something went wrong!';
$msg=base64_encode($filing_no);
$msg1=$message.'||'.$msg;
$hash=base64_encode($msg1);

$msg3=base64_encode($form_status);
	header("Location:../scrutiny/document_scrutiny.php?hash=$hash&hash2=$msg3");
}


}
?>
