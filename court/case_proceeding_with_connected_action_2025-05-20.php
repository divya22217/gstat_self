<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");
include("../db_inc1.php");
include("../master/functions.php");
require "../vendor/autoload.php";
require_once('../object_storage/S3Service.php');
$s3Service = new S3Service();
use Dompdf\Dompdf;
$dompdf = new Dompdf();
date_default_timezone_set("Asia/Kolkata");
 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{

	die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
	echo "you Can't access this page";
}

/*if( $_POST['frm'] != $_SESSION['form_token'])
{
	echo  'Invalid form submission';

}*/
else
{
try{
	$db->beginTransaction();  // begin transaction
	$location_code = $_SESSION['location'];
$curDay=htmlspecialchars(date("d"));
$curMonth=htmlspecialchars(date("m"));
$curYear=htmlspecialchars(date("Y"));
$cur_date = "$curDay/$curMonth/$curYear";
$curdate="$curYear-$curMonth-$curDay";
$current_timestamp = htmlspecialchars(date("Y-m-d H:i:s"));
$wday = mktime(0,0,0,date("$curMonth"),date("d")-5,date("Y"));
$wday1= date("Y-m-d", $wday);
$username = $_SESSION['user_actual_name'];
$put_api_cases = array();

function error_msg_fun($check,$case_num){
	$validation_msg = "For $case_num Please enter ";
	$count = 0;
	foreach($check as $key=>$value){
		if($value == '' || $value == 0){
			$validation_msg .= $key.' , ';
			$count++;
		}
	}
	if($count > 0){
	 $validation_msg = substr($validation_msg, 0, -2);
	 $return = array("error"=>"yes","validation_msg"=>$validation_msg);
	}else{
	 $return = array("error"=>"no","validation_msg"=>'');
	}
	return $return;
}

function update_parties($db,$parties,$filing_no){
	$document_flag = 'TRUE';
	$party_ids = implode(',',$parties);
	$update_party=$db->prepare("update e_cases_party set document_upload_flag = ? where filing_no = ? and id in ($party_ids)");
	$update_party->bindParam(1, $document_flag, PDO::PARAM_STR);
	$update_party->bindParam(2, $filing_no, PDO::PARAM_STR);
	$update_party->execute();
}

function get_all_childs($schemas,$db,$filing_no,$status = 'P'){
	$select_case=$db->prepare("select filing_no from $schemas.case_detail where main_case_ia_no = ? and status = ? order by filing_no");
	$select_case->bindParam(1, $filing_no, PDO::PARAM_STR);
	$select_case->bindParam(2, $status, PDO::PARAM_STR);
	$select_case->execute();
    $data = $select_case->fetchAll();
	return $data;
}

function get_disposal_nature($schemas,$db,$action_code){
	$select_case=$db->prepare("select action_type from $schemas.master_action where action_code = ?");
	$select_case->bindParam(1, $action_code, PDO::PARAM_STR);
	$select_case->execute();
    $data = $select_case->fetchColumn();
	return $data;
}

function get_case_type_detail($db,$case_type){
	$select_case=$db->prepare("select main_or_child from case_type where id = ?");
	$select_case->bindParam(1, $case_type, PDO::PARAM_STR);
	$select_case->execute();
    $data = $select_case->fetchColumn();
	return $data;
}

function get_bench_detail($db, $location_id)
{
    $sql = "select a.city_name,b.state_name from mater_location_city as a
left join master_states as b on b.state_id = a.city_id
where a.city_id = ?";
    $bench_query = $db->prepare($sql);
    $bench_query->bindParam(1, $location_id, PDO::PARAM_STR);
    $bench_query->execute();
    return $bench_query->fetch();
}

function get_crn_detail($db,$filing_no){
	$query = "select a.dt_of_filing::timestamp::date as filed_date,a.condonation_delay,b.gst_number,b.applent_name,b.crn_number,b.order_number,a.e_reference_no from e_case_detail as a 
		left join e_order_details as b on b.filing_no = a.filing_no where a.filing_no = ?";
    $gst_detail = $db->prepare($query);
    $gst_detail->bindParam(1,$filing_no,PDO::PARAM_STR);
    $gst_detail->execute();
    $gst_data = $gst_detail->fetch();
    return $gst_data;
}

function condonation_delay($db,$filing_no){
	$sql = "select condonation_delay,loginid from e_case_detail where filing_no = ?";
    $bench_query = $db->prepare($sql);
    $bench_query->bindParam(1, $filing_no, PDO::PARAM_STR);
    $bench_query->execute();
    return $bench_query->fetch();
}

function get_holidays($db,$schemas){

	$holidays_query=$db->prepare("select holiday_date from $schemas.holidays order by holiday_date");
	$holidays_query->execute();
    $holidays = $holidays_query->fetchAll();
    $holidays = array_column($holidays, 'holiday_date');
	return $holidays;

}

function recursive_fn_nextlist_date($next_list_date,$holidays){
	if(in_array($next_list_date, $holidays)){
		$date=date_create($next_list_date);
		date_add($date,date_interval_create_from_date_string("1 days"));
		$next_list_date = date_format($date,"Y-m-d");
		return recursive_fn_nextlist_date($next_list_date,$holidays);
	}else{
		return $next_list_date;
	}
}

function callApiAsync($url) {
   // $command = "curl -s $url > /dev/null &";
	// exec($command);
   $curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Cookie: SERVERID=gst_bk_efile_91'
  ),
));
$response = curl_exec($curl);
curl_close($curl);

}

function get_e_case_detail($db,$filing_no){
	$query = "select supply_disputed_questions,refile_count from e_case_detail where filing_no = ?";
    $gst_detail = $db->prepare($query);
    $gst_detail->bindParam(1,$filing_no,PDO::PARAM_STR);
    $gst_detail->execute();
    $gst_data = $gst_detail->fetch();
    return $gst_data;
}

function allow_refiling_date($db,$schemas,$listing_date){
	$query = "WITH RECURSIVE working_days AS (
    SELECT '$listing_date'::date AS check_date,
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
//	echo $query;
$next_date_query = $db->prepare($query);
$next_date_query->execute();
$next_date = $next_date_query->fetchColumn();

$allow_refiling_date = str_replace('/', '-', $next_date);
$allow_refiling_date = date('Y-m-d', strtotime($allow_refiling_date));
}

$bench_detail = get_bench_detail($db, $location_code);
$state_name = $bench_detail['state_name'];
$bench_name = $bench_detail['city_name'];



$msg = '';
$main_case_filing = $_REQUEST['filing_no'];
$main_case_status = $_REQUEST['pen_dis'.$main_case_filing];
$all_cases = array();
$all_cases[] = $main_case_filing; 
$no = $_REQUEST['no'];
$filing_no_link= $_REQUEST['no'];
list($filing_no_link1,$court_no_link,$listing_date_link,$list_before_link,$list_flag,$purpose_old_link,$bench_code1)=explode('@',$filing_no_link);
$parties = (isset($_POST['parties']) && !empty($_POST['parties']))?$_POST['parties']:'';
$schemas=htmlspecialchars($_SESSION['schema_name']);
$sessionUserType=htmlspecialchars($_SESSION['id']);
 $list_date_link1= $_REQUEST['list_date_link'];
