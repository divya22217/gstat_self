<?php
// File used to connect to database
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");

include("../db_inc1.php");
include("../db_inc2.php");

require_once('../SrcCauselist/Causelist.php');

$date = htmlspecialchars(date("d/m/Y"));
$date1 = htmlspecialchars(date("F j, Y g:i a"));
$msg_ip = isset($_REQUEST['msg_ip']) ? $_REQUEST['msg_ip'] :'';
$year=htmlspecialchars(date("Y"));
$msg_ip .= "User IP : ".$_SERVER["REMOTE_ADDR"]."\r\n"; //Sender's IP



// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	// If they are not, we redirect them to the login page.
	// Remember that this die statement is absolutely critical.  Without it,
	// people can view your members-only content without logging in.
	die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
	echo "you Can't access this page";
}
else
 {
	
	
	
function remove_path($file, $path = UPLOAD_PATH) {
if(strpos($file, $path) !== FALSE) {
return substr($file, strlen($path));
}
}

$frm = md5( uniqid('auth', true) );

/*** set the session form token ***/
$_SESSION['form_token'] = $frm;//csrf

$schemas=htmlspecialchars($_SESSION['schema_name']);

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>NCLT | Dashboard</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.7 -->
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



	

	<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
	<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
	<script src="../plugins/jQueryUI/jquery-ui.js"></script>
	<script src="../plugins/jQueryUI/date.js"></script>
	
<link rel="stylesheet" type="text/css" href="../includes/highslide/highslide.css" />
<script type="text/javascript" src="../includes/highslide/highslide-with-html1.js"></script>
	<script language="javascript">
		function change(id, newClass)
		{
			identity=document.getElementById(id);
			identity.className=newClass;

		}
		function printPage()
		{
			change("testdiv","hidden");
			window.print();
		}

		function popsurety_pet_adv_name(cfy)

		{

			var url = "../public/details.php?filing_no="+cfy;
			var width = 800;
			var height = 1300;

			var left = (screen.width-width)/2;

			var top = (screen.height - height)/2;

			var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left+ ',resizable=1';

			window.open(url,"print",params);
		}





		function popsurety_pet_adv_name2(cfy)

		{

			var url = cfy;
			var width = 800;
			var height = 1300;

			var left = (screen.width-width)/2;

			var top = (screen.height - height)/2;

			var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left+ ',scrollbars=1';

			window.open(url,"print",params);
		}




	</script>
	<script>
		function submitForm()
		{
			with(document.frm)
			{


				if(next_list_date.value == "")
				{
					alert("Enter ORDER DATE....");
					next_list_date.value='';
					next_list_date.focus();
					return false;
				}

				action="judgement_report.php";
				submit();
				document.frm.submit1.disabled = true;
				document.frm.submit1.value = 'Please Wait...';
				return true;
			}

		}



	</script>

	<style>
		table, td, th {
			border: 1px solid #1d99d4;
		}



		th {
			background-color: #074c62;
			color: white;
		}
	</style>
	<?php 
	 
  
   
  $itemno='01';
 // $applno='1010000007632017';
    $courtno='09';
    $caseno='/08/DLKSL934033/Delhi/2017' ;
    $casetype='NCLT';
    $partyname='MMK';
    $title='DMS';
    //$status='P';	
    $j_key='vVl/Az1yGsjOAG18WDeScg=='; 
    $j_securityKey='!TZFIMZbTiUtqXMfARJ1DGgyNicWFwYkwTA0ip/Q8Wns=';
?>
	 <script >
	function submitForm2()
{
	document.getElementById('next_list_date').value = '';
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
		
		action = "judgement_report.php";
		submit();
	}
} 
	 
	 
	 
	function OpenDMSForm(url,val1,val2,val3,val4,val5,val6,val7,val8,val9,val10)
{
document.getElementById("frm").action=url;
document.getElementById("itemno1").value=val1;
document.getElementById("applno1").value=val2;
document.getElementById("courtno1").value=val3;
document.getElementById("caseno1").value=val4;
document.getElementById("casetype1").value=val5;
document.getElementById("partyname1").value=val6;
document.getElementById("title1").value=val7;
document.getElementById("status1").value=val8;
document.getElementById("j_key1").value=val9;
document.getElementById("j_securityKey1").value=val10;
document.getElementById("frm").submit();
}
 </script> 
 <!--
 <form action="http://efiling.nclt.gov.in/dms-ecourt/ecourt-order-search-within-dms" method="POST"  target="_blank"id="dms">
    <input type="hidden" name="itemno"  value="01"/>
    <input type="hidden" name="applno" value="1010000007632017"/>
    <input type="hidden" name="courtno" value="09"/>
    <input type="hidden" name="caseno" value="/08/DLKSL934033/Delhi/2017" />
    <input type="hidden" name="casetype" value="NCLT"/>
    <input type="hidden" name="partyname" value="MMK">
    <input type="hidden" name="title" value="DMS">
    <input type="hidden" name="status" value="P">	
    <input type="hidden" name="j_key" value="vVl/Az1yGsjOAG18WDeScg=="> 
    <input type="hidden" name="j_securityKey" value="!TZFIMZbTiUtqXMfARJ1DGgyNicWFwYkwTA0ip/Q8Wns=">
   
