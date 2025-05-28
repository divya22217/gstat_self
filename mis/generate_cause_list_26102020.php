<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
 <script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<?php 
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
include '../db_inc2.php';
include("../master/functions.php");
session_start();

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);   */

$m=0;
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
$main_cases = main_case_type();	
$main_location_code = $_SESSION['location'];
$list_flag=$_REQUEST["listtype"];

$counttime=0;
$pagec=1;
$listbefore=$_REQUEST["list_before"];
//$bench_code1=$_REQUEST["bench_code1"];
$list_date =$_REQUEST['next_list_date'];
$court_no_remark = $court_no=$court_nono=$_REQUEST['court_no'];
//$type = $_REQUEST['cause_list_type'];
$list_flag_remark = $list_flag=$_REQUEST['cause_list_type'];
//echo $list_flag; die;

if($list_flag ==1)
{
 $causelisthead="DAILY CAUSE LIST";
}
if($list_flag==2)
{
$causelisthead ="SUPPLEMENTRY CAUSE LIST";
}
if($list_flag==3)
{
$causelisthead ="VACATION CAUSE LIST";
}
list($day,$month,$year)=explode('/',$list_date);
$next_list_date11=$year.'-'.$month.'-'.$day;
$print_header = "Y";
$printtop="Y";
$serialno=1;
$list_date = $list_date;
$print_flag_right_data='N';

list($day, $month, $year) = explode('/', $list_date);
$listdate_entire = $year . '-' . $month . '-' . $day;
$print_count=1;
$court_no=$_REQUEST['court_no'];

$schemas=htmlspecialchars($_SESSION['schema_name']); ?>

<div class="container-fluid">
<table cellspacing="1" cellpadding="1" border="0" align="center">
<div id="testdiv" style="visibility: visible;"><a href="javascript:window.print();"><font size="4" color="red">
Print</font></a></span>
<a href="./causelist.php">
	<font face="Verdana" color="red" size="3"><center><b>BACK</b></center></font>
</a>
</div>
</table>

