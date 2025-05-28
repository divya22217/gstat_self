<?php
// File used to connect to database
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");

/*  ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */  

include("../db_inc1.php");


 include("../inheader.php");
include("../custom/custom_function.php");

$date = htmlspecialchars(date("d/m/Y"));
$date1 = htmlspecialchars(date("F j, Y g:i a"));
$msg_ip = isset($_REQUEST['msg_ip']) ? $_REQUEST['msg_ip'] :'';
$year=htmlspecialchars(date("Y"));
$msg_ip .= "User IP : ".$_SERVER["REMOTE_ADDR"]."\r\n"; //Sender's IP
$user = $_SESSION['user'];
$location_id = $_SESSION['location'];




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
$user_id=$_SESSION['id'];
$main_cases = main_case_type();

function coram($db,$schemas,$bench_no,$listing_date)	{
	$query = "select string_agg('Justice '||b.judge_name, ', ') AS judge_names from $schemas.bench_judge as a
				left join $schemas.master_judge as b on b.judge_code = a.judge_code
				where a.bench_no = ? and a.from_list_date = ?";
	$res = $db->prepare($query);
	$res->bindParam(1, $bench_no, PDO::PARAM_STR);
	$res->bindParam(2, $listing_date, PDO::PARAM_STR);
	$res->execute();
	$result = $res->fetchColumn();
	return $result;
}
?>





	

	<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
	<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
	<script src="../plugins/jQueryUI/jquery-ui.js"></script>
	<script src="../plugins/jQueryUI/date.js"></script>
	
	
<link rel="stylesheet" type="text/css" href="../includes/highslide/highslide.css" />
<script type="text/javascript" src="../includes/highslide/highslide-with-html1.js"></script>
<link rel="stylesheet" type="text/css" href="../datatable/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="../datatable/css/buttons.dataTables.min.css">
	<script language="javascript">
	//start of my script
function submitForm()
{
	
 	with(document.frm)
	{		
	 action = "cases_mis.php";
	 submit();
	}
}


	</script>


	<style>
		table, td, th {
			border: 1px solid #ffffff;
		}



		th {
			background-color: #008b43;
			color: white;
		}
	</style>
</head>


<div class="wrapper" style="background-color:#ffffff;">

	

	<div class="content-wrapper" style="min-height: 946px;">
		<!-- Content Header (Page header) -->
		<section class="content">


<table class="table">
<tr>
	<th valign="top" align="center" colspan="16">
					<b><font face="Verdana" size="3"><u>Judgement authored by report</u></font> </b>
	</th>
	</tr>
<form name="frm" method="post" action="">

<tr><td colspan="16"></td></tr>

<?php  $from_date = isset($_REQUEST['from_date']) ? $_REQUEST['from_date'] :'';
		$to_date = isset($_REQUEST['to_date']) ? $_REQUEST['to_date'] :'';
		$selected_judge = isset($_REQUEST['judges']) ? $_REQUEST['judges'] :'0';
		$search_case_type  = isset($_REQUEST['search_case_type']) ? $_REQUEST['search_case_type'] : '';
		if($search_case_type == ''){
			$ct_qyery = '';
			$case_type_name_selected = '';
		}else{
			
				$ct_qyery = "and a.case_type = ? ";
			 
			    $query = "select case_type_desc from case_type where id = ?";
				$res = $db->prepare($query);
				$res->bindParam(1, $search_case_type, PDO::PARAM_STR);
				$res->execute();
				$case_type_name_selected = $res->fetchColumn();
				
				$query = "select judge_neme from $schemas.master_judge where judge_code = ?";
				$res = $db->prepare($query);
				$res->bindParam(1, $selected_judge, PDO::PARAM_STR);
				$res->execute();
				$selected_judge_name = $res->fetchColumn();
			
		} 
 ?>
<tr><td colspan="16">
<font color="red">*</font><font size="1"></font>
<?php
	$query = "select * from $schemas.master_judge order by judge_desg_code";
	$res = $db->prepare($query);
	$res->execute();
	$judges = $res->fetchAll(); ?>
	<select name='judges'>
	<option value=''>Select Judge</option>
	<?php foreach($judges as $k=>$judge){ ?>
	
	<option value='<?php echo $judge['judge_code']; ?>'  <?php echo ($selected_judge == $judge['judge_code'])?'selected':''; ?>><?php echo $judge['judge_name']; ?></option>
<?php	}
 echo "</select>";
 ?>
