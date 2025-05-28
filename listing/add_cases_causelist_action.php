<?php 
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
include '../db_inc2.php';
   
session_start();
$m=0;
$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
/*
if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
echo "Access Problem....."; 
session_unset();     // unset $_SESSION variable for the run-time
session_destroy();
header("Location: ../login.php?aa=100");
die();
}
*/
/*
if($main_id =='9999' and $localadmin =='0')
{
		if($_SESSION['menuaccess_codeall'] !='3')
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
	
	$server_date= date('d-m-Y'); //Returns IST 
if($server_date !='')
{
	list($day2,$month2,$year2)=explode('-',$server_date);
	 $entry_date=$year2."-".$month2."-".$day2;
}

$sessionUserType=htmlspecialchars($_SESSION['id']);
	
//print_r($_POST);	
$purp_hearing=$_REQUEST["purp_hearing"];
//print_r($purp_hearing);
$remarks=$_REQUEST["remarks"];
//print_r($remarks);
$bench_nature_f=$_REQUEST["bench_nature_f"];
//print_r($bench_nature);
$filing_no_f=$_REQUEST["filing_no_f"];
//print_r($filing_no_f);die('o');
//$list_date_f=$_REQUEST["list_date_f"];
//list($day2,$month2,$year2)=explode('/',$list_date_f);
	 //$list_date_f=$year2."-".$month2."-".$day2;
//print_r($list_date_f);
//$court_no_f=$_REQUEST["court_no_f"];
//print_r($court_no_f);
//$bench_no_f=$_REQUEST["bench_no_f"];
//print_r($bench_no_f);
 $bench_no_f=$_REQUEST["bench_no_f"];

$listing_date_f=$_REQUEST["listing_date_f"];
	list($day2,$month2,$year2)=explode('/',$listing_date_f);
	 $listing_date_f=$year2."-".$month2."-".$day2;
 //$court_no_f=$_REQUEST["court_no_f"];
 
$sql_court_no=$db->prepare("select court_no from $schemas.bench where from_list_date=? and bench_no=?");
$sql_court_no->bindParam(1, $listing_date_f, PDO::PARAM_STR);
$sql_court_no->bindParam(2, $bench_no_f, PDO::PARAM_STR);
$sql_court_no->execute();
$court_no_f = $sql_court_no->fetchColumn();

//start of code to check entry already exist or not
$checkcasesql=$db->prepare("select * from $schemas.case_allocation_temp where filing_no=? and listing_date=?" );	
$checkcasesql->bindParam(1, $filing_no_f, PDO::PARAM_STR);
$checkcasesql->bindParam(2, $listing_date_f, PDO::PARAM_STR);
$checkcasesql->execute();
$checkcasesql_result = $checkcasesql->fetch(PDO::FETCH_OBJ);

if($checkcasesql_result)
{
	
	$message="Already Exist in cause list";
	header("Location:./add_cases_causelist.php?msg=$message");
die();
}
$checkcasesql1=$db->prepare("select * from $schemas.case_allocation where filing_no=? and listing_date=?");	
$checkcasesql1->bindParam(1, $filing_no_f, PDO::PARAM_STR);
$checkcasesql1->bindParam(2, $listing_date_f, PDO::PARAM_STR);
$checkcasesql1->execute();
$checkcasesql_result1 = $checkcasesql->fetch(PDO::FETCH_OBJ);
if($checkcasesql_result1)
{
	$message="Already Exist in cause list";
	header("Location:./add_cases_causelist.php?msg=$message");
die();
}

//start of code to get location code selected
$getloccodesql=$db->prepare("select location_code from $schemas.bench where bench_no=? and from_list_date=?");	
$getloccodesql->bindParam(1, $bench_no_f, PDO::PARAM_STR);
$getloccodesql->bindParam(2, $listing_date_f, PDO::PARAM_STR);
$getloccodesql->execute();
$getloccodesql_result = $getloccodesql->fetch(PDO::FETCH_OBJ);

$location_code = $getloccodesql_result->location_code;

//start of code to check its a fresh case or old case
$checkcasenosql=$db->prepare("select * from $schemas.case_detail where filing_no=?");	
$checkcasenosql->bindParam(1, $filing_no_f, PDO::PARAM_STR);
$checkcasenosql->execute();
$checkcasenosql_result = $checkcasenosql->fetch(PDO::FETCH_OBJ);

$case_no_check = $checkcasenosql_result->case_no;
$case_type = $checkcasenosql_result->case_type;

//print_r($case_no_check);die('jj');

/*if($case_no_check==NULL || $case_no_check==''){

	//generation of case number 
	
date_default_timezone_set("Asia/Kolkata");

$server_date= date('d-m-Y'); //Returns IST 
if($server_date !='')
{
	list($day,$month,$year)=explode('-',$server_date);
	 $reg_year_server=$year;	
}
$regis_date =$year.'-'.$month.'-'.$day;


$newst6="select reg_no from $schemas.case_type_reg where case_type='$case_type' and reg_year='$reg_year_server' and location_code='$location_code' ";

$newst6=$db->prepare($newst6);
$newst6->execute();
$regis_no = $newst6->fetchColumn();
if($regis_no==0)
{
$regis_no=1;
//print_r($regis_no);die('p');
}
else
{
$regis_no++;
}

$newst7 ="update $schemas.case_detail set case_no='$regis_no',case_year ='$reg_year_server',case_type='$case_type',location_code ='$location_code',regis_date='$regis_date' where filing_no='$filing_no_f'";

$db->query($newst7) or die("case no. not updated");
		
  $newst8 ="update $schemas.case_type_reg set reg_no ='$regis_no'  where case_type='$case_type' and location_code='$location_code' and reg_year='$reg_year_server'";
         
	 $db->query($newst8) or die("filing counter not updated");

//code to insert data to case_allocation_temp
$case_allocation_sql =$db->prepare("insert into $schemas.case_allocation_temp (filing_no,
listing_date,purpose,entry_date,deal_cd,connected,priority_serial,remarks,bench_nature,list_criteria,court_no,bench_no,list_flag,next_list_date,last_listing_date,last_bench_no,last_court_no,last_bench_nature,listed) values
('$filing_no_f','$listing_date_f','$purp_hearing','$entry_date','$sessionUserType','N','999','$remarks','$bench_nature_f',NULL,'$court_no_f','$bench_no_f','1',NULL,NULL,'0','0','0','1')");

$case_allocation_sql->execute();
	 
	 $message='Fresh Case is successfully added to cause list';
		header("Location:./add_cases_causelist.php?msg=$message");

}
else*/
//{

