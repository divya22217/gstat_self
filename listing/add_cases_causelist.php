<?php
session_start();
ob_start();
include("../db_inc1.php");
date_default_timezone_set("Asia/Kolkata");
$server_date= date('d/m/Y'); //Returns IST 
 $schemas=htmlspecialchars($_SESSION['schema_name']);
include '../inheader.php';
include '../insidebar.php';
extract($_REQUEST);

?>
<head>
<script src="../plugins/jQueryUI/date.js"></script>

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


<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css"/>
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>

<!-- Select2 -->
<script src="../bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="../plugins/input-mask/jquery.inputmask.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="../bower_components/moment/min/moment.min.js"></script>
<!--<script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>-->
<!-- bootstrap datepicker -->
<!--<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>-->
<!-- bootstrap color picker -->
<script src="../bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->

</head>
<style>
table td{font-size: 10pt;}
table th{font-size: 12pt;}
</style>
<script language="javascript">
function submitrefForm()
		{
			with(document.refForm)
			{
				/*if(case_nos.value == "")
				{
					alert("Select Case No....");
					next_list_date.value='';
					next_list_date.focus();
					return false;
				}*/

				action="add_cases_causelist_action.php";
				submit();
				document.refForm.submitrefForm.disabled = true;
				document.refForm.submitrefForm.value = 'Please Wait...';
				return true;
			}

		}
