<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
session_start();
$schemas=htmlspecialchars($_SESSION['schema_name']);
include '../db_inc2.php';
include("../db_inc1.php");
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); 
$sessionUserType=htmlspecialchars($_SESSION['id']);

$filing_no_all = json_decode(stripslashes($_POST['data']));  //array of all filing_no

$penordis_main = stripslashes($_POST['penordis_main']);
$action_type_main = stripslashes($_POST['action_type_main']);

$purpose_code_main = stripslashes($_POST['purpose_code_main']);

$date_main = stripslashes($_POST['date_main']);    //main case next list date

if($penordis_main == 'D'){
    $action_type_main = 0; 
    $purpose_code_main = 0;
    $date_main = "1111-11-11"; 
}

$remarks_main = stripslashes($_POST['remarks_main']);

//echo $filing_no_main = stripslashes($_POST['filing_no_main']);

$listing_date_main = stripslashes($_POST['listing_date_main']);
if($listing_date_main!=''){
list($day,$month,$year)=explode('/',$listing_date_main);
     $listing_date_main=$year.'-'.$month.'-'.$day;
}

$list_before_link = stripslashes($_POST['bench_nature_main']);

$disposal_nature_main = stripslashes($_POST['disposal_nature_main']);

$date_dis_main = stripslashes($_POST['date_dis_main']);

