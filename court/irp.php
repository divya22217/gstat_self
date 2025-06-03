<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include("../db_inc1.php");
session_start();
$judge =htmlentities($_REQUEST['judge']);
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
	header("Location: ../login.php");
	die();
}
setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);

if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";
	$link_scrutiny_idaccess='1';
?>
<?php 
include '../inheader.php';
include '../insidebar.php';
include '../db_inc2.php';
?>

<style>

.multipleInput-container {
     border:1px #ccc solid;
     padding:1px;
     padding-bottom:0;
     cursor:text;
     font-size:13px;
     width:100%;
     height: 75px;
     overflow: auto;
     background-color: white;
   border-radius:3px;
}
 
.multipleInput-container input {
     font-size:13px;
     width:150px;
     height:24px;
     border:0;
     margin-bottom:1px;
     outline: none
}
 
.multipleInput-container ul {
     list-style-type:none;
     padding-left: 0px !important;
}
 
li.multipleInput-email {
     float:left;
     margin-right:2px;
     margin-bottom:1px;
     border:1px #BBD8FB solid;
     padding:2px;
     background:#F3F7FD;
}
 
.multipleInput-close {
     width:16px;
     height:16px;
     background:url(close.png);
     display:block;
     float:right;
     margin:0 3px;
}
.email_search{
  width: 100% !important;
}
</style>

<script language="javascript">
function submitForm2()
{
 	with(document.frm)
	{

 		
		if(case_type.value == "select")
		{
			alert("Please select Case Type");
			case_type.focus();
			return false;
		}
		if(case_no.value=="")
		{
			
			alert("Please Enter Case No.");
			case_no.focus();
			return false;
		}
		if(isNaN(case_no.value) == true)
		{
			alert("Please enter  numeric Case No.");
			case_no.select();
			return false;
		}
		if(case_year.value=="")
		{
			alert("Please Enter Case Year");
			case_year.focus();
			return false;
		}
		if(isNaN(case_year.value) == true)
		{
			alert("Please enter  numeric Case Year");
			case_year.select();
			return false;
		}
		if(case_year.value.length!=4)
		{
			alert("Please Enter 4 digit case year");
			case_year.select();
			return false;
		}
		
		action = "irp.php";
		submit();
	}
}
function submitForm()
{
 	with(document.frm)
	{
		
		var fup = document.getElementById('userfile');
        	var fileName = fup.value;
        	var ext = fileName.substring(fileName.lastIndexOf('.') + 1);

    		if(ext =="pdf" || ext=="Pdf" || ext=="PDF")
    		{
      	 		 return true;
    		}
    		else
    		{
       	 		alert("Upload PDF only");
        		return false;
   	 	}
		action = "irp.php";
		submit();
	}
}

function validateForm() {
  var x = document.forms["frm"]["email_irp"].value;
  var atpos = x.indexOf("@");
  var dotpos = x.lastIndexOf(".");
  if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
    alert("You have entered an invalid email address!");
    return false;
  }
}

function validateForm1() {
	if(cc_email.value!='')
	{
  //var x = document.forms["frm"]["cc_email"].value;
  
  var x = document.forms["frm"]["cc_email"].value;
var emails = x.split(",");
emails.forEach(function (email) {
  validate(email.trim());
});
  
  var atpos = x.indexOf("@");
  var dotpos = x.lastIndexOf(".");
  if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
    alert("You have entered an invalid email address!");
    return false;
  }
	}
}
var x = getEmails();
var emails = x.split(",");
emails.forEach(function (email) {
  validate(email.trim());
});

function validate()
{
 	with(document.frm)
	{

	if(name.value=='')
       		{
    	   		alert("Please Enter Name");
    	   		name.focus();
			return false;
       		}	
			if(role.value=='')
       		{
    	   		alert("Please Select Role");
    	   		role.focus();
			return false;
       		}	
			if(email_irp.value=='')
       		{
    	   		alert("Please Enter Email");
    	   		email_irp.focus();
			return false;
       		}	
			
			
			if(mobile.value=='')
       		{
    	   		alert("Please Enter Mobile");
    	   		mobile.focus();
			return false;
       		}	
			 if(isNaN(mobile.value) == true)
		{
			alert("Please Enter  Numeric Mobile Number");
			mobile.select();
			return false;
		}  
		if(mobile.value.length!=10)
{
	alert("Please Enter  10 Digit Mobile Number");
			mobile.select();
			return false;
}
		if(dt_of_appointment.value=='')
{
	alert("Please Select Date of appointment");
			dt_of_appointment.select();
			return false;
}		
var email_value = '';
$(".appended_email").each(function() {
	email_value += $(this).val()+",";
});
var newStr = email_value.substring(0, email_value.length - 1);
$("#cc_email").val(newStr);

    	
		action = "irp_action.php";
		submit();
	}
}



