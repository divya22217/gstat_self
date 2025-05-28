
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


$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key=$_SESSION['csrf'];

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

	

	$ref_form=sha1(rand());
	$_SESSION['reg_form'] = $ref_form;

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

function goFinal1()
{
 	with(document.form)
	{

	action="create_esttb.php";
	submit();



	}
}
</script>

	<script language="javascript">

function submitForm1()
{
	 	with(document.form)
		{

	 	if(est_id.value=='')
				{
						alert("Please Enter Employee Code..... ");
						est_id.focus();
						return false;
				}
				 re = /^\w+$/;
    if(!re.test(document.form.est_id.value)) {
      alert("Error: employee code  must contain only letters, numbers !");
      document.form.est_id.focus();
      return false;
    }

		 action = "create_esttb.php";
		 submit();
		 document.frm.gobtn.disabled = true;
	   	document.frm.gobtn.value = 'Please Wait...';
	   	return true;
		}
	}
	</script>
<script type="text/javascript">

function check_validation()
{

//	if( document.form.drtname.value == '' )
//	   {
//	     alert( "Please select DRT/DART NAME.....!" );
//	     return false;
//	   }

//  if(document.form.uname.value == "") {
//      alert("Error: Username cannot be blank!");
//     // form.username.focus();
//      return false;
//    }
// re = /^\w+$/;
//    if(!re.test(document.form.uname.value)) {
//      alert("Error: Username must contain only letters, numbers and underscores!");
//      document.form.uname.focus();
//      return false;
//    }
//	   if(document.form.uname.value != "" ) {
//      if(document.form.uname.value.length < 5) {
//        alert("Error: username must contain at least Five characters!");
//      return false;
//      }}


if(document.form.useremail.value=="")
{
alert("Please enter the Valid Email");
document.form.useremail.focus();
return false;
}
 var regemail = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
      //var address = document.getElementById[email].value;
      if (regemail.test(document.form.useremail.value) == false)

      {
          alert('Invalid Email Address');
          return false;
      }
if(document.form.fname.value=="")
{
alert("Please enter Your First Name ");
document.form.fname.focus();
return false;
}
if(document.form.est_id.value=="")
{
alert("Please enter Your Employee Code  ");
document.form.est_id.focus();
return false;
}
if(document.form.designation.value=="")
{
alert("Please choose designation  ");
document.form.designation.focus();
return false;
}
/*if(document.form.officer_name.value=="")
{
alert("Please choose officer name  ");
document.form.officer_name.focus();
return false;
}*/

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
document.form.userpassword1.value = md5password1;
}

</script>
<?php
$masternot = sha1( uniqid('auth', true) );
$_SESSION['masterform'] = $masternot;

$no1=base64_encode($masternot);

?>

</head>
<body id="top">
<?php include("../includes/header.php");?>
<div class="wrapper row3">
  <main class="hoc container clear">
	<div class="content">
      <div id="comments">
       <form name="form" method="post" action="esttb_reg_action1.php" />
       <input type="hidden" name="ref_validate" value="<?php echo htmlentities(htmlspecialchars($ref_form));?>" />
	<fieldset>
		<legend><b>Establishment Registration </b></legend>

	<?php
	if($_SESSION['menuaccess_codeall'] !='9' and $main_id !='9999' and ($localadmin !='1' OR $localadmin !='2'))
	{

	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	print "Invalid Form Entry .....";
	$aazzs='108';
	header("Location: ../login.php?aa=$aazzs");
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
   echo 'Establishment Added Successfully !!!!!';
   echo "</br>";
   }
   ?>

     </font>
   </h6>








 <?php

 if($_SESSION['menuaccess_codeall'] =='9' and $main_id =='9999' and $localadmin =='2')
 {

 ?>
<font color="red">*</font>Location:
  <?php  $drtname = isset($_REQUEST['drtname']) ? $_REQUEST['drtname'] :$schema_idrun;?>

<select name="drtname" style="width:380px" >
<option value="">SELECT EPFO LOCATION</option>
                        <?php

            $st = $db->prepare("select * from initilization order by short_name ASC ");
            //$st->bindParam(1, $schema_idrun, PDO::PARAM_STR);
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

            ?></select>
            <?php
				 } // Super admin
            ?>

 <?php

 if($_SESSION['menuaccess_codeall'] =='9' and $main_id =='9999' and $localadmin =='1')
 {
 ?>
<?php  $drtname = isset($_REQUEST['drtname']) ? $_REQUEST['drtname'] :$schema_idrun;?>
<input type="hidden" name="drtname" value="<?php echo $drtname;?>">
<?php
}
?>