foreach($filing_no_all as $filing_no_main){

if($filing_no_main!=''){     //universal loop

$sth1 = $db->prepare("select listing_date,purpose,bench_nature,court_no,bench_no from $schemas.case_allocation where filing_no=?");
$sth1->bindParam(1, $filing_no_main, PDO::PARAM_STR);
$sth1->execute();
$ca = $sth1->fetch();
//print_r($ca);
//die('sad');


$bench_no=$ca['bench_no'];
$bench_nature=$ca['bench_nature'];
$bench_nature = $list_before_link;
$court_no = $ca['court_no'];
$purpose_old = $ca['purpose'];
$list_date=$ca['listing_date'];


if($bench_no=='') $bench_no=0;
if($bench_nature=='') $bench_nature=0;
if($court_no=='') $court_no=0;
if($purpose_old=='') $purpose_old=0;
if($list_date=='') $list_date=$curdate;

$ipaddress=htmlspecialchars($_SERVER["REMOTE_ADDR"]);   //unused
$accesstime=htmlspecialchars(date("Y-m-d H:i:s"));   //unused
$accesspage="case_proceeding";     //unused


$stkr="select action_type from $schemas.master_action where action_code =?";
$stk=$db->prepare($stkr);
$stk->bindParam(1, $action_type_main, PDO::PARAM_STR);
$stk->execute();
$action_name =$stk->fetchColumn();

/*--------------------------------only disposal-------------------------------------------------*/
/*----------------------------------------------------------------------------------------------*/

if($penordis_main == 'D' OR $penordis_main == 'd'){
   
       $judge_code_insert='';
       $st= "select judge_code from $schemas.bench_judge where bench_no=? and from_list_date=? order by judge_code asc";
       $st=$db->prepare($st);
       $st->bindParam(1, $bench_no, PDO::PARAM_STR);
       $st->bindParam(2, $list_date, PDO::PARAM_STR);
       $st->execute();
       while ($row_jud = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
       {
           if($judge_code_insert=='')
           {
           $judge_code_insert =$row_jud['judge_code'];
           }
           else
           {
           $judge_code_insert=$judge_code_insert.",".$row_jud['judge_code'];
           }
       }
   
   $st="insert into $schemas.case_disposal
       (filing_no,disposal_date,disposal_nature,court_no,bench_nature,case_type,case_no,case_year,judge_code,remarks,entry_date,user_id)
       VALUES
       (?,?,?,?,?,?,?,?,?,?,?,?)";
   $st=$db->prepare($st);
   $st->bindParam(1, $filing_no_main, PDO::PARAM_STR);
   $st->bindParam(2, $date_dis_main, PDO::PARAM_STR);
   $st->bindParam(3, $disposal_nature_main, PDO::PARAM_STR);
   $st->bindParam(4, $court_no, PDO::PARAM_INT);
   $st->bindParam(5, $bench_nature, PDO::PARAM_INT);
   $st->bindParam(6, $case_type, PDO::PARAM_STR);
   $st->bindParam(7, $case_no, PDO::PARAM_STR);
   $st->bindParam(8, $case_year, PDO::PARAM_STR);
   $st->bindParam(9, $judge_code_insert, PDO::PARAM_INT);
   $st->bindParam(10, $remarks_main, PDO::PARAM_STR);
   $st->bindParam(11, $server_date, PDO::PARAM_STR);
   $st->bindParam(12, $sessionUserType, PDO::PARAM_INT);
   $st->execute();
   
   $statusx='D';
   $std = "update $schemas.case_detail set status =? where filing_no=?";
       $std=$db->prepare($std);
       $std->bindParam(1, $statusx, PDO::PARAM_STR);
       $std->bindParam(2, $filing_no_main, PDO::PARAM_STR);
       $std->execute();
   
       
   
       $st="insert into $schemas.case_allocation_his_temp (select * from $schemas.case_allocation_temp where filing_no=?)";
       $st=$db->prepare($st);
       $st->bindParam(1, $filing_no_main, PDO::PARAM_STR);
       $st->execute();
   
       echo "Case is disposed successfully!!!";
}

//code for case_proceeding(Pending + Disposal)
/*------------------------------------------------------------------------------------------*/
/*------------------------------------------------------------------------------------------*/

$check_case_allocation_query = "select filing_no from $schemas.case_proceeding where filing_no=? and listing_date=?";
//echo $check_case_allocation_query;echo $filing_no;
$check_case_allocation_query=$db->prepare($check_case_allocation_query);
$check_case_allocation_query->bindParam(1, $filing_no_main, PDO::PARAM_STR);
$check_case_allocation_query->bindParam(2, $listing_date_main, PDO::PARAM_STR);
$check_case_allocation_query->execute();
$get_filing_no =$check_case_allocation_query->fetchColumn();

if($get_filing_no==''){
	
$sty="insert into $schemas.case_proceeding
(filing_no,listing_date,purpose,court_no,bench_nature,bench_no,
next_list_purpose,next_list_criteria,
next_list_date,todays_action,todays_status,entry_date,remarks,user_id)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$st13=$db->prepare($sty);
//$st13 = array($filing_no,$list_date_link,$purpose_old,$court_no,$bench_nature,$bench_no,$purpose_code_next,$criteria,$next_list_date,$action_type,$pen_dis,$curdate,$remarks,$sessionUserType);
$st13->bindParam(1, $filing_no_main, PDO::PARAM_STR);
$st13->bindParam(2, $listing_date_main, PDO::PARAM_STR);
$st13->bindParam(3, $purpose_old, PDO::PARAM_STR);
$st13->bindParam(4, $court_no, PDO::PARAM_STR);
$st13->bindParam(5, $bench_nature, PDO::PARAM_STR);
$st13->bindParam(6, $bench_no, PDO::PARAM_STR);
$st13->bindParam(7, $purpose_code_main, PDO::PARAM_STR);
$st13->bindParam(8, $criteria, PDO::PARAM_STR);
$st13->bindParam(9, $date_main, PDO::PARAM_STR);
$st13->bindParam(10, $action_type_main, PDO::PARAM_STR);
$st13->bindParam(11, $penordis_main, PDO::PARAM_STR);
$st13->bindParam(12, $server_date, PDO::PARAM_STR);
$st13->bindParam(13, $remarks_main, PDO::PARAM_STR);
$st13->bindParam(14, $sessionUserType, PDO::PARAM_STR);
$st13->execute();

}

else
{
	$sty="insert into $schemas.case_proceeding_his
	(filing_no,listing_date,purpose,court_no,bench_nature,bench_no,
	next_list_purpose,next_list_criteria,
	next_list_date,todays_action,todays_status,entry_date,remarks,user_id)
	VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
	
	$st13=$db->prepare($sty);
//$st13 = array($filing_no,$list_date_link,$purpose_old,$court_no,$bench_nature,$bench_no,$purpose_code_next,$criteria,$next_list_date,$action_type,$pen_dis,$curdate,$remarks,$sessionUserType);
$st13->bindParam(1, $filing_no_main, PDO::PARAM_STR);
$st13->bindParam(2, $listing_date_main, PDO::PARAM_STR);
$st13->bindParam(3, $purpose_old, PDO::PARAM_STR);
$st13->bindParam(4, $court_no, PDO::PARAM_STR);
$st13->bindParam(5, $bench_nature, PDO::PARAM_STR);
$st13->bindParam(6, $bench_no, PDO::PARAM_STR);
$st13->bindParam(7, $purpose_code_main, PDO::PARAM_STR);
$st13->bindParam(8, $criteria, PDO::PARAM_STR);
$st13->bindParam(9, $date_main, PDO::PARAM_STR);
$st13->bindParam(10, $action_type_main, PDO::PARAM_STR);
$st13->bindParam(11, $penordis_main, PDO::PARAM_STR);
$st13->bindParam(12, $server_date, PDO::PARAM_STR);
$st13->bindParam(13, $remarks_main, PDO::PARAM_STR);
$st13->bindParam(14, $sessionUserType, PDO::PARAM_STR);
$st13->execute();

	$update_case_allocation_sql = "update $schemas.case_proceeding set
 filing_no=?,listing_date=?,purpose=?,court_no=?,bench_nature=?,bench_no=?,next_list_purpose=?,next_list_criteria=?, next_list_date=?, todays_action=?, todays_status=?, entry_date=?, remarks=?, user_id=? where filing_no=? and listing_date=?";
 $update_case_allocation_result=$db->prepare($update_case_allocation_sql);
 $update_case_allocation_result->bindParam(1, $filing_no_main, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(2, $listing_date_main, PDO::PARAM_INT);
 $update_case_allocation_result->bindParam(3, $purpose_old, PDO::PARAM_STR);

 $update_case_allocation_result->bindParam(4, $court_no, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(5, $bench_nature, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(6, $bench_no, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(7, $purpose_code_main, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(8, $criteria, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(9, $date_main, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(10, $action_type_main, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(11, $penordis_main, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(12, $server_date, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(13, $remarks_main, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(14, $sessionUserType, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(15, $filing_no_main, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(16, $listing_date_main, PDO::PARAM_STR);
 $update_case_allocation_result->execute();
 
}


 //$cout=count($checkbox);

/*if($cout > 0)
{
for($i=0;$i<$cout;$i++)
{
	$update_connected = "update $schemas.case_allocation set connected='C' where 
 filing_no=?";
 $update_case_allocation_result=$db->prepare($update_case_allocation_sql);
 $update_case_allocation_result->bindParam(1, $checkbox[$i], PDO::PARAM_STR);
	$update_case_allocation_result->execute();
}
}*/

//try{
	//$st->execute($paaaa);
//	}
/*catch(Execption $e)
		{
			echo "Failed:".$e->getMessage();
		}*/

echo "Case Proceeding successfully done";

if($penordis_main == 'P' OR $penordis_main == 'p')
{

$st="insert into $schemas.case_allocation_his_temp (select * from $schemas.case_allocation_temp where filing_no=?)";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no_main, PDO::PARAM_STR);
//$st->execute();

$listed='0';

 $st="update $schemas.case_allocation_temp set
purpose=?,deal_cd=?,entry_date=?,next_list_date=?,listed=? where filing_no=? and listing_date=?";

$st=$db->prepare($st);
$st->bindParam(1, $purpose_code_main, PDO::PARAM_STR);
$st->bindParam(2, $sessionUserType, PDO::PARAM_INT);
$st->bindParam(3, $server_date, PDO::PARAM_STR);
//$st->bindParam(4, $criteria, PDO::PARAM_STR);
$st->bindParam(4, $date_main, PDO::PARAM_STR);
$st->bindParam(5, $listed, PDO::PARAM_STR);
$st->bindParam(6, $filing_no_main, PDO::PARAM_STR);
$st->bindParam(7, $listing_date_main, PDO::PARAM_STR);
$st->execute();
echo "  and updated";
}
}
}
?>