if($list_date_link1 !='')
{
	list($day,$month,$year)=explode('/',$list_date_link1);
	 $list_date_link=$year.'-'.$month.'-'.$day;
}
$child_or_connected = (isset($_REQUEST['checkbox']))?$_REQUEST['checkbox']:array();
$all_cases = array_merge($all_cases,$child_or_connected);
/* echo "<pre>"; print_r($all_cases); 
echo "<pre>"; print_r($_POST); die; */
if($main_case_status == 'P'){

//echo "<pre>"; print_r($all_cases); die;
foreach($all_cases as $key=>$filing_no){
	
	$remarks= (isset($_REQUEST['remarks'.$filing_no]))?$_REQUEST['remarks'.$filing_no]:'';
	$remarks = pg_escape_string($remarks);
	$criteria= $_REQUEST['criteria'.$filing_no];
	$next_list_date= (isset($_REQUEST['next_list_date'.$filing_no]))?$_REQUEST['next_list_date'.$filing_no]:'';  //
	$action_type= (isset($_REQUEST['action_type'.$filing_no]))?$_REQUEST['action_type'.$filing_no]:0;
	$purpose_old= (isset($_REQUEST['purpose_old'.$filing_no]))?$_REQUEST['purpose_old'.$filing_no]:0;
	$purpose_code_next= (isset($_REQUEST['purpose_code'.$filing_no]))?$_REQUEST['purpose_code'.$filing_no]:0;
	$disposal_nature= (isset($_REQUEST['disposal_nature'.$filing_no]))?$_REQUEST['disposal_nature'.$filing_no]:0;
	$disposal_date= (isset($_REQUEST['disposal_date'.$filing_no]))?$_REQUEST['disposal_date'.$filing_no]:'';
	$choose_option= (isset($_REQUEST['choose_option_'.$filing_no]))?$_REQUEST['choose_option_'.$filing_no]:0;
	$not_fixed_element= (isset($_REQUEST['not_fixed_element_'.$filing_no]))?$_REQUEST['not_fixed_element_'.$filing_no]:0;
	$not_fixed_date= (isset($_REQUEST['not_fixed_date_'.$filing_no]))?$_REQUEST['not_fixed_date_'.$filing_no]:0;
	$next_listing_court= (isset($_REQUEST['next_list_court'.$filing_no]))?$_REQUEST['next_list_court'.$filing_no]:0;

	

$pen_dis= (isset($_REQUEST['pen_dis'.$filing_no]))?$_REQUEST['pen_dis'.$filing_no]:'';
$sth1 = $db->prepare("select a.case_no,a.case_year,a.case_type,b.short_name,a.regis_date,a.list_with_defect from $schemas.case_detail as a left join case_type as b on b.id = a.case_type where a.filing_no=?");
$sth1->bindParam(1, $filing_no, PDO::PARAM_STR);
$sth1->execute();
$case_info =$sth1->fetchAll();
$case_info = array_shift($case_info);
$case_type = $case_info['case_type'];
$case_no = $case_info['case_no'];
$case_year = $case_info['case_year'];
$case_type_short_name = $case_info['short_name'];
$list_with_defect = $case_info['list_with_defect'];
$registration_date = $case_info['regis_date'];

if($pen_dis =='P' OR $pen_dis=='p' OR $pen_dis =='D' OR $pen_dis =='d')
{
	$viewable_case_no = $case_type_short_name.'/'.$case_no.'/'.$case_year;
	if(strtoupper($pen_dis) == 'P'){
		//$check_array = array('action type'=>$action_type,'purpose'=>$purpose_code_next,'next list date'=>$next_list_date);
		if($purpose_code_next == '19' || $purpose_code_next == '27' || $purpose_code_next == '28'){
		$check_array = array('action type'=>$action_type,'purpose'=>$purpose_code_next);
		}else{
			if($choose_option == '1'){
				$check_array = array('action type'=>$action_type,'purpose'=>$purpose_code_next,'Listing Date'=>$next_list_date);
			}if($choose_option == '2'){
				$check_array = array('action type'=>$action_type,'purpose'=>$purpose_code_next,'Select Option'=>$not_fixed_element, 'Select Option Value'=>$not_fixed_date);
			}
		}
		$error_report = error_msg_fun($check_array,$viewable_case_no);
		if($error_report['error'] == 'yes'){
			$type="danger";
			$msghash1=$type.'-'.$error_report['validation_msg'].'-'.$case_type.'-'.$case_year;
			$msghash=base64_encode($msghash1);
			header("Location:case_proceeding_with_connected1.php?no=$no&msghash=$msghash");
			die;
		}
	}
	if(strtoupper($pen_dis) == 'D'){

		if($disposal_nature == '46'){
			$check_array = array('disposal nature'=>$disposal_nature);
		}else{

		$check_array = array('disposal nature'=>$disposal_nature,'disposal date'=>$disposal_date);
		}

		$error_report = error_msg_fun($check_array,$viewable_case_no);
		if($error_report['error'] == 'yes'){
			$type="danger";
			$msghash1=$type.'-'.$error_report['validation_msg'].'-'.$case_type.'-'.$case_year;
			$msghash=base64_encode($msghash1);
			header("Location:case_proceeding_with_connected1.php?no=$no&msghash=$msghash");
			die;
		}
	}

//===========pending related data===========

if($choose_option == '1'){
	$not_fixed_element = 0;
	$not_fixed_date = '';
	if($next_list_date !='')
	{
		list($day,$month,$year)=explode('/',$next_list_date);
		$next_list_date=$year.'-'.$month.'-'.$day;
	}
	else
	{
		$next_list_date="1111-11-11";
	}
}

if($choose_option == '2'){
	if($not_fixed_element != '' || $not_fixed_element != '0'){
		if($not_fixed_element == '1')
			$days = $not_fixed_date+1;
		else if($not_fixed_element == '2')
			$days = $not_fixed_date*7+1;
		else
			$days = $not_fixed_date*30+1;
		$date=date_create($list_date_link);
		date_add($date,date_interval_create_from_date_string("$days days"));
		$next_list_date = date_format($date,"Y-m-d");
		$holidays = get_holidays($db,$schemas);
		$next_list_date = recursive_fn_nextlist_date($next_list_date,$holidays);
	}else{
		$next_list_date="1111-11-11";
	}
}

if($purpose_code_next == '19' || $purpose_code_next == '27' || $purpose_code_next == '28'){
		$next_list_date = '1111-11-11';
		$not_fixed_element = 0;
		$not_fixed_date = '';
		$choose_option = 0;
}


// echo "<pre>"; print_r($_REQUEST);
// echo $days." days   ".$choose_option." selected option    ".$not_fixed_element."  not fixed element   ".$not_fixed_date;
// echo $next_list_date."  next list date "; die;



//=================close pending related data===============

//=========disposal related data============

if($disposal_nature=='') {$disposal_nature=0;}
if($pen_dis=='D')
{
	$action_type=$disposal_nature;
	$next_list_date = '1111-11-11';

}


$new_status = $pen_dis;


if($disposal_date !='')
{
	list($day,$month,$year)=explode('/',$disposal_date);
	$disposal_date_new=$year.'-'.$month.'-'.$day;

/* $st="select listing_date from $schemas.case_allocation where filing_no=? and listing_date = ? order by listing_date desc limit 1";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $listing_date_link, PDO::PARAM_STR);
$st->execute();
$listdate_compar =$st->fetchColumn(); */

list($year,$month,$day)=explode('-',$list_date_link);
$listdate_comparvad="$year$month$day";

list($day,$month,$year)=explode('/',$disposal_date);
$validatecc="$year$month$day";

list($day,$month,$year)=explode('/',$cur_date );
$validatecutdate="$year$month$day";

if($listdate_compar !='')
{
//if(($validatecc < $listdate_comparvad) OR ($validatecutdate > $validatecc))
if($validatecc < $listdate_comparvad || $validatecc > $listdate_comparvad)
{
    ?>
<a href="./case_proceeding_with_connected1.php?no=<?php echo $no; ?>"><font color="red" size="3"><b>BACK</b></font></a></br>
<?php
echo "For $viewable_case_no <br/>";
echo "Listing Date Is: ".htmlspecialchars($listdate_compar);
echo '</br>';
/* echo "Date Is Not Greater Then Current Date";
echo '</br>'; */
echo "Date Is Not Smaller or Greater Then Listing Date";
die();
}

if('11111111' == $listdate_comparvad)
{
    ?>
<a href="./case_proceeding_with_connected1.php?no=<?php echo $no; ?>"><font color="red" size="3"><b>BACK</b></font></a></br>
<?php
echo "For $viewable_case_no <br/>";
echo "Listing Date Is: ".htmlspecialchars($listdate_compar);
echo '</br>';
    die("Listing Date Not Fixed");
}

}

}
else
{
	$disposal_date_new="1111-11-11";
}

//========close disposal related data========


$sth1 = $db->prepare("select listing_date,purpose,bench_nature,court_no,bench_no,list_flag from $schemas.case_allocation where filing_no=? and listing_date = ? order by listing_date desc limit 1");
$sth1->bindParam(1, $main_case_filing, PDO::PARAM_STR);
$sth1->bindParam(2, $list_date_link, PDO::PARAM_STR);
$sth1->execute();
$ca = $sth1->fetch();
//
//print_r($ca);
//die('sad');
$bench_no=$ca['bench_no'];
$bench_nature=$ca['bench_nature'];
$court_no = $ca['court_no'];
$purpose_old = $ca['purpose'];
$list_date=$ca['listing_date'];
$list_flag = $ca['list_flag'];


if($bench_no=='') $bench_no=0;
if($bench_nature=='') $bench_nature=0;
if($court_no=='') $court_no=0;
if($purpose_old=='') $purpose_old=0;
if($list_date=='') $list_date=$curdate;

 $ipaddress=htmlspecialchars($_SERVER["REMOTE_ADDR"]);
$accesstime=htmlspecialchars(date("Y-m-d H:i:s"));
$accesspage="case_proceeding";


$stkr="select action_type from $schemas.master_action where action_code =?";
$stk=$db->prepare($stkr);
$stk->bindParam(1, $action_type, PDO::PARAM_STR);
$stk->execute();
$action_name =$stk->fetchColumn();

/*start of code to remove duplicacy of record in case_proceeding*/
$check_case_allocation_query = "select filing_no from $schemas.case_proceeding where filing_no=? and listing_date=? limit 1";
//echo $check_case_allocation_query;echo $filing_no;
$check_case_allocation_query=$db->prepare($check_case_allocation_query);
$check_case_allocation_query->bindParam(1, $filing_no, PDO::PARAM_STR);
$check_case_allocation_query->bindParam(2, $list_date_link, PDO::PARAM_STR);
$check_case_allocation_query->execute();
$get_filing_no =$check_case_allocation_query->fetchColumn();
//echo $get_filing_no;

if($get_filing_no==''){
	//echo "ins in case_pro";
$sty="insert into $schemas.case_proceeding
(filing_no,listing_date,purpose,court_no,bench_nature,bench_no,
next_list_purpose,next_list_criteria,
next_list_date,todays_action,todays_status,entry_date,remarks,user_id,next_list_date_selection_option,next_list_date_selection_type,next_list_date_selection_type_value,next_listing_court)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

if($list_date=='') {$list_date=$curdate;}
$st="insert into $schemas.case_proceeding_his
(filing_no,listing_date,purpose,court_no,bench_nature,bench_no,next_list_purpose,next_list_criteria,next_list_date,todays_action,todays_status,entry_date,
remarks,user_id,next_list_date_selection_option,next_list_date_selection_type,next_list_date_selection_type_value,next_listing_court)
VALUES
(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $list_date_link, PDO::PARAM_STR);
$st->bindParam(3, $purpose_old, PDO::PARAM_STR);
$st->bindParam(4, $court_no, PDO::PARAM_INT);
$st->bindParam(5, $bench_nature, PDO::PARAM_INT);
$st->bindParam(6, $bench_no, PDO::PARAM_INT);
$st->bindParam(7, $purpose_code_next, PDO::PARAM_STR);
$st->bindParam(8, $criteria, PDO::PARAM_STR);
$st->bindParam(9, $next_list_date, PDO::PARAM_STR);
$st->bindParam(10, $action_type, PDO::PARAM_INT);
$st->bindParam(11, $new_status, PDO::PARAM_STR);
$st->bindParam(12, $current_timestamp, PDO::PARAM_STR);
$st->bindParam(13, $remarks, PDO::PARAM_STR);
$st->bindParam(14, $sessionUserType, PDO::PARAM_INT);
$st->bindParam(15, $choose_option, PDO::PARAM_INT);
$st->bindParam(16, $not_fixed_element, PDO::PARAM_INT);
$st->bindParam(17, $not_fixed_date, PDO::PARAM_INT);
$st->bindParam(18, $next_listing_court, PDO::PARAM_INT);
$st->execute();

}else{
	//echo "ins in his";
	$sty="insert into $schemas.case_proceeding_his
	(filing_no,listing_date,purpose,court_no,bench_nature,bench_no,
	next_list_purpose,next_list_criteria,
	next_list_date,todays_action,todays_status,entry_date,remarks,user_id,next_list_date_selection_option,next_list_date_selection_type,next_list_date_selection_type_value,next_listing_court)
	VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

	$update_case_allocation_sql = "update $schemas.case_proceeding set
 filing_no=?,listing_date=?,purpose=?, court_no=?, bench_nature=?, bench_no=?, next_list_purpose=?, next_list_criteria=?, next_list_date=?, todays_action=?, todays_status=?, remarks=?, updated_by=?, updated_by_username = ?, updated_date =?, next_list_date_selection_option = ?, next_list_date_selection_type = ?, next_list_date_selection_type_value = ?, next_listing_court = ? where filing_no=? and listing_date=?";
 $update_case_allocation_result=$db->prepare($update_case_allocation_sql);
 $update_case_allocation_result->bindParam(1, $filing_no, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(2, $list_date_link, PDO::PARAM_INT);
 $update_case_allocation_result->bindParam(3, $purpose_old, PDO::PARAM_STR);
 //$st->bindParam(4, $curdate, PDO::PARAM_STR);
 //$st->bindParam(5, $next_list_date, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(4, $court_no, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(5, $bench_nature, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(6, $bench_no, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(7, $purpose_code_next, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(8, $criteria, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(9, $next_list_date, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(10, $action_type, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(11, $new_status, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(12, $remarks, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(13, $sessionUserType, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(14, $username, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(15, $current_timestamp, PDO::PARAM_STR);
  $update_case_allocation_result->bindParam(16, $choose_option, PDO::PARAM_STR);
   $update_case_allocation_result->bindParam(17, $not_fixed_element, PDO::PARAM_STR);
    $update_case_allocation_result->bindParam(18, $not_fixed_date, PDO::PARAM_STR);
    $update_case_allocation_result->bindParam(19, $next_listing_court, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(20, $filing_no, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(21, $list_date_link, PDO::PARAM_STR);
 $update_case_allocation_result->execute();
}


$st=$db->prepare($sty);
$paaaa = array($filing_no,$list_date_link,$purpose_old,$court_no,$bench_nature,$bench_no,$purpose_code_next,$criteria,$next_list_date,$action_type,$new_status,$current_timestamp,$remarks,$sessionUserType,$choose_option,$not_fixed_element,$not_fixed_date,$next_listing_court);

try{
	$st->execute($paaaa);}
catch(Execption $e)
	{
		echo "Failed:".$e->getMessage();
	}
}



if($pen_dis == 'P' OR $pen_dis == 'p')
{
$e_case_detail = get_e_case_detail($db,$filing_no);
$new_court = ($next_listing_court=='0')?$court_no:$next_listing_court;
$st="select count(*) from $schemas.case_allocation_temp where filing_no = ?";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();
$count_rows = $st->fetchColumn();
if($count_rows > 0){
$st="insert into $schemas.case_allocation_his_temp (select * from $schemas.case_allocation_temp where filing_no=?)";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();

$listed='0';

 /* $st="update $schemas.case_allocation_temp set purpose=?,deal_cd=?, entry_date=?,  list_criteria=?
 ,next_list_date=?,listed=? where filing_no=? and listing_date=?"; */
 
 $st="update $schemas.case_allocation_temp set purpose=?,deal_cd=?, entry_date=?,  list_criteria=?
 ,next_list_date=?,listed=?,court_no=? where filing_no=?";

$st=$db->prepare($st);
$st->bindParam(1, $purpose_code_next, PDO::PARAM_STR);
$st->bindParam(2, $sessionUserType, PDO::PARAM_INT);
$st->bindParam(3, $curdate, PDO::PARAM_STR);
$st->bindParam(4, $criteria, PDO::PARAM_STR);
$st->bindParam(5, $next_list_date, PDO::PARAM_STR);
$st->bindParam(6, $listed, PDO::PARAM_STR);
$st->bindParam(7, $new_court, PDO::PARAM_STR);
$st->bindParam(8, $filing_no, PDO::PARAM_STR);
$st->execute();
}else{
	$st="insert into $schemas.case_allocation_temp (filing_no,listing_date,purpose,entry_date,deal_cd,bench_nature,list_criteria,court_no,bench_no,list_flag,next_list_date,listed) 
											values(?,?,?,?,?,?,?,?,?,?,?,?)"; 

$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $list_date_link, PDO::PARAM_STR);
$st->bindParam(3, $purpose_code_next, PDO::PARAM_STR);
$st->bindParam(4, $curdate, PDO::PARAM_STR);
$st->bindParam(5, $sessionUserType, PDO::PARAM_INT);
$st->bindParam(6, $bench_nature, PDO::PARAM_INT);
$st->bindParam(7, $criteria, PDO::PARAM_STR);
$st->bindParam(8, $new_court, PDO::PARAM_STR);
$st->bindParam(9, $bench_no, PDO::PARAM_STR);
$st->bindParam(10, $list_flag, PDO::PARAM_STR);
$st->bindParam(11, $next_list_date, PDO::PARAM_STR);
$st->bindParam(12, $listed, PDO::PARAM_STR);
//$st->bindParam(13, $location_code, PDO::PARAM_STR);
$st->execute();

//$has_keyword=1;
$group_scrutiny=2;
$legal_aid = 'A';
$std = "update $schemas.case_detail set legal_aid = ? where filing_no=?";
$std=$db->prepare($std);
//$std->bindParam(1, $has_keyword, PDO::PARAM_STR);
//$std->bindParam(1, $group_scrutiny, PDO::PARAM_STR);
$std->bindParam(1, $legal_aid, PDO::PARAM_STR);
$std->bindParam(2, $filing_no, PDO::PARAM_STR);
$std->execute();
}
$status_up='P';
$std = "update $schemas.case_detail set status =? where filing_no=?";
$std=$db->prepare($std);
$std->bindParam(1, $status_up, PDO::PARAM_STR);
$std->bindParam(2, $filing_no, PDO::PARAM_STR);
$std->execute();

$std = "select count(*) from $schemas.case_no_generation where filing_no=?";
$std=$db->prepare($std);
$std->bindParam(1, $filing_no, PDO::PARAM_STR);
$std->execute();
$is_exists_in_case_gen = $std->fetchColumn();
$allow_refiling_date = allow_refiling_date($db,$schemas,$list_date_link);
if($is_exists_in_case_gen == 0){
	
	if($list_with_defect == 1 && empty($registration_date) && $action_type == '44' ){
		$query = "update e_case_detail set allow_refiling = 1, scrutiny_count = 0, allow_refiling_date = ? where filing_no = ?";
	 	$std=$db->prepare($query);
		$std->bindParam(1, $allow_refiling_date, PDO::PARAM_STR);
		$std->bindParam(2, $filing_no, PDO::PARAM_STR);
		$std->execute();
	}
	elseif($list_with_defect == 1 && empty($registration_date) && $action_type == '45' ){

		if($e_case_detail['supply_disputed_questions'] == '1' && $e_case_detail['refile_count'] == '1'){

			$query = "update e_case_detail set place_of_supply_accepted = 1 where filing_no = ?";
		 	$std=$db->prepare($query);
			$std->bindParam(1, $filing_no, PDO::PARAM_STR);
			$std->execute();
		
		}else{
			$query = "insert into $schemas.case_no_generation (filing_no,from_type) values (?,'P')";
		 	$std=$db->prepare($query);
			$std->bindParam(1, $filing_no, PDO::PARAM_STR);
			$std->execute();	

		}

		$query = "update e_case_detail set allow_refiling = 0, allow_refiling_date = null  where filing_no = ?";
	 	$std=$db->prepare($query);
		$std->bindParam(1, $filing_no, PDO::PARAM_STR);
		$std->execute();

	}else{
		$query = "delete from $schemas.case_no_generation where filing_no = ?";
	 	$std=$db->prepare($query);
		$std->bindParam(1, $filing_no, PDO::PARAM_STR);
		$std->execute();

		$query = "update e_case_detail set allow_refiling = 0, allow_refiling_date = null where filing_no = ?";
	 	$std=$db->prepare($query);
		$std->bindParam(1, $filing_no, PDO::PARAM_STR);
		$std->execute();
	}
}else{
	if($list_with_defect == 1 && empty($registration_date) && $action_type == '44' ){
		$query = "delete from $schemas.case_no_generation where filing_no = ?";
	 	$std=$db->prepare($query);
		$std->bindParam(1, $filing_no, PDO::PARAM_STR);
		$std->execute();

		$query = "update e_case_detail set allow_refiling = 1, scrutiny_count = 0 , allow_refiling_date = ? where filing_no = ?";
	 	$std=$db->prepare($query);
	 	$std->bindParam(1, $allow_refiling_date, PDO::PARAM_STR);
		$std->bindParam(2, $filing_no, PDO::PARAM_STR);
		$std->execute();
	}
	if($list_with_defect == 1 && empty($registration_date) && $action_type == '45' ){
		$query = "update e_case_detail set allow_refiling = 0, allow_refiling_date = null where filing_no = ?";
	 	$std=$db->prepare($query);
		$std->bindParam(1, $filing_no, PDO::PARAM_STR);
		$std->execute();
		if($e_case_detail['supply_disputed_questions'] == '1' && $e_case_detail['refile_count'] == '1'){

			$query = "update e_case_detail set place_of_supply_accepted = 1 where filing_no = ?";
		 	$std=$db->prepare($query);
			$std->bindParam(1, $filing_no, PDO::PARAM_STR);
			$std->execute();
		
		}
	}else{
		$query = "delete from $schemas.case_no_generation where filing_no = ?";
	 	$std=$db->prepare($query);
		$std->bindParam(1, $filing_no, PDO::PARAM_STR);
		$std->execute();

		$query = "update e_case_detail set allow_refiling = 0, allow_refiling_date = null where filing_no = ?";
	 	$std=$db->prepare($query);
		$std->bindParam(1, $filing_no, PDO::PARAM_STR);
		$std->execute();
	}
	 
}

$msg .= "<br/>$viewable_case_no is proceeded";

// sms and email start

$is_condonation_delay = condonation_delay($db,$filing_no);
$condonation_delay_flag = $is_condonation_delay['condonation_delay'];
$loginid = $is_condonation_delay['loginid'];

if($condonation_delay_flag && $action_type == '32'){

	$subject = "Condonation of delay (allowed)";
	$message = "Dear ".$loginid.", your application number ".$filing_no." for condonation of delay  has been allowed. The case number of your application is ".$viewable_case_no.". This is a computer-generated message, please do not reply. GSTAT-GSTN";
	$email_text = "Dear ".$loginid.", your application number ".$filing_no." for condonation of delay  has been allowed. The case number of your application is ".$viewable_case_no.". This is a computer-generated message, please do not reply. GSTAT-GSTN";

	$send_mail = fn_sms($db, '17', '', $filing_no, $subject, $message, $email_text);
}

// sms and email end

$type='success';
$msghash1=$type.'-'.$msg.'-'.$case_type.'-'.$case_year;
$msghash=base64_encode($msghash1);


}


//CODE FOR CASE DISPOSAL :START
if($pen_dis=='D' OR $pen_dis=='d')
{
	
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
	$msg_txt = "disposed";
	
$st="insert into $schemas.case_disposal_his (select * from $schemas.case_disposal where filing_no = ?)";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();

$st="delete from $schemas.case_disposal where filing_no = ?";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();

$st="insert into $schemas.case_disposal
	(filing_no,disposal_date,disposal_nature,court_no,bench_nature,bench_no,case_type,case_no,case_year,judge_code,remarks,entry_date,user_id)
	VALUES
	(?,?,?,?,?,?,?,?,?,?,?,?,?)";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $disposal_date_new, PDO::PARAM_STR);
$st->bindParam(3, $disposal_nature, PDO::PARAM_STR);
$st->bindParam(4, $court_no, PDO::PARAM_INT);
$st->bindParam(5, $bench_nature, PDO::PARAM_INT);
$st->bindParam(6, $bench_no, PDO::PARAM_INT);
$st->bindParam(7, $case_type, PDO::PARAM_STR);
$st->bindParam(8, $case_no, PDO::PARAM_STR);
$st->bindParam(9, $case_year, PDO::PARAM_STR);
$st->bindParam(10, $judge_code_insert, PDO::PARAM_INT);
$st->bindParam(11, $remarks, PDO::PARAM_STR);
$st->bindParam(12, $curdate, PDO::PARAM_STR);
$st->bindParam(13, $sessionUserType, PDO::PARAM_INT);
$st->execute();

if($disposal_nature != '46'){
	$statusx='D';
	$std = "update $schemas.case_detail set status =? where filing_no=?";
	$std=$db->prepare($std);
	$std->bindParam(1, $new_status, PDO::PARAM_STR);
	$std->bindParam(2, $filing_no, PDO::PARAM_STR);
	$std->execute();
}
	
	$st="insert into $schemas.case_allocation_his_temp (select * from $schemas.case_allocation_temp where filing_no=?)";
	$st=$db->prepare($st);
	$st->bindParam(1, $filing_no, PDO::PARAM_STR);
	$st->execute();
	
	$next_l_date = '1111-11-11';
	$st="update $schemas.case_allocation_temp set next_list_date=? where filing_no=?";

	$st=$db->prepare($st);
	$st->bindParam(1, $next_l_date, PDO::PARAM_STR);
	$st->bindParam(2, $filing_no, PDO::PARAM_STR);
	$st->execute();

	//set status =2 in case_allocation table
	$statusz='2';
	$criteria_from_datez='1111-11-11';

	$msg .="<br/>$viewable_case_no Case is $msg_txt";

	// for apl02A start

	$is_main_case = get_case_type_detail($db,$case_type);
	$is_condonation_delay = condonation_delay($db,$filing_no);
	$condonation_delay_flag = $is_condonation_delay['condonation_delay'];
	$loginid = $is_condonation_delay['loginid'];

	if($condonation_delay_flag  && $disposal_nature == '3'){
		$attachment = '';
		$template_id = 18;
		$subject = "Condonation of delay (dismissed)";
		$message = "Dear ".$loginid.", your application number ".$filing_no." for condonation of delay has been dismissed. This is a computer-generated message, please do not reply. GSTAT-GSTN";
		$email_text = "Dear ".$loginid.", your application number ".$filing_no." for condonation of delay has been dismissed. This is a computer-generated message, please do not reply. GSTAT-GSTN";
	}else{

		if($disposal_nature == '38' && $is_main_case == 'M'){

			$crn_detail = get_crn_detail($db,$filing_no);
			$crn_number = $crn_detail['crn_number'];
			$filed_date = $crn_detail['filed_date'];
			$order_number = $crn_detail['order_number'];
			$e_reference_no = $crn_detail['e_reference_no'];

			$pdf_html = '<div style="text-align:center;"><p>Form GST APL-02 Part B</p><p><b>Final Acknowledgement for registration of Appeal/Application</b></p><p>Your Appeal/application filed vide provisional acknowledgment reference number '.$filing_no.' dated '.date('d/m/Y',strtotime($filed_date)).' has been rejected</p></div><table style="width:100%"><tr><td><b>Date of rejection:  '.date('d/m/Y',strtotime($disposal_date_new)).'</b></td><td style="text-align:right"><b>Registrar<br/>GSTAT: '.$bench_name .' Bench</b></td></tr></table>';


			     	$time = time();				
					$pdf_file_name=$filing_no."-apl02A-".$time;
				  $filename=$pdf_file_name.".pdf";
				  $filename_sms = $pdf_file_name;
				 $dompdf->loadHtml($pdf_html);
				  $dompdf->setPaper('A4');
				  $dompdf->render();
				  $outputff = $dompdf->output();
				  $upload_dir = "Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/apl02A/$filing_no";
				  $pp_path = $upload_dir."/$filename";
				  $save_path = $upload_dir."/$filename";
				  if (!file_exists($save_path)) {
						mkdir($upload_dir, 0777, true);
					}
				  $save_file = file_put_contents($pp_path, $outputff);
				  $save_apl = $s3Service->uploadDynamicFile($outputff, $save_path);
				  $pdf_hash = hash_file('sha256', $pp_path);
				$apl_form= $db->prepare("update e_case_detail set apl_02b_form_path = ?, apl_02b_accept_reject = 2, pdf_hash = ?  where filing_no = ?");
				$apl_form->bindParam(1, $pp_path, PDO::PARAM_STR);
				$apl_form->bindParam(2, $pdf_hash, PDO::PARAM_STR);
				$apl_form->bindParam(3, $filing_no, PDO::PARAM_STR);
				$apl_form->execute();

				$today_date = date('Y-m-d');
				$doctype = 8;
				$subdoctype = 178;
				$docum_type = "APL02_REJECTED";
				$party_name = "apl02";
				$doc_level = 9;
				$save_doc = save_document_uplaod($db,$doctype,$pp_path,$sessionUserType,$subdoctype,$e_reference_no,$filename,$docum_type,'',$filename,$filing_no,true,1,$party_name,$list_date,'A');
				unlink($pp_path);
				array_push($put_api_cases, $filing_no);
			}

		// for apl02A end

		// for sms and email start

		$disposal_nature_text = get_disposal_nature($schemas,$db,$disposal_nature);
		$gstat_bench = "court $court_no , $bench_name, $state_name";
		$subject = "Your Case is disposed";

		if($is_main_case == 'M'){
			$apl_02_A = '';
			$attachment = '';
			$template_id = 15;
			$message = "Your case number ".$viewable_case_no." listed before the ".$gstat_bench." on ".$disposal_date." has been disposed-off by way of being ".$disposal_nature_text.". This is a computer-generated message, please do not reply. GSTAT-GSTN";
			if($disposal_nature == '38'){
				$apl_02_A = " Please find attached APL-02 Part B";
				$attachment = $save_path;
			}
			$email_text = "Your case number ".$viewable_case_no." listed before the ".$gstat_bench." on ".$disposal_date." has been disposed-off by way of being ".$disposal_nature_text.". ".$apl_02_A." This is a computer-generated message, please do not reply. GSTAT-GSTN";

		}else{
			$attachment = '';
			$template_id = 16;
			$message = "Your application number ".$viewable_case_no." listed before the ".$gstat_bench."on ".$disposal_date." has been disposed-off by way of being ".$disposal_nature_text.". This is a computer-generated message, please do not reply. GSTAT-GSTN";
			$email_text = "Your application number ".$viewable_case_no." listed before the ".$gstat_bench."on ".$disposal_date." has been disposed-off by way of being ".$disposal_nature_text.". This is a computer-generated message, please do not reply. GSTAT-GSTN";
		}
	}

	$send_mail = fn_sms($db, $template_id, $attachment, $filing_no, $subject, $message, $email_text);
	// for sms and email end

	$type='success';
	$msghash1=$type.'-'.$msg.'-'.$case_type.'-'.$case_year;
	$msghash=base64_encode($msghash1);

}
}

if(!empty($parties)){
	//update_parties($db,$parties,$filing_no);
}
}else{
	$other_childs = array();
$sqlq="select case_year,case_no,case_type,location_code,main_case_ia_no from $schemas.case_detail where filing_no=?";
$sanR = $db->prepare($sqlq);
$sanR->execute(array($filing_no_link1));
$sanR_data = $sanR->fetch();
$main_cases = main_case_type();	
$main_case_fn = '';
if (in_array($sanR_data['case_type'], $main_cases)){
	
}else{
	$main_case_fn  = htmlspecialchars($sanR_data['main_case_ia_no']);
}	
$connected_cases = selected_final_cases($schemas,$db,$filing_no_link1,1,$list_date_link,$bench_code1,$list_flag,$court_no_link,'C','');
$child_cases = selected_final_cases($schemas,$db,$filing_no_link1,1,$list_date_link,$bench_code1,$list_flag,$court_no_link,'I','');
if(!empty($main_case_fn)){
	$other_childs = selected_final_cases($schemas,$db,$main_case_fn,1,$list_date_link,$bench_code1,$list_flag,$court_no_link,'I',$filing_no_link1);
}
$all_childs = get_all_childs($schemas,$db,$filing_no_link1);
//$child_cases = array_merge($child_cases,$other_childs);
$all_conn_child = array_merge($child_cases,$other_childs);
$all_conn_child = array_merge($child_cases,$connected_cases);
$seperate_proceeding_cases = array();
$cases_to_disposed_with_main_case = array();
$all_cases_to_dispose[] = $main_case_filing;
foreach($all_conn_child as $key=>$value){
	$count = 0;
	$c_filing_no = $value['filing_no'];
	foreach($all_cases as $k=>$v){
		if($c_filing_no == $v){
			$seperate_proceeding_cases[] = $v;
			$count = 1;
		}
	}
	if($count == 0){
		if($value['child_or_connected'] != 'C'){
			$cases_to_disposed_with_main_case[] = $c_filing_no;
		}
	}
}
$all_cases_to_dispose = array_merge($all_cases_to_dispose,$cases_to_disposed_with_main_case);
$all_childs_array = array();
foreach($all_childs as $k=>$val){
	$all_childs_array[] = $val['filing_no'];
}
$all_childs_array = array_diff($all_childs_array,$seperate_proceeding_cases);

$all_cases_to_dispose = array_merge($all_cases_to_dispose,$all_childs_array);
$all_cases_to_dispose = array_unique($all_cases_to_dispose);

foreach($seperate_proceeding_cases as $key=>$filing_no){
	
	$remarks= (isset($_REQUEST['remarks'.$filing_no]))?$_REQUEST['remarks'.$filing_no]:'';
	$remarks = pg_escape_string($remarks);
	$criteria= $_REQUEST['criteria'.$filing_no];
	$next_list_date= (isset($_REQUEST['next_list_date'.$filing_no]))?$_REQUEST['next_list_date'.$filing_no]:'';  //
	$action_type= (isset($_REQUEST['action_type'.$filing_no]))?$_REQUEST['action_type'.$filing_no]:0;
	$purpose_old= (isset($_REQUEST['purpose_old'.$filing_no]))?$_REQUEST['purpose_old'.$filing_no]:0;
	$purpose_code_next= (isset($_REQUEST['purpose_code'.$filing_no]))?$_REQUEST['purpose_code'.$filing_no]:0;
	$disposal_nature= (isset($_REQUEST['disposal_nature'.$filing_no]))?$_REQUEST['disposal_nature'.$filing_no]:0;
	$disposal_date= (isset($_REQUEST['disposal_date'.$filing_no]))?$_REQUEST['disposal_date'.$filing_no]:'';
	$choose_option= (isset($_REQUEST['choose_option_'.$filing_no]))?$_REQUEST['choose_option_'.$filing_no]:0;
	$not_fixed_element= (isset($_REQUEST['not_fixed_element_'.$filing_no]))?$_REQUEST['not_fixed_element_'.$filing_no]:0;
	$not_fixed_date= (isset($_REQUEST['not_fixed_date_'.$filing_no]))?$_REQUEST['not_fixed_date_'.$filing_no]:0;
	$next_listing_court= (isset($_REQUEST['next_list_court'.$filing_no]))?$_REQUEST['next_list_court'.$filing_no]:0;	
$pen_dis= (isset($_REQUEST['pen_dis'.$filing_no]))?$_REQUEST['pen_dis'.$filing_no]:'';

$sth1 = $db->prepare("select a.case_no,a.case_year,a.case_type,b.short_name,a.regis_date,a.list_with_defect  from $schemas.case_detail as a left join case_type as b on b.id = a.case_type where a.filing_no=?");
$sth1->bindParam(1, $filing_no, PDO::PARAM_STR);
$sth1->execute();
$case_info =$sth1->fetchAll();
$case_info = array_shift($case_info);
$case_type = $case_info['case_type'];
$case_no = $case_info['case_no'];
$case_year = $case_info['case_year'];
$case_type_short_name = $case_info['short_name'];
$list_with_defect = $case_info['list_with_defect'];
$registration_date = $case_info['regis_date'];

if($pen_dis =='P' OR $pen_dis=='p' OR $pen_dis =='D' OR $pen_dis =='d')
{
	$viewable_case_no = $case_type_short_name.'/'.$case_no.'/'.$case_year;
	if(strtoupper($pen_dis) == 'P'){
		//$check_array = array('action type'=>$action_type,'purpose'=>$purpose_code_next,'next list date'=>$next_list_date);
		if($purpose_code_next == '19'  || $purpose_code_next == '27' || $purpose_code_next == '28'){
		$check_array = array('action type'=>$action_type,'purpose'=>$purpose_code_next);
		}else{
		if($choose_option == '1'){
				$check_array = array('action type'=>$action_type,'purpose'=>$purpose_code_next,'Listing Date'=>$next_list_date);
			}if($choose_option == '2'){
				$check_array = array('action type'=>$action_type,'purpose'=>$purpose_code_next,'Select Option'=>$not_fixed_element, 'Select Option Value'=>$not_fixed_date);
			}
		}
		$error_report = error_msg_fun($check_array,$viewable_case_no);
		if($error_report['error'] == 'yes'){
			$type="danger";
			$msghash1=$type.'-'.$error_report['validation_msg'].'-'.$case_type.'-'.$case_year;
			$msghash=base64_encode($msghash1);
			header("Location:case_proceeding_with_connected1.php?no=$no&msghash=$msghash");
			die;
		}
	}
	if(strtoupper($pen_dis) == 'D'){

		if($disposal_nature == '46'){
			$check_array = array('disposal nature'=>$disposal_nature);
		}else{

		$check_array = array('disposal nature'=>$disposal_nature,'disposal date'=>$disposal_date);
		}
		
		$error_report = error_msg_fun($check_array,$viewable_case_no);
		if($error_report['error'] == 'yes'){
			$type="danger";
			$msghash1=$type.'-'.$error_report['validation_msg'].'-'.$case_type.'-'.$case_year;
			$msghash=base64_encode($msghash1);
			header("Location:case_proceeding_with_connected1.php?no=$no&msghash=$msghash");
			die;
		}
	}

//===========pending related data===========



if($choose_option == '1'){
	$not_fixed_element = 0;
	$not_fixed_date = '';
	if($next_list_date !='')
	{
		list($day,$month,$year)=explode('/',$next_list_date);
		$next_list_date=$year.'-'.$month.'-'.$day;
	}
	else
	{
		$next_list_date="1111-11-11";
	}
}

if($choose_option == '2'){
	if($not_fixed_element != '' || $not_fixed_element != '0'){
		if($not_fixed_element == '1')
			$days = $not_fixed_date+1;
		else if($not_fixed_element == '2')
			$days = $not_fixed_date*7+1;
		else
			$days = $not_fixed_date*30+1;
		$date=date_create($list_date_link);
		date_add($date,date_interval_create_from_date_string("$days days"));
		$next_list_date = date_format($date,"Y-m-d");
		$holidays = get_holidays($db,$schemas);
		$next_list_date = recursive_fn_nextlist_date($next_list_date,$holidays);
	}else{
		$next_list_date="1111-11-11";
	}
}

if($purpose_code_next == '19' || $purpose_code_next == '27' || $purpose_code_next == '28'){
		$next_list_date = '1111-11-11';
		$not_fixed_element = 0;
		$not_fixed_date = '';
		$choose_option = 0;
}
//=================close pending related data===============

//=========disposal related data============

if($disposal_nature=='') {$disposal_nature=0;}
if($pen_dis=='D')
{
	$action_type=$disposal_nature;
	$next_list_date = '1111-11-11';

}


$new_status = $pen_dis;


if($disposal_date !='')
{
	list($day,$month,$year)=explode('/',$disposal_date);
	$disposal_date_new=$year.'-'.$month.'-'.$day;

/* $st="select listing_date from $schemas.case_allocation where filing_no=? and listing_date = ? order by listing_date desc limit 1";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $listing_date_link, PDO::PARAM_STR);
$st->execute();
$listdate_compar =$st->fetchColumn(); */

list($year,$month,$day)=explode('-',$list_date_link);
$listdate_comparvad="$year$month$day";

list($day,$month,$year)=explode('/',$disposal_date);
$validatecc="$year$month$day";

list($day,$month,$year)=explode('/',$cur_date );
$validatecutdate="$year$month$day";

if($listdate_compar !='')
{
//if(($validatecc < $listdate_comparvad) OR ($validatecutdate > $validatecc))
if($validatecc < $listdate_comparvad || $validatecc > $listdate_comparvad)
{
    ?>
<a href="./case_proceeding_with_connected1.php?no=<?php echo $no; ?>"><font color="red" size="3"><b>BACK</b></font></a></br>
<?php
echo "For $viewable_case_no <br/>";
echo "Listing Date Is: ".htmlspecialchars($listdate_compar);
echo '</br>';
/* echo "Date Is Not Greater Then Current Date";
echo '</br>'; */
echo "Date Is Not Smaller or Greater Then Listing Date";
die();
}

if('11111111' == $listdate_comparvad)
{
    ?>
<a href="./case_proceeding_with_connected1.php?no=<?php echo $no; ?>"><font color="red" size="3"><b>BACK</b></font></a></br>
<?php
echo "For $viewable_case_no <br/>";
echo "Listing Date Is: ".htmlspecialchars($listdate_compar);
echo '</br>';
    die("Listing Date Not Fixed");
}

}

}
else
{
	$disposal_date_new="1111-11-11";
}

//========close disposal related data========


$sth1 = $db->prepare("select listing_date,purpose,bench_nature,court_no,bench_no,list_flag from $schemas.case_allocation where filing_no=? and listing_date = ? order by listing_date desc limit 1");
$sth1->bindParam(1, $main_case_filing, PDO::PARAM_STR);
$sth1->bindParam(2, $list_date_link, PDO::PARAM_STR);
$sth1->execute();
$ca = $sth1->fetch();
//
//print_r($ca);
//die('sad');
$bench_no=$ca['bench_no'];
$bench_nature=$ca['bench_nature'];
$court_no = $ca['court_no'];
$purpose_old = $ca['purpose'];
$list_date=$ca['listing_date'];
$list_flag = $ca['list_flag'];


if($bench_no=='') $bench_no=0;
if($bench_nature=='') $bench_nature=0;
if($court_no=='') $court_no=0;
if($purpose_old=='') $purpose_old=0;
if($list_date=='') $list_date=$curdate;

 $ipaddress=htmlspecialchars($_SERVER["REMOTE_ADDR"]);
$accesstime=htmlspecialchars(date("Y-m-d H:i:s"));
$accesspage="case_proceeding";


$stkr="select action_type from $schemas.master_action where action_code =?";
$stk=$db->prepare($stkr);
$stk->bindParam(1, $action_type, PDO::PARAM_STR);
$stk->execute();
$action_name =$stk->fetchColumn();

/*start of code to remove duplicacy of record in case_proceeding*/
$check_case_allocation_query = "select filing_no from $schemas.case_proceeding where filing_no=? and listing_date=? limit 1";
//echo $check_case_allocation_query;echo $filing_no;
$check_case_allocation_query=$db->prepare($check_case_allocation_query);
$check_case_allocation_query->bindParam(1, $filing_no, PDO::PARAM_STR);
$check_case_allocation_query->bindParam(2, $list_date_link, PDO::PARAM_STR);
$check_case_allocation_query->execute();
$get_filing_no =$check_case_allocation_query->fetchColumn();
//echo $get_filing_no;

if($get_filing_no==''){
	//echo "ins in case_pro";
$sty="insert into $schemas.case_proceeding
(filing_no,listing_date,purpose,court_no,bench_nature,bench_no,
next_list_purpose,next_list_criteria,
next_list_date,todays_action,todays_status,entry_date,remarks,user_id,next_list_date_selection_option,next_list_date_selection_type,next_list_date_selection_type_value,next_listing_court)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

if($list_date=='') {$list_date=$curdate;}
$st="insert into $schemas.case_proceeding_his
(filing_no,listing_date,purpose,court_no,bench_nature,bench_no,next_list_purpose,next_list_criteria,next_list_date,todays_action,todays_status,entry_date,
remarks,user_id,next_list_date_selection_option,next_list_date_selection_type,next_list_date_selection_type_value,next_listing_court)
VALUES
(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $list_date_link, PDO::PARAM_STR);
$st->bindParam(3, $purpose_old, PDO::PARAM_STR);
$st->bindParam(4, $court_no, PDO::PARAM_INT);
$st->bindParam(5, $bench_nature, PDO::PARAM_INT);
$st->bindParam(6, $bench_no, PDO::PARAM_INT);
$st->bindParam(7, $purpose_code_next, PDO::PARAM_STR);
$st->bindParam(8, $criteria, PDO::PARAM_STR);
$st->bindParam(9, $next_list_date, PDO::PARAM_STR);
$st->bindParam(10, $action_type, PDO::PARAM_INT);
$st->bindParam(11, $new_status, PDO::PARAM_STR);
$st->bindParam(12, $current_timestamp, PDO::PARAM_STR);
$st->bindParam(13, $remarks, PDO::PARAM_STR);
$st->bindParam(14, $sessionUserType, PDO::PARAM_INT);
$st->bindParam(15, $choose_option, PDO::PARAM_INT);
$st->bindParam(16, $not_fixed_element, PDO::PARAM_INT);
$st->bindParam(17, $not_fixed_date, PDO::PARAM_INT);
$st->bindParam(18, $next_listing_court, PDO::PARAM_INT);
$st->execute();

}else{
	//echo "ins in his";
	$sty="insert into $schemas.case_proceeding_his
	(filing_no,listing_date,purpose,court_no,bench_nature,bench_no,
	next_list_purpose,next_list_criteria,
	next_list_date,todays_action,todays_status,entry_date,remarks,user_id,next_list_date_selection_option,next_list_date_selection_type,next_list_date_selection_type_value,next_listing_court)
	VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

	$update_case_allocation_sql = "update $schemas.case_proceeding set
 filing_no=?,listing_date=?,purpose=?, court_no=?, bench_nature=?, bench_no=?, next_list_purpose=?, next_list_criteria=?, next_list_date=?, todays_action=?, todays_status=?, remarks=?, updated_by=?, updated_by_username = ?, updated_date =?, next_list_date_selection_option=?, next_list_date_selection_type=?, next_list_date_selection_type_value=?, next_listing_court = ? where filing_no=? and listing_date=?";
 $update_case_allocation_result=$db->prepare($update_case_allocation_sql);
 $update_case_allocation_result->bindParam(1, $filing_no, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(2, $list_date_link, PDO::PARAM_INT);
 $update_case_allocation_result->bindParam(3, $purpose_old, PDO::PARAM_STR);
 //$st->bindParam(4, $curdate, PDO::PARAM_STR);
 //$st->bindParam(5, $next_list_date, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(4, $court_no, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(5, $bench_nature, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(6, $bench_no, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(7, $purpose_code_next, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(8, $criteria, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(9, $next_list_date, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(10, $action_type, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(11, $new_status, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(12, $remarks, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(13, $sessionUserType, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(14, $username, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(15, $current_timestamp, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(16, $choose_option, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(17, $not_fixed_element, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(18, $not_fixed_date, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(19, $next_listing_court, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(20, $filing_no, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(21, $list_date_link, PDO::PARAM_STR);
 $update_case_allocation_result->execute();
}


$st=$db->prepare($sty);
$paaaa = array($filing_no,$list_date_link,$purpose_old,$court_no,$bench_nature,$bench_no,$purpose_code_next,$criteria,$next_list_date,$action_type,$new_status,$current_timestamp,$remarks,$sessionUserType,$choose_option,$not_fixed_element,$not_fixed_date,$next_listing_court);

try{
	$st->execute($paaaa);}
catch(Execption $e)
	{
		echo "Failed:".$e->getMessage();
	}
}



if($pen_dis == 'P' OR $pen_dis == 'p')
{
	$e_case_detail = get_e_case_detail($db,$filing_no);
$new_court = ($next_listing_court=='0')?$court_no:$next_listing_court;
$st="select count(*) from $schemas.case_allocation_temp where filing_no = ?";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();
$count_rows = $st->fetchColumn();
if($count_rows > 0){
$st="insert into $schemas.case_allocation_his_temp (select * from $schemas.case_allocation_temp where filing_no=?)";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();

$listed='0';

 /* $st="update $schemas.case_allocation_temp set purpose=?,deal_cd=?, entry_date=?,  list_criteria=?
 ,next_list_date=?,listed=? where filing_no=? and listing_date=?"; */
 
 $st="update $schemas.case_allocation_temp set purpose=?,deal_cd=?, entry_date=?,  list_criteria=?
 ,next_list_date=?,listed=?,court_no=? where filing_no=?";

$st=$db->prepare($st);
$st->bindParam(1, $purpose_code_next, PDO::PARAM_STR);
$st->bindParam(2, $sessionUserType, PDO::PARAM_INT);
$st->bindParam(3, $curdate, PDO::PARAM_STR);
$st->bindParam(4, $criteria, PDO::PARAM_STR);
$st->bindParam(5, $next_list_date, PDO::PARAM_STR);
$st->bindParam(6, $listed, PDO::PARAM_STR);
$st->bindParam(7, $new_court, PDO::PARAM_STR);
$st->bindParam(8, $filing_no, PDO::PARAM_STR);
$st->execute();
}else{
	$st="insert into $schemas.case_allocation_temp (filing_no,listing_date,purpose,entry_date,deal_cd,bench_nature,list_criteria,court_no,bench_no,list_flag,next_list_date,listed) 
											values(?,?,?,?,?,?,?,?,?,?,?,?)"; 

$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $list_date_link, PDO::PARAM_STR);
$st->bindParam(3, $purpose_code_next, PDO::PARAM_STR);
$st->bindParam(4, $curdate, PDO::PARAM_STR);
$st->bindParam(5, $sessionUserType, PDO::PARAM_INT);
$st->bindParam(6, $bench_nature, PDO::PARAM_INT);
$st->bindParam(7, $criteria, PDO::PARAM_STR);
$st->bindParam(8, $new_court, PDO::PARAM_STR);
$st->bindParam(9, $bench_no, PDO::PARAM_STR);
$st->bindParam(10, $list_flag, PDO::PARAM_STR);
$st->bindParam(11, $next_list_date, PDO::PARAM_STR);
$st->bindParam(12, $listed, PDO::PARAM_STR);
//$st->bindParam(13, $location_code, PDO::PARAM_STR);
$st->execute();

//$has_keyword=1;
//$group_scrutiny=2;
$legal_aid = 'A';
$std = "update $schemas.case_detail set legal_aid = ? where filing_no=?";
$std=$db->prepare($std);
$std->bindParam(1, $legal_aid, PDO::PARAM_STR);
$std->bindParam(2, $filing_no, PDO::PARAM_STR);
$std->execute();
}
$status_up='P';
$std = "update $schemas.case_detail set status =? where filing_no=?";
$std=$db->prepare($std);
$std->bindParam(1, $status_up, PDO::PARAM_STR);
$std->bindParam(2, $filing_no, PDO::PARAM_STR);
$std->execute();

$std = "select count(*) from $schemas.case_no_generation where filing_no=?";
$std=$db->prepare($std);
$std->bindParam(1, $filing_no, PDO::PARAM_STR);
$std->execute();
$is_exists_in_case_gen = $std->fetchColumn();
$allow_refiling_date = allow_refiling_date($db,$schemas,$list_date_link);
if($is_exists_in_case_gen == 0){
	if($list_with_defect == 1 && empty($registration_date) && $action_type == '44' ){
		$query = "update e_case_detail set allow_refiling = 1, scrutiny_count = 0, allow_refiling_date = ? where filing_no = ?";
	 	$std=$db->prepare($query);
	 	$std->bindParam(1, $allow_refiling_date, PDO::PARAM_STR);
		$std->bindParam(2, $filing_no, PDO::PARAM_STR);
		$std->execute();
	}
	elseif($list_with_defect == 1 && empty($registration_date) && $action_type == '45' ){
		if($e_case_detail['supply_disputed_questions'] == '1' && $e_case_detail['refile_count'] == '1'){

			$query = "update e_case_detail set place_of_supply_accepted = 1 where filing_no = ?";
		 	$std=$db->prepare($query);
			$std->bindParam(1, $filing_no, PDO::PARAM_STR);
			$std->execute();
		
		}else{
			$query = "insert into $schemas.case_no_generation (filing_no,from_type) values (?,'P')";
		 	$std=$db->prepare($query);
			$std->bindParam(1, $filing_no, PDO::PARAM_STR);
			$std->execute();	

		}

		$query = "update e_case_detail set allow_refiling = 0, allow_refiling_date = null where filing_no = ?";
	 	$std=$db->prepare($query);
		$std->bindParam(1, $filing_no, PDO::PARAM_STR);
		$std->execute();

	}else{
		$query = "delete from $schemas.case_no_generation where filing_no = ?";
	 	$std=$db->prepare($query);
		$std->bindParam(1, $filing_no, PDO::PARAM_STR);
		$std->execute();

		$query = "update e_case_detail set allow_refiling = 0, allow_refiling_date = null where filing_no = ?";
	 	$std=$db->prepare($query);
		$std->bindParam(1, $filing_no, PDO::PARAM_STR);
		$std->execute();
	}
}else{
	if($list_with_defect == 1 && empty($registration_date) && $action_type == '44' ){
		$query = "delete from $schemas.case_no_generation where filing_no = ?";
	 	$std=$db->prepare($query);
		$std->bindParam(1, $filing_no, PDO::PARAM_STR);
		$std->execute();

		$query = "update e_case_detail set allow_refiling = 1 , scrutiny_count = 0, allow_refiling_date = ? where filing_no = ?";
	 	$std=$db->prepare($query);
	 	$std->bindParam(1, $allow_refiling_date, PDO::PARAM_STR);
		$std->bindParam(2, $filing_no, PDO::PARAM_STR);
		$std->execute();
	}
	if($list_with_defect == 1 && empty($registration_date) && $action_type == '45' ){
		$query = "update e_case_detail set allow_refiling = 0, allow_refiling_date = null where filing_no = ?";
	 	$std=$db->prepare($query);
		$std->bindParam(1, $filing_no, PDO::PARAM_STR);
		$std->execute();

		if($e_case_detail['supply_disputed_questions'] == '1' && $e_case_detail['refile_count'] == '1'){

			$query = "update e_case_detail set place_of_supply_accepted = 1 where filing_no = ?";
		 	$std=$db->prepare($query);
			$std->bindParam(1, $filing_no, PDO::PARAM_STR);
			$std->execute();
		
		}
	}else{
		$query = "delete from $schemas.case_no_generation where filing_no = ?";
	 	$std=$db->prepare($query);
		$std->bindParam(1, $filing_no, PDO::PARAM_STR);
		$std->execute();

		$query = "update e_case_detail set allow_refiling = 0, allow_refiling_date = null where filing_no = ?";
	 	$std=$db->prepare($query);
		$std->bindParam(1, $filing_no, PDO::PARAM_STR);
		$std->execute();
	}
	 
}

$msg .= "<br/>$viewable_case_no is proceeded";

// sms and email start

if($action_type == '32'){

	$subject = "Condonation of delay (allowed)";
	$message = "Dear {#var#}, your application number ".$filing_no." for condonation of delay  has been allowed. The case number of your application is ".$viewable_case_no.". This is a computer-generated message, please do not reply. GSTAT-GSTN";
	$email_text = "Dear {#var#}, your application number ".$filing_no." for condonation of delay  has been allowed. The case number of your application is ".$viewable_case_no.". This is a computer-generated message, please do not reply. GSTAT-GSTN";

	$send_mail = fn_sms($db, '17', '', $filing_no, $subject, $message, $email_text);
}
// sms and email end

$type='success';
$msghash1=$type.'-'.$msg.'-'.$case_type.'-'.$case_year;
$msghash=base64_encode($msghash1);


}


//CODE FOR CASE DISPOSAL :START
if($pen_dis=='D' OR $pen_dis=='d')
{
	
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
	$msg_txt = "disposed";
	
$st="insert into $schemas.case_disposal_his (select * from $schemas.case_disposal where filing_no = ?)";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();

$st="delete from $schemas.case_disposal where filing_no = ?";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);

$st->execute();

$st="insert into $schemas.case_disposal
	(filing_no,disposal_date,disposal_nature,court_no,bench_nature,bench_no,case_type,case_no,case_year,judge_code,remarks,entry_date,user_id)
	VALUES
	(?,?,?,?,?,?,?,?,?,?,?,?,?)";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $disposal_date_new, PDO::PARAM_STR);
$st->bindParam(3, $disposal_nature, PDO::PARAM_STR);
$st->bindParam(4, $court_no, PDO::PARAM_INT);
$st->bindParam(5, $bench_nature, PDO::PARAM_INT);
$st->bindParam(6, $bench_no, PDO::PARAM_INT);
$st->bindParam(7, $case_type, PDO::PARAM_STR);
$st->bindParam(8, $case_no, PDO::PARAM_STR);
$st->bindParam(9, $case_year, PDO::PARAM_STR);
$st->bindParam(10, $judge_code_insert, PDO::PARAM_INT);
$st->bindParam(11, $remarks, PDO::PARAM_STR);
$st->bindParam(12, $curdate, PDO::PARAM_STR);
$st->bindParam(13, $sessionUserType, PDO::PARAM_INT);
$st->execute();

if($disposal_nature != '46'){
	$statusx='D';
	$std = "update $schemas.case_detail set status =? where filing_no=?";
	$std=$db->prepare($std);
	$std->bindParam(1, $new_status, PDO::PARAM_STR);
	$std->bindParam(2, $filing_no, PDO::PARAM_STR);
	$std->execute();
}
	
	$st="insert into $schemas.case_allocation_his_temp (select * from $schemas.case_allocation_temp where filing_no=?)";
	$st=$db->prepare($st);
	$st->bindParam(1, $filing_no, PDO::PARAM_STR);
	$st->execute();
	
	$next_l_date = '1111-11-11';
	$st="update $schemas.case_allocation_temp set next_list_date=? where filing_no=?";

	$st=$db->prepare($st);
	$st->bindParam(1, $next_l_date, PDO::PARAM_STR);
	$st->bindParam(2, $filing_no, PDO::PARAM_STR);
	$st->execute();

	//set status =2 in case_allocation table
	$statusz='2';
	$criteria_from_datez='1111-11-11';

	$msg .="<br/>$viewable_case_no Case is $msg_txt";

	// for apl02A start

	$is_main_case = get_case_type_detail($db,$case_type);

	$is_condonation_delay = condonation_delay($db,$filing_no);
	$condonation_delay_flag = $is_condonation_delay['condonation_delay'];
	$loginid = $is_condonation_delay['loginid'];

	if($condonation_delay_flag  && $disposal_nature == '3'){
		$attachment = '';
		$template_id = 18;
		$subject = "Condonation of delay (dismissed)";
		$message = "Dear ".$loginid.", your application number ".$filing_no." for condonation of delay has been dismissed. This is a computer-generated message, please do not reply. GSTAT-GSTN";
		$email_text = "Dear ".$loginid.", your application number ".$filing_no." for condonation of delay has been dismissed. This is a computer-generated message, please do not reply. GSTAT-GSTN";
	}else{

		if($disposal_nature == '38' && $is_main_case == 'M'){

			$crn_detail = get_crn_detail($db,$filing_no);
			$crn_number = $crn_detail['crn_number'];
			$filed_date = $crn_detail['filed_date'];
			$order_number = $crn_detail['order_number'];
			$e_reference_no = $crn_detail['e_reference_no'];

			$pdf_html = '<div style="text-align:center;"><p>Form GST APL-02 Part B</p><p><b>Final Acknowledgement for registration of Appeal/Application</b></p><p>Your Appeal/application filed vide provisional acknowledgment reference number '.$filing_no.' dated '.date('d/m/Y',strtotime($filed_date)).' has been rejected</p></div><table style="width:100%"><tr><td><b>Date of rejection:  '.date('d/m/Y',strtotime($disposal_date_new)).'</b></td><td style="text-align:right"><b>Registrar<br/>GSTAT: '.$bench_name .' Bench</b></td></tr></table>';


			     	$time = time();				
					$pdf_file_name=$filing_no."-apl02A-".$time;
				  $filename=$pdf_file_name.".pdf";
				  $filename_sms = $pdf_file_name;
				 $dompdf->loadHtml($pdf_html);
				  $dompdf->setPaper('A4');
				  $dompdf->render();
				  $outputff = $dompdf->output();
				  $upload_dir = "Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/apl02A/$filing_no";
				  $pp_path = $upload_dir."/$filename";
				  $save_path = $upload_dir."/$filename";
				  if (!file_exists($save_path)) {
						mkdir($upload_dir, 0777, true);
					}
				 $save_file = file_put_contents($pp_path, $outputff);
				 $save_apl = $s3Service->uploadDynamicFile($outputff, $save_path);
				 $pdf_hash = hash_file('sha256', $pp_path);
				$apl_form= $db->prepare("update e_case_detail set apl_02b_form_path = ?, apl_02b_accept_reject = 2, pdf_hash = ?  where filing_no = ?");
				$apl_form->bindParam(1, $pp_path, PDO::PARAM_STR);
				$apl_form->bindParam(2, $pdf_hash, PDO::PARAM_STR);
				$apl_form->bindParam(3, $filing_no, PDO::PARAM_STR);
				$apl_form->execute();

				$today_date = date('Y-m-d');
				$doctype = 8;
				$subdoctype = 178;
				$docum_type = "APL02_REJECTED";
				$party_name = "apl02";
				$doc_level = 9;
				$save_doc = save_document_uplaod($db,$doctype,$pp_path,$sessionUserType,$subdoctype,$e_reference_no,$filename,$docum_type,'',$filename,$filing_no,true,1,$party_name,$list_date,'A');
				unlink($pp_path);
				array_push($put_api_cases, $filing_no);
			}

		// for apl02A end

		// for sms and email start

		$disposal_nature_text = get_disposal_nature($schemas,$db,$disposal_nature);
		$gstat_bench = "court $court_no , $bench_name, $state_name";
		$subject = "Your Case is disposed";

		if($is_main_case == 'M'){
			$apl_02_A = '';
			$attachment = '';
			$template_id = 15;
			$message = "Your case number ".$viewable_case_no." listed before the ".$gstat_bench." on ".$disposal_date." has been disposed-off by way of being ".$disposal_nature_text.". This is a computer-generated message, please do not reply. GSTAT-GSTN";
			if($disposal_nature == '38'){
				$apl_02_A = " Please find attached APL-02 Part B";
				$attachment = $save_path;
			}
			$email_text = "Your case number ".$viewable_case_no." listed before the ".$gstat_bench." on ".$disposal_date." has been disposed-off by way of being ".$disposal_nature_text.". ".$apl_02_A." This is a computer-generated message, please do not reply. GSTAT-GSTN";

		}else{
			$attachment = '';
			$template_id = 16;
			$message = "Your application number ".$viewable_case_no." listed before the ".$gstat_bench."on ".$disposal_date." has been disposed-off by way of being ".$disposal_nature_text.". This is a computer-generated message, please do not reply. GSTAT-GSTN";
			$email_text = "Your application number ".$viewable_case_no." listed before the ".$gstat_bench."on ".$disposal_date." has been disposed-off by way of being ".$disposal_nature_text.". This is a computer-generated message, please do not reply. GSTAT-GSTN";
		}
	}

	$send_mail = fn_sms($db, $template_id, $attachment, $filing_no, $subject, $message, $email_text);
	// for sms and email end

	$type='success';
	$msghash1=$type.'-'.$msg.'-'.$case_type.'-'.$case_year;
	$msghash=base64_encode($msghash1);

}
}

foreach($all_cases_to_dispose as $key=>$filing_no){
	
	$remarks= (isset($_REQUEST['remarks'.$main_case_filing]))?$_REQUEST['remarks'.$main_case_filing]:'';
	$remarks = pg_escape_string($remarks);
	$criteria= $_REQUEST['criteria'.$main_case_filing];
	$next_list_date= (isset($_REQUEST['next_list_date'.$main_case_filing]))?$_REQUEST['next_list_date'.$main_case_filing]:'';  //
	$action_type= (isset($_REQUEST['action_type'.$main_case_filing]))?$_REQUEST['action_type'.$main_case_filing]:'';
	$purpose_old= (isset($_REQUEST['purpose_old'.$main_case_filing]))?$_REQUEST['purpose_old'.$main_case_filing]:0;
	$purpose_code_next= (isset($_REQUEST['purpose_code'.$main_case_filing]))?$_REQUEST['purpose_code'.$main_case_filing]:0;
	$disposal_nature= (isset($_REQUEST['disposal_nature'.$main_case_filing]))?$_REQUEST['disposal_nature'.$main_case_filing]:0;
	$disposal_date= (isset($_REQUEST['disposal_date'.$main_case_filing]))?$_REQUEST['disposal_date'.$main_case_filing]:0;
	$choose_option= (isset($_REQUEST['choose_option_'.$main_case_filing]))?$_REQUEST['choose_option_'.$main_case_filing]:0;
	$not_fixed_element= (isset($_REQUEST['not_fixed_element_'.$main_case_filing]))?$_REQUEST['not_fixed_element_'.$main_case_filing]:0;
	$not_fixed_date= (isset($_REQUEST['not_fixed_date_'.$main_case_filing]))?$_REQUEST['not_fixed_date_'.$main_case_filing]:0;
	$next_listing_court= (isset($_REQUEST['next_list_court'.$filing_no]))?$_REQUEST['next_list_court'.$filing_no]:0;
	
$pen_dis= (isset($_REQUEST['pen_dis'.$main_case_filing]))?$_REQUEST['pen_dis'.$main_case_filing]:'';

$sth1 = $db->prepare("select a.case_no,a.case_year,a.case_type,b.short_name,a.regis_date,a.list_with_defect  from $schemas.case_detail as a left join case_type as b on b.id = a.case_type where a.filing_no=?");
$sth1->bindParam(1, $main_case_filing, PDO::PARAM_STR);
$sth1->execute();
$case_info =$sth1->fetchAll();
$case_info = array_shift($case_info);
$case_type = $case_info['case_type'];
$case_no = $case_info['case_no'];
$case_year = $case_info['case_year'];
$case_type_short_name = $case_info['short_name'];
$list_with_defect = $case_info['list_with_defect'];
$registration_date = $case_info['regis_date'];

if($pen_dis =='P' OR $pen_dis=='p' OR $pen_dis =='D' OR $pen_dis =='d')
{
	$viewable_case_no = $case_type_short_name.'/'.$case_no.'/'.$case_year;
	if(strtoupper($pen_dis) == 'P'){
		//$check_array = array('action type'=>$action_type,'purpose'=>$purpose_code_next,'next list date'=>$next_list_date);
		if($purpose_code_next == '19'  || $purpose_code_next == '27' || $purpose_code_next == '28'){
		$check_array = array('action type'=>$action_type,'purpose'=>$purpose_code_next);
		}else{
		if($choose_option == '1'){
				$check_array = array('action type'=>$action_type,'purpose'=>$purpose_code_next,'Listing Date'=>$next_list_date);
			}if($choose_option == '2'){
				$check_array = array('action type'=>$action_type,'purpose'=>$purpose_code_next,'Select Option'=>$not_fixed_element, 'Select Option Value'=>$not_fixed_date);
			}
		}
		$error_report = error_msg_fun($check_array,$viewable_case_no);
		if($error_report['error'] == 'yes'){
			$type="danger";
			$msghash1=$type.'-'.$error_report['validation_msg'].'-'.$case_type.'-'.$case_year;
			$msghash=base64_encode($msghash1);
			header("Location:case_proceeding_with_connected1.php?no=$no&msghash=$msghash");
			die;
		}
	}
	if(strtoupper($pen_dis) == 'D'){

		if($disposal_nature == '46'){
			$check_array = array('disposal nature'=>$disposal_nature);
		}else{

		$check_array = array('disposal nature'=>$disposal_nature,'disposal date'=>$disposal_date);
		}

		$error_report = error_msg_fun($check_array,$viewable_case_no);
		if($error_report['error'] == 'yes'){
			$type="danger";
			$msghash1=$type.'-'.$error_report['validation_msg'].'-'.$case_type.'-'.$case_year;
			$msghash=base64_encode($msghash1);
			header("Location:case_proceeding_with_connected1.php?no=$no&msghash=$msghash");
			die;
		}
	}

//===========pending related data===========



if($choose_option == '1'){
	$not_fixed_element = 0;
	$not_fixed_date = '';
	if($next_list_date !='')
	{
		list($day,$month,$year)=explode('/',$next_list_date);
		$next_list_date=$year.'-'.$month.'-'.$day;
	}
	else
	{
		$next_list_date="1111-11-11";
	}
}

if($choose_option == '2'){
	if($not_fixed_element != '' || $not_fixed_element != '0'){
		if($not_fixed_element == '1')
			$days = $not_fixed_date+1;
		else if($not_fixed_element == '2')
			$days = $not_fixed_date*7+1;
		else
			$days = $not_fixed_date*30+1;
		$date=date_create($list_date_link);
		date_add($date,date_interval_create_from_date_string("$days days"));
		$next_list_date = date_format($date,"Y-m-d");
		$holidays = get_holidays($db,$schemas);
		$next_list_date = recursive_fn_nextlist_date($next_list_date,$holidays);
	}else{
		$next_list_date="1111-11-11";
	}
}

if($purpose_code_next == '19' || $purpose_code_next == '27' || $purpose_code_next == '28'){
		$next_list_date = '1111-11-11';
		$not_fixed_element = 0;
		$not_fixed_date = '';
		$choose_option = 0;
}
//=================close pending related data===============

//=========disposal related data============

if($disposal_nature=='') {$disposal_nature=0;}
if($pen_dis=='D')
{
	$action_type=$disposal_nature;
	$next_list_date = '1111-11-11';

}

$new_status = $pen_dis;


if($disposal_date !='')
{
	list($day,$month,$year)=explode('/',$disposal_date);
	$disposal_date_new=$year.'-'.$month.'-'.$day;

/* $st="select listing_date from $schemas.case_allocation where filing_no=? and listing_date = ? order by listing_date desc limit 1";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $listing_date_link, PDO::PARAM_STR);
$st->execute();
$listdate_compar =$st->fetchColumn(); */
$listdate_compar = $list_date_link;
list($year,$month,$day)=explode('-',$list_date_link);
$listdate_comparvad="$year$month$day";

list($day,$month,$year)=explode('/',$disposal_date);
$validatecc="$year$month$day";

list($day,$month,$year)=explode('/',$cur_date );
$validatecutdate="$year$month$day";

if($listdate_compar !='')
{
//if(($validatecc < $listdate_comparvad) OR ($validatecutdate > $validatecc))
if($validatecc < $listdate_comparvad || $validatecc > $listdate_comparvad)
{
    ?>
<a href="./case_proceeding_with_connected1.php?no=<?php echo $no; ?>"><font color="red" size="3"><b>BACK</b></font></a></br>
<?php
echo "For $viewable_case_no <br/>";
echo "Listing Date Is: ".htmlspecialchars($listdate_compar);
echo '</br>';
/* echo "Date Is Not Greater Then Current Date";
echo '</br>'; */
echo "Date Is Not Smaller or Greater Then Listing Date";
die();
}

if('11111111' == $listdate_comparvad)
{
    ?>
<a href="./case_proceeding_with_connected1.php?no=<?php echo $no; ?>"><font color="red" size="3"><b>BACK</b></font></a></br>
<?php
echo "For $viewable_case_no <br/>";
echo "Listing Date Is: ".htmlspecialchars($listdate_compar);
echo '</br>';
    die("Listing Date Not Fixed");
}

}

}
else
{
	$disposal_date_new="1111-11-11";
}

//========close disposal related data========


$sth1 = $db->prepare("select listing_date,purpose,bench_nature,court_no,bench_no,list_flag from $schemas.case_allocation where filing_no=? and listing_date = ? order by listing_date desc limit 1");
$sth1->bindParam(1, $main_case_filing, PDO::PARAM_STR);
$sth1->bindParam(2, $list_date_link, PDO::PARAM_STR);
$sth1->execute();
$ca = $sth1->fetch();
//
//print_r($ca);
//die('sad');
$bench_no=$ca['bench_no'];
$bench_nature=$ca['bench_nature'];
$court_no = $ca['court_no'];
$purpose_old = $ca['purpose'];
$list_date=$ca['listing_date'];
$list_flag = $ca['list_flag'];


if($bench_no=='') $bench_no=0;
if($bench_nature=='') $bench_nature=0;
if($court_no=='') $court_no=0;
if($purpose_old=='') $purpose_old=0;
if($list_date=='') $list_date=$curdate;

 $ipaddress=htmlspecialchars($_SERVER["REMOTE_ADDR"]);
$accesstime=htmlspecialchars(date("Y-m-d H:i:s"));
$accesspage="case_proceeding";


$stkr="select action_type from $schemas.master_action where action_code =?";
$stk=$db->prepare($stkr);
$stk->bindParam(1, $action_type, PDO::PARAM_STR);
$stk->execute();
$action_name =$stk->fetchColumn();

/*start of code to remove duplicacy of record in case_proceeding*/
$check_case_allocation_query = "select filing_no from $schemas.case_proceeding where filing_no=? and listing_date=? limit 1";
//echo $check_case_allocation_query;echo $filing_no;
$check_case_allocation_query=$db->prepare($check_case_allocation_query);
$check_case_allocation_query->bindParam(1, $filing_no, PDO::PARAM_STR);
$check_case_allocation_query->bindParam(2, $list_date_link, PDO::PARAM_STR);
$check_case_allocation_query->execute();
$get_filing_no =$check_case_allocation_query->fetchColumn();
//echo $get_filing_no;

if($get_filing_no==''){
	//echo "ins in case_pro";
$sty="insert into $schemas.case_proceeding
(filing_no,listing_date,purpose,court_no,bench_nature,bench_no,
next_list_purpose,next_list_criteria,
next_list_date,todays_action,todays_status,entry_date,remarks,user_id,next_list_date_selection_option,next_list_date_selection_type,next_list_date_selection_type_value,next_listing_court)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

if($list_date=='') {$list_date=$curdate;}
$st="insert into $schemas.case_proceeding_his
(filing_no,listing_date,purpose,court_no,bench_nature,bench_no,next_list_purpose,next_list_criteria,next_list_date,todays_action,todays_status,entry_date,
remarks,user_id,next_list_date_selection_option,next_list_date_selection_type,next_list_date_selection_type_value,next_listing_court)
VALUES
(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $list_date_link, PDO::PARAM_STR);
$st->bindParam(3, $purpose_old, PDO::PARAM_STR);
$st->bindParam(4, $court_no, PDO::PARAM_INT);
$st->bindParam(5, $bench_nature, PDO::PARAM_INT);
$st->bindParam(6, $bench_no, PDO::PARAM_INT);
$st->bindParam(7, $purpose_code_next, PDO::PARAM_STR);
$st->bindParam(8, $criteria, PDO::PARAM_STR);
$st->bindParam(9, $next_list_date, PDO::PARAM_STR);
$st->bindParam(10, $action_type, PDO::PARAM_INT);
$st->bindParam(11, $new_status, PDO::PARAM_STR);
$st->bindParam(12, $current_timestamp, PDO::PARAM_STR);
$st->bindParam(13, $remarks, PDO::PARAM_STR);
$st->bindParam(14, $sessionUserType, PDO::PARAM_INT);
$st->bindParam(15, $choose_option, PDO::PARAM_INT);
$st->bindParam(16, $not_fixed_element, PDO::PARAM_INT);
$st->bindParam(17, $not_fixed_date, PDO::PARAM_INT);
$st->bindParam(18, $next_listing_court, PDO::PARAM_INT);
$st->execute();

}else{
	//echo "ins in his";
	$sty="insert into $schemas.case_proceeding_his
	(filing_no,listing_date,purpose,court_no,bench_nature,bench_no,
	next_list_purpose,next_list_criteria,
	next_list_date,todays_action,todays_status,entry_date,remarks,user_id,next_list_date_selection_option,next_list_date_selection_type,next_list_date_selection_type_value,next_listing_court)
	VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
	
	echo "<pre>"; print_r($paaaa);

	$update_case_allocation_sql = "update $schemas.case_proceeding set
 filing_no=?,listing_date=?,purpose=?, court_no=?, bench_nature=?, bench_no=?, next_list_purpose=?, next_list_criteria=?, next_list_date=?, todays_action=?, todays_status=?, remarks=?, updated_by=?, updated_by_username = ?, updated_date =?, next_list_date_selection_option= ? ,next_list_date_selection_type = ?, next_list_date_selection_type_value = ?, next_listing_court = ? where filing_no=? and listing_date=?";
 $update_case_allocation_result=$db->prepare($update_case_allocation_sql);
 $update_case_allocation_result->bindParam(1, $filing_no, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(2, $list_date_link, PDO::PARAM_INT);
 $update_case_allocation_result->bindParam(3, $purpose_old, PDO::PARAM_STR);
 //$st->bindParam(4, $curdate, PDO::PARAM_STR);
 //$st->bindParam(5, $next_list_date, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(4, $court_no, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(5, $bench_nature, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(6, $bench_no, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(7, $purpose_code_next, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(8, $criteria, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(9, $next_list_date, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(10, $action_type, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(11, $new_status, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(12, $remarks, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(13, $sessionUserType, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(14, $username, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(15, $current_timestamp, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(16, $choose_option, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(17, $not_fixed_element, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(18, $not_fixed_date, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(19, $next_listing_court, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(20, $filing_no, PDO::PARAM_STR);
 $update_case_allocation_result->bindParam(21, $list_date_link, PDO::PARAM_STR);
 $update_case_allocation_result->execute();
}


$st=$db->prepare($sty);
$paaaa = array($filing_no,$list_date_link,$purpose_old,$court_no,$bench_nature,$bench_no,$purpose_code_next,$criteria,$next_list_date,$action_type,$new_status,$current_timestamp,$remarks,$sessionUserType,$choose_option,$not_fixed_element,$not_fixed_date,$next_listing_court);

try{
	$st->execute($paaaa);}
catch(Execption $e)
	{
		echo "Failed:".$e->getMessage();
	}
}

//CODE FOR CASE DISPOSAL :START
if($pen_dis=='D' OR $pen_dis=='d')
{
	
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
	$msg_txt = "disposed";
	
$st="insert into $schemas.case_disposal_his (select * from $schemas.case_disposal where filing_no = ?)";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();

$st="delete from $schemas.case_disposal where filing_no = ?";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();

$st="insert into $schemas.case_disposal
	(filing_no,disposal_date,disposal_nature,court_no,bench_nature,bench_no,case_type,case_no,case_year,judge_code,remarks,entry_date,user_id)
	VALUES
	(?,?,?,?,?,?,?,?,?,?,?,?,?)";
$st=$db->prepare($st);
$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->bindParam(2, $disposal_date_new, PDO::PARAM_STR);
$st->bindParam(3, $disposal_nature, PDO::PARAM_STR);
$st->bindParam(4, $court_no, PDO::PARAM_INT);
$st->bindParam(5, $bench_nature, PDO::PARAM_INT);
$st->bindParam(6, $bench_no, PDO::PARAM_INT);
$st->bindParam(7, $case_type, PDO::PARAM_STR);
$st->bindParam(8, $case_no, PDO::PARAM_STR);
$st->bindParam(9, $case_year, PDO::PARAM_STR);
$st->bindParam(10, $judge_code_insert, PDO::PARAM_INT);
$st->bindParam(11, $remarks, PDO::PARAM_STR);
$st->bindParam(12, $curdate, PDO::PARAM_STR);
$st->bindParam(13, $sessionUserType, PDO::PARAM_INT);
$st->execute();

if($disposal_nature != '46'){
	$statusx='D';
	$std = "update $schemas.case_detail set status =? where filing_no=?";
	$std=$db->prepare($std);
	$std->bindParam(1, $new_status, PDO::PARAM_STR);
	$std->bindParam(2, $filing_no, PDO::PARAM_STR);
	$std->execute();
}
	
	$st="insert into $schemas.case_allocation_his_temp (select * from $schemas.case_allocation_temp where filing_no=?)";
	$st=$db->prepare($st);
	$st->bindParam(1, $filing_no, PDO::PARAM_STR);
	$st->execute();
	
	$next_l_date = '1111-11-11';
	$st="update $schemas.case_allocation_temp set next_list_date=? where filing_no=?";

	$st=$db->prepare($st);
	$st->bindParam(1, $next_l_date, PDO::PARAM_STR);
	$st->bindParam(2, $filing_no, PDO::PARAM_STR);
	$st->execute();

	//set status =2 in case_allocation table
	$statusz='2';
	$criteria_from_datez='1111-11-11';

	$msg .="<br/>$viewable_case_no Case is $msg_txt";


	// for apl02A start

	$is_main_case = get_case_type_detail($db,$case_type);

	$is_condonation_delay = condonation_delay($db,$filing_no);
	$condonation_delay_flag = $is_condonation_delay['condonation_delay'];
	$loginid = $is_condonation_delay['loginid'];

	if($condonation_delay_flag  && $disposal_nature == '3'){
		$attachment = '';
		$template_id = 18;
		$subject = "Condonation of delay (dismissed)";
		$message = "Dear ".$loginid.", your application number ".$filing_no." for condonation of delay has been dismissed. This is a computer-generated message, please do not reply. GSTAT-GSTN";
		$email_text = "Dear ".$loginid.", your application number ".$filing_no." for condonation of delay has been dismissed. This is a computer-generated message, please do not reply. GSTAT-GSTN";
	}else{

		if($disposal_nature == '38' && $is_main_case == 'M'){

			$crn_detail = get_crn_detail($db,$filing_no);
			$crn_number = $crn_detail['crn_number'];
			$filed_date = $crn_detail['filed_date'];
			$order_number = $crn_detail['order_number'];
			$e_reference_no = $crn_detail['e_reference_no'];

			$pdf_html = '<div style="text-align:center;"><p>Form GST APL-02 Part B</p><p><b>Final Acknowledgement for registration of Appeal/Application</b></p><p>Your Appeal/application filed vide provisional acknowledgment reference number '.$filing_no.' dated '.date('d/m/Y',strtotime($filed_date)).' has been rejected</p></div><table style="width:100%"><tr><td><b>Date of rejection:  '.date('d/m/Y',strtotime($disposal_date_new)).'</b></td><td style="text-align:right"><b>Registrar<br/>GSTAT: '.$bench_name .' Bench</b></td></tr></table>';


			     	$time = time();				
					$pdf_file_name=$filing_no."-apl02A-".$time;
				  $filename=$pdf_file_name.".pdf";
				  $filename_sms = $pdf_file_name;
				 $dompdf->loadHtml($pdf_html);
				  $dompdf->setPaper('A4');
				  $dompdf->render();
				  $outputff = $dompdf->output();
				  $upload_dir = "Efile_Document/GSTAT_Documents/CIS_Documents/casedoc/apl02A/$filing_no";
				  $pp_path = $upload_dir."/$filename";
				  $save_path = $upload_dir."/$filename";
				 if (!file_exists($save_path)) {
						mkdir($upload_dir, 0777, true);
					}
				  $save_file = file_put_contents($pp_path, $outputff);
				 $save_apl = $s3Service->uploadDynamicFile($outputff, $save_path);
				$pdf_hash = hash_file('sha256', $pp_path);
				$apl_form= $db->prepare("update e_case_detail set apl_02b_form_path = ?, apl_02b_accept_reject = 2, pdf_hash = ?  where filing_no = ?");
				$apl_form->bindParam(1, $pp_path, PDO::PARAM_STR);
				$apl_form->bindParam(2, $pdf_hash, PDO::PARAM_STR);
				$apl_form->bindParam(3, $filing_no, PDO::PARAM_STR);
				$apl_form->execute();

				$today_date = date('Y-m-d');
				$doctype = 8;
				$subdoctype = 178;
				$docum_type = "APL02_REJECTED";
				$party_name = "apl02";
				$doc_level = 9;
				$save_doc = save_document_uplaod($db,$doctype,$pp_path,$sessionUserType,$subdoctype,$e_reference_no,$filename,$docum_type,'',$filename,$filing_no,true,1,$party_name,$list_date,'A');
				unlink($pp_path);
				array_push($put_api_cases, $filing_no);
			}

		// for apl02A end

		// for sms and email start

		$disposal_nature_text = get_disposal_nature($schemas,$db,$disposal_nature);
		$gstat_bench = "court $court_no , $bench_name, $state_name";
		$subject = "Your Case is disposed";

		if($is_main_case == 'M'){
			$apl_02_A = '';
			$attachment = '';
			$template_id = 15;
			$message = "Your case number ".$viewable_case_no." listed before the ".$gstat_bench." on ".$disposal_date." has been disposed-off by way of being ".$disposal_nature_text.". This is a computer-generated message, please do not reply. GSTAT-GSTN";
			if($disposal_nature == '38'){
				$apl_02_A = " Please find attached APL-02 Part B";
				$attachment = $save_path;
			}
			$email_text = "Your case number ".$viewable_case_no." listed before the ".$gstat_bench." on ".$disposal_date." has been disposed-off by way of being ".$disposal_nature_text.". ".$apl_02_A." This is a computer-generated message, please do not reply. GSTAT-GSTN";

		}else{
			$attachment = '';
			$template_id = 16;
			$message = "Your application number ".$viewable_case_no." listed before the ".$gstat_bench."on ".$disposal_date." has been disposed-off by way of being ".$disposal_nature_text.". This is a computer-generated message, please do not reply. GSTAT-GSTN";
			$email_text = "Your application number ".$viewable_case_no." listed before the ".$gstat_bench."on ".$disposal_date." has been disposed-off by way of being ".$disposal_nature_text.". This is a computer-generated message, please do not reply. GSTAT-GSTN";
		}
	}

	$send_mail = fn_sms($db, $template_id, $attachment, $filing_no, $subject, $message, $email_text);
	// for sms and email end

	$type='success';
	$msghash1=$type.'-'.$msg.'-'.$case_type.'-'.$case_year;
	$msghash=base64_encode($msghash1);

}
}
// code for dispose all case if main case is disposed except for those cases which has seperate proceeding information

}
//CASE DISPOSAL : END
$db->commit();
foreach ($put_api_cases as $key => $filing_no) {
	$url = "http://10.193.85.11/efiling/getdataapl02b.drt?filingNo=$filing_no&schema=$schemas";
		callApiAsync($url);
}
header("Location:case_proceeding_with_connected1.php?msghash=$msghash");
}catch(exception $e){
	$db->rollBack();
	$msg .="Some Error Occurred df";
    print_r($e);
    die;
	$type='error';
	$msghash1=$type.'-'.$msg.'-'.$case_type.'-'.$case_year;
	$msghash=base64_encode($msghash1);
	header("Location:case_proceeding_with_connected1.php?msghash=$msghash");
}
}
?>
