<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/exportPDF/tableexport.js"></script>
<script src="../assets/exportPDF/FileSaver.js"></script>
<script src="../assets/exportPDF/jspdf.min.js"></script>
<script src="../assets/exportPDF/libs/jspdf.plugin.autotable.js"></script>
<script src="../assets/exportPDF/tableexport.js"></script>
<?php 
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
include("../master/functions.php");
session_start();

  /* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */ 

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
//$list_flag=$_REQUEST["listtype"];
$counttime=0;
$pagec=1;
$listbefore=$_REQUEST["list_before"];
//$bench_code1=$_REQUEST["bench_code1"];
$list_date =$_REQUEST['next_list_date'];
$court_no_remark=$court_no=$court_nono=$_REQUEST['court_no'];
$list_flag_remark =$list_flag=$_REQUEST['cause_list_type'];


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
$causelisthead ="PRIORITY CAUSE LIST";
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



$schemas=htmlspecialchars($_SESSION['schema_name']);

?>
<script language="javascript">
function popsurety_pending_report(cfy)

    {
    	
    		var url = "./generate_order.php?no="+cfy;


    		 window.open(url,"_blank","directories=no, status=no,width=800, height=800, left=100, scrollbars=yes");  
    		
    }
</script>

<div class="container-fluid">
<table cellspacing="1" cellpadding="1" border="0" align="center">
<div id="testdiv" style="visibility: visible;"><a href="javascript:window.print();"><font size="4" color="red">
Print</font></a>
<!--<a href="../mis/causelist_doc.php?list_flag=1&court_num=<?php echo $court_no; ?>&listing_date=<?php echo $listdate_entire; ?>"><font size="4" color="red">
Export in word</font></a>
<a href="javascript:void(0);" onclick="exportEXL()" class="btn btn-secondary d-print-none"><i class="fa fa-file-excel-o" aria-hidden="true"></i><font size="4" color="red">Export As Excel</font></a>-->
<a href="./causelist_order.php">
<font face="Verdana" color="red" size="3"><center><b>BACK</b></center></font></a>
</div>
</table>
<?php
$all_listing_dates =$db->prepare( "select distinct(listing_date),court_no,bench_nature,list_flag from $schemas.case_allocation
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
bench_no=? and (? between from_list_date and to_list_date);";
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
<table width="100%" border='0' cellpadding="1" cellspacing="3" align="center"  id="causelist">
<?php
$header =0;
$sql="select bench_no,detail  from $schemas.bench where bench_nature='$listbefore' and court_no='$court_no' and from_list_date='$listdate_entire' order by court_no,id,priority asc";
foreach($db->query($sql) as $row)
	{	
		  $bench_code1 =$row['bench_no'];
		  $bench_hearder_remark = $row['detail'];
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
 $case_count="select count(*) as count  from $schemas.case_allocation where listing_date='$todate'
 and bench_no='$bench_code1' and court_no='$court_no' and list_flag = '$list_flag'";
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


$sr_no=1;
$purpose_added = 0;

$main_city_name = return_zone_name_for_causelist($main_location_code);

?>
<tr><td align="center" colspan="7" style="font-size:20px;"><b><u>
<?php  echo htmlspecialchars(strtoupper('Goods and Services Tax Appellate Tribunal, '.$main_city_name)); ?>
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
<td align="center" colspan="7" style="font-size:16px;"><b><u><?php echo strtoupper($causelisthead.' DATED '.$causelist_display_date.' ('.$dayOfWeek.')'); ?></u></b>
</font>
</td>
</tr>
<tr>
<td align="center" colspan="7" style="font-size:16px;"><b><u><?php echo strtoupper($display_court_text.' '.$bench_hearder_remark); ?></u></b>
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


 $stat="select * from $schemas.bench where  bench_nature ='$listbefore' and from_list_date='$listdate_entire' and  bench_no='$bench_code1'";
$stat=$db->prepare("$stat");
$stat->execute();
while ($row = $stat->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$from_time= $row['from_time'];
$custom_text= $row['custom_text'];
if(empty($from_time)){
	$from_time = 'After the above';
}
$bench_remarks= $row['detail'];
$bench_no= $row['bench_no'];
$court_no =$row['court_no'];
 $presiding=$row['presiding'];
 $stat1="select *  from $schemas.master_judge where judge_code ='$presiding'";
$stat1 = $db->prepare($stat1);
$stat1->execute();
$presiding1 = $stat1->fetch();

$listingDate = date('j<\s\up>S</\s\up> F, Y', strtotime($listdate_entire));
echo "$from_time $custom_text</u></b></td></tr><tr><td colspan='7' style='font-size:16px;'><b>";
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
<table class="table table-bordered" id="cases" style="background-color:#ffffff;">

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
   $sql_count="select count(*) as count from $schemas.case_allocation where purpose='$purpose_code' and 
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
<?php  
// if($purpose_code == '12'){
// 	$purpose_name = "FOR ADMISSION (FRESH CASE/CASES)";
// }
echo htmlspecialchars(ucwords(strtoupper($purpose_name)));
$purpose_name ='';
?>
</font>
</td></tr>
<?php if($purpose_added == 0) { 
				$purpose_added = 1;
			?>
<tr>

<td width="10%" align="left" >

<b>
<?php echo strtoupper("s.no."); ?>
</b>
</td>
<td width="20%" align="left" >
<b>
<?php echo strtoupper("case no."); ?>
</b>
</td>
<td width="30%" align="left">
<b>
<?php echo strtoupper("name of parties"); ?>
</b>
</td>
<td width="40%" align="left">
<b>
<?php echo strtoupper("Authorized representative for Petitioners"); ?>
</b>
</td>
<td width="40%" align="left">
<b>
<?php echo strtoupper("Authorized representative for Respondents"); ?>
</b>
</td>
<!--<td width="40%" align="left">
<b>
<?php echo strtoupper("actions"); ?>
</b>
</td>-->
</tr>

<?php
} }
$count=0;
$countzz=1;
  $sql_allocation="select a.filing_no ,a.remarks,a.pet_name,a.res_name,a.list_with_defect  from $schemas.case_allocation a,
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
$list_with_defect = $row1['list_with_defect'];
$hc_dc_caseno=$row1['hc_dc_caseno'];
$remarkss=$row1['remarks'];
$pet_saved_name = $row1['pet_name'];
$res_saved_name = $row1['res_name'];

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

$ref_lcode ="select short_name from mater_location_city where city_id ='$location_code'";
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

}
		

 $main_case_fn = '';
 $case_no=$row2['case_no'];
 $case_type=$row2['case_type'];
 $case_year=$row2['case_year'];
 if (in_array($case_type, $main_cases)){
	$show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($filing_no);
}else{
	$main_case_fn = $show_party_filing_no = $direct_parent_filing_no = htmlspecialchars($row2['main_case_ia_no']);
	if(empty($show_party_filing_no))
		$show_party_filing_no = htmlspecialchars($filing_no);
}
if(!empty($pet_saved_name) && !empty($res_saved_name)){
	$pet_name = $pet_saved_name;
	$res_name = $res_saved_name;
}else{
$pet_name =get_party($db,$show_party_filing_no,'P',1);
$res_name =get_party($db,$show_party_filing_no,'R',1);
}
$location_code =$row2['location_code'];
//$location_code = $row2['bench_location'];
$lcode ="select short_name from mater_location_city where city_id ='$location_code'";
$lcode=$db->prepare($lcode);
$lcode->execute();
$lcodename = $lcode->fetchColumn();
$noaddfilingno =$row2['filing_no'];

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
		
$old_case_num = $tr_short = '';
 if($case_type == '40'){
	$old_case_info = get_old_case_info($db,$filing_no);
    if(!empty($old_case_info)){
		$transfer_case_type = $old_case_info['transfer_case_type'];
		if($transfer_case_type == '32'){
			$tr_short = ' (Company)';
		}else if($transfer_case_type == '33'){
			$tr_short = ' (Ins.)';
		}else if($transfer_case_type == '34'){
			$tr_short = ' (Compt.)';
		}else{
			$tr_short = '';
		}
	}	 
	$old_case_num = old_case_num($db,$schemas,$filing_no);
	if(!empty($old_case_num)){
		//$old_case_num = ' (old case '.$old_case_num.') ';
	}
	if($list_with_defect == '1'){
		$CASE_NO = htmlspecialchars(strtoupper($case_type_short_name).$tr_short.' No. '.'/'.$lcodename.'/'.$case_year1aa.' E-filling No. '.display_filing_no($filing_no));
	}else{
		$CASE_NO = htmlspecialchars(strtoupper($case_type_short_name).$tr_short.' No. '.$case_num1aa.'/'.$lcodename.'/'.$case_year1aa);
	}
 }else{	
		 if($list_with_defect == '1'){
		 $CASE_NO =  htmlspecialchars(' Listed With Defects '.display_filing_no($filing_no));
		}else{
			$CASE_NO = htmlspecialchars(strtoupper($case_type_short_name).'/'.$case_num1aa.'/'.$lcodename.'/'.$case_year1aa);
		}
 }

 
$is_uploaded = order_upload_status($schemas,$db,$filing_no,$listdate_entire);
$case_count="select count(*) as count  from $schemas.case_proceeding where listing_date=? and court_no = ? and filing_no = ?";
$sth15=$db->prepare($case_count);
$sth15->bindParam(1, $todate, PDO::PARAM_STR);
$sth15->bindParam(2, $court_nono, PDO::PARAM_STR);
$sth15->bindParam(3, $filing_no, PDO::PARAM_STR);
$sth15->execute();
$proceeding_exist=$sth15->fetchColumn();
if(!$proceeding_exist){
	$color = 'color:#ffffff;';
	$background = 'background-color:blue;';
}
elseif($proceeding_exist && !$is_uploaded){
	$color = 'color:#000000;';
	$background = 'background-color:yellow;';
}
elseif($is_uploaded == 'N'){
	$color = 'color:#ffffff;';
	$background = 'background-color:orange;';
}else{
	$color = 'color:#ffffff;';
	$background = 'background-color:green;';
}
$is_defective = check_is_partially_defective($db,$schemas,$filing_no,$listdate_entire);
if($is_defective)
	$defective_text = ' With Defects';
else
	$defective_text = '';
?>

<tr style='<?php echo $background.$color; ?>'>
<td width="10%" align="left" valign="top"><font size='2'><?php echo htmlspecialchars($sr_no++).".";?></font></td>
<td width="20%" align="left" valign="top" >
<font face="Verdana" size ="2">

<?php

$serial_no=$sr_no-1;

$filing_no_link1=$filing_no."@".$court_no."@".$list_date."@".$list_before."@".$list_flag."@".$purpose_code."@".$bench_code1."@".$sr_no;

if($proceeding_exist){ ?>
	<a href="javascript:popsurety_pending_report('<?php echo htmlspecialchars($filing_no_link1);?>');" style="text-decoration: none;<?php echo $color; ?>" > <?php echo $CASE_NO.''.$old_case_num.' '.$remarkss.$defective_text; ?></a>
<?php 
}else{ ?>
	<a href="javascript::void(0);" style="text-decoration: none;<?php echo $color; ?>" > <?php echo $CASE_NO.''.$old_case_num.' '.$remarkss.$defective_text; ?></a>

<?php 
}


// if main case has ia 
	//echo ia_cases_of_main_cases($schemas,$db,$db,$case_type,$filing_no,$type_of_filing = 2);
	//echo main_cases_child($schemas,$db,$filing_no,$status = 'p');
	echo show_selected_final_child_cases($schemas,$db,$filing_no,1,$listdate_entire,$bench_code1,$list_flag,$court_no,$child_or_connected = 'I',''); 
	echo recursive_cases($schemas,$db,$case_type,$filing_no);
	echo show_selected_final_child_cases($schemas,$db,$main_case_fn,1,$listdate_entire,$bench_code1,$list_flag,$court_no,$child_or_connected = 'I',$filing_no); 
	$all_connected_cases = selected_final_cases($schemas,$db,$filing_no,1,$listdate_entire,$bench_code1,$list_flag,$court_no,$child_or_connected = 'C','');
	
// end
?>
</font>
</td>

<td align="left" width="30%" valign="top">
<font face="Verdana" size ="2"><?php echo $pet_name." <br>Vs<br>".$res_name ;?>
<?php
	if(!empty($all_connected_cases)){
			echo '<br/><br/> <b>WITH</b>';
		}
 ?>
</font>
</td>

<td width="40%" align="left" valign="top">
<?php
$pet_adv_list = get_advocate_list($db,$schemas,$filing_no,$listdate_entire,$bench_code1,$court_no,$list_flag,'P');
if(!empty($pet_adv_list)){
	foreach($pet_adv_list as $k=>$val_adv){ ?>
		<font face="Verdana" size ="2"> 
	 <?php 
	 if(strtolower($val_adv['adv_name']) != strtolower('none')) {echo strtoupper($val_adv['adv_name'])." ".$val_adv['remarks'].'<br>'; }
	}
}else{
$case_type_query = $db->prepare("select id from case_type where status = 't' and main_or_child = 'C'");
$case_type_query->execute();
$case_type_array = $case_type_query->fetchAll();
$case_type_array = array_column($case_type_array, 'id');
	if (in_array($case_type, $case_type_array)){
	$main_case_filing_no = get_main_case_filing_no($schemas,$db,$filing_no);
}else{
	$main_case_filing_no = $filing_no;
}

$st12=$db->prepare("select a.rep_code,b.party_serial_no,b.party_flag,a.party_flag as pflag,a.remarks from e_more_representative as a left join e_cases_party as b on b.id = a.party_code where a.filing_no=? and a.party_flag='P' and a.show_in_causelist='t'
    UNION ALL
    select a.rep_code,b.party_serial_no,b.party_flag,a.party_flag as pflag,a.remarks 
    from e_more_representative_gst_nodal as a left join e_cases_party as b on b.id = a.party_code 
    where a.filing_no=? and a.party_flag='P' and a.show_in_causelist='t'");
	$st12->bindParam(1, $filing_no, PDO::PARAM_STR);
	$st12->bindParam(2, $filing_no, PDO::PARAM_STR);
	$st12->execute();
	while ($row12 = $st12->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		
	     $adv_id = htmlspecialchars($row12['rep_code']);
		if(!empty(htmlspecialchars($row12['party_serial_no']))){
	     $party_sr_no = htmlspecialchars($row12['pflag']).htmlspecialchars($row12['party_serial_no']);
		 }else{
			 $party_sr_no = '';
		 }
	
	$stqq12 = $db->prepare("select rep_name from e_master_advocate where id=?");
	$stqq12->bindParam(1, $adv_id, PDO::PARAM_INT);
	$stqq12->execute();
	  $pet_advname22 = $stqq12->fetchColumn();
	 ?>
	<font face="Verdana" size ="2"> 
	 <?php 
	 if(strtolower($pet_advname22) != strtolower('none')) {echo strtoupper($pet_advname22)." ".$row12['remarks'].'<br>'; }
	}
}	
?>
</td>

<td width="40%" align="left" valign="top">
<?php
$res_adv_list = get_advocate_list($db,$schemas,$filing_no,$listdate_entire,$bench_code1,$court_no,$list_flag,'R');
if(!empty($res_adv_list)){
	foreach($res_adv_list as $k=>$val_adv){ ?>
		<font face="Verdana" size ="2"> 
	 <?php 
	 if(strtolower($val_adv['adv_name']) != strtolower('none')) {echo strtoupper($val_adv['adv_name'])."  ".$val_adv['adv_type'].$val_adv['party_serial']." ".$val_adv['remarks'].'<br>'; }
	}
}else{


$st12=$db->prepare("select a.rep_code,b.party_serial_no,b.party_flag,a.party_flag as pflag,a.remarks from e_more_representative as a left join e_cases_party as b on b.id = a.party_code where a.filing_no=? and a.party_flag='R' and a.show_in_causelist='t'
    UNION ALL
    select a.rep_code,b.party_serial_no,b.party_flag,a.party_flag as pflag,a.remarks from e_more_representative_gst_nodal as a left join e_cases_party as b on b.id = a.party_code where a.filing_no=? and a.party_flag='R' and a.show_in_causelist='t'");
	$st12->bindParam(1, $filing_no, PDO::PARAM_STR);
	$st12->bindParam(2, $filing_no, PDO::PARAM_STR);
	$st12->execute();
	while ($row12 = $st12->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		
	     $adv_id = htmlspecialchars($row12['rep_code']);
		if(!empty(htmlspecialchars($row12['party_serial_no']))){
	     $party_sr_no = htmlspecialchars($row12['pflag']).htmlspecialchars($row12['party_serial_no']);
		 }else{
			 $party_sr_no = '';
		 }
	
	$stqq12 = $db->prepare("select rep_name from e_master_advocate where id=?");
	$stqq12->bindParam(1, $adv_id, PDO::PARAM_INT);
	$stqq12->execute();
	  $pet_advname22 = $stqq12->fetchColumn();
	 ?>
	<font face="Verdana" size ="2"> 
	 <?php 
	 if(strtolower($pet_advname22) != strtolower('none')) {echo strtoupper($pet_advname22)." ".$party_sr_no." ".$row12['remarks'].'<br>'; }
	}
}	
?>
</td>
</tr>



<?php
if(!empty($all_connected_cases)){
	$count_connected = (count($all_connected_cases)-1);
	foreach($all_connected_cases as $key=>$con_cases){
		$with_text = '';
		if($count_connected != $key){
			$with_text = '<br/></br/> <b>WITH</b>';
		}
		$conected_case_record = get_case_details($schemas,$db,$con_cases['filing_no']);
		if(!empty($con_cases['pet_name']) && !empty($con_cases['res_name'])){
			$pet_name_conn = $con_cases['pet_name'];
			$res_name_conn = $con_cases['res_name'];
		}else{
			$pet_name_conn = $conected_case_record['pet_name'];
			$res_name_conn = $conected_case_record['res_name'];
		}
		echo "<tr><td width='10%' align='left' valign='top'><font face='Verdana' size ='2'>".$sr_no++."</font></td>"; ?>
		<td width='20%' align='left' valign='top'><font face='Verdana' size ='2'><a href='javascript::void();'><?php echo connected_case_no($schemas,$db,$conected_case_record); ?></a>
		<?php
			echo show_selected_final_child_cases($schemas,$db,$con_cases['filing_no'],1,$listdate_entire,$bench_code1,$list_flag,$court_no,$child_or_connected = 'I',''); 
			echo recursive_cases($schemas,$db,$case_type,$con_cases['filing_no']);
		?>
		</font></td>
		<?php 
		echo "<td width='30%' align='left' valign='top'><font face='Verdana' size ='2'>".$pet_name_conn."<br/>VS</br>".$res_name_conn.$with_text."</font></td>";
		echo "<td width='40%' align='left' valign='top'>".counsel_parties($schemas,$db,$db,$case_type_array,$conected_case_record['case_type'],$con_cases['filing_no'],'P',$listdate_entire,$bench_code1,$list_flag,$court_no)."</td><td width='40%' align='left' valign='top'>".counsel_parties($schemas,$db,$db,$case_type_array,$conected_case_record['case_type'],$con_cases['filing_no'],'R',$listdate_entire,$bench_code1,$list_flag,$court_no)."</td></tr>";

	}
}
?>



<?php
}
}
}
}
?>
</table><table  width="100%" border='0' cellpadding="1" cellspacing="3" align="center">
<?php }
	} 
	$causelist_remarks = get_causelist_remark($db,$schemas,$listdate_entire,$court_no_remark,$list_flag_remark);
	if(!empty($causelist_remarks['remark'])){
		echo "<h4><b>$causelist_remarks[remark]</b></h4>";
	}
	if(!empty($causelist_remarks['remark_footer'])){
		echo html_entity_decode($causelist_remarks['remark_footer']);
	}	else {
	?>
	<table  width="100%" border='0' cellpadding="1" cellspacing="3" align="center">
		<tr><td><b>Copy to : -</b></td><td></td><td></td></tr>
		<tr><td></td><td><b>1 . Notice Board</b></td><td><b>By order of</b></td></tr>
		<tr><td></td><td><b>2 . Website:<a href='https://www.gstat.nic.in' target='_blank'>gstat.nic.in</a></b></td><td><b>Hon’ble President, GSTAT.</b></td></tr>
	</table>
<?php
	}
}else{
	echo "<div style='text-align:center;color:red;'><h4>No record found</h4></div>";
}
}
?>
</table>
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

<script>
var currentdate = new Date();
   var datetime = currentdate.getHours()+ "/" + currentdate.getMinutes() + "/" + currentdate.getSeconds();
   var listing_date = '<?php echo $listdate_entire; ?>'
   var court_no = '<?php echo $court_no; ?>'
  var xlsdocname = listing_date+"court_no"+court_no;
function exportEXL(){
  $('#cases').tableExport({
    type:'excel',
    fileName: xlsdocname,
    worksheetName: xlsdocname
  
  });
};

function exportCSV(){
  $('#cases').tableExport({
    type:'csv',
    fileName: xlsdocname
  });
};
</script>