/*//start of code to get details of cases from case_alloacation
$casedetailssql=$db->prepare("select listing_date, bench_nature, bench_no, court_no, connected, priority_serial, list_criteria,id,list_flag from $schemas.case_allocation where filing_no=?");	
$casedetailssql->bindParam(1, $filing_no_f, PDO::PARAM_STR);
//print_r($casedetailssql);die('h');
$casedetailssql->execute();
$casedetailssql_result = $casedetailssql->fetch(PDO::FETCH_OBJ);

$bench_nature = $casedetailssql_result->bench_nature;
$bench_no = $casedetailssql_result->bench_no;
$court_no = $casedetailssql_result->court_no;
$listing_date = $casedetailssql_result->listing_date;
$connected = $casedetailssql_result->connected;
$priority_serial = $casedetailssql_result->priority_serial;
$list_criteria = $casedetailssql_result->list_criteria;
$id = $casedetailssql_result->id;
$list_flag = $casedetailssql_result->list_flag;
//print_r($list_flag);die(yy);

$case_allocation_sql =$db->prepare("insert into $schemas.case_allocation_temp (filing_no,
listing_date,purpose,entry_date,deal_cd,connected,priority_serial,remarks,bench_nature,list_criteria,court_no,id,bench_no,list_flag,next_list_date,last_listing_date,last_bench_no,last_court_no,last_bench_nature,listed) values
(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

$case_allocation_sql =$db->prepare("insert into $schemas.case_allocation_temp (filing_no,
listing_date,purpose,entry_date,deal_cd,connected,priority_serial,remarks,bench_nature,list_criteria,court_no,id,bench_no,list_flag,next_list_date,last_listing_date,last_bench_no,last_court_no,last_bench_nature,listed) values
('$filing_no_f','$listing_date','$purp_hearing','$entry_date','$sessionUserType','$connected','$priority_serial','$remarks','$bench_nature','$list_criteria','$court_no','$id','$bench_no','$list_flag',NULL,NULL,'0','0','0','0')");
//print_r($case_allocation_sql);die('here');
//$bench_sql->bindParam(1, $bench_nature, PDO::PARAM_STR);
//$bench_sql->bindParam(2, $max_bench, PDO::PARAM_STR);
//$bench_sql->bindParam(3, $court_no, PDO::PARAM_STR);
//$bench_sql->bindParam(4, $list_date, PDO::PARAM_STR);
//$bench_sql->bindParam(5, $list_date, PDO::PARAM_STR);
//$bench_sql->bindParam(6, $presiding1, PDO::PARAM_STR);
//$bench_sql->bindParam(7, $entry_date, PDO::PARAM_STR);
//$bench_sql->bindParam(8, $sessionUserType, PDO::PARAM_STR);
//$bench_sql->bindParam(9, $detail, PDO::PARAM_STR);
//$bench_sql->bindParam(10, $detail, PDO::PARAM_STR);
//$bench_sql->bindParam(11, $bench_remarks, PDO::PARAM_STR);
//$bench_sql->bindParam(12, $priority, PDO::PARAM_STR);
//$bench_sql->bindParam(13, $limit_case, PDO::PARAM_STR);
//$bench_sql->bindParam(14, $bench_location, PDO::PARAM_STR);

//$bench_sql->bindParam(15, $limit_case, PDO::PARAM_STR);
$case_allocation_sql->execute();*/

