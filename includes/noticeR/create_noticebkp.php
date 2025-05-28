<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");
include("../db_inc1.php");
 
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
echo "you Can't access this page";
}
else {

$curYear = date('Y');
$curMonth = date('m');
$curDay = date('d');
$cur_date = "$curDay/$curMonth/$curYear";
$curdate="$curYear-$curMonth-$curDay";

$wday = mktime(0,0,0,date("$curMonth"),date("d")-5,date("Y"));
$wday1= date("Y-m-d");


$schemas=htmlspecialchars($_SESSION['schema_name']);
$frm = md5( uniqid('auth', true) );


$_SESSION['form_token'] = $frm;



?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>NCLT | Dashboard</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
<!-- jvectormap -->
<link rel="stylesheet" href="../bower_components/jvectormap/jquery-jvectormap.css">
<!-- Theme style -->
<link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins
folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">



<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="../plugins/iCheck/all.css">
<!-- Bootstrap Color Picker -->
<link rel="stylesheet" href="../bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
<!-- Select2 -->
<link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">

<!--[endif]-->
<style>
    input[type=text] {
        padding: 1px;
        margin: 8px 0;
        box-sizing: border-box;
    }
    select{padding: 1px;
    margin: 8px 0;
    box-sizing: border-box; }
    /*[type=text]{padding: 5px;        margin-bottom: 5px;}*/
</style>

<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>
<script language="javascript">
function submitForm1()
{
with(document.frm)
{
if(prt_type.value=='1')
{
if(case_type.value == "select")
{
alert("Select Case Type");
case_type.focus();
return false;
}
if(case_no.value == "")
{
alert("Enter Case Number");
case_no.value='';
case_no.focus();
return false;
}

if(case_year.value == "")
{
alert("Enter 4 digit Case Registration Year");
case_year.focus();
return false;
}

}
if(prt_type =='2')
{
if(dairyNo.value=='')
{
alert("Please Enter Dairy No... ");
dairyNo.focus();
return false;
}
if(dairyyear.value=='')
{
alert("Please Enter Case Year... ");
dairyyear.focus();
return false;
}
}

action="create_notice.php";
submit();
}

}
function submitForm()
{
with(document.frm)
{

action = "create_notice.php";
submit();
}
}
function run()
{
with(document.frm)
{


action = "create_notice.php";
submit();
}
}
function validate()
{
with(document.frm)
{

if(case_type.value == "select")
{
alert("Select Case Type");
case_type.focus();
return false;
}
if(case_no.value == "")
{
alert("Enter  Case Number");
case_no.focus();
return false;
}
if(isNaN(case_no.value) == true)
{
alert("Please Enter Numeric Case No.");
case_no.select();
return false;
}
if(case_year.value == "")
{
alert("Enter 4 digit Case Registration Year");
case_year.focus();
return false;
}
if(case_year.value < 4)
{
alert("Enter 4 digit Case Registration Year");
case_year.focus();
return false;
}
if(isNaN(case_year.value) == true)
{
alert("Please Enter Numeric Case Year");
case_year.select();
return false;
}


if(pt_name1.value == "")
{
alert("INVALID CASE");
pt_name1.focus();
return false;
}
if(notice_type.value == "")
{
alert("Please Select Notice Type");
notice_type.focus();
return false;
}
if(notice_type.value == "")
{
alert("Please Select Notice Type");
notice_type.focus();
return false;
}
if(n_date.value == "")
{
alert("Please Enter Date");
n_date.focus();
return false;
}
var rgx = /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/;

  if(n_date.value !="")
               	{        
               		if(!n_date.value.match(rgx))
			{
               			alert("Please Enter Valid Date ");
				
               			n_date.focus();
               			return false;
               		}

 	       }




action="create_notice_action.php";
submit();
document.frm.submit1.disabled = true;
document.frm.submit1.value = 'Please Wait...';
return true;
}

}
function SetBg(txt)
{
txt.style.backgroundColor='#ffff99';
}
function UnSetBg(txt)
{
txt.style.backgroundColor='white';
}


function popdashboard()
{

var url = "dashboard.php";
var width = 800;
var height = 1300;

var left = (screen.width-width)/2;

var top = (screen.height - height)/2;

var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left+ ',scrollbars=1';

window.open(url,"print",params);
}

function addNumbers(val)
{
      var c=document.getElementById("n_date").value.length;
       if(c==2 || c==5  )
       {
                var newval = val+ "/";
                document.getElementById("n_date").value=newval;
       }

}

function popdashboard1()
{

var url = "dashboard1.php";
var width = 800;
var height = 1300;

var left = (screen.width-width)/2;

var top = (screen.height - height)/2;

var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left+ ',scrollbars=1';

window.open(url,"print",params);
}

 function popsurety_pending_report(cfy)

    {
    	
    		var url = "./notice_view.php?no="+cfy;


    		 window.open(url,"_blank","directories=no, status=no,width=800, height=800, left=100, scrollbars=yes");   
    		
    }

//  END
</script>
<style>
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {padding:0px;}

</style>



</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php include("../includes/banner.php");?>
<div class="wrapper">

<?php include("../includes/header.php");
include '../sidebar.php';
?>

<div class="content-wrapper" style="min-height: 946px;">
<!-- Content Header (Page header) -->
<section class="content">

<table  border="2" class="table"  align="center" >
<tr>
<td valign="top" align="right" colspan="16"><center>
<b><font face="Verdana" size="3"><u>FRESH NOTICE </u></font> </b>
</td>
</tr>
<tr><td valign="top" align="center" colspan="16">
<font face="Verdana" size="2">Fields marked with a <font color='red'>*</font> are compulsory.</font> </td>
</tr>



<form name="frm" method="post" action="create_notice_action.php">


<?php

$msghash=$_REQUEST['msghash'];
if($msghash !='')
{
$msghashz=(base64_decode($msghash));

$msghashz = explode("@", $msghashz);

$msg1 = $msghashz[0];
$case_number_dis = $msghashz[1];
$filing_no_dis =   $msghashz[2];
$notice_date_dis = $msghashz[3];
$notice_type_dis = $msghashz[4];
$prt_type_dis =    $msghashz[5];

if($msghashz != '')
{
?>
<tr>
<td height="30" align="center" cellpadding="0" colspan="16">
<?php
$no1=$filing_no_dis.'@'.$notice_date_dis.'@'.$notice_type_dis;
?>
  <a href="javascript:popsurety_pending_report('<?php echo htmlspecialchars($no1);?>');" ><b><font color = 'red'  ><?php echo $msg1?> <br>Click Here for view <?php echo $case_number_dis ?></font></a></u>
<!--	       
<a href="notice_view.php?filing_no=<?php echo $filing_no_dis ?>&notice_date=<?php echo $notice_date_dis;?>&notice_id=<?php echo $notice_type_dis;?>" 
onclick="MM_openBrWindow('notice_view.php?filing_no=<?php echo $filing_no_dis?>&notice_date=<?echo $notice_date_dis?>&notice_id=<?echo $notice_type_dis ?>','','width=650,height=450,scrollbars=Yes');return true " target="_blank"><b><font color = 'red'  > <?php echo $msg1?> <br>Click Here for view <?php echo $case_number_dis ?></a>  </b>

-->
</td>
</tr>
<?php
}
}
$case_type=$_REQUEST['case_type'];
$case_year=$_REQUEST['case_year'];
?>
<tr>
<td  colspan="16" align="left">

<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Search Case:
<?php $prt_type = isset($_REQUEST['prt_type']) ? $_REQUEST['prt_type'] :'1'; ?>

<select id="prt_type" name="prt_type" onchange="javascript:submitForm();" class="frm-field required" >
<option value="1" <?php if($prt_type == 1) { print " selected"; } ?> >Case No. Wise</option>
<option value="2" <?php if($prt_type == 2) { print " selected"; } ?> >Diary No. Wise</option>
</select>

</td>
</tr>
<tr>
<td>
<?php if($prt_type =='1')
{?>
<?php  $bench_type = isset($_REQUEST['bench_type']) ? $_REQUEST['bench_type'] :'';?>
<?php  $case_type = isset($_REQUEST['case_type']) ? $_REQUEST['case_type'] :'';?>
<?php  $case_no = isset($_REQUEST['case_no']) ? $_REQUEST['case_no'] :'';?>
<?php  $case_year = isset($_REQUEST['case_year']) ? $_REQUEST['case_year'] :'';?>



<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Bench:
<select name="bench_type">

<?php
$st = $db->prepare("select * from $schemas.bench_location where display = 'TRUE' order by bench_location_name asc");
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$ctc=htmlspecialchars($row['bench_location_code']);
if($bench_type == $ctc)
{
print "<option value=".htmlspecialchars($row['bench_location_code'])." selected>".htmlspecialchars($row['bench_location_name'])."</option>";
}
else
{
print "<option value=".htmlspecialchars($row['bench_location_code']).">".htmlspecialchars($row['bench_location_name'])."</option>";
}
}

?>

</select>
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Case Type:

<select name="case_type">

<?php
$st = $db->prepare("select * from case_type where display = 'TRUE' order by case_type_desc asc");
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$ctc=htmlspecialchars($row['id']);
if($case_type == $ctc)
{
print "<option value=".htmlspecialchars($row['id'])." selected>".htmlspecialchars($row['case_type_desc'])."</option>";
}
else
{
print "<option value=".htmlspecialchars($row['id']).">".htmlspecialchars($row['case_type_desc'])."</option>";
}
}

