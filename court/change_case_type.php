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

<script language="javascript">

function submitForm()
{
 	with(document.frm)
	{
		
		
		
	if(diary_no.value == "")
{
alert("Please Enter Diary No");
diary_no.focus();
return false;
}
if(diary_no.value.length!=16)
{
	alert("Please Enter  16 Digit Diary Number");
			diary_no.select();
			return false;
}		
	
		

	
		action = "change_case_type.php";
		submit();
	}
}




function submitForm1()
{
	
	if(diary_no.value == "")
{
alert("Please Enter Diary No");
diary_no.focus();
return false;
}	
if(diary_no.value.length!=16)
{
	alert("Please Enter  16 Digit Diary Number");
			diary_no.select();
			return false;
}	
if(case_type.value == "")
{
alert("Please Select Case Type");
case_type.focus();
return false;
}		


	
	var answer = confirm('Are you sure you want to commit the changes?')
	if(answer){
 	with(document.frm)
	{   		 					

	action = "change_case_type.php";
		submit();
	}
}else{return;}
}


function validate()
{
with(document.frm)
{
if(diary_no.value == "")
{
alert("Please Enter Diary No");
diary_no.focus();
return false;
}	
if(diary_no.value.length!=16)
{
	alert("Please Enter  16 Digit Diary Number");
			diary_no.select();
			return false;
}

if(case_type.value == "")
{
alert("Please Select Case Type");
case_type.focus();
return false;
}		



action="change_case_type_action.php";
submit();
document.frm.submit1.disabled = true;
document.frm.submit1.value = 'Please Wait...';
return true;
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
<script src="../dist/js/pages/dashboard2.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>


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
    <title>EDIT CASE TYPE BEFORE CASE NO GENERATION</title>
  <div class="content-wrapper">
      <section class="content-header">
      <h1>
        <center>EDIT CASE TYPE BEFORE CASE NO GENERATION
        </center>
      </h1>
  
    </section>
 <p> <h4><center>All <font color="red">*</font></span> is mandatory Field </center></h4></p>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">
  
<form name="frm" method="post" action="" >

<?php
//print_r($_REQUEST);

$diary_no = htmlentities($_REQUEST['diary_no']);


?>

<tr><td><font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Search by Diary No
		</font>	<input type="text"  maxlength="16" size="20" name="diary_no" value="<?php print $diary_no; ?>" >
	<input type="button" name="button" value="Go" size="20" onClick="return submitForm();">
</td>
</tr>
<?php
$msg =$_REQUEST['msg'];
if($msg !='')
{
?>
<tr>
<td colspan="6"><center>
<font style='font-weight:bold' color='red' size='4'><?php echo $msg;?></font> 
</td>
</center>
</tr>
<?php
}
?>
<input type="hidden" name="frm" value="<?php echo htmlspecialchars($frm); ?>" />
<?php


$sql2=$db->prepare("select * from e_case_detail_local where filing_no=? ");

$sql2->bindParam(1, $diary_no, PDO::PARAM_STR);

$sql2->execute();
while ($row1 = $sql2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $filing_no1 =$row1['filing_no'];
   $old_case_type =$row1['case_type'];
   $pet_name =htmlspecialchars($row1['pet_name']);
   $res_name =htmlspecialchars($row1['res_name']);
    $party_name=$pet_name." Vs ".$res_name;
   
}

$sql21=$db->prepare("select * from $schemas.case_detail where filing_no=? ");

$sql21->bindParam(1, $diary_no, PDO::PARAM_STR);

$sql21->execute();
while ($row2 = $sql21->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
    $filing_no112 =$row2['filing_no'];
   
   
}



?>

<input type="hidden" name="filing_no" value="<?php print htmlentities(htmlspecialchars($filing_no1)); ?>">


<?php

if($filing_no112!='' && $diary_no!='')
{
	

	?>
	<tr>
		<td height="30" align="center" cellpadding="0" colspan="6">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
		<span class="error"> <b> <?php  echo "CASE NO GENERATED HAVE NO PERMISSION TO EDIT CASE TYPE"; ?></span></font>
		</td>
		</tr>
		
		<?php 
	exit();
	

}
?>

<?php

if($filing_no1=='' && $diary_no!='')
{

	?>
	<tr>
		<td height="30" align="center" cellpadding="0" colspan="6">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
		<span class="error"> <b> <?php  echo "RECORD NOT FOUND"; ?></span></font>
		</td>
		</tr>
		
		<?php 
	exit();
	

}
?>
<tr>
		<td height="30" align="center" cellpadding="0" colspan="6">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
		<span class="error"> <b> <?php  echo strtoupper($party_name) ; ?></span></font>
		</td>
		</tr>
		<tr>
		<td>
	<font face="Verdana" size="2">Old Case Type:
		
<select name="old_case_type">
<option value=''>Select</option>
<?php
$st = $db->prepare("select * from case_type where display = 'TRUE'  and id='$old_case_type' order by case_type_desc asc");
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$oldctc=htmlspecialchars($row['id']);
if($old_case_type == $oldctc)
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
		</td>
		</tr>
		
		
		
		
		<tr>
		<td>
		<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">New Case Type:
		
<select name="case_type">
<option value=''>Select</option>
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
		</td>
		</tr>
		

</table>

       <table align="center"  border="0" width="40%" > 
					
						<form name="frm" method="post" action="change_case_type_action.php" >
			<?php
			if($filing_no1!='')
			{
			?>
					
						<tr>  <td><br>
                                    <input id="submitcmod" type="submit"  name="submitcmod" value="Submit" onClick="return validate();"
                                            /></td></tr>
											<?php
			}
			?>
						

     </form>    
   </table>
     <?php 
  
 }
 
