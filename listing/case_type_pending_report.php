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

	<meta name="description" content="The HTML5 Herald">
    <meta name="author" content="SitePoint">
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>
   
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
	 <script src="../export/dist/jquery.table2excel.js"></script>
    <script src="../export/dist/jquery.tabletoCSV.js"></script>
	
<link rel="stylesheet" type="text/css" href="../includes/highslide/highslide.css" />
<script type="text/javascript" src="../includes/highslide/highslide-with-html1.js"></script>
<script>
function toExcel(myMessage) {
	
        $(".table2excel").table2excel({
            exclude: ".noExl",
            name: "Excel Document Name",
            filename: "myFileName" + new Date().toISOString().replace(/[\-\:\.]/g, ""),
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });
    }

    function toCsv(myMessage) {
        $(".table2excel").tableToCSV({
            exclude: ".noExl",
            name: "Excel Document Name",
            filename: "myFileName" + new Date().toISOString().replace(/[\-\:\.]/g, ""),
            fileext: ".csv",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });
    }
    </script>

	</script>
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
				if(case_year1.value == "")
				{
					alert("Please Enter Case Year...");
					search_case_year1.value='';
					search_case_year1.focus();
					return false;
				}

				action="case_type_pending_report.php";
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

<table cellspacing="1" cellpadding="1" border="0" width="95%" align="center">
        <div id="testdiv" style="visibility: visible;"><a href="javascript:window.print();">
                <font size="4" color="red">
                    </font>
            </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:window.toExcel()"><b>EXPORT EXCEL</b></a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:window.toCsv()"><b></b></a>
        </div>
    </table>
<table class="table">
<tr>
	<th valign="top" align="center" colspan="16">
					<b><font face="Verdana" size="3"><u>CASE TYPE WISE PENDING REPORT</u></font> </b>
	</th>
	</tr>
<form name="frm" method="post" action="dcase_reg_report.php">

<tr><td>


<?php  $search_case_type = isset($_REQUEST['search_case_type']) ? $_REQUEST['search_case_type'] :''; ?>
<font face="Verdana, Arial, Helvetica, sans-serif" size="2"><font color="red">*</font>Case Type
<select name="search_case_type" style="width:250px" onFocus="SetBg(this)" onBlur="UnSetBg(this)">
<option value="">select</option>
<?php

 $sql2=" select id,case_type_desc from case_type order by case_type_desc ";

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

<?php  $case_year1 = isset($_REQUEST['case_year1']) ? $_REQUEST['case_year1'] :''; ?>

<font color="red">*</font><font face="Verdana" size="2">Case Year</font>
          <input type="text" name="case_year1"   maxlength="4" size="9" value="<?php echo $case_year1; ?>" >
<input id="submit1" type="button"  name="submit1" value="SEARCH"  onClick="return submitForm();" />
			</td>
			
 </br>
</tr>
</table>
 <table class="responstable table2excel">
      	
<tr>
<th>SERIAL NO.</th>
<th>DIARY NO.</th>
<th>DATE OF FILING.</th>
<th>CASE NO.</th>
<th>PARTY DETAIL</th>
<th>LAST LISTING DATE</th>
<th>COURT NO</th>

</tr>
 
 <?php
  
 $status='P';
 
 $stnq = $db->prepare("select filing_no from $schemas.case_detail where case_type=? and status=? and location_code is NOT NULL and case_no !='' and case_year=?");
  $stnq->bindParam(1, $search_case_type, PDO::PARAM_INT);
 $stnq->bindParam(2, $status, PDO::PARAM_INT); 
  $stnq->bindParam(3, $case_year1, PDO::PARAM_INT); 

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
 	$stcn = $db->prepare("select pet_name,res_name,case_no,dt_of_filing,filing_no,case_type,case_year,location_code from $schemas.case_detail where filing_no=? and case_type=? and status=? and location_code is NOT NULL and case_no!='' and case_year=?");
 	$stcn->bindParam(1, $filing_no, PDO::PARAM_STR);
	$stcn->bindParam(2, $search_case_type, PDO::PARAM_STR);
	$stcn->bindParam(3, $status, PDO::PARAM_STR);
		$stcn->bindParam(4, $case_year1, PDO::PARAM_STR);

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
		/*
		$st1 = $db->prepare("select short_name from $schemas.bench_location where bench_location_code=?");
		$st1->execute(array($location_code));
		$bech_data= $st1->fetch();
		$bech_code = $bech_data['short_name'];
		*/
		if($dt_of_filing!='')
		{
			 list($year,$month,$day)=explode('-',$dt_of_filing);
          $filing_date_all=$day.'/'.$month.'/'.$year;
		}
if($case_type > 0)
	{
		$stQ = $db->prepare("select case_type_desc from case_type where id = ?");
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
	

	$listing_date3='';
   $stcn1 = $db->prepare("select max(listing_date) as listing_date from $schemas.case_allocation_temp where filing_no=? ");
 	$stcn1->bindParam(1, $filing_no, PDO::PARAM_STR);
	
 	$stcn1->execute();
 	while ($rw21 = $stcn1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 	{
 	
 		  $listing_date = htmlspecialchars($rw21['listing_date']);
		 //$court_no = htmlspecialchars($rw21['court_no']);		 
		
		 
	}   

	$court_no11='';
	if($filing_no!='' and $listing_date!='')
	{
	$stcn2 = $db->prepare("select court_no,listing_date from $schemas.case_allocation_temp where filing_no=? and listing_date=?");
 	$stcn2->bindParam(1, $filing_no, PDO::PARAM_STR);
	 	$stcn2->bindParam(2, $listing_date, PDO::PARAM_STR);

	
 	$stcn2->execute();
 	while ($rw2 = $stcn2->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 	{
$listing_date2='';
 	
 		 $court_no11 = htmlspecialchars($rw2['court_no']);
		 $listing_date2 = htmlspecialchars($rw2['listing_date']);
		 if($listing_date2!='')
		{
			 $listing_date3='';
			 list($year,$month,$day)=explode('-',$listing_date2);
             $listing_date3=$day.'/'.$month.'/'.$year;
		}
		 //$court_no = htmlspecialchars($rw21['court_no']);		 
		
		 
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
<?php 


echo $listing_date3;?>
</td>
<td>
<?php echo $court_no11;?>
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

