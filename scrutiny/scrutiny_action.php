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

/*
if( $_POST['form2'] != $_SESSION['form2_scruniny'])
{
	echo 'Invalid form submission';
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	//echo "You are not Valied User..... please login again";
	header("Location: ../login.php?aa=100");
	die();
}
if( $_POST['form2'] =='')
{
	echo 'Invalid form submission1';
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	//echo "You are not Valied User..... please login again";
	header("Location: ../login.php?aa=100");
	die();
}
*/

  $searchby = $_REQUEST['searchby'];
 
 
if (!is_numeric($searchby))
{
	print "TYPE NOT ENTERED";
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	header("Location: ../login.php?aa=100");
	die();

}

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
 echo $notification_date1=$year.'-'.$month.'-'.$day;

 
 $comment = htmlspecialchars(htmlentities($_REQUEST['comment']));

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

$status=$_REQUEST['status'];
$comment=$_REQUEST['comment'];
$code1=$_REQUEST['id_check'];

$len=htmlspecialchars(count($_REQUEST['id_check']));

for($i=0;$i<$len;$i++)
{

$code11=htmlspecialchars($code1[$i]);
	
$status1=htmlspecialchars($status[$i]);
$comment1 = htmlspecialchars($comment[$i]);

$code11=htmlspecialchars(addslashes($code11));
$status1=htmlspecialchars(addslashes($status1));
$comment1 = htmlspecialchars(addslashes($comment1));

$adddef_sql = "insert into $schemas.objection_details (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code,completed_flag) values
(?,?,?,?,?,?,?,?,?)";
$sthaqq = $db->prepare($adddef_sql);
$aa='0';
$yes='Y';
$sthaqq->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa,$yes));
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


$st=$db->prepare("select * from e_case_detail_local where filing_no=? ");
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$filing_no = ($row['filing_no']);
	$pet_name = ($row['pet_name']);
	$res_name = ($row['res_name']);
	$dt_of_filing = ($row['dt_of_filing']);
	$case_type=($row['case_type']);
	$pet_type=$row['pet_type'];
	$pet_adv=$row['pet_adv'];
	$pet_address=$row['pet_address'];
	$pet_state=$row['pet_state'];
	$pet_district=$row['pet_district'];
	$pet_email=$row['pet_email'];
	$pet_mobile=$row['pet_mobile'];
	$pet_phone=$row['pet_phone'];
	$pet_fax=$row['pet_fax'];
	$res_type=$row['res_type'];
	$res_adv=$row['res_adv'];
	$res_address=$row['res_address'];
	$res_state=$row['res_state'];
	$res_district=$row['res_district'];
	$res_email=$row['res_email'];
	$res_mobile=$row['res_mobile'];
	$res_phone=$row['res_phone'];
	$res_fax=$row['res_fax'];
	$amount=$row['amount'];
	$pet_code=$row['pet_code'];
	$res_code=$row['res_code'];
	$pet_adv_name=$row['pet_adv_name'];
	$res_adv_name=$row['res_adv_name'];
	$pet_pin=$row['pet_pin'];
	$res_pin=$row['res_pin'];
	$payment_status=$row['payment_status'];
	$e_reference_no=$row['e_reference_no'];

}
if($pet_type =='' OR $pet_type =='0')
{$pet_type='1';}
if($pet_adv =='' OR $pet_adv =='0')
{$pet_adv ='0';}
if($pet_state =='' OR $pet_state =='0')
{$pet_state='0';}
if($pet_district =='' OR $pet_district =='0')
{$pet_district='0';}
if($res_type =='' OR $res_type =='0')
{$res_type='1';}
if($res_adv =='' OR $res_adv =='0')
{$res_adv='0';}
if($res_state =='' OR $res_state =='0')
{$res_state='0';}
if($res_district =='' OR $res_district =='0')
{$res_district='0';}
if($pet_code =='' OR $pet_code =='0')
{$pet_code='0';}
if($res_code =='' OR $res_code =='0')
{$res_code='0';}
if($pet_pin =='' OR $pet_pin =='0')
{$pet_pin='0';}
if($res_pin =='' OR $res_pin =='0')
{$res_pin='0';}


$status='P';


