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
//$doc_level_user='1';


$st12x =$db->prepare("update document_upload set scrutiny=?,doc_level=? where miscellenous_no=? and scrutiny=? and display=? and doc_flag = ?");
$st12x->bindParam(1, $doc_sc, PDO::PARAM_STR);
$st12x->bindParam(2, $doc_level, PDO::PARAM_STR);
$st12x->bindParam(3, $miscellaneous_no, PDO::PARAM_STR);
$st12x->bindParam(4, $ef_sc, PDO::PARAM_STR);
$st12x->bindParam(5, $ef_dis, PDO::PARAM_STR);
$st12x->bindParam(6, $doc_flag, PDO::PARAM_STR);
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

$adddef_sql = "update $schemas.document_objection_details set comments=?,userid=?,entry_dt=now(),status=?,"
        . "case_type=?,objection_sub_code=?,completed_flag=?,level_level=?,completion_date = now() where miscellaneous_no=? and objection_code=?";

$sthaqq = $db->prepare($adddef_sql);
//$aa='0';


$yes='Y';
$ll='22';

$sthaqq->execute(array($comment1,$sessionUserType,$status1,
$case_type,$aa,$yes,$ll,$miscellaneous_no,$code11));

$adddef_sql = "insert into $schemas.document_objection_details_his (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,completed_flag,level_level,miscellaneous_no,completion_date) values
(?,?,?,?,now(),?,?,?,?,?,?,now())";
$sthaqq = $db->prepare($adddef_sql);
//$aa='0';


$yes='Y';
$ll='22';

$sthaqq->execute(array($filing_no,$code11,$comment1,$sessionUserType,$status1,
$case_type,$aa,$yes,$ll,$miscellaneous_no));
$comment1="";
}

	$ll='22';
	$obj_st='N';
	$def='N';
	$sccc='1';

$st1=$db->prepare("update $schemas.scrutiny_doc set compliance_date =?,level_level=?,varifyed_userid=?,objection_status=?,defects=?,scrutinu_comp=?, updated_at = now() where miscellaneous_no =? ");
$st1->bindParam(1, $notification_date1, PDO::PARAM_STR);
$st1->bindParam(2, $ll, PDO::PARAM_STR);
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


$ll='22';
$obj_st='N';
$def='N';


