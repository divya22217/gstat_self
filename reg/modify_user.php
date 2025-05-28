<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../includes/db_inc.php");//database connection
session_start();
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
$level_level=$_SESSION['level_level'];
$dept=$_SESSION['dept'];
$leveladd=$_SESSION['level_level'];
if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
echo "Access Problem.....";
header("Location: ../login.php");
die();
}
$_SESSION['menuaccess_codeall'];
if($_SESSION['menuaccess_codeall'] !='9' and $main_id !='9999' and ($localadmin !='1' OR $localadmin !='2'))
{
echo "You Are Not Access This Page......";
header("Location: ../login.php");
die();
}
setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
die("Redirecting to login.php");
}
if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{


// This code not use next time .......	Schema session create Hear....


$sessionUserType=htmlspecialchars($_SESSION['id']);
$stlu = $db->prepare("select schema_id from users where id= ? ");
$stlu->bindParam(1, $sessionUserType, PDO::PARAM_STR);

$stlu->execute();
while ($row = $stlu->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$schema_idrun=$row['schema_id'];
}
$stlu = $db->prepare("select location from users where id= ? ");
$stlu->bindParam(1, $sessionUserType, PDO::PARAM_STR);

$stlu->execute();
while ($row = $stlu->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
	$locationq=$row['location'];
}
if($_SESSION['location'] != $locationq)
{
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	echo "You are not Valied User..... please login again";
	header("Location: ../login.php");
	die();
}
if($locationq =='')
{
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	echo "You are not Valied User..... please login again";
	header("Location: ../login.php");
	die();
}



?>


<!DOCTYPE html>
<html>
<head>
<title>EPFO || <?php
$urlll = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
$url_var = explode('/' , $urlll);
echo ucwords(end($url_var)); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php include("../includes/common_js.php");?>
<script>
	function DisableBackButton() {
		window.history.forward()
	}
	DisableBackButton();
	window.onload = DisableBackButton;
	window.onpageshow = function(evt) { if (evt.persisted) DisableBackButton() }
	window.onunload = function() { void (0) }
</script>
<script type="text/javascript" src="../includes/js/utf8_encode.js"></script>
<script src="../includes/js/sha256.js" language="javascript" ></script>
<script language="javascript">
function submitForm(){with(document.form){action = "<?php echo $_SERVER[SCRIPT_NAME];?>";submit();}}
	function submitForm1()
	{
	with(document.form)
	{
	if(search_by.value=='employee_code'){
	if(employee_code_check.value=='')
	{
	alert("Please Enter Employee Code..... ");
	employee_code_check.focus();
	return false;
	}
	re = /^\w+$/;
	if(!re.test(document.form.employee_code_check.value)) {
	alert("Error: employee code  must contain only letters, numbers !");
	document.form.employee_code_check.focus();
	return false;
	}
	}
	if(search_by.value=='email'){
	if(check_email.value=="")
	{
	alert("Please enter the Valid Email");
	check_email.focus();
	return false;
	}
	var regemail = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	if (regemail.test(check_email.value) == false)
	{
	alert('Invalid Email Address');
	return false;
	}
	}

	action = "<?php echo $_SERVER[SCRIPT_NAME];?>";
	submit();
	document.frm.gobtn.disabled = true;
	document.frm.gobtn.value = 'Please Wait...';
	return true;
	}
	}


function check_validation()
{
with(document.form)
	{
if(document.form.fname.value=="")
{
alert("Please enter Your First Name ");
document.form.fname.focus();
return false;
}



if(search_by.value=="email"){
if(document.form.employee_code.value=="")
{
alert("Please enter the Valid Employee Code");
document.form.employee_code.focus();
return false;
}
re = /^\w+$/;
	if(!re.test(document.form.employee_code.value)) {
	alert("Error: employee code  must contain only letters, numbers !");
}
}

if(search_by.value=='employee_code'){
if(document.form.useremail.value=="")
{
alert("Please enter Your Email");
document.form.useremail.focus();
return false;
}
var regemail = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
if (regemail.test(document.form.useremail.value) == false)
{
alert('Invalid Email Address');
return false;
}
}



/*
if(document.form.userpassword.value=="")
{
alert("Please enter the Valid Password");
document.form.userpassword.focus();
return false;
}
//      if(document.form.userpassword.value == document.form.uname.value) {
//        alert("Error: Password must be different from Username!");
//      return false;
//      }
	  if(document.form.userpassword.value != "" ) {
  if(document.form.userpassword.value.length < 8) {
	alert("Error: Password must contain at least Eight characters!");
	//form.password.focus();
	return false;
  }}
  re = /[0-9]/;
  if(!re.test(document.form.userpassword.value)) {
	alert("Error: password must contain at least one number (0-9)!");
	document.form.userpassword.focus();
	return false;
	}
		  re = /[a-z]/;
  if(!re.test(document.form.userpassword.value)) {
	alert("Error: password must contain at least one lowercase letter (a-z)!");
	document.form.userpassword.focus();
	return false;
  }
  re = /[A-Z]/;
  if(!re.test(document.form.userpassword.value)) {
	alert("Error: password must contain at least one uppercase letter (A-Z)!");
	document.form.userpassword.focus();
	return false;
  }

  iChars = /[!@#$%^&*]/;
  if(!iChars.test(document.form.userpassword.value)) {
	  alert("Error: password must contain at least one Special Characters FOR [ ! @ # $ % ^ & * ]...");
	  document.form.userpassword.focus();
	  return false;
	}

  if(document.form.userpassword.value != document.form.userpassword1.value)
  {
  alert("Password Not Matched...");
  return false;
  }
var md5password = sha256_digest(document.form.userpassword.value);
document.form.userpassword.value = md5password;
var md5password1 = sha256_digest(document.form.userpassword1.value);
document.form.userpassword1.value = md5password1;*/
	}
	
	return true;
}

</script>

</head>
<body id="top">
<?php include("../includes/header.php");?>
<div class="wrapper row3">
<main class="hoc container clear">
<div class="content">
  <div id="comments">
   <form name="form" method="post" action="modify_user_action.php" />
   <input type="hidden" name="csrf_token" value="<?php echo htmlentities(htmlspecialchars($_SESSION[reg_form]=sha1(rand())));?>" />
<fieldset>
	<legend><b>User Modification </b></legend>

<?php
if($_SESSION['menuaccess_codeall'] !='9' and $main_id !='9999' and ($localadmin !='1' OR $localadmin !='2'))
{

session_unset();session_destroy();
print "Invalid Form Entry .....";
header("Location: ../login.php?aa=108");
}
?>



<?php
if($_SESSION['menuaccess_codeall'] =='9' and $main_id =='9999' and ($localadmin =='1' OR $localadmin =='2'))
{

?>
<?php
if(htmlspecialchars_decode(html_entity_decode($_REQUEST['aap'])) =='117')
{
echo '<h6>';
   echo '<font color="red" size="4">';
echo 'User Create Successfully !!!!!';
echo "</br>";
}
?>

 </font>
</h6>





<div class="one_quarter first">
<?php   $search_by = isset($_REQUEST['search_by']) ? $_REQUEST['search_by'] :'employee_code';?>
<label for="name"><span color="red">*</span>Search By</label>
<select name="search_by"style="width:90%;" onChange="javascript:submitForm();">
<option value="employee_code" <?php  if($search_by=='employee_code'){ echo 'selected';}?> >Employee Code</option>
<option value="email" <?php  if($search_by=='email'){ echo 'selected';}?>>Email</option>
</select>
</div>


<?php if($search_by=='employee_code'){?>
<div class="one_quarter">
<label for="name"><span color="red">*</span>Employee Code</label>
<?php  $employee_code_check = isset($_REQUEST['employee_code_check']) ? $_REQUEST['employee_code_check'] :'';
if($employee_code_check !=''){
if (preg_match('/[\'^£$#~?><>]/',$employee_code_check)){
$employee_code_check='';
}
}
if($employee_code_check !='')
{
$sql_all12="select * from users where employee_code=?";
$sql_all=$db->prepare($sql_all12);
$sql_all->bindParam(1, $employee_code_check, PDO::PARAM_STR);
$sql_all->execute();
}
?>
<input type="text"  autocomplete="off" name="employee_code_check" value="<?php echo $employee_code_check;?>" maxlength="49"  />
</div>
<?php } ?>




<?php if($search_by=='email'){?>
<div class="one_quarter">
<label for="name"><span color="red">*</span>Email</label>
<?php  $check_email = isset($_REQUEST['check_email']) ? $_REQUEST['check_email'] :'';
if($check_email !=''){
if (preg_match('/[\'^£$#~?><>]/',$check_email)){
$check_email='';
}
}
if($check_email !='')
{
$sql_all12="select * from users where email=?";
$sql_all=$db->prepare($sql_all12);
$sql_all->bindParam(1, $check_email, PDO::PARAM_STR);
$sql_all->execute();
}
?>
<input type="text"  autocomplete="off" name="check_email" value="<?php echo $check_email;?>" maxlength="49"  />
</div>
<?php } ?>










<div class="one_quarter">
<label for="name">&nbsp;</label>
<input type="button"  size=5 name="go" id="gobtn" value="Check" onClick="javascript:submitForm1();">
</div>



<?php

if($employee_code_check !='' || $check_email!='')
{
if($employee_code_check!=''){
if($sql_all->rowCount()==0 )	{
echo '<div class="one_quarter first"><h2>EMPLOYEE CODE not found ! Please Re-enter other EMPLOYEE CODE  !!!!! </h2></div>';
}
}
if($check_email !='' )
{
if($sql_all->rowCount()==0 )	{
echo '<div class="one_quarter first"><h2>Email ID not found ! Please Re-enter other Email ID  !!!!! </h2></div>';
}
}


if($sql_all->rowCount()>0){
$rowdata = $sql_all->fetch();
//print_r($rowdata);
extract($rowdata);

?>
<?php
// Super admin access
if($_SESSION[localadmin] ==2)
{
?>
<div class="one_quarter first">
<font color="red">*</font>Location:
<?php  $drtname = isset($_REQUEST['drtname']) ? $_REQUEST['drtname'] :$schema_id;?>
<select name="drtname" style="width:380px" >
<option value="">--SELECT--</option>
<?php
$st = $db->prepare("select * from initilization order by short_name ASC ");
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$c_type = htmlspecialchars($row['schema_id']);
$short_name = htmlspecialchars(ucwords($row['short_name']));
$short_name = htmlspecialchars(ucwords(strtoupper($short_name)));
if($drtname == $c_type )
{
print "<option value=".htmlspecialchars($row['schema_id'])." selected>".htmlspecialchars(ucwords(strtoupper($short_name)))."</option>";
}
else
{
print "<option value=".htmlspecialchars($row['schema_id']).">".htmlspecialchars(ucwords(strtoupper($short_name)))."</option>";
}
}
?>
</select>
</div>
<?php
}
?>

<?php
//admin access
if($_SESSION[localadmin]==1)
{
?>
<div class="one_quarter first">
<input type="hidden" name="drtname" value="<?php echo $schema_id;?>">
</div>
<?php
}
?>

<div class="one_quarter first">
<label for="name"><span color="red">*</span> First Name:</label>
<?php   $fname = isset($_REQUEST['fname']) ? $_REQUEST['fname'] :$fname; ?>
<input type="text"  autocomplete="off" name="fname" onkeyup="this.value=this.value.replace(/[^a-z,A-Z]/g,'');"
value="<?php echo htmlentities(htmlspecialchars(strtoupper($fname)));?>" maxlength="255"  />
</div>

<div class="one_quarter">
<label for="name"><span color="red"></span>Middle Name:</label>
<?php  $mname = isset($_REQUEST['mname']) ? $_REQUEST['mname'] :''; ?>
<input type="text"  autocomplete="off" name="mname" onkeyup="this.value=this.value.replace(/[^a-z,A-Z]/g,'');"
value="<?php echo htmlentities(htmlspecialchars(strtoupper($mname)));?>" maxlength="150"  />
</div>

<div class="one_quarter">
<label for="name"><span color="red"></span>Last Name:</label>
<?php  $lname = isset($_REQUEST['lname']) ? $_REQUEST['lname'] :$lname; ?>
<input type="text"  autocomplete="off" name="lname" onkeyup="this.value=this.value.replace(/[^a-z,A-Z]/g,'');"
value="<?php echo htmlentities(htmlspecialchars(strtoupper($lname)));?>" maxlength="150"  />
</div>


<?php if($search_by =='employee_code'){?>
<div class="one_quarter first">
<?php  $useremail = isset($_REQUEST['useremail']) ? $_REQUEST['useremail'] :$email; ?>
<label for="name"> Email Id:<span color="red">*</span></label>
<input type="email" autocomplete="off" name="useremail" value="<?php echo htmlentities(htmlspecialchars($useremail));?>" maxlength="255" />
</div>
<?php } ?>

<?php if($search_by =='email'){?>
<div class="one_quarter first">
<?php  $employee_code = isset($_REQUEST['employee_code']) ? $_REQUEST['employee_code'] :$employee_code; ?>
<label for="name"> Employee Code:<span color="red">*</span></label>
<input type="email" autocomplete="off" name="employee_code" value="<?php echo htmlentities(htmlspecialchars($employee_code));?>" maxlength="255" />
</div>
<?php } ?>


<!--div class="one_quarter ">
<label for="name"><span color="red"></span>Father Name:</label>
<?php  $father_name = isset($_REQUEST['father_name']) ? $_REQUEST['father_name'] :$father_name; ?>
<input type="text"  autocomplete="off" name="father_name" onkeyup="this.value=this.value.replace(/[^a-z,A-Z]/g,'');"
value="<?php echo $father_name;?>" maxlength="150"  />
</div>


<div class="one_quarter ">
<label for="name"><span color="red"></span>Nationality</label>
<?php  $nationality = isset($_REQUEST['nationality']) ? $_REQUEST['nationality'] :$nationality; ?>
<input type="text"  autocomplete="off" name="nationality" maxlength="50" value="<?php echo $nationality;?>"   />
</div>


<div class="one_quarter ">
<label for="name"><span color="red"></span>Religion</label>
<?php  $religion = isset($_REQUEST['religion']) ? $_REQUEST['religion'] :$religion; ?>
<input type="text"  autocomplete="off" name="religion"  maxlength="50" value="<?php echo $religion;?>"   />
</div-->

<div class="one_quarter">
<label for="name"><span color="red"></span>Gender</label>
<?php  $gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] :$gender; ?>
<select name="gender" style="width:80%;">
<option value="">Select</option>
<option value="male" <?php if($gender =='male'){ echo 'selected';}?> >Male</option>
<option value="female"  <?php if($gender =='female'){ echo 'selected';}?> >Female</option>
</select>
</div>



<div class="one_quarter ">
<label for="name"><span color="red"></span>Designation</label>
<?php  $designation = isset($_REQUEST['designation']) ? $_REQUEST['designation'] :$designation; ?>
<select name="designation" style="width: 80%;">
<option value="">--select--</option>
<option value="0" <?php if($designation=='0'){echo 'selected';}?>>other</option>
<?php
$q_desg ="select * from master_desg WHERE display='TRUE' ORDER  by desg_name ASC";
$desg_data = $db->prepare($q_desg);
$desg_data->execute();

if($desg_data->rowCount()>0){

while($desg_row = $desg_data->fetch()){
?>
<option value="<?php echo htmlspecialchars($desg_row[desg_code])?>" <?php if($designation==$desg_row[desg_code]){echo 'selected';}?>  >
<?php echo htmlspecialchars($desg_row[desg_name])?></option>
<?php }} ?>
</select>
</div>

<!--div class="one_quarter ">
<label for="name"><span color="red"></span>Aadhar Number:</label>
<?php  $aadharnumber = isset($_REQUEST['aadharnumber']) ? $_REQUEST['aadharnumber'] :$aadhar_no; ?>
<input type="text"  autocomplete="off" name="aadharnumber" onkeypress="return isNumberKey(event)"
value="<?php echo htmlentities(htmlspecialchars($aadharnumber));?>" maxlength="12"  />
</div-->


<?php  $usermobile = isset($_REQUEST['usermobile']) ? $_REQUEST['usermobile'] :$mobile_no; ?>
<div class="one_quarter first">
<label for="name"><span color="red"></span> Mobile Number :</label>
<input type="text" autocomplete="off" id="numbersOnly"  onkeypress="return isNumberKey(event)"
name="usermobile" value="<?php echo $usermobile;?>" maxlength="10"  />
</div>

<div class="one_quarter">
<label for="name">Joining Date :</label>
<?php  $dt_of_appoint = isset($_REQUEST['dt_of_appoint']) ? $_REQUEST['dt_of_appoint'] :$dt_of_appoint; ?>
<input type="text"  autocomplete="off" class="datepicker" name="dt_of_appoint" value="<?php echo $dt_of_appoint;?>"   />
</div>

<!--div class="one_quarter ">
<label for="name"><span color="red"></span>Date Of Retirement:</label>
<?php  $dt_o_r = isset($_REQUEST['dt_o_r']) ? $_REQUEST['dt_o_r'] :$dt_o_r; ?>
<input type="text"  autocomplete="off" readonly="true" class="datepicker" name="dt_o_r" value="<?php echo $dt_o_r;?>"   />
</div>
<div class="one_quarter ">
<label for="name"><span color="red"></span>Year of Allotment</label>
<?php  $year_of_allotment = isset($_REQUEST['year_of_allotment']) ? $_REQUEST['year_of_allotment'] :$year_of_allotment; ?>
<input type="text"  autocomplete="off"  name="year_of_allotment" value="<?php echo $year_of_allotment;?>"   />
</div>


<div class="one_quarter first">
<label for="name"><span color="red"></span>Type of Appointment</label>
<?php  $type_of_appoint = isset($_REQUEST['type_of_appoint']) ? $_REQUEST['type_of_appoint'] :$appoint_type; ?>
<select name="type_of_appoint" style="width:80%;">
<option value="dp" <?php if($type_of_appoint =='dp'){ echo 'selected';}?> >Direct Posting</option>
<option value="dr"  <?php if($type_of_appoint =='dr'){ echo 'selected';}?> >Direct Recruitment</option>
</select>
</div-->



<div class="one_quarter first">
<label for="name"><span color="red">*</span> Password : :</label>
<?php  $userpassword = isset($_REQUEST['userpassword']) ? $_REQUEST['userpassword'] :''; ?>
<input type="password"  autocomplete="off" name="userpassword" value="<?php echo htmlentities(htmlspecialchars($userpassword));?>" maxlength="350" />
</div>

<div class="one_quarter">
<label for="name"><span color="red">*</span> Re Enter Password :</label>
<?php  $userpassword1 = isset($_REQUEST['userpassword1']) ? $_REQUEST['userpassword1'] :''; ?>
<input type="password"  autocomplete="off" name="userpassword1" value="<?php echo htmlentities(htmlspecialchars($userpassword1));?>" maxlength="350" />
</div>





<div class="block clear">
<label for="name">&nbsp;</label>
<input type="submit" name="register" id="register" class="next btn btn-info" value="Update" onclick="return check_validation();"/>
</div>

<?php
}
}
?>


</fieldset>
</form>
</div>
</div>
</main>
</div>




<?php include("../includes/footer.php");?>
</body></html>

<?php } ?>

<?php } ?>