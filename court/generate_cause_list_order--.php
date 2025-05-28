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

if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
echo "Access Problem....."; 
session_unset();     // unset $_SESSION variable for the run-time
session_destroy();
header("Location: ../login.php?aa=100");
die();
}

/*if($main_id =='9999' and $localadmin =='0')
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
	

//$list_flag=$_REQUEST["listtype"];
$counttime=0;
$pagec=1;
$listbefore=$_REQUEST["list_before"];
//$bench_code1=$_REQUEST["bench_code1"];
$list_date =$_REQUEST['next_list_date'];
$court_no=$court_nono=$_REQUEST['court_no'];
//$list_flag=$_REQUEST['listflag'];

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

$list_flag =1;

$schemas=htmlspecialchars($_SESSION['schema_name']);





$sr_no =1;
$sql="select * from $schemas.bench b ,$schemas.bench_nature bn
where b.from_list_date= '$listdate_entire' and  b.bench_nature='$listbefore' and bn.bench_code ='$bench_code1' and
b.bench_nature=bn.bench_code and b.court_no ='$court_no' order by b.court_no, b.priority asc";

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
background-color: linen;
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
<script language="javascript">
function popsurety_pending_report(cfy)

    {
    	
    		var url = "./order_creation_bulk_ind1.php?no="+cfy;


    		 window.open(url,"_blank","directories=no, status=no,width=800, height=800, left=100, scrollbars=yes");   
    		
    }
</script>
<style type="text/css">
div.hidden {
display: none;
}

</style>
<table cellspacing="1" cellpadding="1" border="0" width="95%"   align="center">
<div id="testdiv" style="visibility: visible;"><a href="javascript:window.print();"><font size="4" color="red">
Print</font></a>
	
</div>
<tr><td colspan="15"><a href="./order_creation_bulk_ind.php">
<font face="Verdana" color="red" size="3"><center><b>BACK</b></center></font></a>

</td></tr>
</table>

<table width="100%" border='0' cellpadding="1" cellspacing="3" align="center">
<?php
$header =0;
$sql="select bench_no  from $schemas.bench where bench_nature='$listbefore' and court_no='$court_no' and from_list_date='$listdate_entire'";
foreach($db->query($sql) as $row)
	{	
		  $bench_code1 =$row['bench_no'];
	



$sql_judge="select jm.judge_name,jm.judge_desg_code,jm.judge_code from $schemas.master_judge as jm,
$schemas.bench as b where  b.from_list_date=? and b.bench_no=? and
b.court_no=? and jm.judge_code=b.presiding";
$sth101=$db->prepare($sql_judge);
$sth101->bindParam(1, $todate, PDO::PARAM_STR);
$sth101->bindParam(2, $bench_no1, PDO::PARAM_STR);
$sth101->bindParam(3, $court_no, PDO::PARAM_STR);
$sth101->execute();
while ($j = $sth101->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$judge_name=$j['judge_name'];
$judge_desg_code=$j['judge_desg_code'];
$presiding_code=$j['judge_code'];

/*query for display of judge*/
// $jj = "select display from $schemas.bench_judge where display='true' and judge_code =$presiding_code  and from_list_date=$todate and bench_no=$bench_no1 ";
$jj = "select display from $schemas.bench_judge where display='true' and judge_code =?  and from_list_date=? and bench_no=? ";
$jj=$db->prepare($jj);
$jj->bindParam(1, $presiding_code, PDO::PARAM_STR);
$jj->bindParam(2, $todate, PDO::PARAM_STR);
$jj->bindParam(3, $bench_no1, PDO::PARAM_STR);
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
 and bench_no='$bench_code1' and court_no='$court_no'";
$sth15=$db->prepare($case_count);
//$sth15->bindParam(1, $todate, PDO::PARAM_STR);
//$sth15->bindParam(2, $list_flag, PDO::PARAM_STR);
//$sth15->bindParam(3, $bench_code1, PDO::PARAM_STR);
//$sth15->bindParam(4, $court_no, PDO::PARAM_STR);
$sth15->execute();
$case_exist=$sth15->fetchColumn();

