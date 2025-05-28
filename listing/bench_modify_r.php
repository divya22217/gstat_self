<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include("../db_inc1.php");
session_start();
$judge = htmlentities($_REQUEST['judge']);

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

$_SESSION['user'];
$_SESSION['location'];
$leveladd = $_SESSION['level_level'];
$localadmin = $_SESSION['localadmin'];
$main_id = $_SESSION['main_id'];
$schemas = htmlspecialchars($_SESSION['schema_name']);
$userid = $_SESSION['id'];
$user_court = $_SESSION['user_court'];

//include '../inheader.php';
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

	//code to get requestRB
	if ($_REQUEST['hash2'] != '') {
		$hashfrom_view = base64_decode($_REQUEST['hash2']);
		list($preciding, $bench_nature, $location_code, $bench_no) = explode("/", $hashfrom_view);
		//print_r($preciding);
		list($ly, $lm, $ld) = explode("-", $list_date);
		$from_list_date1 = $ld . '/' . $lm . '/' . $ly;
		$bench_id = $preciding;
		$_SESSION["word"] = $bench_id;
	}
?>

	<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
	<link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

	<?php


	?>

	<script language="javascript">
		function MM_openBrWindow(theURL, winName, features) { //v2.0
			window.open(theURL, winName, features);
			return false;
		}

		function submitForm() {
			with(document.frm) {
				action = "bench_modify_r.php";
				submit();
			}
		}

		function submitForm1() {
			with(document.frm) {

				/*if(bench_location.value == "")
				        	{
				        	alert("Please Select Bench !!!!!");
				        	bench_location.focus();
				        	return false;
				        	}

				if(bench_code.value == "")
				        	{
				        	alert("Please Select Bench Nature!!!!!");
				        	bench_code.focus();
				        	return false;
				        	}*/
				var flds1 = document.getElementsByName('judge[]');
				for (var i = 0; i < flds1.length; i++) {
					if (flds1[i].value == '') {
						alert("Please Select Quorum");
						flds1[i].focus();
						return false;
					}

				}
				if (bench_code.value == 7 && no_of_judge1.value == "") {
					alert("Please provide number of judges.");
					no_of_judge1.focus();
					return false;
				}
				if (from_list_date.value == "") {
					alert("Please Select Listing Date!!!!!");
					from_list_date.focus();
					return false;
				}

				/* if(to_list_date.value == "")
				{
				alert("Please Select To Date!!!!!");
				to_list_date.focus();
				return false;
				} */

				if (court_no.value == "") {
					alert("Please Enter Court No!!!!!");
					court_no.focus();
					return false;
				}

				if (isNaN(court_no.value) == true) {
					alert("Please Enter Numeric Court No.");
					court_no.select();
					return false;
				}

				if(vdo_cnfr_lnk.value == "")
	        	{
		        	alert("Please Enter Video Conference Link!!!");
		        	limit_case.focus();
		        	return false;
	        	}

				if(meet_pwd.value == "")
	        	{
		        	alert("Please Enter Meeting Password!!!!");
		        	limit_case.focus();
		        	return false;
	        	}

				if (limit_case.value == "") {
					alert("Please Enter Limit of Case!!!!!");
					limit_case.focus();
					return false;
				}
				if (isNaN(limit_case.value) == true) {
					alert("Please Enter Numeric for Limit Case");
					limit_case.select();
					return false;
				}

				if (limit_case.value != "") {
					if (limit_case.value < 1) {
						alert("Please Enter Valid Limit of Case!!!!!");
						limit_case.focus();
						return false;
					}
				}
				action = "bench_modify_r_action.php";
				submit();
			}
		}

		function bench_popup() {
			var myWindow = window.open("bench_composition_delete.php", "", "width=1200,height=700");
		}

		function bench_tab() {
			window.open("bench_composition_delete.php");
		}
	</script>

	</head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="renderer" content="webkit">
	<title>Modify Bench</title>


	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<div class="row" style="padding:15px 15px 15px 15px;margin:0px 0px 0px 0px !important;">
			<div class="box box-success">
				<div class="box-body">
					<section class="content-header">
						<h1>
							<center>Bench Modification
							</center>
						</h1>

					</section>
					<p>
					<h4>
						<center>All <font color="red">*</font></span> is mandatory Field </center>
					</h4>
					</p>


					<form name="frm" method="post" action="bench_modify_r_action.php">

						<?php
						$msg = htmlentities($_REQUEST['msg']);
						if ($msg != '') {
						?>
							<div class="form-group row">
								<center>
									<font style='font-weight:bold' color='red' size='4'> <?php echo $msg . "<a href='' onclick='bench_popup()'>Click </a>to view bench report"; ?></font>
								</center>
							</div>
						<?php
						}
						?>
						<?php
						// Start of Code to ModifyRB

						$bench_id = $_SESSION["word"];

						$benchsql = $db->prepare("select * from $schemas.bench where id=? ");
						//$display='Y';
						$benchsql->bindParam(1, $bench_id, PDO::PARAM_STR);
						$benchsql->execute();
						$bench_result = $benchsql->fetch(PDO::FETCH_OBJ);

						$location_code = $bench_result->location_code;

						$bench_nature_code = $bench_result->bench_nature;

						$bench_num = $bench_result->bench_no;

						$from_time = $bench_result->from_time;

						if (!$_REQUEST['bench_location']) {
							$list_date = $bench_result->from_list_date;
							$list_date_to = $bench_result->to_list_date;

							$_SESSION["list_date_rb"] = $list_date;
							$_SESSION["list_date_rb_to"] = $list_date_to;
						}
						$court_num = $bench_result->court_no;
						$custom_text = $bench_result->custom_text;
						$vdo_cnfr_lnk = $bench_result->vdo_cnfr_lnk;
						$meet_pwd = $bench_result->meet_pwd;

						$limit_of_case = $bench_result->limit_case;

						$bench_header_rem = $bench_result->detail;

						$presiding_judge_code = $bench_result->presiding;


						//print_r($presiding_judge_code);


						//fetching location from here
						$locationsql = $db->prepare("select bench_location_name from $schemas.bench_location where bench_location_code=?");
						//print_r($locationsql);
						$locationsql->bindParam(1, $location_code, PDO::PARAM_STR);
						$locationsql->execute();
						$locationsql_result = $locationsql->fetch(PDO::FETCH_OBJ);
						$location_name = $locationsql_result->bench_location_name;


						//fetching bench nature from here
						$benchnaturesql = $db->prepare("select bench_name from $schemas.bench_nature where bench_code=?");
						//print_r($locationsql);
						$benchnaturesql->bindParam(1, $bench_nature_code, PDO::PARAM_STR);
						$benchnaturesql->execute();
						$benchnaturesql_result = $benchnaturesql->fetch(PDO::FETCH_OBJ);
						$bench_nature_name = $benchnaturesql_result->bench_name;
						//print_r($bench_nature_name);

						//fetching presiding_judge_name from here
						$presjudgenamesql = $db->prepare("select judge_name from $schemas.master_judge where judge_code=?");
						//print_r($locationsql);
						$presjudgenamesql->bindParam(1, $presiding_judge_code, PDO::PARAM_STR);
						$presjudgenamesql->execute();
						$presjudgenamesql_result = $presjudgenamesql->fetch(PDO::FETCH_OBJ);
						$pres_judgename = $presjudgenamesql_result->judge_name;
						//print_r($pres_judgename);

						?>

						<div class="form-group row">
							<div class="col-sm-6 col-md-6">
								<label for="bench" class="col-sm-4 col-form-label">
									<font color="red">*</font></span></font>Bench
								</label>
								<div class="col-sm-8">
									<select name="bench_location" id="test" class="form-control" onChange="javascript:submitForm();">
										<option value="<?php echo $location_code; ?>"><?php echo $location_name; ?></option>
									</select>
								</div>
							</div>
							<div class="col-sm-6 col-md-6">
								<label for="bench" class="col-sm-4 col-form-label">
									<font color="red">*</font></span><span id="chnage_ty">Bench Nature</span>
								</label>
								<div class="col-sm-8">
									<select name="bench_code" id="test" class="form-control">
										<?php
										$sqlm = $db->prepare("select * from $schemas.bench_nature where display=? order by bench_code ASC");
										$display = 'Y';
										$sqlm->bindParam(1, $display, PDO::PARAM_STR);
										$sqlm->execute();
										while ($row = $sqlm->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

											$bench_code = $_POST['bench_code'];
										?>
											<option value="<?php echo htmlspecialchars($row['bench_code']); ?>" <?php if ($bench_nature == $row['bench_code']) {							echo "selected";						
											} ?>>
											<?php echo htmlspecialchars($row['bench_name']); ?></option>
										<?php
										}
										?>
									</select>
									<!-- Start of code RB -->

									<?php
									if (!$_REQUEST['bench_location']) { ?>
										<?php
										if ($_REQUEST['bench_code'] == 3) {
											echo '<script>$( "#chnage_ty" ).html("Registrar");</script>';
										} else {
											echo '<script>$( "#chnage_ty" ).html("Bench Nature");</script>';
										}
										if ($benchlocation == 1) {
											$court_no = 1;
										}
										$bench_code = htmlentities($_POST['bench_code']);

										if ($bench_code > 0 and $bench_code != 7) {

											$sql = "select no_of_judges from $schemas.bench_nature where bench_code = ? ";
											$sth = $db->prepare($sql);
											$sth->bindParam(1, $bench_code, PDO::PARAM_STR);
											$sth->execute();
											$no_of_judge = $sth->fetchColumn();
											if ($bench_code != 3) {
												echo "<font color='red'><b>NUMBER OF Members:</b>   " . htmlspecialchars($no_of_judge) . "<br></font>";
											}
										}

										if ($bench_code == 7) {
											echo "<font ><b>NUMBER OF MEMBERS:</b><input type='text' required='required'onblur='javascript:submitForm();' name='no_of_judge1' value='$_POST[no_of_judge1]'></font>";
											$aaa = $db->prepare("select count(*) from $schemas.master_judge where display='TRUE' and 	judge_desg_code!=6");
											$aaa->execute();
											$dd  = $aaa->fetchColumn();
											if ($_POST['no_of_judge1'] <= $dd) {
												$no_of_judge = $_POST['no_of_judge1'];
											} else {
												$no_of_judge = $dd;
											}
										}

										?>

										<input type="hidden" maxlength="2" size="4" name="judge_count" value="<?php echo htmlspecialchars(htmlentities($no_of_judge)); ?>">

								</div>
							</div>
						</div>

						<div class="form-group row">

							<hr>
							<b><i>
									<font face="verdana" color='red'>
										<center>

											<?php
											if ($_REQUEST['bench_code'] == 3) {
											?>
												Registrar
											<?php
											} else {
											?>
												Quorum
											<?php
											}
											?>
										</center>
									</font>
								</i></b>
						</div>

						<?php

										if ($_REQUEST['bench_code'] == 3) {
											$name = 'Registrar';
										} else {
											$name = 'Select Member';
										}
										$m = 0;

										$arr = array();

										$no_of_judge = 2;



										//fetching judge_code from specific bench_no and listing_dateRB
										$judgecodesql = $db->prepare("select judge_code from $schemas.bench_judge where bench_no=? and from_list_date=? AND to_list_date = ?");
										$judgecodesql->bindParam(1, $bench_num, PDO::PARAM_STR);
										$judgecodesql->bindParam(2, $list_date, PDO::PARAM_STR);
										$judgecodesql->bindParam(3, $list_date_to, PDO::PARAM_STR);
										$judgecodesql->execute();

										//for($i=0;$i<$no_of_judge;$i++)     --code ommited
										//{
										$judge_count = 0;
										while ($judgecoderow = $judgecodesql->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
											$judge_code = $judgecoderow['judge_code'];
											$judge_count++;

											//fetching judge_name from here
											$judgenamesql = $db->prepare("select judge_name, judge_desg_code from $schemas.master_judge where judge_code=?");
											//print_r($locationsql);
											$judgenamesql->bindParam(1, $judge_code, PDO::PARAM_STR);
											$judgenamesql->execute();
											$judgenamesql_result = $judgenamesql->fetch(PDO::FETCH_OBJ);
											$judgename = $judgenamesql_result->judge_name;
											$judge_des_code = $judgenamesql_result->judge_desg_code;
											//print_r($bench_nature_name);

											//fetching designation of judges
											$judgenamesql = $db->prepare("select desg_name from $schemas.master_desg where desg_code=?");
											//print_r($locationsql);
											$judgenamesql->bindParam(1, $judge_des_code, PDO::PARAM_STR);
											$judgenamesql->execute();
											$judgenamesql_result = $judgenamesql->fetch(PDO::FETCH_OBJ);
											$judge_des_name = $judgenamesql_result->desg_name;

											$m++;
											$ii = $i + 1;
						?>

							<div class="form-group row" id="t1">
								<label for="bench" class="col-sm-2 col-form-label" id="judge_count_label<?php echo $judge_count; ?>">
									<font color="red">*</font></span></font><?php echo $name; ?>
								</label>
								<div class="col-sm-10" id="judge_count<?php echo $judge_count; ?>">
									<select name="judge[]" class="form-control" id="judge_count_select<?php echo $judge_count; ?>">
										<option value="<?php echo $judge_code; ?>"><?php echo $judgename . ' ' . $judge_des_name; ?></option>

										<?php
											$display = 'TRUE';
											$rcode = $_REQUEST['bench_code'];
											if ($rcode == 3) {

												$sqlf = $db->prepare("select * from $schemas.master_judge where judge_desg_code = 5 and display=? and judge_code=?  order by judge_desg_code desc");
												$sqlf->bindParam(1, $display, PDO::PARAM_STR);
												//$sqlf->bindParam(2, $rcode, PDO::PARAM_STR);

										?>

											<?php
											}
											if ($rcode != 3) {
												$rcode = 3;
												$sqlf = $db->prepare("select * from $schemas.master_judge where display=? and judge_code<>? order by judge_desg_code desc");
												$sqlf->bindParam(1, $display, PDO::PARAM_STR);
												$sqlf->bindParam(2, $rcode, PDO::PARAM_STR);
											}
											if ($benchlocation == 1 and $bench_code == 1) {
												$sqlf = $db->prepare("select * from $schemas.master_judge where judge_desg_code=1 and display='TRUE' order by judge_desg_code desc");
											}
											if ($bench_code == 1) {
												$sqlf = $db->prepare("select * from $schemas.master_judge where judge_desg_code!=2 and display='TRUE' order by judge_desg_code desc");
											}
											if ($bench_code == 7) {
												$sqlf = $db->prepare("select * from $schemas.master_judge where judge_desg_code!=2 and display='TRUE' order by judge_code ASC");
											}
											$sqlf->execute();
											$datata = $sqlf->fetchAll();
											//if($ii!=1){
											//shuffle($datata);
											//}
											//$move = $datata[$i];
											//unset($datata[$i]);
											//array_unshift($datata, $move);


											foreach ($datata  as $row) {

												switch ($benchlocation) {
													case 1:
														switch ($bench_code) {
															case 3:
																$showjudge = false;
																break;
															case 7:
																$showjudge = true;
																$first_rec_j = true;
																$show_bench_j = true;
																break;
															default:
																$showjudge = true;
																$first_rec_j = true;
														}
														break;
													case 2:
														switch ($bench_code) {
															case 3:
																$showjudge = false;
																break;
																break;
															case 7:
																$showjudge = true;
																$first_rec_j = true;
																$show_bench_j = true;
																break;
															default:
																$showjudge = false;
																$first_rec_j = false;
																$show_bench_j = false;
														}
														break;
													default:
												}


												$judge_code = $row['judge_code'];
												$judge_desg_code = $row['judge_desg_code'];
												$desgsthname = '';
												$des_ql = $db->prepare("select desg_name from $schemas.master_desg where desg_code=? order by desg_code desc");
												$des_ql->execute(array($judge_desg_code));
												$desgsthname = $des_ql->fetchColumn();
												//$shollcheif=false;	
												/*if($benchlocation ==1){	$shollcheif=true;}
		if($benchlocation ==2 && $bench_code==7){$shollcheif=true;}
			//$showreg =false;
			if($rcode!=3)$showreg = 'true';
			if($ii==1)$showreg = 'true';
			if($ii==1 && $_REQUEST[bench_code]==3)$showreg = 'false';
			if($benchlocation ==2 && $bench_code==7){$showreg='false';}
			//echo $showreg.$shollcheif;
			
		if($shollcheif ==true && $ii==1){
			
		if($showreg=='true'){
		 if($judge_desg_code==1){

		 ?>
		 <option value="<?php echo htmlspecialchars($row['judge_code']);?>"> 
		 <?php echo htmlspecialchars($row['judge_name']); ?></option>
		 <?php
		 
		}
		}else{?>
		<option value="<?php echo htmlspecialchars($row['judge_code']);?>"> 
		 <?php echo htmlspecialchars($row['judge_name']); ?></option>
		<?php 	
		}
		}
		else 
		{
			
			if($judge_desg_code!=1){
		?> 
		<option value="<?php echo htmlspecialchars($row['judge_code']);?>"> <?php echo htmlspecialchars($row['judge_name']); ?></option>
		<?php 
			
			 }
			
			
		}*/

												if ($showjudge == true) {
													if ($show_bench_j == true) { ?>
													<option value="<?php echo htmlspecialchars($row['judge_code']); ?>"> <?php echo htmlspecialchars($row['judge_name']) . ' ' . $desgsthname; ?></option>
													<?php
													} else {
														if ($first_rec_j == true && $ii == 1) {
															if ($judge_desg_code == 1) {
													?>
															<option value="<?php echo htmlspecialchars($row['judge_code']); ?>"> <?php echo htmlspecialchars($row['judge_name']) . ' ' . $desgsthname; ?></option>
														<?php
															}
														} else {
															if ($judge_desg_code != 1) { ?>

															<option value="<?php echo htmlspecialchars($row['judge_code']); ?>"> <?php echo htmlspecialchars($row['judge_name']) . ' ' . $desgsthname; ?></option>
													<?php
															}
														}
													}
												} else {
													if ($judge_desg_code != 1) {
													?>
													<option value="<?php echo htmlspecialchars($row['judge_code']); ?>"> <?php echo htmlspecialchars($row['judge_name']) . ' ' . $desgsthname; ?></option>
										<?php
													}
												}
											}
										?>
									</select><button type="button" name="remove_judge" id="remove_judge_btn<?php echo $judge_count; ?>" onClick="return remove_judges('<?php echo $judge_count; ?>',this);" class="btn btn-sm btn-danger" style="float:right;">Remove</button><br>
								</div>
							</div>

						<?php
										}  ?>
						<div id="add_more_judges">

						</div>
						<div class="add_more row">
							<button type="button" name="add_more_judge" onClick="return add_more_judges();" class="btn btn-sm btn-success" style="float:right;">Add More Member</button>
						</div>

						<?php
										// set 100 temp
										if ($no_of_judge > 100) {
						?>
							<div class="form-group row">
								<label for="bench" class="col-sm-2 col-form-label">
									<font color="red">*</font></span></font>Presiding Member
								</label>
								<div class="col-sm-10">
									<select name="presiding" class="form-control">


										<option value=""><?php echo $pres_judgename; ?></option>

									</select>
								</div>
							</div>
					<?php
										}
									}
					?>

					<hr />

					<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
					<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css" />
					<script src="../plugins/jQueryUI/jquery-ui.js"></script>
					<script src="../plugins/jQueryUI/date.js"></script>
					<div class="form-group row">
						<div class="col-sm-6 col-md-6 col-lg-6">
							<label for="bench" class="col-sm-4 col-form-label">
								<font color="red">*</font></span></font>Time(Display In Cause List):
							</label>
							<div class="col-sm-8">
								<div class="bootstrap-timepicker" style="width:200px;float:left; margin-top:10px;">
									<div class="form-group">


										<div class="input-group">

											<input type="text" class="form-control timepicker" name="details">

											<div class="input-group-addon">
												<i class="fa fa-clock-o"></i>
											</div>
										</div>
										<!-- /.input group -->
									</div>
									<!-- /.form group -->
								</div>

							</div>
						</div>
					</div>

						<div class="form-group row">
							<div class="col-sm-6 col-md-6">
								<label for="bench" class="col-sm-4 col-form-label"><font color="red">*</font></span></font>Video Conference Link</label>
								<div class="col-sm-8">
									<input  class="form-control" type="text" required autocomplete="off"   name="vdo_cnfr_lnk" value="<?php echo htmlspecialchars(htmlentities($vdo_cnfr_lnk)); ?>">
								</div>
							
							</div>
							<div class="col-sm-6 col-md-6">
								<label for="bench" class="col-sm-4 col-form-label"><font color="red">*</font></span></font>Meeting Password</label>
								<div class="col-sm-8">
									<input  class="form-control" type="text" required  name="meet_pwd" value="<?php echo htmlspecialchars(htmlentities($meet_pwd)); ?>">
								</div>
							
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-12 col-md-12">
								<label for="bench" class="col-sm-2 col-form-label">Custom Text</label>
								<div class="col-sm-10">
									<textarea name="custom_text" rows="3" class="form-control" cols="50"><?php echo htmlspecialchars(htmlentities($custom_text)); ?></textarea>
								</div>
							</div>
						</div>
						<?php 
							if($_SESSION['menuaccess_codeall'] == 11)
								$query = "select * from $schemas.court  order by court_no";
							else
								$query = "select * from $schemas.court where court_no = $user_court order by court_no"; 

							$qry1=$db->prepare($query);
							$qry1->execute();
							$courts = $qry1->fetchAll();


						?>
						<div class="form-group row">
							<div class="col-sm-6 col-md-6 col-lg-6">
								<label for="bench" class="col-sm-4 col-form-label">
									<font color="red">*</font></span></font>Court No.
								</label>
								<div class="col-sm-8">
									<select class="form-control" name='court_no'>
										<?php 
											foreach($courts as $k=>$court){ ?>
												<option value="<?php echo $court['court_no'] ?>" <?php echo ($court_num == $court['court_no']) ? 'selected' : ''; ?>><?php echo $court['display_court_text'] ?></option>
										<?php	}
										 ?>
									</select>

								</div>
							</div>
							<div class="col-sm-6 col-md-6 col-lg-6">
								<label for="bench" class="col-sm-4 col-form-label">
									<font color="red">*</font></span></font>Limit of Case.
								</label>
								<div class="col-sm-8">
									<input onkeypress="return isNumberKey(event)" type="text" class="form-control" autocomplete="off" maxlength="3" size="2" name="limit_case" value="<?php echo htmlspecialchars(htmlentities($limit_of_case)); ?>">

								</div>
							</div>
						</div>

					<input type="hidden" name="bench_no" value="<?php echo htmlspecialchars(htmlentities($bench_num)); ?>">
					<input type="hidden" name="bench_id" value="<?php echo htmlspecialchars(htmlentities($bench_id)); ?>">

					<div class="form-group row">
						<div class="col-sm-6 col-md-6 col-lg-6">
							<label for="bench" class="col-sm-4 col-form-label">Bench Header Remarks</label>
							<div class="col-sm-8">
								<textarea name="bench_remarks" rows="3" class="form-control" cols="50"><?php echo htmlspecialchars(htmlentities($bench_header_rem)); ?></textarea>

							</div>
						</div>
					</div>


					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hovered" cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">

							<tr>
								<td align="center" nowrap="nowrap">
									<font face="Verdana" size="2"><b><i>Purpose </b></i></font>
								</td>
								</td>
								<td align="center" nowrap="nowrap">
									<font face="Verdana" size="2"><b><i>Priority </b></i></font>
								</td>
							</tr>
							<?php

							$sqlf = $db->prepare("select bpp.purpose,bpp.court_no,bpp.bench_nature,bpp.idit,bpp.priority,mp.purpose_name from $schemas.bench_purpose_priority as bpp left join $schemas.master_purpose as mp on mp.purpose_code = bpp.purpose where bpp.from_date = ? and bpp.bench_no = ?");
							$sqlf->bindParam(1, $list_date, PDO::PARAM_STR);
							$sqlf->bindParam(2, $bench_no, PDO::PARAM_STR);
							$sqlf->execute();
							$data = $sqlf->fetchAll();
							foreach ($data as $key => $row) {
								$purpose_name = $row['purpose_name'];
								$purpose_priority = $row['priority'];
								$purpose_code = $row['purpose'];
							?>
								<tr>
									<td align="center">
										<?php
										echo $purpose_name; ?>
									<td align="center">
										<input type="text" autocomplete="off" name="purpose_priority[]
" maxlength="5" size="2" value="<?php echo htmlspecialchars(htmlentities($purpose_priority)); ?>">
									</td>
									<input type="hidden" name="purpose_code[]" value="<?php echo htmlspecialchars($purpose_code); ?>" />
								<?php
							}


								?>

								</td>


								</tr>

						</table>
					</div>

					<input type="hidden" name="frm" value="<?php //echo htmlspecialchars($frm); 
															?>" />
					<input type="hidden" name="bench_no" value="<?php echo htmlspecialchars($bench_num); ?>" />
					<input type="hidden" name="from_list_date" value="<?php echo htmlspecialchars($list_date); ?>" />
					<div class="form-group row">
						<center><input type="submit" name="submit1" value="Submit" class="btn btn-sm btn-success" onClick="return submitForm1();"></center>
					</div>



					</form>

					<?php

					//include '../bfooter.php';
					?>
					<script src="../bower_components/jquery/dist/jquery.min.js"></script>
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
					<!-- ChartJS -->
					<!-- <script src="../bower_components/Chart.js/Chart.js"></script> -->
					<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
					<script src="../dist/js/pages/dashboard2.js"></script>
					<!-- AdminLTE for demo purposes -->
					<script src="../dist/js/demo.js"></script>


					<!-- Select2 -->
					<script src="../bower_components/select2/dist/js/select2.full.min.js"></script>
					<!-- InputMask -->
					<script src="../plugins/input-mask/jquery.inputmask.js"></script>
					<script src="../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
					<script src="../plugins/input-mask/jquery.inputmask.extensions.js"></script>
					<!-- date-range-picker -->
					<script src="../bower_components/moment/min/moment.min.js"></script>
					<script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
					<!-- bootstrap datepicker -->
					<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
					<!-- bootstrap color picker -->
					<script src="../bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
					<!-- bootstrap time picker -->
					<script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
					<!-- SlimScroll -->
					<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
					<!-- iCheck 1.0.1 -->
					<script src="../plugins/iCheck/icheck.min.js"></script>
					<!-- FastClick -->
					<script>
						$('#t1 select').on('change', function() {
							$('option').prop('disabled', false);
							$('#t1 select').each(function() {
								var val = this.value;
								$('#t1 select').not(this).find('option').filter(function() {
									return this.value === val;
								}).prop('disabled', true);
							});
						}).change();
						//$('.timepicker').timepicker({      showInputs: false    })
						var from_time = '<?php echo $from_time; ?>';
						var list_date = '<?php echo $list_date; ?>';
						$('.timepicker').timepicker({
							showInputs: false
						}).val(from_time);
						$('.datepicker').datepicker("setDate", new Date(list_date));

						function remove_judges(judge_count) {
							$("#judge_count" + judge_count).hide();
							$("#judge_count_label" + judge_count).hide();
							$("#remove_judge_btn" + judge_count).hide();
							$("#judge_count_select" + judge_count).attr('disabled', 'disabled');
						}

						function add_more_judges() {
							$.ajax({
								type: "GET",
								url: "add_more_judges.php",
								beforeSend: function() {},
								success: function(data) {
									$("#add_more_judges").append(data);
									// location.reload(true);
								},
								error: function(textStatus, errorThrown) {
									/* console.log(errorThrown);
			console.log(textStatus); */
									alert("error");
									//location.reload(true);
								}

							});
						}
					</script>
				<?php } ?>