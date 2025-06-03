<?php 
require_once('../includes/helper.php');
deny_direct_access();
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
date_default_timezone_set("Asia/Kolkata");

$server_date= date('d-m-Y'); //Returns IST 

if($server_date !='')
{
	list($day2,$month2,$year2)=explode('-',$server_date);
	 $entry_date=$year2."-".$month2."-".$day2;
	
}
/*

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
	
	
	
// This code not use next time .......	Schema session create Hear....
	
	
	$sessionUserType=htmlspecialchars($_SESSION['id']);



$curYear = htmlspecialchars(date("Y"));
$curMonth = htmlspecialchars(date("m"));
$curDay = htmlspecialchars(date("d"));
$cur_date = "$curYear-$curMonth-$curDay";
$cur_date1 ="$curDay/$curMonth/$curYear";

$link_scrutiny_idaccess='1';

$prt_type =$_REQUEST['prt_type'];
$filing_no =$_REQUEST['filing_no'];
$bench_type =$_REQUEST['bench_type'];
$case_type =$_REQUEST['case_type'];
$case_no =$_REQUEST['case_no'];
$case_year =$_REQUEST['case_year'];
$notice_type =$_REQUEST['notice_type'];
$n_date =$_REQUEST['n_date'];
$filing_party =$_REQUEST['filing_party'];
$adm_rules =$_REQUEST['adm_rules'];
$reasons =$_REQUEST['reasons'];
$behalf_applicant =$_REQUEST['behalf_applicant'];
$sign_address =$_REQUEST['sign_address'];
$tel_no =$_REQUEST['tel_no'];
$fax =$_REQUEST['fax'];
$email =$_REQUEST['email'];
$under_rule =$_REQUEST['under_rule'];
$tr_reh =$_REQUEST['tr_reh'];
$reh_pet_no =$_REQUEST['reh_pet_no'];
$tr_pet_no =$_REQUEST['tr_pet_no'];
$matter_from =$_REQUEST['matter_from'];
$other_matter =$_REQUEST['other_matter'];
$ins_relief =$_REQUEST['ins_relief'];


if($notice_type==2)
{

$filing_party =$_REQUEST['from_3'];

$adm_rules =$_REQUEST['name_3'];
$fileno_3 =$_REQUEST['fileno_3'];
$ins_relief =$_REQUEST['insert_relief_3'];
$under_rule =$_REQUEST['insert_section_3'];
$reasons =$_REQUEST['insert_concise_3'];
$behalf_applicant =$_REQUEST['name_title_3'];
$sign_address =$_REQUEST['address_3'];
$tel_no =$_REQUEST['tel_no_3'];
$fax =$_REQUEST['fax_no_3'];
$email =$_REQUEST['email_3'];

}

if($notice_type==3)
{

$under_rule =$_REQUEST['us_3A'];
$filing_party =$_REQUEST['comp_3A'];
$adm_rules =$_REQUEST['presented_3A'];
$on_3A =$_REQUEST['on_3A'];
$fixed_3A =$_REQUEST['fixed_3A'];
$bench_3A =$_REQUEST['bench_3A'];
$reasons =$_REQUEST['sd_3A'];
$behalf_applicant =$_REQUEST['name_3A'];
$sign_address =$_REQUEST['address_3A'];


}
if($notice_type==4)
{

$to_3B =$_REQUEST['to_3B'];
$us_3B =$_REQUEST['us_3B'];
$dated_3B =$_REQUEST['dated_3B'];
$presented_3B =$_REQUEST['presented_3B'];
$bench_3B =$_REQUEST['bench_3B'];
$state_3B =$_REQUEST['state_3B'];
$before_3B =$_REQUEST['before_3B'];
$days_3B =$_REQUEST['days_3B'];
$applicant_3B =$_REQUEST['applicant_3B'];
$place_3B =$_REQUEST['place_3B'];

}

if($notice_type==6)
{

$to_3B =$_REQUEST['us3_3B'];
$us_3B =$_REQUEST['us_3B'];
$days_3B =$_REQUEST['us1_3B'];
$dated_3B =$_REQUEST['dated_3B'];
$on_3A =$_REQUEST['dated1_3B'];
$presented_3B =$_REQUEST['presented_3B'];
$bench_3B =$_REQUEST['bench_3B'];

}

if($notice_type==7)
{

$to_3B =$_REQUEST['us3_3B'];
$us_3B =$_REQUEST['us_3B'];
$matter_from =$_REQUEST['us1_3B'];
$presented_3B =$_REQUEST['presented_3B'];
$bench_3B =$_REQUEST['bench_3B'];
$place_3B =$_REQUEST['us2_3B'];
$state_3B =$_REQUEST['us4_3B'];
$days_3B =$_REQUEST['us5_3B'];

}

if($notice_type==10)
{

$to_3B =$_REQUEST['us3_3B'];
$us_3B =$_REQUEST['us_3B'];
$matter_from =$_REQUEST['us1_3B'];
$presented_3B =$_REQUEST['presented_3B'];
$bench_3B =$_REQUEST['bench_3B'];
$place_3B =$_REQUEST['us2_3B'];
$state_3B =$_REQUEST['us4_3B'];
$days_3B =$_REQUEST['dated2_3B'];
$dated_3B =$_REQUEST['dated_3B'];
$on_3A =$_REQUEST['dated1_3B'];
//$entry_date =$_REQUEST['dated2_3B'];
$filing_party =$_REQUEST['bench1_3B'];
$under_rule =$_REQUEST['bench2_3B'];
$adm_rules =$_REQUEST['bench3_3B'];
$fixed_3A =$_REQUEST['bench4_3B'];
$applicant_3B =$_REQUEST['bench5_3B'];

}


if($notice_type==16)
{

$under_rule =$_REQUEST['us_3A'];
$filing_party =$_REQUEST['lr_3B'];
$adm_rules =$_REQUEST['lr1_3B'];
$fixed_3A =$_REQUEST['us1_3B'];

}

if($notice_type==15)
{

$under_rule =$_REQUEST['us_3A'];
$filing_party =$_REQUEST['pl_3B'];
$adm_rules =$_REQUEST['dated_3B'];
$on_3A =$_REQUEST['on_3A'];

}







//////////////rana/////////////////////////
if($notice_type==5)
{
$matter_from = $_REQUEST[to_3B];
}

if($notice_type==8)
{

	echo ','. $applicant_3B = $_REQUEST[name_a];
	echo ','. $days_3B =$_REQUEST[age_a];
	echo ','. $sign_address = $_REQUEST[residing];
	echo ','. $under_rule=$_REQUEST[desig];
	echo ','.$matter_from = $_REQUEST[company_at]; 

	}
if($notice_type==11)
{
	print_r($_REQUEST);

	
	echo ','. $applicant_3B = $_REQUEST[name_a];
	echo ','. $reasons = $_REQUEST[name_ab];
	echo ','. $days_3B =$_REQUEST[age_a];
	echo ','. $sign_address = $_REQUEST[residing];
	
	echo ','. $adm_rules = $_REQUEST[practic];
	echo ','. $other_matter = $_REQUEST[roll_as];
	
	
	
	echo ','. $under_rule=$_REQUEST[desig];
	echo ','.$matter_from = $_REQUEST[company_at];
	echo ','. $presented_3b = $_REQUEST[cal];
	echo ','. $bench_3B = $_REQUEST[todate];
	echo ','. $state_3B = $_REQUEST[place];
	echo ','. $behalf_applicant = $_REQUEST[toadd];
	
	//die();
	}
	
	
////////////////rana////////////////////////


///////////////akhlesh////////////////////

if($notice_type==13)
{
$presented_3B =$_REQUEST['presented_3B'];
$bench_3B =$_REQUEST['bench_3B'];
$dated_3B =$_REQUEST['dated_3B'];
$place_3B =$_REQUEST['place_3B'];

}

if($notice_type==14)
{

$dated_3B =$_REQUEST['dated_3B'];
$presented_3B =$_REQUEST['presented_3B'];
$bench_3B =$_REQUEST['bench_3B'];
$state_3B =$_REQUEST['state_3B'];
$before_3B =$_REQUEST['before_3B'];
$place_3B =$_REQUEST['place_3B'];
$applicant_3B =$_REQUEST['applicant_3B'];
$filing_party =$_REQUEST['filing_party'];
$fixed_3A =$_REQUEST['fixed_3A'];
$adm_rules =$_REQUEST['adm_rules'];

}



if($notice_type==18)
{

$presented_3B =$_REQUEST['presented_3B'];
$bench_3B =$_REQUEST['bench_3B'];
$state_3B =$_REQUEST['state_3B'];
$before_3B =$_REQUEST['before_3B'];
$place_3B =$_REQUEST['place_3B'];
$applicant_3B =$_REQUEST['applicant_3B'];
$fixed_3A =$_REQUEST['fixed_3A'];
$adm_rules =$_REQUEST['adm_rules'];

}

///////////////////////////////////


if($tr_pet_no!='')
{
$tr_pet_number=$tr_pet_no;
}
else
{
$tr_pet_number=$reh_pet_no;
}
//$entry_date = htmlspecialchars(date("F j, Y g:i a"));
$schemas=htmlspecialchars($_SESSION['schema_name']);
if($n_date!='')
{
list($day,$month,$year)=explode('/',$n_date);
 $n_date=$year.'-'.$month.'-'.$day;
}
if($on_3A!='')
{
list($day2,$month2,$year2)=explode('/',$on_3A);
 $on_3A=$year2.'-'.$month2.'-'.$day2;
}

$tmpremarks='';	
 

$bench_sql =$db->prepare("insert into $schemas.notice_creation_details (filing_no,case_no,send_date,notice_type,notice_date,
party_admission,sec_of_act,concise_applicant,behalf_applicant,address,tel_no,fax,email,prescribed_under_rule,reh_tr_petition_no,
matters_from,remarks,today_date,other_matter,user_id,reh_tr_type,case_type,case_year,bench_loc,ins_relief,file_no,fixed3a,on3a,bench3a,to_3b,
dated_3b,presented_3b,bench_3b,state_3b,before_3b,days_3b,applicant_3b,place_3b,us_3b) values
(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");





$bench_sql->bindParam(1, $filing_no, PDO::PARAM_STR);
$bench_sql->bindParam(2, $case_no, PDO::PARAM_STR);
$bench_sql->bindParam(3, $entry_date, PDO::PARAM_STR);
$bench_sql->bindParam(4, $notice_type, PDO::PARAM_STR);
$bench_sql->bindParam(5, $n_date, PDO::PARAM_STR);
$bench_sql->bindParam(6, $filing_party, PDO::PARAM_STR);
$bench_sql->bindParam(7, $adm_rules, PDO::PARAM_STR);
$bench_sql->bindParam(8, $reasons, PDO::PARAM_STR);
$bench_sql->bindParam(9, $behalf_applicant, PDO::PARAM_STR);
$bench_sql->bindParam(10, $sign_address, PDO::PARAM_STR);
$bench_sql->bindParam(11, $tel_no, PDO::PARAM_STR);
$bench_sql->bindParam(12, $fax, PDO::PARAM_STR);
$bench_sql->bindParam(13, $email, PDO::PARAM_STR);
$bench_sql->bindParam(14, $under_rule, PDO::PARAM_STR);
$bench_sql->bindParam(15, $tr_pet_number, PDO::PARAM_STR);

$bench_sql->bindParam(16, $matter_from, PDO::PARAM_STR);
$bench_sql->bindParam(17, $tmpremarks, PDO::PARAM_STR);
$bench_sql->bindParam(18, $entry_date, PDO::PARAM_STR);
$bench_sql->bindParam(19, $other_matter, PDO::PARAM_STR);
$bench_sql->bindParam(20, $sessionUserType, PDO::PARAM_STR);
$bench_sql->bindParam(21, $tr_reh, PDO::PARAM_STR);

$bench_sql->bindParam(22, $case_type, PDO::PARAM_STR);
$bench_sql->bindParam(23, $case_year, PDO::PARAM_STR);
$bench_sql->bindParam(24, $bench_type, PDO::PARAM_STR);
$bench_sql->bindParam(25, $ins_relief, PDO::PARAM_STR);
$bench_sql->bindParam(26, $fileno_3, PDO::PARAM_STR);
$bench_sql->bindParam(27, $fixed_3A, PDO::PARAM_STR);
$bench_sql->bindParam(28, $on_3A, PDO::PARAM_STR);
$bench_sql->bindParam(29, $bench_3A, PDO::PARAM_STR);


$bench_sql->bindParam(30, $to_3B, PDO::PARAM_STR);
$bench_sql->bindParam(31, $dated_3B, PDO::PARAM_STR);
$bench_sql->bindParam(32, $presented_3B, PDO::PARAM_STR);

$bench_sql->bindParam(33, $bench_3B, PDO::PARAM_STR);
$bench_sql->bindParam(34, $state_3B, PDO::PARAM_STR);
$bench_sql->bindParam(35, $before_3B, PDO::PARAM_STR);

$bench_sql->bindParam(36, $days_3B, PDO::PARAM_STR);
$bench_sql->bindParam(37, $applicant_3B, PDO::PARAM_STR);
$bench_sql->bindParam(38, $place_3B, PDO::PARAM_STR);
$bench_sql->bindParam(39, $us_3B, PDO::PARAM_STR);

$bench_sql->execute();
		
$message='FRESH NOTICE ENTERED SUCESSFULLY DONE...';


$st = $db->prepare("select * from case_type where id='$case_type' and display = 'TRUE' order by case_type_desc asc");
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$case_type_name=htmlspecialchars($row['case_type_desc']);
}



	if($prt_type==1)
	{
	$case_no=ltrim($case_no,0);
	 $manual_case_number = $case_type_name.'/'.$case_no.'/'.$case_year;

	$msg = "Record Inserted Sucessfully";
	$msghash1 =$msg."@".$manual_case_number."@".$filing_no."@".$n_date."@".$notice_type."@".$prt_type;
	}

$msghash=base64_encode($msghash1);
header("Location:./create_notice.php?msghash=$msghash");

}
?>
