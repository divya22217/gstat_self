<!DOCTYPE html>
<html>

<head>
	<title>GSTAT|Scrutiny</title>
	<style>
		body {
			background-color: white;
		}

		h1 {
			color: maroon;
			margin-left: 40px;
		}

		@media print {
			#testdiv {
				display: none;
			}
		}

		div.hidden {
			display: none;
		}

		.load_container {
			background: rgba(0, 0, 0, 0.50);
			width: 100%;
			height: 100%;
			position: fixed;
			top: 0;
			z-index: 99;
			left: 0;
		}

		.load_container .loader {
			display: block;
			width: 60px;
			height: 60px;
			position: absolute;
			top: 50%;
			left: 0;
			right: 0;
			margin: auto;
		}

		a.disabled {
			color: grey;
			pointer-events: none;
			text-decoration: none
		}

		/* Code By Ravi Kumar */
		#testdiv a {
			color: darkblue;
			font-size: large;
			text-align: right;
		}

		input[type="radio"] {
			display: none;
		}

		.custom-radio {
			display: inline-block;
			width: 16px;
			height: 16px;
			border: 2px solid darkblue;
			border-radius: 50%;
			position: relative;
			cursor: pointer;
			margin-right: 8px;
			vertical-align: middle;
		}

		input[type="radio"]:checked+.custom-radio {
			background-color: white;
			border: 1px solid darkblue;
		}

		input[type="radio"]:checked+.custom-radio::after {
			content: "";
			width: 8px;
			height: 8px;
			background-color: darkblue;
			border-radius: 50%;
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
		}

		label b {
			font-size: 14px;
			font-family: Arial, sans-serif;
			vertical-align: middle;
		}

		.radio-container {
			display: inline-block;
			padding: 3px 10px;
			border-radius: 5px;
			margin: 1px;
			text-align: center;
			margin-left: 50px;
			cursor: pointer;
		}

		.radio-container:hover {
			cursor: pointer;
		}

		.radio-container label {
			margin: 0;
			display: flex;
			align-items: center;
			gap: 8px;
		}

		.radio-container.checked {
			border: 1px solid darkblue;
		}
	</style>
</head>

