<?php
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  */
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
session_start();

$_SESSION['user'];
$_SESSION['location'];
$leveladd = $_SESSION['level_level'];
$localadmin = $_SESSION['localadmin'];
$main_id = $_SESSION['main_id'];
$schemas = htmlspecialchars($_SESSION['schema_name']);
$userid = $_SESSION['id'];

if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
	echo "Access Problem.....";
	header("Location: ../login.php");
	die();
}
$c_case = $_REQUEST['ccase'];

function remove_path($file, $path = 'UPLOAD_PATH')
{
	if (strpos($file, $path) !== FALSE) {
		return substr($file, strlen($path));
	}
}

setcookie("PHPSESSID", "", time() - 3600, "", "", TRUE, TRUE);


$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key = $_SESSION['csrf'];

// At the top of the page we check to see whether the user is logged in or not
if (empty($_SESSION['user']) and $_SESSION['location'] == '') {
	die("#2E2E2Eirecting to login.php");
}

if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {
	$filing_no_next = $_REQUEST['filing_no_next'];

	$hash1 = htmlspecialchars(base64_decode($filing_no_next));
	$hash1 = explode("-", $hash1);
	$miscellaneous_no = $hash1[0];
	$filing_no_fou = $hash1[1];

	$token_fou = $hash1[2];

	$hash = $_REQUEST['doc_hash'];
	$hash = htmlspecialchars(base64_decode($hash));
	if (!$hash || $hash == '') {
		echo "You can't access this page.....";
		header("Location: ../login.php?aa=1001");
		die();
	}
	/* if($hash != $_SESSION['random_key'])
	   {
		   echo "You can't access this page.....";
		   header("Location: ../login.php?aa=100");
		   die();
	   } */



	if ($_SESSION['qqcc'] != $token_fou) {
		echo "Access Problem.....";
		header("Location: ../login.php?aa=1002");
		die();
	}
	if ($token_fou == '') {
		echo "Access Problem.....";
		header("Location: ../login.php?aa=1003");
		die();
	}



	// This code not use next time .......	Schema session create Hear....

	$location_access = $_SESSION['location'];
	$sessionUserType = htmlspecialchars($_SESSION['id']);


	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 = "$curDay/$curMonth/$curYear";


	include '../inheader.php';
	//include '../insidebar.php';

?>
	<?php

	$form2 = sha1(uniqid('auth', true));
	$_SESSION['form2_scruniny'] = $form2;
	?>


	<script>
		/*$( window ).load(function() {
			alert('sadsad');
	  console.log( "window loaded" );
	  myFunction();
  
	  });
		*/
	</script>

	<body onload="myFunction()">
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->

			<!-- Main content -->
			<section class="content">
				<?php
				$hash = $_REQUEST['hash'];

				if ($hash != '') {

					$hash1 = htmlspecialchars(base64_decode($hash));
					$hash1 = explode("/", $hash1);
					$massage = $hash1[0];
					$filing_no_backpage = $hash1[1];
					$filing_no_backpage_print = htmlspecialchars(base64_decode($filing_no_backpage));

					echo "<center></br><font color='red' size='4'>" . htmlspecialchars($msg) . '</br>';
				}
				?>
				<!-- Default box -->
				<div style='text-align:center;'><a href="../scrutiny/document_scrutiny.php" class="text-danger font-weight-bold">
						<< BACK << </a>
				</div>
				<div class="box">
					<div class="box-header with-border">
						<h4 class="box-title">Case Scurtiny &nbsp;&nbsp;
							Diary No :
							<?php echo htmlspecialchars($filing_no_fou); ?>
							&nbsp;&nbsp;
							<?php

							$get_case_info = $db->prepare("select case_type,filing_no,main_case_ia_no as ia_ma_filing_no from $schemas.case_detail where filing_no=?");
							$get_case_info->bindParam(1, $filing_no_fou, PDO::PARAM_STR);
							$get_case_info->execute();
							$get_case_info = $get_case_info->fetchAll();
							$get_case_info = array_shift($get_case_info);
							$case_type = $get_case_info['case_type'];
							$scrutinized_filing_no = $get_case_info['filing_no'];
							$filing_no_ia_ma = $get_case_info['ia_ma_filing_no'];

							if ($filing_no_ia_ma == '') {
								$final_filing_no = $scrutinized_filing_no;
							} else {
								$final_filing_no = $filing_no_ia_ma;
							}

							$st1 = $db->prepare("select * from e_case_detail where filing_no=? and location_id=? ");
							$st1->bindParam(1, $final_filing_no, PDO::PARAM_STR);
							$st1->bindParam(2, $location_access, PDO::PARAM_STR);
							$st1->execute();
							while ($row = $st1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

								$filing_no = htmlspecialchars($row['filing_no']);

								$dt_of_filing = htmlspecialchars($row['dt_of_filing']);
							}

							$E_party_flag1 = 'P';
							$E_party_serial_no1 = '1';

							$st33 = $db->prepare("select * from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
							$st33->bindParam(1, $filing_no, PDO::PARAM_STR);
							$st33->bindParam(2, $E_party_flag1, PDO::PARAM_STR);
							$st33->bindParam(3, $E_party_serial_no1, PDO::PARAM_STR);
							$st33->execute();

							while ($row = $st33->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

								$E_party_flagP = $row['party_flag'];
								$E_party_serial_noP = $row['party_serial_no']; //0
								$E_nameP = $row['name']; //0
								$E_party_org_typeP = $row['party_org_type']; //0
								$E_party_org_contact_personP = $row['party_org_contact_person'];
								//$E_party_addressP=$row['party_address']; 
								$E_pinP = $row['pin']; //0
								$E_state_codeP = $row['state_code']; //0
								$E_district_codeP = $row['district_code']; //0
								$E_nationalityP = $row['nationality'];
								$E_emailP = $row['email'];
								$E_mobileP = $row['mobile'];
								$E_representative_codeP = $row['representative_code']; //0
								$E_aadhar_noP = $row['aadhar_no']; //0
								$E_cin_noP = $row['cin_no']; //0
							}

							$E_party_flag1 = 'R';
							$E_party_serial_no1 = '1';
							$st34 = $db->prepare("select * from e_cases_party where filing_no=? and party_flag=? and party_serial_no=? ");
							$st34->bindParam(1, $filing_no, PDO::PARAM_STR);
							$st34->bindParam(2, $E_party_flag1, PDO::PARAM_STR);
							$st34->bindParam(3, $E_party_serial_no1, PDO::PARAM_STR);
							$st34->execute();
							while ($row = $st34->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

								$E_party_flagR = $row['party_flag'];
								$E_party_serial_noR = $row['party_serial_no']; //0
								$E_nameR = $row['name']; //0
								$E_party_org_typeR = $row['party_org_type']; //0
								$E_party_org_contact_personR = $row['party_org_contact_person'];
								//$E_party_addressR=$row['party_address'];
								$E_pinR = $row['pin']; //0
								$E_state_codeR = $row['state_code']; //0
								$E_district_codeR = $row['district_code']; //0
								$E_nationalityR = $row['nationality'];
								$E_emailR = $row['email'];
								$E_mobileR = $row['mobile'];
								$E_representative_codeR = $row['representative_code']; //0
								$E_aadhar_noR = $row['aadhar_no']; //0
								$E_cin_noR = $row['cin_no']; //0
							}
							?>
							<font color="#0000FF">
								<?php echo htmlspecialchars_decode(strtoupper($E_nameP)) . '&nbsp; Vs. &nbsp;' . htmlspecialchars_decode(strtoupper($E_nameR)); ?>
							</font>
						</h4>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
								<i class="fa fa-minus"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
								<i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">

						<div class="form-group">
							<div class="col-md-12">

								<script>
									function defect_submit() {
										//validate3();

										with(document.form2) {
											var validformat = /^\d{2}\/\d{2}\/\d{4}$/
											if (!validformat.test(notification_date.value)) {
												alert("Invalid Date Format. Correct Date Format (dd/mm/yyyy)")
												return false;
											}

											if (searchby.options[searchby.selectedIndex].value == "0") {
												alert("Please Select Defect/ Defect Free  ");
												searchby.focus();
												return false;
											}

											var status1 = "";
											//alert('self submit');	
											var tnl = document.getElementsByName("status");
											for (i = 0; i < tnl.length; i++) {

												var val = tnl[i].value;
												var status1 = status1 + val + ','
											}
											if (!document.getElementById('agree').checked) {
												alert('You must agree to the terms first.');
												//agree.focus();
												return false;
											}


											action = "document_scrutiny_action.php?test=" + status1;
											submit();
											document.form2.submit_final.disabled = true;
											document.form2.submit_final.value = 'Please Wait...';
											return true;
										}
									}
								</script>
								<script>
									/* function myFunction() {
								
										 with(document.form2)
											  { 
								
											 var tnl = document.getElementById("status");
										 
												for(i=0;i<tnl.length;i++){
													if(tnl[i].selected == true){
														alert(tnl[i].value);
													}
												}
								
								
									
											  }
									}
									 */


									function myFunction() {

										var tnl = document.getElementsByName("status");

										var val1 = ""

										for (i = 0; i < tnl.length; i++) {

											var val = tnl[i].value;
											if (val == 'NO') {
												val1 = 'NO';
											}


										}
										if (val1 == "") {
											val1 = 'YES';
										}

										if (window.XMLHttpRequest) {

											xmlhttp = new XMLHttpRequest();
										} else {

											xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
										}
										xmlhttp.onreadystatechange = function() {
											if (this.readyState == 4 && this.status == 200) {
												document.getElementById("befornotification1").innerHTML = this.responseText;
											}
										};
										xmlhttp.open("GET", "notificationdate.php?val=" + val1, true);
										xmlhttp.send();


										document.getElementById("befornotification1").style.display = 'block';
										document.getElementById("befornotification").style.display = 'none';

									}
								</script>
								<script type="text/javascript" src="accordion.js"></script>
								<script type="text/javascript" src="jquery.min.js"></script>
								<link href="demo.css" rel="stylesheet">
								<style>
									button.accordion {
										background-color: #eee;
										color: #444;
										cursor: pointer;
										padding: 18px;
										width: 100%;
										border: none;
										text-align: left;
										outline: none;
										font-size: 15px;
										transition: 0.4s;
									}

									button.accordion.active,
									button.accordion:hover {
										background-color: #ddd;
									}

									div.panel {
										padding: 0 18px;
										display: none;
										background-color: white;
									}
								</style>
								<script>
									var acc = document.getElementsByClassName("accordion");
									var i;

									for (i = 0; i < acc.length; i++) {
										acc[i].onclick = function() {
											this.classList.toggle("active");
											var panel = this.nextElementSibling;
											if (panel.style.maxHeight) {
												panel.style.maxHeight = null;
											} else {
												panel.style.maxHeight = panel.scrollHeight + "px";
											}
										}
									}
								</script>
								<?php
								$remove_defact = sha1(uniqid('auth', true));
								$_SESSION['remove_defact'] = $remove_defact;


								?>

								<form name="form2" method="post" action="document_scrutiny_action.php">
									<input type="hidden" name="form2" value="<?php echo htmlspecialchars($form2); ?>" />
									<input type="hidden" name="filing_no_next" value="<?php echo htmlspecialchars($filing_no_next); ?>" />
									<table width="100%">

										<tr>
											<td colspan="6">
												<?php



												$tokenno = $filing_no_fou;


												?>
											</td>
										</tr>
									</table>

									<?php

									if ($tokenno != '') {
									?>

										<tr>
											<td colspan="8">
												<style>
													.tbl-accordion {
														margin: 0 auto;
														width: 900px;
														border: 1px solid #d9d9d9;
													}

													.tbl-accordion thead {
														background: #d9d9d9;
													}

													.tbl-accordion .tbl-accordion-nested {
														width: 100%;
													}

													.tbl-accordion .tbl-accordion-nested tr:nth-child(even) {
														background-color: #eeeeee;
													}

													.tbl-accordion .tbl-accordion-nested td,
													.tbl-accordion .tbl-accordion-nested th {
														padding: 10px;
														border-bottom: 1px solid #d9d9d9;
													}

													.tbl-accordion .tbl-accordion-nested .tbl-accordion-section {
														background: #333;
														color: #fff;
														cursor: pointer;
													}
												</style>

												<script>
													$('.tbl-accordion-nested').each(function() {
														var thead = $(this).find('thead');
														var tbody = $(this).find('tbody');

														tbody.hide();
														thead.click(function() {
															tbody.slideToggle();
														})
													})
												</script>

												<table cellpadding="0" cellspacing="0" class="tbl-accordion">
													<tbody>
														<tr>
															<td colspan="3">
																<table cellpadding="0" cellspacing="0" border='1' class="tbl-accordion-nested">
																	<thead>
																		<tr>
																			<td class="tbl-accordion-section">Documents </td>
																			<td><b> Case Detail </b></td>
																			<td><b> Payment Details </b></td>
																		</tr>



																		<tr>


																			<?php

																			$sthr = $db->prepare("select * from e_case_detail  where filing_no=? ");
																			$sthr->bindParam(1, $tokenno, PDO::PARAM_STR);
																			$sthr->execute();
																			while ($rowa = $sthr->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
																				$fileupload_uniqueid = $rowa['unique_id_no'];
																			}
																			$display = '1';
																			$scrutiny = '0';
																			$form_status = "C";


																			?>
																			<input type="hidden" name="form_status" value="<?php echo htmlspecialchars(htmlentities($form_status)); ?>" />
																			<?php

																			$st = $db->prepare("select *  from document_upload where filing_no=? and scrutiny=? and display=?  and miscellenous_no = ?");
																			$st->bindParam(1, $tokenno, PDO::PARAM_STR);
																			$st->bindParam(2, $scrutiny, PDO::PARAM_STR);
																			$st->bindParam(3, $display, PDO::PARAM_STR);
																			$st->bindParam(4, $miscellaneous_no, PDO::PARAM_STR);
																			$st->execute();
																			while ($rowa = $st->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
																				$miscellaneous_ref_no = $rowa['miscellaneous_ref_no'];
																				$fil_no = $rowa['filing_no'];
																				$sub_doc_type = $rowa['subdoctype'];


																				$document_filed_date = $rowa['document_filed_date'];
																				$path = $rowa['fileupload'];
																				$returnfilename = $rowa['returnfilename'];

																				list($returnfilename, $ext) = explode('.', $returnfilename);
																				$returnfilename1 = $returnfilename;



																				$stqq = $db->prepare("select e_document_name from e_document_type  where e_document_type=?");
																				$stqq->bindParam(1, $sub_doc_type, PDO::PARAM_INT);
																				$stqq->execute();

																				$e_document_name_print = $stqq->fetchColumn();
																			}


																			?>
																	</thead>
																	<tbody>

																		<!--start of code for case no. -->

																		<?php
																		//echo "select case_type, case_no, case_year, location_code from $schemas.case_detail where filing_no='$filing_no'";
																		$casenosql = $db->prepare("select case_type, case_no, case_year, location_code from $schemas.case_detail where filing_no='$filing_no_fou'");
																		$casenosql->execute();
																		$row = $casenosql->fetch();
																		$case_no = htmlspecialchars($row['case_no']);
																		//echo $case_no;
																		$case_no = ltrim($case_no, 0);
																		//echo $case_no;
																		$casetype = htmlspecialchars($row['case_type']);
																		//echo $casetype;
																		$locode = htmlspecialchars($row['location_code']);
																		//echo $locode;
																		$case_year = htmlspecialchars($row['case_year']);
																		//echo $case_year;

																		$casetypesql = $db->prepare("select short_name from case_type where id = '$casetype'");
																		$casetypesql->execute();
																		$case_type_short_name = $casetypesql->fetchColumn();
																		$case_type_short_name = strtoupper($case_type_short_name);
																		//echo $case_type_short_name;
																		if ($locode == '') {
																			$locode = 0;
																		}
																		//echo $lcodename;

																		$case_no_final = $case_type_short_name . '/' . $case_no . '/' . $case_year;
																		//echo $case_no_final;


																		//$case_no_final = 0999988888;

																		?>

																		<td>
																			<a onclick="OpenDMSForm('3','<?php echo $filing_no_fou; ?>','','<?php echo $miscellaneous_no; ?>')" style="cursor: pointer">

																				<font color="#900C3F" size="3">&nbsp;&nbsp;
																					&nbsp;&nbsp;View
																			</a>

																		</td>
																		<td>
																			<button type="button" onclick="previewCIS('<?php echo $filing_no; ?>')" style="cursor: pointer">
																				<font color="#900C3F" size="3">
																					&nbsp;&nbsp;
																					&nbsp;&nbsp;View
																			</button>
																			<!-- <a target="_blank"
																				href="https://efiling.nclat.gov.in/previewCIS.drt?filingNo=<?php  //echo $filing_no_fou 
																																			?>">
																				<font color="#900C3F" size="3">&nbsp;&nbsp;View


																			</a> -->




																		</td>
																		<td>
																			<!-- <a target="_blank"
																				href="https://efiling.nclat.gov.in/previewReceipt.drt?filingNo=<?php //echo $filing_no_fou 
																																				?>">
																				<font color="#900C3F" size="3">&nbsp;&nbsp;View
																			</a> -->
																			<a onclick="OpenPreviewReceipt('<?php echo $filing_no; ?>')" style="cursor: pointer">
																				<font color="#900C3F" size="3">
																					View
																			</a>
																		</td>



														</tr>

														<?php

														//echo "select *  from document_upload where filing_no='$filing_no_fou' and scrutiny='$scrutiny' and display='$display'  ";
														$get_mis_no_doc = $db->prepare("select *  from document_upload where filing_no=? and miscellaneous_ref_no=? and scrutiny=? and display=?  ");
														$get_mis_no_doc->bindParam(1, $filing_no_fou, PDO::PARAM_STR);
														$get_mis_no_doc->bindParam(2, $miscellaneous_ref_no_post, PDO::PARAM_STR);
														$get_mis_no_doc->bindParam(3, $scrutiny, PDO::PARAM_STR);
														$get_mis_no_doc->bindParam(4, $display, PDO::PARAM_STR);
														$get_mis_no_doc->execute();
														$doc_c = 1;
														while ($row_mnd = $get_mis_no_doc->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
															$miscellaneous_ref_no = $row_mnd['miscellaneous_ref_no'];
															$doc_id = $row_mnd['documentuploadmodelid'];
															if ($miscellaneous_ref_no) { ?>
																<tr>
																	<td>
																		<?php echo $doc_c . "). " . $miscellaneous_ref_no; ?>
																	</td>
																</tr>
															<?php
																$doc_c++;
															} else { ?>
																<tr>
																	<td>
																		<?php echo "Not Found"; ?>
																	</td>
																</tr>
														<?php }
														}

														?>

														<?php



														if ($ccase == '4') {
															if ($subdoctype == '17') {
																$subdocname = 'Report';
																$form_type = 'R';
															}
															if ($subdoctype == '33') {
																$subdocname = 'Order';
																$form_type = 'O';
															}

															//echo "select *  from document_upload where filing_no='$filing_no_fou' and scrutiny='$scrutiny' and display='$display'  ";
															$get_mis_no_doc = $db->prepare("select *  from document_upload where filing_no=? and miscellaneous_ref_no=? and scrutiny=? and display=? and subdoctype=? and party_type IN (select party_flag from e_master_govt_body) ");
															$get_mis_no_doc->bindParam(1, $filing_no_fou, PDO::PARAM_STR);
															$get_mis_no_doc->bindParam(2, $miscellaneous_ref_no_post, PDO::PARAM_STR);
															$get_mis_no_doc->bindParam(3, $scrutiny, PDO::PARAM_STR);
															$get_mis_no_doc->bindParam(4, $display, PDO::PARAM_STR);
															$get_mis_no_doc->bindParam(5, $subdoctype, PDO::PARAM_STR);
															$get_mis_no_doc->execute();
															$doc_c = 1;
															while ($row_mnd = $get_mis_no_doc->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
																$miscellaneous_ref_no = $row_mnd['miscellaneous_ref_no'];
																$report_party_type = $row_mnd['party_type'];
																$doc_id = $row_mnd['documentuploadmodelid'];
																if ($miscellaneous_ref_no) { ?>
																	<tr>
																		<td>
																			<?php echo $miscellaneous_ref_no . " (" . $subdocname . ")"; ?>
																		</td>
																	</tr>
																<?php
																	$doc_c++;
																} else { ?>
																	<tr>
																		<td>
																			<?php echo "Not Found"; ?>
																		</td>
																	</tr>
														<?php }
															}
														}
														?>



													</tbody>
												</table>
											</td>
										</tr>
										</table>

										</td>
										</tr>
							</div>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<style>
						.greenText {
							background-color: green;
						}

						.blueText {
							background-color: blue;
						}
					</style>


					<div class="main">
						<div class="accordion">

							<?php



							?>
							<div class="row">
								<div class="col-sm-12 col-md-12 col-lg-12" id="objection_tab">
									<div class="accordion-section">
										<a class="accordion-section-title" style="background-color:#00a65a !important;" href="#accordion-1">
											<?php echo htmlspecialchars("Scrutiny Check List"); ?>
										</a>
										<div id="accordion-1" class="accordion-section-content">


											<table border="1">
												<tr>
													<td colspan="12"></td>
												</tr>
												<tr style="background-color:#6D6968 ;text-align: center; color: #FFFFFF;">
													<td width="3%">Sr. No</td>
													<td width="67%">Description</td>
													<td width="6%">Defect Free</td>
													<td width="16%">Comments</td>
												</tr>
												<tr>
													<td colspan="12">

														<?php $status = $_REQUEST['status'];

														?>

														<?php


														$display = 'TRUE';

														$sth = $db->prepare("select * from check_list_local order by id ASC ");
														//$sth->bindParam(1, $location_access, PDO::PARAM_STR);
														$sth->execute();
														$i = 0;
														$j = 1;
														$check_list_count = 1;
														while ($rowa = $sth->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
															$id_check = $rowa['id'];
															$check_list = $rowa['check_list'];
														?>
												<tr>

													<td width="5%">

														<?php echo htmlspecialchars($check_list_count); ?>

													</td>


													<td width="60%">
														<font color=" #1c2833 ">
															<?php echo htmlspecialchars($check_list); ?>
														</font>



													<td width="7%">


														<select name="status" id="status" onchange="myFunction()" style="
	background-color: silver;
	color: #000000;
	padding: 7px 7px;
	margin: 2px 0;
	border: none;
	border-radius: 4px;
	cursor: pointer;">
															<?php $status = "YES"; ?>
															<option value="NO" <?php if ($status == 'NO')
																					echo "selected"; ?>>
																<font color="red">NO</font>
															</option>
															<option value="YES" <?php if ($status == 'YES')
																					echo "selected"; ?>>YES</option>
															<option value="NA" <?php if ($status == 'NA')
																					echo "selected"; ?>>
																NA</option>

														</select>

													<td width="20%">

														<textarea name="comment[]" onChange="makeUppercase(this)" cols="30" rows="1" style="background-color: silver;"><?php if ($comment[$i] != "") { echo htmlspecialchars($comment[$i]); } ?></textarea>
													</td>


												</tr>

												<input type="hidden" name="id_check[]" maxlength="3" readonly="readonly" value="<?php echo htmlspecialchars(htmlentities($id_check . ',' . 'gen')); ?>" size="2" />

												<input type="hidden" name="filing_no" value="<?php echo htmlspecialchars(htmlentities($scrutinized_filing_no)); ?>" />

											<?php
															$j++;
															$i++;
															$check_list_count++;
														}

											?>

											</td>
											</tr>

											</table>

										</div>
									</div>
								</div>
								<div class="" id="pdf_tab">
									<div class="main">
										<div class="accordion">
											<div id="view_pdf">

											</div>
										</div>
									</div>
								</div>
							</div>
							<table>
								<tr>
									<td>
										<?php


										$st2 = $db->prepare("select * from e_case_detail_fees where filing_no=? ");
										$st2->bindParam(1, $tokenno, PDO::PARAM_STR);
										$st2->execute();
										$i = 0;
										while ($row2 = $st2->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
											$E_sec_id = $row2['sec_id'];
											$E_act_id = $row2['act_id'];
											$inter_act_id = $row2['inter_act_id'];


										?>
											<input type="hidden" name='found_all[]' value='<?php echo $E_sec_id; ?>' />

									</td>
								</tr>


							</table>


							</tr>


							<input type="hidden" name="filing_no" value="<?php echo htmlspecialchars(htmlentities($scrutinized_filing_no)); ?>" />


							</td>
							</tr>
							</table>
						</div>
					</div>

				<?php
										}

										//aaa
				?>

				</div>
				<div style='padding-left:20%;'>
					<table style='padding-left:10px;'>
						<tr>
							<td>
								Notified Date: <input id="in_notification_date" type="text" name="notification_date" readonly="readonly" value="<?php echo htmlspecialchars(htmlentities($cur_date1)); ?>" size="10" maxlength="10" /></td>
							<td>
								<input type="checkbox" value="0" id="agree" name="agree" required="required">
								<b>
									<font color="red">Are You Sure</font>
								</b>
							</td>
							<td style="display: block;padding-top: 6px;" colspan="4" id="befornotification1"></td>
							<td style="display: block; " colspan="5" id="befornotification">


								<select id="in_searchby" name="searchby" style="display: block">

									<option value="2">Defects Free</option>
									<option value="1">Case Is Defective</option>


								</select>
							</td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							</td>
							<td> <input id="submit_final" type="submit" name="submit_final" class="submit" value="Proceed Further" style="
  background-color: green;
	color: #FFFFFF;
	padding: 5px 5px;
	margin: 16px 15;
	border: none;
	border-radius: 4px;
	cursor: pointer;	
	" onClick="return defect_submit();" />

							</td>
						</tr>
					</table>
				</div>

				<?php ?>


				</form>

				<!-- /.box-footer-->
		</div>
		</div>
		</div>
		<!-- /.box -->
		</section>
		<!-- /.content -->
		</div>

		<script>
			function viewpdf_new(pdfpath) {
				$("#objection_tab").removeClass("col-sm-12 col-md-12 col-lg-12").addClass("col-sm-6 col-md-6 col-lg-6");
				var loader = "<center><img src='../loader/loader.gif'></img></center>";
				var height = $("#accordion-1").height();
				height = height + 20;
				$.ajax({
					type: "POST",
					url: "../scrutiny/test_pdf.php",
					data: {
						path: pdfpath,
						type: 1
					},
					beforeSend: function() {

						$("#view_pdf").css('height', height);
						$("#view_pdf").html(loader);
					},
					success: function(data) {

						$("#view_pdf").css('height', height);
						$("#view_pdf").html(loader);
						$("#view_pdf").html(data);
						//alert("success");
					},
					error: function(textStatus, errorThrown) {
						$("#view_pdf").html('');
						alert("error");
					}

				});
			}
		</script>
		<form action="" method="POST" target="_blank" id="frm">
			<input type="hidden" id="itemno1" name="itemno" value="" />
			<input type="hidden" id="applno1" name="applno" value="" />
			<input type="hidden" id="courtno1" name="courtno" value="" />
			<input type="hidden" id="caseno1" name="caseno" value="" />
			<input type="hidden" id="casetype1" name="casetype" value="" />
			<input type="hidden" id="partyname1" name="partyname" value="">
			<input type="hidden" id="title1" name="title" value="">
			<input type="hidden" id="status1" name="status" value="">
			<input type="hidden" id="j_key1" name="j_key" value="">
			<input type="hidden" id="j_securityKey1" name="j_securityKey" value="">

		</form>
		<!-- /.content-wrapper -->
		<!-- Modal -->
		<div id="iframemodal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title" id="modal_title">PDF</h4>
					</div>
					<div class="modal-body" id="modal_body">

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>

			</div>
		</div>


		<?php
										include '../infooter.php';
		?>


		<script>
			function OpenDMSForm(step, filing_no, dms_type, misc_no) {
				document.getElementById("step").value = step;
				document.getElementById("filing_no").value = filing_no;
				document.getElementById("dms_type").value = dms_type;
				document.getElementById("misc_no").value = misc_no;
				document.getElementById("frm_dms").submit();
			}

			function previewCIS(filing_no) {
				document.getElementById("filling_no").value = filing_no;
				document.getElementById("previewCIS").submit();
			}

			function OpenPreviewReceipt(filing_no) {
				document.getElementById("fillingNu").value = filing_no;
				document.getElementById("previewReceipt").submit();
			}
		</script>

		<form action="https://efiling.nclat.gov.in/dmsnclat/dashboard" method="POST" target="_blank" id="frm_dms">
			<input type="hidden" id="step" name="step" value="" />
			<input type="hidden" id="filing_no" name="filing_no" value="" />
			<input type="hidden" id="dms_type" name="dms_type" value="" />
			<input type="hidden" id="misc_no" name="misc_no" value="" />
		</form>
		<form action="https://efiling.nclat.gov.in/previewCIS.drt" method="POST" target="_blank" id="previewCIS">
			<input type="hidden" id="filling_no" name="filingNo" value="" />
		</form>

		<form action="https://efiling.nclat.gov.in/previewReceipt.drt" method="POST" target="_blank" id="previewReceipt">
			<input type="hidden" id="fillingNu" name="filingNo" value="" />
		</form>

<?php }
								} ?>