//code to insert data to case_allocation_temp

$newst6="select filing_no from $schemas.case_allocation_temp where filing_no='$filing_no_f' and listing_date='$listing_date_f' ";

$newst6=$db->prepare($newst6);
$newst6->execute();
$fil_found = $newst6->fetchColumn();

if($fil_found=='' || $fil_found=NULL)
{
$case_allocation_sql =$db->prepare("insert into $schemas.case_allocation_temp (filing_no,
listing_date,purpose,entry_date,deal_cd,connected,priority_serial,remarks,bench_nature,list_criteria,court_no,bench_no,list_flag,next_list_date,last_listing_date,last_bench_no,last_court_no,last_bench_nature,listed) values
('$filing_no_f','$listing_date_f','$purp_hearing','$entry_date','$sessionUserType','N',NULL,'$remarks','$bench_nature_f',NULL,'$court_no_f','$bench_no_f','1',NULL,NULL,'0','0','0','1')");

$case_allocation_sql->execute();
}


$newst61="select filing_no from $schemas.case_allocation where filing_no='$filing_no_f' and listing_date='$listing_date_f' ";

$newst61=$db->prepare($newst61);
$newst61->execute();
$fil_found1 = $newst61->fetchColumn();
if($fil_found=='' || $fil_found=NULL)
{
$case_allocation_sql1 =$db->prepare("insert into $schemas.case_allocation (filing_no,
listing_date,purpose,entry_date,deal_cd,connected,priority_serial,remarks,bench_nature,list_criteria,court_no,bench_no,list_flag,next_list_date,last_listing_date,last_bench_no,last_court_no,last_bench_nature,listed) values
('$filing_no_f','$listing_date_f','$purp_hearing','$entry_date','$sessionUserType','N',NULL,'$remarks','$bench_nature_f',NULL,'$court_no_f','$bench_no_f','1',NULL,NULL,'0','0','0','1')");

$case_allocation_sql1->execute();
}

$pet_adv_code='';	
	$pet_adv_name='';
	$pet_adv_email='';
	$pet_adv_mobile='';	
	
$check_sql =$dbonline->prepare("select rep_code from e_more_representative where filing_no =? and party_flag='P' and party_serial_no='1'");
$check_sql->bindParam(1, $filing_no_f, PDO::PARAM_STR);
$check_sql->execute();
 $pet_adv_code= $check_sql->fetchColumn();


if($pet_adv_code=='')
{
	$pet_adv_code='0';
}
if($pet_adv_code >0)
{
$stqq12 = $dbonline->prepare("select rep_name from e_master_advocate where id=?");
$stqq12->bindParam(1, $pet_adv_code, PDO::PARAM_INT);
$stqq12->execute();
$pet_adv_name = $stqq12->fetchColumn();	

$stqq12 = $dbonline->prepare("select email from e_master_advocate where id=?");
$stqq12->bindParam(1, $pet_adv_code, PDO::PARAM_INT);
$stqq12->execute();
$pet_adv_email = $stqq12->fetchColumn();

$stqq12 = $dbonline->prepare("select mobile from e_master_advocate where id=?");
$stqq12->bindParam(1, $pet_adv_code, PDO::PARAM_INT);
$stqq12->execute();
$pet_adv_mobile = $stqq12->fetchColumn();	

	
}

$res_adv_code='';
$res_adv_name='';	
$res_adv_mobile='';
 $res_adv_email='';

 $adv_party='R';
$adv_party_serial='1';
$check_sql1 =$dbonline->prepare("select rep_code from e_more_representative where filing_no =? and party_flag=? and party_serial_no=?");
$check_sql1->bindParam(1, $filing_no_f, PDO::PARAM_STR);
$check_sql1->bindParam(2, $adv_party, PDO::PARAM_STR);
$check_sql1->bindParam(3, $adv_party_serial, PDO::PARAM_STR);
$check_sql1->execute();
$res_adv_code= $check_sql1->fetchColumn();
if($res_adv_code=='')
{
	$res_adv_code='0';
}

