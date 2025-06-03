<script type="text/javascript" language="javascript">
function DisableBackButton() {
window.history.forward()
}
DisableBackButton();
window.onload = DisableBackButton;
window.onpageshow = function(evt) { if (evt.persisted) DisableBackButton() }
window.onunload = function() { void (0) }
</script>
<?php 
global $action_type;
global $order_passed;
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
include '../db_inc2.php';
//session_start();
global  $res_adv_name;


if($_REQUEST['filing_no'] !=''){
$hash =base64_decode($_REQUEST['filing_no']);
$hash11 = explode("/",$hash);

$filing_no = $hash11[0];
$schemas = $hash11[1];






}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title>Detail Report Report</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/sb-admin.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/coin-slider.css" />
<script type="text/javascript" src="./includes/highslide/highslide-with-html1.js"></script>
<link rel="stylesheet" type="text/css" href="./includes/highslide/highslide.css" />

<style type="text/css">
div.hidden {
display: none;
}
</style>

<script language="javascript">
function change(id, newClass)
{
identity=document.getElementById(id);
identity.className=newClass;

}
function printPage()
{http://www.novell.com/linux/10.html
change("testdiv","hidden");
window.print();
}


</script>
<style>
table, td, th {
border: 1px solid #2874f0;
}
body{
background-color:  white;
color:black;
}
th {
background-color: #3c8dbc;
color: white;
font-weight: bold;
}
</style>



</head>

<body>
<div class="h-100 d-inline-block">
<p>
<form name="frm" method="post">
<table cellspacing="1" cellpadding="1" border="0" width="95%" class="fixed"  align="center" bgcolor="#F0F8FF"> 
<tr>

<SCRIPT language=JavaScript>

function win(){

//window.opener.location.href="./logout1.php";

self.close();

}

function printPage(){
var printButton = document.getElementById("testdiv");
printButton.style.visibility = 'hidden';

var LogoutBtn = document.getElementById("Logout_ID");
LogoutBtn.style.visibility = 'hidden';
window.print();
printButton.style.visibility = 'visible';
LogoutBtn.style.visibility = 'visible';


}
</SCRIPT>
<td align="left" colspan="2">
<div id="testdiv" style="visibility: visible;">   <input type='button' value='Print' onClick="javascript:printPage();" /></div>
</td>
<td align="right" colspan="7">
<div Id ="Logout_ID">
<input type='button' value='Close' onClick="win();" />
</div>
</td>
</tr>

</table>

<?php
global $scrtok;
if($scrtok == '1'){
$sql_all1234="select filing_no from e_case_detail where filing_no=?";
$sql_all1234=$dbonline->prepare($sql_all1234);
$sql_all1234->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql_all1234->execute();
$filing_no = $sql_all1234->fetchColumn();
}else{
$sql_all1234="select filing_no from $schemas.case_detail where filing_no=?";
$sql_all1234=$db->prepare($sql_all1234);
$sql_all1234->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql_all1234->execute();
$filing_no = $sql_all1234->fetchColumn();
}

if($filing_no == "")
{

echo "</br><hr /></br>";
die("Record Not Found.....");
}


