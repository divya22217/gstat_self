<?php 
header("location:../index.php");
die();
?>

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
header("Cache-Control=proxy-revalidate");

date_default_timezone_set("Asia/Kolkata");
//require '../db_inc1.php';
include("../includes/db_inc.php");//database connection
session_start();

$_SESSION['user'];
$_SESSION['location'];

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

	/*
	 $uname=$_SESSION['user'];
$ipaddress=htmlspecialchars($_SERVER["REMOTE_ADDR"]);
$yy= date("Y/m/d h:i:s");
$at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status) values (?,?,?,?,?)");
	  $xx='Case Scrutiny';
	  $x='Valid security';
$at->bindParam(1, $uname, PDO::PARAM_STR);
$at->bindParam(2, $ipaddress, PDO::PARAM_STR);
$at->bindParam(3, $yy, PDO::PARAM_STR);
$at->bindParam(4, $xx, PDO::PARAM_STR);
$at->bindParam(5, $x, PDO::PARAM_STR);
$at->execute();
	 */

	$ref_form=sha1(rand());
	$_SESSION['reg_form'] = $ref_form;

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>USER REGISTRATION</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="../style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/cufon-yui.js"></script>
<script type="text/javascript" src="../js/arial.js"></script>
<script type="text/javascript" src="../js/cuf_run.js"></script>
<script type="text/javascript" src="../includes/angular.min.js"></script>
<?php include("../includes/common_js.php");?>
<link rel="stylesheet" type="text/css" href="../includes/mycssjs/jquery-ui-1.8.2.custom.css" />

<style type="text/css">

/* DivTable.com */
.divTable{
	display: table;
	width: 60%;
}
.divTableRow {
	display: table-row;
}
.divTableHeading {
	background-color: #EEE;
	display: table-header-group;
}
.divTableCell, .divTableHead {
	border: 1px solid #999999;
	display: table-cell;
	padding: 3px 10px;
}
.divTableHeading {
	background-color: #EEE;
	display: table-header-group;
	font-weight: bold;
}
.divTableFoot {
	background-color: #EEE;
	display: table-footer-group;
	font-weight: bold;
}
.divTableBody {
	display: table-row-group;
}



			#demoWrapper {
				padding : 1em;
				width : 80%;
				border-style: solid;

			}

			#fieldWrapper {
			}

			#demoNavigation {
				margin-top : 0.5em;
				margin-right : 1em;
				text-align: right;
			}

			#data {
				font-size : 0.7em;
			}

			input {
				margin-right: 0.1em;
				margin-bottom: 0.5em;
				color: red;
			}

			.input_field_25em {
				width: 2.5em;
			}

			.input_field_3em {
				width: 3em;
			}

			.input_field_35em {
				width: 3.5em;
			}

			.input_field_12em {
				width: 12em;
			}

			label {
				margin-bottom: 0.2em;
				font-weight: bold;
				font-size: 0.8em;
			}

			label.error {
				color: red;
				font-size: 0.8em;
				margin-left : 0.5em;
			}

			.step span {
				float: left;
				font-weight: bold;
				padding-right: 0.8em;
			}

			.navigation_button {
				width : 70px;
			}

			#data {
					overflow : auto;
			}
		</style>


<script>
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

</script>

<script type="text/javascript" src="../utf8_encode.js"></script>
<script src="sha256.js" language="javascript" ></script>
	<script language="javascript">

// WRITE YOUR CODE HERE

function goFinal1()
{
 	with(document.form)
	{

	action="index.php";
	submit();



	}
}
</script>
	<script language="javascript">

