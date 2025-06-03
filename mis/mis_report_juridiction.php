<?php 
//print_r($_POST);die();
if(isset($_POST[submit1]))
{
$prev = htmlentities($_POST[prev_list_date]);
$next = htmlentities($_POST[next_list_date]);
}
//variables to hold value for fresh filing
$m = get_month($prev);
$y = get_year($prev);

//old code start
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
$benchlocation =htmlentities($_REQUEST['bench_location']);
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
<script type="text/javascript">     
    function PrintDiv() {    
       var divToPrint = document.getElementById('divToPrint');
       var popupWin = window.open('', '_blank', 'width=300,height=300');
       popupWin.document.open();
       popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
            }
 </script>
<?php 

include '../inheader.php';
include '../insidebar.php';

?>

<!--<script language="javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
  return false;
}
function submitForm()
{
 	with(document.frm)
	{
		action = "create_bench.php";
		submit();
	}
}
function submitForm1()
{
 	with(document.frm)
	{

if(bench_location.value == "")
        	{
        	alert("Please Select Bench !!!!!");
        	bench_location.focus();
        	return false;
        	}

if(bench_code.value == "")
        	{
        	alert("Please Select Bench Nature!!!!!");
        	bench_code.focus();
        	return false;
        	}
var flds1=document.getElementsByName('judge[]');
		for (var i=0;i<flds1.length;i++)
		{
		 	if(flds1[i].value=='')
			{
				alert("Please Select Coram");
				flds1[i].focus();
				return false;
			}
			
		}
if(bench_code.value ==7 && no_of_judge1.value=="" ){
	alert("Please provide number of no of judge !!!!");
        	no_of_judge1.focus();
        	return false;
}		
if(from_list_date.value == "")
        	{
        	alert("Please Select Listing Date!!!!!");
        	from_list_date.focus();
        	return false;
        	}

if(court_no.value == "")
        	{
        	alert("Please Enter Court No!!!!!");
        	court_no.focus();
        	return false;
        	}

if(isNaN(court_no.value) == true)
			{
				alert("Please Enter Numeric Court No.");
				court_no.select();
				return false;
			}

if(limit_case.value == "")
        	{
        	alert("Please Enter Limit of Case!!!!!");
        	limit_case.focus();
        	return false;
        	}
if(isNaN(limit_case.value) == true)
			{
				alert("Please Enter Numeric for Limit Case");
				limit_case.select();
				return false;
			}

if(limit_case.value!= "")
        	{
if(limit_case.value<1)
        	{
        	alert("Please Enter Valid Limit of Case!!!!!");
        	limit_case.focus();
        	return false;
        	}
}
	action = "bench_action.php";
		submit();
	}
}
</script>-->
<head>
<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
    padding: 5px;
    text-align: center;
	margin-left: 5px;
}
</style>
</head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <title>Report 2</title>
   
    
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="divToPrint" style="overflow-y: scroll; padding-bottom:10px;">
  <h4><button onclick="PrintDiv();" >Print</button></h4>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <center>Statement of state-wise Pending cases for the Month of <?php echo for_month($prev); ?>
        </center>
      </h1>
      
    </section>
 <!--<p> <h4><center>All <font color="red">*</font></span> is mandatory Field </center></h4></p>-->
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">

  <tr>
    <th colspan="41" align="center">National Company Law Tribunal</th>
  </tr>
  <tr>
    <th colspan="41" align="center">Statement of state-wise Pending cases for the Month of <?php echo for_month($prev); ?></th>
  </tr>
  <tr>
    <th colspan="2"></th>
	
	<?php
	
	//get data for benches
   $qry1=$dbo->prepare("select bench_name, e_master_bench_id from public.e_master_bench");
   $qry1->execute();
  while ($judgerow1 = $qry1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	
   // code to get no of rows for colspan	
   $bench_id = $judgerow1['e_master_bench_id'];
   
	//get data for juridiction
   $qry2=$dbo->prepare("select territorial_jurisdiction_name from public.e_master_territorial_jurisdiction where e_master_bench_id = '$bench_id'");
   //print_r($qry2);
   $qry2->execute();
   $number_of_rows = $qry2->rowCount();
	  
	?>	
    <th colspan="<?php echo $number_of_rows; ?>"><?php echo bench_jargon(htmlspecialchars($judgerow1['bench_name'])); ?></th>
	<?php
  }
  ?>
  <th rowspan="2">Total</th>
  </tr>
   <tr>
    <th>Sr. No.</th>
    <th>Section</th>
	
	<?php
	
	//get data for benches
   $qry1=$dbo->prepare("select e_master_bench_id from public.e_master_bench");
   //print_r($qry1);
   $qry1->execute();
  while ($judgerow1 = $qry1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	  $bench_id = $judgerow1['e_master_bench_id'];
	//print_r($bench_id);
	//get data for juridiction
   $qry2=$dbo->prepare("select territorial_jurisdiction_name from public.e_master_territorial_jurisdiction where e_master_bench_id = '$bench_id'");
   //print_r($qry2);
   $qry2->execute();
  while ($judgerow2 = $qry2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	?>
	
    <th><?php echo territorial_jargon(htmlspecialchars($judgerow2['territorial_jurisdiction_name'])); ?></th>
<?php
  }
  }
  ?>
  </tr>
  
  <!-- Start of code for data in table -->
  
  <?php
  
  //get data for benches
   $qry3=$dbo->prepare("select act_id, act_name from public.master_act");
   $qry3->execute();
   $countsno = 1;
  while ($judgerow3 = $qry3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	  $act_id = $judgerow3['act_id'];
  ?>
  <tr><th><?php echo $countsno; ?></th><th><?php echo act_jargon(htmlspecialchars($judgerow3['act_name'])); ?></th>
  
  <?php
  $qry1=$dbo->prepare("select e_master_bench_id from public.e_master_bench");
   //print_r($qry1);
   $qry1->execute();
   $countltot = 0;
  while ($judgerow1 = $qry1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	  $bench_id = $judgerow1['e_master_bench_id'];
  //get data for territorial_jurisdiction
  $qry2=$dbo->prepare("select territorial_jurisdiction_name, e_master_territorial_jurisdiction_id from public.e_master_territorial_jurisdiction where e_master_bench_id = '$bench_id'");
   //print_r($qry2);
   $qry2->execute();
  while ($judgerow2 = $qry2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	  $ter_jur_id = $judgerow2['e_master_territorial_jurisdiction_id'];
	  //print_r($ter_jur_id);
	  
	  //get data from e_case_details
  $qry4=$dbo->prepare("select filing_no from public.e_case_detail where territorial_jurisdiction = '$ter_jur_id' and act_id = '$act_id' and dt_of_filing between '$prev' and '$next'");
   //print_r($qry4);
   $qry4->execute();
   $countdata = 0;
  while ($judgerow4 = $qry4->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	  if($judgerow4['filing_no']!='' && $judgerow4['filing_no']!='NA'){
		$countdata++;  
	  }
  }
  ?>
  <td><?php echo $countdata; ?></td>
  <?php
  $countltot = $countltot + $countdata;
  }
  }
  ?>
  <td><?php echo $countltot; ?></td>
  </tr>
  <?php
  $countsno ++;
  }
  ?>
  
  <!-- start of code for total(bottom) -->
  <!-- <tr><th colspan = "2">Total</th><tr> -->
    <!-- end of code for total(bottom) -->
  
  <!-- End of code for data in table -->
  
   </table>