if($case_exist == '0' || $case_exist =='')
{
$sql_desg="select name from initilization";
$sth14=$db->prepare($sql_desg);
$sth14->execute();
$name_ins=$sth14->fetchColumn();
?>






<tr><td align="center" colspan="7"><font face="Verdana" color="red" size ="4"><?php echo htmlspecialchars("NO RECORD FOUND");?> </font>
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
?>
<tr><td align="center" colspan="7"><font face="Verdana" size ="2">
<?php  echo htmlspecialchars(strtoupper($name_ins)); ?>
</font>
</td></tr>
<?php
$dateCol = 'detail';

 $sql_time="select $dateCol from $schemas.bench  where court_no=? and
bench_no=? and (? between from_list_date and to_list_date);";
$sth10=$db->prepare($sql_time);
$sth10->bindParam(1, $court_no, PDO::PARAM_STR);
$sth10->bindParam(2, $bench_no1, PDO::PARAM_STR);
$sth10->bindParam(3, $todate, PDO::PARAM_STR);
$sth10->execute();
$detail123=$sth10->fetchColumn();

?>
<tr><td align="center" colspan="7"><font face="Verdana" size ="2">
<?php  echo "<br>DAILY CAUSE LIST <br> PRINCIPAL BENCH NEW DELHI<br>".htmlspecialchars($detail123)."&nbsp;".htmlspecialchars(strtoupper($listdate)); ?>
</font>
</td>
</tr>
<?php
}
?>

<tr>
<td colspan="7">
<p style="border-bottom: 2px dotted #000000;"></p>
</td>
</tr>
<tr>
<td align="center" colspan="7"><font face="Verdana" size ="2"><b>COURT NO :
<?php
echo htmlspecialchars($court_no);
?>
</b></font>
</td>
</tr>

<tr><td align="center" colspan="7"><b>CORAM</b></td></tr>

<tr><td align="center" colspan="7"><font face="Verdana" size ="2" ><b>
<?php 
list($day,$month,$year)=explode('/',$nd);
$fd=$year.'-'.$month.'-'.$day;

$stat="select * from $schemas.bench where  bench_nature ='$listbefore' and from_list_date='$listdate_entire' and  bench_no='$bench_code1'";
$stat=$db->prepare("$stat");
//$stat->bindParam(1, $display, PDO::PARAM_STR);
$stat->execute();
while ($row = $stat->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$from_time= $row['from_time'];
$bench_no= $row['bench_no'];
$court_no =$row['court_no'];
$presiding=$row['presiding'];
$stat1="select *  from $schemas.master_judge where judge_code ='$presiding'";
$stat1 = $db->prepare($stat1);
$stat1->execute();
$presiding1 = $stat1->fetch();
 echo $gen=$presiding1['gen']."&nbsp";
echo  $presiding1['judge_name'];
$hon_text=$presiding1['hon_text'];
$stat1="select desg_name from $schemas.master_desg where desg_code =?";
$stat1 = $db->prepare($stat1);
$stat1->bindParam(1,$presiding1[judge_desg_code], PDO::PARAM_STR);
$stat1->execute();
echo", ".$hon_text." ".$desg_name = $stat1->fetchColumn();






}




?>
<br>

<?php
 $stat2="select distinct(judge_code) as judge_code from $schemas.bench_judge where bench_nature='$listbefore' and from_list_date='$listdate_entire' and judge_code !='$presiding' and bench_no='$bench_code1'";
$stat2=$db->prepare("$stat2");
//$stat->bindParam(1, $display, PDO::PARAM_STR);
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
 $hon_text = $judge_data['hon_text'];

  
echo $gen." ".$judge_name = $judge_data['judge_name'];
$desg_code = $judge_data['judge_desg_code'];
$stat1="select desg_name from $schemas.master_desg where desg_code =?";
$stat1 = $db->prepare($stat1);
$stat1->bindParam(1,$desg_code, PDO::PARAM_STR);
$stat1->execute();
echo", ".$hon_text." ".$desg_name= $stat1->fetchColumn()."<br>";



}

?>


</font>

</td>


</tr>
<tr>
</tr>

<?php if($from_time!='')
{
?>
<tr>
<td>
</td>
<td>
</td>
<td>
</td>

<td align="left"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;Time:

<?php echo $from_time;?>
<?php
}
?>
</b>
</td>
<tr>

<td>

<b>
Serial No.
</b>
</td>
<td>
<b>
Case No.
</b>
</td>
<td>
<b>
Section
</b>
</td>
<td>
<b>
Name of Parties
</b>
</td>
<td>
<b>
Advocate Name
</b>
</td>

</tr>
<tr>
<td colspan="7">
<p style="border-bottom: 2px dotted #000000;"></p>
</td>
</tr>



