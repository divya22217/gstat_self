<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css">

<style>
	.dataTables_filter {
		margin-right: 11%;
	}

	.align_text_center {
		text-align: center;
	}

	.show_parties {
		margin-top: 10px;
		padding: 10px;
		background: beige;
	}
</style>

<?php
session_start();
ob_start();
include("../db_inc1.php");
//include("../db_inc2.php");
date_default_timezone_set("Asia/Kolkata");

$bench_no = '';
$_SESSION['user'];
$_SESSION['location'];
$leveladd = $_SESSION['level_level'];

$schemas = htmlspecialchars($_SESSION['schema_name']);
include '../inheader.php';
//include '../insidebar.php';

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

	date_default_timezone_set("Asia/Kolkata");
	$server_date = date('Y-m-d'); //Returns IST 

?>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="renderer" content="webkit">
	<title>View Case Docs</title>

	<script src="../bower_components/jquery/dist/jquery.min.js"></script>

	<!-- Bootstrap 3.3.7 -->
	<link href="../dist/css/sweetalert.css" rel="stylesheet" />
	<script src="../dist/js/adminlte.min.js"></script>
	<script src="../dist/js/sweetalert.min.js"></script>
	<script src="../dist/js/sweetalert-dev.min.js"></script>


	<style>
		.no-border {
			border: none;
		}

		#errmsg {
			color: red;
		}
	</style>
	<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
	<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css" />
	<script src="../plugins/jQueryUI/jquery-ui.js"></script>
	<script src="../plugins/jQueryUI/date.js"></script>
	<script src="../src/calendar.js"></script>
	<script src="../bower_components/select2/dist/js/select2.min.js"></script>
	</head>

	<body>
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<div class="row" style="padding:15px 15px 15px 15px;margin:0px 0px 0px 0px !important;">
				<div class="box box-success">
					<div class="box-body">
						<!-- Content Header (Page header) -->
						<section class="content-header">
							<center><b>Case Docs</b></center>
						</section>
						<div class="form-inline" id="search_form">
							<div class="form-group">
								<?php
								$dispaly_city = true;
								$search_city = $db->prepare("select * from mater_location_city where display=? order by city_id desc");
								$search_city->bindParam(1, $dispaly_city, PDO::PARAM_INT);
								$search_city->execute();
								$search_city = $search_city->fetchAll();

								?>
								<label>Location : </label>
								<select class="form-control" id="search_location" name="search_location">
									<option value=''>Select Location</option>
									<?php foreach ($search_city as $key => $city) { ?>
										<option value="<?php echo $city['schema_name'] . '/' . $city['city_id']; ?>"><?php echo $city['city_name']; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<?php
								$dispaly_Case_type = 't';
								$search_case_types = $db->prepare("select * from case_type where status=? order by id asc");
								$search_case_types->bindParam(1, $dispaly_Case_type, PDO::PARAM_INT);
								$search_case_types->execute();
								$search_case_types = $search_case_types->fetchAll();
								?>
								<label>Case Type : </label>
								<select class="form-control" id="search_case_type" name="search_case_type">
									<option value=''>Select Case Type</option>
									<?php foreach ($search_case_types as $key => $search_case_type) { ?>
										<option value="<?php echo $search_case_type['id']; ?>"><?php echo $search_case_type['case_type_desc']; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<label>Case No : </label>
								<input type="number" class="form-control" placeholder="Enter Case Number" onKeyPress="return number_validation(this.id,4)" name="case_no" id="case_no" autocomplete="off" required="required">
							</div>
							<div class="form-group">
								<label>Case Year : </label>
								<input type="number" class="form-control" placeholder="Enter Case Year" onKeyPress="return number_validation(this.id,4)" name="case_year" id="case_year" autocomplete="off" required="required">
							</div>
							<button type="button" class="btn btn-primary" onClick="return search_data();">search</button>
							<button type="button" class="btn btn-warning" onClick="window.location.reload();">Reset</button>

							<div id="show_case_docs">

							</div>
						</div>
					</div>
				</div>
			</div>
			<script src="../src/calendar.js"></script>
			<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
			<script src="../bower_components/bootstrap/dist/js/jquery.dataTables.min.js"></script>
			<script src="../bower_components/bootstrap/dist/js/dataTables.bootstrap.min.js"></script>
			<script>
				function number_validation(element_id, number_length) {
					//called when key is pressed in textbox
					$("#" + element_id).keypress(function(e) {
						var filing_no = $("#" + element_id).val();
						if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
							return false;
						}
						if (filing_no.length >= number_length) {
							return false;
						}
					});
				};


				function IsValidJSONString(str) {
					try {
						JSON.parse(str);
					} catch (e) {
						return false;
					}
					return true;
				}


				function search_data() {
					var search_location = $("#search_location").val();
					var case_type = $("#search_case_type").val();
					var case_no = $("#case_no").val();
					var case_year = $("#case_year").val();
					if (case_type == '' || case_no == '' || case_year == '') {
						swal("", "please enter all details", "warning");
						return false;
					}
					$("#show_case_detail").html("<tr><td colspan='7'><center>loading......<center></td></tr>");
					$.ajax({
						type: "POST",
						url: "case_doc_ajax.php",
						data: {
							type: 'search_case',
							case_type: case_type,
							case_no: case_no,
							case_year: case_year,
							search_location: search_location
						},
						success: function(data) {
							var IsValidJSON = IsValidJSONString(data);
							if (IsValidJSON) {
								var obj = JSON.parse(data);
								if (obj.status == '0') {
									$("#show_case_docs").html("");
									swal("", obj.message, "warning");
									return false;
								}
							} else {
								$("#show_case_docs").html(data);
							}
						},
						error: function(textStatus, errorThrown) {
							console.log(textStatus);
							alert(errorThrown);
						}

					});

				}

				function viewpdf(pdfpath) {
					var loader = "<center><img src='../loader/loader.gif'></img></center>";
					$.ajax({
						type: "POST",
						url: "../scrutiny/readpdf_file.php",
						data: {
							pdfpath: pdfpath
						},
						beforeSend: function() {
							$("#case_document_body").html('Loading....');
							$("#case_document").modal('show');
						},
						success: function(data) {

							$("#case_document_body").html(data);
							//alert("success");
						},
						error: function(textStatus, errorThrown) {
							$("#case_document_body").html('');
							alert("error");
						}

					});
				}
			</script>


			<!-- edit status modal -->
			<div id="case_document" class="modal fade" role="dialog">
				<div class="modal-dialog" style="width:90%">

					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Document</h4>
						</div>
						<div class="modal-body" id="case_document_body" style="height:500px;">
							<p>Loading.........</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>

				</div>
			</div>
			<!-- end -->




	</body>

<?php } ?>