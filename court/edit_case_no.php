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
		
		
		action = "edit_case_no.php";
		submit();
	}
}
function submitForm4()
{
 	with(document.frm)
	{
		
		
		action = "edit_case_no.php";
		submit();
	}
}


function submitForm3()
{
 	with(document.frm)
	{
	if(new_case_type.value == "select")
		{
			alert("Please select New Case Type");
			new_case_type.focus();
			return false;
		}
		if(new_case_no.value=="")
		{
			
			alert("Please Enter New Case No.");
			new_case_no.focus();
			return false;
		}
		if(isNaN(new_case_no.value) == true)
		{
			alert("Please enter  numeric Case No.");
			new_case_no.select();
			return false;
		}
		if(new_case_year.value=="")
		{
			alert("Please Enter New Case Year");
			new_case_year.focus();
			return false;
		}
		if(isNaN(new_case_year.value) == true)
		{
			alert("Please enter  numeric New Case Year");
			new_case_year.select();
			return false;
		}
		if(new_case_year.value.length!=4)
		{
			alert("Please Enter 4 digit New case year");
			new_case_year.select();
			return false;
		}	
		
		
action = "edit_case_no.php";
		submit();
	}
}
function submitForm1()
{
	var answer = confirm('Are you sure you want to commit the changes?')
	if(answer){
 	with(document.frm)
	{   

	action = "edit_case_no_action.php";
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
    <title>EDIT CASE NUMBER</title>
  <div class="content-wrapper">
      <section class="content-header">
      <h1>
        <center>EDIT CASE NO
        </center>
      </h1>
  
    </section>
 <p> <h4><center>All <font color="red">*</font></span> is mandatory Field </center></h4></p>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">
  
<form name="frm" method="post" action="edit_case_no_action.php" >




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
<?php 

	
	$diary_no =$_REQUEST['diary_no'];
?>

<tr>
	<td>
	<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Diary No. &nbsp;
		</font>	<input type="text"  maxlength="16" size="22" name="diary_no" value="<?php print htmlentities(htmlspecialchars($diary_no)); ?>" >
			<input type="button" name="button" value="Go" size="20" onClick="return submitForm4();">
	</td>
	</tr>

<?php

$sql22=$db->prepare("select * from $schemas.case_detail where filing_no=?");

$sql22->bindParam(1, $diary_no, PDO::PARAM_STR);

//echo $case_year;

$sql22->execute();
while ($row22 = $sql22->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $pet_name1 =$row22['pet_name'];
   $res_name1 =$row22['res_name'];
    $filing_no23 =$row22['filing_no'];
   
    $party_name1=$pet_name1." Vs ".$res_name1;
}

?>
<tr>
		<td height="30" align="center" cellpadding="0" colspan="6">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
		<span class="error"> <b> <?php  echo strtoupper($party_name1) ; ?></span></font>
		</td>
		</tr>





<?php 

if($filing_no23=='' and $diary_no!='')
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

<input type="hidden" name="filing_no" value="<?php print htmlentities(htmlspecialchars($filing_no23)); ?>">


		<tr>
		<td>
	
<?php

$new_bench_type =$_REQUEST['new_bench_type'];
$new_case_type =$_REQUEST['new_case_type'];
 $new_case_no =$_REQUEST['new_case_no'];
$new_case_year =$_REQUEST['new_case_year']
?>


 

		<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">New Bench:
		<select name="new_bench_type">
			<?php
			$st1 = $db->prepare("select * from $schemas.bench_location where display = 'TRUE' order by bench_location_name asc");
			$st1->execute();
			while ($row2 = $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
			{
				$ctc2=htmlspecialchars($row2['bench_location_code']);
				if($new_bench_type == $ctc2)
				{
					print "<option value=".htmlspecialchars($row2['bench_location_code'])." selected>".htmlspecialchars($row2['bench_location_name'])."</option>";
				}
				else
				{
					print "<option value=".htmlspecialchars($row2['bench_location_code']).">".htmlspecialchars($row2['bench_location_name'])."</option>";
				}
			}

			?>

		</select>

		<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">New Case Type
<select name="new_case_type" style="width:200px">
<option>select</option>
<?php
 $sql1="select * from case_type where display='Y' order by case_type_desc ASC";
foreach($dbh->query($sql1) as $row1)
{

  $casetypecode1=$row1['id'];
 if($new_case_type == $casetypecode1)
                {
		print "<option value=".htmlentities(htmlspecialchars($row1['id']))." selected>".strtoupper(htmlentities(htmlspecialchars($row1['case_type_desc'])))."</option>";
                }
           else
                {
                print "<option value=".htmlentities(htmlspecialchars($row1['id'])).">".strtoupper(htmlentities(htmlspecialchars($row1['case_type_desc'])))."</option>";
		}
 }
 ?>

</select><font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">New Case No
		</font>	<input type="text"  maxlength="7" size="8" name="new_case_no" value="<?php print htmlentities(htmlspecialchars($new_case_no)); ?>" >
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">New Case Year
<input type="text"  maxlength="4" size="4" name="new_case_year" value="<?php print htmlentities(htmlspecialchars($new_case_year)); ?>" >
	<input type="button" name="button" value="Search" size="20" onClick="return submitForm3();">
</td>
</tr>

<?php
if($new_case_type!='' && $new_case_no!='')
{
 $sql1="select count(*) from $schemas.case_detail where case_type='$new_case_type'  and case_no='$new_case_no' and case_year='$new_case_year' and location_code='$new_bench_type'";
  $sql1=$db->prepare($sql1);
  $sql1->execute();
  $case_exist=$sql1->fetchColumn();
  if($case_exist >0)
  {
	  ?>
	 <tr><td align="center" colspan="7"><font face="Verdana" color="red" size ="4">

	 <?PHP echo $msg2="RECORD ALREADY EXISTS" ;
  }

?>
</font>
<?php
if($case_exist ==0 )
{
	?>
	<tr>
<td colspan="8" align="left" valign="top"><div align="center">
 <input id="submitcmod" type="submit"  name="submitcmod" value="Update" onClick="return submitForm1();"
</td>
</tr>

  <?php 
}
}
}  ?>