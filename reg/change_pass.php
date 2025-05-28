<script type="text/javascript" language="javascript">
function DisableBackButton() {
window.history.forward()
}
DisableBackButton();
window.onload = DisableBackButton;
window.onpageshow = function(evt) { if (evt.persisted) DisableBackButton() }
window.onunload = function() { void (0) }
</script>
<?php 


include("../includes/db_inc.php");
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

date_default_timezone_set("Asia/Kolkata");

$sessionUserType=$_SESSION['id'];
if($sessionUserType > 0)
{
	$atrrs = $db->prepare(" select * from users where id=? ");
	$atrrs->bindParam(1, $sessionUserType, PDO::PARAM_STR);
	$atrrs->execute();
	while ($row = $atrrs->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		$accesspoint1=$row['accesspoint1'];
	}
}
if($accesspoint1 =='0')
{
echo "you do not have permission to access Page.....";

die();
}


// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{

	die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
	echo "you Can't access this page";
}
else {

	$form3 = md5( uniqid('auth', true) );
	
	/*** set the session form token ***/
	$_SESSION['form_token'] = $form3;//csrf
	
?>


<html>
<head>
<title>
Case Categorization
</title>

    <link rel="icon" href="../images/epfo.ico" type="image/x-icon"/>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <style>
        input[type=text], select {

            padding: 5px 40px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #000000;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type=password], select {
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
    <script type="text/javascript" src="../includes/js/utf8_encode.js"></script>
    <script src="../includes/js/sha256.js" language="javascript" ></script>


<script type="text/javascript">
function check_validation()
{
var iChars = "!@#$%^&*()+=[]\\\';,/{}|\":<>?";
if (document.form3.old_password.value=="")
{

alert("Please enter old password");
return false;
}
if(document.form3.old_password.value=="")
{
alert("Please enter the Valid Password");
return false;
}





if (document.form3.new_password.value=="")
{

alert("Please enter new password");
return false;
}
if(document.form3.new_password.value != "" ) {
    if(document.form3.new_password.value.length < 8) {
      alert("Error: Password must contain at least Eight characters!");
      //form.password.focus();
      return false;
    }}
re = /[0-9]/;
if(!re.test(document.form3.new_password.value)) {
  alert("Error: password must contain at least one number (0-9)!");
  document.form3.new_password.focus();
  return false;
	}
	      re = /[a-z]/;
if(!re.test(document.form3.new_password.value)) {
  alert("Error: password must contain at least one lowercase letter (a-z)!");
  document.form3.new_password.focus();
  return false;
}
re = /[A-Z]/;
if(!re.test(document.form3.new_password.value)) {
  alert("Error: password must contain at least one uppercase letter (A-Z)!");
  document.form3.new_password.focus();
  return false;
}

if(document.form3.new_password.value=="")
{
alert("Please enter the Valid Password");
return false;
}

iCharsx = /[!@#$%^&*]/;
if(!iCharsx.test(document.form3.new_password.value)) {
    alert("Error: password must contain at least one Special Characters FOR [ !@#$%^&* ]...");
    document.form3.new_password.focus();
    return false;
  }





if (document.form3.confirm_new_password.value=="")
{

alert("Please enter confirm password");
return false;
}
if(document.form3.confirm_new_password.value=="")
{
alert("Please enter the Valid Confirm Password");
return false;
}
    if(document.form3.new_password.value == document.form3.old_password.value)
    {
        alert("Password Same For Previous One....");
        return false;
    }
var md5password = sha256_digest(document.form3.old_password.value)
document.form3.old_password.value = md5password;


 var md5password1 = sha256_digest(document.form3.new_password.value)
document.form3.new_password.value = md5password1;
 
 var md5password2 = sha256_digest(document.form3.confirm_new_password.value)
document.form3.confirm_new_password.value = md5password2;




document.form3.submit();
}



function submitForm1()
{
with(document.frm)
 {
 action = "change_pass.php";
 submit();
 }
 }
</script>
</head>
<body>
    

<?php

$random =$_REQUEST['random'];
if($random ==$_SESSION['random'])
{

$_SESSION['salt'] = sha1(microtime());

?>
<table border="0" width="90%" class="std" align="left">
<tr><td colspan="10"><font color="red" size="2">
The password should be at least “eight” characters in length. (Longer is generally better.)
The password should have at least one lower case alphabet, one upper case alphabets, one digit and one special character (a-z, A-Z, 0-9, Special characters Like [ !@#$%^&* ]
</font></td></tr>
</table>
<table border="0" width="50%" class="std" align="center">

<form name="form3" method="post" action="change_pass_action.php">
<br>
<input type="hidden" name="key" value="<?php echo htmlspecialchars($link); ?>" />
<br>
<?php 
$uname=$_SESSION['user'];
$ipaddress=htmlspecialchars($_SERVER["REMOTE_ADDR"]);
$yy= date("Y/m/d h:i:s");
$at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status)
		values (?,?,?,?,?)");
$xx='Change Password';
$x='security True Access';
$at->bindParam(1, $uname, PDO::PARAM_STR);
$at->bindParam(2, $ipaddress, PDO::PARAM_STR);
$at->bindParam(3, $yy, PDO::PARAM_STR);
$at->bindParam(4, $xx, PDO::PARAM_STR);
$at->bindParam(5, $x, PDO::PARAM_STR);
$at->execute();


if($accesspoint1 =='0')
{	
?>
<tr>
<td colspan="4"><font face="Verdana" size="2"><span class="error">
<a href="../logout.php">LOGOUT</a>
</td>
</tr>
<?php 
}
?>
<tr>
<td colspan="4"><font face="Verdana" size="2"><span class="error">
<?php
if($_REQUEST['msg']==1)
{
echo htmlspecialchars("Old password entered is not correct ");
}
?>
</td>
</tr>
<tr>
<td colspan="4"><font face="Verdana" size="2"><span class="error">
<?php
if($_REQUEST['msg']==4)
{
echo htmlspecialchars("Password entered is  Match For Last Password ");
}
?>
</td>
</tr>
<tr>
<td colspan="4"><font face="Verdana" size="2"><span class="error">
<?php
if($_REQUEST['msg']==2)
{
echo htmlspecialchars("New password and confirm new password are not same ");
}
?>
</td>
</tr>

<tr>
<td colspan="4"><font face="Verdana" size="2"><span class="error">
<?php
if($_REQUEST['msg']==3)
{
echo htmlspecialchars("Password changed successfully ");
}
?>
</td>
</tr>
<?php

?>

<tr>
<td width="104" align="left valign="top"  nowrap="nowrap"><font face="Verdana" size="2"><span class="error">*</span>Enter Old Password</font></td>
          <td width="110" align="left" valign="top"><input type="password" name="old_password"   maxlength="15" size="15" value="<?php print htmlspecialchars(htmlentities($old_password)); ?>" required  
>
</td>
</tr>

<tr>
<td width="104" align="left valign="top"  nowrap="nowrap"><font face="Verdana" size="2"><span class="error">*</span>Enter New  Password</font></td>
          <td width="110" align="left" valign="top"><input type="password" name="new_password"   maxlength="15" size="15" value="<?php print htmlspecialchars(htmlentities($new_password)); ?>" required >
</td>
</tr>


<tr>
<td width="104" align="left valign="top"  nowrap="nowrap"><font face="Verdana" size="2"><span class="error">*</span>Confirm New Password</font></td>
          <td width="110" align="left" valign="top"><input type="password" name="confirm_new_password"   maxlength="15" size="15" value="<?php print htmlspecialchars(htmlentities($confirm_new_password)); ?>" required >
</td>
</tr>
<?php 
// $_SESSION['np'] = htmlspecialchars($new_password);
?>
<input name="accesspoint11" type="hidden" value="<?php echo htmlspecialchars(htmlentities($accesspoint1)); ?>"/>

<!-- <input name="salt" type="hidden" value="<?php echo htmlspecialchars(htmlentities($_SESSION['salt'])); ?>"/>-->
<input type="hidden" name="form3" value="<?php echo htmlspecialchars($form3); ?>" />

<tr><td>&nbsp;</td><td colspan="8" align="left">
        <input type="submit" name="submit1" value="Change password"  onclick="return check_validation();">


</td></tr>

<?php
}
else
{
 print "Unauthorized access not permitted";
}
?>
<?php } ?>
