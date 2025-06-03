<?php 
//print_r($_POST);die();
if(isset($_POST[submit1]))
{
$prev = htmlentities($_POST[prev_list_date]);
$next = htmlentities($_POST[next_list_date]);
}
list($d,$m,$y) = explode('/',$prev);
	  $prev =$y.'-'.$m.'-'.$d;
	 
	  list($d,$m1,$Y) = explode('/',$next);
	  $next =$Y.'-'.$m1.'-'.$d;
//variables to hold value for fresh filing
$m = get_month($prev);
$y = get_year($prev);

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
//$schemas='public';
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
<script type="text/javascript">     
    function PrintDiv() {    
       var divToPrint = document.getElementById('divToPrint');
       var popupWin = window.open('', '_blank', 'width=300,height=300');
       popupWin.document.open();
       popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
            }
 </script>
<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
    padding: 5px;
    text-align: center;
}
</style>
<script>
function myFunction() {
    window.print();
}
</script>
</head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <title>MIS Report</title>
    
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="divToPrint">
    <!-- Content Header (Page header) -->
    <!--<section class="content-header">
      <h1>
        <center>Institution, Disposal and Pendency for the Month of
        </center>
      </h1>
      
    </section>-->
	
		<?php
		//to get data from db for benches
   $qry1=$db->prepare("select bench_name, location_id from public.mater_location_bench order by location_id desc");
   $qry1->execute();

