<?php
$editor_data = $_POST[ 'editor' ];
//print_r($_REQUEST);die;
//store item no in advocates too!!!
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
date_default_timezone_set("Asia/Kolkata");
$server_date= date('Y-m-d'); //Returns IST 
ob_flush();
ob_start();

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	// If they are not, we redirect them to the login page.
	// Remember that this die statement is absolutely critical.  Without it,
	// people can view your members-only content without logging in.
	die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
	echo "you Can't access this page";
}

else
{
$all_filingno= htmlspecialchars($_REQUEST['all_filingno']);
//$all_filingno_count = count($all_filingno);
$name= $_REQUEST['name'];
//print_r($name);die;
$namecount = count($name);
$rname= $_REQUEST['rname'];
$rnamecount = count($rname);
$item_no=$_REQUEST['item_no'];

	$date = htmlspecialchars(date("d/m/Y"));
	$date1 = htmlspecialchars(date("F j, Y g:i a"));
	$msg_ip = isset($_REQUEST['msg_ip']) ? $_REQUEST['msg_ip'] :'';
	$year=htmlspecialchars(date("Y"));
	
	
	 $sessionUserType=htmlspecialchars($_SESSION['id']);
	$item_no1 = $_REQUEST['item_no1'];
	
	$schemas=htmlspecialchars($_SESSION['schema_name']);
	//$applicant_advocate=serialize($_REQUEST[applicant]);
	 $applicant= $_REQUEST['applicant'];
	$attend= $_REQUEST['attend'];
	$respondent_advocate=serialize($_REQUEST[respondent]);
	$applicant1=$_REQUEST['applicant1'];
	$applicant2=$_REQUEST['applicant2'];
	$applicant3=$_REQUEST['applicant3'];
	$applicant4=$_REQUEST['applicant4'];
	$applicant5=$_REQUEST['applicant5'];
	$applicant6=$_REQUEST['applicant6'];
	$applicant7=$_REQUEST['applicant7'];
	$applicant8=$_REQUEST['applicant8'];
	$applicant9=$_REQUEST['applicant9'];
	$applicant10=$_REQUEST['applicant10'];
	$respondent1=$_REQUEST['respondent1'];
	$respondent2=$_REQUEST['respondent2'];
	$respondent3=$_REQUEST['respondent3'];
	$respondent4=$_REQUEST['respondent4'];
	$respondent5=$_REQUEST['respondent5'];
	$respondent6=$_REQUEST['respondent6'];
	$respondent7=$_REQUEST['respondent7'];
	$respondent8=$_REQUEST['respondent8'];
	$respondent9=$_REQUEST['respondent9'];
	$respondent10=$_REQUEST['respondent10'];
	$author_name=$_REQUEST['author_name'];
	//$order_of_tribunal=$_REQUEST['order_of_tribunal'];
	$order_of_tribunal = $editor_data;
	$courtno=$_REQUEST['courtno'];
	$benchnature=$_REQUEST['benchnature'];
	 $order_bench_code=$_REQUEST['order_bench_code'];

	$next_list_date=$_REQUEST['next_list_date'];
	list($day,$month,$year)=explode('/',$next_list_date);
	$next_list_date9=$year.'-'.$month.'-'.$day;
	
	$filing_no=htmlspecialchars($_REQUEST['filing_no']);
    $entry_date =date('Y-m-d');
 
/*if($item_no >0)
{
	
$st=$db->prepare("update $schemas.order_daily set order_tribunal=?,applicant1=?,applicant2=?,applicant3=?,applicant4=?,
			applicant5=?,applicant6=?,applicant7=?,applicant8=?,applicant9=?,applicant10=?,respondent1=?,
			respondent2=?,respondent3=?,respondent4=?,respondent5=?,respondent6=?,respondent7=?,respondent8=?,
			respondent9=?,respondent10=? where filing_no =? and item_no =?");

$st->execute(array($order_of_tribunal,$applicant1,$applicant2,$applicant3,$applicant4,
			$applicant5,$applicant6,$applicant7,$applicant8,$applicant9,$applicant10,$respondent1,
			$respondent2,$respondent3,$respondent4,$respondent5,$respondent6,$respondent7,$respondent8,
			$respondent9,$respondent10,$filing_no,$item_no));

echo " Draft Order Successfully modified";
die();   
}
else
{*/

for($k=0;$k<$namecount;$k++)
{

	$party_type1='P';
$st3=$db-> prepare("insert into $schemas.order_daily_advocate(filing_no,order_date,advocate,advocate_type,adv_serial,entry_date,user_id)   
values(?,?,?,?,?,?,?)");
$st3->bindParam(1, $all_filingno, PDO::PARAM_STR);
$st3->bindParam(2, $next_list_date9, PDO::PARAM_STR);
$st3->bindParam(3, $name[$k], PDO::PARAM_STR);
$st3->bindParam(4, $party_type1, PDO::PARAM_STR);
$st3->bindParam(5, $k, PDO::PARAM_STR);
$st3->bindParam(6, $server_date, PDO::PARAM_STR);
$st3->bindParam(7, $sessionUserType, PDO::PARAM_STR);

$st3->execute();

 
}	

for($m=0;$m<$rnamecount;$m++)
{

	$party_type1='R';
$st4=$db-> prepare("insert into $schemas.order_daily_advocate(filing_no,order_date,advocate,advocate_type,adv_serial,entry_date,user_id)   
values(?,?,?,?,?,?,?)");
$st4->bindParam(1, $all_filingno, PDO::PARAM_STR);
$st4->bindParam(2, $next_list_date9, PDO::PARAM_STR);
$st4->bindParam(3, $rname[$m], PDO::PARAM_STR);
$st4->bindParam(4, $party_type1, PDO::PARAM_STR);
$st4->bindParam(5, $m, PDO::PARAM_STR);
$st4->bindParam(6, $server_date, PDO::PARAM_STR);
$st4->bindParam(7, $sessionUserType, PDO::PARAM_STR);

$st4->execute();
}	
/*
echo $sql="insert into $schemas.order_daily(filing_no,order_date,order_tribunal,
user_id,flag,bench_nature,court_no,applicant1,applicant2,applicant3,applicant4,
applicant5,applicant6,applicant7,applicant8,applicant9,applicant10,respondent1,
respondent2,respondent3,respondent4,respondent5,respondent6,respondent7,respondent8,
respondent9,respondent10,item_no,entry_date,pet_advocate,res_advocate,bench_no)
values('$filing_no','$next_list_date9','$order_of_tribunal','$sessionUserType','$display','$benchnature','$courtno','$applicant1','$applicant2','$applicant3','$applicant4','$applicant5','$applicant6','$applicant7','$applicant8','$applicant9','$applicant10','$respondent1','$respondent2','$respondent3','$respondent4','$respondent5','$respondent6','$respondent7','$respondent8','$respondent9','$respondent10','$item_no1','$entry_date','$applicant_advocate','$respondent_advocate','$order_bench_code')";
die();
*/

//for($p=0;$p<$all_filingno_count;$p++)
//{
$st = $db->prepare("insert into $schemas.order_daily(filing_no,order_date,order_tribunal,
user_id,flag,bench_nature,court_no,applicant1,applicant2,applicant3,applicant4,
applicant5,applicant6,applicant7,applicant8,applicant9,applicant10,respondent1,
respondent2,respondent3,respondent4,respondent5,respondent6,respondent7,respondent8,
respondent9,respondent10,item_no,entry_date,pet_advocate,res_advocate,bench_no)
values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
$display='N';

$st->bindParam(1, $all_filingno, PDO::PARAM_STR);
$st->bindParam(2, $next_list_date9, PDO::PARAM_STR);
$st->bindParam(3, $order_of_tribunal, PDO::PARAM_STR);
$st->bindParam(4, $sessionUserType, PDO::PARAM_STR);
$st->bindParam(5, $display, PDO::PARAM_STR);
$st->bindParam(6, $benchnature, PDO::PARAM_STR);
$st->bindParam(7, $courtno, PDO::PARAM_STR);
$st->bindParam(8, $applicant1, PDO::PARAM_STR);
$st->bindParam(9, $applicant2, PDO::PARAM_STR);
$st->bindParam(10, $applicant3, PDO::PARAM_STR);
$st->bindParam(11, $applicant4, PDO::PARAM_STR);
$st->bindParam(12, $applicant5, PDO::PARAM_STR);
$st->bindParam(13, $applicant6, PDO::PARAM_STR);
$st->bindParam(14, $applicant7, PDO::PARAM_STR);
$st->bindParam(15, $applicant8, PDO::PARAM_STR);
$st->bindParam(16, $applicant9, PDO::PARAM_STR);
$st->bindParam(17, $applicant10, PDO::PARAM_STR);
$st->bindParam(18, $respondent1, PDO::PARAM_STR);
$st->bindParam(19, $respondent2, PDO::PARAM_STR);
$st->bindParam(20, $respondent3, PDO::PARAM_STR);
$st->bindParam(21, $respondent4, PDO::PARAM_STR);
$st->bindParam(22, $respondent5, PDO::PARAM_STR);
$st->bindParam(23, $respondent6, PDO::PARAM_STR);
$st->bindParam(24, $respondent7, PDO::PARAM_STR);
$st->bindParam(25, $respondent8, PDO::PARAM_STR);
$st->bindParam(26, $respondent9, PDO::PARAM_STR);
$st->bindParam(27, $respondent10, PDO::PARAM_STR);
$st->bindParam(28, $item_no1, PDO::PARAM_STR);
$st->bindParam(29, $entry_date, PDO::PARAM_STR);
$st->bindParam(30, $applicant_advocate, PDO::PARAM_STR);
$st->bindParam(31, $respondent_advocate, PDO::PARAM_STR);
$st->bindParam(32, $order_bench_code, PDO::PARAM_STR);
$st->execute();
//}

echo "<h2 style='color:green;'>Draft Order Successfully Submited</h2>";
sleep(10);
echo "<script>
    window.onunload = refreshParent;
    function refreshParent() {
        window.opener.location.reload();
    }
</script>";
echo "<script>window.close();</script>";
//}
} 
?>
