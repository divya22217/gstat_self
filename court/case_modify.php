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
		
		action = "case_modify.php";
		submit();
	}
}
function submitForm()
{
 	with(document.frm)
	{
		
		
		action = "case_modify.php";
		submit();
	}
}
/*function validate()
{
 	with(document.frm)
	{

	if(role.value=='')
       		{
    	   		alert("Please Select ROC Name");
    	   		role.focus();
			return false;
       		}	   	
		action = "case_modify_action.php";
		submit();
	}
}*/



function submitForm1()
{
	var answer = confirm('Are you sure you want to commit the changes?')
	if(answer){
 	with(document.frm)
	{   		 					
if(diary_no.value.length!=16)
{
	alert("Please Enter  16 Digit Diary Number");
			diary_no.select();
			return false;
}	

	action = "case_modify.php";
		submit();
	}
}else{return;}
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
    <title>CASE MODIFY</title>
  <div class="content-wrapper">
      <section class="content-header">
      <h1>
        <center>CASE MODIFY
        </center>
      </h1>
  
    </section>
 <p> <h4><center>All <font color="red">*</font></span> is mandatory Field </center></h4></p>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">
  
<form name="frm" method="post" action="" >

<?php
//print_r($_REQUEST);

$diary_no = htmlentities($_REQUEST['diary_no']);

$bench_type =htmlentities($_REQUEST['bench_type']);
$case_type =$_REQUEST['case_type'];
 $case_no =$_REQUEST['case_no'];
$case_year =$_REQUEST['case_year'];
 $sql="select * from case_type where display='Y' order by case_type_desc ASC";
?>
<!--<tr>
<td   align="left" colspan="6">
		<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Bench:
		<select name="bench_type">
			<?php
			//$st = $db->prepare("select * from $schemas.bench_location where display = 'TRUE' order by bench_location_name asc");
			//$st->execute();
			//while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
			//{
				//$ctc=htmlspecialchars($row['bench_location_code']);
				//if($bench_type == $ctc)
				//{
					//print "<option value=".htmlspecialchars($row['bench_location_code'])." selected>".htmlspecialchars($row['bench_location_name'])."</option>";
				//}
				//else
				//{
					//print "<option value=".htmlspecialchars($row['bench_location_code']).">".htmlspecialchars($row['bench_location_name'])."</option>";
				//}
			//}

			?>

		</select>

</font>
	<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Case Type
<select name="case_type" style="width:200px">
<option>select</option>-->
<?php

/*foreach($dbh->query($sql) as $row)
{

  $casetypecode=$row['id'];
 if($case_type == $casetypecode)
                {
		print "<option value=".htmlentities(htmlspecialchars($row['id']))." selected>".strtoupper(htmlentities(htmlspecialchars($row['case_type_desc'])))."</option>";
                }
           else
                {
                print "<option value=".htmlentities(htmlspecialchars($row['id'])).">".strtoupper(htmlentities(htmlspecialchars($row['case_type_desc'])))."</option>";
		}
 }*/
 ?>

<!--</select><font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Case No
		</font>	<input type="text"  maxlength="7" size="8" name="case_no" value="<?php //print htmlentities(htmlspecialchars($case_no)); ?>" >
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Year
<input type="text"  maxlength="4" size="4" name="case_year" value="<?php //print htmlentities(htmlspecialchars($case_year)); ?>" >
	<input type="button" name="button" value="Go" size="20" onClick="return submitForm2();">
</td>
</tr>-->


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

//$sql2=$db->prepare("select * from $schemas.case_detail where location_code=? and case_type=? and case_no=? and case_year=? ");
$sql2=$db->prepare("select * from $schemas.case_detail where filing_no=? ");

$sql2->bindParam(1, $diary_no, PDO::PARAM_STR);
//$sql2->bindParam(1, $bench_type, PDO::PARAM_STR);
//$sql2->bindParam(2, $case_type, PDO::PARAM_STR);
//$sql2->bindParam(3, $case_no, PDO::PARAM_STR);
//$sql2->bindParam(4, $case_year, PDO::PARAM_STR);
//echo $case_year;

$sql2->execute();
while ($row1 = $sql2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $filing_no =$row1['filing_no'];
   $pet_name =htmlspecialchars($row1['pet_name']);
   $res_name =htmlspecialchars($row1['res_name']);
   $party_name=$pet_name." Vs ".$res_name;
   $pet_mob =htmlspecialchars($row1['pet_mobile']);
   $pet_email =htmlspecialchars($row1['pet_email']);
   $res_mob =htmlspecialchars($row1['res_mobile']);
   $res_email =htmlspecialchars($row1['res_email']);
}

$sql2=$db->prepare("select * from  $schemas.case_detail where filing_no=?  ");

$sql2->bindParam(1, $filing_no, PDO::PARAM_STR);


$sql2->execute();
	
while ($row2 = $sql2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $filing_no333 =$row2['filing_no'];
   $name =$row2['roc_name'];
   $role2 = $row2['roc_id'];      	
}
?>

<input type="hidden" name="filing_no" value="<?php print htmlentities(htmlspecialchars($filing_no)); ?>">
<?php

if($filing_no333=='' && $diary_no!='')
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

</table>
  <?php 
	 if($filing_no!='' and $filing_no333!='' )
 {
	 $party_type = $_REQUEST['party'];
	 ?> 
 <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">

 <tr>
 <td align="center">
<font face="Verdana" color="red">*</span> </font><font face="Verdana" size="2">Petitioner:
</td>
<td valign="top"  align="center">
		<input type="radio"  name="party"  class="party" onchange="submitForm();" value="pet" <?php if($party_type =='pet')echo 'checked';?>>
		</td>
 <td align="center">
<font face="Verdana" color="red">*</span> </font><font face="Verdana" size="2">Respondent:
</td>
<td valign="top"  align="center">
		<input type="radio"  name="party"  class="party" onchange="submitForm();" value="res" <?php if($party_type =='res')echo 'checked';?>>
		</td>
</tr>
</table>
    <?php
 }
 ?>
 <!--<table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">

 <?php 

 //if($filing_no!='' and $filing_no333=='' and $filing_no1!='')
	// if($filing_no!='' and $filing_no333!='' )
 //{
	// print_r($st);
	 ?>
 <tr>
 <td colspan="3" align="center">
<input type="submit" name="submit1" value="Submit" class="button" onClick="return validate();">     
</td>
<?php
 //}
 ?>
     </form>    
   </table>-->
     <?php 
	 if($party_type == 'pet' || $party_type == 'res')
 {
	 if($party_type == 'pet'){
		 $mob = $pet_mob;
		 $email = $pet_email;
		 $petorRes_name = $pet_name;
		 $filing_no_case_detail = $filing_no;
		 
		$pet_additional_party_sql = $db->prepare("select * from $schemas.additional_party where filing_no=? and party_flag='P' order by name");
        $pet_additional_party_sql->bindParam(1, $filing_no, PDO::PARAM_STR);
        $pet_additional_party_sql->execute(); 
	 }
	 else if($party_type == 'res'){
		 $mob = $res_mob;
		 $email = $res_email;
		 $petorRes_name = $res_name;
		 $filing_no_case_detail = $filing_no;
		 
		$pet_additional_party_sql = $db->prepare("select * from $schemas.additional_party where filing_no=? and party_flag='R' order by name");
        $pet_additional_party_sql->bindParam(1, $filing_no, PDO::PARAM_STR);
        $pet_additional_party_sql->execute(); 
	 }
	 ?> 
       <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std"> 
<?php
$party_select = isset($_REQUEST['party_select']) ? $_REQUEST['party_select'] :'';
?>
						<tr>
                        <td align="right" colspan="4" style="padding-bottom:1em;"><span><font color="red">*</font></span><b>PARTY:</b>
                        </td>
                        <td align="left" colspan="6">
                            <select name="party_select" onchange="javascript:submitForm();">
							<option value=""><?php print htmlspecialchars(ucwords(strtoupper($petorRes_name))) ?></option>
							<?php
							while ($pet_additional_party_sql_result = $pet_additional_party_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                                    {
										$adp_id=htmlspecialchars($pet_additional_party_sql_result['id']);
                                        $adp_name=htmlspecialchars($pet_additional_party_sql_result['name']);
										
										 if($party_select == $adp_id)
                                        {
							print "<option value=".$adp_id." selected>".ucwords(strtoupper($adp_name))."</option>";
										}else{
											print "<option value=".$adp_id.">".ucwords(strtoupper($adp_name))."</option>";
										}
									}
							?>							
                            </select>
                        </td></tr>
						<?php
						if($party_select == ''){
						?>
						<form name="frm" method="post" action="" >
						<tr>
                        <td align="right" colspan="4" style="padding-bottom:1em;"><span></span><b>NAME:</b>
                        </td>
                        <td align="left" colspan="6" style="padding-bottom:1em;">
						 <input type="text" name="party_name" value="<?php echo $petorRes_name; ?>"><br>
						 </td></tr>

<tr>
                        <td align="right" colspan="4" style="padding-bottom:1em;"><span></span><b>Mobile No.:</b>
                        </td>
						 <td align="left" colspan="6" style="padding-bottom:1em;">
						 <input type="text" name="mob_no" value="<?php echo $mob; ?>"><br>
						 </td>
						</tr>

<tr>
                        <td align="right" colspan="4" style="padding-bottom:1em;"><b>E-mail:</b>
                        </td>
						 <td align="left" colspan="6" style="padding-bottom:1em;">
						 <input size="30" type="text" name="email" value="<?php echo $email; ?>"><br>
						 </td>
						</tr>
						<input type="hidden" name="filing_no_case_detail" value="<?php echo $filing_no_case_detail; ?>">
						<?php
						}else{
						$additional_party_sql = $db->prepare("select * from $schemas.additional_party where id=?");
                        $additional_party_sql->bindParam(1, $party_select, PDO::PARAM_STR);
                        $additional_party_sql->execute(); 
						$additional_party_sql_result = $additional_party_sql->fetch(PDO::FETCH_ASSOC);
						
						$name_add=htmlspecialchars($additional_party_sql_result['name']);
						$mob_add=htmlspecialchars($additional_party_sql_result['mobile']);
						$email_add=htmlspecialchars($additional_party_sql_result['email']);
						$party_flag_add=htmlspecialchars($additional_party_sql_result['party_flag']);
						
						$add_party_id = htmlspecialchars($additional_party_sql_result['id']);
						?>
						<form name="frm" method="post" action="" >
			<tr>
                        <td align="right" colspan="4" style="padding-bottom:1em;"><span><font color="red">*</font></span><b>NAME:</b>
                        </td>
                        <td align="left" colspan="6" style="padding-bottom:1em;">
						 <input type="text" name="party_name" value="<?php echo $name_add; ?>"><br>
						 </td></tr>

<tr>
                        <td align="right" colspan="4" style="padding-bottom:1em;"><span></span><b>Mobile No.:</b>
                        </td>
						 <td align="left" colspan="6" style="padding-bottom:1em;">
						 <input type="text" name="mob_no" value="<?php echo $mob_add; ?>"><br>
						 </td>
						</tr>

<tr>
                        <td align="right" colspan="4" style="padding-bottom:1em;"><b>E-mail:</b>
                        </td>
						 <td align="left" colspan="6" style="padding-bottom:1em;">
						 <input size="30" type="text" name="email" value="<?php echo $email_add; ?>"><br>
						 </td>
						 <input type="hidden" name="adp_id" value="<?php echo $add_party_id; ?>">
						 <input type="hidden" name="party_flag_add" value="<?php echo $party_flag_add; ?>">
						</tr>
						<?php
						}
						?>
						<tr>  <td align="right" colspan="4" style="padding-bottom:1em;">
                        </td><td>
                                    <input id="submitcmod" type="submit"  name="submitcmod" value="Submit" onClick="return submitForm1();"
                                            /></td></tr>
						
<!--my code end here-->



  
     </form>    
   </table>
     <?php 
  
 }
 
//  submitting form now

  if(isset($_REQUEST['submitcmod'])){
	  $localIP = getHostByName(getHostName());
	  $timestamp = date("Y-m-d H:i:s");
	  //print_r($_REQUEST);
	  if($_REQUEST['filing_no_case_detail']){  
		  $party = $_REQUEST['party'];
		  $party_name = $_REQUEST['party_name'];
		  $mobile = $_REQUEST['mob_no'];
		  $get_email = $_REQUEST['email'];
		  $filing_no_case_detail = $_REQUEST['filing_no_case_detail'];
		  $localIP = getHostByName(getHostName());
		  $timestamp = date("Y-m-d H:i:s");
		  
		  if($party == 'pet'){
			  $party_flag='P';
			  /* start case_modify_track */
			  //print_r($localIP);			    
			  //echo $rr = "insert into $schemas.case_modify_track(filing_no,old_party_name,old_party_mobile,old_party_email,party_flag,user_id,ip_address,modified_at)values('$filing_no_case_detail','$petorRes_name','$mob','$email','$party_flag','$userid','$localIP','$timestamp')";
			  $insert_modify_track_sql = $db->prepare("insert into $schemas.case_modify_track(filing_no,old_party_name,old_party_mobile,old_party_email,party_flag,user_id,ip_address,modified_at)values(?,?,?,?,?,?,?,?)");
			  $query_param_imts = array($filing_no_case_detail,$petorRes_name,$mob,$email,$party_flag,$userid,$localIP,$timestamp);
              $result_imts = $insert_modify_track_sql->execute($query_param_imts);
			   /* end case_modify_track */
			  
		if($result_imts){
			//die("update $schemas.case_detail set(pet_name, pet_mobile, pet_email)=('$party_name','$mobile','$get_email') where filing_no='$filing_no_case_detail'");	  
		  $insert_case_detail_pet =$db->prepare("update $schemas.case_detail set(pet_name, pet_mobile, pet_email)=(?,?,?) where filing_no=?");

$insert_case_detail_pet->bindParam(1, $party_name, PDO::PARAM_STR);
$insert_case_detail_pet->bindParam(2, $mobile, PDO::PARAM_STR);
$insert_case_detail_pet->bindParam(3, $get_email, PDO::PARAM_STR);
$insert_case_detail_pet->bindParam(4, $filing_no_case_detail, PDO::PARAM_STR);

$insert_case_detail_pet->execute();
$msg = 'Successfully updated Petitioner data';
header("Location:./case_modify.php?msg=$msg");
		}else{die('something went wrong...');}	  
		  
		  }else if($party == 'res'){
			  $party_flag='R';
			   /* start case_modify_track */
			  //print_r($localIP);			    
			  //echo $rr = "insert into $schemas.case_modify_track(filing_no,old_pet_name,old_pet_mobile,old_pet_email,user_id,ip_address,modified_at)values('$filing_no_case_detail','$petorRes_name','$mob','$email','$userid','$localIP',$timestamp)";
			  $insert_modify_track_sql = $db->prepare("insert into $schemas.case_modify_track(filing_no,old_party_name,old_party_mobile,old_party_email,party_flag,user_id,ip_address,modified_at)values(?,?,?,?,?,?,?,?)");
			  $query_param_imts = array($filing_no_case_detail,$petorRes_name,$mob,$email,$party_flag,$userid,$localIP,$timestamp);
              $result_imts = $insert_modify_track_sql->execute($query_param_imts);
			   /* end case_modify_track */
			  
			  if($result_imts){
			  //die("update $schemas.case_detail set(res_name, res_mobile, res_email)=('$party_name','$mobile','$get_email') where filing_no='$filing_no_case_detail'");
			  $insert_case_detail_res =$db->prepare("update $schemas.case_detail set(res_name, res_mobile, res_email)=(?,?,?) where filing_no=?");

$insert_case_detail_res->bindParam(1, $party_name, PDO::PARAM_STR);
$insert_case_detail_res->bindParam(2, $mobile, PDO::PARAM_STR);
$insert_case_detail_res->bindParam(3, $get_email, PDO::PARAM_STR);
$insert_case_detail_res->bindParam(4, $filing_no_case_detail, PDO::PARAM_STR);

$insert_case_detail_res->execute();
$msg = 'Successfully updated Respondent data';
header("Location:./case_modify.php?msg=$msg");
			  }else{die('something went wrong...');}	
		  }
	  
	  }else if($_REQUEST['adp_id']){
		  
		  $party_name = htmlspecialchars($_REQUEST['party_name']);
		  $mobile = htmlspecialchars($_REQUEST['mob_no']);
		  $get_email = htmlspecialchars($_REQUEST['email']);
		  $add_party_id = htmlspecialchars($_REQUEST['adp_id']);
		  //$party_flag_add = $_REQUEST['party_flag_add'];
		  $party_flag='A';
		  
		  /* start case_modify_track */		    
			  //echo $rr = "insert into $schemas.case_modify_track(filing_no,old_party_name,old_party_mobile,old_party_email,party_flag,
			  //user_id,ip_address,modified_at,additional_party_id)values('$filing_no_case_detail','$name_add','$mob_add','$email_add',
			  //'$party_flag','$userid','$localIP','$timestamp','$add_party_id')";
			  //die(p);
			  $insert_modify_track_sql = $db->prepare("insert into $schemas.case_modify_track(filing_no,old_party_name,old_party_mobile,old_party_email,party_flag,user_id,ip_address,modified_at,additional_party_id)values(?,?,?,?,?,?,?,?,?)");
			  $query_param_imts = array($filing_no_case_detail,$name_add,$mob_add,$email_add,$party_flag,$userid,$localIP,$timestamp,$add_party_id);
              $result_imts = $insert_modify_track_sql->execute($query_param_imts);
			   /* end case_modify_track */
		  
		  if($result_imts){
		  $insert_add_party =$db->prepare("update $schemas.additional_party set(name, mobile, email)=(?,?,?) where id=?");

$insert_add_party->bindParam(1, $party_name, PDO::PARAM_STR);
$insert_add_party->bindParam(2, $mobile, PDO::PARAM_STR);
$insert_add_party->bindParam(3, $get_email, PDO::PARAM_STR);
$insert_add_party->bindParam(4, $add_party_id, PDO::PARAM_STR);

$insert_add_party->execute();
$msg = 'Successfully updated additional party';
header("Location:./case_modify.php?msg=$msg");
}else{die('something went wrong...');}
	  }
	  
	  //validation code
	 // $select_menu_sql=$db->prepare("select menu_id from menu_r where menu_name=?");
	  //$select_menu_sql->bindParam(1, $menu_name, PDO::PARAM_STR);
      //$select_menu_sql->execute(); 
	  //$row = $select_menu_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT)

	  
	  
	  
  }
  ?>
  <?php 
  
  //include '../bfooter.php';
  ?>


  <?php } ?>