function submitForm()
{
 	with(document.frm)
	{ 
		action = "add_cases_causelist.php";
		submit();
	}
}
function validate()
{
	with(document.frm)
	{
		
		if(next_list_date.value =="")
		{                     
			alert("Please Enter Valid Date of Listing");
			next_list_date.select();
			return false;
		}	
		
		
		var rgx = /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/;

		if(next_list_date.value !="")
		{        
		      if(!next_list_date.value.match(rgx))
			{
			       alert("Please enter valid Hearing Date ");
			       next_list_date.focus();
			       return false;
			}
		}
		
		var checkboxes = document.getElementsByName('bench_no');

		var selected = [];
		for (var i=0; i<checkboxes.length; i++) {
		if (checkboxes[i].checked) {selected.push(checkboxes[i].value);}
		}
		if(selected=="")
		{
		alert("Please choose From bench ");
		return false;
		}
		
		
		
		
		
		
		/*
		var str1 = next_list_date.value;
		var str2 = server_date.value;

		var dt1   = parseInt(str1.substring(0,2),10); 
		var mon1  = parseInt(str1.substring(3,5),10);
		var yr1   = parseInt(str1.substring(6,10),10); 

		var dt2   = parseInt(str2.substring(0,2),10); 
		var mon2  = parseInt(str2.substring(3,5),10); 
		var yr2   = parseInt(str2.substring(6,10),10);

		mon1 = mon1 -1 ;
		mon2 = mon2 -1 ;
		var nextlistdate = new Date(yr1, mon1, dt1); 
		var curdate = new Date(yr2, mon2, dt2); 

		if(nextlistdate<curdate)
		{
			alert("listing  date should be greater than current date ");
			next_list_date.focus();
			return false;
		}
		if(court_no_new.options[court_no_new.selectedIndex].value == "")
		{
			alert("Please Select court number !!!");
			court_no_new.focus();
			return false;
		}
		if(bench_no_new.options[bench_no_new.selectedIndex].value == "")
		{
			alert("Please Select Bench Number");
			bench_no_new.focus();
			return false;
		}
		if(court_no_transfer.options[court_no_transfer.selectedIndex].value == "")
		{
			alert("Please Select Court Number For Transfer");
			court_no_transfer.focus();
			return false;
		}
		if(bench_no_transfer.options[bench_no_transfer.selectedIndex].value == "")
		{
			alert("Please Select Bench Number For Transfer");
			bench_no_transfer.focus();
			return false;
		}*/


	
		
		/*var chks = document.getElementsByName('checkbox[]');
		 
		var hasChecked = false;
		for (var i = 0; i < chks.length; i++)
		{
		        if (chks[i].checked)
		        {
		                hasChecked = true;
		                break;
		        }
		}
		
		
		if (hasChecked == false)
		{
		        alert("Please select at least one case.");
		        return false;
		}
		*/
		
		
		var filing_case = $(".checkbox").is(":checked");
		if (filing_case == false)
		{
		        alert("Please select at least one case.");
		        return false;
		}
	  
		var chks1 = document.getElementsByName('bench_no_new');
		var hasChecked1 = false;
		for (var i = 0; i < chks1.length; i++)
		{
		        if (chks1[i].checked)
		        {
		                hasChecked1 = true;
		                break;
		        }
		}
		if (hasChecked1 == false)
		{
		        alert("Please select at least one bench to transfer.");
		        return false;
		}
		return confirm('Are you sure to Final Todays Cases?');
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

function isNumberKey(evt) {
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	// Added to allow decimal, period, or delete
	if (charCode == 110 || charCode == 190 || charCode == 46) 
		return true;
	
	if (charCode > 31 && (charCode < 48 || charCode > 57)) 
		return false;
	
	return true;
} 

</script>
<body>
<div class="content-wrapper">
<form name="frm" method="post" action="transfer_bench_action.php" onSubmit="return validate();">

<input type="hidden" name="server_date" value="<?php echo $server_date;?>">
<table cellspacing="2" cellpadding="2" border="1" width="95%" class="std" align="center"> 

<tr>
<td valign="top" align="right" colspan="7">
<p align="center"><b><font face="Verdana" size="3"><U>Add Cases to Cause List Module</U></font></b></td>
</tr>
<tr>
	<td valign="top" align="right" colspan="7">
	<p align="center"><font face="Verdana" size="2">Fields marked with a <span class="error">*</span> are compulsory.</font>
	</td>
</tr>

<?php
if($msg != "")
{
	?>
	<tr>
	<td height="30" align="center" cellpadding="0" colspan="6" > <font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red"> 		<span class="error"> 
	<b><?php echo "$msg";?>	</span></font>	</td>    
	</tr>
	<?php
}


$sql_prps = "select * from $schemas.master_purpose";
$sth5 = $dbh->prepare($sql_prps);
$sth5->execute();
$purposeAll = $sth5->fetchAll();
?>
<tr>
	<!--td  align="right" > </td-->
	<td align="left" colspan="7" ><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
	<span class="error">*</span>Date of Listing</font>
	
		<input type="text"  name="next_list_date" id="next_list_date" class="datepickerGreater" size="10" value="<?php echo  $next_list_date; ?>" 
		onKeyup="javascript:addNumbers(this.value)" onFocus="SetBg(this)" onchange="submitForm();UnSetBg(this);">
 
		</td>
</tr>
<?php
list($day,$month,$year)=explode('/',$next_list_date);
$court_date_new=$year.'-'.$month.'-'.$day;
if($court_date_new!='')
{
$sql2=" select * from $schemas.bench where  from_list_date ='$court_date_new' order by court_no asc";
$bench_d=$dbh->prepare($sql2);
$bench_d->execute();

 
if($bench_d->rowCount()>0)
{
	$bench_data=$bench_d->fetchAll();
 ?>


<tr>
<th ><font face="Verdana, Arial, Helvetica, sans-serif" >&nbsp;</font></th>
<th   valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" >Court No.</font></th>

<th colspan="6" align="left"><font face="Verdana, Arial, Helvetica, sans-serif">Hon'ble Justice</font></th>
</tr>
<?php

	$flag=0;
	
	foreach($bench_data as $row2)
	{
		$flag=1;
		$court_no =$row2[court_no]; 
		$bench_code1 = $row2['bench_no'];
		?>
		<tr >
		<td valign="top"  align="center">
		<input type="radio"  name="bench_no"  class="bench_no"onchange="submitForm();" value="<?php echo $bench_code1;?>" <?php if($bench_no ==$bench_code1)echo 'checked';?>>
 		</td>
		<td  valign="top" align="center"><?php  
		$sql2q=" select bench_name from $schemas.bench_nature where  bench_code ='$row2[bench_nature]'";
		$sth11= $dbh->prepare($sql2q);
		$sth11->execute();
		echo $sth11->fetchColumn();?>
		<input type="hidden"  name="bench_nature" value="<?php echo $row2[bench_nature]; ?>">
		<input type="hidden"  name="court_no" value="<?php echo $court_no; ?>">
		<?php if($court_no!=3)echo '<br>Court No : '.$court_no;?>
		</td>

		<td colspan="6" align="left">
		<?php
		/*$sql6=" select court_no from $schemas.bench where from_list_date='$court_date_new' and bench_no='$bench_code1' ";
		$sth = $dbh->prepare($sql6);
		$sth->execute();
		$court_no = $sth->fetchColumn();

		$sql5=" select from_time,to_time from $schemas.bench where  from_list_date <='$court_date_new' and to_list_date >= '$court_date_new' and bench_no='$bench_code1' and court_no='$court_no'";
		foreach($dbh->query($sql5) as $row5)
		{
			$court_stime=$row5['from_time'];
			$court_etime=$row5['to_time'];
		}*/
		$sql="select bj.judge_code  from $schemas.bench_judge as bj,$schemas.master_judge as jm where bj.from_list_date ='$court_date_new' and bj.bench_no='$bench_code1' and jm.judge_code=bj.judge_code ";
		$sth = $dbh->prepare($sql);
		$m=0;
		$arr=[];
		foreach($dbh->query($sql) as $row)
		{
			$arr[$m]=$row['judge_code'];
			$m++;
		}
		$sql="select presiding from $schemas.bench where from_list_date ='$court_date_new'  and bench_no='$bench_code1'";
		$sth = $dbh->prepare($sql);
		$sth->execute();
		$presiding = $sth->fetchColumn();
		$arr1 = sizeof($arr);
		for($i=0;$i<$arr1;$i++)
		{
			$jcode =$arr[$i];
			$sql = "select judge_name from $schemas.master_judge where judge_code =$jcode";
			$sth = $dbh->prepare($sql);
			$sth->execute();
			$judge = $sth->fetchColumn();

			print "<font size='2' ><b>".strtoupper($judge);
			if($jcode == $presiding) print "<font color='red'><b> (PRESIDING JUDGE )</b></font>";
			print"<br>";
		}echo'</td>';
	}
	echo'</tr>';
}
}
	?>
			
	
</table>

<?php
//condition after date is selected
if($next_list_date!=''&& $bench_no!='')
{	
	$list_date_r = $_REQUEST['next_list_date'];
	$bench_no_r = $_REQUEST['bench_no'];
	$bench_nature_r = $_REQUEST['bench_nature'];
	$court_no_r = $_REQUEST['court_no'];
?>
<br>
<div class="content" id="contentid">
<table width="100%" border='0' cellpadding="0" cellspacing="3" align="center">
<form name="searchcase_form" method="post" action="">
<tr><span class="error">*</span>Case Type:&emsp;<select name="case_type">
<option value="">Select Case Type</option>
<?php
$casetypesql="select * from case_type where display='TRUE'";
foreach($db->query($casetypesql) as $rowpurpose)
	{	
	$purpose_name =$rowpurpose['case_type_desc'];
	$purpose_code =$rowpurpose['id'];
    echo "<option value=".$purpose_code.">".$purpose_name."</option>";				 
}
?>
</select>
&emsp;&emsp;
Case No:
<input onkeypress="return isNumberKey(event)" type="text" autocomplete="off" name="case_no" style="width:100px;">

&emsp;&emsp;
Case Year:
<input onkeypress="return isNumberKey(event)" type="text" autocomplete="off" name="case_yr" maxlength="4" style="width:50px;">

&emsp;&emsp;

Location:&emsp;<select name="case_loc">
<option value="">Select Location</option>
<?php
$locationsql="select * from $schemas.bench_location where display='TRUE'";
foreach($db->query($locationsql) as $rowlocationsql)
	{	
	$location_name =$rowlocationsql['short_name'];
	$location_code =$rowlocationsql['bench_location_code'];
    echo "<option value=".$location_code.">".$location_name."</option>";				 
}
?>
</select></tr><br><br>

<tr><div style="text-align: center;">
    <span style="color:red;font-weight:bold;">
        Or
    </span>
</div></tr><br>

<tr><div style="text-align: center;">Filing No:

<input onkeypress="return isNumberKey(event)" type="text" autocomplete="off" name="filing_no_s" maxlength="16"></div></tr>

<br>
<br>
<div style="text-align: center;"><input id="submit1" type="button"  name="submit1" value="GO"  onClick="return submitForm();" /></div>
<br>
</tr>
</form>
<br>
</table>
</div>
<?php
$filling_no_selected = isset($_REQUEST['filing_no_s']) ? $_REQUEST['filing_no_s'] :'';
$case_type_selected = isset($_REQUEST['case_type']) ? $_REQUEST['case_type'] :'';
$case_no_selected = isset($_REQUEST['case_no']) ? $_REQUEST['case_no'] :'';
$case_yr_selected = isset($_REQUEST['case_yr']) ? $_REQUEST['case_yr'] :'';
$loc_no_selected = isset($_REQUEST['case_loc']) ? $_REQUEST['case_loc'] :'';
?>
<table width="100%" border='1' cellpadding="1" cellspacing="3" align="center">
<?php
if($filling_no_selected!='')
{
$casedetailsql=$db->prepare("select case_no,entry_date, pet_name,res_name,status,petadvname,res_adv_name from $schemas.case_detail where filing_no=?");
$casedetailsql->bindParam(1, $filling_no_selected, PDO::PARAM_STR);
$casedetailsql->execute();
$casedetailsql_result = $casedetailsql->fetch(PDO::FETCH_OBJ);

checkcasexist($casedetailsql_result);

//code to check objection_status in scrutiny table
/*$checkobjsql=$db->prepare("select objection_status from $schemas.scrutiny where filing_no=?");
$checkobjsql->bindParam(1, $filling_no_selected, PDO::PARAM_STR);
$checkobjsql->execute();
$checkobjsql_result = $checkobjsql->fetch(PDO::FETCH_OBJ);

checkobjstatus($checkobjsql_result);*/

$case_no = $casedetailsql_result->case_no;
checkcasenoexist($case_no);


$pet_name = $casedetailsql_result->pet_name;
$res_name = $casedetailsql_result->res_name;
$status = $casedetailsql_result->status;
$petadvname = $casedetailsql_result->petadvname;
$resadvname = $casedetailsql_result->res_adv_name;
$filing_no_f = $filling_no_selected;
$entry_date_f = $casedetailsql_result->entry_date;
}else{
	
	if($case_type_selected!='' and $case_no_selected!='')
	{
$casedetailsql=$db->prepare("select case_no,filing_no, entry_date, pet_name,res_name,status,petadvname,res_adv_name from $schemas.case_detail where case_type='$case_type_selected' and case_no='$case_no_selected'and case_year='$case_yr_selected' and location_code='$loc_no_selected'");
//$casedetailsql->bindParam(1, $filling_no_selected, PDO::PARAM_STR);
//$casedetailsql->bindParam(1, $case_type_selected, PDO::PARAM_STR);
//$casedetailsql->bindParam(2, $case_no_selected, PDO::PARAM_STR);
//$casedetailsql->bindParam(3, $case_yr_selected, PDO::PARAM_STR);
//$casedetailsql->bindParam(4, $loc_no_selected, PDO::PARAM_STR);
$casedetailsql->execute();
$casedetailsql_result = $casedetailsql->fetch(PDO::FETCH_OBJ);

checkcasexist($casedetailsql_result);

$case_no = $casedetailsql_result->case_no;
checkcasenoexist($case_no);
$pet_name = $casedetailsql_result->pet_name;
$res_name = $casedetailsql_result->res_name;
$status = $casedetailsql_result->status;
$petadvname = $casedetailsql_result->petadvname;
$resadvname = $casedetailsql_result->res_adv_name;
$filing_no_f = $casedetailsql_result->filing_no;
$entry_date_f = $casedetailsql_result->entry_date;
}
}
?>
<tr>
<td align="center">
<font color='red'>
<?php
if($pet_name ==''){echo htmlspecialchars(ucwords(strtoupper('Not Found!')));}else{echo htmlspecialchars(ucwords(strtoupper($pet_name)));}
echo "</font><br><font color='blue'>Vs.</font><br><font color='red'>";
if($res_name ==''){echo htmlspecialchars(ucwords(strtoupper('Not Found!')));}else{echo htmlspecialchars(ucwords(strtoupper($res_name)));}
?></td>	
</tr>
<tr>
<td align="center"><b>
Case Is: <?php if($status == 'P'){echo 'Pending';}else {echo 'Done';} ?></b>
</td>
</tr>
</table>
<br>

<table width="100%" border='1' cellpadding="1" cellspacing="3" align="center">
<tr><b><u>Reference Cases</u></b></tr></br></br>

<form name="refForm" method="post" action="">
<tr>Purpose of Hearing &emsp;<select name="purp_hearing">
<option value="">Select Purpose</option>
<?php
$purposesql="select purpose_name, purpose_code from $schemas.master_purpose";
foreach($db->query($purposesql) as $rowpurpose)
	{	
	$purpose_name =$rowpurpose['purpose_name'];
	$purpose_code =$rowpurpose['purpose_code'];
    echo "<option value=".$purpose_code.">".$purpose_name."</option>";				 
}
?>
</select>&emsp;&emsp;
Remarks:

<input type="text" autocomplete="off" name="remarks"></tr><br>
<input type="hidden" autocomplete="off" name="bench_nature_f" value="<?php echo $bench_nature_r; ?>"></tr><br>
<input type="hidden" autocomplete="off" name="court_no_f" value="<?php echo $court_no_r; ?>"></tr><br>
<input type="hidden" autocomplete="off" name="filing_no_f" value="<?php echo $filing_no_f; ?>"></tr><br>
<!--<input type="hidden" autocomplete="off" name="list_date_f" value="<?php //echo $list_date; ?>"></tr><br>-->
<input type="hidden" autocomplete="off" name="bench_no_f" value="<?php echo $bench_no_r; ?>"></tr><br>
<input type="hidden" autocomplete="off" name="listing_date_f" value="<?php echo $list_date_r; ?>"></tr><br>
<input id="submitRefForm" type="button"  name="submitRefForm" value="Submit"  onClick="return submitrefForm();" />
</form>
</table>

<?php
 }

function checkcasexist($value){
	if($value)
		return $value;
	else{?><td height="30" align="center" cellpadding="0" colspan="6" > <font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red"> 		<span class="error"> 
	<b>Entered case does not exist in fresh filing.	</span></font>	</td>    <?php
	die();
	}
	}
	
	function checkobjstatus($value){
		if($value)
			return $value;
		else{?><td height="30" align="center" cellpadding="0" colspan="6" > <font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red"> 		<span class="error"> 
	<b>Entered case does not have valid objection status.	</span></font>	</td>    <?php
	die();
	}
	}
	
	function checkcasenoexist($value){
	if($value)
		return $value;
	else{?><td height="30" align="center" cellpadding="0" colspan="6" > <font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red"> 		<span class="error"> 
	<b> case is not registered 	</span></font>	</td>    <?php
	die();
	}
	}
 ?>
	</table>
</form>
</div>
</body>

 <?php 
  include '../bfooter.php';
  ?>
