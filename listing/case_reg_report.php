<?php
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


				if(regis_date.value == "")
				{
					alert("Please Select Registration Date...");
					regis_date.value='';
					regis_date.focus();
					return false;
				}
				if(search_case_type.value == "")
				{
					alert("Please Select Case Type...");
					search_case_type.value='';
					search_case_type.focus();
					return false;
				}

				action="case_reg_report.php";
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


<table class="table">
<tr>
	<th valign="top" align="center" colspan="16">
					<b><font face="Verdana" size="3"><u>CASE REGISTRATION REPORT</u></font> </b>
	</th>
	</tr>
<form name="frm" method="post" action="dcase_reg_report.php">

<tr><td colspan="16"></td></tr>

<?php  $regis_date = isset($_REQUEST['regis_date']) ? $_REQUEST['regis_date'] :''; ?>
<tr><td colspan="16"><font color="red">*</font><font size="2">REGISTRATION DATE:</font>
<input type="text" id="regis_date" name="regis_date" class="datepicker"
readonly="readonly" size="8" autocomplete="off" maxlength="10" value="<?php print htmlspecialchars($regis_date); ?>" />
<?php  $search_case_type = isset($_REQUEST['search_case_type']) ? $_REQUEST['search_case_type'] :''; ?>
<font face="Verdana, Arial, Helvetica, sans-serif" size="2"><font color="red">*</font>Case Type
<select name="search_case_type" style="width:250px" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
<option value="">select</option>
<?php

	echo $sql2=" select * from case_type order by case_type_desc ";

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


&nbsp;&nbsp;&nbsp;&nbsp;
<input id="submit1" type="button"  name="submit1" value="SEARCH"  onClick="return submitForm();" />
 </br>
</tr>	
<tr>
<th>SERIAL NO.</th>
<th>DIARY NO.</th>
<th>DATE OF FILING.</th>
<th>CASE NO.</th>
<th>PARTY DETAIL</th>
<th>SECTION</th>

</tr>
 
 <?php
  if($regis_date){
 list($day,$month,$year)=explode('/',$regis_date);
 $regis_cdate=$year.'-'.$month.'-'.$day;
 $stnq = $db->prepare("select * from $schemas.case_detail where regis_date=? and case_type=?");
 $stnq->bindParam(1, $regis_cdate, PDO::PARAM_INT);
  $stnq->bindParam(2, $search_case_type, PDO::PARAM_INT);
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
 	$stcn = $db->prepare("select * from $schemas.case_detail where filing_no=? and case_type=?");
 	$stcn->bindParam(1, $filing_no, PDO::PARAM_STR);
	$stcn->bindParam(2, $search_case_type, PDO::PARAM_STR);
 	$stcn->execute();
 	while ($rw2 = $stcn->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 	{
 	
 		$filing_no98 = htmlspecialchars($rw2['filing_no']);
               $dt_of_filing=htmlspecialchars($rw2['dt_of_filing']);
			   
 		$case_no=htmlspecialchars($rw2['case_no']);
 		$pt_name=htmlspecialchars($rw2['pet_name']);
 		$rs_name=htmlspecialchars($rw2['res_name']);
 		$case_type=htmlspecialchars($rw2['case_type']);
 		$case_year=htmlspecialchars($rw2['case_year']);
		$location_code=htmlspecialchars($rw2['location_code']);
		$party_name=$pt_name." Vs " .$res_name;
		$st1 = $db->prepare("select * from $schemas.bench_location where bench_location_code=?");
		$st1->execute(array($location_code));
		$bech_data= $st1->fetch();
		$bech_code = $bech_data[short_name];
		if($dt_of_filing!='')
		{
			 list($year,$month,$day)=explode('-',$dt_of_filing);
          $filing_date_all=$day.'/'.$month.'/'.$year;
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
                      $st2->bindParam(1, $filing_no, PDO::PARAM_STR);
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
 	
 	}
 	
 	

 	}
 	


 ?>
<tr>
<td> <?php echo $counter;?>

</td>
<td>
<?php echo $filing_no;?>
</td>
<td>
<?php echo $filing_date_all;?>
</td>
<td>
<?php echo $case_number;?>
</td>
<td>
<?php echo $party_name;?>
</td>
<td>
<?php  echo rtrim($r,',');?>
</td>


</tr>
<?php 


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

