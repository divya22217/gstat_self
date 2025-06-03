<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css">

<style>
	.dataTables_filter {
		margin-right: 11%;
	}
</style>

<?php
session_start();
ob_start();
include("../db_inc1.php");
include("../db_inc2.php");
date_default_timezone_set("Asia/Kolkata");

$bench_no = '';
$_SESSION['user'];
$_SESSION['location'];
$leveladd = $_SESSION['level_level'];

$schemas = htmlspecialchars($_SESSION['schema_name']);
include '../inheader.php';

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
	<title>Transfer Cases From One Bench To Another Bench Of Same Listing Date</title>

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
	</head>

	<body>
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<div class="row" style="padding:15px 15px 15px 15px;margin:0px 0px 0px 0px !important;">
				<div class="box box-success">
					<div class="box-body">
						<!-- Content Header (Page header) -->
						<section class="content-header">
							<center><b>Set Cases Priority Wise</b></center>
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
						<form action="transfer_case_action.php" method='POST' id='transfer_case'>
							<div class="form-inline" id="search_form">
								<div class="form-group">
									<label>Listing Date : </label>
									<input type="text" class="form-control datepicker" autocomplete="off" id="listing_date" placeholder="Enter Listing Date" name="listing_date" onchange="return getbench(this.id,'benches_new');">
									<input type='hidden' value='1' id='causelist_type' name='causelist_type'>
								</div>
							</div>
							<div class="row" id="benches_new" style="padding:25px;"></div>
							<div id="cases" class="panel-group">

							</div>
						</form>


					</div>
				</div>
			</div>
		</div>
		<script src="../src/calendar.js"></script>
		<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="../bower_components/bootstrap/dist/js/jquery.dataTables.min.js"></script>
		<script src="../bower_components/bootstrap/dist/js/dataTables.bootstrap.min.js"></script>
		<script>
			function getbench(listing_date_id, div_id) {
				listing_date = $("#" + listing_date_id).val();
				if (listing_date === '') {
					alert("please enter listing date");
					return false;
				}
				$("#" + div_id).html("<center>loading......<center>");
				$.ajax({
					type: "POST",
					url: "../listing/getbench_by_listing_date.php",
					data: {
						listing_date: listing_date
					},
					success: function(data) {
						$("#" + div_id).html(data);
						if (div_id == 'transfer_to') {
							$("#transfer_to .bench_no").removeAttr('onchange');
							$("#transfer_to .bench_no").attr('name', 'bench_no_transfer');
						} else {
							$("#transfer_to").html('');
							$("#transfer_div").css('display', 'none');
						}
						//alert("success");
					},
					error: function(textStatus, errorThrown) {
						alert("error");
					}

				});
			}


			function submitForm() {
				var bench_no = $("input[name='bench_no']:checked").val();
				var listing_date = $("#listing_date").val();
				var causelist_type = $("#causelist_type").val();
				if (bench_no) {
					$.ajax({
						type: "POST",
						url: "../listing/get_cases_to_set_priority.php",
						data: {
							type: 'get_cases',
							listing_date: listing_date,
							bench_no: bench_no,
							causelist_type: causelist_type
						},
						success: function(data) {
							$("#cases").html(data);
							//alert("success");
						},
						error: function(textStatus, errorThrown) {
							alert("error");
						}

					});
				}
			}

			function submit_priority_form() {
				swal({
						title: "Are You Sure",
						text: "Do you want to change cases priority",
						type: "info",
						showCancelButton: true,
						closeOnConfirm: false,
						confirmButtonText: "Yes",
						// showLoaderOnConfirm: true,
						animation: "slide-from-top",
					},
					function(isConfirm) {
						if (isConfirm) {
							$.ajax({
								type: "POST",
								url: "set_cases_priority_action.php",
								data: $("form #priority_form").serialize(),
								/* contentType: false,
								cache: false,
								processData:false, */
								dataType: 'json',
								beforeSend: function() {
									/* $('#transfer').attr("disabled","disabled");
									$('#transfer_case').css("opacity",".5"); */
								},
								success: function(response) {
									/* $('#transfer_case').css("opacity","");
									$("#transfer").removeAttr("disabled"); */
									if (response.status == 0) {
										swal('', response.message, 'warning');
									} else {
										swal('', response.message, 'success');
										submitForm();
									}
								},
								error: function(textStatus, errorThrown) {
									console.log(textStatus);
									alert(errorThrown);
								}

							});
							return false;
						} else {}
					});
			}


			// Submit form data via Ajax
			$(document).ready(function() {
				$(document).on('submit', '#priority_form', function(e) {
					e.preventDefault();
					var formdata = new FormData(this);
					swal({
							title: "Are You Sure",
							text: "Do you want to change cases priority",
							type: "info",
							showCancelButton: true,
							closeOnConfirm: false,
							confirmButtonText: "Yes",
							// showLoaderOnConfirm: true,
							animation: "slide-from-top",
						},
						function(isConfirm) {
							if (isConfirm) {
								$.ajax({
									type: "POST",
									url: "set_cases_priority_action.php",
									data: formdata,
									contentType: false,
									cache: false,
									processData: false,
									dataType: 'json',
									beforeSend: function() {
										/* $('#transfer').attr("disabled","disabled");
										$('#transfer_case').css("opacity",".5"); */
									},
									success: function(response) {
										/* $('#transfer_case').css("opacity","");
										$("#transfer").removeAttr("disabled"); */
										if (response.status == 0) {
											swal('', response.message, 'warning');
										} else {
											swal('', response.message, 'success');
											submitForm();
										}
									},
									error: function(textStatus, errorThrown) {
										console.log(textStatus);
										alert(errorThrown);
									}

								});
								return false;
							} else {}
						});
				});
			});
		</script>



	</body>

<?php } ?>