</form>
-->
 <!--
 <form action="" method="POST" target="_blank" id="frm">
    <input type="hidden" id="itemno1" name="itemno"  value=""/>
    <input type="hidden" id="applno1" name="applno" value=""/>
    <input type="hidden" id="courtno1" name="courtno" value=""/>
    <input type="hidden" id="caseno1" name="caseno" value="" />
    <input type="hidden" id="casetype1" name="casetype" value=""/>
    <input type="hidden" id="partyname1" name="partyname" value="">
    <input type="hidden" id="title1"name="title" value="">
    <input type="hidden" id="status1" name="status" value="">	
    <input type="hidden" id="j_key1"name="j_key" value=""> 
    <input type="hidden" id="j_securityKey1" name="j_securityKey" value="">
   
</form>-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<form action="" method="POST" target="_blank" id="frm">
    <input type="hidden" id="itemno1" name="itemno"  value=""/>
    <input type="hidden" id="applno1" name="applno" value=""/>
    <input type="hidden" id="courtno1" name="courtno" value=""/>
    <input type="hidden" id="caseno1" name="caseno" value="" />
    <input type="hidden" id="casetype1" name="casetype" value=""/>
    <input type="hidden" id="partyname1" name="partyname" value="">
    <input type="hidden" id="title1"name="title" value="">
    <input type="hidden" id="status1" name="status" value="">	
    <input type="hidden" id="j_key1"name="j_key" value=""> 
    <input type="hidden" id="j_securityKey1" name="j_securityKey" value="">
   
</form>
<?php include("../includes/banner.php");?>
<div class="wrapper">

	<?php include("../includes/header.php");
	include '../insidebar.php';
	?>

	<div class="content-wrapper" style="min-height: 946px;">
		<!-- Content Header (Page header) -->
		<section class="content">


<table class="table">
<tr>
	<th valign="top" align="center" colspan="16">
					<b><font face="Verdana" size="3"><u>Judgements/Orders Uploaded Report</u></font> </b>
	</th>
	</tr>
<form name="frm" method="post" action="judgement_report.php">

<tr><td colspan="16"></td></tr>

<?php 
       $bench_type  = htmlentities(htmlspecialchars($_REQUEST['bench_type']));
       $case_type  = htmlentities(htmlspecialchars($_REQUEST['case_type']));
       $case_no    = htmlentities(htmlspecialchars($_REQUEST['case_no']));
       $case_year  = htmlentities(htmlspecialchars($_REQUEST['case_year']));

 $next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] :''; ?>
<tr><td colspan="16"><font color="red">*</font><font size="1">LISTING DATE:</font>
<input type="text" id="next_list_date" name="next_list_date" class="datepickerToday"
readonly="readonly" size="8" autocomplete="off" maxlength="10" value="<?php print htmlspecialchars($next_list_date); ?>" />

<input id="submit1" type="button"  name="submit1" value="SEARCH"  onClick="return submitForm();" />
 </br>
 </td></tr>

 <tr><td colspan="7"><font><center><b>Or</b></font></center></td></tr>
 
 <?php $sql="select * from case_type where display='Y' order by case_type_desc ASC"; ?>
<tr>
 
<td   align="left" colspan="7">
		<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Bench:
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


	<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Case Type
<select name="case_type" style="width:200px" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
<option>select</option>
<?php

foreach($dbh->query($sql) as $row)
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
 }
 ?>

</select><font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Case No
			<input type="text"  maxlength="7" size="8" name="case_no" value="<?php print htmlentities(htmlspecialchars($case_no)); ?>" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Year
