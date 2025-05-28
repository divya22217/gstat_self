<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include("../db_inc1.php");
include '../db_inc2.php';
session_start();
$_SESSION['user'];
$_SESSION['location'];
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

?>

<script language="javascript">
function submitForm2()
{
 	with(document.frm)
	{

 		if(diary_no.value=='')
		{
			alert("Please Enter Diary No.");
			diary_no.focus();
			return false;
		}
		
		action = "direct_disposed.php";
		submit();
	}
}
function submitForm()
{
 	with(document.frm)
	{
			
		action = "direct_disposed.php";
		submit();
	}
}
function submitForm3()
{
 	with(document.frm)
	{
			
		action = "direct_disposed.php";
		submit();
	}
}
function validate()
{
 	with(document.frm)
	{		 
if(disposal_date.value=='')
		{
			alert("Please Select Disposal Date");
			disposal_date.focus();
			return false;
		}
		if(bench_no.value=='0')
		{
			alert("Please Select Court/Coram");
			bench_no.focus();
			return false;
		}
		
		if(disposal_nature.value=='')
		{
			alert("Please Select Disposal Nature");
			disposal_nature.focus();
			return false;
		}
		  	
		action = "direct_disposed_action.php";
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
    <title>DIRECT DISPOSED </title>
  <div class="content-wrapper">
      <section class="content-header">
      <h1>
        <center>DIRECT DISPOSED
        </center>
      </h1>
  
    </section>
 <p> <h4><center>All <font color="red">*</font></span> is mandatory Field </center></h4></p>
    <table  align="center" width="90%" class="std">
  
<form name="frm" method="post" action="direct_disposed_action.php" enctype="multipart/form-data" >

<?php
$msg =htmlentities($_REQUEST['msg']);
if($msg !='')
{
?>
<tr>
<td colspan="6"><center>
<font color='red' size='3'><b> <?php echo $msg;?></b></font> 
</td>
</center>
</tr>
<?php
}
$diary_no =$_REQUEST['diary_no'];
?>
<tr>
<td>
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Diary No.
<input type="text"  maxlength="16" size="16" name="diary_no" value="<?php print htmlentities(htmlspecialchars($diary_no)); ?>" >
	<input type="submit" name="search" value="Go" size="20" onClick="return submitForm2();">
</td>


</tr>


<input type="hidden" name="frm" value="<?php echo htmlspecialchars($frm); ?>" />
<?php
 $stat='P';
$sql2=$db->prepare("select * from $schemas.case_detail  where filing_no=?  and status=? ");

$sql2->bindParam(1, $diary_no, PDO::PARAM_STR);
$sql2->bindParam(2, $stat, PDO::PARAM_STR);


$sql2->execute();
while ($row1 = $sql2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $filing_no =$row1['filing_no'];
  $todays_status =$row1['status'];
  
}


	$case_status="CASE IS PENDING";


?>

		<?php
		
if($filing_no=='' and $diary_no!='')
{

	?>
	
	<tr>
		<td height="30" align="center" cellpadding="0" colspan="6">
		<font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="red">
		<span class="error"> <b> <?php  echo "RECORD NOT FOUND OR CASE IS DISPOSED"; ?></span></font>
		</td>
		</tr>
		
		<?php 
	
	exit();

}
?>

<?php
if($filing_no!='')
{
	
	$stat='P';
$sql3=$db->prepare("select * from $schemas.case_detail  where filing_no=? and status=? ");

$sql3->bindParam(1, $filing_no, PDO::PARAM_STR);
$sql3->bindParam(2, $stat, PDO::PARAM_STR);

$sql3->execute();
while ($row3 = $sql3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
  //$filing_no2 =$row3['filing_no'];
  //$todays_status2 =$row3['status'];
  $case_type =$row3['case_type'];
  $case_no =$row3['case_no'];
  $case_year =$row3['case_year'];
  $location_code =$row3['location_code'];
  $pet_name =$row3['pet_name'];
  $res_name =$row3['res_name'];
  $party_name=$pet_name." Vs ".$res_name;
}

if($location_code > 0)
	{
		 $ref_lcode ="select short_name from $schemas.bench_location where bench_location_code ='$location_code'";
$ref_lcode=$db->prepare($ref_lcode);
$ref_lcode->execute();
$lcodename = $ref_lcode->fetchColumn();
   
 
	}
	if($case_type > 0)
	{
		$stQ = $db->prepare("select case_type_desc from case_type where id = ?");
		$stQ->bindParam(1, $case_type, PDO::PARAM_STR);
		$stQ->execute();
		$case_type_short_name=$stQ->fetchColumn();
	}
	
	$sql212=$db->prepare("select * from $schemas.case_disposal where filing_no=?   ");
   $sql212->bindParam(1, $filing_no2, PDO::PARAM_STR);
   
   $sql212->execute();
   while ($row112 = $sql212->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	
    //$todays_status =$row112['todays_status'];
    $disposal_date =$row112['disposal_date'];
	$court_no =$row112['court_no'];
	$disposal_nature =$row112['disposal_nature'];
	$judge_code =$row112['judge_code'];
	if($disposal_date!='')
	{
	list($year,$month,$day)=explode('-', $disposal_date);
	 $disposal_date_dis=$day.'/'.$month.'/'.$year;
	}
	$sql4=$db->prepare("select action_type from $schemas.master_action where action_code=? ");
   $sql4->bindParam(1, $disposal_nature, PDO::PARAM_STR);
   
   $sql4->execute();
   while ($row4 = $sql4->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
    $disposal_nature_dis=$row4['action_type'];
}
}

$case_number=$case_type_short_name."/".$case_no."(".$lcodename.")".$case_year;
	

?>


 <?php 
}

  ?>
</div>
</table>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">
<tr>
		<td align="center"><font face="Verdana" size="2"><b>CASE DETAILS</b>
		
		</td>
		</tr>
		<tr>
		<td><font face="Verdana" size="2"><b>Case No.:</b></font><font face="Verdana" size="3" color="red">
		<b><?php echo $case_number;?></b></font>
		</td>
		</tr>
		<tr>
		<td><font face="Verdana" size="2"><b>Case Title :</b></font><font face="Verdana" size="3" color="red">
		<b><?php echo $party_name;?></b></font>
		</td>
		</tr>
		<tr>
		<td><font face="Verdana" size="2"><b>Case Status : <?php echo $case_status;?></b></font>
		</td>
		
		</tr>
	

	</table>
	<table border='1' align="center" width="90%">
	
	<tr>
<?php  $disposal_date = isset($_REQUEST['disposal_date']) ? $_REQUEST['disposal_date'] :'';?>
<td>
<font face="Verdana" size="2" color="red">*</span> </font>
<font face="Verdana, Arial, Helvetica, sans-serif" size="2"> Disposal Date

<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>

<input type="text" autocomplete="off" name="disposal_date" readonly="readonly" size="10" maxlength="10" onchange="submitForm();UnSetBg(this);" class="datepickerToday" value="<?php print htmlspecialchars($disposal_date); ?>"/>
</td>
</tr>
<?php
if($disposal_date!='')
{
list($day,$month,$year)=explode('/',$disposal_date);
$listdate_entire=$year.'-'.$month.'-'.$day;
}
 //$sql="select distinct(court_no) from $schemas.bench where from_list_date='$listdate_entire' order by court_no asc";
?>
<?php

if($listdate_entire!='')
{

$sql2=" select * from $schemas.bench where  from_list_date ='$listdate_entire' order by court_no asc";
$bench_d=$dbh->prepare($sql2);
$bench_d->execute();
if($bench_d->rowCount()>0)
{
	
	 $bench_data=$bench_d->fetchAll();
 ?>

  <input type="hidden" name="dis_date" value="<?php echo htmlspecialchars($listdate_entire);?>" />   
<tr>
<th ><font face="Verdana, Arial, Helvetica, sans-serif" >&nbsp;</font></th>
<th><font face="Verdana, Arial, Helvetica, sans-serif" >Court </font></th>

<th><font face="Verdana, Arial, Helvetica, sans-serif">Hon'ble Justice</font></th>

</tr>
<?php

	$flag=0;
$case_limit_avail='0';
	
	foreach($bench_data as $row2)
	{
	
	$bench_code1='';
		
		$flag=1;
		$court_no =$row2['court_no']; 
		$bench_code1 = $row2['bench_no'];
		
		?>
		<tr>
		<?php $bench_nocheck = isset($_REQUEST['bench_no']) ? $_REQUEST['bench_no'] : ''; ?>
		<td align="center">
		<input type="radio"  name="bench_no"  class="bench_no"  value="<?php echo $bench_code1;?>" <?php if($bench_nocheck ==$bench_code1)echo 'checked';?> onchange="submitForm();UnSetBg(this);">
		</td>
		<td align="center"><?php  
		$sql2q=" select bench_name from $schemas.bench_nature where  bench_code ='$row2[bench_nature]'";
		$sth11= $dbh->prepare($sql2q);
		$sth11->execute();
		echo $sth11->fetchColumn(); ?>
		<?php echo '<br>Court No : '.$court_no;?>
		</td>
<?php 
if($bench_no=='')
{
	$bench_no=0;
	}
	


?>
		<td colspan="6" align="left">
		<?php
		
		$sql="select bj.judge_code  from $schemas.bench_judge as bj,$schemas.master_judge as jm where bj.from_list_date ='$listdate_entire' and bj.bench_no='$bench_code1' and jm.judge_code=bj.judge_code ";
		$sth = $dbh->prepare($sql);
		$m=0;
		$arr=[];
		foreach($dbh->query($sql) as $row)
		{
			$arr[$m]=$row['judge_code'];
			$m++;
		}
		$sql="select presiding from $schemas.bench where from_list_date ='$listdate_entire'  and bench_no='$bench_code1'";
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
<table align="center" width="90%">	
			
<tr>
<td>
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Disposal Nature



<?php  $disposal_nature = isset($_REQUEST['disposal_nature']) ? $_REQUEST['disposal_nature'] :'';?>
<select  name="disposal_nature">
<option value="">Select</option>
<?php
$statusd='D';
$st= $db->prepare("select * from $schemas.master_action where  status=?");
$st->bindParam(1, $statusd, PDO::PARAM_STR);
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$actioncode = $row['action_code'];
if($actioncode == $disposal_nature)
{
print "<option value=".htmlspecialchars($row['action_code'])." selected>".htmlspecialchars($row['action_type'])."</option>";
}
else
{
print "<option value=".htmlspecialchars($row['action_code']).">".htmlspecialchars($row['action_type'])."</option>";
}
}
?>
</select>
</td>
</tr>

		


		
		


<input type="hidden" name="filing_no" value="<?php print htmlentities(htmlspecialchars($filing_no)); ?>">
<input type="hidden" name="todays_status" value="<?php print htmlentities(htmlspecialchars($todays_status)); ?>">
<?php 

if($filing_no!='' and $todays_status=='P' and $bench_nocheck!=0)
{
	?>
 <tr>
 <td align="center">
<input type="submit" name="submit1" value="Submit" class="button" onClick="return validate();">     
</td>
</tr>
<?php
}
?>
     </form>    
   </table>
<?php
//include '../bfooter.php';
  } ?>

 
  