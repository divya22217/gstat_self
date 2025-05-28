<link rel="stylesheet" href="../css/bootstrap.min.css">


<?php
session_start();
ob_start();
include("../db_inc1.php");
//if we remove db_inc2 then it stops working
date_default_timezone_set("Asia/Kolkata");

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);   */

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

	date_default_timezone_set("Asia/Kolkata");
	$server_date = date('Y-m-d'); //Returns IST 
	$appeals = main_case_type();
	function generate_case_no($schemas, $db, $case_type, $case_year, $case_no, $location_code)
	{
		$case_num = '';
		$case_short_name = $db->prepare("select short_name from case_type where id=?");
		$case_short_name->bindParam(1, $case_type, PDO::PARAM_INT);
		$case_short_name->execute();
		$case_short_name = $case_short_name->fetchColumn();

		$city_name = $db->prepare("select short_name from mater_location_city where city_id=?");
		$city_name->bindParam(1, $location_code, PDO::PARAM_INT);
		$city_name->execute();
		$city_name = $city_name->fetchColumn();

		if (!empty($case_type)) {

			$case_num = $case_short_name . "/" . $case_no . "($city_name)" . "/" . $case_year;
		}
		return $case_num;
	}

	function main_case_filing_no($schemas, $db, $filing_no)
	{
		$main_case_filing_no = $db->prepare("select main_case_ia_no from $schemas.case_detail where filing_no=?");
		$main_case_filing_no->bindParam(1, $filing_no, PDO::PARAM_INT);
		$main_case_filing_no->execute();
		$main_case_filing_no = $main_case_filing_no->fetchColumn();
		return $main_case_filing_no;
	}

	function display_date_format($date)
	{
		list($year, $month, $day) = explode('-', $date);
		$converted_date = $day . '/' . $month . '/' . $year;
		return $converted_date;
	}


	function main_case_no($schemas, $db, $case_type, $main_case_filing_no)
	{
		$case_type_array = array(35, 36, 37, 38, 39, 60);
		if (in_array($case_type, $case_type_array)) {

			//$main_case_filing_no = main_case_filing_no($schemas,$db,$filing_no); 
			$main_case_record = $db->prepare("select case_type,case_year,case_no,location_code from $schemas.case_detail where filing_no=?");
			$main_case_record->bindParam(1, $main_case_filing_no, PDO::PARAM_INT);
			$main_case_record->execute();
			$main_case_record = $main_case_record->fetchAll();
			$main_case_record = array_shift($main_case_record);

			$main_case_type = $main_case_record['case_type'];
			$main_case_year = $main_case_record['case_year'];
			$main_case_no = $main_case_record['case_no'];
			$location_code = $main_case_record['location_code'];


			$main_case_no = generate_case_no($schemas, $db, $main_case_type, $main_case_year, $main_case_no, $location_code);

			/* $next_list_date = $db->prepare("select next_list_date from $schemas.case_allocation where filing_no=? order by id desc limit 1");
		$next_list_date->bindParam(1, $main_case_filing_no, PDO::PARAM_INT);
		$next_list_date->execute();
		$next_list_date = $next_list_date->fetchColumn();
		
		if($next_list_date && $next_list_date != ''){
			$main_case_no .= "/$next_list_date";
		} */
		} else {
			$main_case_no = '';
		}
		return $main_case_no;
	}

	function main_case_next_list_date($schemas, $db, $case_type, $filing_no)
	{
		$main_case_next_listing_date = '';
		if ($case_type == 6) {
			$main_case_filing_no = main_case_filing_no($schemas, $db, $filing_no);
			$main_case_record = $db->prepare("select next_list_date from $schemas.case_allocation where filing_no=? order by id desc limit 1");
			$main_case_record->bindParam(1, $main_case_filing_no, PDO::PARAM_INT);
			$main_case_record->execute();
			$main_case_next_listing_date = $main_case_record->fetchColumn();
			if ($main_case_next_listing_date) {
				$main_case_next_listing_date = display_date_format($main_case_next_listing_date);
				return " (" . $main_case_next_listing_date . ")";
			}
		}
		return $main_case_next_listing_date;
	}

	function get_display_court_text($db, $schemas, $court_no)
	{
		$select = $db->prepare("select causelist_court_text from $schemas.court where court_no = ?");
		$select->bindParam(1, $court_no, PDO::PARAM_STR);
		$select->execute();
		$res = $select->fetchColumn();
		return $res;
	}

	function get_court($db, $schemas)
	{
		$courts = $db->prepare("select * from $schemas.court order by court_no");
		$courts->execute();
		$courts = $courts->fetchAll();
		return $courts;
	}

	function main_case_court_no_from_note($db, $schemas, $main_case_number)
	{
		$select = $db->prepare("select first_court_no from $schemas.scrutiny where filing_no = ?");
		$select->bindParam(1, $main_case_number, PDO::PARAM_STR);
		$select->execute();
		$res = $select->fetchColumn();
		return $res;
	}

	function main_case_court_no_from_allocation($db, $schemas, $main_case_number)
	{
		$select = $db->prepare("select court_no from $schemas.case_allocation_temp where filing_no = ? limit 1");
		$select->bindParam(1, $main_case_number, PDO::PARAM_STR);
		$select->execute();
		$res = $select->fetchColumn();
		return $res;
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
						<?php
						$s2 = 'listingclerk';


						?>
						<section class="content-header">
							<center><b>Link Caveat</b></center>
						</section>
						<?php
						$msghash = $_REQUEST['msghash'];
						$courts = get_court($db, $schemas);
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
								<thead style="background-color:#00a65a;color:#ffffff;">
									<th><b>Sr.No.</b></th>
									<th align="left" width="150"><b>Diary No.</b></th>
									<th align="left" width="700"><B>Case No </b></th>
									<th align="left" width="700"><B>Main Case No </b></th>
									<th align="left" width="700"><B>Cavetor </b></th>
									<th align="left" width="250"><B>Date Of Filing</b></th>
									<th align="left" width="250"><B>Date Of Registration</b></th>
									<th align="left" width="700"><B>Link Case </b></th>
								</thead>
								<tbody id="search_data_here">
									<?php
									$count = 0;

									$sql1 = $db->prepare("select a.filing_no,a.case_no,a.case_type,a.case_year,a.pet_name,a.res_name,a.dt_of_filing,a.location_code,a.regis_date,a.main_case_ia_no, 
					ecd.patially_defective from $schemas.case_detail as a 
					left join e_case_detail as ecd on ecd.filing_no = a.filing_no
					where (a.case_no is NOT NULL OR a.case_no != '') and (a.case_year is NOT NULL OR a.case_year != '') and (a.case_type is NOT NULL and a.case_type = 60) and 
					(a.location_code is NOT NULL) and  (a.legal_aid IS NULL OR a.legal_aid = 'NULL') and a.status = 'P' order by a.filing_no asc");
									$sql1->execute();
									while ($row1 = $sql1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
										$filing_no = $row1['filing_no'];
										$main_case_number = $direct_parent_filing_no = $row1['main_case_ia_no'];
										$filing_date = $row1['dt_of_filing'];
										$regis_date = $row1['regis_date'];
										$case_type = $row1['case_type'];
										if (in_array($case_type, $appeals)) {
											$show_party_filing_no = htmlspecialchars($filing_no);
										} else {
											$show_party_filing_no = htmlspecialchars($direct_parent_filing_no);
											if (empty($show_party_filing_no))
												$show_party_filing_no = htmlspecialchars($filing_no);
										}
										$pet_name = get_party($db, $show_party_filing_no, 'P', 1);
										$pet_name = strtoupper($pet_name);
										$res_name = get_party($db, $show_party_filing_no, 'R', 1);
										$res_name = strtoupper($res_name);
										$location_code = $row1['location_code'];
										$case_no = $row1['case_no'];
										$case_year = $row1['case_year'];
										$for_listing = $row1['first_listing_date'];
										$for_court = $row1['first_court_no'];
										$is_partially_defective = $row1['patially_defective'];
										if ($is_partially_defective == 1)
											$d_searis = 'D';
										else
											$d_searis = '';

										$get_caveators = get_cavetor_info($db, $filing_no);

										$count++;


									?>
										<tr>
											<td><?php echo $count; ?></td>

											<td><?php echo $filing_no . "  <em style='color:red;'>" . $d_searis . "</em>"; ?></td>

											<td><?php echo generate_case_no($schemas, $db, $case_type, $case_year, $case_no, $location_code); ?></td>
											<td>
												<?php if (!empty($main_case_number)) {
													echo main_case_no($schemas, $db, $case_type, $main_case_number);
												} else {
													echo 'Not Linked Yet';
												}
												//echo main_case_next_list_date($schemas,$db,$case_type,$filing_no);
												?>

											</td>

											<td><?php foreach ($get_caveators as $key => $cav) {
													if ($key == 0)
														$comma = '';
													else
														$comma = ',';

													echo $comma . $cav['name'];
												}  ?>
											</td>

											<td>
												<?php
												if ($filing_date != '') {
													echo display_date_format($filing_date);
												} ?>
											</td>
											<td>
												<?php
												if ($regis_date != '') {
													echo display_date_format($regis_date);
												} ?>
											</td>
											<td>
												<?php if (empty($main_case_number)) { ?>
													<button type="button" id="link_case_<?php echo $filing_no; ?>" class="btn brn-sm btn-success link_caveat" name="link_case_<?php echo $filing_no; ?>" onClick="return link_cav_form('<?php echo $filing_no; ?>');">Link</button>
												<?php } ?>
											</td>
										</tr>


								<?php

									}
									// for paginatin links start
									/* if($page != 0){
									echo $pagination->paginate($pages,$page,$colspan=7);
								  } */
									// for paginatin links end
								}

								?>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- computation note modal -->
		<div id="link_cav_modal" class="modal fade" role="dialog">
			<div class="modal-dialog" style="width:90%;">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body" id="link_cav_modal_body">
						<p>Some text in the modal.</p>
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
			$(document).ready(function() {
				$('.casealloc_table').dataTable({
					"lengthMenu": [
						[50, 100, 200, -1],
						[50, 100, 200, "All"]
					],
					"pageLength": 100
				});
			});

			function link_cav_form(filing_no) {
				$.ajax({
					type: "POST",
					url: "../custom/link_cav_ajax.php",
					data: {
						type: 'link_cav_form',
						filing_no: filing_no
					},
					success: function(data) {
						$("#link_cav_modal_body").html(data);
						$("#link_cav_modal").modal('show');
					},
					error: function(textStatus, errorThrown) {
						alert("error");
						$("#link_cav_modal").modal('hide');
					}
				});
			}

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
			}

			function IsValidJSONString(str) {
				try {
					JSON.parse(str);
				} catch (e) {
					return false;
				}
				return true;
			}

			function search_data(filing_no) {
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
					url: "../custom/link_cav_ajax.php",
					data: {
						type: 'search_case',
						filing_no_cav: filing_no,
						case_type: case_type,
						case_no: case_no,
						case_year: case_year
					},
					success: function(data) {
						var IsValidJSON = IsValidJSONString(data);
						if (IsValidJSON) {
							var obj = JSON.parse(data);
							if (obj.status == '0') {
								$("#show_case_detail").html("");
								swal("", obj.message, "warning");
								return false;
							}
						} else {
							$("#show_case_detail").html(data);
							// $("#enter_case_info").css("display","none");
							$("#case_no").attr("disabled", "disabled");
							$("#case_year").attr("disabled", "disabled");
							$("#search_case_type").attr("disabled", "disabled");
						}
					},
					error: function(textStatus, errorThrown) {
						console.log(textStatus);
						alert(errorThrown);
					}

				});

			}

			function link_case(cav_filing_no, main_case_filing_no) {
				swal({
						title: "Are You Sure",
						text: "Do you want to link case",
						type: "warning",
						showCancelButton: true,
						closeOnConfirm: false,
						confirmButtonText: "Yes, Link case",
						// showLoaderOnConfirm: true,
						animation: "slide-from-top",
					},
					function(isConfirm) {
						if (isConfirm) {
							$.ajax({
								type: "POST",
								url: "../custom/link_cav_ajax.php",
								data: {
									type: 'link_case',
									cav_filing_no: cav_filing_no,
									main_case_filing_no: main_case_filing_no
								},
								dataType: 'json',
								success: function(response) {
									if (response.status == 1) {
										swal('', response.message, 'success');
										setTimeout(function() {
											location.reload(true);
										}, 3000);
									} else {
										swal('Oops!!', response.message, 'error');
									}
								},
								error: function(textStatus, errorThrown) {
									console.log(textStatus);
									swal("Oops!!", errorThrown, 'error');
								}
							});
						} else {}
					});

			}


			function save_reg_listing_date(filing_no) {
				var reg_listing_date = $("#reg_list_date_" + filing_no).val();
				var reg_court_no = $("#reg_court_no_" + filing_no).val();
				if (reg_listing_date === '') {
					//swal('','Please enter listing date','error');
					alert('Please enter listing date');
					return false;
				}
				if (reg_court_no === '') {
					//swal('','Please select Court','error');
					alert('Please select Court');
					return false;
				}
				$.ajax({
					type: "POST",
					url: "get_cases_for_allocation.php",
					data: {
						type: 'save_reg_listing_date',
						filing_no: filing_no,
						reg_listing_date: reg_listing_date,
						reg_court_no: reg_court_no
					},
					success: function(data) {
						if (data) {
							alert("Listing date updated");
							location.reload(true);
						} else {
							alert("some error occured");
						}
					},
					error: function(textStatus, errorThrown) {
						alert("error");
					}
				});
			}
		</script>
	</body>