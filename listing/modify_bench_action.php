<?php 
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


if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
echo "Access Problem....."; 
session_unset();     // unset $_SESSION variable for the run-time
session_destroy();
header("Location: ../login.php?aa=100");
die("#2E2E2Eirecting to login.php");
}


setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);
$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key=$_SESSION['csrf'];


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
 $from_list_date =$_REQUEST['from_list_date'];
$entry_date = htmlspecialchars(date("F j, Y g:i a"));
$schemas=htmlspecialchars($_SESSION['schema_name']);
list($day,$month,$year)=explode('/',$from_list_date);
$list_date=$year.'-'.$month.'-'.$day;
$bench_nature =$_POST['bench_code'];
$judge =$_REQUEST['judge'];
$court_no =$_REQUEST['court_no'];
$presiding =$_REQUEST['presiding'];
  for($i=0;$i<=count($judge);$i++)
  {
  	if($presiding==$i)
  	{
  		 $presiding1=$judge[$i];
  	}
  }


$bench_remarks =$_REQUEST[bench_remarks];
$detail =$_REQUEST['detail'];
$limit_case =$_REQUEST['limit_case'];
$bench_location =$_REQUEST['bench_location'];

$bench_no = $_REQUEST[bench_no];

	
 //$priority=0;
  //$presiding=0;
/* 
if($bench_nature==1)
	{
	$presiding=1;
	}
 */
 
//echo "update $schemas.bench set court_no='$court_no',presiding='$presiding',entry_date='$cur_date',deal_cd='$sessionUserType',from_time='$detail',to_time='$detail',limit_case='$limit_case',available_quota='$limit_case' where bench_no='$bench_no' and from_list_date='$list_date'";




$bench_sql =$db->prepare("update $schemas.bench set court_no=?,presiding=?,entry_date=?,deal_cd=?,from_time=?,to_time=?,limit_case=?,available_quota=?,detail=? where bench_no=? and from_list_date=?");

$bench_sql->bindParam(1, $court_no, PDO::PARAM_STR);
$bench_sql->bindParam(2, $presiding1, PDO::PARAM_STR);
$bench_sql->bindParam(3, $entry_date, PDO::PARAM_STR);
$bench_sql->bindParam(4, $sessionUserType, PDO::PARAM_STR);
$bench_sql->bindParam(5, $detail, PDO::PARAM_STR);
$bench_sql->bindParam(6, $detail, PDO::PARAM_STR);
$bench_sql->bindParam(7, $limit_case, PDO::PARAM_STR);
$bench_sql->bindParam(8, $limit_case, PDO::PARAM_STR);
$bench_sql->bindParam(9, $bench_remarks, PDO::PARAM_STR);
$bench_sql->bindParam(10, $bench_no, PDO::PARAM_STR);
$bench_sql->bindParam(11, $list_date, PDO::PARAM_STR);
$bench_sql->execute();
		
		
//if($bench_sql->rowCount()>0){
$ins_bench_judge = $db->prepare("insert into $schemas.bench_judge_his(select * from $schemas.bench_judge where bench_no=? and from_list_date=? )");
$ins_bench_judge->execute(array($bench_no,$list_date));


$del_judge = $db->prepare("delete from $schemas.bench_judge where bench_no=? and from_list_date=?");
$del_judge->execute(array($bench_no,$list_date));
$judge =$_REQUEST['judge'];
for($i=0;$i<=count($judge);$i++)
{
$judge_code=$judge[$i];
if($judge_code >0)
{
$bench_sql1 =$db->prepare("insert into $schemas.bench_judge (bench_no,judge_code,from_list_date,from_time,to_list_date,to_time,entry_date,deal_cd,bench_nature,court_no) values
(?,?,?,?,?,?,?,?,?,?)");
$judge_arr = array($bench_no,$judge_code,$list_date,$detail,$list_date,$detail,$entry_date,$sessionUserType,$bench_nature,$court_no);
$bench_sql1->execute($judge_arr);
}
}
	

$bench_pro = $db->prepare("insert into $schemas.bench_purpose_priority_his(select * from $schemas.bench_purpose_priority where bench_no=? and bench_nature=?)");
$bench_pro->execute(array($bench_no,$bench_nature));

$del_bench_pro = $db->prepare("delete from $schemas.bench_purpose_priority where bench_no=? and bench_nature=?");
$del_bench_pro->execute(array($bench_no,$bench_nature));

$purpose_priority =$_REQUEST['purpose_priority'];
$purpose_code =$_REQUEST['purpose_code'];
$len=htmlspecialchars(count($_REQUEST['purpose_code']));
for($i=0;$i<$len;$i++)
{
$purpose=htmlspecialchars($purpose_code[$i]);
$purpose=htmlspecialchars(addslashes($purpose));
$purpose_priority1 = htmlspecialchars($purpose_priority[$i]);
$purpose_priority1=htmlspecialchars(addslashes($purpose_priority1));


$bench_sql2 =$db->prepare("insert into $schemas.bench_purpose_priority (from_date,to_date,from_time,to_time,court_no,purpose,priority,deal_cd,entry_date,bench_nature,bench_no) values
(?,?,?,?,?,?,?,?,?,?,?)");
$bench_sql2->execute(array($list_date,$list_date,$detail,$detail,
$court_no,$purpose,$purpose_priority1,$sessionUserType,$entry_date,$bench_nature,$bench_no));
}


//}


$message='Bench Modify successfully ';
//header("Location:./modify_bench.php?msg=$message");
echo "<h2 style='color:red;'>$message</h2>";
}
?>