</div>
  <?php  
  include '../bfooter.php';
  ?>

<!--<script>
$('#t1 select').on('change', function() {
$('option').prop('disabled', false);
$('#t1 select').each(function() {
var val = this.value;
$('#t1 select').not(this).find('option').filter(function() {
return this.value === val;
}).prop('disabled', true);
});
}).change();
 $('.timepicker').timepicker({      showInputs: false    })
</script>-->
  <?php }
function bench_jargon($bname){
	$bname = explode(" ",$bname); 
	return  $bname[4];
}

function territorial_jargon($ter_name){
	$ter_name = explode(" ",$ter_name);
	return end($ter_name);
}

function act_jargon($i){
	  if($i == 'Company Petition')
		  return 'CP';
  else if($i == 'Merger and Amalgamation')
	  return 'M&A';
  else if($i == 'IBC Act')
	  return 'IBC';
  else
	  return 'Not found!';
}

function for_month($j){
$month_arr = explode("/",$j);
$month_arr = $month_arr[1];
if($month_arr == 1)
return 'January';
if($month_arr == 2)
return 'Febuary';
if($month_arr == 3)
return 'March';
if($month_arr == 4)
return 'April';
if($month_arr == 5)
return 'May';
if($month_arr == 6)
return 'June';
if($month_arr == 7)
return 'July';
if($month_arr == 8)
return 'August';
if($month_arr == 9)
return 'September';
if($month_arr == 10)
return 'October';
if($month_arr == 11)
return 'November';
if($month_arr == 12)
return 'December';
}

function get_month($k){
$month_arr = explode("/",$k);
return $month_arr = $month_arr[1];
  }
  
  function get_year($l){
$month_arr = explode("/",$l);
return $month_arr = $month_arr[2];
  }

  ?>