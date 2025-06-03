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
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");
date_default_timezone_set("Asia/Kolkata");
include("../includes/db_inc.php");//database connection
$sessionUserType=htmlspecialchars($_SESSION['id']);
	$stlu = $db->prepare("select main_id,localadmin,location from users where id= ? ");
	$stlu->bindParam(1, $sessionUserType, PDO::PARAM_STR);

	$stlu->execute();
	while ($row = $stlu->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		$localadminzz=$row['localadmin'];
		$main_id=$row['main_id'];
		$locationq=$row['location'];
	}
	if($main_id =='9999' and $localadminzz != '0')
	{
// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{

	die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
echo "you Can't access this page";
}
else 
{


	$stlu = $db->prepare("select schema_id,main_id,localadmin,location from users where id= ? ");
	$stlu->bindParam(1, $sessionUserType, PDO::PARAM_STR);
	$stlu->execute();
	while ($row = $stlu->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		$localadminzz=$row['localadmin'];
		$main_id=$row['main_id'];
		$locationq=$row['location'];
		$schema_id_db=$row['schema_id'];

	}
	if($main_id =='9999' and ($localadminzz =='2' OR $localadminzz =='1'))
	{
	
	$hash= $_REQUEST['ida'];
	$hash =htmlspecialchars_decode(base64_decode($hash));
	$hash=explode("#",$hash);
	$_token = $hash[0];
	$aazz=$hash[1];
	$link_schema=$hash[2];
	$place=$hash[4];
	if($_token!=$_SESSION[reg_form]){
	session_unset();session_destroy();
    header("Location: ../login.php?aa=108");
    die('form Not Matched');
	}
	if($localadminzz==1) {
	/* if($locationq!=$place){
	session_unset();session_destroy();
    header("Location: ../login.php?aa=108");
    die('Location2 Not Matched');
	}
	if($schema_id_db!=$link_schema){
	session_unset();session_destroy();
    header("Location: ../login.php?aa=108");
    die('Location Not Matched');
	} */
	$userids = $db->prepare("select schema_id,main_id,localadmin,location from users where id= ? ");
	$userids->bindParam(1, $aazz, PDO::PARAM_STR);
	$userids->execute();
	while ($row = $userids->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		
		
		$userlocationq=$row['location'];
		$userschema_id_db=$row['schema_id'];

	}
	 if($locationq!=$userlocationq){
	session_unset();session_destroy();
    header("Location: ../login.php?aa=108");
    die('Location2 Not Matched');
	}
	if($schema_id_db!=$userschema_id_db){
	session_unset();session_destroy();
    header("Location: ../login.php?aa=108");
    die('Location Not Matched');
	} 
	
	}


?>
<!DOCTYPE html>
<html>
    <head>
<meta charset="UTF-8">
<title></title>

<script type="text/javascript" src="../includes/js/utf8_encode.js"></script>
<script type="text/javascript" src="../includes/js/sha256.js"></script>
<script type="text/javascript">
function check_validation()
{
var iChars = "!@#$%^&*()+=[]\\\';,/{}|\":<>?";
if(document.form.password.value=="")
{
alert("Please enter the Valid Password");
return false;
}
if(document.form.password.value != "" ) {
if(document.form.password.value.length < 8) {
alert("Error: Password must contain at least Eight characters!");
//form.password.focus();
return false;
}}
re = /[0-9]/;
if(!re.test(document.form.password.value)) {
alert("Error: password must contain at least one number (0-9)!");
document.form.password.focus();
return false;
}
re = /[a-z]/;
if(!re.test(document.form.password.value)) {
alert("Error: password must contain at least one lowercase letter (a-z)!");
document.form.password.focus();
return false;
}
re = /[A-Z]/;
if(!re.test(document.form.password.value)) {
alert("Error: password must contain at least one uppercase letter (A-Z)!");
document.form.password.focus();
return false;
}
iCharsx = /[!@#$%^&*]/;
if(!iCharsx.test(document.form.password.value)) {
alert("Error: password must contain at least one Special Characters FOR [ !@ # $ % ^ & * ]...");
document.form.password.focus();
return false;
}
if(document.form.old_password.value=="")
{
alert("Please enter the Valid Confirm Password");
return false;
}

if(document.form.password.value != document.form.old_password.value)
{
alert("Password Not Same ....");
return false;
}



var md5password =sha256_digest(document.form.password.value);
document.form.password.value = md5password;
var md5password1 = sha256_digest(document.form.old_password.value);
document.form.old_password.value = md5password1;

}

</script>
     
    </head>
    <body>

<center>
<h1><u>User Password Reset</u></h1>
<?php
 $form = md5( uniqid('auth', true) );

/*** set the session form token ***/
$_SESSION['form_token'] = $form;//csrf
?>
<form action="forget_password_action.php" method="POST"  name="form">

<input type="hidden" name="aazz" value="<?php echo htmlspecialchars(htmlentities(base64_encode($aazz))); ?>" />

</br></br>
   Reset Password:<b/r></br>
    Reset Password<input type="password" name="password" required="required" autocomplete="off" />
   
    <br /></br>
Confirm Password<input type="password" name="old_password" required="required" autocomplete="off" />
  <input type="hidden" name="form1" value="<?php echo htmlspecialchars($form); ?>" />     
    </br></br>
    <input type="submit" value="<?php echo htmlspecialchars('Reset Password');?>" onclick="return check_validation();"/>
</form>
  </center>      
        
    </body>
</html>
	
	
<?php 

	}
else{echo "Access Problem .....";}	
	
}  //else loop close

	}//close if condt...
?>	
	