if($filing_no != "" && $scrtok != '1')
{
 
 $sql_cp="select * from $schemas.case_detail where filing_no=?";

$sql_cp = $db->prepare($sql_cp);
$sql_cp->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql_cp->execute();


while ($row = $sql_cp-> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{ 

$case_type = htmlentities(htmlspecialchars($row['case_type']));
//$short_name = htmlentities(htmlspecialchars($row['short_name']));
$caseno =htmlentities(htmlspecialchars($row['case_no']));
 $location_code =htmlentities(htmlspecialchars($row['location_code']));
 $caseyear =htmlentities(htmlspecialchars($row['case_year']));
$filing_no =htmlentities(htmlspecialchars($row['filing_no']));
$filing_no1234=substr($filing_no,5,6);
$filing_year=substr($filing_no,11,4);
$main_status = htmlentities(htmlspecialchars($row['status']));
$dt_of_filing = htmlentities(htmlspecialchars($row['dt_of_filing']));
$dt_of_filing1 = explode("-" , $dt_of_filing);
$dt_of_filing2 = $dt_of_filing1[2]."-".$dt_of_filing1[1]."-".$dt_of_filing1[0];
$filin_no1=htmlentities(htmlspecialchars($row['filing_no']));

$dt_of_filing2_exp = explode("-" , $dt_of_filing2);
$dt_of_filing2_display = $dt_of_filing2_exp[0]."/".$dt_of_filing2_exp[1]."/".$dt_of_filing2_exp[2];



}
$sql2="select * from case_type where id=?";

$sql2 = $db->prepare($sql2);
$sql2->bindParam(1, $case_type, PDO::PARAM_STR);
$sql2->execute();


while ($row =$sql2-> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{ 

$case_type_name = htmlentities(htmlspecialchars($row['case_type_desc_cis']));
}


$sql2="select * from $schemas.bench_location where bench_location_code=?";

$sql2 = $db->prepare($sql2);
$sql2->bindParam(1, $location_code, PDO::PARAM_STR);
$sql2->execute();


while ($row =$sql2-> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{ 

 $location_code = htmlentities(htmlspecialchars($row['short_name']));
}

if($main_status=='P')
{
$main_statusx="Pending";
}

if($main_status=='D')
{
$main_statusx="Disposed";
}
$filing_no2=$filin_no1;
$case_type_name;	
//echo 'dd';
if($caseno=='') { $display_case_no=$case_type_name;}
else {
$display_case_no=htmlspecialchars(strtoupper($case_type_name)."/".$caseno."(".$location_code.")".$caseyear);
}
?>

<table height="40%" width="90%" border="0" cellpadding="0" cellspacing="2"  align="center">   

<tr><th colspan=5 align="center"><b>CASE STATUS </b></th></tr>
<tr><td width="30%"><b>Diary no/Year</b></td>

<td width="70%"><?php echo htmlspecialchars($filing_no) ;?></td></tr>

<tr><td width="30%"><b>Case Type/Case No/Year</b></td> 
<td width="70%"><?php if($filing_no!=""){ echo htmlspecialchars($display_case_no);}else echo 'Not Registered';?></td></tr>
	
<tr><td width="30%"><b>Date of Filing</b></td><td width="70%"><?php echo  htmlspecialchars($dt_of_filing2_display); ?></td></tr>
<tr><td width="30%"><b>Case Status</b></td><td width="70%"><font size="-1" ><?php echo htmlspecialchars($main_statusx);?></td></tr>
<?php 
print "<tr><td colspan=2 align=\"center\">&nbsp;</td></tr>";
?>
<tr><th colspan=5 align="center"><b>CURRENT STAGE </b></th></tr>

<?php
if($main_statusx =='Pending' OR $main_status=='P')
{

$sql2="select * from $schemas.case_allocation_temp  where filing_no=?";
$sql2= $db->prepare($sql2);
$sql2->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql2->execute();
while ($row3 = $sql2-> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{ 
$listing_date=htmlentities(htmlspecialchars($row3['listing_date']));
$be_name=htmlentities(htmlspecialchars($row3['court_no']));
$pur_code=htmlentities(htmlspecialchars($row3['purpose']));
$bench_nature=htmlentities(htmlspecialchars($row3['bench_nature']));
 $bench_no_dis=htmlentities(htmlspecialchars($row3['bench_no']));
 $list_date_case=htmlentities(htmlspecialchars($row3['listing_date']));

$sql="select purpose_name from $schemas.master_purpose where purpose_code=?";
$sth = $db->prepare($sql);
$sth->bindParam(1, $pur_code, PDO::PARAM_INT);
$sth->execute();
$pur_name = $sth->fetchColumn();

$sql="select court_no from $schemas.bench where court_no=? and bench_nature=?";
$sth = $db->prepare($sql);
$sth->bindParam(1, $be_name, PDO::PARAM_INT);
$sth->bindParam(2, $bench_nature, PDO::PARAM_INT);
$sth->execute();
$court_no = htmlentities(htmlspecialchars($sth->fetchColumn()));

$sql="select bench_name from $schemas.bench_nature where  bench_code=?";
$sth = $db->prepare($sql);
$sth->bindParam(1, $bench_nature, PDO::PARAM_INT);
$sth->execute();
$bench_name = htmlentities(htmlspecialchars($sth->fetchColumn()));

$date122 = explode("-" , $list_date_case);
$next_list_date = $date122[2]."-".$date122[1]."-".$date122[0];
$next_list_date_display = $date122[2]."/".$date122[1]."/".$date122[0];
}
?>




<tr><td width="30%" ><b>Bench Nature</b></td>
<td width="70%" ><?php echo htmlentities(htmlspecialchars($bench_name));?> </td></tr>
<tr><td width="30%" ><b>Court</b></td>

<td width="70%" ><?php echo htmlentities(htmlspecialchars($be_name));?></td></tr>
<tr><td width="30%" ><b>Bench No</b></td>

<td width="70%" ><?php echo htmlentities(htmlspecialchars($bench_no_dis));?></td></tr>

<?php
}

if($main_status=='D' OR $main_statusx=='Disposed')
{
		
$dis="select * from $schemas.case_disposal where filing_no =?";
$dis= $db->prepare($dis);
$dis->bindParam(1, $filing_no, PDO::PARAM_STR);
$dis->execute();
while ($row = $dis-> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{
   $date_of_disposal =$row['disposal_date'];
   $disposal_nature  =$row['disposal_nature'];

$date1 = explode("-" , $date_of_disposal);
$date_of_disposal = $date1[2]."-".$date1[1]."-".$date1[0];
$date_of_disposal_display = $date1[2]."/".$date1[1]."/".$date1[0];

}

$nat ="select action_type from $schemas.master_action where action_code =?"; 
$sth = $db->prepare($nat);
$sth->bindParam(1, $disposal_nature, PDO::PARAM_STR);
$sth->execute();
$disposal_nature =htmlentities(htmlspecialchars($sth->fetchColumn()));
?>
<tr><td width="30%">Date of Disposal </td>
<td width="70%"><blink><?php echo htmlspecialchars($date_of_disposal_display);?></blink></td></tr>		
<tr><td width="30%" ">Disposal Nature </td>
<td width="70%" ><?php echo htmlspecialchars($disposal_nature);?></td></tr>
<?php
}
else
{
if($next_list_date=='11-11-1111') { $next_list_date='-';}
?>
<tr><td width="30%" ><b>Listing Date </b></td>
<td width="70%" ><blink><?php echo htmlspecialchars($next_list_date_display);?></blink></td></tr>		
<tr><td width="30%" ><b>Listing Purpose </b></td>
<td width="70%" ><?php echo htmlentities(htmlspecialchars($pur_name));?></td></tr>

<?php
}
?>
<!-- <table width="90%" border="0" cellpadding="0" cellspacing="2"  align="center">   

<tr>
<th colspan="5" align="center"><b>SUBJECT CATEGORY</b></th>	
</tr>
<tr>
<td>Subj Code</td>
<td align="center">Subject</td>
<td>Subject Category</td>
<td>Subject Sub Category</td>
<td>Subject Sub Sub Category</td>
</tr>
<?php




/*$i=0;
$section_rule=array();
if($filing_no!="")
{

$sql="select a.sub_cat_name,a.sub_cat_code from $schemas.cat_master a left join $schemas.case_category b on
a.sub_cat_code=b.subject   where b.filing_no=?
";
$sql= $db->prepare($sql);
$sql->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql->execute();
while ($row1 = $sql-> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{
$cat_name=$row1['sub_cat_name'];
//$sub_name=$row1['sub_cat_name'];
$cat_code=$row1['sub_cat_code'];
}
}
$count=$i;
*/
?>
<tr>
<td><?php echo htmlentities(htmlspecialchars($cat_code));?></td>
<td><?php echo htmlentities(htmlspecialchars($cat_name));?></td>
<td><?php echo htmlentities(htmlspecialchars('-'));?></td>
<td><?php echo htmlentities(htmlspecialchars('-'));?></td>
<td><?php echo '-';?></td>
</tr>
</table>-->

<!--table width="90%" border="0" cellpadding="1" cellspacing="2" class="" align="center">
<tr>
<th colspan="3" align="center" ><b>RC DETAIL</b></th>	
</tr>
</table-->
<table height="25%" width="90%" border="0" cellpadding="1" cellspacing="2" class="" align="center">
<tr>
<th colspan="3" align="center" ><b>PETITIONER DETAIL</b></th>	
</tr>


<?php
if($filing_no!="")
{

$st = "select * from $schemas.case_detail where filing_no =?";
$sql= $db->prepare($st);
$sql->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql->execute();
while ($row = $sql-> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{
	
$pet_name = htmlentities(htmlspecialchars($row['pet_name']));
$filing_date = htmlentities(htmlspecialchars($row['dt_of_filing']));
list($year,$month,$day)=explode('-',$filing_date);
$dt_filing=$day.'/'.$month.'/'.$year;  	
$pet_name = htmlentities(htmlspecialchars($row['pet_name']));
$pet_adv_name = htmlentities(htmlspecialchars($row['pet_adv_name']));	
$pet_adv = htmlentities(htmlspecialchars($row['pet_adv']));
$res_name = htmlentities(htmlspecialchars($row['res_name']));
global $pet_type;
global $pet_case_filed;

if (isset($_POST['pet_representative'])) 
{ 
$pet_representative=htmlentities(htmlspecialchars($row['pet_representative']));    
}

if (isset($_POST['user_id'])) 
{ 
$user_id=htmlentities(htmlspecialchars($row['user_id']));  
}
if (isset($_POST['pet_case_filed'])) 
{ 
$pet_case_filed=htmlentities(htmlspecialchars($row['pet_case_filed']));
}
if (isset($_POST['pet_flag'])) 
{ 
$pet_flag=htmlentities(htmlspecialchars($row['pet_flag']));
}
if (isset($_POST['res_flag'])) 
{ 
$res_flag=htmlentities(htmlspecialchars($row['res_flag']));
}



$advo=htmlentities(htmlspecialchars($row['pet_adv_name']));
$res=htmlentities(htmlspecialchars($row['res_adv_name']));
//$pet_org_type = htmlentities(htmlspecialchars($row['pet_org_type']));
//$pet_type = htmlspecialchars($row['pet_type']);
//$res_type = htmlspecialchars($row['res_type']);
$pet_address=$row['pet_address'];
$res_address=$row['res_address'];


if($pet_type =='4')
{
if($pet_org_type > 0)
{

$stp = $db->prepare("select org_name from $schemas.master_fi where org_code=?");
$stp->bindParam(1, $pet_org_type, PDO::PARAM_STR);
$stp->execute();
$pet_org_name = $stp->fetchColumn();

}
}



if($pet_type == '1' and $pet_org_type =='0')
{
$stpp = $db->prepare("select bank_name from master_bank where ifsc_code=?");
$stpp->bindParam(1, $pet_name, PDO::PARAM_STR);
$stpp->execute();
$pet_org_name = $stpp->fetchColumn();

}

if($pet_case_filed=='2')
{ 
$pet_adv_name=$pet_name;
}
if($pet_case_filed=='3')
{ $pet_adv_name=$pet_name;}
if ($pet_case_filed=='1' and $pet_adv=='0')
{ $pet_adv_name=$advo;}
if ($pet_case_filed=='4')
{ $pet_adv_name=$pet_representative;}
if($pet_case_filed=='1' and $advo=='')
{
$sql3="select adv_name from $schemas.master_advocate where adv_code=?";
$sth1 = $db->prepare($sql3);
	$sth1->bindParam(1, $pet_adv, PDO::PARAM_INT);
$sth1->execute();
$pet_adv_name = htmlentities(htmlspecialchars(ucwords(strtoupper($sth1->fetchColumn()))));

}
?>

<tr><td colspan="3">
<b>Petitioner Name </b>&nbsp;&nbsp;-<?php 
if($pet_type != '2' and $pet_type != '3')
{
	echo htmlspecialchars(htmlentities(strtoupper($pet_name)));
}

if($pet_name !='' and ($pet_type == '2' OR $pet_type == '3'))
{
echo htmlspecialchars(htmlentities(strtoupper($pet_name)));
}
?>
</br>
<!--Additional Party(Pet.):&nbsp;
<?php 
$partyf='P';
$st = "select name from $schemas.additional_party where filing_no = ? and party_flag=?" ;
$sql= $db->prepare($st);
$sql->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql->bindParam(2, $partyf, PDO::PARAM_STR);
$sql->execute();
while ($row = $sql-> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))

{
$extra_pretetioner_name=$row['name'];

echo htmlspecialchars(strtoupper($extra_pretetioner_name)).',';
}
?><br>
pet. Address:-->
<?php
echo htmlentities(htmlspecialchars(htmlspecialchars(strtoupper($pet_address))));
?>
<br>

<b>Pet. Advocate Name:</b>
<?php
if($pet_adv > 0)
{
$sth =$dbonline->prepare("select rep_name from e_master_advocate where id=?");
$sth->bindParam(1, $pet_adv, PDO::PARAM_STR);
$sth->execute();
$pet_adv_name = $sth->fetchColumn();
}
echo htmlentities(htmlspecialchars(htmlspecialchars(strtoupper($pet_adv_name))));
?>
<!--/br>Additional Advocate(Pet.):&nbsp;-->
<?php 
/*$patyf='P';

$sql13 = "select distinct(adv_code) from $schemas.additional_advocate where
filing_no=? and party_flag=?";
$sql= $db->prepare($sql13);
$sql->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql->bindParam(2, $partyf, PDO::PARAM_STR);
$sql->execute();
while ($row = $sql -> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{  
$adv_code1=$row['adv_code'];
if($adv_code1 > 0)
{
$sth =$db->prepare("select adv_name from $schemas.master_advocate where adv_code=?");
$sth->bindParam(1, $adv_code1, PDO::PARAM_STR);
$sth->execute();
$adv_name = $sth->fetchColumn();
}

echo htmlspecialchars(strtoupper($adv_name)).','; 

}*/
}
}


?>
</td>
</tr>
<tr>
<th colspan="3" align="center"><b>RESPONDENTS DETAIL</b></th>	
</tr>

<?php


if($filing_no!="")
{

$st = "select * from $schemas.case_detail where filing_no =?" ;
$sth1=$db->prepare($st);
$sth1->bindParam(1, $filing_no, PDO::PARAM_STR);
$sth1->execute();

while ($row = $sth1-> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{                
$res_name = htmlentities(htmlspecialchars($row['res_name']));

//$res_org_type = htmlentities(htmlspecialchars($row['res_org_type']));
$res_adv = htmlentities(htmlspecialchars($row['res_adv']));
$res_address = htmlentities(htmlspecialchars($row['res_address']));
$pet_type = htmlspecialchars($row['pet_type']);
$res_type = htmlspecialchars($row['res_type']);
//$res_address=strtoupper(htmlentities(htmlspecialchars($res_address)));



?>

<tr><td colspan="3">
<b>Respondent Name </b>&nbsp;&nbsp;-<?php 
if($res_type != '2' and $res_type != '3')
{
	echo htmlspecialchars(htmlentities(strtoupper($res_name)));
}

if($res_name !='' and ($res_type == '2' OR $res_type == '3'))
{
echo htmlspecialchars(htmlentities(strtoupper($res_name)));
}

?>
</br>
<!--Additional Party(Res.):&nbsp;
<?php 
$partyf='R';
$st = "select name from $schemas.additional_party where filing_no = ? and party_flag=?" ;
$sql= $db->prepare($st);
$sql->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql->bindParam(2, $partyf, PDO::PARAM_STR);
$sql->execute();
while ($row = $sql-> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))

{
$extra_pretetioner_name=$row['name'];

echo htmlspecialchars(strtoupper($extra_pretetioner_name)).',';
}
?><br>
Res. Address:-->
<?php
echo htmlentities(htmlspecialchars(htmlspecialchars(strtoupper($res_address))));
?>
<?php 

if($res_adv>0)
{
$sth =$dbonline->prepare("select rep_name from e_master_advocate where id=?");
$sth->bindParam(1, $res_adv, PDO::PARAM_STR);
$sth->execute();
$res_adv_name = $sth->fetchColumn();
}
?>


</br>
<b>Respondent Advocate </b>&nbsp;-<?php echo htmlspecialchars(strtoupper($res_adv_name));
?>
<!--/br>Additional Advocate(Res.):&nbsp;-->
<?php 
$patyf1='R';

$sql13 = "select distinct(adv_code) from $schemas.additional_advocate where
filing_no=? and party_flag=?";
$sql= $db->prepare($sql13);
$sql->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql->bindParam(2, $patyf1, PDO::PARAM_STR);
$sql->execute();
while ($row = $sql -> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{  
$adv_code11=$row['adv_code'];
if($adv_code11 > 0)
{
$sth =$db->prepare("select adv_name from $schemas.master_advocate where adv_code=?");
$sth->bindParam(1, $adv_code11, PDO::PARAM_STR);
$sth->execute();
$adv_name11 = $sth->fetchColumn();
}

echo htmlspecialchars(strtoupper($adv_name11)).','; 

}
}
}


?>
</td>
</tr>

</table>
<!-------------orders------------>
<!--table-- width="90%" align="center" >
    <tr>
        <th colspan="3" align="center"><b>ORDERS</b></th>
    </tr>
    <tr>
        <td  align="center"><b>Order Date</b></td>
        <td  align="center"><b>File Name</b></td>
        <td  align="center"><b>&nbsp;</b></td>
    </tr>

    <?php
    /*$getorders = $db->prepare("select * from $schemas.order_daily where filing_no = ? order by order_date DESC");
    $getorders->execute(array($filing_no));
    $getorders = $getorders->fetchAll();
    foreach($getorders as $order){
        list($yy,$mm,$dd) = explode("-",$order[order_date]);
        $order_date = $dd.'/'.$mm.'/'.$yy;
    ?>
    <tr>
<td colspan=""><?php echo $order_date;  ?></td>
        <td colspan=""><?php echo $order[order_tribunal];  ?></td>
<td colspan=""><font><a href="./uploads/orders/<?php echo $order[order_tribunal];?>" onclick="return hs.htmlExpand(this, { objectType: 'iframe' } )">
<img src="./images/document.png" width="45"><br></a>

</td>
    </tr>
    <?php }*/ ?>
</table-->
<!------------------------->

<table height="20%" width="90%" border="0" cellpadding="1" cellspacing="2" class="" align="center">
<tr>
<th colspan="8" align="center"><b>CASE PROCEEDING DETAILS</b></th>	
</tr>
<tr><td><b>Bench No </b></td>  	

<td><b>Court No</b></td>

<td><b>Hearing Date</b></td> 

<td><b>Purpose</b></td>

<td><b>Next Listing Date</b></td>

<td><b>Next Listing Purpose</b></td>

<td><b>Status</b></td>

<td><b>Order</b></td>
</tr>
<?php

if($filing_no!="")
{
	//echo $filing_no;
$sql_all="select * from $schemas.case_proceeding where filing_no=?";
$sqall=$db->prepare($sql_all);
$sqall->bindParam(1, $filing_no, PDO::PARAM_STR);
$sqall->execute();

}
else 
{ 
die('Filing No Not Found');
/*
$sql_all="select * from $schemas.case_proceeding_his where filing_no=?";
$sqall=$db->prepare($sql_all);
$sqall->bindParam(1, $filing_no, PDO::PARAM_STR);
$sqall->execute();
*/
} 


while ($rowall = $sqall-> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{
$date_l=$rowall['listing_date'];
$todays_status=$rowall['todays_status'];
$todays_action=$rowall['todays_action'];
$bench_no=$rowall['bench_no'];
$purposecode=$rowall['purpose'];

if (isset($_POST['order_passed'])) 
{ 
$order_passed=htmlentities(htmlspecialchars($row['order_passed']));
}



$court_no=$rowall['court_no'];
$nxtlistdate = $rowall['next_list_date'];
$nxtlistpurp = $rowall['next_list_purpose'];


/*if($todays_action !='')
{
$sql_all1234="select action_type from $schemas.master_action where action_code=?";
$sql_all1234=$db->prepare($sql_all1234);
$sql_all1234->bindParam(1, $todays_action, PDO::PARAM_STR);
$sql_all1234->execute();
$action_type = $sql_all1234->fetchColumn();

}
*/
if($purposecode !='')
{
$sql_all12="select purpose_name from $schemas.master_purpose where purpose_code=?";
$sql_all=$db->prepare($sql_all12);
$sql_all->bindParam(1, $purposecode, PDO::PARAM_INT);
$sql_all->execute();
$purpose_name = $sql_all->fetchColumn();

}
if($nxtlistpurp !='')
{
$sql_all12="select purpose_name from $schemas.master_purpose where purpose_code=?";
$sql_all=$db->prepare($sql_all12);
$sql_all->bindParam(1, $nxtlistpurp, PDO::PARAM_INT);
$sql_all->execute();
$nxtlistpurp = $sql_all->fetchColumn();

}
if($date_l!='')
{
list($year,$month,$day)=explode('-',$date_l);
$date_l=$day.'/'.$month.'/'.$year;
}
if($nxtlistdate!='')
{
list($year,$month,$day)=explode('-',$nxtlistdate);
$nxtlistdate=$day.'/'.$month.'/'.$year;
}
?>
<tr>
<td><?php echo htmlentities(htmlspecialchars($bench_no));?></td>

<td><?php echo htmlentities(htmlspecialchars($court_no));?></td>

<td><?php echo htmlentities(htmlspecialchars($date_l));?></td>


<td><?php 
echo htmlspecialchars($purpose_name);
?></td>
<?php ?>

<td><?php echo htmlentities(htmlspecialchars($nxtlistdate));?></td>
<td><?php echo htmlentities(htmlspecialchars($nxtlistpurp));?></td>
<td><?php echo htmlspecialchars($todays_status);?></td>
<td><?php echo htmlspecialchars($action_type)." ".htmlspecialchars(strtoupper($order_passed));?></td>
</tr>
<?php } ?>
</table>
<table height="20%" width="90%" border="0" cellpadding="1" cellspacing="2" class="" align="center">
<tr>
<th colspan="8" align="center"><b>CASE STAGE DETAILS</b></th>	
</tr>

<?php
$sql_scrutiny="select level_level from $schemas.scrutiny where filing_no=?";
$sql_scrutiny=$db->prepare($sql_scrutiny);
$sql_scrutiny->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql_scrutiny->execute();
$level = $sql_scrutiny->fetchColumn();
$level = htmlentities(htmlspecialchars($level));


$sql_defects="select defects from $schemas.scrutiny where filing_no=?";
$sql_defects=$db->prepare($sql_defects);
$sql_defects->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql_defects->execute();
$sc_defects = $sql_defects->fetchColumn();
 $sc_defects = htmlentities(htmlspecialchars($sc_defects));

$sql_case_det="select case_no from $schemas.case_detail where filing_no=?";
$sql_case_det=$db->prepare($sql_case_det);
$sql_case_det->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql_case_det->execute();
$case_nos = $sql_case_det->fetchColumn();
$case_nos = htmlentities(htmlspecialchars($case_nos));

$sql_case_all_temp="select filing_no from $schemas.case_allocation_temp where filing_no=?";
$sql_case_all_temp=$db->prepare($sql_case_all_temp);
$sql_case_all_temp->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql_case_all_temp->execute();
$filing_no_cat = $sql_case_all_temp->fetchColumn();
$filing_no_cat = htmlentities(htmlspecialchars($filing_no_cat));

if($level == '0'){
	$level_name = 'Case is in Scrutiny Clerk Login';
}else if($level == null && $filing_no_cat != null){
    $level_name = 'Case is listed';
	$scode = '(S->N, CAT->Y)';
}else if($level != null && $filing_no_cat != null){
    $level_name = 'Case is listed';
}else if($level == '1'){
        $level_name = 'Case is in Assistant Registrar Login';
}else if($level == '2' && $filing_no_cat != null && ($case_nos == null || $case_nos == '')) {
	$level_name = 'Generate Manual Case No for this Case/Case is list by Registrar';
}else if($level == '2' && $filing_no_cat == null && $legal_aid == 'A' && ($case_nos == null || $case_nos == '')){
	$level_name = 'Generate Manual Case No for this Case';
}else if(($case_nos != null || $case_nos != '') && $filing_no_cat == null){
	$level_name = 'Case is not listed from Fresh Case Listing';
}else if($level == '2' && ($legal_aid == '' || $legal_aid == null) && ($case_nos == null || $case_nos == '') && $sc_defects='N'){
	$level_name = 'Generate Manual Case No for this Case/Case in Unallocated fresh cases';
}

else if($level == '2' && ($legal_aid == '' || $legal_aid == null) && ($case_nos == null || $case_nos == '') && $sc_defects='Y'){
	$level_name = 'CASE is in Scrutiny Clerk';
}
?>

<tr><td width="30%" ><b>Case Stage</b></td>
<td width="70%" ><font color="red"><?php echo $level_name;?> </td></tr>
<span id="scode" style="display:none"><?php echo $scode;  ?></span>
<?php
}
?>
</table>

<!------------------------------------------------------------------------------------------------------------>

<?php
if($filing_no != "" && $scrtok == '1')
{
	
	$sql_defects="select defects from $schemas.scrutiny where filing_no=?";
$sql_defects=$db->prepare($sql_defects);
$sql_defects->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql_defects->execute();
$sc_defects = $sql_defects->fetchColumn();
 $sc_defects = htmlentities(htmlspecialchars($sc_defects));
	
	
    $gdfecd=$db->prepare("select * from $schemas.case_detail where filing_no=?");
		$gdfecd->bindParam(1, $filing_no, PDO::PARAM_STR);
		$gdfecd->execute();
		
		while ($rgdfecd = $gdfecd->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
            $dt_of_filing=$rgdfecd['dt_of_filing']; 
			$case_no=$rgdfecd['case_no'];
            list($yy,$mm,$dd) = explode("-",$dt_of_filing);
            $dt_of_filing = $dd.'/'.$mm.'/'.$yy;
            //$CASE_NOc='Not Registered';
            $main_statusx='';
        }
		 
		if($case_no=='' && $dt_of_filing!='' && $sc_defects=='N')
		{
			$main_statusx='Scrutiny Passed ';
		}
		
		if($case_no=='' && $dt_of_filing=='' && $sc_defects=='N')
		{
			$main_statusx='Scrutiny Passed ';
		}
		if($case_no=='' && $dt_of_filing=='' && $sc_defects=='Y')
		{
			$main_statusx='Under Scrutiny  ';
		}
?>
<table height="40%" width="90%" border="0" cellpadding="0" cellspacing="2"  align="center">   

<tr><th colspan=5 align="center"><b>CASE STATUS </b></th></tr>
<tr><td width="30%"><b>Diary no/Year</b></td>

<td width="70%"><?php echo htmlspecialchars($filing_no) ;?></td></tr>

<tr><td width="30%"><b>Case Type/Case No/Year</b></td> 
<td width="70%"><?php echo  htmlspecialchars($CASE_NOc); ?></td></tr>
	
<tr><td width="30%"><b>Date of Filing</b></td><td width="70%"><?php echo  htmlspecialchars($dt_of_filing); ?></td></tr>
<tr><td width="30%"><b>Case Status</b></td><td width="70%"><?php echo htmlspecialchars($main_statusx);?></td></tr>
</table>
<table height="20%" width="90%" border="0" cellpadding="1" cellspacing="2" class="" align="center">
<tr>
<th colspan="8" align="center"><b>CASE STAGE DETAILS</b></th>	
</tr>

<?php
$sql_scrutiny="select level_level from $schemas.scrutiny where filing_no=?";
$sql_scrutiny=$db->prepare($sql_scrutiny);
$sql_scrutiny->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql_scrutiny->execute();
$level = $sql_scrutiny->fetchColumn();
$level = htmlentities(htmlspecialchars($level));

$sql_defects="select defects from $schemas.scrutiny where filing_no=?";
$sql_defects=$db->prepare($sql_defects);
$sql_defects->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql_defects->execute();
$sc_defects = $sql_defects->fetchColumn();
 $sc_defects = htmlentities(htmlspecialchars($sc_defects));

$sql_case_det="select case_no from $schemas.case_detail where filing_no=?";
$sql_case_det=$db->prepare($sql_case_det);
$sql_case_det->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql_case_det->execute();
$case_nos = $sql_case_det->fetchColumn();
$case_nos = htmlentities(htmlspecialchars($case_nos));

$sql_case_det2="select legal_aid from $schemas.case_detail where filing_no=?";
$sql_case_det2=$db->prepare($sql_case_det2);
$sql_case_det2->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql_case_det2->execute();
$legal_aid = $sql_case_det2->fetchColumn();
$legal_aid = htmlentities(htmlspecialchars($legal_aid));


$sql_case_all_temp="select filing_no from $schemas.case_allocation_temp where filing_no=?";
$sql_case_all_temp=$db->prepare($sql_case_all_temp);
$sql_case_all_temp->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql_case_all_temp->execute();
$filing_no_cat = $sql_case_all_temp->fetchColumn();
$filing_no_cat = htmlentities(htmlspecialchars($filing_no_cat));

if($level == '0'){
	
	$level_name = 'Case is in Scrutiny Clerk Login';
}else if($level == null && $filing_no_cat != null){
    $level_name = 'Case is listed';
	$scode = '(S->N, CAT->Y)';
}else if($level != null && $filing_no_cat != null){
    $level_name = 'Case is listed';
}else if($level == '1'){
        $level_name = 'Case is in Assistant Registrar Login';
}else if($level == '2' && $filing_no_cat != null && ($case_nos == null || $case_nos == '')) {
	$level_name = 'Generate Manual Case No for this Case/Case is list by Registrar';
}else if($level == '2' && $filing_no_cat == null && $legal_aid == 'A' && ($case_nos == null || $case_nos == '')){
	$level_name = 'Generate Manual Case No for this Case';
}else if(($case_nos != null || $case_nos != '') && $filing_no_cat == null){
	$level_name = 'Case is not listed from Fresh Case Listing';
}else if($level == '2' && ($legal_aid == '' || $legal_aid == null ) && ($case_nos == null || $case_nos == '') && $sc_defects=='N'){
	$level_name = 'Generate Manual Case No for this Case/Case in Unallocated fresh cases';

}
 
else if($level == '2' && ($legal_aid == '' || $legal_aid == null  ) && ($case_nos == null || $case_nos == '') && $sc_defects=='Y'){
	$level_name = 'CASE IS IN SCRUTINY CLERK';
}


?>

<tr><td width="30%" ><b>Case Stage</b></td>
<td width="70%" ><font color="red"><?php echo $level_name;?> </td></tr>
<span id="scode" style="display:none"><?php echo $scode;  ?></span>
<?php
}
?>
</table>



</div>
</form>
</div>
<script language="javascript">

    hs.graphicsDir = './includes/highslide/graphics/';
    hs.outlineType = 'rounded-white';
    hs.wrapperClassName = 'draggable-header';


</script>

</body></html>