<?php
}
if($b_nature > 0)
{
$sql_purpose="select distinct(purpose), priority from $schemas.bench_purpose_priority where
from_date='$todate' and  bench_no='$bench_code1'  and 
bench_nature='$b_nature' order by priority ASC";

$sth_j12=$db->prepare($sql_purpose);
//$sth_j12->bindParam(1, $todate, PDO::PARAM_STR);
//$sth_j12->bindParam(2, $from_time, PDO::PARAM_STR);
//$sth_j12->bindParam(3, $bench_code1, PDO::PARAM_STR);
//$sth_j12->bindParam(4, $b_nature, PDO::PARAM_STR);
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
court_no='$court_nono'";

$sth1r=$db->prepare($sql_count);
//$sth1r->bindParam(1, $purpose_code, PDO::PARAM_STR);
//$sth1r->bindParam(2, $todate, PDO::PARAM_STR);
//$sth1r->bindParam(3, $list_flag, PDO::PARAM_STR);
//$sth1r->bindParam(3, $bench_code1, PDO::PARAM_STR);
//$sth1r->bindParam(4, $court_no, PDO::PARAM_STR);
$sth1r->execute();
 $count_filing=$sth1r->fetchColumn();
}
if($count_filing > 0)
{
$sql_purpose_name="select purpose_name from $schemas.master_purpose where 
purpose_code='$purpose_code' ";
$sth4x=$db->prepare($sql_purpose_name);
//$sth4x->bindParam(1, $purpose_code, PDO::PARAM_STR);
$sth4x->execute();
$purpose_name=$sth4x->fetchColumn();
}
if($purpose_name !='')
{
?> <tr><td align="center" colspan="7"><font face="Verdana" size ="3"><u><b>
<?php  echo "<br>".htmlspecialchars(ucwords(strtoupper($purpose_name)))."<br><br>";
$purpose_name ='';
?></u>
</font>
</td></tr>

<?php
}

$count=0;
$countzz=1;
 $sql_allocation="select a.filing_no ,a.remarks  from $schemas.case_allocation a,
$schemas.case_detail d where d.status =? and  a.purpose=? and a.listing_date=?
and a.bench_no=? and a.court_no=?  and
a.filing_no=d.filing_no order by  a.priority_serial,d.case_no,d.case_year asc";

