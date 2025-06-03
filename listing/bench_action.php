
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


 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);   
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
	
	try {
        $db->beginTransaction();

	
	
// This code not use next time .......	Schema session create Hear....
	
	
		$sessionUserType=htmlspecialchars($_SESSION['id']);



		$curYear = htmlspecialchars(date("Y"));
		$curMonth = htmlspecialchars(date("m"));
		$curDay = htmlspecialchars(date("d"));
		$cur_date = "$curYear-$curMonth-$curDay";
		$cur_date1 ="$curDay/$curMonth/$curYear";

		$link_scrutiny_idaccess='1';


		 $from_list_date =$_REQUEST['from_list_date'];
		 
		 //$to_list_date = $_REQUEST['to_list_date'];

		$entry_date = htmlspecialchars(date("F j, Y g:i a"));
		$schemas=htmlspecialchars($_SESSION['schema_name']);


		list($day,$month,$year)=explode('/',$from_list_date);
		//$list_date=$year.'-'.$month.'-'.$day;
		$list_date=$year.'-'.$day.'-'.$month;

		/* list($day,$month,$year)=explode('/',$to_list_date);
		$list_date_to=$year.'-'.$month.'-'.$day; */



		$bench_nature =$_POST['bench_code'];

		 $judge =$_REQUEST['judge'];

		$court_no =$_REQUEST['court_no'];
		$list_flag = $_REQUEST['b_type'];

		// if($bench_nature == '3'){
		// 	$court_no = 5;
		// }

	  $presiding =$_REQUEST['presiding'];
	  for($i=0;$i<=count($judge);$i++)
	  {
	  	if($presiding==$i)
	  	{
	  		 $presiding1=$judge[$i];
	  	}
	  }



		$detail =$_POST['details'];
		//echo "<pre>"; print_r($_REQUEST); die("sdfsdf");
	  	$limit_case =$_POST['limit_case'];
	  	$bench_location =$_POST['bench_location'];
		$bench_remarks =$_POST['bench_remarks'];
		$meet_pwd =$_POST['meet_pwd'];
		$vdo_cnfr_lnk =$_POST['vdo_cnfr_lnk'];
//echo $list_date;

		$sql_max="select max(bench_no) from $schemas.bench where from_list_date = ? ";

		$sql_max = $db->prepare($sql_max);
		$sql_max->bindParam(1, $list_date, PDO::PARAM_STR);
		$sql_max->execute();
		$max_bench = $sql_max->fetchColumn();
		if($max_bench=='' || $max_bench=='0')
			{
			$max_bench=1;
			}
			else
			{
				$max_bench=$max_bench+1;
			}
			
		 $priority=0;
		  //$presiding=0;
		/* 
		if($bench_nature==1)
			{
			$presiding=1;
			}
		 */
		$bench_sql =$db->prepare("insert into $schemas.bench (bench_nature,
		bench_no,court_no,from_list_date,to_list_date,presiding,entry_date,deal_cd,from_time,to_time,detail,priority,limit_case,location_code,available_quota,list_flag,custom_text,vdo_cnfr_lnk,meet_pwd) values
		(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

		$bench_sql->bindParam(1, $bench_nature, PDO::PARAM_STR);
		$bench_sql->bindParam(2, $max_bench, PDO::PARAM_STR);
		$bench_sql->bindParam(3, $court_no, PDO::PARAM_STR);
		$bench_sql->bindParam(4, $list_date, PDO::PARAM_STR);
		$bench_sql->bindParam(5, $list_date, PDO::PARAM_STR);
		$bench_sql->bindParam(6, $presiding1, PDO::PARAM_STR);
		$bench_sql->bindParam(7, $entry_date, PDO::PARAM_STR);
		$bench_sql->bindParam(8, $sessionUserType, PDO::PARAM_STR);
		$bench_sql->bindParam(9, $detail, PDO::PARAM_STR);
		$bench_sql->bindParam(10, $detail, PDO::PARAM_STR);
		$bench_sql->bindParam(11, $bench_remarks, PDO::PARAM_STR);
		$bench_sql->bindParam(12, $priority, PDO::PARAM_STR);
		$bench_sql->bindParam(13, $limit_case, PDO::PARAM_STR);
		$bench_sql->bindParam(14, $bench_location, PDO::PARAM_STR);

		$bench_sql->bindParam(15, $limit_case, PDO::PARAM_STR);
		$bench_sql->bindParam(16, $list_flag, PDO::PARAM_STR);
		$bench_sql->bindParam(17, $custom_text, PDO::PARAM_STR);
		$bench_sql->bindParam(18, $vdo_cnfr_lnk, PDO::PARAM_STR);
		$bench_sql->bindParam(19, $meet_pwd, PDO::PARAM_STR);

		$bench_sql->execute();

 		$judge =$_REQUEST['judge'];


		for($i=0;$i<=count($judge);$i++)
		{
			$judge_code=$judge[$i];
			if($judge_code >0)
			{

				$bench_sql1 =$db->prepare("insert into $schemas.bench_judge (bench_no,judge_code,from_list_date,from_time,to_list_date,to_time,entry_date,deal_cd,bench_nature,court_no) values
				(?,?,?,?,?,?,?,?,?,?)");

				
				$bench_sql1->execute(array($max_bench,$judge_code,$list_date,$detail,
						$list_date,$detail,$entry_date,$sessionUserType,$bench_nature,$court_no));
			}

		}
		



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
						$court_no,$purpose,$purpose_priority1,$sessionUserType,$entry_date,$bench_nature,$max_bench));
		}
		$db->commit();
		$message='Bench Composition Successfully Done  ';
		header("Location:./create_bench.php?msg=$message");
	}catch(Exception $e){
		$db->rollBack();
		$message='Something went wrong';
		header("Location:./create_bench.php?msg=$message");
	}
}
?>
