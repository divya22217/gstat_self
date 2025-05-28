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


				

				action="irp_case_allocation_report.php";
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
					<b><font face="Verdana" size="3"><u>IRP REPORT</u></font> </b>
	</th>
	</tr>
<form name="frm" method="post" action="irp_case_allocation_report.php">


<tr>
<th width="5%">Serial No.</th>
<th width="5%">Diary No.</th>
<th width="5%">Date of Filing</th>
<th width="5%">Case No.</th>
<th width="5%">Registration Date</th>
<th width="5%">Court No.</th>
<th width="25%">Case Title.</th>
<th width="10%">Section</th>
<th width="15%">Name of IRP</th>
<th width="10%">Enrollment No.</th>
<th width="10%">Claim Amount</th>
</tr>
 
 <?php
$counter=1;
 	$stcn = $db->prepare("select * from $schemas.irp_detail as a ,$schemas.case_detail as b where a.filing_no=b.filing_no order by cast(b.case_no as INTEGER),case_year asc ");
 
 	$stcn->execute();
 	while ($rw2 = $stcn->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 	{
 	
 		$filing_no = htmlspecialchars($rw2['filing_no']);
		$case_no = htmlspecialchars($rw2['case_no']);
		$dt_of_filing = htmlspecialchars($rw2['dt_of_filing']);
		$case_type = htmlspecialchars($rw2['case_type']);
		$regis_date = htmlspecialchars($rw2['regis_date']);
		$location_code = htmlspecialchars($rw2['location_code']);
		$case_year = htmlspecialchars($rw2['case_year']);
		$irp_name = htmlspecialchars($rw2['name']);
		$enrolment_no = htmlspecialchars($rw2['enrolment_no']);
		
		
		if($location_code!='' || $location_code!=0)
		{
		$stQ11 = $db->prepare("select short_name from $schemas.bench_location where bench_location_code= ?");
$stQ11->bindParam(1, $location_code, PDO::PARAM_STR);
$stQ11->execute();
$location_name=$stQ11->fetchColumn();
		}
		
		$st335=$dbonline->prepare("select * from e_case_detail where filing_no=?  ");
		$st335->bindParam(1, $filing_no, PDO::PARAM_STR);
		
		$st335->execute();
		
		while ($row5 = $st335->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		
			$amount=$row5['amount']; 
		}
		
		$st35=$db->prepare("select * from $schemas.case_allocation where filing_no=?  ");
		$st35->bindParam(1, $filing_no, PDO::PARAM_STR);
		
		$st35->execute();
		
		while ($row35 = $st35->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		
			$court_no=$row35['court_no']; 
		}
		  
          $E_party_flag1='P';
	  $E_party_serial_no1='1';
       $E_nameP='';
	   $E_nameR='';
		$st33=$dbonline->prepare("select * from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
		$st33->bindParam(1, $filing_no, PDO::PARAM_STR);
		$st33->bindParam(2, $E_party_flag1, PDO::PARAM_STR);
		$st33->bindParam(3, $E_party_serial_no1, PDO::PARAM_STR);
		$st33->execute();
		
		while ($row = $st33->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		
			$E_party_flagP=$row['party_flag']; 
			$E_party_serial_noP=$row['party_serial_no']; //0
			$E_nameP=$row['name']; //0
		}
		
		$E_party_flag1='R';
		$E_party_serial_no1='1';
		$st34=$dbonline->prepare("select * from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
		$st34->bindParam(1, $filing_no, PDO::PARAM_STR);
		$st34->bindParam(2, $E_party_flag1, PDO::PARAM_STR);
		$st34->bindParam(3, $E_party_serial_no1, PDO::PARAM_STR);
		$st34->execute();
		while ($row = $st34->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
		
			$E_party_flagR=$row['party_flag'];
			$E_party_serial_noR=$row['party_serial_no']; //0
			$E_nameR=$row['name']; //0
		}
		$main_party=$E_nameP." Vs ".$E_nameR;
		$stQ1 = $db->prepare("select short_name from case_type where id = ?");
$stQ1->bindParam(1, $case_type, PDO::PARAM_STR);
$stQ1->execute();
$case_type_desc_name=$stQ1->fetchColumn();
  
		
	 $case_number_display=$case_type_desc_name."/".$case_no."(".$location_name.")"."/".$case_year;
	 
	 $st25=$dbonline->prepare("select * from e_case_detail_fees where filing_no =? ");
                   $st25->bindParam(1, $filing_no, PDO::PARAM_STR);
                   $st25->execute();
$i=0;$r='';
                   while ($row25= $st25->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                   {    
                         $E_sec_id=$row25['sec_id'];
                    if($E_sec_id == 0 || $E_sec_id == ''){
            $r = '----';
          }else
                       if($E_sec_id > '0')
                       {
                   $st35=$dbonline->prepare("select * from master_section_act where id=? ");
                   $st35->bindParam(1, $E_sec_id, PDO::PARAM_STR);
                   $st35->execute();
                   
                   while ($row35 = $st35->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                   {
                       $E_add_sec_id=$row35['section_companies'];                      
                         $r.=$E_add_sec_id.',';
                      
                    
                   }
                       }
                   }

 ?>
<tr>
<td width="5%"> <?php echo $counter;?>

</td>
<td width="5%">
<?php echo $filing_no;?>
</td>
<td width="5%">
<?php 
if($dt_of_filing!='')
{
list($yeari,$monthi,$dayi)=explode('-',$dt_of_filing);
$dt_of_filing=$dayi.'/'.$monthi.'/'.$yeari;
}

echo $dt_of_filing;?>
</td>

<td width="5%">
<?php echo $case_number_display;?>
</td>
<td width="5%">
<?php 
if($regis_date!='')
{
list($yearir,$monthir,$dayir)=explode('-',$regis_date);
$regis_date_dis=$dayir.'/'.$monthir.'/'.$yearir;
}
echo $regis_date_dis;
?>
</td>
<td width="5%">
<?php
echo $court_no; 
?>
</td>
<td width="25%">
<?php
echo $main_party; 
?>
</td>
<td width="10%">
<?php
echo rtrim($r,','); 
?>
</td>
 
<td width="15%">
<?php echo strtoupper($irp_name);?>
</td>

<td width="10%">
<?php echo strtoupper($enrolment_no);?>
</td>

<td width="10%">
<?php echo strtoupper($amount);?>
</td>

</tr>
<?php 


$counter++;
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

