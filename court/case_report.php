<?php
require_once('../SrcCauselist/Causelist.php');
$causelist = new Causelist();
// File used to connect to database
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");
include("../db_inc1.php");
include("../db_inc2.php");
$date = htmlspecialchars(date("d/m/Y"));
$date1 = htmlspecialchars(date("F j, Y g:i a"));
$year=htmlspecialchars(date("Y"));

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
	echo "you Can't access this page";
}
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

	</script>
	<script>
		function submitForm()
		{
			with(document.frm)
			{


				if(search_case_type.value == "")
				{
					alert("Please Select Case Type...");
					search_case_type.value='';
					search_case_type.focus();
					return false;
				}

				action="case_report.php";
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
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php include("../includes/banner.php");?>
<div class="wrapper">

	<?php include("../includes/header.php");
	include '../insidebar.php';
	?>

	<div class="content-wrapper" style="min-height: 946px;">
		<!-- Content Header (Page header) -->
		<section class="content">


<table>
<tr>
	<th valign="top" align="center" colspan="16">
					<b><font face="Verdana" size="3"><u>CASE REGISTRATION REPORT</u></font> </b>
	</th>
	</tr>
	
<form name="frm" method="post" action="case_report.php">

<tr><td colspan="16"></td></tr>
<?php
$s_year='';
$from_daterr='';
$to_daterr='';
$search_case_type='';
?>


<?php  $s_year = isset($_REQUEST['s_year']) ? $_REQUEST['s_year'] :''; ?>
<tr>
<td colspan="2">
Registration Year</td>
<td>
<!-- displaying the dropdown list -->
<select name="s_year">
    <option value="">Select Year</option>
    <?php
    $sql2=" select distinct(reg_year) from $schemas.case_type_reg  where reg_year >'2019' order by reg_year asc";

foreach($dbh->query($sql2) as $row)
{

  $s_year1=$row['reg_year'];
if($s_year1 == $s_year)
                {
		print "<option value=".htmlentities(htmlspecialchars($row['reg_year']))." selected>".strtoupper(htmlentities(htmlspecialchars($row['reg_year'])))."</option>";
                }
           else
                {
                print "<option value=".htmlentities(htmlspecialchars($row['reg_year'])).">".strtoupper(htmlentities(htmlspecialchars($row['reg_year'])))."</option>";
		}
}

$from_daterr = isset($_REQUEST['from_date']) ? $_REQUEST['from_date'] :'';

$to_daterr = isset($_REQUEST['to_date']) ? $_REQUEST['to_date'] :'';
if(!empty($from_daterr))
{
list($day,$month,$year)=explode('/',$from_daterr);
    $from_date1rr=$year.'-'.$month.'-'.$day;
}
	if(!empty($to_daterr))
{
	list($day,$month,$year)=explode('/',$to_daterr);
    $to_date1rr=$year.'-'.$month.'-'.$day;
}

    ?>	
</td>
<?php 
//print_r($from_date1rr); 
?>
<td colspan="3">Registration Date from:
<input type="text"  name="from_date" class="datepicker"
 size="8" autocomplete="off" maxlength="10" value="<?php print htmlspecialchars($from_daterr); ?>" />
</td>
<td colspan="3">Registration Date To :
<input type="text"  name="to_date" class="datepicker"
size="8" autocomplete="off" maxlength="10" value="<?php print htmlspecialchars($to_daterr); ?>" />
</td>
<?php  $search_case_type = isset($_REQUEST['search_case_type']) ? $_REQUEST['search_case_type'] :''; ?>
<td colspan="4">
<font face="Verdana, Arial, Helvetica, sans-serif" size="2"><font color="red">*</font>Case Type
</td>
<?php
?>
<td colspan="2">
<select name="search_case_type" style="width:250px" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
<option value="">select</option>
<?php   
	 $sql2=" select * from case_type order by case_type_desc ";

foreach($dbh->query($sql2) as $row)
{

  $s_case_type=$row['id'];
 if($search_case_type == $s_case_type)
                {
		print "<option value=".htmlentities(htmlspecialchars($row['id']))." selected>".strtoupper(htmlentities(htmlspecialchars($row['case_type_desc'])))."</option>";
                }
           else
                {
                print "<option value=".htmlentities(htmlspecialchars($row['id'])).">".strtoupper(htmlentities(htmlspecialchars($row['case_type_desc'])))."</option>";
		}
 }
 ?>
 </select>
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;
<input id="submit1" type="button"  name="submit1" value="SEARCH"  onClick="return submitForm();" />
</td>
 </br>
</tr>	
</table>
<table>
<tr>
<th width="10%">Serial No.</th>
<th width="10%">Diary No.</th>
<th width="10%">Date of Filing.</th>
<th width="10%">Case No.</th>
<th width="10%">Court No.</th>
<th width="20%">Party Detail</th>
<th width="10%">Section</th>
<th width="10%">Registration Date</th>
<th width="10%">Registrar Date</th>
<th width="10%">Main Case</th>

</tr>
 
 <?php

 if($search_case_type!='' and $s_year!='' and $from_date1rr!='' and $to_date1rr!='')
 {

 $stnq = $db->prepare("select * from $schemas.case_detail where case_type=? and case_year=? and regis_date between ? and ? order by cast(case_no as INTEGER) asc ");
 $stnq->bindParam(1, $search_case_type, PDO::PARAM_INT);
  $stnq->bindParam(2, $s_year, PDO::PARAM_INT);
    $stnq->bindParam(3, $from_date1rr, PDO::PARAM_INT);
  $stnq->bindParam(4, $to_date1rr, PDO::PARAM_INT);

 $stnq->execute();
 }

else if($search_case_type!='' and $year!='' )
{

 $stnq = $db->prepare("select * from $schemas.case_detail where case_type=? and case_year=?  order by cast(case_no as INTEGER) asc ");
 $stnq->bindParam(1, $search_case_type, PDO::PARAM_INT);
  $stnq->bindParam(2, $s_year, PDO::PARAM_INT);
 

 $stnq->execute();
 }
 
 
 

 if($stnq->rowCount()==0)
 {
 ?>
 <tr>
<td align="center"  ><font color="red" size="4"><b>No Record Found </b></font></td>
</tr>
 <?php
 } 
 if($stnq->rowCount()>0)
 {
$counter=1;


 while ($rw2 = $stnq->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 {
	
 	
 	
 		$filing_no98 = htmlspecialchars($rw2['filing_no']);
        $dt_of_filing=htmlspecialchars($rw2['dt_of_filing']);
		$court_no=htmlspecialchars($rw2['court_no']);
 		$case_no=htmlspecialchars($rw2['case_no']);
 		$pt_name=htmlspecialchars($rw2['pet_name']);
 		$rs_name=htmlspecialchars($rw2['res_name']);
 		$case_type=htmlspecialchars($rw2['case_type']);
 		$case_year=htmlspecialchars($rw2['case_year']);
		$regis_date=htmlspecialchars($rw2['regis_date']);
		$registrar_date=htmlspecialchars($rw2['registrar_date']);
		$location_code=htmlspecialchars($rw2['location_code']);
		$party_name=$pt_name." Vs " .$rs_name;
		
		//$st1 = $db->prepare("select * from $schemas.bench_location where bench_location_code=?");
		//$st1->execute(array($location_code));
		//$bech_data= $st1->fetch();
		//$bech_code = $bech_data[short_name];
		if($dt_of_filing!='')
		{
			 list($year,$month,$day)=explode('-',$dt_of_filing);
          $filing_date_all=$day.'/'.$month.'/'.$year;
		}
		$main_fno='';
		if($case_type == 11 || $case_type == 13 || $case_type==18){				
	//echo $filing_no98;
		$stQe = $dbonline->prepare("select in_filingno from e_case_detail where filing_no = ?");
		$stQe->bindParam(1, $filing_no98, PDO::PARAM_STR);
		$stQe->execute();
		$main_fno=$stQe->fetchColumn();
		$main_case_no = $causelist->nclt_case_no($main_fno, $db, $schemas);
		}
		
		
if($case_type > 0)
	{
		$stQ = $db->prepare("select short_name from case_type where id = ?");
		$stQ->bindParam(1, $case_type, PDO::PARAM_STR);
		$stQ->execute();
		$case_type_short_name=$stQ->fetchColumn();
	}
	
	if($location_code > 0)
	{
		 $ref_lcode ="select short_name from $schemas.bench_location where bench_location_code ='$location_code'";
$ref_lcode=$db->prepare($ref_lcode);
$ref_lcode->execute();
$lcodename = $ref_lcode->fetchColumn();
   
 
	}

                      $st2=$dbonline->prepare("select * from e_case_detail_fees where filing_no=? ");
                      $st2->bindParam(1, $filing_no98, PDO::PARAM_STR);
                      $st2->execute();
$i=0;$r='';
                      while ($row2= $st2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {    
                           $E_sec_id=$row2['sec_id'];
                       
                          if($E_sec_id > '0')
                          {
                      $st3=$dbonline->prepare("select * from master_section_act where id=? ");
                      $st3->bindParam(1, $E_sec_id, PDO::PARAM_STR);
                      $st3->execute();
                      
                      while ($row3 = $st3->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                      {
                      	  $E_add_sec_id=$row3['section_companies'];                      
                           $r.=$E_add_sec_id.',';
                         
                       
                      }
                          }
                      }
                     
	
	
 		$case_number=$case_type_short_name."/".$case_no."(".$lcodename.")".$case_year;
 	
 	
 	
 	


 	


 ?>
<tr>
<td width="2%"> <?php echo $counter;?>

</td>
<td width="10%">
<?php echo $filing_no98;?>
</td>
<td width="10%">
<?php echo $filing_date_all;?>
</td>

<td width="18%">
<?php echo $case_number;?>
</td>

<td width="2%">
<?php 
if($court_no=='0')
{
	$court_no='';
}

echo $court_no;?>
</td>
<td width="34%">
<?php echo $party_name;?>
</td>

<td width="10%">
<?php  echo rtrim($r,',');?>
</td>

<td width="4%">
<?php echo $regis_date;?>
</td>


<td width="10%">
<?php echo $registrar_date;?>
</td>

<td width="10%">
<?php echo $main_fno;?><br>
<?php echo '<b>'.$main_case_no.'</b>';?>
</td>

</tr>
<?php 


$counter++;
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

