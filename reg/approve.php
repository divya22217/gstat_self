<?php
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


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>EPFO</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php include("../includes/common_js.php");?>
<script language="javascript">

// WRITE YOUR CODE HERE
function search()
{
with(document.frm)
{
    action = "approve.php";
    submit();
}
}


function goFinal()
{
with(document.frm)
{

    if(case_type.options[case_type.selectedIndex].value == "")
    {
        alert("Please Select User Name  ");
        case_type.focus();
        return false;
    }
    if(menu.options[menu.selectedIndex].value == "")
    {
        alert("Please Select Head Menu  ");
        menu.focus();
        return false;
    }

    action="approve_action.php";
    submit();
    document.frm.submit11.disabled = true;
    document.frm.submit11.value = 'Please Wait...';
    return true;


}
}
</script>
</head>
<body>
<?php include("../includes/header.php");?>
<div class="wrapper row3">
<main class="hoc container clear">
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
if($_SESSION['menuaccess_codeall'] =='9' and $main_id =='9999' and $localadmin =='2')
{
echo '<h6 style="text-transform: none; color: red;">You have not permission to use  this module .Please contact to local admin  </h6>';
}?>




<?php
if($_SESSION['menuaccess_codeall'] =='9' and $main_id =='9999' and $localadmin =='1')
{

?>
<legend> <h1 ><font color="#000000" size='3'><u>User Request Pending for activation</u></font></h1></legend>

<fieldset>





            <form name="frm" method="post" action="approve.php">
                <table border="112">
                <tr>
                <!--th  align="center" colspan="10">
                <b>ASSIGN MENU</b></font></th-->
                </tr>

<?php
if(htmlentities($_REQUEST['msg1']) =='01x2')
{
echo "<font color='red'>Action implemented successfully</font>";
}
?>

<tr>
<td>
<b>
<font color="blue"><i><u>EMPLOYEE CODE</u></i></font>
</b>
</td>

<td>
<b>
<font color="blue"><i><u>USERNAME</u></i></font>
</b>
</td>

<td>
<b>
<font color="blue"><i><u>eMAIL</u></i></font>
</b>
</td>

<td>
<b>
<font color="blue"><i><u>MOBILE</u></i></font>
</b>
</td>

<td>
<b>
<font color="blue"><i><u>APPROVE</u></i></font>
</b>
</td>

<td>
<b>
<font color="blue"><i><u>REJECT</u></i></font>
</b>
</td>

</tr>
                <?php

        $stlu = $db->prepare("select schema_id from users where id= ? ");
	$stlu->bindParam(1, $sessionUserType, PDO::PARAM_STR);
	$stlu->execute();
	while ($row = $stlu->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		$schema_idrun=$row['schema_id'];
	}


 $st = $db->prepare("select * from users where schema_id=? and login_status ='Applied' order by id asc");
                                
$st->bindParam(1, $schema_idrun, PDO::PARAM_STR);
$st->execute();
$m=0;
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
?>
<tr>
<td>
<?php
echo htmlentities($row['employee_code']);
?>
</td>

<td>
<?php
echo htmlentities($row['username']);
?>
</td>

<td>
<?php
echo htmlentities($row['email']);
?>
</td>

<td>
<?php
echo htmlentities($row['mobile_no']);
?>
</td>



<td>
<input type="checkbox" name="approved[]" value="<?php echo htmlentities($row['id']);?>">
</td>

<td>
<input type="checkbox" name="rejected[]" value="<?php echo htmlentities($row['id']);?>">
</td>

</tr>
<?php
}
?>
<tr>

<input type ="hidden" name="action" value ="1"/>

<td colspan="6" align="center">
<input type="submit" value="Activate">
</td>
</tr>
                          
                    </table>

</fieldset>
<?php 

$action=htmlentities($_REQUEST['action']);

if($action>0)
{


foreach($_REQUEST['approved'] as $id) 
{

if ($id != '')
{
$status='Approved'.' '.date('Y-m-d');
                $st = $db->prepare("update users set login_status=? where id=?");
                $st->bindParam(1, $status, PDO::PARAM_STR);
                $st->bindParam(2, $id, PDO::PARAM_STR);
                $st->execute();
    //header("Location:approve.php?msg1=01x2");

}

}

foreach($_REQUEST['rejected'] as $id) 
{

if ($id != '')
{
$status='Rejected'.' '.date('Y-m-d');
                $st = $db->prepare("update users set login_status=? where id=?");
                $st->bindParam(1, $status, PDO::PARAM_STR);
                $st->bindParam(2, $id, PDO::PARAM_STR);
                $st->execute();
    //header("Location:approve.php?msg1=01x2");

}

}

header("Location:approve.php?msg1=01x2");

}







} ?>
<div class="clr"></div>
</div>
<?php include("../includes/footer.php");?>
</body></html>
 <?php } ?>
