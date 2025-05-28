<?php
die();
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");
date_default_timezone_set("Asia/Kolkata");
include("../includes/db_inc.php");
// At the top of the page we check to see whether the user is logged in or not
$page_access_time= date("Y/m/d h:i:s");
$at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status)
		values (?,?,?,?,?)");
$xx='User Deleted';
$x='security True Access';
$at->bindParam(1, $_SESSION[user], PDO::PARAM_STR);
$at->bindParam(2, $_SERVER[REMOTE_ADDR], PDO::PARAM_STR);
$at->bindParam(3, $page_access_time, PDO::PARAM_STR);
$at->bindParam(4, $xx, PDO::PARAM_STR);
$at->bindParam(5, $x, PDO::PARAM_STR);
$at->execute();
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{

	die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
	echo "you Can't access this page";
}
else {

	$stlu = $db->prepare("select schema_id,main_id,localadmin,location from users where id= ? ");
	$stlu->bindParam(1, $_SESSION[id], PDO::PARAM_STR);
	$stlu->execute();
	while ($row = $stlu->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		$localadminzz=$row['localadmin'];
		$main_id=$row['main_id'];
		$locationq=$row['location'];
		$schema_id_db=$row['schema_id'];

	}
	$hash=$_REQUEST['ida'];
	$hash =htmlspecialchars_decode(base64_decode($hash));
	$hash=explode("#",$hash);
	$_token = $hash[0];
	$msg_id=$hash[1];
	$link_schema=$hash[2];
	$place=$hash[4];
	if($_token!=$_SESSION[reg_form]){
		//session_unset();session_destroy();
		header("Location: ../login.php?aa=108");
	}
	if($localadminzz==1) {
		if ($locationq != $place) {
			session_unset();session_destroy();
			header("Location: ../login.php?aa=108");
			die('Location2 Not Matched');
		}
		if ($schema_id_db != $link_schema) {
			session_unset();session_destroy();
			header("Location: ../login.php?aa=108");
			die('Location Not Matched');
		}
	}

if($msg_id !='')
{

$access = '';
	$stl = $db->prepare("update users set accesspoint1=? ,login_status=? where id =? ");
	$stl->execute(array($access,'Deleted',$msg_id));
	
	$deletemsg="User Account Deleted Successfully....";
	echo "<h2>$deletemsg <h2>";
	//$msg11=$msg1.'/'.$sessionUserType;
	//$hashpss=base64_encode($deletemsg);
	//header("Location:user_report.php?msggss=$hashpss");

}
	
}
?>