$aa=$db->prepare("insert into $schemas.case_detail(filing_no,case_type,dt_of_filing,pet_type,pet_name,pet_adv,
		pet_address,pet_state,pet_district,pet_email,pet_mobile,pet_phone,
		pet_fax,res_type,res_name,res_adv,res_address,res_state,
		res_district,res_email,res_mobile,res_phone,res_fax,amount_payment,
		pet_code,res_code,pet_adv_name,res_adv_name,pet_pin,res_pin,
		loginid,entry_date,case_year,e_reference_no,status)
		values
		('$filing_no','$case_type','$dt_of_filing','$pet_type','$pet_name','$pet_adv','$pet_address','$pet_state',
		'$pet_district','$pet_email','$pet_mobile','$pet_phone','$pet_fax','$res_type','$res_name','$res_adv',
		'$res_address','$res_state','$res_district','$res_email','$res_mobile','$res_phone','$res_fax','$amount',
		'$pet_code','$res_code','$pet_adv_name','$res_adv_name','$pet_pin','$res_pin','$userid','$cur_date',
		'$rgyear','$e_reference_no','$status')");
$aa->execute();


$sccc='1';
$st1x =$db->prepare("update e_case_detail set scrutiny_comp1 = ? where filing_no=? ");
$st1x->bindParam(1, $sccc, PDO::PARAM_STR);
$st1x->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x->execute();

$sccc='1';
$st1x =$db->prepare("update e_case_detail_local set scrutiny_comp1 = ? where filing_no=? ");
$st1x->bindParam(1, $sccc, PDO::PARAM_STR);
$st1x->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x->execute();


$sccc='1';
$st1x =$db->prepare("update $schemas.scrutiny set scrutinu_comp = ? where filing_no=? ");
$st1x->bindParam(1, $sccc, PDO::PARAM_STR);
$st1x->bindParam(2, $filing_no, PDO::PARAM_STR);
$st1x->execute();


echo $message = 'Scrutiny Done Successfully Without Defect';
$msg=base64_encode($filing_no);
$msg1=htmlspecialchars($message.'/'.$msg);
$hash=base64_encode($msg1);

header("Location:./scrutiny.php?hash=$hash");
unset($_SESSION['form2_scruniny']);

die();
}


 if($searchby == '1')
{ 
	
	$st1=$db->prepare("select case_type from  e_case_detail_local where filing_no =? ");
	$st1->bindParam(1, $filing_no, PDO::PARAM_STR);
	$st1->execute();
	$case_type_edetail= $st1->fetchColumn();
	
	$case_type=$case_type_edetail;
	
	

	$status=$_REQUEST['status'];
	$comment=$_REQUEST['comment'];
	$code1=$_REQUEST['id_check'];
	
	
	
	 $len=htmlspecialchars(count($_REQUEST['id_check']));
	
	for($i=0;$i<$len;$i++)
	{ 
        
		 $code11=htmlspecialchars($code1[$i]);
		 
		 $status1=htmlspecialchars($status[$i]);
		 $comment1 = htmlspecialchars($comment[$i]);

			$code11=htmlspecialchars(addslashes($code11));
			$status1=htmlspecialchars(addslashes($status1));
			$comment1 = htmlspecialchars(addslashes($comment1));
		
		   $adddef_sql1 =$db->prepare("insert into $schemas.objection_details (filing_no,objection_code,
comments,userid,entry_dt,status,case_type,objection_sub_code) values 
(?,?,?,?,?,?,?,?)");
		   $aa='0';
$adddef_sql1->execute(array($filing_no,$code11,$comment1,$sessionUserType,$notification_date1,$status1,
$case_type,$aa));
 	}

	$st21c =$db->prepare("update $schemas.scrutiny set objection_status=?,
			notification_date=?
	,defects=?,user_id=? where filing_no=?");
		   $aaq='Y';
		   $kks='Y';
$st21c->execute(array($kks,$notification_date1,$aaq,$sessionUserType,$filing_no));
	

echo $message = 'Scrutiny Done Successfully With Defect';
$msg=base64_encode($filing_no);
$msg1=htmlspecialchars($message.'/'.$msg);
$hash=base64_encode($msg1);
header("Location:./scrutiny.php?hash=$hash");
	unset($_SESSION['form2_scruniny']);
   die();       	
	
}
echo 'Invalid Entry.......';
header("Location:../index.php");
}
?>