?>

</select>

<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Case No:</font>

<input type="text" id="case_no" maxlength="7" autocomplete="off" size="6" name="case_no" value="<?php print htmlspecialchars(ltrim($case_no,0)); ?>"/>&nbsp;&nbsp;
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">


Case Year:</font>

<input type="text" id="case_year" maxlength="4" autocomplete="off" size="4" name="case_year" value="<?php if($case_year!=''){print htmlspecialchars($case_year);}else{ print htmlspecialchars($curYear);} ?>" />
<?php } ?>

<?php
if($prt_type =='2')
{
?>

<?php  $dairyNo = isset($_REQUEST['dairyNo']) ? $_REQUEST['dairyNo'] :'';?>
<?php  $dairyyear = isset($_REQUEST['dairyyear']) ? $_REQUEST['dairyyear'] :'';?>

<font face="Verdana" size="2"><span class="error">*</span>Dairy No:</font>
<input onkeypress="return isNumberKey(event)"  autocomplete="off" maxlength="16" name="dairyNo" value="<?php echo htmlspecialchars(htmlentities($dairyNo));?>"  placeholder="Dairy No." type="text" required="required">
<font face="Verdana" size="2"><span class="error">*</span>Dairy Year:</font>
<input onkeypress="return isNumberKey(event)" autocomplete="off" maxlength="4" name="dairyyear" value="<?php echo htmlspecialchars(htmlentities($dairyyear));?>"  placeholder="Dairy Year" type="text" required="required" size="4"-->

<?php } ?>



