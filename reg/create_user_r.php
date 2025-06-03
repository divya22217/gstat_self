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
$location_code = $_SESSION['location'];
if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
	echo "Access Problem.....";
	header("Location: ../login.php");
	die();
}
setcookie("PHPSESSID", "", time() - 3600, "", "", TRUE, TRUE);
$benchlocation = htmlentities($_REQUEST['bench_location']);

//*****************start of code so that admin of other bench could not login


if ($_SESSION['user'] != '' and $_SESSION['location'] != '' and ($_SESSION['localadmin'] == '2' || $_SESSION['localadmin'] == '1')) {
	$sessionUserType = htmlspecialchars($_SESSION['id']);
	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 = "$curDay/$curMonth/$curYear";
	$link_scrutiny_idaccess = '1';

	//my code
	$sessionUserType = htmlspecialchars($_SESSION['id']);
	$stlu = $db->prepare("select schema_id from public.users_cis where id=? ");
	//print_r($stlu);
	$stlu->bindParam(1, $sessionUserType, PDO::PARAM_STR);
	$stlu->execute();
	while ($row = $stlu->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
		$schema_idrun = $row['schema_id'];
		//print_r($schema_idrun);
	}

?>
	<?php

	include '../inheader.php';
	//include '../insidebar.php';

	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<script language="javascript">
			//my code
			function check_parent() {
				with(document.frm) {
					action = "create_menu_r.php";
					submit();
				}
			}

			function submitForm1() {
				with(document.frm) {

					if (location.value == "") {
						alert("Please Select Bench Location !!!!!");
						location.focus();
						return false;
					}

					if (first_name.value == "") {
						alert("Please Enter Your First name !!!!!");
						first_name.focus();
						return false;
					}

					if (email_id.value == "") {
						alert("Please enter the Valid Email");
						email_id.focus();
						return false;
					}
					var regemail = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
					//var address = document.getElementById[email].value;
					if (regemail.test(email_id.value) == false)

					{
						alert('Invalid Email Address');
						return false;
					}

					// if (designation.value == "") {
					// 	alert("Please Select Designation!!!!!");
					// 	designation.focus();
					// 	return false;
					// }
					// if (bench_code.value == 7 && no_of_judge1.value == "") {
					// 	alert("Please provide number of no of judge !!!!");
					// 	no_of_judge1.focus();
					// 	return false;
					// }
					if (password.value == "") {
						alert("Please Enter Password!!!!!");
						password.focus();
						return false;
					}
					if (conf_password.value == "") {
						alert("Please Enter Password!!!!!");
						conf_password.focus();
						return false;
					}
					if (password.value != "") {
						if (password.value.length < 8) {
							alert("Error: Password must contain at least Eight characters!");
							frm.password.focus();
							return false;
						}
					}
					re = /[0-9]/;
					if (!re.test(document.frm.password.value)) {
						alert("Error: password must contain at least one number (0-9)!");
						document.form.userpassword.focus();
						return false;
					}
					re = /[a-z]/;
					if (!re.test(document.frm.password.value)) {
						alert("Error: password must contain at least one lowercase letter (a-z)!");
						document.form.userpassword.focus();
						return false;
					}
					re = /[A-Z]/;
					if (!re.test(document.frm.password.value)) {
						alert("Error: password must contain at least one uppercase letter (A-Z)!");
						document.form.userpassword.focus();
						return false;
					}

					iChars = /[!@#$%^&*]/;
					if (!iChars.test(document.frm.password.value)) {
						alert("Error: password must contain at least one Special Characters FOR [ ! @ # $ % ^ & * ]...");
						document.frm.password.focus();
						return false;
					}
					if (!iChars.test(document.frm.conf_password.value)) {
						alert("Error: password must contain at least one Special Characters FOR [ ! @ # $ % ^ & * ]...");
						document.frm.conf_password.focus();
						return false;
					}

					if (document.frm.password.value != document.frm.conf_password.value) {
						alert("Password Not Matched...");
						return false;
					}
					var saltk = "saltzz";
					// var md5password =  sha256_digest(sha256_digest(document.frm.password.value)+ document.frm.salt.value+ saltk )
					// var md5password1 =  sha256_digest(sha256_digest(document.frm.conf_password.value)+ document.frm.salt.value+ saltk );
					var md5password = sha256_digest(document.frm.password.value);
					var md5password1 = sha256_digest(document.frm.conf_password.value);
					// var md5password1 = sha256_digest(document.frm.conf_password.value);
					document.frm.password.value = md5password;
					document.frm.conf_password.value = md5password1;
					document.frm.salt.value = "";
					action = "create_user_r.php";
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

	<body>
		<title>Create User</title>


		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					<center>User Registration
					</center>
				</h1>

			</section>
			<p>
			<h4>
				<center>All <font color="red">*</font></span> is mandatory Field </center>
			</h4>
			</p>
			<table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">
			<form name="frm" method="post" action="">
					<?php
					$msg = htmlentities($_REQUEST['msg']);
					if ($msg != '') {
					?>
						<tr>
							<td colspan="6">
								<center>
									<font style='font-weight:bold' color='green' size='4'> <?php echo $msg; ?></font>
							</td>
							</center>
						</tr>
					<?php
					}
					$msgerror = htmlentities($_REQUEST['msgerror']);
					if ($msgerror != '') {
					?>
						<tr>
							<td colspan="6">
								<center>
									<font style='font-weight:bold' color='red' size='4'> <?php echo $msgerror; ?></font>
							</td>
							</center>
						</tr>
					<?php
					}
					if ($localadmin == '2') {
					?>
						<tr>
							<td align="right" colspan="4" style="padding-bottom:1em;"><span>
									<font color="red">*</font>
								</span><b>LOCATION: </b></td>
							<td align="left" colspan="6" style="padding-bottom:1em;">
								<select name="location" onchange="javascript:select_menu();">
									<option value="">--SELECT BENCH LOCATION--</option>
									<?php
									//code for regular drop-down
									$select_menu_sql = $db->prepare("select city_name, city_id from public.mater_location_city order by city_id asc");
									$select_menu_sql->execute();
									while ($select_menu_sql_result = $select_menu_sql->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
										print "<option value=" . htmlspecialchars($select_menu_sql_result['city_id']) . ">" . htmlspecialchars(ucwords(strtoupper($select_menu_sql_result['city_name']))) . "</option>";
									}

									?>

								</select>

							</td>
						</tr>
					<?php } ?>


					<?php if ($localadmin == '1') { ?>

						<tr>
							<td align="right" colspan="4" style="padding-bottom:1em;"><span>
									<font color="red">*</font>
								</span><b>LOCATION: </b></td>
							<td align="left" colspan="6" style="padding-bottom:1em;">
								<select name="location" onchange="javascript:select_menu();">
									<?php
									$selected_loc_sql = $db->prepare("select city_name, city_id from public.mater_location_city where city_id=?");
									$selected_loc_sql->bindParam(1, $location_code, PDO::PARAM_STR);
									$selected_loc_sql->execute();

									while ($selected_loc_sql_result = $selected_loc_sql->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
										print "<option value=" . htmlspecialchars($selected_loc_sql_result['city_id']) . " selected>" . htmlspecialchars(ucwords(strtoupper($selected_loc_sql_result['city_name']))) . "</option>";
									}
									?>
								</select>

							</td>
						</tr>

						<tr>
							<td align="right" colspan="4" style="padding-bottom:1em;"><span>
									<font color="red">*</font>
								</span><b>DESIGNATION:</b>
							</td>
							<td align="left" colspan="6" style="padding-bottom:1em;">

								<select name="designation">
									<option value="">--SELECT--</option>
									<?php $user_roles_sql = $db->prepare("select * from public.master_user_roles_r where display = true");
									$user_roles_sql->execute();
									while ($user_roles_sql_result = $user_roles_sql->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
										print "<option value=" . htmlspecialchars($user_roles_sql_result['menuaccess_code_all_id']) . ">" . htmlspecialchars(ucwords(strtoupper($user_roles_sql_result['user_type']))) . "</option>";
									}
									?>
								</select>

							</td>
						</tr><?php } ?>
					<?php $_SESSION['salt'] = sha1(microtime());
					$saltbb = $_SESSION['salt']; ?>
					<input name="salt" type="hidden" value="<?php echo htmlspecialchars(htmlentities($saltbb)); ?>" />
					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;"><span>
								<font color="red">*</font>
							</span><b>FIRST NAME:</b>
						</td>
						<td align="left" colspan="6" style="padding-bottom:1em;">

							<input type="text" name="first_name"><br>
						</td>

					</tr>
					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;"><b>LAST NAME:</b>
						</td>
						<td align="left" colspan="6" style="padding-bottom:1em;">

							<input type="text" name="last_name"><br>
						</td>
					</tr>

					</tr>
					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;"><span>
								<font color="red">*</font>
							</span><b>USERNAME:</b>
						</td>
						<td align="left" colspan="6" style="padding-bottom:1em;">

							<input type="text" name="username"><br>
						</td>
					</tr>

					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;"><span>
								<font color="red">*</font>
							</span><b>E-MAIL ID:</b>
						</td>
						<td align="left" colspan="6" style="padding-bottom:1em;">

							<input type="text" name="email_id"><br>
						</td>

					</tr>
					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;"><b>GENDER:</b>
						</td>
						<td align="left" colspan="6" style="padding-bottom:1em;">

							<select name="gender">
								<option value="">--SELECT--</option>
								<option value="male">MALE</option>
								<option value="female">FEMALE</option>
							</select>

						</td>
					</tr>
					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;"><span>
								<font color="red">*</font>
							</span><b>Court: </b></td>
						<td align="left" colspan="6" style="padding-bottom:1em;">
							<select name="court">
								<?php
								$selected_court_sql = $db->prepare("select * from $schemas.court order by court asc");
								
								$selected_court_sql->execute();

								while ($result = $selected_court_sql->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
									print "<option value=" . htmlspecialchars($result['court_no']) . " selected>" . htmlspecialchars(ucwords(strtoupper($result['display_court_text']))) . "</option>";
								}
								?>
							</select>

						</td>
					</tr>
					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;"><b>MOBILE No.:</b>
						</td>
						<td align="left" colspan="6" style="padding-bottom:1em;">
							<input type="text" name="mob_no" minlength="10" maxlength="10"><br>
						</td>

					</tr>
					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;"><span>
								<font color="red">*</font>
							</span><b>PASSWORD:</b>
						</td>
						<td align="left" colspan="6" style="padding-bottom:1em;">
							<input type="password" name="password"><br>
						</td>

					</tr>
					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;"><span>
								<font color="red">*</font>
							</span><b>CONFIRM PASSWORD:</b>
						</td>
						<td align="left" colspan="6" style="padding-bottom:1em;">
							<input type="password" name="conf_password"><br>
						</td>

					</tr>

					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;">
						</td>
						<td>
							<input id="registeruser" type="submit" name="registeruser" value="REGISTER ME" onClick="return submitForm1();" />
						</td>
					</tr>

				</form>
				<!-- <form name="frm" method="post" action="">
					<?php
				//	$msg = htmlentities($_REQUEST['msg']);
					if ($msg != '') {
					?>
						<tr>
							<td colspan="6">
								<center>
									<font style='font-weight:bold' color='green' size='4'> <?php // echo $msg; ?></font>
							</td>
							</center>
						</tr>
					<?php
					}
					$msgerror = htmlentities($_REQUEST['msgerror']);
					if ($msgerror != '') {
					?>
						<tr>
							<td colspan="6">
								<center>
									<font style='font-weight:bold' color='red' size='4'> <?php // echo $msgerror; ?></font>
							</td>
							</center>
						</tr>
					<?php
					}
					if ($localadmin == '2') {
					?>
						<tr>
							<td align="right" colspan="4" style="padding-bottom:1em;"><span>
									<font color="red">*</font>
								</span><b>LOCATION: </b></td>
							<td align="left" colspan="6" style="padding-bottom:1em;">
								<select name="location" onchange="javascript:select_menu();">
									<option value="">--SELECT BENCH LOCATION--</option>
									<?php
									//code for regular drop-down
									$select_menu_sql = $db->prepare("select city_name, city_id from public.mater_location_city order by city_id asc");
									$select_menu_sql->execute();
									while ($select_menu_sql_result = $select_menu_sql->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
										// print "<option value=" . htmlspecialchars($select_menu_sql_result['city_id']) . ">" . htmlspecialchars(ucwords(strtoupper($select_menu_sql_result['city_name']))) . "</option>";
									}

									?>

								</select>

							</td>
						</tr>
					<?php } ?>


					<?php if ($localadmin == '1') { ?>

						<tr>
							<td align="right" colspan="4" style="padding-bottom:1em;"><span>
									<font color="red">*</font>
								</span><b>LOCATION: </b></td>
							<td align="left" colspan="6" style="padding-bottom:1em;">
								<select name="location" onchange="javascript:select_menu();">
									<?php
									$selected_loc_sql = $db->prepare("select city_name, city_id from public.mater_location_city where city_id=?");
									$selected_loc_sql->bindParam(1, $location_code, PDO::PARAM_STR);
									$selected_loc_sql->execute();

									while ($selected_loc_sql_result = $selected_loc_sql->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
										//print "<option value=" . htmlspecialchars($selected_loc_sql_result['city_id']) . " selected>" . htmlspecialchars(ucwords(strtoupper($selected_loc_sql_result['city_name']))) . "</option>";
									}
									?>
								</select>

							</td>
						</tr>

						<tr>
							<td align="right" colspan="4" style="padding-bottom:1em;"><span>
									<font color="red">*</font>
								</span><b>DESIGNATION:</b>
							</td>
							<td align="left" colspan="6" style="padding-bottom:1em;">

								<select name="designation">
									<option value="">--SELECT--</option>
									<?php $user_roles_sql = $db->prepare("select * from public.master_user_roles_r");
									$user_roles_sql->execute();
									while ($user_roles_sql_result = $user_roles_sql->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
										//print "<option value=" . htmlspecialchars($user_roles_sql_result['menuaccess_code_all_id']) . ">" . htmlspecialchars(ucwords(strtoupper($user_roles_sql_result['user_type']))) . "</option>";
									}
									?>
								</select>

							</td>
						</tr><?php } ?>

					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;"><span>
								<font color="red">*</font>
							</span><b>FIRST NAME:</b>
						</td>
						<td align="left" colspan="6" style="padding-bottom:1em;">

							<input type="text" name="first_name"><br>
						</td>

					</tr>
					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;"><b>LAST NAME:</b>
						</td>
						<td align="left" colspan="6" style="padding-bottom:1em;">

							<input type="text" name="last_name"><br>
						</td>
					</tr>

					</tr>
					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;"><span>
								<font color="red">*</font>
							</span><b>USERNAME:</b>
						</td>
						<td align="left" colspan="6" style="padding-bottom:1em;">

							<input type="text" name="username"><br>
						</td>
					</tr>

					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;"><span>
								<font color="red">*</font>
							</span><b>E-MAIL ID:</b>
						</td>
						<td align="left" colspan="6" style="padding-bottom:1em;">

							<input type="text" name="email_id"><br>
						</td>

					</tr>
					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;"><b>GENDER:</b>
						</td>
						<td align="left" colspan="6" style="padding-bottom:1em;">

							<select name="gender">
								<option value="">--SELECT--</option>
								<option value="male">MALE</option>
								<option value="female">FEMALE</option>
							</select>

						</td>
					</tr>
					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;"><b>MOBILE No.:</b>
						</td>
						<td align="left" colspan="6" style="padding-bottom:1em;">
							<input type="text" name="mob_no"><br>
						</td>

					</tr>
					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;"><span>
								<font color="red">*</font>
							</span><b>PASSWORD:</b>
						</td>
						<td align="left" colspan="6" style="padding-bottom:1em;">
							<input type="password" name="password"><br>
						</td>

					</tr>
					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;"><span>
								<font color="red">*</font>
							</span><b>CONFIRM PASSWORD:</b>
						</td>
						<td align="left" colspan="6" style="padding-bottom:1em;">
							<input type="password" name="conf_password"><br>
						</td>

					</tr>

					<tr>
						<td align="right" colspan="4" style="padding-bottom:1em;">
						</td>
						<td>
							<input id="registeruser" type="submit" name="registeruser" value="REGISTER ME" onClick="return submitForm1();" />
						</td>
					</tr>

					my code end here




				</form> -->
			</table>

			<?php
			if (isset($_REQUEST['registeruser'])) {
				//print_r($_REQUEST);die('check');
				$location = $_REQUEST['location'];
				$first_name = $_REQUEST['first_name'];
				$last_name = $_REQUEST['last_name'];
				$username = $_REQUEST['username'];
				$email_id = $_REQUEST['email_id'];
				$gender = $_REQUEST['gender'];
				$mob_no = $_REQUEST['mob_no'];
				$password = $_REQUEST['password'];
				$conf_password = $_REQUEST['conf_password'];
				$court = $_REQUEST['court'];
				if ($localadmin == '2') {
					$designation = '1';     //sets menuaccess_codeaall(designation) to 1 for everyevery admin created by superadmin.
				} else {
					$designation = $_REQUEST['designation'];
				}


				if ($first_name != '' || $username != '' || $email_id != '' || $designation != '' || $password != '' || $conf_password != '') {
					//validation code
					$check_emailid_sql = $db->prepare("select id from public.users_cis where email=?");
					$check_emailid_sql->bindParam(1, $email_id, PDO::PARAM_STR);
					$check_emailid_sql->execute();
					$check_emailid_sql_results = $check_emailid_sql->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
					if ($check_emailid_sql_results) {
						echo '<h2><center>E-MAIL ID ALREADY EXIST ... PLEASE RE-ENTER OTHER E_MAIL ID !!!!! </center></h2>';
						die();
					}

					if (strlen($first_name) < '3') {
						echo '<h2><center>MINIMUM 4 CHARACTERS OF FIRST NAME</center></h2>';
						die();
					}

					if (strlen($username) < '3') {

						die("Minimum 3 characters OF USER NAME!");
					}

					if (strlen($password) < '7') {
						echo '<h2><center>MINIMUM 8 CHARACTERS OF PASSWORD</center></h2>';
						die();
					}

					if (empty($first_name)) {
						echo '<h2><center>PLEASE ENTER FIRST NAME</center></h2>';
						die();
					}

					if (!filter_var($email_id, FILTER_VALIDATE_EMAIL)) {
						echo '<h2><center>INVALID E-MAIL</center></h2>';
						die();
					}

					if ($password != $conf_password) {
						echo '<h2><center>PASSWORD NOT MATCH</center></h2>';
						die();
					}

					$salt_sat = '@2016@';
					//$password = hash('sha256', (get_magic_quotes_gpc() ? stripslashes($password) : $password));
					$pwd_hash = '@2016@';
					$main_id = '9999';
					$login_status = 'Approved';
					$schema_id = $location;
					$user_creation_date = $cur_date;

					if ($localadmin == '1') {
						$localadmin = '0';
						$user_type = $localadmin;
					} else if ($localadmin == '2') {
						$localadmin = '1';
						$user_type = $localadmin;
					} else {
						echo '<h2><center>SORRY! YOU ARE NOT AUTHORIZED TO PERFORM THIS ACTION</center></h2>';
						die();
					}


					//$create_user_sql =$db->prepare("insert into public.users_cis (username, password, salt, email, gender, location, fname, lname) values (?,?,?,?,?,?,?,?)");

					$create_user_sql = $db->prepare("insert into public.users_cis (username, password, salt, email, gender, location, fname, lname, pwd_hash, main_id, login_status, schema_id, user_creation_date, localadmin, menuaccess_codeall, user_type, mobile_no, court) values ('$username','$password','$salt_sat','$email_id','$gender','$location','$first_name','$last_name','$pwd_hash','$main_id','$login_status','$schema_id','$user_creation_date','$localadmin','$designation','$user_type', '$mob_no','$court')");
					//print_r($create_user_sql);die();

					/*$create_menu_sql->bindParam(1, $username, PDO::PARAM_STR);
$create_menu_sql->bindParam(2, $password, PDO::PARAM_STR);
$create_menu_sql->bindParam(3, $salt_sat, PDO::PARAM_STR);
$create_menu_sql->bindParam(4, $email_id, PDO::PARAM_STR);
$create_menu_sql->bindParam(5, $gender, PDO::PARAM_STR);
$create_menu_sql->bindParam(6, $location, PDO::PARAM_STR);
$create_menu_sql->bindParam(7, $first_name, PDO::PARAM_STR);
$create_menu_sql->bindParam(8, $last_name, PDO::PARAM_STR);*/

					$create_user_sql->execute();
					$message = 'USER SUCCESSFULLY REGISTERED.';
					header("Location:./create_user_r.php?msg=$message");
				} else {
					$message = 'ERROR! PLEASE FILL ALL MANDATORY FIELDS BEFORE SUBMITTING THE FORM !';
					header("Location:./create_user_r.php?msgerror=$message");
				}
			}
			include '../bfooter.php';
			?>

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
				$('.timepicker').timepicker({
					showInputs: false
				})
			</script>
	</body>
<?php } ?>