function submitForm1()
{
 	with(document.frm)
	{

     if(diary_no.value=='')
       		{
    	   		alert("Please Enter Diary Number");
    	   		diary_no.focus();
			return false;
       		}
        if(isNaN(diary_no.value) == true)
		{
			alert("Please Enter  numeric Diary Number");
			diary_no.select();
			return false;
		}  

if(diary_no.value.length!=16)
{
	alert("Please Enter  16 Digit Diary Number");
			diary_no.select();
			return false;
}	

	action = "irp.php";
		submit();
	}
}

function submitype()
{
 	with(document.frm)
	{
		action = "irp.php";
		submit();
	}
}
</script>

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



<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Sparkline -->
<script src="../bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap  -->
<script src="../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS -->
<!-- <script src="../bower_components/Chart.js/Chart.js"></script> -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->

<!-- AdminLTE for demo purposes -->


<!-- Select2 -->
<script src="../bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="../plugins/input-mask/jquery.inputmask.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="../bower_components/moment/min/moment.min.js"></script>
<script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap color picker -->
<script src="../bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->

<!-- AdminLTE App -->



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
</head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <title>IRP</title>
  <div class="content-wrapper">
      <section class="content-header">
      <h1>
        <center>IRP/RP
        </center>
      </h1>
  
    </section>
 <p> <h4><center>All <font color="red">*</font></span> is mandatory Field </center></h4></p>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">
  
<form name="frm" method="post" action="irp_action.php" enctype="multipart/form-data" >

<?php
$msg =htmlentities($_REQUEST['msg']);
if($msg !='')
{
?>
<tr>
<td colspan="6"><center>
<font color='red' size='2'> <?php echo $msg;?></font> 
</td>
</center>
</tr>
<?php
}
?>
<?php
$bench_type =htmlentities($_REQUEST['bench_type']);
$case_type =$_REQUEST['case_type'];
$msg =$_REQUEST['msg'];
 $case_no =$_REQUEST['case_no'];
$case_year =$_REQUEST['case_year'];
 $sql="select * from case_type where status='t' order by case_type_desc_cis ASC";
?>
<tr>
 
<td   align="left" colspan="6">
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

</font>
	<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Case Type
<select name="case_type" style="width:200px">
<option>select</option>
<?php