if($res_adv_code >0)
{
$stqq121 = $dbonline->prepare("select rep_name from e_master_advocate where id=?");
$stqq121->bindParam(1, $res_adv_code, PDO::PARAM_INT);
$stqq121->execute();
$res_adv_name = $stqq121->fetchColumn();	

$stqq122 = $dbonline->prepare("select email from e_master_advocate where id=?");
$stqq122->bindParam(1, $res_adv_code, PDO::PARAM_INT);
$stqq122->execute();
 $res_adv_email = $stqq122->fetchColumn();	
$stqq123 = $dbonline->prepare("select mobile from e_master_advocate where id=?");
$stqq123->bindParam(1, $res_adv_code, PDO::PARAM_INT);
$stqq123->execute();
$res_adv_mobile = $stqq123->fetchColumn();	

	
}

$pet_flag='P';
$pet_serial='1';

$pet_email='';
$pet_mobile='';
$pet_name='';
$sthr2=$dbonline->prepare("select email,mobile,name from e_cases_party where filing_no =? and party_flag=? and party_serial_no=? ");
  $sthr2->bindParam(1, $filing_no_f, PDO::PARAM_STR);
  $sthr2->bindParam(2, $pet_flag, PDO::PARAM_STR);
  $sthr2->bindParam(3, $pet_serial, PDO::PARAM_STR);
  $sthr2->execute();
  while ($row1 = $sthr2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  	$pet_email=$row1['email'];
	$pet_mobile=$row1['mobile'];
	$pet_name=$row1['name'];
  }

$res_flag='R';
$res_serial='1';

$res_email='';
$res_mobile='';
$res_name='';
  $sthr3=$dbonline->prepare("select email,mobile,name from e_cases_party where filing_no =? and party_flag=? and party_serial_no=?");
  $sthr3->bindParam(1, $filing_no_f, PDO::PARAM_STR);
  $sthr3->bindParam(2, $res_flag, PDO::PARAM_STR);
  $sthr3->bindParam(3, $res_serial, PDO::PARAM_STR);
  $sthr3->execute();
  while ($row3 = $sthr3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  	$res_email=$row3['email'];
	$res_mobile=$row3['mobile'];
	$res_name=$row3['name'];
  }
 

  $sql2="select * from $schemas.case_detail where filing_no='$filing_no_f'";
   foreach($dbh->query($sql2) as $f2)
   {
	   $case_case_type =$f2['case_type'];
	   $case_case_no =$f2['case_no'];
	   $case_case_year =$f2['case_year'];
	  $case_case_location =$f2['location_code'];
   }
   
   $lcodename='';
   $case_type_short_name='';
   $case_numaa='';
   $case_year1aa='';
   $case_num1aa='';
   $CASE_NO='';
   
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
  $case_numaa = $case_case_no;
$case_year1aa = $case_case_year;
		$case_num1aa=ltrim($case_numaa,0); 
   
$CASE_NO = htmlspecialchars(strtoupper($case_type_short_name).'/'.$case_num1aa.'('.$lcodename.')'.$case_year1aa); 
  
$subject="Application/Petition registered with Case No:".$CASE_NO;
	   
	   list($Y,$m,$d) =explode('-',$listing_date_f);
$date_new1 =$d.'/'.$m.'/'.$Y;
	   
	  $email_text="Your Application/Petition at NCLT ".$lcodename." with Filing Number:".$filing_no_f." is registered with Case Number:".$CASE_NO."and will be listed on date:".$date_new1." This is a Computer Generated message, Please do not reply";
$msg555="Your application/Petition at NCLT ".$lcodename." with Filing No.:".$filing_no_f."is Registered with Case No.".$CASE_NO." and will be listed on date:".$date_new1;



 $sql ="insert into sms(filing_no,case_number,msg,pet_adv_name,pet_adv_mob_no,pet_adv_email,pet_mobile,res_mobile,pet_name,res_name,res_adv_code,pet_adv_code,res_adv_name,res_adv_mob_no,subject,email_text,res_adv_email,pet_email,res_email,send_flag,entry_date,sms_flag,listing_date) 
VALUES('$filing_no_f','$CASE_NO','$msg555','$pet_adv_name','$pet_adv_mobile','$pet_adv_email','$pet_mobile','$res_mobile','$pet_name','$res_name','$res_adv_code','$pet_adv_code','$res_adv_name','$res_adv_mobile','$subject','$email_text','$res_adv_email','$pet_email','$res_email','0','$entry_date','L','$listing_date_f')";

$st = $dbonline->prepare($sql);
$st->execute();     


$message='Case is successfully added to cause list';
		header("Location:./add_cases_causelist.php?msg=$message");
//}
}
?>