<body>

	<?php

	header("Cache-Control: private");
	header("Cache-Control: no-cache, no-store, must-revalidate");
	header("Pragma: no-cache");
	header("Cache-Control=proxy-revalidate");

	date_default_timezone_set("Asia/Kolkata");
	$server_date = date('Y-m-d');

	include("./db_inc1.php");

	include_once('custom/custom_function.php');


	$_SESSION['user'];

	$_SESSION['location'];
	$leveladd = $_SESSION['level_level'];
	$localadmin = $_SESSION['localadmin'];
	$main_id = $_SESSION['main_id'];
	$schemas = htmlspecialchars($_SESSION['schema_name']);
	$userid = $_SESSION['id'];
	$sessionUserType = htmlspecialchars($_SESSION['id']);
	$location_access = $_SESSION['location'];
	$schema_id = $_SESSION['schema_idccc'];

	if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
		echo "Access Problem.....";
		header("Location: ./login.php");
		die();
	}

	setcookie("PHPSESSID", "", time() - 3600, "", "", TRUE, TRUE);


	$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
	$key = $_SESSION['csrf'];

	// At the top of the page we check to see whether the user is logged in or not
	if (empty($_SESSION['user']) and $_SESSION['location'] == '') {
		die("#2E2E2Eirecting to login.php");
	}

	if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {

		function get_caseno_doc($case_no, $casetype, $locode, $case_year)
		{
			global $db;
			global $schemas;
			$casetypesql = $db->prepare("select case_type_desc_cis from case_type where id = '$casetype'");
			$casetypesql->execute();
			$case_type_short_name = $casetypesql->fetchColumn();

			$case_type_short_name = strtoupper($case_type_short_name);

			$lcodesql = "select short_name from $schemas.bench_location where bench_location_code ='$locode'";
			$lcodesql = $db->prepare($lcodesql);
			$lcodesql->execute();
			$lcodename = $lcodesql->fetchColumn();
			$lcodename;

			return $case_no_final = $case_type_short_name . '/' . $case_no . '(' . $lcodename . ')' . $case_year;
		}

		function display_filing_no($filing_no_display)
		{
			$lastFour =  substr($filing_no_display, -4);
			$lastFive = substr($filing_no_display, -9, -4);
			$left = substr($filing_no_display, -16, -9);
			return $dis_fil_no = $left . '/<b>' . $lastFive . '/' . $lastFour . '</b>';
		}


		$sessionUserType = htmlspecialchars($_SESSION['id']);


		$curYear = htmlspecialchars(date("Y"));
		$curMonth = htmlspecialchars(date("m"));
		$curDay = htmlspecialchars(date("d"));
		$cur_date = "$curYear-$curMonth-$curDay";
		$cur_date1 = "$curDay/$curMonth/$curYear";
		$link_scrutiny_idaccess = '1';
		include 'header.php';
		$hash2 = $_REQUEST['hash2'];

		if ($hash2) {
			$c_case = htmlspecialchars(base64_decode($hash2));
			if ($c_case == 'R') {
				$showradio = 4;
			}
			if ($c_case == 'C') {
				$showradio = 2;
			}
			if ($c_case == 'F') {
				$showradio = 1;
			}
		} else {
			$showradio = 1;
		}

		$_SESSION['qqcc'] = rand();
		$qq1cc = $_SESSION['qqcc'];
	?>

		<div class="content-wrapper">

			<form name="frm" method="post">

				<section class="content">

					<div class="row">
						<div class="col-md-12">
							<?php

							if ($localadmin == '0' and $_SESSION['menuaccess_codeall'] == '3') {

								$c_case = isset($_REQUEST['c_case']) ? $_REQUEST['c_case'] : 12;
							?>

								<table class="table no-margin">
									<thead>
										<tr>
											<th>

											<div class="radio-container">
																<label>
																<input type="radio"  class="radio-btn" name="c_case" value="12" onChange="javascript:submitForm3();" <?php if ($c_case == 12) { echo 'checked';} ?>>
																	<span class="custom-radio"></span>
																	<b>Cause List</b>&nbsp;&nbsp;
																</label>
															</div> </th>
											<th>

											<div class="radio-container">
																<label>
																<input type="radio"  class="radio-btn" name="c_case" value="13" onChange="javascript:submitForm3();" <?php if ($c_case == 13) { echo 'checked';} ?>>
																	<span class="custom-radio"></span>
																	<b>Timeline Cases</b>&nbsp;&nbsp;
																</label>
															</div> 


											
											</th>
										</tr>
									</thead>
								</table>
								<?php
								if ($c_case == 12) {
								?>
									<div class="form-group row">
										<label for="listing_Date" class="col-sm-offset-2 col-sm-2 form-label">
											<font color="red">*</font></span></font>Date:(DD/MM/YYYY)
										</label>
										<div class="col-sm-6">
											<input type="text" id="next_list_date" name="next_list_date" autocomplete="off" class="form-control datepicker" size="10" onchange="show_causelist(this.value);" value="<?php print htmlspecialchars($next_list_date); ?>" />
										</div>
									</div>

									<script src="./src/calendar.js"></script>
									<div id="show_content">
									</div>
								<?php
									include_once('custom/member_dashboard.php');
									//die("sdf");
								}
								if ($c_case == 13) {
									include_once('custom/timeline_cases.php');
								}
							}


							if ($localadmin == '0' and $_SESSION['menuaccess_codeall'] == '2') {


								?>
								<div class="box box-info">
									<?php
									$hash = htmlspecialchars($_REQUEST['hash']);
									if ($hash != '') {
										$hash1 = htmlspecialchars(base64_decode($hash));
										$hash1 = explode("-", $hash1);
										$massage = $hash1[0];
										$token_filing_no_scrutiny = $hash1[1];
									?>
										<div class="alert alert-success" role="alert">
											<?php echo htmlspecialchars($massage); ?>
										</div>
									<?php }
									$c_case = isset($_REQUEST['c_case']) ? $_REQUEST['c_case'] : $showradio; ?>
									<div class="box-body">
										<div class="table-responsive">
										<table class="table no-margin">
												<thead>
													<tr>
														<th>
															<div class="radio-container">
																<label>
																	<input type="radio" class="radio-btn" name="c_case" value="1" onchange="javascript:submitForm3();" <?php if ($c_case == 1) echo 'checked'; ?>>
																	<span class="custom-radio"></span>
																	<b>Fresh case for scrutiny</b>&nbsp;&nbsp;
																</label>
															</div>
														</th>
														<th>
															<div class="radio-container">
																<label>
																	<input type="radio" class="radio-btn" name="c_case" value="5" onchange="javascript:submitForm3();" <?php if ($c_case == 5) echo 'checked'; ?>>
																	<span class="custom-radio"></span>
																	<b>Defective cases</b>&nbsp;&nbsp;
																</label>
															</div>
														</th>
														<th>
															<div class="radio-container">
																<label>
																	<input type="radio" class="radio-btn" name="c_case" value="8" onchange="javascript:submitForm3();" <?php if ($c_case == 8) echo 'checked'; ?>>
																	<span class="custom-radio"></span>
																	<b>Refiled Cases</b>&nbsp;&nbsp;
																</label>
															</div>
														</th>
														<th>
															<div class="radio-container">
																<label>
																	<input type="radio" class="radio-btn" name="c_case" value="9" onchange="javascript:submitForm3();" <?php if ($c_case == 9) echo 'checked'; ?>>
																	<span class="custom-radio"></span>
																	<b>Return Cases</b>&nbsp;&nbsp;
																</label>
															</div>
														</th>
														<th>
															<div id="testdiv" style="visibility: visible;">
																<a href="javascript:window.print();" style="text-decoration: none; color: darkblue; font-size: 18px;">
																	PRINT
																</a>
															</div>
														</th>
													</tr>
												</thead>
											</table>
										</div>
									</div>
									<?php
									$app_pet = isset($_REQUEST['app_pet']) ? $_REQUEST['app_pet'] : 'P';
									if ($c_case == "1") {
										include_once('custom/fresh_cases.php');
									}
									if ($c_case == "9") {
										include_once('custom/return_cases.php');
									}
									if ($c_case == "5") {
										include_once('custom/defective_cases.php');
									}
									if ($c_case == "8") {
										include_once('custom/refiled_cases.php');
									}
									?>
								</div>
							<?php

							}

							if ($localadmin == '0' and $_SESSION['menuaccess_codeall'] == '11') {

							?>
								<div class="box box-info">
									<?php
									$hash = htmlspecialchars($_REQUEST['hash']);
									if ($hash != '') {
										$hash1 = htmlspecialchars(base64_decode($hash));
										$hash1 = explode("-", $hash1);
										$massage = $hash1[0];
										$token_filing_no_scrutiny = $hash1[1];
									?>
										<div class="alert alert-success" role="alert">
											<?php echo htmlspecialchars($massage); ?>
										</div>
									<?php }
									$c_case = isset($_REQUEST['c_case']) ? $_REQUEST['c_case'] : $showradio; ?>
									<div class="box-body">
										<div class="table-responsive">
										<table class="table no-margin">
                                                <thead>
                                                    <tr>

                                                        <th>
                                                            <div class="radio-container">
                                                                <label>
                                                                    <input type="radio" class="radio-btn" name="c_case" value="1" onChange="javascript:submitForm3();" <?php if ($c_case == 1) {
                                                                                                                                                                            echo 'checked';
                                                                                                                                                                        } ?>>
                                                                    <span class="custom-radio"></span>
                                                                    <b>Fresh case</b>&nbsp;&nbsp;
                                                                </label>
                                                            </div>
                                                        </th>

                                                        <th>
                                                            <div class="radio-container">
                                                                <label>
                                                                    <input type="radio" class="radio-btn" name="c_case" value="3" onChange="javascript:submitForm3();" <?php if ($c_case == 3) {
                                                                                                                                                                            echo 'checked';
                                                                                                                                                                        } ?>>
                                                                    <span class="custom-radio"></span>
                                                                    <b>Refiled Cases</b>&nbsp;&nbsp;
                                                                </label>
                                                            </div>
                                                        </th>

                                                        <th>
                                                            <div class="radio-container">
                                                                <label>
                                                                    <input type="radio" class="radio-btn" name="c_case" value="5" onChange="javascript:submitForm3();" <?php if ($c_case == 5) {
                                                                                                                                                                            echo 'checked';
                                                                                                                                                                        } ?>>
                                                                    <span class="custom-radio"></span>
                                                                    <b>Defective cases</b>&nbsp;&nbsp;
                                                                </label>
                                                            </div>
                                                        </th>
                                                        <!--<th>
                                                            <div class="radio-container">
										                    	<input type="radio" class="radio-btn" name="c_case" value="6" onChange="javascript:submitForm3();" <?php if ($c_case == 6) {
                                                                                                                                                                        echo 'checked';
                                                                                                                                                                    } ?>>
                                                                    <span class="custom-radio"></span>
                                                                    <b>Create Summary Note</b>&nbsp;&nbsp;
                                                            </div>
										                </th>-->
										                <?php if($location_access != 1) { ?>
                                                        <th>
                                                            <div class="radio-container">
                                                                <label>
                                                                    <input type="radio" class="radio-btn" name="c_case" value="7" onChange="javascript:submitForm3();" <?php if ($c_case == 7) {
                                                                                                                                                                            echo 'checked';
                                                                                                                                                                        } ?>>
                                                                    <span class="custom-radio"></span>
                                                                    <b>Case No Generation</b>&nbsp;&nbsp;
                                                                </label>
                                                            </div>
                                                        </th>
                                                    <?php } ?>
                                                        <th>
                                                            <div class="radio-container">
                                                                <label>
                                                                    <input type="radio" class="radio-btn" name="c_case" value="10" onChange="javascript:submitForm3();" <?php if ($c_case == 10) {
                                                                                                                                                                            echo 'checked';
                                                                                                                                                                        } ?>>
                                                                    <span class="custom-radio"></span>
                                                                    <b>Timeline Cases</b>&nbsp;&nbsp;
                                                                </label>
                                                            </div>
                                                        </th>


                                                        <th>
                                                            <div id="testdiv" style="visibility: visible;">
                                                                <a href="javascript:window.print();" style="text-decoration: none; color: darkblue; font-size: 18px;">
                                                                    <!-- <i class="fas fa-print"></i>--> PRINT
                                                                </a>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
										</div>
									</div>
									<?php
									$app_pet = isset($_REQUEST['app_pet']) ? $_REQUEST['app_pet'] : 'P';
									if ($c_case == "1") {
										include_once('custom/fresh_cases_ar.php');
									}

									if ($c_case == "3") {
										include_once('custom/refiled_cases_ar.php');
									}
									if ($c_case == "5") {
										include_once('custom/defective_cases.php');
									}
									if ($c_case == "6") {
										include_once('custom/summary_note.php');
									}
									if ($c_case == "7") {
										include_once('custom/case_no_generation.php');
									}
									if ($c_case == "10") {
										include_once('custom/timeline_cases.php');
									}
									?>
								</div>
							<?php

							}  

							if ($localadmin == '0' and $_SESSION['menuaccess_codeall'] == '6') {
							 ?>
							 <div class="box box-info">
									<?php
									$hash = htmlspecialchars($_REQUEST['hash']);
									if ($hash != '') {
										$hash1 = htmlspecialchars(base64_decode($hash));
										$hash1 = explode("-", $hash1);
										$massage = $hash1[0];
										$token_filing_no_scrutiny = $hash1[1];
									?>
										<div class="alert alert-success" role="alert">
											<?php echo htmlspecialchars($massage); ?>
										</div>
									<?php }
									$c_case = isset($_REQUEST['c_case']) ? $_REQUEST['c_case'] : 7; ?>
									<div class="box-body">
										<div class="table-responsive">
										<table class="table no-margin">
                                                <thead>
                                                    <tr>
                                                    	<th>
                                                            <div class="radio-container">
                                                                <label>
                                                                    <input type="radio" class="radio-btn" name="c_case" value="7" onChange="javascript:submitForm3();" <?php if ($c_case == 7) {
                                                                                                                                                                            echo 'checked';
                                                                                                                                                                        } ?>>
                                                                    <span class="custom-radio"></span>
                                                                    <b>Case No Generation</b>&nbsp;&nbsp;
                                                                </label>
                                                            </div>
                                                        </th>
                                                    </tr>
                                            </thead>
                                        </table>
									</div>
								</div>
								<?php
								$app_pet = isset($_REQUEST['app_pet']) ? $_REQUEST['app_pet'] : 'P';
								if ($c_case == "7") {
										include_once('custom/case_no_generation.php');
									}
								?>
							<?php } ?>
						</div>
					</div>
				</section>
			</form>
		</div>
	<?php	}
	?>
	<script type="text/javascript" language="javascript">
		//$('.load_container').fadeOut(500);
		function change() {
			with(document.frm) {
				action = "index.php";
				submit();

			}
		}

		function submitForm() {
			with(document.frm) {
				action = "index.php";
				submit();
				document.frm_doc_search.submit1.disabled = true;
				document.frm_doc_search.submit1.value = 'Please Wait...';
				return true;
			}
		}

		function DisableBackButton() {
			window.history.forward()
		}

		DisableBackButton();
		window.onload = DisableBackButton;
		window.onpageshow = function(evt) {
			if (evt.persisted) DisableBackButton()
		}
		window.onunload = function() {
			void(0)
		}

		function submitForm3() {
			with(document.frm) {
				action = "index.php";
				submit();
			}
		}

		function reset_case() {
			$("#filing_no").val('');
			$("#selected_case_type").val('');
			$("#from_date").val('');
			$("#to_date").val('');
			with(document.frm) {
				action = "index.php";
				submit();
			}
		}

		function assignToMyself(cis_user_id, filling_number, court_id) {
			$.ajax({
				url: 'custom/scrutiny_model.php', // URL to fetch data from
				method: 'POST', // HTTP method
				data: {
					cis_user_id: cis_user_id,
					court: court_id,
					filling_no: filling_number,
					method: "assignToMyself"
				},
				dataType: 'json', // Expected data type from server
				success: function(response) {
					//console.log(response);
					if (response.status == '200') {
						alert("Diary Number assigned successfully");
						location.reload();
					} else {
						alert(response.msg);
					}
				},
				error: function(xhr, status, error) {
					alert(error);
				}
			});
		}
	</script>

	<?php include 'footer1.php'; ?>



	<script>
		// radio buttons logic
		document.addEventListener("DOMContentLoaded", function() {
            const radioButtons = document.querySelectorAll('.radio-btn');

            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {

                    document.querySelectorAll('.radio-container').forEach(container => {
                        container.classList.remove('checked');
                    });

                    if (radio.checked) {
                        radio.closest('.radio-container').classList.add('checked');
                    }
                });

                if (radio.checked) {
                    radio.closest('.radio-container').classList.add('checked');
                }
            });
        });


		$(document).on('click', '#upload_return_timeline', function(e) {
			if (confirm('Are you sure ?')) {
				var remark_return_cases = $("#remark_return_cases").val();
				if (remark_return_cases == '') {
					alert('Please Enter Remark');
				} else {
					var data = {};
					data['action'] = 'return_cases_timeline';
					data['filing_no'] = $("#hidden_filling_no11111").val();
					data['message'] = $("#remark_return_cases").val();
					console.log(data);
					//return false;
					$.ajax({
						type: "POST",
						url: "./ajax/return_ajax.php",
						data: data,
						dataType: 'html',
						success: function(data11) {
							alert('Message are send sucessfully.');
							location.href = 'https://e-commcourt.gov.in/gstat/index.php';
						},
						error: function(request, error) {
							alert('Something error.');
							console.log("Something error.");
						}
					});
				}
			}
		});
	</script>