foreach($dbh->query($sql) as $row)
{

  $casetypecode=$row['id'];
 if($case_type == $casetypecode)
                {
		print "<option value=".htmlentities(htmlspecialchars($row['id']))." selected>".strtoupper(htmlentities(htmlspecialchars($row['case_type_desc_cis'])))."</option>";
                }
           else
                {
                print "<option value=".htmlentities(htmlspecialchars($row['id'])).">".strtoupper(htmlentities(htmlspecialchars($row['case_type_desc_cis'])))."</option>";
		}
 }
 ?>`

</select><font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Case No
		</font>	<input type="text"  maxlength="7" size="8" name="case_no" value="<?php print htmlentities(htmlspecialchars($case_no)); ?>" >
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Year
<input type="text"  maxlength="4" size="4" name="case_year" value="<?php print htmlentities(htmlspecialchars($case_year)); ?>" >
	<input type="button" name="button" value="Go" size="20" onClick="return submitForm2();">
</td>


</tr>



<input type="hidden" name="frm" value="<?php echo htmlspecialchars($frm); ?>" />
<?php

$sql2=$db->prepare("select * from $schemas.case_detail where location_code=? and case_type=? and case_no=? and case_year=? ");

$sql2->bindParam(1, $bench_type, PDO::PARAM_STR);
$sql2->bindParam(2, $case_type, PDO::PARAM_STR);
$sql2->bindParam(3, $case_no, PDO::PARAM_STR);
$sql2->bindParam(4, $case_year, PDO::PARAM_STR);

$sql2->execute();
while ($row1 = $sql2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
  $filing_no =$row1['filing_no'];
   $pet_name =$row1['pet_name'];
   $res_name =$row1['res_name'];
   $party_name=$pet_name." Vs ".$res_name;
  
  

}

$sql2=$db->prepare("select * from  $schemas.irp_detail where filing_no=? ");

$sql2->bindParam(1, $filing_no, PDO::PARAM_STR);

$sql2->execute();
	
while ($row2 = $sql2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $filing_no333 =$row2['filing_no'];
}
?>
<?php 
if($filing_no333!='')
{
	?>
<tr>
		<td height="30" align="center" cellpadding="0" colspan="6">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
		<span class="error"> <b> <?php  echo "RECORD ALREADY EXISTS"; ?></span></font>
		</td>
		</tr>
<?php 
}

?>

<?php
$IRP='I';
$sql1=$dbonline->prepare("select * from  e_cases_party where filing_no=? and party_flag=?");

$sql1->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql1->bindParam(2, $IRP, PDO::PARAM_STR);
$sql1->execute();
	
while ($row1 = $sql1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $name =$row1['name'];
   $party_code =$row1['party_code'];
    $filing_no22 =$row1['filing_no'];
}


	

if($filing_no=='')
{

	?>
	<tr>
		<td height="30" align="center" cellpadding="0" colspan="6">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
		<span class="error"> <b> <?php  echo "RECORD NOT FOUND"; ?></span></font>
		</td>
		</tr>
		
		<?php 
	
	

}

?>
<tr>
		<td height="30" align="center" cellpadding="0" colspan="6">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
		<span class="error"> <b> <?php  echo strtoupper($party_name) ; ?></span></font>
		</td>
		</tr>
		<?php 
if($filing_no22!='')
{
	
	
$level_level=2;
$objection_status='N';
$sql21=$db->prepare("select * from  $schemas.scrutiny where filing_no=? and level_level=? and objection_status=?");

$sql21->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql21->bindParam(2, $level_level, PDO::PARAM_STR);
$sql21->bindParam(3, $objection_status, PDO::PARAM_STR);

$sql21->execute();
	
while ($row21 = $sql21->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
    $filing_no1 =$row21['filing_no'];
	$compliance_date =$row21['compliance_date'];
}
if($filing_no1=='' || $filing_no1==0)
{
	?>
	<tr>
		<td height="30" align="center" cellpadding="0" colspan="6">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
		<span class="error"> <b> <?php  echo "SCRUTINY NOT DONE"; ?></span></font>
		</td>
		</tr>
	<?php 
	
}

}

$lcode ="select short_name from $schemas.bench_location where bench_location_code ='$bench_type'";
$lcode=$db->prepare($lcode);
$lcode->execute();
$lcodename = $lcode->fetchColumn();
if($case_type > 0)
{
	
$stQ = $db->prepare("select short_name from case_type where id = ?");
$stQ->bindParam(1, $case_type, PDO::PARAM_STR);
$stQ->execute();
 $case_type_short_name=$stQ->fetchColumn();
}

 $case_no1 = htmlspecialchars(strtoupper($case_type_short_name).'/'.$case_no2.'('.$lcodename.')'.$case_year);
?>
</table>
<div align="center">
<tr align="center">
<td>
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Select type IRP:</font>
</td>
<?php
 $typeorder= isset($_REQUEST['typeorder']) ? $_REQUEST['typeorder'] :'';
?>

<td>
 <select name="typeorder" class="" style='width:150px;' onChange="javascript:submitype();">
<option value="">-select-</option>
<option value="type1" <?php if($typeorder == 'type1'){ ?> selected <?php } ?>>From E-filling IRP name</option>
<option value="type2" <?php if($typeorder == 'type2'){ ?> selected <?php } ?>>From Cis IRP name</option>
</select>
</td>
</tr>
</div>
 
 <?php 
 if($typeorder == 'type1'){
 ?>

 <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">
 
<tr>
<td>
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Name:</font>
</td>
<td>
<input type="text" id="name" maxlength="250" autocomplete="off" size="50" name="name" readonly value="<?php echo $name; ?>"/>
</td> 
 </tr>
 <tr>
 <td>
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Role:
</td>
<td>
<select name="role">
<?php
$st = $db->prepare("select * from $schemas.master_role_irp where display = 'TRUE'  and code IN(1) order by role_name asc");
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$ctc=htmlspecialchars($row['code']);
if($role == $ctc)
{
print "<option value=".htmlspecialchars($row['code'])." selected>".htmlspecialchars($row['role_name'])."</option>";
}
else
{
print "<option value=".htmlspecialchars($row['code']).">".htmlspecialchars($row['role_name'])."</option>";
}
}
?>
</select>
</td>
</tr>

<tr>
<td>
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Email Id:</font>
</td>
<td>
<input type="text" id="email_irp" maxlength="100" autocomplete="off" size="50" name="email_irp"   value="<?php echo $email_irp; ?>"  onblur="validateForm();"/>
</td> 
 </tr>
 <tr>
<td>
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Mobile Number:</font>
</td>
<td>
<input type="text" id="mobile" maxlength="15" autocomplete="off" size="50" name="mobile" value="<?php echo $mobile; ?>"/>
</td> 
 </tr>
 <tr>
<td>
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Enrolment Number:</font>
</td>
<td>
<input type="text" id="enrol_no" maxlength="50" autocomplete="off" size="50" name="enrol_no" value="<?php echo $party_code; ?>"/>
</td> 
 </tr>
  <tr>
<td>
<font face="Verdana" size="2">Case Number:</font>
</td>
<td>
<input type="text" id="case_no1" maxlength="15" autocomplete="off" size="15" radonly name="case_no1" value="<?php echo $case_no1; ?>"/>
</td> 
 </tr>

<tr>
<td  align="left"><font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Date of Appointment</font></td>
<td align="left" ><input type="text"  maxlength="10" size="10" name="dt_of_appointment" id="dt_of_appointment" class="datepickerToday" value="<?php print htmlspecialchars($dt_of_appointment); ?>" </td>
</tr>

 <tr>
<td><font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Copy of Order (upload *pdf) </font>
</td>
<td>
<input type="file" name="userfile" id="userfile">
</td></tr>

<tr>
<td>

</td>
</tr>
</table>
 <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">



 <?php 

 //if($filing_no!='' and $filing_no333=='' and $filing_no1!='')
	 if($filing_no!='' and $filing_no333=='' )
 {
	 ?>
<input type="hidden" name="filing_no" value="<?php print htmlentities(htmlspecialchars($filing_no)); ?>">
 <tr>
 <td colspan="3" align="center">
<input type="submit" name="submit1" value="Submit" class="button" onClick="return validate();">     
</td>
<?php
 }
 ?>
     </form>    
   </table>

 <?php }else if($typeorder == 'type2'){ ?>

 <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">
 
<tr>
<td>
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Name:</font>
</td>
<td>
<input type="text" id="name" maxlength="250" autocomplete="off" size="50" name="name" value="<?php echo $name; ?>"/>
</td> 
 </tr>
 <tr>
 <td>
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Role:
</td>
<td>
<select name="role">
<?php
$st = $db->prepare("select * from $schemas.master_role_irp where display = 'TRUE'  and code IN(1) order by role_name asc");
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$ctc=htmlspecialchars($row['code']);
if($role == $ctc)
{
print "<option value=".htmlspecialchars($row['code'])." selected>".htmlspecialchars($row['role_name'])."</option>";
}
else
{
print "<option value=".htmlspecialchars($row['code']).">".htmlspecialchars($row['role_name'])."</option>";
}
}
?>
</select>
</td>
</tr>

<tr>
<td>
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Email Id:</font>
</td>
<td>
<input type="text" id="email_irp" maxlength="100" autocomplete="off" size="50" name="email_irp" value="<?php echo $email_irp; ?>" onblur="validateForm();" />
</td> 
 </tr>
 
 
 <tr>
<td>
 <font face="Verdana" size="2"> &nbsp; CC Email Id(ROC and Court Officer):</font>
</td>
<td>
<input type="text" id="ccr_email" maxlength="500" autocomplete="off" size="50" name="ccr_email" value="<?php echo $ccr_email; ?>"  onblur="validateEmail();"/>
</td> 
 </tr>
 <tr>
<td>
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Mobile Number:</font>
</td>
<td>
<input type="text" id="mobile" maxlength="15" autocomplete="off" size="50" name="mobile" value="<?php echo $mobile; ?>"/>
</td> 
 </tr>
 <tr>
<td>
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Enrolment Number:</font>
</td>
<td>
<input type="text" id="enrol_no" maxlength="50" autocomplete="off" size="50" name="enrol_no" value="<?php echo $party_code; ?>"/>
</td> 
 </tr>
  <tr>
<td>
<font face="Verdana" size="2">Case Number:</font>
</td>
<td>
<input type="text" id="case_no1" maxlength="15" autocomplete="off" size="15" name="case_no1" value="<?php echo $case_no1; ?>"/>
</td> 
 </tr>

<tr>
<td  align="left"><font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Date of Appointment</font></td>
<td align="left" ><input type="text"  maxlength="10" size="10" name="dt_of_appointment" id="dt_of_appointment" class="datepickerToday" value="<?php print htmlspecialchars($dt_of_appointment); ?>" </td>
</tr>

 <tr>
<td><font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Copy of Order (upload *pdf) </font>
</td>
<td>
<input type="file" name="userfile" id="userfile">
</td></tr>

<tr>
<td>

</td>
</tr>
</table>
<?php 

 //if($filing_no!='' and $filing_no333=='' and $filing_no1!='')
	 if($filing_no!='' and $filing_no333=='' )
 {
	 ?>
 <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">
 <input type="hidden" name="cc_email" id="cc_email" value="">
<input type="hidden" name="filing_no" value="<?php print htmlentities(htmlspecialchars($filing_no)); ?>">
 <td colspan="3" align="center">
<button type="button" name="submit1" class="button" onClick="return validate();">Submit</button> 
</td>
<?php 
 }
 ?>

     </form>    
   </table>
   <table style="display:none">
	<tr id="email_storage"></tr>
   </table>
 <?php } } ?>
 
 <script>
    function validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}
(function( $ ){
     $.fn.multipleInput = function() {
          return this.each(function() {
               // list of email addresses as unordered list
               $list = $('<ul/>');
               // input
			   var input_event = $('<input type="email" name="email" id="email_search" class="email_search multiemail"/>');
               var $input = input_event.keyup(function(event) { 
                    if(event.which == 13 || event.which == 32 || event.which == 188) {                        
                         if(event.which==188){
                           var val = $(this).val().slice(0, -1);// remove space/comma from value
                         }
                         else{
                         var val = $(this).val(); // key press is space or comma                        
                         }                         
                         if(validateEmail(val)){
							 var splitemail = val.split('@');
						 var splitemail_id = splitemail[0];
                         // append to list of emails with remove button
                         $list.append($('<li class="multipleInput-email"><span>' + val + '</span></li>')
                              .append($('<a href="#" class="multipleInput-close" title="Remove"><i class="glyphicon glyphicon-remove-sign"></i></a>')
                                   .click(function(e) {
                                        $(this).parent().remove();
										$("#"+splitemail_id).remove();
                                        e.preventDefault();
                                   })
                              )
							  
                         );
		
						 $("#email_storage").append('<td id="'+splitemail_id+'"><input type="email" name="cc_new_email[]" id="cc_email_search" value="'+val+'" class="appended_email email_search multiemail"/></td>')
                         $(this).attr('placeholder', '');
                         // empty input
                         $(this).val('');
						 
                          }
                          else{
                            alert('Please enter valid email id, Thanks!');
                          }
                    }
               });
			   
			    var $input = input_event.blur(function(event) { 
						var val = $(this).val(); // key press is space or comma                        
                                                
                         if(validateEmail(val)){
							 var splitemail = val.split('@');
						 var splitemail_id = splitemail[0];
                         // append to list of emails with remove button
                         $list.append($('<li class="multipleInput-email"><span>' + val + '</span></li>')
                              .append($('<a href="#" class="multipleInput-close" title="Remove"><i class="glyphicon glyphicon-remove-sign"></i></a>')
                                   .click(function(e) {
                                        $(this).parent().remove();
										$("#"+splitemail_id).remove();
                                        e.preventDefault();
                                   })
                              )
							  
                         );
		
						 $("#email_storage").append('<td id="'+splitemail_id+'"><input type="email" name="cc_new_email[]" id="cc_email_search" value="'+val+'" class="appended_email email_search multiemail"/></td>')
                         $(this).attr('placeholder', '');
                         // empty input
                         $(this).val('');
						 $(this).css("color","black");
						 $(this).focus(); 
						 
                          }else{
							  if($(this).val() == ''){
		
								return false;
							  }
	
							 $(this).focus(); 
							  $(this).val('');
							 alert('Please enter valid email id, Thanks!');
						  }
               }); 
               // container div
               var $container = $('<div class="multipleInput-container" />').click(function() {
                    $input.focus();
               });
               // insert elements into DOM
               $container.append($list).append($input).insertAfter($(this));
               return $(this).hide();
          });
     };
})( jQuery );
$('#ccr_email').multipleInput();

/* $(document).on('focusout','#email_search',function(){
	alert("sdfds");
	$('#ccr_email').multipleInput();
}); */
</script>