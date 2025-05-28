<link rel="stylesheet" href="../css/bootstrap.min.css">


<?php
session_start();
ob_start();
include("../db_inc1.php");
//if we remove db_inc2 then it stops working
date_default_timezone_set("Asia/Kolkata");
/* 
 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */

$bench_no = '';
$_SESSION['user'];
$_SESSION['location'];
$leveladd = $_SESSION['level_level'];
//$localadmin=$_SESSION['localadmin'];
//$main_id=$_SESSION['main_id'];
$schemas = htmlspecialchars($_SESSION['schema_name']);
include '../inheader.php';
include '../custom/custom_function.php';
//include '../insidebar.php';
//include ('../classes/pagination.class.php');
//$pagination = new pagination(100);
//$userid=$_SESSION['id'];
if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
	echo "Access Problem.....";
	header("Location: ../login.php");
	die();
}
setcookie("PHPSESSID", "", time() - 3600, "", "", TRUE, TRUE);
$benchlocation = htmlentities($_REQUEST['bench_location']);
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {
	$sessionUserType = htmlspecialchars($_SESSION['id']);
	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 = "$curDay/$curMonth/$curYear";
	$link_scrutiny_idaccess = '1';
	$location_id = $_SESSION['location'];

	date_default_timezone_set("Asia/Kolkata");
	$server_date = date('Y-m-d'); //Returns IST 
	$appeals = main_case_type();

	function get_short_name($db, $table, $search_column_name, $condtion_column_name, $condtion_column_value)
	{
		$short_name = $db->prepare("select $search_column_name from $table where $condtion_column_name = ? ");
		$short_name->bindParam(1, $condtion_column_value, PDO::PARAM_INT);
		$short_name->execute();
		$short_name = $short_name->fetchColumn();
		return $short_name;
	}


?>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="renderer" content="webkit">
	<title>Link Caveat</title>
	<!-- Bootstrap 3.3.7 -->
	<link href="../dist/css/sweetalert.css" rel="stylesheet" />
	<script src="../dist/js/adminlte.min.js"></script>
	<script src="../dist/js/sweetalert.min.js"></script>
	<script src="../dist/js/sweetalert-dev.min.js"></script>

	<style>
		.no-border {
			border: none;
		}

		#DataTables_Table_0_filter {
			padding-left: 56%;
		}
	</style>
	<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
	<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css" />
	<script src="../plugins/jQueryUI/jquery-ui.js"></script>
	<script src="../plugins/jQueryUI/date.js"></script>
	<script src="../src/calendar.js"></script>

	</head>

	<body>
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<div class="row" style="padding:15px 15px 15px 15px;margin:0px 0px 0px 0px !important;">
				<div class="box box-success">
					<div class="box-body">
						<!-- Content Header (Page header) -->

						<section class="content-header">
							<center><b>Wrongly Updated Cases</b></center>
						</section>
						<?php
						$msghash = $_REQUEST['msghash'];
						if ($msghash != '') {
							$msghashz = (base64_decode($msghash));

							$msghashz = explode("@", $msghashz);

							$msg1 = $msghashz[0];
							$case_list_date = $msghashz[1];

							list($cyear, $cmonth, $cday) = explode('-', $case_list_date);

							$case_list_date_dis = $cday . '/' . $cmonth . '/' . $cyear;
						}
						?>

						<div class="table-responsive" id="table_data">
							<table cellspacing="0" align="center" class="table no-margin table-bordered table-striped table-hover casealloc_table" cellpadding="2" border="1" width="95%" class="std">
								<thead>
									<th>Diary No</th>
									<th>Case No</th>
									<th>Title</th>
									<th>Date Of Filing</th>
									<th>Last Defect Date</th>
									<th>Scrutiny Defect Free Date</th>
									<th>Computation Note Date</th>
									<th>Computation Note Approved Date</th>
									<th>Date Of Registration</th>
									<th>Status</th>
									<th>More Details</th>
								</thead>
								<tbody id="search_data_here">
									<?php
									$count = 0;
									$status = 'W';
									$cases = $db->prepare("select cd.filing_no,cd.status,cd.dt_of_filing,cd.case_no,cd.case_year,cd.case_type,
									cd.regis_date,cd.entry_date,cd.main_case_ia_no,
									s.notification_date,cn.entry_date,ecd.defect_date,cn.is_approved,cn.updated_date
									from $schemas.case_detail as cd 
									left join $schemas.scrutiny as s on s.filing_no = cd.filing_no
									left join $schemas.computational_note as cn on cn.filing_no = cd.filing_no
									left join e_case_detail as ecd on ecd.filing_no = cd.filing_no
									where cd.status = ? and cd.location_code = ?");
									$cases->bindParam(1, $status, PDO::PARAM_INT);
									$cases->bindParam(2, $location_id, PDO::PARAM_INT);
									$cases->execute();
									$cases = $cases->fetchAll();
									foreach ($cases as $k => $case_detail) {
										$filing_no = $case_detail['filing_no'];
										$case_type = $case_detail['case_type'];
										$case_no = $case_detail['case_no'];
										$case_year = $case_detail['case_year'];
										if (!empty($case_detail['main_case_ia_no'])) {
											$party_filing_no = $case_detail['main_case_ia_no'];
										} else {
											$party_filing_no = $case_detail['filing_no'];
										}
										$pet_name = get_party($db, $party_filing_no, 'P', '1');
										$res_name = get_party($db, $party_filing_no, 'R', '1');
									?>
										<tr>
											<td><?php echo $filing_no; ?></td>
											<td><?php echo get_short_name($db, 'case_type', 'short_name', 'id', $case_type) . '/' . $case_no . '(' . get_short_name($db, 'mater_location_city', 'short_name', 'city_id', $location_id) . ")/" . $case_year; ?></td>
											<td><?php echo $pet_name . "  VS  " . $res_name; ?></td>
											<td><?php echo (!empty($case_detail['dt_of_filing'])) ? date('d/m/Y', strtotime($case_detail['dt_of_filing'])) : ''; ?></td>
											<td><?php echo (!empty($case_detail['defect_date'])) ? date('d/m/Y', strtotime($case_detail['defect_date'])) : ''; ?></td>
											<td><?php echo (!empty($case_detail['notification_date'])) ? date('d/m/Y', strtotime($case_detail['notification_date'])) : ''; ?></td>
											<td><?php echo (!empty($case_detail['entry_date'])) ? date('d/m/Y', strtotime($case_detail['entry_date'])) : ''; ?></td>
											<td><?php
												if ($case_detail['is_approved']) {
													echo (!empty($case_detail['updated_date'])) ? date('d/m/Y', strtotime($case_detail['updated_date'])) : '';
												}
												?></td>
											<td><?php echo (!empty($case_detail['regis_date'])) ? date('d/m/Y', strtotime($case_detail['regis_date'])) : ''; ?></td>

											<td><?php echo ($case_detail['status'] == 'D') ? 'Disposed' : (($case_detail['status'] == 'W') ? 'Wrongly Updated' : 'Pending'); ?></td>
											<td>
												<button type="button" onclick="previewCIS('<?php  echo $case_detail['filing_no']; ?>')" class="btn btn-primary btn-sm text-white" style="cursor: pointer"><i class="fa fa-eye"></i> View
												</button>
											</td>
										</tr>

								<?php	}
								}

								?>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script src="../src/calendar.js"></script>
		<script src="../bower_components/bootstrap/dist/js/jquery.dataTables.min.js"></script>
		<script src="../bower_components/bootstrap/dist/js/dataTables.bootstrap.min.js"></script>
		<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="../js/custom.js"></script>
		<script>
			function previewCIS(filing_no) {
				document.getElementById("filling_no").value = filing_no;
				document.getElementById("previewCIS").submit();
			}
		</script>
		<form action="https://efiling.nclat.gov.in/previewCIS.drt" method="POST" target="_blank" id="previewCIS">
			<input type="hidden" id="filling_no" name="filing_no" value="" />
		</form>
	</body>