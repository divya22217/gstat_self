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
		
		action = "irp_edit.php";
		submit();
	}
}
function submitForm()
{
 	with(document.frm)
	{
		
		
		action = "irp_edit.php";
		submit();
	}
}
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
if(enrol_no.value=='')
{
	alert("Please enter enrollment no.");
			enrol_no.select();
			return false;
}
   	
	/*	if(dt_of_appointment.value=='')
{
	alert("Please Select Date of appointment");
			dt_of_appointment.select();
			return false;
}		
		if(confirmation_rp.value=='')
{
	alert("Please Select Date of Conformation of RP");
			confirmation_rp.select();
			return false;
}	

		if(change_name_irp.value=='')
{
	alert("Please Select Date of IRP Name Change");
			change_name_irp.select();
			return false;
}	

		if(change_name_rp.value=='')
{
	alert("Please Select Date of RP Change");
			change_name_rp.select();
			return false;
}	*/
		
		action = "irp_edit_action.php";
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

	action = "irp_edit.php";
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

<!-- AdminLTE App -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    })

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })
  })
</script>
</head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <title>IRP</title>
  <div class="content-wrapper">
      <section class="content-header">
      <h1>
        <center>IRP (Edit)
        </center>
      </h1>
  
    </section>
 <p> <h4><center>All <font color="red">*</font></span> is mandatory Field </center></h4></p>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">
  
<form name="frm" method="post" action="irp_edit_action.php" >

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
 ?>

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
$stage='I';
$sql2=$db->prepare("select * from  $schemas.irp_detail where filing_no=?  ");

$sql2->bindParam(1, $filing_no, PDO::PARAM_STR);


$sql2->execute();
	
while ($row2 = $sql2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $filing_no333 =$row2['filing_no'];
   $name =$row2['name'];
   $email_irp =$row2['email'];
   $mobile =$row2['mobile'];
   //$enrolment_no =$row2['enrolment_no'];
    $role2 =$row2['role'];
   $dt_of_appointment =$row2['appoinment'];
    $confirmation_rp =$row2['confirmation_rp'];
   $change_name_irp =$row2['change_irp_name'];
   $change_name_rp =$row2['change_rp_name'];
   $accp_rej =$row2['acc_rej'];
   
   
   
   
  if($dt_of_appointment!='')
	{
	list($year,$month,$day)=explode('-',$dt_of_appointment);
	$dt_of_appointment=$day.'/'.$month.'/'.$year;
	}
	if($dt_of_appointment=='11/11/1111')
	{
		$dt_of_appointment='';
	}
	
	if($confirmation_rp!='')
	{
	list($year1,$month1,$day1)=explode('-',$confirmation_rp);
	$confirmation_rp=$day1.'/'.$month1.'/'.$year1;
	}
	
	
	
	if($confirmation_rp=='11/11/1111')
	{
		$confirmation_rp='';
	}
	
	if($change_name_irp!='')
	{
	list($year2,$month2,$day2)=explode('-',$change_name_irp);
	$change_name_irp=$day2.'/'.$month2.'/'.$year2;
	}
	
	if($change_name_irp=='11/11/1111')
	{
		$change_name_irp='';
	}
	if($change_name_rp!='')
	{
	list($year3,$month3,$day3)=explode('-',$change_name_rp);
	 $change_name_rp=$day3.'/'.$month3.'/'.$year3;
	}
	
	if($change_name_rp=='11/11/1111')
	{
		$change_name_rp='';
	}
	
	if($accp_rej!='')
	{
	list($year4,$month4,$day4)=explode('-',$accp_rej);
	$accp_rej=$day4.'/'.$month4.'/'.$year4;
	}
if($accp_rej=='11/11/1111')
	{
		$accp_rej='';
	}

	
}
?>

<input type="hidden" name="filing_no" value="<?php print htmlentities(htmlspecialchars($filing_no)); ?>">
<?php
if($role2=='2')
{
	?>
	<tr>
		<td height="30" align="center" cellpadding="0" colspan="6">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
		<span class="error"> <b> <?php  echo "IRP IS NOW RP YOU CAN'T EDIT IRP"; ?></span></font>
		</td>
		</tr>
	<?php
}

?>
<?php 
if($filing_no333=='')
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

 $case_no1 = htmlspecialchars(strtoupper($case_type_short_name).'/'.$case_no.'('.$lcodename.')'.$case_year);
?>
</table>
 <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">
 
<tr>
<td>
<font face="Verdana" size="2" color="red"> </font><font face="Verdana" size="2">Name:</font>
</td>
<td>
<input type="text" id="name" maxlength="250" autocomplete="off" size="50" name="name" value="<?php echo $name; ?>"/>
</td> 
 </tr>
 <tr>
 <td>
<font face="Verdana" size="2" color="red"></font><font face="Verdana" size="2">Role:
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
<font face="Verdana" size="2" color="red"></font><font face="Verdana" size="2">Email Id:</font>
</td>
<td>
<input type="text" id="email_irp" maxlength="100" autocomplete="off" size="50" name="email_irp" value="<?php echo $email_irp; ?>" />
</td> 
 </tr>
 <tr>
<td>
<font face="Verdana" size="2" color="red"></font><font face="Verdana" size="2">Mobile Number:</font>
</td>
<td>
<input type="text" id="mobile" maxlength="15" autocomplete="off" size="50" name="mobile" value="<?php echo $mobile; ?>"/>
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
<td  align="left"><font face="Verdana" size="2" color="red"></font><font face="Verdana" size="2">Date of Appointment</font></td>

<!--<td align="left" ><input type="text"  maxlength="10" size="10" readonly name="dt_of_appointment" id="dt_of_appointment" class="datepickerToday" value="<?php print htmlspecialchars($dt_of_appointment); ?>" </td>
-->
<td align="left" ><input type="text"  maxlength="10" size="10"  name="dt_of_appointment" id="dt_of_appointment" class="datepickerToday" value="<?php print htmlspecialchars($dt_of_appointment); ?>" </td>


</tr>
<?php
$sql12=$db->prepare("select * from  $schemas.irp_detail where filing_no=? ");

$sql12->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql12->execute();
	
while ($row12 = $sql12->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
  //$name =$row1['name'];
   $enrolment_no =$row12['enrolment_no'];
   $party_code = $enrolment_no;
   // $filing_no22 =$row1['filing_no'];
}
if($enrolment_no == ''){
$IRP='I';
$sql1=$dbonline->prepare("select * from  e_cases_party where filing_no=? and party_flag=?");

$sql1->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql1->bindParam(2, $IRP, PDO::PARAM_STR);
$sql1->execute();
	
while ($row1 = $sql1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
  //$name =$row1['name'];
    $party_code =$row1['party_code'];
   // $filing_no22 =$row1['filing_no'];
}
}
?>
<tr>
<td>
<tr>
<td>
<font face="Verdana" size="2" color="red">*<span></font><font face="Verdana" size="2">Enrolment Number:</font>
</td>
<td>
<input type="text" id="enrol_no" maxlength="50" autocomplete="off" size="50" name="enrol_no" value="<?php echo $party_code; ?>"/>
</td> 
 </tr>
</td>
</tr>
</table>
 <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">


 <?php 
/* 
	 if($filing_no!='' and $filing_no333!='')
 {
	 if($role2=='1')
	 {
		 */
	 ?>
 <tr>
 <td colspan="3" align="center">
<input type="submit" name="submit1" value="Submit" class="button" onClick="return validate();">     
</td>
<?php
 /*
 }
 }
 */
 ?>
     </form>    
   </table>

  


  <?php } ?>