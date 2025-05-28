<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include("db_inc1.php");
session_start();
$judge = htmlentities($_REQUEST['judge']);

$user = $_SESSION['user'];

$location = $_SESSION['location'];
$leveladd = $_SESSION['level_level'];
$localadmin = $_SESSION['localadmin'];
$main_id = $_SESSION['main_id'];
$schemas = htmlspecialchars($_SESSION['schema_name']);


$sessionUserType = $_SESSION['id'];

if ($sessionUserType > 0) {
  $atrrs = $db->prepare(" select * from users_cis where id=? ");
  $atrrs->bindParam(1, $sessionUserType, PDO::PARAM_STR);
  $atrrs->execute();
  while ($row = $atrrs->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
    //$accesspoint1=$row['accesspoint1'];
    $localadmincontrol = $row['localadmin'];
  }
}

if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
  echo "Access Problem.....";
  header("Location: ../login.php");
  die();
}
if (empty($_SESSION['user']) and $_SESSION['location'] == '') {

  die("Redirecting to login.php");
}
if (($_SESSION['user']) == '') {
  echo "you Can't access this page";
}
setcookie("PHPSESSID", "", time() - 3600, "", "", TRUE, TRUE);

if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {
  $sessionUserType = htmlspecialchars($_SESSION['id']);
  $curYear = htmlspecialchars(date("Y"));
  $curMonth = htmlspecialchars(date("m"));
  $curDay = htmlspecialchars(date("d"));
  $cur_date = "$curYear-$curMonth-$curDay";
  $cur_date1 = "$curDay/$curMonth/$curYear";
  $link_scrutiny_idaccess = '1';
?>
  <?php


  ?>
  <html>

  <head>
    <title>
      Change Password
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<script src="sha256.js"></script>
    <style>
      input[type=text],
      select {

        padding: 5px 40px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #000000;
        border-radius: 4px;
        box-sizing: border-box;
      }

      input[type=password],
      select {
        padding: 5px 40px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #000000;
        border-radius: 4px;
        box-sizing: border-box;
      }

      input[type=submit] {

        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
      }

      input[type=submit]:hover {
        background-color: #45a049;
      }
    </style>
    <script>
      function myFunction() {
        var x = document.getElementById("myInput");
        if (x.type === "password") {
          x.type = "text";
        } else {
          x.type = "password";
        }
      }



      function check_validation() {
        with(document.frm) {
          // var iChars = "!@#$%^&*()+=[]\\\';,/{}|\":<>?";
          var new_password1 = document.frm.new_password.value;
          var confirm_new_password1 = document.frm.confirm_new_password.value;
		  var old_password1 = document.frm.old_password.value;
     
		  if (old_password.value == "") {
            alert("Please Enter  Old Password");
            old_password.focus();
            return false;
          }
          if (new_password.value == "") {
            alert("Please Enter  New Password");
            new_password.focus();
            return false;
          }
          if (new_password.value != "") {
            if (document.frm.new_password.value.length < 8) {
              alert("Error: Password must contain at least Eight characters!");
              return false;
            }
          }

          re = /[0-9]/;
          if (!re.test(new_password.value)) {
            alert("Error: password must contain at least one number (0-9)!");
            document.frm.new_password.focus();
            return false;
          }
          re = /[a-z]/;
          if (!re.test(new_password.value)) {
            alert("Error: password must contain at least one lowercase letter (a-z)!");
            document.frm.new_password.focus();
            return false;
          }
          re = /[A-Z]/;
          if (!re.test(new_password.value)) {
            alert("Error: password must contain at least one uppercase letter (A-Z)!");
            document.frm.new_password.focus();
            return false;
          }

          iCharsx = /[!@#$%^&*]/;
          if (!iCharsx.test(new_password.value)) {
            alert("Error: password must contain at least one Special Characters FOR [ !@#$%^&* ]...");
            document.frm.new_password.focus();
            return false;
          }

          if (confirm_new_password1 == "") {
            alert("Please Enter Confirm New Password");
           confirm_new_password.focus();
            return false;
          }
          if (new_password1 != confirm_new_password1) {
            alert("Password not matched ");
           confirm_new_password.focus();
            return false;
          }

          var saltk = "saltzz";
          var md5password1 = sha256_digest(new_password1);
          document.frm.new_password.value = md5password1;
          var md5password2 = sha256_digest(confirm_new_password1);
          document.frm.confirm_new_password.value = md5password2;
		  var md5passwordold = sha256_digest(old_password1);
          document.frm.old_password.value = md5passwordold;
        }

      }
    </script>
  </head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="renderer" content="webkit">
  <title>Change Password</title>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <center>Change Password
        </center>
      </h1>

    </section>
    <p>
    <h4>
      <center>All <font color="red">*</font></span> is mandatory Field </center>
    </h4>
    </p>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">

      <tr>
        <td colspan="10">
          <font color="blue" size="2">
            1. The password should be at least “eight” characters in length. (Longer is generally better.)
        </td>
        </font>
      </tr>
      <tr>
        <td>
          <font color="blue" size="2">
            2. The password should have at least one lower case alphabet
        </td>
        </font>
      </tr>
      <tr>
        <td>
          <font color="blue" size="2">
            3. The password should have at least one upper case alphabets
        </td>
        </font>
      </tr>
      <tr>
        <td>
          <font color="blue" size="2">
            4.The password should have at least one digit and one special character (a-z, A-Z, 0-9, Special characters Like [ !@#$%^&* ]
          </font>
        </td>
        </font>
      </tr>
    </table>
    <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">


      <?php
      $uname = $_SESSION['user'];
      $ipaddress = htmlspecialchars($_SERVER["REMOTE_ADDR"]);
      $yy = date("Y/m/d h:i:s");
      $at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status)
		values (?,?,?,?,?)");
      $xx = 'Change Password';
      $x = 'security True Access';
      $at->bindParam(1, $uname, PDO::PARAM_STR);
      $at->bindParam(2, $ipaddress, PDO::PARAM_STR);
      $at->bindParam(3, $yy, PDO::PARAM_STR);
      $at->bindParam(4, $xx, PDO::PARAM_STR);
      $at->bindParam(5, $x, PDO::PARAM_STR);
      $at->execute();


      ?>

      <tr>
        <td align="center"><b>
            <font face="Verdana" size="2" color="red"><span class="error">
                <?php
                $msg = $_REQUEST['msg'];
				
				if ($_REQUEST['msg'] == 4) {
                  echo htmlspecialchars("old password does not matched ");
                }

                if ($_REQUEST['msg'] == 2) {
                  echo htmlspecialchars("New password and confirm new password are not same ");
                }
                ?>
        </td>
      </tr>
      <form name="frm" method="post" action="update_pass_action.php">
        <tr>
          <td align="center"><b>
              <font face="Verdana" size="2" color="red"><span class="error">
                  <?php


                  if ($_REQUEST['msg'] == 3) {
                    echo '<script>alert("Password changed successfully")</script>';
                    echo "<script>window.location.href = 'logout1.php';</script>";
                    exit();
                  }
                  ?>
            </b>
          </td>
          </font>
        </tr>
    </table>

    <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">
      <tr>
        <td width="104" align="left valign=" top" nowrap="nowrap">
          <font color="red">*</font>
          <font face="Verdana" size="2">Old Password</font>
        </td>
        <td width="110" align="left" valign="top">
			<input id="old_password" type="password" name="old_password" maxlength="15" size="15" required>
        </td>
      </tr>
      <tr>
        <td width="104" align="left valign=" top" nowrap="nowrap">
          <font color="red">*</font>
          <font face="Verdana" size="2">Enter New Password</font>
        </td>
        <td width="110" align="left" valign="top"><input id="myInput" type="password" name="new_password" maxlength="15" size="15" required>
          <input type="checkbox" onclick="myFunction()">Show Password
        </td>
      </tr>
      <tr>
        <td width="104" align="left valign=" top" nowrap="nowrap">
          <font color="red">*</font>
          <font face="Verdana" size="2">Confirm New Password</font>
        </td>
        <td width="110" align="left" valign="top"><input type="password" id="confirm_new_password" name="confirm_new_password" maxlength="15" size="15" required>
        </td>
      </tr>



      <input name="localadmincont" type="hidden" value="<?php echo htmlspecialchars(htmlentities($localadmincontrol)); ?>" />



      <input type="hidden" name="form3" value="<?php echo htmlspecialchars($form3); ?>" />

      <tr>
        <td>&nbsp;</td>
        <td colspan="8" align="left">
          <input type="submit" name="submit1" value="Change password" onclick="return check_validation();">
        </td>
      </tr>

      </form>
    </table>

    <?php

    include '../infooter.php';
    ?>


  <?php } ?>