<input type="button"  size=5 name="go" id="gobtn" value="Go" onClick="javascript:submitForm1();"> </td>
</tr>
<tr><td align="left" valign="top" colspan="1" width="40%" >

<?php

//CODE CHANGE 

if($prt_type =='1')
{

$st = $db->prepare("select filing_no from $schemas.case_detail where location_code=? and case_type=? and case_no=? and case_year=?");
$parm = array($bench_type,$case_type,$case_no,$case_year);
//print_r($parm);
//$st->bindParam(1, $c_no, PDO::PARAM_STR);
}
if($prt_type == '2')
{
//$fil_no = $hsc.str_pad($dairyNo, 6,'0',STR_PAD_LEFT).$dairyyear;
$fil_no = $dairyNo;

//$display_accept='P';
$st = $db->prepare("select filing_no from $schemas.case_detail where filing_no=?");
//$st->bindParam(1, $fil_no, PDO::PARAM_STR);
$parm = array($fil_no);
//$st->bindParam(2, $display_accept, PDO::PARAM_STR);

}

$st->execute($parm);
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{

$filing_no = htmlspecialchars($row['filing_no']);
}
if(strlen($c_no)==16 and $filing_no =='')
{
echo "<h1><font color='red'><center>RECORD NOT FOUND CONTACT TO ADMIN....</center></font></h1>";

}