$st13=$db->prepare("insert into  $schemas.scrutiny_doc_his (filing_no,defects,notification_date,user_id,level_level,miscellaneous_no,entry_date)
		values (?,?,?,?,?,?,now()) ");
$st13->bindParam(1, $filing_no, PDO::PARAM_STR);
$st13->bindParam(2, $def, PDO::PARAM_STR);
$st13->bindParam(3, $notification_date1, PDO::PARAM_STR);
$st13->bindParam(4, $sessionUserType, PDO::PARAM_STR);
$st13->bindParam(5, $ll, PDO::PARAM_STR);
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

	$st1x111 =$db->prepare("update document_upload set scrutiny = ? , display=? ,doc_level=? where  miscellenous_no=? and scrutiny=? and display=? and doc_flag = ?");

//$st1x111->bindParam(1, $scrutiny_dc, PDO::PARAM_STR);
$st1x111->bindParam(1, $display_dc, PDO::PARAM_STR);
$st1x111->bindParam(2, $scr_display, PDO::PARAM_STR);
$st1x111->bindParam(3, $doc_level, PDO::PARAM_STR);
$st1x111->bindParam(4, $miscellaneous_no, PDO::PARAM_STR);
$st1x111->bindParam(5, $scrutiny_dc, PDO::PARAM_STR);
$st1x111->bindParam(6, $display_dc, PDO::PARAM_STR);
$st1x111->bindParam(7, $doc_flag, PDO::PARAM_STR);

$st1x111->execute();


	
			


$ll='22';
	
	
	


		   $adddef_sql1 =$db->prepare("update $schemas.document_objection_details set comments=?,userid=?,entry_dt=now(),"
                           . "status=?,case_type=?,objection_sub_code=?,level_level=?,rej_count=?, completion_date = now() where miscellaneous_no=? and objection_code=? and count_scrutiny=?");
		  // $aa='0';
		   
$adddef_sql1->execute(array($comment1,$sessionUserType,$status1,
$case_type,$aa,$ll,$rej_count,$miscellaneous_no,$code11,$count_scrutiny));



$ll='22';
	


		   $adddef_sql1 =$db->prepare("insert into $schemas.document_objection_details_his (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,level_level,miscellaneous_no,completion_date) values 
(?,?,?,?,now(),?,?,?,?,?,now())");
		  // $aa='0';
		   
		   
$adddef_sql1->execute(array($filing_no,$code11,$comment1,$sessionUserType,$status1,
$case_type,$aa,$ll,$miscellaneous_no));

$comment1="";

 	}
 


$aaq='Y';
$kks='Y';
$ll='22';
	

  	       $st13 =$db->prepare("update $schemas.scrutiny_doc set objection_status=?,compliance_date=?,defects=?,varifyed_userid=?,level_level=?, updated_at = now() where miscellaneous_no=?");
		   $st13->bindParam(1, $kks, PDO::PARAM_STR);
		   $st13->bindParam(2, $notification_date1, PDO::PARAM_STR);
		   $st13->bindParam(3, $aaq, PDO::PARAM_STR);
		   $st13->bindParam(4, $sessionUserType, PDO::PARAM_STR);
		   $st13->bindParam(5, $ll, PDO::PARAM_STR);
		   $st13->bindParam(6, $miscellaneous_no, PDO::PARAM_STR);
		   $st13->execute();
//$st21c->execute(array($kks,$notification_date1,$aaq,$sessionUserType,$ll,$filing_no));
	
	

			$ll='22';
		   $obj_st='Y';
		   $def='Y';
	
	
		   
		   $st13=$db->prepare("insert into  $schemas.scrutiny_doc_his (filing_no,defects,notification_date,user_id,level_level,miscellaneous_no,entry_date)
		   		values (?,?,?,?,?,?,now()) ");
		   $st13->bindParam(1, $filing_no, PDO::PARAM_STR);
		   $st13->bindParam(2, $def, PDO::PARAM_STR);
		   $st13->bindParam(3, $notification_date1, PDO::PARAM_STR);
		   $st13->bindParam(4, $sessionUserType, PDO::PARAM_STR);
		   $st13->bindParam(5, $ll, PDO::PARAM_STR);
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
  $defective_html = "<html>
					<table style='font-size:20px;'>
						<tbody>
							<tr>
								<td colspan='2' style='text-align:center;'><b><u>F.No $display_scrutiny_date/GSTAT/UR/$miscellaneous_no</u></b></td>
							</tr>
							<tr>
								<td colspan='2' style='text-align:center;'><b><u>GSTAT</u></b></td>
							</tr>
							<tr>
								<td colspan='2' style='text-align:center;'><b><u>$city_name</u></b></td>
							</tr>
							<tr>
								<td colspan='2' style='text-align:center;'>$case_type_detail[case_type_desc] No.. of $filed_case_year</td>
							</tr>
							<tr>
								<td colspan='2' style='text-align:center;'>e-filing number :  $display_fn</td>
							</tr>";
	$defective_html .=		"<tr>
								<td style='text-align:left;'>$pet_name1</td>
								<td style='text-align:right;'>....Appellant / Petitioner</td>
							</tr>
							<tr>
								<td colspan='2' style='text-align:center;'>And</td>
							</tr>
							<tr>
								<td style='text-align:left;'>$res_name1</td>
								<td style='text-align:right;'>....Respondent</td>
							</tr>
							<tr>
								<td colspan='2'><b><u>The noticed defects are as under:-</u></b></td>
							</tr>";
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
	$defective_html .= "<tr><td style='padding-left:10px;' colspan='2'>$sr.   $comment1.</tr>";
	$sr++;
	}
	$comment1 = '';
	$cm++;
	}
	$defective_html .=		"<tr>
								<td colspan='2' style='text-indent:10px;'>You are requested to remove the defects and then refile within 7 days from date of receiving notification</td>
							</tr>
							<tr>
								<td colspan='2'>Date of scrutiny: $display_scrutiny_date</td>
							</tr>
							<tr>
								<td colspan='2'>Date of intimation to the appellant: $display_scrutiny_date</td>
							</tr>
							<tr>
								<td colspan='2'>Mode of intimation to the appellant: $petitioner_info[mobile] , $petitioner_info[email]</td>
							</tr>
							<tr>
								<td colspan='2'>Name of person intimated: $petitioner_info[party_org_contact_person]</td>
							</tr>
							<tr>
								<td colspan='2' style='text-align:right;'>Dealing Head: $username</td>
							</tr>
						</tbody>
					</table>";

  $time = time();				
  $rep_miscellaneous_no = str_replace("/","-",$miscellaneous_no);
  $pdf_file_name=$rep_miscellaneous_no."-".$time;;
  $filename=$pdf_file_name.".pdf";
  $filename_sms = $pdf_file_name;
   $dompdf->loadHtml($defective_html);
  $dompdf->setPaper('A4');
  $dompdf->render();
  $outputff = $dompdf->output();
  $upload_dir = "/Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/defects/$filing_no";
  $pp_path = $upload_dir."/$filename";
  $up_path = $upload_dir."/$filename";
  if (!file_exists($save_path)) {
		mkdir($upload_dir, 0777, true);
	}
  file_put_contents($pp_path, $outputff);

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
	header("Location:../scrutiny/document_scrutiny.php");
}


}
?>