<font color="red">*</font><font size="1">From Date:</font>
<input type="text" id="from_date" name="from_date" class="datepicker"
readonly="readonly" size="8" autocomplete="off" maxlength="10" value="<?php print htmlspecialchars($from_date); ?>" />
<font color="red">*</font><font size="1">To Date:</font>
<input type="text" id="to_date" name="to_date" class="datepicker"
readonly="readonly" size="8" autocomplete="off" maxlength="10" value="<?php print htmlspecialchars($to_date); ?>" />
Case Type : <select name='search_case_type' id='search_case_type'>
			<option value="" <?php echo ($search_case_type == '')?'selected':''; ?> >ALL</option>
			<?php 
				 $query = "select * from case_type where status = 't' or id = '60'";
				$res = $db->prepare($query);
				$res->execute();
				$all_case_types = $res->fetchAll();
				foreach($all_case_types as $case_types) { ?>
					<option value='<?php echo $case_types['id']; ?>' <?php echo ($search_case_type == $case_types['id'])?'selected':''; ?> ><?php echo $case_types['case_type_desc']; ?></option>
			<?php	} 
			?>
			</select>
<input type="submit" id="submit11" name="submit11" value="Search" />
</td>
</tr>
</form>
</table>
<div class='table-responsive'>

<?php
 if(isset($_POST['submit11'])){ ?>
<?php 

if($judges == '0'){
	echo "<tr><td colspan='6' style='color:red;'>Please select judge</td></tr>";
}

list($day,$month,$year)=explode('/',$from_date);
 $from_date_post=$year.'-'.$month.'-'.$day;
 
 list($day_to,$month_to,$year_to)=explode('/',$to_date);
 $to_date_post=$year_to.'-'.$month_to.'-'.$day_to;
 $na = 'NA';
 $blank = '';
 $filing_no_length = '16';
 $status = 'W';
 $order_type = 'J';
 $order_type_corrected = 'JC';

	$backlog = 0;
	$query = "select od.order_date,od.entry_date,od.filing_no,a.dt_of_filing as date_of_filing,a.regis_date as registration_date,c.case_type_desc as case_type_name,jm.judge_name,ma.action_type,od.bench_no,od.bench_nature,od.court_no,co.display_court_text,
	a.case_no,a.case_year,d.bench_location_name,a.status,a.main_case_ia_no,a.transfrred_case_type_short 
	from $schemas.order_daily as od
	left join $schemas.case_detail as a on a.filing_no = od.filing_no
	left join $schemas.case_disposal as cd on cd.filing_no = a.filing_no
	left join $schemas.master_judge as jm on jm.judge_code = od.author_by
	left join $schemas.master_action as ma on ma.action_code = cd.disposal_nature
	left join $schemas.court as co on co.court_no = cast(od.court_no as integer)
	left join case_type as c on c.id = a.case_type
	left join $schemas.bench_location as d on d.city_id = a.location_code
	where od.author_by = ? and (od.order_date between ? and ?) and (od.order_type = ? OR od.order_type = ?) and a.location_code =?
	and a.filing_no != ? and a.filing_no is not null and a.filing_no != ? and length(a.filing_no) = ?  and a.status != ? $ct_qyery order by od.order_date";
	//echo $query;
	$report_data = $db->prepare($query);
	$report_data->bindParam(1, $selected_judge, PDO::PARAM_STR);
	$report_data->bindParam(2, $from_date_post, PDO::PARAM_STR);
	$report_data->bindParam(3, $to_date_post, PDO::PARAM_STR);
	$report_data->bindParam(4, $order_type, PDO::PARAM_STR);
	$report_data->bindParam(5, $order_type_corrected, PDO::PARAM_STR);
	$report_data->bindParam(6, $location_id, PDO::PARAM_STR);
	$report_data->bindParam(7, $na, PDO::PARAM_STR);
	$report_data->bindParam(8, $blank, PDO::PARAM_STR);
	$report_data->bindParam(9, $filing_no_length, PDO::PARAM_STR);
	$report_data->bindParam(10, $status, PDO::PARAM_STR);
	$snn = 10;
	if($search_case_type != ''){
	$report_data->bindParam($snn, $search_case_type, PDO::PARAM_STR);
	$snn++;
	} 
	$report_data->execute();
	$report_data = $report_data->fetchAll(); ?>
	<table class='table table-responsive table-hover table-bordered' id="case_details">
		<thead>
			<tr>
				<th colspan='11'><center>Total judgements authored By <?php echo ($selected_judge_name != '')?"($selected_judge_name)":""; ?> from <?php echo $from_date; ?> to <?php echo $to_date; ?> : <?php echo count($report_data); ?></center></th>
			</tr>
			<tr>
				<th>SN</th>
				<th>Diary No</th>
				<th>Case no</th>
				<th>Case Title</th>
				<th>Nature</th>
				<th>Author By</th>
				<th>Bench</th>
				<th>Location</th>
				<th>Court</th>
				<th>Date Of Pronouncement</th>
				<th>Date Of Uploaing</th>
				<!--<th>Date of filing</th>
				<th>Date of Registration</th>
				<th>Dispose date</th>-->
				
			</tr>
		</thead>
		<tbody>
	<?php if(!empty($report_data)){ 
		foreach($report_data as $k=>$report)
		{
			if($report['main_case_ia_no'] == '')
				$party_filing_no = $report['filing_no'];
			else
				$party_filing_no = $report['main_case_ia_no'];
			
			$pet_name = get_party($db,$party_filing_no,'P',1);
			$res_name = get_party($db,$party_filing_no,'R',1);
			$transfrred_case_type_short = $report['transfrred_case_type_short'];
			$tr_short = '';
			if(!empty($transfrred_case_type_short)){
				$tr_short = " ($transfrred_case_type_short)";
			}
			$coram = coram($db,$schemas,$report['bench_no'],$report['order_date']);
			
	?>
		<tr>
			<td><?php echo ++$k; ?></td>
			<td><?php echo $report['filing_no']; ?></td>
			<td><?php echo (!empty($report['registration_date']))?$report['case_type_name'].$tr_short.'/'.$report['case_no'].'/'.$report['case_year']:''; ?></td>
			<td><?php echo $pet_name.' <br/>VS<br/> '.$res_name; ?></td>
			<td><?php echo $report['action_type']; ?></td>
			<td><?php echo "Justice ".$report['judge_name']; ?></td>
			<td><?php echo $coram; ?></td>
			
			<!--<td><?php echo date('d/m/Y',strtotime($report['date_of_filing'])); ?></td>
			<td><?php echo (!empty($report['registration_date'])) ?date('d/m/Y',strtotime($report['registration_date'])):''; ?></td>
			<td><?php echo date('d/m/Y',strtotime($report['disposal_date'])); ?></td>-->
			
			<td><?php echo $report['bench_location_name']; ?></td>
			<td><?php echo $report['display_court_text']; ?></td>
			<td><?php echo (!empty($report['order_date'])) ?date('d/m/Y',strtotime($report['order_date'])):''; ?></td>
			<td><?php echo (!empty($report['entry_date'])) ?date('d/m/Y',strtotime($report['entry_date'])):''; ?></td>
			
			
		</tr>
	<?php
	} }
	echo "</tbody></table>";
 

} ?>
	 </div>
	</div>

</section>


<?php include '../infooter.php'; ?>
<script src="../datatable/js/jquery.dataTables.min.js"></script>
<script src="../datatable/js/dataTables.buttons.min.js"></script>
<script src="../datatable/js/buttons.flash.min.js"></script>
<script src="../datatable/js/jszip.min.js"></script>
<script src="../datatable/js/pdfmake.min.js"></script>
<script src="../datatable/js/vfs_fonts.js"></script>
<script src="../datatable/js/buttons.html5.min.js"></script>
<script src="../datatable/js/buttons.print.min.js"></script>
<!-- Theme JS files -->
<script src="../datatable/js/datatables_extension_buttons_html5.js"></script>
<script>
	$(document).ready(function() {
	
    $('#case_details').DataTable({
        "pageLength": -1,
		"lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
		 buttons: [
            {
                extend: 'excel',
                title: 'excel_report' 
            },
            {
                extend: 'csv',
                title: 'csv_report' 
            }
        ]
    });
});
</script>

<?php } ?>