function submitForm1()
{
	 	with(document.form)
		{

	 	if(uname.value=='')
				{
						alert("Please Enter User Name ..... ");
						uname.focus();
						return false;
				}

		 action = "index.php";
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

	if( document.form.drtname.value == '' )
	   {
	     alert( "Please select DRT/DART NAME.....!" );
	     return false;
	   }

  if(document.form.uname.value == "") {
      alert("Error: Username cannot be blank!");
     // form.username.focus();
      return false;
    }
 re = /^\w+$/;
    if(!re.test(document.form.uname.value)) {
      alert("Error: Username must contain only letters, numbers and underscores!");
      document.form.uname.focus();
      return false;
    }
	   if(document.form.uname.value != "" ) {
      if(document.form.uname.value.length < 5) {
        alert("Error: username must contain at least Five characters!");
      return false;
      }}


if(document.form.userpassword.value=="")
{
alert("Please enter the Valid Password");
return false;
}
      if(document.form.userpassword.value == document.form.uname.value) {
        alert("Error: Password must be different from Username!");
      return false;
      }
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

      if(document.form.fname.value == "") {
          alert("Error: First name cannot be blank!");
         //form.username.focus();
          return false;
        }

        if(document.form.useremail.value == "") {
          alert("Error: Email cannot be blank!");
         //form.username.focus();
          return false;
        }

      var regemail = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
      //var address = document.getElementById[email].value;
      if (regemail.test(document.form.useremail.value) == false)

      {
          alert('Invalid Email Address');
          return false;
      }


var md5password = sha256_digest(document.form.userpassword.value);

document.form.userpassword.value = md5password;


var md5password1 = sha256_digest(document.form.userpassword1.value);
document.form.userpassword1.value = md5password1;
/*
var md5p =sha256_digest(userpassword.value);
userpassword.value=md5p;
var md5p1 =sha256_digest(userpassword1.value);
userpassword1.value=md5p1;
*/
}

</script>
<?php
$masternot = sha1( uniqid('auth', true) );
$_SESSION['masterform'] = $masternot;

$no1=base64_encode($masternot);

?>
 <script>
    function popsurety_pending_report(cfy)

    {

    		var url = "../pending_report.php?no="+cfy;


    		 window.open(url,"_blank","directories=no, status=no,width=800, height=800, left=100, scrollbars=yes");

    }
    function popsurety_disposal_report(cfy)

    {

    		var url = "../disposal_report.php?no="+cfy;


    		 window.open(url,"_blank","directories=no, status=no,width=800, height=800, left=100, scrollbars=yes");

    }
    function popsurety_todayfiling_report(cfy)

    {

    		var url = "../todayfiling_report.php?no="+cfy;


    		 window.open(url,"_blank","directories=no, status=no,width=800, height=800, left=100, scrollbars=yes");

    }

   function  popsurety_olddairy_report(cfy)

    {

    		var url = "../old_dairynoreport.php?no="+cfy;


    		 window.open(url,"_blank","directories=no, status=no,width=800, height=800, left=100, scrollbars=yes");

    }



    </script>
</head>
<body>
<?php include("../includes/header.php");?>
<div class="wrapper row3">
<main class="hoc container clear">
 <div class="clr"></div>

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
      	<center>
      	    <legend> <h1><font color="#000000" size='3'><u>Create User:</u></font></h1></legend>

    <fieldset>
   <center>
   <font color="red" size="4">
   <?php
   $aap=$_REQUEST['aap'];
   if($aap =='117')
   {
   echo htmlspecialchars(htmlentities(" User Create Successfully !!!!! "));
   echo "</br>";
   }
   ?>

     </font>
   </center>

 <form name="form" method="post" action="reg_action.php" />
 <input type="hidden" name="ref_validate" value="<?php echo htmlentities(htmlspecialchars($ref_form));?>" />
   <div class="divTable" >
<div class="divTableBody">

<div class="divTableRow">
 <div class="divTableCell" align="right">
   <font color="red">*</font>Location:</div>
   <div class="divTableCell" >

 <?php

 if($_SESSION['menuaccess_codeall'] =='9' and $main_id =='9999' and $localadmin =='2')
 {

 ?>

  <?php  $drtname = isset($_REQUEST['drtname']) ? $_REQUEST['drtname'] :'';?>

<select name="drtname" style="width:380px" >
<option value="">SELECT EPFO LOCATION</option>
                        <?php

            $st = $db->prepare("select * from initilization ");
            //$st->bindParam(1, $schema_idrun, PDO::PARAM_STR);
            $st->execute();
            while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
            {

            		$c_type = htmlspecialchars($row['schema_id']);
            		$short_name = htmlspecialchars(ucwords($row['name']));
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

  <?php  $drtname = isset($_REQUEST['drtname']) ? $_REQUEST['drtname'] :'';?>

<select name="drtname" style="width:200px" >

                        <?php

            $st = $db->prepare("select * from initilization where  schema_id=?");
            $st->bindParam(1, $schema_idrun, PDO::PARAM_STR);
            $st->execute();
            while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
            {

            		$c_type = htmlspecialchars($row['schema_id']);
            		$short_name = htmlspecialchars(ucwords($row['name']));
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
				 } // State admin
            ?>

    </div>
   </div>
   <!--  <div class="divTableRow">
   <div class="divTableCell" align="right">
   <font color="red">*</font>Name:</div>
   <div class="divTableCell">
   <input type="hidden" readonly="readonly" style="width:320px;" autocomplete="off" name="abcd"
   value="<?php echo htmlentities(htmlspecialchars(strtoupper($short_name)));?>" maxlength="50"
   id="abcd" />
    </div></div>-->
            <?php

 if($_SESSION['menuaccess_codeall'] =='9' and $main_id =='9999' and $localadmin =='1')
 {


 ?>
  <div class="divTableRow">
 <div class="divTableCell" align="right">
  <font color="red">*</font> Access Menu :

    </div>
    <div class="divTableCell">
 <?php  $m_case_type = isset($_REQUEST['m_case_type']) ? $_REQUEST['m_case_type'] :'';?>

<select name="m_case_type" style="width:200px" onChange="javascript:goFinal1();">
<option value="">Select</option>
<?php
$sqla=$db->prepare("select * from user_accessloc where display=? order by id asc");
$disp='TRUE';
$sqla->bindParam(1, $disp, PDO::PARAM_STR);
 $sqla->execute();
 while ($row = $sqla->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{

  $casetypecode=$row['id'];
 if($m_case_type == $casetypecode)
                {
		print "<option value=".htmlspecialchars($row['id'])." selected>".htmlspecialchars($row['name'])."</option>";
                }
           else
                {
                print "<option value=".htmlspecialchars($row['id']).">".htmlspecialchars($row['name'])."</option>";
		}
 }
 ?>

</select>


  </div></div>
<?php
if($m_case_type == '2')
{
?>
  <div class="divTableRow">
 <div class="divTableCell" align="right">
  <font color="red">*</font> Scrutiny Menu Access :

    </div>
    <div class="divTableCell">
 <?php  $m_case_typex = isset($_REQUEST['m_case_typex']) ? $_REQUEST['m_case_typex'] :'';?>

<select name="m_case_typex" style="width:200px" onChange="javascript:submitForm2();">
<option value="">Select</option>
<option value="1">Scrutiny Asstt. ( DH )</option>
<option value="2">Scrutiny Officer ( SO )</option>
<option value="3">Registatar Room</option>



</select>


  </div></div>
<?php
 }
 }
 ?>

   <div class="divTableRow">
   <div class="divTableCell" align="right">
   <font color="red">*</font>User Name:</div>
   <div class="divTableCell">
   <?php  $uname = isset($_REQUEST['uname']) ? $_REQUEST['uname'] :''; ?>
   <input type="text"  style="width:320px;" autocomplete="off" name="uname"
   value="<?php echo htmlentities(htmlspecialchars(strtolower($uname)));?>" maxlength="50"
   id="uname" onkeypress="this.value = this.value.toLowerCase();" />
  <input type="button"  size=5 name="go" id="gobtn" value="User Name Check" onClick="javascript:submitForm1();">
  <?php
  if($uname !='')
  {
  	$uname=htmlentities(htmlspecialchars(strtolower($uname)));
  	$sql_all12="select username from users where username=?";
  	$sql_all=$db->prepare($sql_all12);
  	$sql_all->bindParam(1, $uname, PDO::PARAM_STR);
  	$sql_all->execute();
  	$found_username = $sql_all->fetchColumn();
  }
  //echo $found_username;
  	if($found_username !='' and $uname !='')
  	{
  echo 'USER NAME FOUND ... PLEASE RE-ENTER USER NAME !!!!! ';
  die();
  }

  ?>

    </div></div>
<?php
if($found_username =='' and $uname !='')
{
?>

  <div class="divTableRow">
  <div class="divTableCell" align="right">
  <?php  $userpassword = isset($_REQUEST['userpassword']) ? $_REQUEST['userpassword'] :''; ?>
  <font color="red">*</font> Password:
   </div>
   <div class="divTableCell">
   <input type="password" style="width:320px;" autocomplete="off" name="userpassword" value="<?php echo htmlentities(htmlspecialchars($userpassword));?>" maxlength="350" />
   </div></div>
   <div class="divTableRow">
   <div class="divTableCell" align="right">
    <?php  $userpassword1 = isset($_REQUEST['userpassword1']) ? $_REQUEST['userpassword1'] :''; ?>

  <font color="red">*</font>Re Enter Password:
  </div>
  <div class="divTableCell">
  <input type="password" style="width:320px;" autocomplete="off" name="userpassword1" value="<?php echo htmlentities(htmlspecialchars($userpassword1));?>" maxlength="350" />
   </div></div>
    <div class="divTableRow">
   <div class="divTableCell" align="right">
      <?php  $fname = isset($_REQUEST['fname']) ? $_REQUEST['fname'] :''; ?>

  <font color="red">*</font> First Name:
   </div>
   <div class="divTableCell">
   <input type="text" style="width:320px;" autocomplete="off" name="fname" onkeyup="this.value=this.value.replace(/[^a-z,A-Z]/g,'');"
   value="<?php echo htmlentities(htmlspecialchars(strtoupper($fname)));?>" maxlength="255"  />
   </div>
 </div>

   <div class="divTableRow">
   <div class="divTableCell" align="right">
        <?php  $mname = isset($_REQUEST['mname']) ? $_REQUEST['mname'] :''; ?>
  Middle Name:
   </div>
   <div class="divTableCell">
   <input type="text" style="width:320px;" autocomplete="off" name="mname" onkeyup="this.value=this.value.replace(/[^a-z,A-Z]/g,'');"
   value="<?php echo htmlentities(htmlspecialchars(strtoupper($mname)));?>" maxlength="150"  />
   </div>
 </div>
   <div class="divTableRow">
   <div class="divTableCell" align="right">

   Last Name:
   </div>
   <div class="divTableCell">
           <?php  $lname = isset($_REQUEST['lname']) ? $_REQUEST['lname'] :''; ?>
      <input type="text" style="width:320px;" autocomplete="off" name="lname" onkeyup="this.value=this.value.replace(/[^a-z,A-Z]/g,'');"
   value="<?php echo htmlentities(htmlspecialchars(strtoupper($lname)));?>" maxlength="150"  />
   </div>
 </div>
   <div class="divTableRow">
   <div class="divTableCell" align="right">
           <?php  $useremail = isset($_REQUEST['useremail']) ? $_REQUEST['useremail'] :''; ?>

  <font color="red">*</font> Email:
   </div>
   <div class="divTableCell">
   <input type="text" style="width:320px;" autocomplete="off" name="useremail" value="<?php echo htmlentities(htmlspecialchars(strtoupper($useremail)));?>" maxlength="255" />
 </div>
 </div>
 <div class="divTableRow">
 <div class="divTableCell" align="right">
    Mobile/Phone No. :
     </div>
               <?php  $usermobile = isset($_REQUEST['usermobile']) ? $_REQUEST['usermobile'] :''; ?>

    <div class="divTableCell">
    <input type="text" size="15" autocomplete="off" id="numbersOnly"  onkeypress="return isNumberKey(event)"
    name="usermobile" value="<?php echo htmlentities(htmlspecialchars(strtoupper($usermobile)));?>" maxlength="15"  />
  </div></div>


 <div class="divTableRow">
 <div class="divTableCell" align="right">

    </div>
    <div class="divTableCell">
     <input type="submit" value="<?php echo htmlspecialchars('Register');?>" onclick="return check_validation();"/>
      </div></div>
<?php } ?>

 </br></br></br></br>
 </div>
  </form>
 </div>
    </fieldset>

     </center>

</div>
<?php include("../includes/footer.php");?>
</body></html>

 <?php } ?>

  <?php } ?>