<?php 
$all_listing_dates =$db->prepare( "select distinct(listing_date),court_no,bench_nature,list_flag from $schemas.case_allocation_temp
where listing_date = ? and court_no = ? and list_flag = ? order by listing_date");
$all_listing_dates->bindParam(1, $listdate_entire, PDO::PARAM_STR);
$all_listing_dates->bindParam(2, $court_no, PDO::PARAM_STR);
$all_listing_dates->bindParam(3, $list_flag, PDO::PARAM_STR);
$all_listing_dates->execute();
$all_listing_dates = $all_listing_dates->fetchAll();
if(!empty($all_listing_dates)){
	foreach($all_listing_dates as $k=>$listing_info){
		$listbefore = $listing_info['bench_nature'];
		$next_list_date11=$listdate_entire = $listing_info['listing_date'];
		$list_flag = $listing_info['list_flag'];

$sr_no =1;


if($listbefore > 0 and $court_no > 0 and ($listdate_entire !='--' OR $listdate_entire !=''))
{
	

$benchloop1 =$db->prepare( "select * from $schemas.bench b ,$schemas.bench_nature bn
where b.from_list_date= ? and  b.bench_nature=? and bn.bench_code =? and
b.bench_nature=bn.bench_code and b.court_no =? order by b.court_no, b.priority asc");
$benchloop1->bindParam(1, $listdate_entire, PDO::PARAM_STR);
$benchloop1->bindParam(2, $listbefore, PDO::PARAM_STR);
$benchloop1->bindParam(3, $listbefore, PDO::PARAM_STR);
$benchloop1->bindParam(4, $court_no, PDO::PARAM_STR);
}


if($listbefore > 0 and $court_no =='' and ($listdate_entire !='--' OR $listdate_entire !=''))
{
$benchloop1 =$db->prepare( "select * from $schemas.bench b ,$schemas.bench_nature bn where
b.from_list_date=?	and b.bench_nature=? and bn.bench_code =? order by b.court_no, b.priority asc");

$benchloop1->bindParam(1, $listdate_entire, PDO::PARAM_STR);
$benchloop1->bindParam(2, $listbefore, PDO::PARAM_STR);
$benchloop1->bindParam(3, $listbefore, PDO::PARAM_STR);

}


if($listbefore == 0 and $court_no > 0  and ($listdate_entire !='--' OR $listdate_entire !=''))
{
$benchloop1 =$db->prepare( "select * from $schemas.bench b ,$schemas.bench_nature bn
where b.from_list_date=? and b.bench_nature=bn.bench_code and b.court_no =?
order by b.court_no, b.priority asc");

$benchloop1->bindParam(1, $listdate_entire, PDO::PARAM_STR);
$benchloop1->bindParam(2, $court_no, PDO::PARAM_STR);
}




if($listbefore ==0 and $court_no =='' and ($listdate_entire !='--' OR $listdate_entire !=''))
{
$benchloop1 =$db->prepare(  "select * from $schemas.bench b ,$schemas.bench_nature bn where 
b.from_list_date=? and b.bench_nature=bn.bench_code order by b.court_no,
b.priority asc");

$benchloop1->bindParam(1, $listdate_entire, PDO::PARAM_STR);
}
$benchloop1->execute();

while ($row_loop1 = $benchloop1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$bench_code12=$row_loop1['id'];
$list_before = $row_loop1['bench_nature'];
//$bench_no1 = $row_loop1['bench_no'];
//$court_no= $row_loop1['court_no'];
$from_time = $row_loop1['from_time'];
$detail    =$row_loop1['detail'];
$presiding_judge = $row_loop1['presiding'];
$bench_code1 = $row_loop1['bench_no'];
$b_nature = $row_loop1['bench_nature'];
$curYear = date('Y');
$curMonth = date('m');
$curDay = date('d');
$cur_date = "$curDay/$curMonth/$curYear";
$curdate="$curYear-$curMonth-$curDay";
$todatexx="$curYear-$curMonth-$curDay";

$frmdate=$year.'-'.$month.'-'.$day;
$listdate=date('l \t\h\e jS F Y', mktime(0, 0, 0, $month, $day, $year));
}

$todate=$next_list_date11;

$sql_time="select from_time from $schemas.bench  where court_no=? and
bench_no=?and (? between from_list_date and to_list_date);";
$sth10=$db->prepare($sql_time);
$sth10->bindParam(1, $court_no, PDO::PARAM_STR);
$sth10->bindParam(2, $bench_no1, PDO::PARAM_STR);
$sth10->bindParam(3, $todate, PDO::PARAM_STR);
$sth10->execute();
$from_time=$sth10->fetchColumn();

?>

<style>
body {
background-color: #ffffff;
}
h1 {
color: maroon;
margin-left: 40px;
}
@media print{
	#testdiv{
		display: none;
	}
}
</style>
<style type="text/css">
div.hidden {
display: none;
}

</style>

<table width="100%" border='0' cellpadding="1" cellspacing="3" align="center">
<?php
$header =0;
$sql="select bench_no  from $schemas.bench where bench_nature='$listbefore' and court_no='$court_no' and from_list_date='$listdate_entire'";
foreach($db->query($sql) as $row)
	{	
		   $bench_code1 =$row['bench_no'];
		   //$fetch_remarks = get_remarks($schemas,$db,$listdate_entire,$listbefore,$court_no,$list_flag,$bench_code1);
		  //$bench_loc_code = $row['bench_code'];


 $sql_judge="select jm.judge_name,jm.judge_desg_code,jm.judge_code from $schemas.master_judge as jm,
$schemas.bench as b where  b.from_list_date=? and b.bench_no=? and
b.court_no=? and jm.judge_code=b.presiding";
$sth101=$db->prepare($sql_judge);
$sth101->bindParam(1, $todate, PDO::PARAM_STR);
$sth101->bindParam(2, $bench_code1, PDO::PARAM_STR);
$sth101->bindParam(3, $court_no, PDO::PARAM_STR);
$sth101->execute();
while ($j = $sth101->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $judge_name=$j['judge_name'];
  
 $judge_desg_code=$j['judge_desg_code'];
 $presiding_code=$j['judge_code'];

/*query for display of judge*/
// $jj = "select display from $schemas.bench_judge where display='true' and judge_code =$presiding_code  and from_list_date=$todate and bench_no=$bench_no1 ";
 $jj = "select display from $schemas.bench_judge where display='true' and judge_code ='$presiding_code'  and from_list_date='$todate' and bench_no='$bench_code1' ";
$jj = "select display from $schemas.bench_judge where display='true' and judge_code =?  and from_list_date=? and bench_no=? ";
$jj=$db->prepare($jj);
$jj->bindParam(1, $presiding_code, PDO::PARAM_STR);
$jj->bindParam(2, $todate, PDO::PARAM_STR);
$jj->bindParam(3, $bench_code1, PDO::PARAM_STR);
$jj->execute();
 $jjj = $jj->fetchColumn();


if($judge_desg_code > 0)
{
$sql_desg="select desg_name from $schemas.master_desg where desg_code=? and display=? ";
$sth14=$db->prepare($sql_desg);
$display='TRUE';
$sth14->bindParam(1, $judge_desg_code, PDO::PARAM_STR);
$sth14->bindParam(2, $display, PDO::PARAM_STR);
$sth14->execute();
$judge_desg=$sth14->fetchColumn();
}
}

 $case_exist=0;
 $case_count="select count(*) as count  from $schemas.case_allocation_temp where listing_date='$todate'
 and bench_no='$bench_code1' and court_no='$court_no' and list_flag='$list_flag'";

$sth15=$db->prepare($case_count);
$sth15->execute();
 $case_exist=$sth15->fetchColumn();


if($case_exist == '0' || $case_exist =='')
{
$sql_desg="select name from initilization";
$sth14=$db->prepare($sql_desg);
$sth14->execute();
$name_ins=$sth14->fetchColumn();


?>






<tr><td align="center" colspan="7"><font face="Verdana" color="red" size ="4"></font>
</td>
</tr>
<?php
}
if($case_exist > 0 || $case_exist!='')
{
	
?>
<?php

if($m==0)
{
$m++;
$sql_desg="select name from initilization";
$sth14=$db->prepare($sql_desg);
$sth14->execute();
$name_ins=$sth14->fetchColumn();


$main_city_name = return_zone_name_for_causelist($main_location_code);

?>
<tr><td align="center" colspan="7" style="font-size:20px;"><b><u>
<?php  echo htmlspecialchars(strtoupper('NATIONAL COMPANY LAW APPELLATE TRIBUNAL')); ?>
</u></b>
</td></tr>
<?php
$unixTimestamp = strtotime($listdate_entire);
list($yc,$mc,$dc)=explode('-',$listdate_entire);
$causelist_display_date=$dc.'.'.$mc.'.'.$yc; 
$dayOfWeek = date("l", $unixTimestamp);
$display_court_text = get_display_court_text($db,$schemas,$court_no);
?>
<tr>
<td align="center" colspan="7" style="font-size:16px;"><b><u>2nd FLOOR, M.T.N.L BUILDING, NEAR SCOPE COMPLEX, CGO COMPLEX, NEW DELHI
</u></b>
</td>
</tr>
<tr>
<td align="center" colspan="7" style="font-size:16px;"><b><u><?php echo strtoupper($causelisthead.' DATED '.$causelist_display_date.' ('.$dayOfWeek.')'); ?></u></b>
</font>
</td>
</tr>
<tr>
<td align="center" colspan="7" style="font-size:16px;"><b><u><?php echo strtoupper($display_court_text); ?></u></b>
</font>
</td>
</tr>
<?php
}

?>
<?php
if($case_exist>0)
{
?>
<tr><td align="center" colspan="7"><b><u>
<?php 
list($day,$month,$year)=explode('/',$nd);
$fd=$year.'-'.$month.'-'.$day;
$per_text = 'In the court of ';
$sr_no=1;
 $stat="select * from $schemas.bench where  bench_nature ='$listbefore' and from_list_date='$listdate_entire' and  bench_no='$bench_code1'";
$stat=$db->prepare("$stat");
$stat->execute();
while ($row = $stat->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$from_time= $row['from_time'];
$bench_remarks= $row['detail'];
$bench_no= $row['bench_no'];
$court_no =$row['court_no'];
 $presiding=$row['presiding'];
 $stat1="select *  from $schemas.master_judge where judge_code ='$presiding'";
$stat1 = $db->prepare($stat1);
$stat1->execute();
$presiding1 = $stat1->fetch();

$listingDate = date('j<\s\up>S</\s\up> F, Y', strtotime($listdate_entire));
echo "$from_time</u></b></td></tr><tr><td colspan='7' style='font-size:16px;'><b>";
echo $per_text.''.$hon_text=$presiding1['hon_text']." ";
echo  $presiding1['judge_name'];
$stat1="select desg_name from $schemas.master_desg where desg_code =?";
$stat1 = $db->prepare($stat1);
$stat1->bindParam(1,$presiding1['judge_desg_code'], PDO::PARAM_STR);
$stat1->execute();
echo " , ".$desg_name = $stat1->fetchColumn();
}

 $stat2="select judge_code from $schemas.bench_judge where bench_nature='$listbefore' and from_list_date='$listdate_entire' and judge_code !='$presiding' and bench_no='$bench_code1'";
$stat2=$db->prepare("$stat2");
$stat2->execute();
while ($row2 = $stat2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{

$judge_code =$row2['judge_code'];
$stat1="select judge_name,judge_desg_code,gen,hon_text from $schemas.master_judge where judge_code =?";
$stat1 = $db->prepare($stat1);
$stat1->bindParam(1,$judge_code, PDO::PARAM_STR);
$stat1->execute();
 $judge_data = $stat1->fetch();
$gen = $judge_data['gen'];
 echo ', '.$hon_text = $judge_data['hon_text'];

  
echo ' '.$judge_name = $judge_data['judge_name'];
$desg_code = $judge_data['judge_desg_code'];
$stat1="select desg_name from $schemas.master_desg where desg_code =?";
$stat1 = $db->prepare($stat1);
$stat1->bindParam(1,$desg_code, PDO::PARAM_STR);
$stat1->execute();
echo" , ".$desg_name = $stat1->fetchColumn();


}

?>

</b>
</td>
</tr>
</table>
<br/>
<div class="table-responsive">
<table class="table table-bordered" style="background-color:#ffffff;">
<?php
}
?>

<?php
}
if($b_nature > 0)
{
 $sql_purpose="select distinct(purpose), priority from $schemas.bench_purpose_priority where
from_date='$todate' and  bench_no='$bench_code1'  and 
bench_nature='$b_nature' order by priority ASC";

$sth_j12=$db->prepare($sql_purpose);
$sth_j12->execute();
 $court_nono;
while ($row = $sth_j12->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
  $purpose_code=$row['purpose'];
$serial_no =1;
if($purpose_code > 0)
{
   $sql_count="select count(*) as count from $schemas.case_allocation_temp where purpose='$purpose_code' and 
listing_date='$todate' and bench_no='$bench_code1' and 
court_no='$court_nono' and list_flag = '$list_flag'"; 



$sth1r=$db->prepare($sql_count);
$sth1r->execute();
   $count_filing=$sth1r->fetchColumn(); 
}
if($count_filing > 0)
{
$sql_purpose_name="select purpose_name from $schemas.master_purpose where 
purpose_code='$purpose_code' ";
$sth4x=$db->prepare($sql_purpose_name);
$sth4x->execute();
$purpose_name=$sth4x->fetchColumn();
}
if($purpose_name !='')
{
?> <tr><td align="left" colspan="7"><font face="Verdana" size ="3"><b>
<?php  echo htmlspecialchars(ucwords(strtoupper($purpose_name)));
$purpose_name ='';
?>
</font>
</td></tr>
<tr>

<td width="10%" align="center" >

<b>
<?php echo strtoupper("s.no."); ?>
</b>
</td>
<td width="20%" align="center" >
<b>
<?php echo strtoupper("case no."); ?>
</b>
</td>
<td width="30%" align="center">
<b>
<?php echo strtoupper("name of parties"); ?>
</b>
</td>
<td width="40%" align="center">
<b>
<?php echo strtoupper("counsel for appellants / petitioner"); ?>
</b>
</td>
<td width="40%" align="center">
<b>
<?php echo strtoupper("counsel for Respondants"); ?>
</b>
</td>
<!--<td width="40%" align="center">
<b>
<?php echo strtoupper("actions"); ?>
</b>
</td>-->
</tr>
<?php
}
$count=0;
$countzz=1;


 $sql_allocation="select a.filing_no ,a.remarks  from $schemas.case_allocation_temp a,
$schemas.case_detail d where a.purpose=? and a.listing_date=?
and a.bench_no=? and a.court_no=? and list_flag = ? and
a.filing_no=d.filing_no order by  a.priority_serial,d.case_no,d.case_year asc";

$status='P';
$sth_j12c=$db->prepare($sql_allocation);
//$sth_j12c->bindParam(1, $status, PDO::PARAM_STR);
$sth_j12c->bindParam(1, $purpose_code, PDO::PARAM_STR);
$sth_j12c->bindParam(2, $todate, PDO::PARAM_STR);
//$sth_j12c->bindParam(4, $list_flag, PDO::PARAM_STR);
$sth_j12c->bindParam(3, $bench_code1, PDO::PARAM_STR);
$sth_j12c->bindParam(4, $court_nono, PDO::PARAM_STR);
$sth_j12c->bindParam(5, $list_flag, PDO::PARAM_STR);
$sth_j12c->execute();
while ($row1 = $sth_j12c->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
  $filing_no=$row1['filing_no'];

$hc_dc_caseno=$row1['hc_dc_caseno'];
$remarkss=$row1['remarks'];

 $sql_cd="select a.pet_type,a.location_code,a.res_type,a.legal_aid ,a.main_case_ia_no,
a.oa_ref_no,a.case_no, a.case_type,a.case_year, a.pet_name,a.res_name,a.pet_adv_name,
a.res_adv_name,a.backlog from $schemas.case_detail as a  where a.filing_no=?
order by case_type,case_no ASC";

$status='P';
$sth_j12cc=$db->prepare($sql_cd);
$sth_j12cc->bindParam(1, $filing_no, PDO::PARAM_STR);
//$sth_j12cc->bindParam(2, $status, PDO::PARAM_STR);
$sth_j12cc->execute();
while ($row2 = $sth_j12cc->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$count++;
$oa_ref_no=$row2['oa_ref_no'];
$backlog = $row2['backlog'];
if($oa_ref_no!='')
{
$ref_newst31="select case_type,case_no,case_year,location_code from $schemas.case_detail where filing_no='$oa_ref_no' order by case_type asc";
$ref_newst31=$db->prepare($ref_newst31);
$ref_newst31->execute();
$ref_resultset1 = $ref_newst31->fetch();
extract($ref_resultset1);

$ref_lcode ="select short_name from $schemas.bench_location where city_id ='$location_code'";
$ref_lcode=$db->prepare($ref_lcode);
$ref_lcode->execute();
$ref_lcodename = $ref_lcode->fetchColumn();
if($case_type > 0)
{
$ref_stQ = $db->prepare("select short_name from case_type where id = ?");
$ref_stQ->bindParam(1, $case_type, PDO::PARAM_STR);
$ref_stQ->execute();
$ref_case_type_short_name=$ref_stQ->fetchColumn();
}



$ref_case_numaa = $case_no;
$ref_case_year1aa = $case_year;
		$ref_case_num1aa=ltrim($case_numaa,0);
		
		$ref_CASE_NO = htmlspecialchars(strtoupper($ref_case_type_short_name).'/'.$ref_case_num1aa.'('.$ref_lcodename.')'.$ref_case_year1aa);


}


 $case_no=$row2['case_no'];
 $case_type=$row2['case_type'];
 $case_year=$row2['case_year'];
 if (in_array($case_type, $main_cases)){
	$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($filing_no);
}else{
	$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($row2['main_case_ia_no']);
	if(empty($show_party_filing_no))
		$show_party_filing_no = htmlspecialchars($filing_no);
}
$pet_name =get_party($db,$show_party_filing_no,'P',1);
$res_name =get_party($db,$show_party_filing_no,'R',1); 
/* $pet_name = $row2['pet_name'];
$res_name = $row2['res_name']; */
$location_code =$row2['location_code'];
//$location_code = $row2['bench_location'];


$lcode ="select short_name from $schemas.bench_location where city_id ='$location_code'";
$lcode=$db->prepare($lcode);
$lcode->execute();
$lcodename = $lcode->fetchColumn();

$noaddfilingno =$row2['filing_no'];
//$sql="select short_name from case_type where id = ?";
if($case_type > 0)
{
$stQ = $db->prepare("select short_name from case_type where id = ?");
$stQ->bindParam(1, $case_type, PDO::PARAM_STR);
$stQ->execute();
$case_type_short_name=$stQ->fetchColumn();
}



$case_numaa = $case_no;
$case_year1aa = $case_year;
		$case_num1aa=ltrim($case_numaa,0);
		
		 $CASE_NO = htmlspecialchars(strtoupper($case_type_short_name).' No. '.$case_num1aa.'/'.$case_year1aa);
//echo remark_text($fetch_remarks,$sr_no);

?>

<tr>
<td width="10%" align="center" valign="top"><font size='2'><?php echo htmlspecialchars($sr_no++).".";?></font></td>
<td width="20%" align="center" valign="top" > 
<font face="Verdana" size ="2">

<?php

$serial_no=$sr_no-1;
$dms_link = '#';
//$dms_link = generate_dms_link($db,$dbonline,$schemas,$filing_no,$main_location_code);
$is_defective = check_is_partially_defective($db,$schemas,$filing_no,$listdate_entire);
if($is_defective)
	$defective_text = ' With Defects';
else
	$defective_text = '';

?>
			 
<a  href="javascript:popsurety_pending_report('<?php echo $dms_link; ?>');" style="cursor: pointer">

<font color="#900C3F" size="2"><?php echo $CASE_NO.$defective_text; ?></a>


<?php
// if main case has ia 
	//echo ia_cases_of_main_cases($schemas,$db,$dbonline,$case_type,$filing_no,$type_of_filing = 2);
	$selected_child = selected_child_cases($schemas,$db,$filing_no,1,$listdate_entire,$bench_code1,$list_flag,$court_no);
	 if(!empty($selected_child)){
		echo show_selected_child_cases($schemas,$db,$filing_no,1,$listdate_entire,$bench_code1,$list_flag,$court_no);
	}else{
	echo main_cases_child($schemas,$db,$filing_no,$status = 'P');
	}
	echo recursive_cases($schemas,$db,$case_type,$filing_no);
	//echo main_cases_child($schemas,$db,$filing_no,$status = 'P');
	$all_connected_cases = get_connected_cases($schemas,$db,$filing_no);
	if(!empty($all_connected_cases)){
			echo '<br/><br/> WITH';
		}
// end
?>
</font>
</td>
<td align="center" width="30%" valign="top">
<font face="Verdana" size ="2"><?php echo $pet_name." <br>Vs<br>".$res_name ;?>
</font>
</td>

<td width="40%" align="center" valign="top">
<?php
$case_type_array = array(2,3,5,6);
	if (in_array($case_type, $case_type_array)){
	$main_case_filing_no = get_main_case_filing_no($schemas,$db,$filing_no);
}else{
	$main_case_filing_no = $filing_no;
}
$st12=$dbonline->prepare("select distinct(rep_code) from e_more_representative where filing_no=? and party_flag='P' and display='t'");
	$st12->bindParam(1, $main_case_filing_no, PDO::PARAM_STR);
	
	$st12->execute();
	while ($row12 = $st12->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		
	     $adv_id = htmlspecialchars($row12['rep_code']);
	
	
	$stqq12 = $dbonline->prepare("select rep_name from e_master_advocate where id=?");
	$stqq12->bindParam(1, $adv_id, PDO::PARAM_INT);
	$stqq12->execute();
	  $pet_advname22 = $stqq12->fetchColumn();
	 ?>
	<font face="Verdana" size ="2"> 
	 <?php 
	  if(strtolower($pet_advname22) != strtolower('none')) {echo strtoupper($pet_advname22).'<br>'; }
	}	 
?>
</td>
<td width="40%" align="center" valign="top">
<?php 
$st121=$dbonline->prepare("select distinct(rep_code) from e_more_representative where filing_no=? and party_flag='R' and display='t'");
	$st121->bindParam(1, $main_case_filing_no, PDO::PARAM_STR);
	
	$st121->execute();
	while ($row121 = $st121->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		
	     $res_adv_id = htmlspecialchars($row121['rep_code']);
	
	
	$stqq121 = $dbonline->prepare("select rep_name from e_master_advocate where id=?");
	$stqq121->bindParam(1, $res_adv_id, PDO::PARAM_INT);
	$stqq121->execute();
	  $res_advname22 = $stqq121->fetchColumn();
	 ?>
	<font face="Verdana" size ="2"> 
	 <?php 
	  if(strtolower($res_advname22) != strtolower('none')) { echo strtoupper($res_advname22).'<br>'; }
	}	 
?>
</font>
</td>
<!--<td width="40%" align="center" valign="top">
<b><?php  echo $remarkss; ?></b>
<br/>
<button type="button" id="edit_<?php echo $filing_no; ?>" class="btn btn-sm btn-warning" name="edit_<?php echo $filing_no; ?>" onClick="return edit_draft_cause_list('<?php echo $filing_no; ?>','<?php echo $listdate_entire; ?>','<?php echo $listbefore; ?>','<?php echo $court_no; ?>','<?php echo $list_flag; ?>','<?php echo $bench_code1; ?>')">Edit</button>
<button type="button" id="remove_<?php echo $filing_no; ?>" class="btn btn-sm btn-danger" name="remove_<?php echo $filing_no; ?>" onClick="return remove_to_fresh_cases('<?php echo $filing_no; ?>','<?php echo $listdate_entire; ?>','<?php echo $listbefore; ?>','<?php echo $court_no; ?>','<?php echo $list_flag; ?>','<?php echo $bench_code1; ?>')">Remove</button>
<button type="button" id="set_child_cases_<?php echo $filing_no; ?>" class="btn btn-sm btn-primary" name="set_child_cases__<?php echo $filing_no; ?>" onClick="return show_child_cases('<?php echo $filing_no; ?>','<?php echo $listdate_entire; ?>','<?php echo $listbefore; ?>','<?php echo $court_no; ?>','<?php echo $list_flag; ?>','<?php echo $bench_code1; ?>')">Select IA/MA To Show</button>
</td>-->
</tr>



<?php

$all_connected_cases = get_connected_cases($schemas,$db,$filing_no);
if(!empty($all_connected_cases)){
	
	foreach($all_connected_cases as $key=>$con_cases){
		$conected_case_record = get_case_details($schemas,$db,$con_cases['conn_filing_no']);
		echo "<tr><td width='10%' align='center' valign='top'><font face='Verdana' size ='2'>".$sr_no++."</font></td><td width='20%' align='center' valign='top'><font face='Verdana' size ='2'><a href='javascript::void();'>".connected_case_no($schemas,$db,$conected_case_record)."</a>".$with_text."</font></td><td width='30%' align='center' valign='top'><font face='Verdana' size ='2'>".$conected_case_record['pet_name']."<br/>VS</br>".$conected_case_record['res_name']."</font></td>";
		echo "<td width='40%' align='center' valign='top'>".counsel_parties($schemas,$db,$dbonline,$case_type_array,$conected_case_record['case_type'],$con_cases['conn_filing_no'],'P')."</td><td width='40%' align='center' valign='top'>".counsel_parties($schemas,$db,$dbonline,$case_type_array,$conected_case_record['case_type'],$con_cases['conn_filing_no'],'R')."</td></tr>";

	}
}

?>



<?php
}
}
}
} ?>
</table><table  width="100%" border='0' cellpadding="1" cellspacing="3" align="center">
<?php }
	} 
	$causelist_remark = get_causelist_remark($db,$schemas,$listdate_entire,$court_no_remark,$list_flag_remark);
	if(!empty($causelist_remark))
		$type = 'update';
	else
		$type = 'add';
	?>
	<form method='post' action='add_causelist_remark_action.php' id='add_causelist_remark_form'>
		<table  width="100%" border='0' cellpadding="1" cellspacing="3" align="center">
			<tr>
			<input type='hidden' name='type_remark' id='type_remark' value='<?php echo $type; ?>'>
				<input type='hidden' name='listing_date_remark' id='listing_date_remark' value='<?php echo $listdate_entire; ?>'>
				<input type='hidden' name='court_no_remark' id='court_no_remark' value='<?php echo $court_no_remark; ?>'>
				<input type='hidden' name='list_flag_remark' id='list_flag_remark' value='<?php echo $list_flag_remark; ?>'>
				<td><textarea rows='2' name='cause_list_remark' id='causelist_remark' class='form-control' placeholder='Type Remark'><?php echo $causelist_remark; ?></textarea></td>
				<td style='padding-left:50px;'><button type='submit' name='submit' class='btn btn-primary btn-sm' id='save_causelist_remark_btn'>Save Remark</td>
			</tr>
		</table>
	</form>
	<table  width="100%" border='0' cellpadding="1" cellspacing="3" align="center">
		<tr><td><b>Copy to : -</b></td><td></td><td></td></tr>
		<tr><td></td><td><b>1 . Notice Board</b></td><td><b>By Order</b></td></tr>
		<tr><td></td><td><b>2 . Reception</b></td><td><b>-sd/-</b></td></tr>
		<tr><td></td><td><b>3 . Record File</b></td><td><b></b></td></tr>
		<tr><td></td><td><b>4 . Website:<a href='https://www.nclat.nic.in' target='_blank'>nclat.nic.in</a></b></td><td><b>Registrar</b></td></tr>
	</table>
<?php
}else{
	echo "<div style='text-align:center;color:red;'><h4>No record found</h4></div>";
}
}
?>
 <script >
 function OpenDMSForm(url,val1,val2,val3,val4,val5,val6,val7,val8,val9,val10)

 {

document.getElementById("frm").action=url;
document.getElementById("itemno1").value=val1;
document.getElementById("applno1").value=val2;
document.getElementById("courtno1").value=val3;
document.getElementById("caseno1").value=val4;
document.getElementById("casetype1").value=val5;
document.getElementById("partyname1").value=val6;
document.getElementById("title1").value=val7;
document.getElementById("status1").value=val8;
document.getElementById("j_key1").value=val9;
document.getElementById("j_securityKey1").value=val10;
document.getElementById("frm").submit();

 }
 
 function add_remark(listing_date,bench_nature,court_no,list_flag){
	//alert(listing_date+"    "+bench_nature+"  "+court_no+"    "+list_flag);
	$.ajax({
		type: "POST",
		url: "get_remarks.php",
		data: {listing_date:listing_date,bench_nature:bench_nature,court_no:court_no,list_flag:list_flag},
		beforeSend: function(){
			$("#remarks_modal_body").html("<h2><center>Loading....</center></h2>");
			$("#remarks_modal").modal('show');
		},
		success: function (data) {
		   $("#remarks_modal_body").html(data);
		},
		error: function (textStatus, errorThrown) {
		   alert("error");
		}

	});
	
 }
 
 function edit_draft_cause_list(filing_no,listing_date,bench_nature,court_no,list_flag,bench_no){
	$.ajax({
		type: "POST",
		url: "edit_draft_ajax.php",
		data: {type:'get_edit_data',filing_no:filing_no,listing_date:listing_date,bench_nature:bench_nature,court_no:court_no,list_flag:list_flag,bench_no:bench_no},
		beforeSend: function(){
			$("#edit_draft_cause_list_model_body").html("<h2><center>Loading....</center></h2>");
			$("#edit_draft_cause_list_model").modal('show');
		},
		success: function (data) {
		   $("#edit_draft_cause_list_model_body").html(data);
		},
		error: function (textStatus, errorThrown) {
		   alert("error");
		}

	});
 }
 
 function remove_to_fresh_cases(filing_no,listing_date,bench_nature,court_no,list_flag,bench_no){
	$.ajax({
		type: "POST",
		url: "edit_draft_ajax.php",
		data: {type:'back_to_fresh_cases',filing_no:filing_no,listing_date:listing_date,bench_nature:bench_nature,court_no:court_no,list_flag:list_flag,bench_no:bench_no},
		dataType: 'json',
		beforeSend: function(){
			$("#edit_draft_cause_list_model_body").html("<h2><center>Removing Case....</center></h2>");
			$("#edit_draft_cause_list_model").modal('show');
		},
		success: function (data) {
			//console.log(data);
		   $("#edit_draft_cause_list_model").modal('hide');
		   alert(data.message);
		   location.reload(true);
		},
		error: function (textStatus, errorThrown) {
			 /* console.log(errorThrown);
			console.log(textStatus); */
		   alert("error");
		   location.reload(true);
		}

	});
 }
 
 function show_child_cases(filing_no,listing_date,bench_nature,court_no,list_flag,bench_no){
	 $.ajax({
		type: "POST",
		url: "edit_draft_ajax.php",
		data: {type:'set_child_cases',filing_no:filing_no,listing_date:listing_date,bench_nature:bench_nature,court_no:court_no,list_flag:list_flag,bench_no:bench_no},
		beforeSend: function(){
			$("#edit_draft_cause_list_model_body").html("<h2><center>Loading....</center></h2>");
			$("#edit_draft_cause_list_model").modal('show');
		},
		success: function (data) {
		   $("#edit_draft_cause_list_model_body").html(data);
		  // location.reload(true);
		},
		error: function (textStatus, errorThrown) {
			 /* console.log(errorThrown);
			console.log(textStatus); */
		   alert("error");
		   //location.reload(true);
		}

	});
 }
 
 $(document).on('click','.close_add_remark',function(){
	$("#remarks_modal").modal('hide'); 
 });
 
  $(document).on('click','.close_edit_draft_cause_list_model',function(){
	$("#edit_draft_cause_list_model").modal('hide'); 
 });
 
 function popsurety_pending_report(url)
{
window.open(url,"_blank","directories=no, status=no,width=800, height=800, left=100, scrollbars=yes");   
		
}

 $('#add_causelist_remark_form').on('submit', function (e) {

          e.preventDefault();

          $.ajax({
            type: 'post',
            url: this.action,
            data: $('form').serialize(),
            beforeSend: function(){
			$("#save_causelist_remark_btn").html("Saving...");
			},
			success: function (data) {
				alert("Remark Saved");
			   location.reload(true);
			},
			error: function (textStatus, errorThrown) {
				 /* console.log(errorThrown);
				console.log(textStatus); */
				$("#save_causelist_remark_btn").html("Save Remark");
			   alert("error");
			   //location.reload(true);
			}
          });

        });
 </script>
  
   <form action="" method="POST" target="_blank" id="frm">
    <input type="hidden" id="itemno1" name="itemno"  value=""/>
    <input type="hidden" id="applno1" name="applno" value=""/>
    <input type="hidden" id="courtno1" name="courtno" value=""/>
    <input type="hidden" id="caseno1" name="caseno" value="" />
    <input type="hidden" id="casetype1" name="casetype" value=""/>
    <input type="hidden" id="partyname1" name="partyname" value="">
    <input type="hidden" id="title1"name="title" value="">
    <input type="hidden" id="status1" name="status" value="">	
    <input type="hidden" id="j_key1"name="j_key" value=""> 
    <input type="hidden" id="j_securityKey1" name="j_securityKey" value="">
   
</form>

<!-- Modal -->
  <div class="modal fade" id="remarks_modal" role="dialog">
    <div class="modal-dialog" style="width:90%">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Remarks</h4>
        </div>
        <div class="modal-body" id="remarks_modal_body">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger close_add_remark">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
 <!-- Modal -->
  <div class="modal fade" id="edit_draft_cause_list_model" role="dialog">
    <div class="modal-dialog" style="width:90%">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Causelist</h4>
        </div>
        <div class="modal-body" id="edit_draft_cause_list_model_body">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger close_edit_draft_cause_list_model">Close</button>
        </div>
      </div>
      
    </div>
  </div>
