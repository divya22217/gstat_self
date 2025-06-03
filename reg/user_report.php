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

$page_access_time= date("Y/m/d h:i:s");
$at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status)
		values (?,?,?,?,?)");
$xx='User Report view';
$x='security True Access';
$at->bindParam(1, $_SESSION[user], PDO::PARAM_STR);
$at->bindParam(2, $_SERVER[REMOTE_ADDR], PDO::PARAM_STR);
$at->bindParam(3, $page_access_time, PDO::PARAM_STR);
$at->bindParam(4, $xx, PDO::PARAM_STR);
$at->bindParam(5, $x, PDO::PARAM_STR);
$at->execute();

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
	$_SESSION[reg_form] = sha1(uniqid(rand()));



?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>EPFO</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php include("../includes/common_js.php");?>

 <script>
    function popsurety_window(url)
    {


    		 window.open(url,"_blank","directories=no, status=no,width=800, height=400, left=50, scrollbars=yes");

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
    <fieldset>

     <legend><h4>User Report <?php if($localadmin =='2'){ echo '(By Super Admin)' ;}else{ echo 'By Admin' ;}	?>:</h4></legend>
 <?php if(isset($_REQUEST[msggss]) !='') { ?><h2 style="color: red;"><?php echo htmlspecialchars(base64_decode($_REQUEST[msggss]))?></h2><?php } ?>

 <div class="scrollable">
 <?php 
 $s="Approved";
if($localadmin =='2')
{
$sts = $db->prepare("select * from users where login_status like ? and localadmin =1 order by username ASC ");
$sts->bindValue(1, "$s%", PDO::PARAM_STR);
}
if($localadmin =='1')
{
$sts = $db->prepare("select * from users where login_status like ? and localadmin =0 and location=? order by username ASC");
$sts->bindValue(1, "$s%", PDO::PARAM_STR);
$sts->bindParam(2, $locationq, PDO::PARAM_STR);
}
$sts->execute();
if($sts->rowCount()==0){ echo '<h2>No Record Found</h2>';}
if($sts->rowCount()>0){
?>
 <table>
 <thead>
<tr>
<th>Employee Code</th>
<th>User Name</th>
<th>Location </th>
<th>Name</th>
<?php if($localadmin ==1){?><th>Menu Modify</th><?php } ?>
<th>&nbsp;</th>
<th>&nbsp;</th>
</tr>
</thead>
<tbody>
<?php
while ($row = $sts->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$username=$row['username'];
$place=$row['location'];
$fname=$row['fname'];
$accesspoint1=$row['accesspoint1'];
$login_status=$row['login_status'];
$user_schema_id=$row['schema_id'];
$idi=$row['id'];
$localadmin = $row['localadmin'];
if($place > 0)
{
$stl = $db->prepare("select schema_name from master_location where location_id =? ");
$stl->bindParam(1, $place, PDO::PARAM_STR);
$stl->execute();
$location_name = $stl->fetchColumn();
}
$hash =base64_encode($_SESSION[reg_form].'#'.$idi.'#'.$user_schema_id.'#'.$login_status.'#'.$place);
?>
<tr>
<td><?php echo htmlspecialchars(htmlentities($row[employee_code]))?></td>
<td><?php echo htmlspecialchars($username); ?></td>
<td ><?php echo htmlspecialchars_decode(html_entity_decode(strtoupper(str_replace("_"," ",$location_name))));?> </td>
<td ><?php echo htmlspecialchars($fname);?></td>
<?php if($localadmin ==0){?>
<td ><a href="#" onclick="javascript:popsurety_window('delete_menus.php?ida=<?php echo htmlspecialchars($hash);?>');">Menu Modify</a></td>
<?php } ?>
<td>
<a href="#" onclick="javascript:popsurety_window('forget_password.php?ida=<?php echo htmlspecialchars($hash);?>');">Reset Password</a>
</td>
</tr>
<?php
}
?>
</tbody>
 </table>
<?php } ?>
 </div>
</fieldset>
</main>
<div class="clr"></div>
</div>
<?php include("../includes/footer.php");?>
</body></html>
<?php } ?>