//to get data from db for acts
   //$qry2=$db->prepare("select act_name from public.master_act");
   //$qry2->execute();
   
   //to get data from db for acts
   //$qry3=$db->prepare("select act_id from public.master_act");
   //$qry3->execute();
   
   //to get data from e_case_detail
   //$qry4=$dbo->prepare("select * from public.e_case_detail");
   //$qry4->execute();
   //$judgerow4 = $qry4->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT);
   //print_r($judgerow4);
   ?>
   
 <!--<p> <h4><center>All <font color="red">*</font></span> is mandatory Field </center></h4></p>-->
 <h4><button onclick="PrintDiv();" >Print</button></h4>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">
  <tr>
    <th colspan="16" align="center">National Company Law Tribunal</th>
  </tr>
  <tr>
    <th colspan="16" align="center">Institution, Disposal and Pendency for the Period of <?php echo $prev; ?> To <?php echo $next; ?></th>
  </tr>
  <tr>
    <th rowspan="2">Bench Name</th>
    <th colspan="3">Pending as on <?php echo $prev; ?></th>
    <th colspan="3">Recieved on Transfer</th>
	<th colspan="3">Fresh Filing</th>
	<th colspan="3">Disposal</th>
	<th colspan="3">Total Pendency</th>
  </tr>
  <tr>
  <?php
  $c=5;
  while($c!=0){
	  //to get data from db for acts
   $qry2=$db->prepare("select act_name from public.master_act");
   $qry2->execute();
  while ($judgerow2 = $qry2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {	 
 ?>
 
    <td><?php echo act_jargon($judgerow2['act_name']); ?></td>
	
		<?php
	}
	$c = $c-1;
	//print_r($c);
  }
	?>
  </tr>
  <?php
  
  //initialize variables for total
$countcp1t = 0;
$countma1t = 0;
$countibc1t = 0;

  while ($judgerow1 = $qry1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {	
 ?>
  <tr>
    <th><?php $bname = htmlspecialchars($judgerow1['bench_name']);
	$bname = explode(" ",$bname); echo  $bname[4]." ".$bname[5]; ?></th>
	
	<?php
	
	//start of code for Pending as on
	
	//to get data from db for acts
   $qry3=$db->prepare("select act_id from public.master_act");
   $qry3->execute();
  while ($judgerow3 = $qry3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	  //print_r($judgerow3);
	  $count1 = 0;
	  //to get data from e_case_detail
      $qry4=$dbo->prepare("select * from public.e_case_detail where dt_of_filing between '$prev' and '$next'");
      $qry4->execute();
	while ($judgerow4 = $qry4->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	  //print_r($judgerow4);
	  $m = 1;
	  $y = 2018;
     	if($judgerow3['act_id'] == $judgerow4['act_id'] && $judgerow4['filing_no']!='NA' && $judgerow4['filing_no']!='' && $judgerow4['location_id'] == $judgerow1['location_id']){
			//$count = $count+1;
			//get data from e_case_detail table of newncltdb
			$qry6=$db->prepare("select * from delhi.case_detail WHERE EXTRACT(MONTH FROM delhi.case_detail.regis_date) = '$m' and EXTRACT(YEAR FROM delhi.case_detail.regis_date) = '$y'");
            $qry6->execute();
			while ($judgerow6 = $qry6->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	  if($judgerow4['filing_no'] == $judgerow6['filing_no']){
			$count1 = $count1+1;
	  }
  }
			
		}	
  }		
 ?>	
    <td><?php echo $count1; ?></td>
  <?php
  // code for total pendency calculation
  if($judgerow3['act_id']==1){
	  $countcp1 = $count1;
  
  //code for total calculation
      $countcp1t = $countcp1t + $countcp1;
  }
  if($judgerow3['act_id']==2){
	  $countma1 = $count1;
  
  //code for total calculation
      $countma1t = $countma1t + $countma1;
  }
  if($judgerow3['act_id']==3){
	  $countibc1 = $count1;
  
  //code for total calculation
      $countibc1t = $countibc1t + $countibc1;
  }
  }
  
  //start of code for Received on transfer
  
  //to get data from db for acts
   $qry3=$db->prepare("select act_id from public.master_act");
   $qry3->execute();
  while ($judgerow3 = $qry3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	  //print_r($judgerow3);
	  $count2 = 0;
	  //to get data from e_case_detail
      $qry4=$dbo->prepare("select * from public.e_case_detail where dt_of_filing between '$prev' and '$next'");
      $qry4->execute();
	while ($judgerow4 = $qry4->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	  //print_r($judgerow4);
     	//if($judgerow3['act_id'] == $judgerow4['act_id'] && $judgerow4['filing_no']!='NA' && $judgerow4['filing_no']!='' && $judgerow4['location_id'] == $judgerow1['location_id']){
			//$count = $count+1;
		//}	
  }		
  ?>	
    <td><?php echo $count2; ?></td>
  <?php
  
  //code for total pendency calculation
  if($judgerow3['act_id']==1)
	  $countcp2 = $count2;
  if($judgerow3['act_id']==2)
	  $countma2 = $count2;
  if($judgerow3['act_id']==3)
	  $countibc2 = $count2;
  }
  
  //start of code for Fresh Filling
  
  //to get data from db for acts
   $qry3=$db->prepare("select act_id from public.master_act");
   $qry3->execute();
  while ($judgerow3 = $qry3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	  //print_r($judgerow3);
	  $count3 = 0;
	  //to get data from e_case_detail
      $qry4=$dbo->prepare("select * from public.e_case_detail WHERE EXTRACT(MONTH FROM public.e_case_detail.dt_of_filing) = '$m' and EXTRACT(YEAR FROM public.e_case_detail.dt_of_filing) = '$y'");
      $qry4->execute();
	while ($judgerow4 = $qry4->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	  //print_r($judgerow4);
     if($judgerow3['act_id'] == $judgerow4['act_id'] && $judgerow4['filing_no']!='NA' && $judgerow4['filing_no']!='' && $judgerow4['location_id'] == $judgerow1['location_id']){
//$count = $count+1;
			//get data from scrutiny table
			$qry7=$db->prepare("select * from delhi.scrutiny");
            $qry7->execute();
			while ($judgerow7 = $qry7->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	  if($judgerow4['filing_no'] == $judgerow7['filing_no']){
			$count3 = $count3+1;
	  }
  }
		}	
  }		
  ?>	
    <td><?php echo $count3; ?></td>
  <?php
  
  //code for total pendency calculation
  if($judgerow3['act_id']==1)
	  $countcp3 = $count3;
  if($judgerow3['act_id']==2)
	  $countma3 = $count3;
  if($judgerow3['act_id']==3)
	  $countibc3 = $count3;
  }
  
   //start of code for Disposal
  
  //to get data from db for acts
   $qry3=$db->prepare("select act_id from public.master_act");
   $qry3->execute();
  while ($judgerow3 = $qry3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	  //print_r($judgerow3);
	  $count4 = 0;
	  //to get data from e_case_detail
      $qry4=$dbo->prepare("select * from public.e_case_detail where dt_of_filing between '$prev' and '$next'");
      $qry4->execute();
	while ($judgerow4 = $qry4->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	  //print_r($judgerow4);
     if($judgerow3['act_id'] == $judgerow4['act_id'] && $judgerow4['filing_no']!='NA' && $judgerow4['filing_no']!='' && $judgerow4['location_id'] == $judgerow1['location_id']){
			//get data from case_disposal table
			$qry5=$db->prepare("select * from delhi.case_disposal");
            $qry5->execute();
			while ($judgerow5 = $qry5->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	  if($judgerow4['filing_no'] == $judgerow5['filing_no']){
			$count4 = $count4+1;
	  }
  }
		}	
  }		
  ?>	
    <td><?php echo $count4; ?></td>
  <?php
  
  //code for total pendency calculation
  if($judgerow3['act_id']==1)
	  $countcp4 = $count4;
  if($judgerow3['act_id']==2)
	  $countma4 = $count4;
  if($judgerow3['act_id']==3)
	  $countibc4 = $count4;
  }
  
  
  //start of code for Total Pendency
	
	//to get data from db for acts
   $qry3=$db->prepare("select act_id from public.master_act");
   $qry3->execute();
  while ($judgerow3 = $qry3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
	  //print_r($judgerow3);
	  //$count = 0;
	  //to get data from e_case_detail
      $qry4=$dbo->prepare("select * from public.e_case_detail where dt_of_filing between '$prev' and '$next'");
      $qry4->execute();
	//while ($judgerow4 = $qry4->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 // {
	  //print_r($judgerow4);
     	//if($judgerow3['act_id'] == $judgerow4['act_id'] && $judgerow4['filing_no']!='NA' && $judgerow4['filing_no']!='' && $judgerow4['location_id'] == $judgerow1['location_id']){
			//$count = $count+1;
			//get data from e_case_detail table of newncltdb
			//$qry6=$db->prepare("select * from delhi.case_detail");
           // $qry6->execute();
			//while ($judgerow6 = $qry6->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  //{
	  //if($judgerow4['filing_no'] == $judgerow6['filing_no']){
			//$count = $count+1;
	  //}
 // }
			
		//}	
 // }	
 
 //code for total pendency calculation
if($judgerow3['act_id']==1)
	  $countall = $countcp1+$countcp2+$countcp3-$countcp4; 
  if($judgerow3['act_id']==2)
	  $countall = $countma1+$countma2+$countma3-$countma4;
  if($judgerow3['act_id']==3)
	  $countall = $countibc1+$countibc2+$countibc3-$countibc4;
 ?>	
    <td><?php echo $countall; ?></td>
  <?php
  }
  ?>
  </tr>
  <?php
  }
  ?>
  <!--<tr><th>Total</th><td><?php echo $countcp1t; ?></td><td><?php echo $countma1t; ?></td><td><?php echo $countibc1t; ?></td></tr>-->
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
  <?php  
  }
function act_jargon($i){
	  if($i == 'Company Petition')
		  return 'Others';
  else if($i == 'Merger and Amalgamation')
	  return 'M&A';
  else if($i == 'IBC Act')
	  return 'IBC';
  else
	  return 'Not found!';
}

/*function for_month($j){
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
}*/
  
  function get_month($k){
$month_arr = explode("/",$k);
return $month_arr = $month_arr[1];
  }
  
  function get_year($l){
$month_arr = explode("/",$l);
return $month_arr = $month_arr[2];
  }
  ?>