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

 		
		
		if(diary_no.value=="")
		{
			
			alert("Please Enter Diary No.");
			diary_no.focus();
			return false;
		}
		if(listing_date.value=="")
		{
			
			alert("Please Select Listing Date.");
			listing_date.focus();
			return false;
		}
		
		
		
		action = "edit_next_purpose.php";
		submit();
	}
}
function submitForm()
{
 	with(document.frm)
	{
			
		action = "edit_next_purpose.php";
		submit();
	}
}
function validate()
{
 	with(document.frm)
	{		 


		if(diary_no.value=="")
		{
			
			alert("Please Enter Diary No.");
			diary_no.focus();
			return false;
		}
		
		
		
		
		  	
		action = "edit_next_purpose_action.php";
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
    <title>Edit Next Purpose </title>
  <div class="content-wrapper">
      <section class="content-header">
      <h1>
        <center>Edit Next Purpose
        </center>
      </h1>
  
    </section>
 <p> <h4><center>All <font color="red">*</font></span> is mandatory Field </center></h4></p>
    <table  align="center" width="90%" class="std">
  
<form name="frm" method="post" action="edit_next_purpose_action.php" enctype="multipart/form-data" >

<?php
$msg =htmlentities($_REQUEST['msg']);
if($msg !='')
{
?>
<tr>
<td colspan="6"><center>
<font color='red' size='4'> <?php echo $msg;?></font> 
</td>
</center>
</tr>
<?php
}
?>

<?php



$diary_no =htmlentities($_REQUEST['diary_no']);

$listing_date =$_REQUEST['listing_date'];

?>
<tr>
<td>

<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Diary No
		</font>	<input type="text"  maxlength="16" size="15" name="diary_no" value="<?php print htmlentities(htmlspecialchars($diary_no)); ?>" >
		

<font color="red">*</font>listing Date</font>
		<input type="text"  readonly name="listing_date" id="listing_date"  maxlength="10" size="10" value="<?php echo  $listing_date; ?>" onKeyup="javascript:addNumbers(this.value)" class="datepickerToday" onFocus="SetBg(this)" >
		<b></b>
		<input type="submit" name="search" value="Go" size="20" onClick="return submitForm2();">
		</td>
		</tr>
<?php
if(isset($_POST['search'])){

?>

<input type="hidden" name="frm" value="<?php echo htmlspecialchars($frm); ?>" />
<?php





 if($listing_date!='')
 {
  
list($day2,$month2,$year2)=explode('/',$listing_date);
 $listing_date_1=$year2.'-'.$month2.'-'.$day2;
 }

$sql2=$db->prepare("select * from $schemas.case_proceeding  where filing_no=?  and listing_date=?");

$sql2->bindParam(1, $diary_no, PDO::PARAM_STR);
$sql2->bindParam(2, $listing_date_1, PDO::PARAM_STR);

$sql2->execute();
while ($row1 = $sql2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $filing_no2 =$row1['filing_no'];
   $purpose_p =$row1['purpose'];
   $listing_date_p =$row1['listing_date'];
   $next_list_date_p =$row1['next_list_date'];
   $next_list_purpose_p =$row1['next_list_purpose'];
   
}


	
	
	
	
if($filing_no2=='')
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


 <?php

if($filing_no2!='' )
	{
	list($year,$month,$day)=explode('-', $next_list_date_p);
	  $next_list_date_dis=$day.'/'.$month.'/'.$year;
	}
  ?>
</div>
</table>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">
<tr>
		<td align="center"><font face="Verdana" size="2"><b>CASE PROCEEDING DETAILS</b>
		
		</td>
		</tr>
		<tr>
		<td><font face="Verdana" size="2"><b>Filing No :</b></font><font face="Verdana" size="3" color="red">
		<b><?php echo $filing_no2;?></b></font>
		</td>
		</tr>
		<tr>
		<td><font face="Verdana" size="2"><b>Listing Date: <?php echo $listing_date_p;?></b></font>
		</td>
		
		</tr>
		<?php 
		$st1 = $db->prepare("select * from $schemas.master_purpose where display = 'TRUE' and purpose_code='$purpose_p' order by purpose_name asc");
			$st1->execute();
			while ($row3= $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
			{
				$purpose_p_d=htmlspecialchars($row3['purpose_name']);
			}
		?>
		<tr>
		<td><font face="Verdana" size="2"><b>Purpose :</b></font><font face="Verdana" size="3" >
		<b><?php echo $purpose_p_d;?></b></font>
		</td>
		</tr>
		<tr>
		<td><font face="Verdana" size="2"><b>Next Listing Date:<font face="Verdana" size="3" color="red"> <?php echo $next_list_date_dis;?></b></font>
		</td>
		
		</tr>
	
		<tr>
		<td><font face="Verdana" size="2"><b>NEXT PURPOSE : <?php echo $next_list_purpose_p;?></b></font>
		</td>
		</tr>
		<?php
	$sql21=$db->prepare("select * from $schemas.case_allocation  where filing_no=?  and listing_date=?");

$sql21->bindParam(1, $diary_no, PDO::PARAM_STR);
$sql21->bindParam(2, $next_list_date_p, PDO::PARAM_STR);

$sql21->execute();
while ($row11 = $sql21->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
   $filing_no3 =$row11['filing_no'];
   $purpose_alloc =$row11['purpose'];
   $listing_date_alloc =$row11['listing_date'];
    $next_list_date_alloc =$row11['next_list_date'];
	if($next_list_date_alloc!='')
	{
  list($year3,$month3,$day3)=explode('-', $next_list_date_alloc);
	 $next_list_date_alloc_dis=$day3.'/'.$month3.'/'.$year3; 
	}
   
}	
?>
<tr>
		<td align="center"><font face="Verdana" size="2"><b>LISTING DETAILS</b>
		
		</td>
		</tr>

		<tr>
		<td><font face="Verdana" size="2"><b>Filing No :</b></font><font face="Verdana" size="3" color="red">
		<b><?php echo $filing_no3;?></b></font>
		</td>
		</tr>
		<tr>
		<td><font face="Verdana" size="2"><b>Listing Date: <?php echo $listing_date_alloc;?></b></font>
		</td>
		
		</tr>
		<?php 
		$st5 = $db->prepare("select * from $schemas.master_purpose where display = 'TRUE' and purpose_code='$purpose_alloc' order by purpose_name asc");
			$st5->execute();
			while ($row5= $st5->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
			{
				$purpose_a_d=htmlspecialchars($row5['purpose_name']);
			}
		?>
		<tr>
		<td><font face="Verdana" size="2"><b>Purpose :</b></font><font face="Verdana" size="3" >
		<b><?php echo $purpose_a_d;?></b></font>
		</td>
		</tr>
		<tr>
		<td><font face="Verdana" size="2"><b>Next Listing Date:<font face="Verdana" size="3" color="red"> <?php echo $next_list_date_alloc_dis;?></b></font>
		</td>
		
		</tr>
	
		
		




<?php 
if($filing_no2!='')
{
	?>
	<tr>
 
<td   align="left" colspan="6">
		<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">purpose:
		<select name="purpose_new">
		
			<?php
			$st = $db->prepare("select * from $schemas.master_purpose where display = 'TRUE'  and purpose_code='$purpose_alloc' order by purpose_name asc");
			$st->execute();
			while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
			{
				$ctc=htmlspecialchars($row['purpose_code']);
				if($purpose_code == $ctc)
				{
					print "<option value=".htmlspecialchars($row['purpose_code'])." selected>".htmlspecialchars($row['purpose_name'])."</option>";
				}
				else
				{
					print "<option value=".htmlspecialchars($row['purpose_code']).">".htmlspecialchars($row['purpose_name'])."</option>";
				}
			}

			?>

		</select>
		<input type="hidden" name="filing_no" value="<?php print htmlentities(htmlspecialchars($filing_no2)); ?>">
<input type="hidden" name="listing_date" value="<?php print htmlentities(htmlspecialchars($listing_date)); ?>">
<input type="hidden" name="new_listing_date" value="<?php print htmlentities(htmlspecialchars($listing_date_alloc)); ?>">

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
  }
} ?>

 
  