if($filing_no != '')
{
$st = $db->prepare("select * from $schemas.case_detail where filing_no=?");

$st->bindParam(1, $filing_no, PDO::PARAM_STR);
$st->execute();

?>


<input type="hidden" name="filing_no" value="<?php print htmlspecialchars($filing_no);?>" />
<?php

while ($rw2 = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{

$filing_no=htmlspecialchars($rw2['filing_no']);
$pt_name=htmlspecialchars($rw2['pet_name']);
$rs_name=htmlspecialchars($rw2['res_name']);
$ci_cri=htmlspecialchars($rw2['ci_cri']);
$status=htmlspecialchars($rw2['status']);
$pet_code=htmlspecialchars($rw2['pet_org_type']);
$res_code=htmlspecialchars($rw2['res_org_type']);
$res_type=htmlspecialchars($rw2['res_type']);
$pet_type=htmlspecialchars($rw2['pet_type']);
$res_adv=htmlspecialchars($rw2['res_adv']);
$pet_adv=htmlspecialchars($rw2['pet_adv']);

}
}
?>
<tr>
<td align="center">
<font color='red'>
<?php
if($pt_name ==''){echo htmlspecialchars(ucwords(strtoupper($pt_name)));}else{echo htmlspecialchars(ucwords(strtoupper($pt_name)));}
echo "</font><br><font color='blue'>VS</font><br><font color='red'>";
if($rs_name ==''){echo htmlspecialchars(ucwords(strtoupper($rs_name)));}else{echo htmlspecialchars(ucwords(strtoupper($rs_name)));}



?></td>	
</tr>
<tr>
<td>

<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Notice Type:

<?php $notice_type = isset($_REQUEST['notice_type']) ; ?>
<select id="notice_type" name="notice_type" onchange="javascript:submitForm();">
<option value="">Select</option>
<?php


$st = $db->prepare("select * from $schemas.master_notice where display ='TRUE' order by notice_title asc");
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$not_id=htmlspecialchars($row['notice_id']);
if($notice_type == $not_id)
{
print "<option value=".htmlspecialchars($row['notice_id'])." selected>".htmlspecialchars($row['notice_title'])."</option>";
}
else
{
print "<option value=".htmlspecialchars($row['notice_id']).">".htmlspecialchars($row['notice_title'])."</option>";
}
}

?>

</select>


<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Date</font>

<?php  $n_date = isset($_REQUEST['n_date']) ? $_REQUEST['n_date'] :'';?>
<input type="text" id="n_date"  autocomplete="off" name="n_date" 
  size="10" maxlength="10" value="<?php print htmlspecialchars($n_date); onKeyup="javascript:addNumbers(this.value)" onFocus="SetBg(this)" ?>"  />