$status='P';
$sth_j12c=$db->prepare($sql_allocation);
$sth_j12c->bindParam(1, $status, PDO::PARAM_STR);
$sth_j12c->bindParam(2, $purpose_code, PDO::PARAM_STR);
$sth_j12c->bindParam(3, $todate, PDO::PARAM_STR);
//$sth_j12c->bindParam(4, $list_flag, PDO::PARAM_STR);
$sth_j12c->bindParam(4, $bench_code1, PDO::PARAM_STR);
$sth_j12c->bindParam(5, $court_nono, PDO::PARAM_STR);
$sth_j12c->execute();
while ($row1 = $sth_j12c->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$filing_no=$row1['filing_no'];

$hc_dc_caseno=$row1['hc_dc_caseno'];
$remarkss=$row1['remarks'];

 $sql_cd="select a.pet_type,a.location_code,a.res_type,a.legal_aid ,
a.oa_ref_no,a.case_no, a.case_type,a.case_year, a.pet_name,a.res_name,a.pet_adv,a.res_adv,a.pet_adv_name,
a.res_adv_name from $schemas.case_detail as a  where a.filing_no=?
and status =? order by a.case_type,a.case_no ASC";

$status='P';
$sth_j12cc=$db->prepare($sql_cd);
$sth_j12cc->bindParam(1, $filing_no, PDO::PARAM_STR);
$sth_j12cc->bindParam(2, $status, PDO::PARAM_STR);
$sth_j12cc->execute();

while ($row2 = $sth_j12cc->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$count++;
$oa_ref_no=$row2['oa_ref_no'];

if($oa_ref_no!='')
{
$ref_newst31="select case_type,case_no,case_year,location_code from $schemas.case_detail where filing_no='$oa_ref_no'";
$ref_newst31=$db->prepare($ref_newst31);
$ref_newst31->execute();
$ref_resultset1 = $ref_newst31->fetch();
extract($ref_resultset1);

$ref_lcode ="select short_name from $schemas.bench_location where bench_location_code ='$location_code'";
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
$pet_name=$row2['pet_name'];
$res_name=$row2['res_name'];
$location_code =$row2['location_code'];

$lcode ="select short_name from $schemas.bench_location where bench_location_code ='$location_code'";
$lcode=$db->prepare($lcode);
$lcode->execute();
$lcodename = $lcode->fetchColumn();


$pet_adv=$row2['pet_adv'];
$res_adv=$row2['res_adv'];

if($pet_adv >0)
{
$stQ = $db->prepare("select rep_name from e_master_advocate where id = ?");
$stQ->bindParam(1, $pet_adv, PDO::PARAM_STR);
$stQ->execute();
$petadvname=$stQ->fetchColumn();
}
if($res_adv >0)
{
$stQ = $db->prepare("select rep_name from e_master_advocate where id = ?");
$stQ->bindParam(1, $res_adv, PDO::PARAM_STR);
$stQ->execute();
$resadvname=$stQ->fetchColumn();
}



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
		
		$CASE_NO = htmlspecialchars(strtoupper($case_type_short_name).'/'.$case_num1aa.'('.$lcodename.')'.$case_year1aa);


?>

<tr>
<td width="5%" align="top"><font size='2'><?php echo htmlspecialchars($sr_no++);?></font></td>
<td width="15%" >
<font size='2' >
 <?php 

  /* 
$username1 = "postgres";
$password1 = "";
$host1 = "localhost";
//$host1="localhost";
$dbname1 = "ncltonline1";
$dbonline = new PDO("pgsql:host={$host1};dbname={$dbname1}", $username1, $password1 );
$dbonline->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbonline->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
   
  
   $stqq = $dbonline->prepare("select count(filing_no) from e_case_detail  where filing_no=?");
   $stqq->bindParam(1, $filing_no, PDO::PARAM_INT);
   $stqq->execute();
   $filing_norevari = $stqq->fetchColumn();
  
   if($filing_norevari > '0')
   {
  $sthr=$dbonline->prepare("select * from e_case_detail  where filing_no=? ");
  $sthr->bindParam(1, $filing_no, PDO::PARAM_STR);
  $sthr->execute();
  while ($rowa = $sthr->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  	$fileupload=$rowa['unique_id_no'];
  	  	
  	
  } 

  ?>
 

  </font>

<?php
$serial_no=$sr_no-1;

echo "<a style='text-decoration:none;' href='http://efiling.nclt.gov.in:8080/dms-ecourt/ecourt-search-within-dms?applno=".$fileupload."&partyname=".$pet_name." VS ".$res_name."&courtno=".$court_no.
"&casetype=".$case_type_short_name."&caseno=".$CASE_NO."&location=".$lcodename."&case_year=".$case_year1aa."&itemno=".$serial_no.
"&status=P&j_key=vVl%2FAz1yGsjOAG18WDeScg%3D%3D&j_securityKey=6D6C069D681B40DBF95CAD7B3ED71BE1A46F0A7036BC711860B00BAAE50FE8A4%21TZFIMZbTiUtqXMfARJ1DGgyNicWFwYkwTA0ip%2FQ8Wns%3D' target='_blank'>".
$CASE_NO."</a>";








?>
<?php
 }
*/
 ?>
<?php
$filing_no_link=$filing_no."@".$court_no."@".$list_date."@".$list_before."@".$list_flag."@".$purpose_code."@".$bench_code1."@".$listbefore;
?>

 <a href="javascript:popsurety_pending_report('<?php echo htmlspecialchars($filing_no_link) ; ?>');" style="text-decoration: none;" > <?php echo $CASE_NO ?></font></a>


<?php

 
	
 

if($ref_CASE_NO!=''){?><br>In<br><?php echo $ref_CASE_NO;}?>
</font>
</td>

<td width="20%">
<?php

$st2=$dbonline->prepare("select * from e_case_detail_fees where filing_no=? ");
                      $st2->bindParam(1, $filing_no, PDO::PARAM_STR);
                      $st2->execute();
$i=0;$r='';
                      while ($row2= $st2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {    
                          $E_sec_id=$row2['sec_id'];
                       
                      $st3=$dbonline->prepare("select * from master_section_act where id=? ");
                      $st3->bindParam(1, $E_sec_id, PDO::PARAM_STR);
                      $st3->execute();
                      
                      while ($row3 = $st3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {
                      	  $E_add_sec_id=$row3['section_companies'];                      
                           $r.=$E_add_sec_id.',';
                       
                        
                       
                      }
                      
                      }
                      echo rtrim($r,',');

?>
</td>

<td width="30%" valign="top">
<font face="Verdana" size ="2"><?php echo $pet_name." <br>"."V/s."."<br>".$res_name ;?>
</font>
</td>
<td valign="top">
<?php
$st12=$dbonline->prepare("select * from e_more_representative where filing_no=? and party_flag='P' ");
	$st12->bindParam(1, $filing_no, PDO::PARAM_STR);
	
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
	  echo strtoupper($pet_advname22).'<br>';
	}	 
?>
<?php  echo "--------------------<br>";?>
<?php 
$st121=$dbonline->prepare("select * from e_more_representative where filing_no=? and party_flag='R' ");
	$st121->bindParam(1, $filing_no, PDO::PARAM_STR);
	
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
	  echo strtoupper($res_advname22).'<br>';
	}	 
?>
</font>
</td>
</tr>



<?php

$con="select conn_filing_no from $schemas.connected_cases where status='C' and filing_no=?";
$con=$db->prepare($con);
$con->bindParam(1, $filing_no, PDO::PARAM_STR);
$con->execute();
if($con->rowCount()>0){
	
	
	
	
	echo '<tr><td></td><td width="30%">with</td></tr>';
}


while ($rowc1 = $con->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
 $connected_filing_no=$rowc1['conn_filing_no'];

$sthrc=$dbonline->prepare("select * from e_case_detail  where filing_no=? ");
  $sthrc->bindParam(1,  $connected_filing_no, PDO::PARAM_STR);
  $sthrc->execute();
  while ($rowac = $sthrc->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  	$fileuploadc=$rowac['unique_id_no'];
  	  	
  	
  } 
	

$condata="select a.pet_type,a.location_code,a.res_type,a.legal_aid ,
a.oa_ref_no,a.case_no, a.case_type,a.case_year, a.pet_name,a.res_name,a.pet_adv,a.res_adv,a.pet_adv_name,
a.res_adv_name from $schemas.case_detail as a  where a.filing_no=?
and a.status =? order by a.case_no ASC";
$status='P';
$condata=$db->prepare($condata);
$condata->bindParam(1, $connected_filing_no, PDO::PARAM_STR);
$condata->bindParam(2, $status, PDO::PARAM_STR);
$condata->execute();
while ($rowc = $condata->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$case_noc=$rowc['case_no'];
$case_typec=$rowc['case_type'];
$case_yearc=$rowc['case_year'];
$pet_namec=$rowc['pet_name'];
$res_namec=$rowc['res_name'];
$location_codec =$rowc['location_code'];
$lcodec ="select short_name from $schemas.bench_location where bench_location_code ='$location_codec'";
$lcodec=$db->prepare($lcodec);
$lcodec->execute();
$lcodenamec = $lcodec->fetchColumn();
//$pet_advc=$row2c['pet_adv'];
//$res_advc=$row2c['res_adv'];
/*
if($pet_advc >0)
{
$stQc = $db->prepare("select rep_name from e_master_advocate where id = ?");
$stQc->bindParam(1, $pet_advc, PDO::PARAM_STR);
$stQc->execute();
$petadvnamec=$stQc->fetchColumn();
}
if($res_advc >0)
{
$stQc = $db->prepare("select rep_name from e_master_advocate where id = ?");
$stQc->bindParam(1, $res_advc, PDO::PARAM_STR);
$stQc->execute();
$resadvnamec=$stQc->fetchColumn();
}
*/
if($case_typec > 0)
{
$stQc = $db->prepare("select short_name from case_type where id = ?");
$stQc->bindParam(1, $case_typec, PDO::PARAM_STR);
$stQc->execute();
$case_type_short_namec=$stQc->fetchColumn();
}
$case_numaac = $case_noc;
$case_year1aac = $case_yearc;
		$case_num1aac=ltrim($case_numaac,0);
		
$CASE_NOc = htmlspecialchars(strtoupper($case_type_short_namec).'/'.$case_num1aac.'('.$lcodenamec.')'.$case_year1aac);


?>

<tr>
<td>
</td>
<td width="30%">
<?php
}

?>
<font face="Verdana" size ="2">
<?php

echo $CASE_NOc;








?>
<br>
<br>
</td>
</font>
<td>
<?php


?>
<td>
</tr>

<?php

}//close of connected data loop
?>



<?php
}
}
}
}
}
}
?>