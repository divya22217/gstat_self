<?php
// File used to connect to database
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");

include("../db_inc1.php");



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
	//script for printing
	function printDiv(divName) {
 var printContents = document.getElementById(divName).innerHTML;
 w=window.open();
 w.document.write(printContents);
 w.print();
 w.close();
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
			<div id="print-button">
			 <form>
				<input type="button" onclick="printDiv('print-section')" value="Print"/>
			</form>
			</div>
<div id="print-section">
<table class="table" border="1">
<tr>
	<th valign="top" colspan="16" align="center">
					<b><font face="Verdana" size="3"><u>Diary No. Wise Filing Report</u></font> </b>
	</th>
	</tr>
<form name="frm" method="post" action="">

<tr><td colspan="16"></td></tr>

 <!-- start of code for bench nature field -->


<!--&nbsp;<font color="red">*</font>Bench Nature:-->
<?php //print_r($schemas); ?>
<tr><td colspan="16"><font color="red">*</font><font size="1">SELECT BENCH:</font>
<select id="bench_name" size="1" name="bench_name">
             <option value="">Select</option>
<?php

  $bench_id_before = isset($_REQUEST['bench_name']) ? $_REQUEST['bench_name'] :'';
//print_r($bench_id_before);die('j');
	$sql="select * from $schemas.bench_location where display='TRUE'";
	//print_r($sql);die('j');
	$schemaName = $db-> prepare($sql);
	$schemaName -> execute();

	while ($row = $schemaName->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
	{
	$bench_id_now = $row['bench_location_code'];
	if($bench_id_now == $bench_id_before)
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

<?php  $prev_list_date = isset($_REQUEST['prev_list_date']) ? $_REQUEST['prev_list_date'] :''; ?>
&nbsp;<font color="red">*</font><font size="1">From:</font>
<input type="text" id="prev_list_date" name="prev_list_date" class="datepicker"
readonly="readonly" size="8" autocomplete="off" maxlength="10" value="<?php print htmlspecialchars($prev_list_date); ?>" />

<?php  $next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] :''; ?>
&nbsp;<font color="red" size="1">*</font><font size="1">To:</font>
<input type="text" id="next_list_date" name="next_list_date" class="datepicker"
readonly="readonly" size="8" autocomplete="off" maxlength="10" value="<?php print htmlspecialchars($next_list_date); ?>" />

&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" id="submit11" name="submit11" value="Search" />

<tr>
<th>S No.</th>
<th>Diary No.</th>
<th>Case No.</th>
<th>Case Type</th>
<th>Case Year</th>
<th>
Party Detail
</th>
</tr>

 <?php
 if(isset($_POST['submit11']) && $_POST[prev_list_date] !='' && $_POST[next_list_date] !='' && $_POST[bench_name] !=''){
	 //print_r($_POST);die('g');

	 list($d,$m,$Y) =explode('/',$prev_list_date);
	 $prev_list_date =$Y.'-'.$m.'-'.$d;

	 list($d,$m,$Y) =explode('/',$next_list_date);
	 $next_list_date =$Y.'-'.$m.'-'.$d;

	 //get  all post values to php variables
   $bench_id = $_POST[bench_name];
//print_r($bench_id);

 $casedetailsql = $db->prepare("select * from $schemas.case_detail where location_code = ? and regis_date BETWEEN ? and ?");
 //$casedetailsql = $db->prepare("select * from $schemas.case_detail where location_code = '$bench_id' and regis_date BETWEEN '$prev_list_date' and '$next_list_date'");
 $casedetailsql->bindParam(1, $bench_id, PDO::PARAM_INT);
 $casedetailsql->bindParam(2, $prev_list_date, PDO::PARAM_INT);
 $casedetailsql->bindParam(3, $next_list_date, PDO::PARAM_INT);
 $casedetailsql->execute();

 if($casedetailsql->rowCount()==0)
 {
 ?>
 <tr>
<td align="center" colspan="16" ><font color="red" size="4"><b>No Record Found </b></font></td>
</tr>
 <?php
 }
 else
 {
	 //print_r($casedetailsql);die('in table');
$counter=1;


 while ($casedetailsqlrow = $casedetailsql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
 {
	$filing_no = htmlspecialchars($casedetailsqlrow['filing_no']);
	$case_type = htmlspecialchars($casedetailsqlrow['case_type']);
	$case_year = htmlspecialchars($casedetailsqlrow['case_year']);
	$case_no = htmlspecialchars($casedetailsqlrow['case_no']);
	$pt_name=htmlspecialchars($casedetailsqlrow['pet_name']);
	$rs_name=htmlspecialchars($casedetailsqlrow['res_name']);

	if($case_type > 0)
	{
		$stQ = $db->prepare("select case_type_desc from case_type where id = ?");
		$stQ->bindParam(1, $case_type, PDO::PARAM_STR);
		$stQ->execute();
		$case_type_short_name=$stQ->fetchColumn();
	}
 ?>

<tr>
 <td><?php echo $counter."."; ?></td>
<td><?php echo $filing_no; ?></td><td><?php echo $case_no; ?></td><td><?php echo $case_type_short_name; ?></td><td><?php echo $case_year; ?></td>
<td>
<?php
		echo "<h7><font color='red'><center>";
		echo htmlspecialchars($pt_name);
	echo "</font><br><font color='blue'>VS</font><br><font color='red'>";
	echo htmlspecialchars($rs_name);
	echo "</center></font></h7>";
		?>
</td>
<?php
 //while loop end all query....
$counter++;
 }
 }
  }
?>

</table>
</div>
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
?>
<?php } ?>