</td>
</tr>
<?php 
if($notice_type==1)
{
?>
<tr>
<td>
<font face="Verdana" size="2">Name of Party Filling the Admission</font>
<center>
<?php  $filing_party = isset($_REQUEST['filing_party']) ? $_REQUEST['filing_party'] :''; ?>
<textarea rows="2"   cols="55" name="filing_party"><?php print htmlspecialchars($filing_party);?></textarea></center>
</td>
</tr>
<tr>
<td>
<font face="Verdana" size="2">(Insert the relief or order sought)</font>
<br>
<center>
<?php  $ins_relief = isset($_REQUEST['ins_relief']) ? $_REQUEST['ins_relief'] :''; ?>
<textarea rows="2"   cols="55" name="ins_relief"><?php print htmlspecialchars($ins_relief);?></textarea></center>
</td>
</tr>

<tr>
<td>
<font face="Verdana" size="2">Insert the section of the Act, or the Rules/ Regulation , that provides for the order or relief sought</font>
<br>
<center>
<?php  $adm_rules = isset($_REQUEST['adm_rules']) ? $_REQUEST['adm_rules'] :''; ?>
<textarea rows="2"   cols="55" name="adm_rules"><?php print htmlspecialchars($adm_rules);?></textarea></center>
</td>
</tr>
<tr>
<td>
<font face="Verdana" size="2">For the following reasons(Insert a concise statement of the circumstances , and the particulars of the request)</font>
<br>
<center>
<?php $reasons = isset($_REQUEST['reasons']) ? $_REQUEST['reasons'] :''; ?>
<textarea rows="2"   cols="55" name="reasons"><?php print htmlspecialchars($reasons);?></textarea></center>
</td>
</tr>
<tr>
<td>
<font face="Verdana" size="2">Name and Title of person signing on behalf of Applicant:</font>
<br>
<center>
<?php $behalf_applicant = isset($_REQUEST['behalf_applicant']) ? $_REQUEST['behalf_applicant'] :''; ?>
<textarea rows="2"   cols="55" name="behalf_applicant"><?php print htmlspecialchars($behalf_applicant);?></textarea></center>
</td>
</tr>
<tr>
<td>
<font face="Verdana" size="2">Authorised Signature and Address:</font>
<br>
<center>
<?php $sign_address = isset($_REQUEST['sign_address']) ? $_REQUEST['sign_address'] :''; ?>
<textarea rows="2"   cols="55" name="sign_address"><?php print htmlspecialchars($sign_address);?></textarea></center>
</td>
</tr>
<center>
<tr>
<td>
<font face="Verdana" size="2">Tel:</font>
<?php $tel_no = isset($_REQUEST['tel_no']) ? $_REQUEST['tel_no'] :''; ?>
<input type="text" id="tel_no" maxlength="15" autocomplete="off" size="30" name="tel_no" value="<?php print htmlspecialchars($tel_no); ?>"/>



<font face="Verdana" size="2">Fax:</font>
<?php $fax = isset($_REQUEST['fax']) ? $_REQUEST['fax'] :''; ?>
<input type="text" id="fax" maxlength="20" autocomplete="off" size="30" name="fax" value="<?php print htmlspecialchars($fax); ?>"/>


<font face="Verdana" size="2">email:</font>
<?php $email = isset($_REQUEST['email']) ? $_REQUEST['email'] :''; ?>
<input type="text" id="email" maxlength="50" autocomplete="off" size="50" name="email" value="<?php print htmlspecialchars($email); ?>"/>
</center>
</td>
</tr>
<tr>
<td>
<font face="Verdana" size="2">This form is prescribed under Rule</font>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php $under_rule = isset($_REQUEST['under_rule']) ? $_REQUEST['under_rule'] :''; ?>
<input type="text" id="under_rule"  autocomplete="off" size="60" name="under_rule" value="<?php print htmlspecialchars($under_rule); ?>"/>

</td>
</tr>

<tr>
<td>

For rehabilitation/For Transferred  

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php   $tr_reh = isset($_REQUEST['tr_reh']) ? $_REQUEST['tr_reh'] :'';?>

<select name="tr_reh" onchange="javascript:submitForm();">
<option value="">Select</option>
<?php
$st = $db->prepare("select * from $schemas.master_notice_order_type where display = 'Y' order by order_type_name asc");
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$order_type_id=htmlspecialchars($row['order_type_id']);
if($tr_reh == $order_type_id)
{
print "<option value=".htmlspecialchars($row['order_type_id'])." selected>".htmlspecialchars($row['order_type_name'])."</option>";
}
else
{
print "<option value=".htmlspecialchars($row['order_type_id']).">".htmlspecialchars($row['order_type_name'])."</option>";
}
}

?>

</select>
</td>
</tr>

<?php
if($tr_reh==1)
{
?>
<tr>
<td>
<font face="Verdana" size="2">Rehab. Petition No</font>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="text" id="reh_pet_no"  autocomplete="off" size="60" name="reh_pet_no" value="<?php print htmlspecialchars($reh_pet_no); ?>"/>

<?php
}
?>
</td>
</tr>
<tr>
<td>
<?php
if($tr_reh==2)
{
?>
<font face="Verdana" size="2">Transfer Petition (CLB/ BIFR/ AIFR/HHC) No</font>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="text" id="tr_pet_no"  autocomplete="off" size="60" name="tr_pet_no" value="<?php print htmlspecialchars($tr_pet_no); ?>"/>
<?php
}
?>

</td>
</tr>
<tr>
<td>
<font face="Verdana" size="2">Matters from the 
</font>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="text" id="matter_from"  autocomplete="off" size="60" name="matter_from" value="<?php print htmlspecialchars($matter_from); ?>"/>
</td>
</tr>
<tr>
<td>
<font face="Verdana" size="2">For Other matter 
</font>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="text" id="other_matter"  autocomplete="off" size="60" name="other_matter" value="<?php print htmlspecialchars($other_matter); ?>"/>
</td>
</tr>
<?php
}
?>
<input type="hidden" name="pt_name1" value="<?php print htmlspecialchars($pt_name);?>" />
<?php
if($pt_name!='')
{
?>
<tr>
<td colspan="8" align="left" valign="top"><div align="center">
<input type="submit" name="submit1" value="Submit" class="button btn-primary" onClick="return validate();">

<?php
}
?>
</div></td>
</tr>
<?php

include '../infooter.php';
?>

<?php

}

?>