<input type="text"  maxlength="4" size="4" name="case_year" value="<?php print htmlentities(htmlspecialchars($case_year)); ?>" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
	<input type="button" name="button" value="SEARCH" size="20" onClick="return submitForm2();">
</td>


</tr>
 
 
<tr >
<th>Case No.</th>
<th>
Party Detail
</th>
<th>Order Type</th>
<?php
if($case_type && $case_year && $case_no){
	?>
<th>Judgement Date</th>
<?php } ?>
<th>Upload Date & Time</th>
<th>Remarks</th>
<th><font><center>View Order</center></font></th>
<!--<th>View </th>-->

</tr>
 
 <?php
  if($next_list_date){
 list($day,$month,$year)=explode('/',$next_list_date);
 $list_cdate=$year.'-'.$month.'-'.$day;
 //echo "select * from $schemas.order_detail det, $schemas.daily_order dor where det.filing_no = do.filing_no and det.order_date='$list_cdate'";
 $stnq = $db->prepare("select * from delhi.order_detail det, delhi.order_daily dor where det.filing_no = dor.filing_no and dor.order_date=?");
 $stnq->bindParam(1, $list_cdate, PDO::PARAM_INT);
 $stnq->execute();

 if($stnq->rowCount()==0)
 {
 ?>
 <tr>
<td align="center" colspan="16" ><font color="red" size="4"><b>No Record Found </b></font></td>
</tr>
 <?php
 } 
 if($stnq->rowCount()>0)
 {
$counter=1;


 while ($rw2 = $stnq->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 {
	extract($rw2);
	
 	if($filing_no !='')
 	{
 	$stcn = $db->prepare("select * from $schemas.case_detail where filing_no=?");
 	$stcn->bindParam(1, $filing_no, PDO::PARAM_STR);
 	$stcn->execute();
 	while ($rw2 = $stcn->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 	{
 		$filing_no98 = htmlspecialchars($rw2['filing_no']);
    	$case_no=htmlspecialchars($rw2['case_no']);
 		$pt_name=htmlspecialchars($rw2['pet_name']);
 		$rs_name=htmlspecialchars($rw2['res_name']);
		
 		//$pet_code=htmlspecialchars($rw2['pet_org_type']);
 		//$res_code=htmlspecialchars($rw2['res_org_type']);
 		$pet_type=htmlspecialchars($rw2['pet_type']);
 		$res_type=htmlspecialchars($rw2['res_type']);
 		$case_type=htmlspecialchars($rw2['case_type']);
 		$case_year=htmlspecialchars($rw2['case_year']);
		$location_code=htmlspecialchars($rw2['location_code']);
		$st1 = $db->prepare("select * from $schemas.bench_location where bench_location_code=?");
		$st1->execute(array($location_code));
		$bech_data= $st1->fetch();
		$bech_code = $bech_data['short_name'];

 		
 	
 	}
 	
 	if($filing_no98 !='')
 	{
 		if($case_type > 0)
 		{
 			$stQ = $db->prepare("select case_type_desc from case_type where id = ?");
 			$stQ->bindParam(1, $case_type, PDO::PARAM_STR);
 			$stQ->execute();
 			$case_type_short_name=$stQ->fetchColumn();
			
			$stQ = $db->prepare("select case_type_desc from case_type where id = ?");
 			$stQ->bindParam(1, $case_type, PDO::PARAM_STR);
 			$stQ->execute();
 			$case_type_short_name=$stQ->fetchColumn();
			$case_no_dms = htmlspecialchars(strtoupper($case_type_short_name).'/'.$case_no.'/'.$case_year);
			
			$causelist = new Causelist();
			$fileupload = $causelist->getFileupload($filing_no, $dbonline, $case_type); 
 		}
		
		    $order_type_q = $db->prepare("select order_type from $schemas.order_detail where filing_no = ?");
 			$order_type_q->bindParam(1, $filing_no98, PDO::PARAM_STR);
 			$order_type_q->execute();
 			$order_type=$order_type_q->fetchColumn();
 		
 	 	
 	}

 	}
 	//$hash=base64_encode($item_no);
	 $hash2 = base64_encode($filing_no.'/'.$schemas); //is used to view page
 


 ?>
<tr>
<td  > <?php echo$counter.'.';?><a href="javascript:popsurety_pet_adv_name('<?php print htmlspecialchars($hash2); ?>');">



<?php $causelist = new Causelist();
$case_nom = $causelist->nclt_case_no($filing_no, $db, $schemas);
echo $case_nom;

?>


</a></td>
<td>
<?php 

		echo "<h7><font color='red'><center>";
		if($pt_name!=''){echo htmlspecialchars($pt_name);}
	echo "</font><br><font color='blue'>VS</font><br><font color='red'>";
		if($rs_name!=''){echo htmlspecialchars($rs_name);}
	
	echo "</center></font></h7>";
		?>
</td>
<td>
<?php 
if($order_type=='I')
{
	$order_type="Interim";
}
if($order_type=='F')
{
	$order_type="Final";
}

echo htmlspecialchars($order_type);?>
</td>
<td>
<?php 
 


echo htmlspecialchars($entry_date);?>
</td>
<td>
<?php 
echo htmlspecialchars($remarks);
//$court_no = 1; ?>
</td>
<td><a  onclick="OpenDMSForm('https://efiling.nclt.gov.in/dms-ecourt/ecourt-search-within-dms','<?php echo $counter; ?>','<?php echo $fileupload; ?>',
'<?php echo $court_no; ?>','<?php echo $case_no_dms;?>','','<?php echo $pt_name ?>','<?php echo $pt_name."   "; ?>Vs.<?php echo "   ".$rs_name;?>','P',
'vVl/Az1yGsjOAG18WDeScg==','!TZFIMZbTiUtqXMfARJ1DGgyNicWFwYkwTA0ip/Q8Wns=')" style="cursor: pointer">
<font><center><img alt="Qries" src="docs.png"
         width="50" height="60"></font></a>
</td>

  
 
  <td>
 <?php 
	 $hash2 = base64_encode($filing_no.'/'.$schemas); //is used to view page

			$sthr=$dbonline->prepare("select * from e_case_detail  where filing_no=? ");
  $sthr->bindParam(1, $filing_no, PDO::PARAM_STR);
  $sthr->execute();
  while ($rowa = $sthr->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  	 $applno=$rowa['unique_id_no'];
  	  
  } 
  ?>
  
  


<!--<a  onclick="OpenDMSForm('<?php// echo $applno; ?>')" style="cursor: pointer">

<font color="#900C3F" size="3">&nbsp;&nbsp;
  &nbsp;&nbsp;View</a>


            </td>


</tr>
-->
<?php 

 //while loop end all query....
$counter++;
 }
 }
  }elseif($case_no && $case_year & $case_type){
	  
	  //-----------------------*----------------------------------------//
 $stnq = $db->prepare("select * from $schemas.order_detail where case_type=? and case_no=? and case_year=? ");
 $stnq->bindParam(1, $case_type, PDO::PARAM_INT);
 $stnq->bindParam(2, $case_no, PDO::PARAM_INT);
 $stnq->bindParam(3, $case_year, PDO::PARAM_INT);
 $stnq->execute();

 if($stnq->rowCount()==0)
 {
 ?>
 <tr>
<td align="center" colspan="16" ><font color="red" size="4"><b>No Record Found </b></font></td>
</tr>
 <?php
 } 
 if($stnq->rowCount()>0)
 {
$counter=1;


 while ($rw2 = $stnq->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 {
	extract($rw2);
	
 	if($filing_no !='')
 	{
 	$stcn = $db->prepare("select * from $schemas.case_detail where filing_no=? and location_code=?");
 	$stcn->bindParam(1, $filing_no, PDO::PARAM_STR);
	$stcn->bindParam(2, $bench_type, PDO::PARAM_STR);
 	$stcn->execute();
 	while ($rw2 = $stcn->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 	{
 		$filing_no98 = htmlspecialchars($rw2['filing_no']);
    	$case_no=htmlspecialchars($rw2['case_no']);
 		$pt_name=htmlspecialchars($rw2['pet_name']);
 		$rs_name=htmlspecialchars($rw2['res_name']);
		
 		//$pet_code=htmlspecialchars($rw2['pet_org_type']);
 		//$res_code=htmlspecialchars($rw2['res_org_type']);
 		$pet_type=htmlspecialchars($rw2['pet_type']);
 		$res_type=htmlspecialchars($rw2['res_type']);
 		$case_type=htmlspecialchars($rw2['case_type']);
 		$case_year=htmlspecialchars($rw2['case_year']);
		$location_code=htmlspecialchars($rw2['location_code']);
		$st1 = $db->prepare("select * from $schemas.bench_location where bench_location_code=?");
		$st1->execute(array($location_code));
		$bech_data= $st1->fetch();
		$bech_code = $bech_data['short_name'];

 		
 	
 	}
 	
 	if($filing_no98 !='')
 	{
 		if($case_type > 0)
 		{
 			$stQ = $db->prepare("select case_type_desc from case_type where id = ?");
 			$stQ->bindParam(1, $case_type, PDO::PARAM_STR);
 			$stQ->execute();
 			$case_type_short_name=$stQ->fetchColumn();
			
			$stQ = $db->prepare("select case_type_desc from case_type where id = ?");
 			$stQ->bindParam(1, $case_type, PDO::PARAM_STR);
 			$stQ->execute();
 			$case_type_short_name=$stQ->fetchColumn();
			$case_no_dms = htmlspecialchars(strtoupper($case_type_short_name).'/'.$case_no.'/'.$case_year);
			
			$causelist = new Causelist();
			$fileupload = $causelist->getFileupload($filing_no, $dbonline, $case_type); 
 		}
 		
 	 	
 	}

 	}
 	//$hash=base64_encode($item_no);
	 $hash2 = base64_encode($filing_no.'/'.$schemas); //is used to view page
 


 ?>
<tr>
<td  > <?php echo$counter.'.';?><a href="javascript:popsurety_pet_adv_name('<?php print htmlspecialchars($hash2); ?>');">



<?php 
$causelist = new Causelist();
$case_nom = $causelist->nclt_case_no($filing_no, $db, $schemas);
echo $case_nom;
?>


</a></td>
<td>
<?php 

		echo "<h7><font color='red'><center>";
		if($pt_name!=''){echo htmlspecialchars($pt_name);}
	echo "</font><br><font color='blue'>VS</font><br><font color='red'>";
		if($rs_name!=''){echo htmlspecialchars($rs_name);}
	
	echo "</center></font></h7>";
		?>
</td>
<td>
<?php 
if($order_type=='I')
{
	$order_type="Interim";
}
if($order_type=='F')
{
	$order_type="Final";
}

echo htmlspecialchars($order_type);?>
</td>


<td>
<?php
$judg_date = htmlspecialchars($date_of_order);
list($year,$month,$day)=explode('-',$judg_date);
$judg_date=$day.'/'.$month.'/'.$year;
 echo htmlspecialchars($judg_date);?>
</td>
<td>
<?php 
 


echo htmlspecialchars($entry_date);?>
</td>
<td>
<?php 
echo htmlspecialchars($remarks);
//$court_no = 1; ?>
</td>
<td><a  onclick="OpenDMSForm('https://efiling.nclt.gov.in/dms-ecourt/ecourt-search-within-dms','<?php echo $counter; ?>','<?php echo $fileupload; ?>',
'<?php echo $court_no; ?>','<?php echo $case_no_dms;?>','','<?php echo $pt_name ?>','<?php echo $pt_name."   "; ?>Vs.<?php echo "   ".$rs_name;?>','P',
'vVl/Az1yGsjOAG18WDeScg==','!TZFIMZbTiUtqXMfARJ1DGgyNicWFwYkwTA0ip/Q8Wns=')" style="cursor: pointer">
<font><center><img alt="Qries" src="docs.png"
         width="50" height="60"></font></a>
</td>

  
 
  </tr>
 <?php 
	 $hash2 = base64_encode($filing_no.'/'.$schemas); //is used to view page

			$sthr=$dbonline->prepare("select * from e_case_detail  where filing_no=? ");
  $sthr->bindParam(1, $filing_no, PDO::PARAM_STR);
  $sthr->execute();
  while ($rowa = $sthr->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
  {
  	 $applno=$rowa['unique_id_no'];
  	  
  } 
  ?>
  
  


<!--<a  onclick="OpenDMSForm('<?php// echo $applno; ?>')" style="cursor: pointer">

<font color="#900C3F" size="3">&nbsp;&nbsp;
  &nbsp;&nbsp;View</a>


            </td>


</tr>
-->
<?php 

 //while loop end all query....
$counter++;
 }
 }  
  }
?>

</table>
	 </div>

</section>
<?php include '../includes/footer.php'; ?>
<div class="control-sidebar-bg"></div>

</div>
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
<script language="javascript">

    hs.graphicsDir = '../includes/highslide/graphics/';
    hs.outlineType = 'rounded-white';
    hs.wrapperClassName = 'draggable-header';


</script>


</body>
</html>

<?php
//count loop End
?>
<?php } ?>