<div class="one_quarter first">
<label for="name"><span color="red"></span>Establishment Code</label>
<?php  $est_id = isset($_REQUEST['est_id']) ? $_REQUEST['est_id'] :'';
if($est_id !=''){
// if(!preg_match('/^[a-zA-Z_]{1,10}$/',$est_id)){
	if (preg_match('/[\'^£$#~?><>]/',$est_id)){
  $est_id='';
}
}
 if($est_id !='')
  {

  	$sql_all12="select est_id from master_est_ecourt where est_id=?";
  	$sql_all=$db->prepare($sql_all12);
  	$sql_all->bindParam(1, $est_id, PDO::PARAM_STR);
  	$sql_all->execute();
  }
?>
<input type="text"  autocomplete="off" name="est_id" value="<?php echo $est_id;?>" maxlength="49"  />
</div>


<div class="one_quarter ">
	<label for="name">&nbsp;</label>
  <input type="button"  size=5 name="go" id="gobtn" value="Check" onClick="javascript:submitForm1();">
</div>
  <?php
//  if($employee_code !='')
//  {
 //check_safe($employee_code);
//  	//$employee_code=htmlspecialchars(htmlentities($employee_code));
//  	$sql_all12="select employee_code from users where employee_code=?";
//  	$sql_all=$db->prepare($sql_all12);
//  	$sql_all->bindParam(1, $employee_code, PDO::PARAM_STR);
//  	$sql_all->execute();
//  	$found_username = $sql_all->fetchColumn();
//  }
	//echo $found_username;
	if($sql_all->rowCount()>0 )	{
	echo '<h2>ESTABLISHMENT AllREADY EXISTS ... PLEASE RE-ENTER CORRECT ESTABLISHMENT CODE  !!!!! </h2>';

	}

if($est_id !=''and $sql_all->rowCount()==0)
{
?>


<div class="one_quarter first">
<label for="name"><span color="red">*</span> Establishment Name:</label>
<?php  $fname = isset($_REQUEST['fname']) ? $_REQUEST['fname'] :''; ?>
<input type="text"  autocomplete="off" required="required" name="fname" onkeyup="this.value=this.value.replace(/[^a-z,A-Z,0-1]/g,'');"
value="<?php echo htmlentities(htmlspecialchars(strtoupper($fname)));?>" maxlength="255"  />
</div>

<div class="one_quarter">
<label for="name"><span color="red"></span>Address 1:</label>
<?php  $mname = isset($_REQUEST['mname']) ? $_REQUEST['mname'] :''; ?>
<input type="text"  autocomplete="off" required="required" name="mname" >
value="<?php echo htmlentities(htmlspecialchars(strtoupper($mname)));?>" maxlength="150"  />
</div>

<div class="one_quarter">
<label for="name"><span color="red"></span>Address 2:</label>
<?php  $lname = isset($_REQUEST['lname']) ? $_REQUEST['lname'] :''; ?>
<input type="text"  autocomplete="off" required="required" name="lname" 
value="<?php echo htmlentities(htmlspecialchars(strtoupper($lname)));?>" maxlength="150"  />
</div>

<div class="one_quarter first">
<label for="name"><span color="red"></span>City:</label>
<?php  $city = isset($_REQUEST['city']) ? $_REQUEST['city'] :''; ?>
<input type="text"  autocomplete="off" required="required" name="city" onkeyup="this.value=this.value.replace(/[^a-z,A-Z]/g,'');"
value="<?php echo htmlentities(htmlspecialchars(strtoupper($city)));?>" maxlength="150"  />
</div>

<div class="one_quarter">
<label for="name"><span color="red"></span>Pincode:</label>
<?php  $pin = isset($_REQUEST['pin']) ? $_REQUEST['pin'] :''; ?>
<input type="text"  autocomplete="off" required="required" name="pin" 
value="<?php echo htmlentities(htmlspecialchars(strtoupper($pin)));?>" maxlength="150"  onkeypress="return isNumberKey(event)"/>
</div>

<?php  $useremail = isset($_REQUEST['useremail']) ? $_REQUEST['useremail'] :''; ?>
<div class="one_quarter ">
<label for="name"> Email Id:<span color="red">*</span></label>
 <input type="email" autocomplete="off" required="required" name="useremail" value="<?php echo htmlentities(htmlspecialchars($useremail));?>" maxlength="255" />
</div>



<?php  $usermobile = isset($_REQUEST['usermobile']) ? $_REQUEST['usermobile'] :''; ?>
<div class="one_quarter first">
<label for="name"><span color="red"></span> Mobile Number :</label>
<input type="text" autocomplete="off" required="required" id="numbersOnly"  onkeypress="return isNumberKey(event)"
    name="usermobile" value="<?php echo $usermobile;?>" maxlength="10"  />
</div>



<div class="block clear">
<label for="name">&nbsp;</label>
<input type="submit" name="register" id="register" value="<?php echo htmlspecialchars('Register');?>" onclick="return check_validation();"/>
</div>

<?php } ?>


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
