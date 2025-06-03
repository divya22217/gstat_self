<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
 $server_date= date('Y-m-d');
include '../db_inc2.php';

if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
echo "you Can't access this page";
}
else
{
	
	
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	$item_no = $_REQUEST['item_no'];
	$schemas=htmlspecialchars($_SESSION['schema_name']);
	
	
	  $filing_no=$_REQUEST['filing_no'];
	  $name2=$_REQUEST['name'];
	  $cc_email=$_REQUEST['cc_email'];
	
	 
	  $email=$_REQUEST['email_irp'];
	  $mobile=$_REQUEST['mobile'];
	  $enrol_no=$_REQUEST['enrol_no'];
	   $role=$_REQUEST['role'];

 $userfile=$_REQUEST['userfile'];

 $file = $_FILES['userfile'];
 $name = $file['name'];

	   $dt_of_appointment=$_REQUEST['dt_of_appointment'];
	   
	list($day1,$month1,$year1)=explode('/',$dt_of_appointment);
$dt_of_appointment=$year1.'-'.$month1.'-'.$day1;
  
	$ds="select max(irp_no) as irp_no from $schemas.irp_detail ";
$st = $db->prepare($ds);
$st->execute();
$irp_no=$st->fetchColumn();

if($irp_no == "" or $irp_no==0 or $irp_no =='NULL')
{
	$irp_no = 1;
}
else
{
	$irp_no = $irp_no + 1;
}




list($year2,$month2,$day2)=explode('-',$server_date);
$order_date_new=$day2.'/'.$month2.'/'.$year2;


switch ($month2)
{
	case 1:	$month_name="January";
       		 break;

	case 2:	$month_name="February";
       		 break;

	case 3:	$month_name="March";
       		 break;

	case 4:	$month_name="April";
       		 break;

	case 5:	$month_name="May";
       		 break;

	case 6:	$month_name="June";
       		 break;

	case 7:	$month_name="July";
       		 break;

	case 8:	$month_name="August";
       		 break;

	case 9:	$month_name="September";
       		 break;

	case 10: $month_name="October";
       		 break;

	case 11: $month_name="November";
       		 break;

	case 12: $month_name="December";
       		 break;

}





	   $pat ='/Efile_Document/CIS_Document/';

	   $path_year ="$pat/irp_order/$year2";
	   
	   
	   $result_year = mkdir($path_year, 0777);
	   $path =  "$pat/irp_order/$year2/$month_name/";
	   $result = mkdir($path,0777);
 
       $userfile_nm = $path."$filing_no"."_$irp_no.pdf";

       $tmp_path = $_FILES['userfile']['tmp_name'];


       $mimeArray = array('application/pdf');
       $mime = mime_content_type($_FILES['userfile']['tmp_name']);
 
if (!in_array($mime, $mimeArray))
{
echo "Mime type is ".$mime;
die();
} 

else 
{
   	//echo "Mime type is ".$mime;
}

    $f_size = $_FILES['userfile']['size'];

    $size_MB = round($f_size/(1024*1024),2); 

	//echo $size_MB. "MB";
 	if($size_MB > 2500)
	{ 
		print "Error::file size should not be greater than 50 MB";
		die(); 
	}

	
move_uploaded_file($_FILES['userfile']['tmp_name'], $userfile_nm);

//$data = bin2hex(file_get_contents($userfile_nm)); // This may be a problem on too large files

if($date_of_removal=='')
{
	$date_of_removal='1111-11-11';
}

if($dt_of_appointment=='')
{
	$dt_of_appointment='1111-11-11';
}
if($confirmation_rp=='')
{
	$confirmation_rp='1111-11-11';
}
if($change_irp_name=='')
{
	$change_irp_name='1111-11-11';
}
if($change_rp_name=='')
{
	$change_rp_name='1111-11-11';
}
if($acc_rej=='')
{
	$acc_rej='1111-11-11';
}

/*echo $sql = "insert into $schemas.irp_detail(name,email,mobile,
enrolment_no,path,defect_removal,appoinment,role,filing_no,entry_date,user_id,confirmation_rp,change_irp_name,change_rp_name,irp_no,acc_rej)
values('$name2','$email','$mobile','$enrol_no','$userfile_nm','$date_of_removal','$dt_of_appointment','$role','$filing_no','$server_date',
'$sessionUserType','$confirmation_rp','$change_irp_name','$change_rp_name','$irp_no','$acc_rej')";
die('DDD');*/
$st = $db->prepare("insert into $schemas.irp_detail(name,email,mobile,
enrolment_no,path,defect_removal,appoinment,role,filing_no,entry_date,user_id,confirmation_rp,change_irp_name,change_rp_name,irp_no,acc_rej,cc_email)
values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
	
$st->bindParam(1, $name2, PDO::PARAM_STR);
$st->bindParam(2, $email, PDO::PARAM_STR);
$st->bindParam(3, $mobile, PDO::PARAM_STR);
$st->bindParam(4, $enrol_no, PDO::PARAM_STR);
$st->bindParam(5, $userfile_nm, PDO::PARAM_STR);
$st->bindParam(6, $date_of_removal, PDO::PARAM_STR);
$st->bindParam(7, $dt_of_appointment, PDO::PARAM_STR);
$st->bindParam(8, $role, PDO::PARAM_STR);
$st->bindParam(9, $filing_no, PDO::PARAM_STR);
$st->bindParam(10, $server_date, PDO::PARAM_STR);
$st->bindParam(11, $sessionUserType, PDO::PARAM_STR);
$st->bindParam(12, $confirmation_rp, PDO::PARAM_STR);
$st->bindParam(13, $change_irp_name, PDO::PARAM_STR);
$st->bindParam(14, $change_rp_name, PDO::PARAM_STR);
$st->bindParam(15, $irp_no, PDO::PARAM_STR);
$st->bindParam(16, $acc_rej, PDO::PARAM_STR);
$st->bindParam(17, $cc_email, PDO::PARAM_STR);


$st->execute();

$sql2="select * from $schemas.case_detail where filing_no='$filing_no'";
   foreach($dbh->query($sql2) as $f2)
   {
	   
	  $case_case_location =$f2['location_code'];
	  $case_case_type=$f2['case_type'];
	  $case_case_no=$f2['case_no'];
	  $case_case_year=$f2['case_year'];
	  $pet_name=$f2['pet_name'];
   }

$lcode ="select short_name from $schemas.bench_location where bench_location_code ='$case_case_location'";
$lcode=$db->prepare($lcode);
$lcode->execute();
$lcodename = $lcode->fetchColumn(); 

if($case_case_type > 0)
{
$stQ = $db->prepare("select short_name from case_type where id = ?");
$stQ->bindParam(1, $case_case_type, PDO::PARAM_STR);
$stQ->execute();
$case_type_short_name=$stQ->fetchColumn();
}

$CASE_NO = htmlspecialchars(strtoupper($case_type_short_name).'/'.$case_case_no.'('.$lcodename.')'.$case_case_year); 

if($schemas=='delhi')
{
	
	$display_bench='Principal Bench';
	$display_bench1="New Delhi";
}
else
{
	
	$display_bench=$schemas;
	$display_bench1=$schemas;
}


$subject="Assignment as IRP under Case Number:".$CASE_NO." by Honorable NCLT ".$lcodename ;
   
//$email_text="You have been assigned as IRP under Case Number ".$CASE_NO." This is a Computer Generated message, Please do not reply";


$stQ = $db->prepare("select max(next_list_date) as next_list_date from $schemas.case_proceeding  where filing_no = ?");
$stQ->bindParam(1, $filing_no, PDO::PARAM_STR);
$stQ->execute();
$next_list_date=$stQ->fetchColumn();

list($year1,$month1,$day1)=explode('-',$next_list_date);
$next_list_date1=$day1.'/'.$month1.'/'.$year1;
$email_text=" I am directed to inform you that Hon’ble ".$display_bench." (National Company Law Tribunal),".$display_bench1." has appointed you to act as Interim Resolution Professional from the panel of names recommended by the IBBI you are to act as Interim Resolution Professional in the matter ".$pet_name." in ".$CASE_NO.". The order in the aforesaid petition has been pronounced on ".$next_list_date." therefore, you are requested to file your declaration and disclosure statement within two days as per the provisions of IBBI Regulation";
$msg555="You have been assigned as IRP Under Case Number ".$CASE_NO." by honorable ".$lcodename;


  $sql ="insert into sms(filing_no,case_number,msg,subject,email_text,send_flag,entry_date,sms_flag,email_irp_rp,mobile_irp_rp,irp_rp_name,cc_email) 
VALUES('$filing_no','$CASE_NO','$msg555','$subject','$email_text','0','$server_date','I','$email','$mobile','$name2','$cc_email')";

$st = $dbonline->prepare($sql);
$st->execute();   




 }
 $msg = 'RECORD ENTERED  SUCESSFULLY .....';

header("Location:./irp.php?msg=$msg");
 
 



?>