</body>




<div id="comp_note" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:90%;">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" id="comp_note_body">
				<p>Some text in the modal.</p>
			</div>
		</div>

	</div>
</div>

<!-- computation note modal -->
<div id="view_doc" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:90%;">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" id="view_doc_body">
				<p>Some text in the modal.</p>
			</div>
		</div>

	</div>
</div>

<script>
	$(document).ready(function(e){
		
		var cis_user_id =<?php echo $_SESSION['id']; ?>;
		var court =<?php echo $_SESSION['user_court']; ?>;
		var schema_id =<?php echo $_SESSION['schema_idccc']; ?>;
		var menu_accesscode=<?php echo  $_SESSION['menuaccess_codeall'];?>;
		var schemas ='<?php echo  $_SESSION['schema_name'];?>';
		//console.log(schemas);
		
		$.ajax({
			type:'POST',
			url:'notifications/Notification.php',
			data:{
				method:"saveReminder",
				cis_user_id:cis_user_id,
				court:court,
				schema_id:schema_id,
				schemas:schemas,
				menu_accesscode:menu_accesscode
			},
			success:function(response){
				console.log(response);
				 e.preventDefault();
				return false;
			},
			error: function(xhr, status, error) {
					//alert(error);
				}
			
		})
	});

	</script>

</html>
