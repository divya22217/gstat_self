<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

date_default_timezone_set("Asia/Kolkata");
include("../includes/db_inc.php");
$_SESSION['user'];
$_SESSION['location'];

// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	// If they are not, we redirect them to the login page.
	// Remember that this die statement is absolutely critical.  Without it,
	// people can view your members-only content without logging in.
	die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
	echo "you Can't access this page";
}


if( $_POST['form3'] != $_SESSION['form_token'])
{
	echo 'Invalid form submission';
}
else {

$keyss=$_REQUEST['key'];	
	$accesspoint11=htmlspecialchars(htmlentities($_REQUEST['accesspoint11']));
if($accesspoint11 =='1' OR $accesspoint11 == '0')
{
	
$sessionUserid=htmlspecialchars($_SESSION['id']);

$old_password =htmlspecialchars(htmlentities($_POST['old_password']));

$new_password =htmlspecialchars(htmlentities($_POST['new_password']));
$confirm_new_password =htmlspecialchars(htmlentities($_POST['confirm_new_password']));

$sql="select password from users where id=?";
$sth = $db->prepare($sql);
$sth->bindParam(1, $sessionUserid, PDO::PARAM_INT);
$sth->execute();
$current_password = $sth->fetchColumn();


 $confirm_old_password= $current_password;

if($old_password !=$confirm_old_password)
{
//header("Location:change_pass.php?msg=1&ipq=$keyss");
die('ffdgfd');
}
if($new_password !=$confirm_new_password)
{
header("Location:change_pass.php?msg=2&ipq=$keyss");
die();
}

$sql="select id from his_password where userid=? and (pass1=? OR pass2=? OR pass3=?) ";
$sth = $db->prepare($sql);

$sth->bindParam(1, $sessionUserid, PDO::PARAM_INT);
$sth->bindParam(2, $new_password, PDO::PARAM_INT);
$sth->bindParam(3, $new_password, PDO::PARAM_INT);
$sth->bindParam(4, $new_password, PDO::PARAM_INT);
$sth->execute();
$oll = $sth->fetchColumn();
if($oll !='')
{
	header("Location:change_pass.php?msg=4&ipq=$keyss");
	die();
}

$accesspoint11=$_REQUEST['accesspoint11'];
if($accesspoint11 =='0')
{
$xa='1';
$sql="update users set accesspoint1=? where id =?";
$hscquery = $db -> prepare($sql);
$hscquery->bindParam(1, $xa, PDO::PARAM_STR);
$hscquery->bindParam(2,$sessionUserid, PDO::PARAM_INT);
$hscquery -> execute();
$sql="update users set password=? where id =?";
$hscquery = $db -> prepare($sql);
$hscquery->bindValue(1,"$new_password", PDO::PARAM_STR);
$hscquery->bindParam(2,$sessionUserid, PDO::PARAM_INT);
$hscquery -> execute();
$sql="update his_password set pass2=? where userid=?";
$hscquery = $db -> prepare($sql);
$hscquery->bindValue(1,"$new_password", PDO::PARAM_STR);
$hscquery->bindParam(2,$sessionUserid, PDO::PARAM_INT);
$hscquery -> execute();

    header("Location: ../private.php");
    die("Password Not Changed Please RE-Login.... ");
}

if($accesspoint11 == '1')
{
	$sql="update users set password=? where id =?";
	$hscquery = $db -> prepare($sql);
	$hscquery->bindValue(1,"$new_password", PDO::PARAM_STR);
	$hscquery->bindParam(2,$sessionUserid, PDO::PARAM_INT);
	$hscquery -> execute();

	$sts = $db->prepare("select * from his_password where userid =? ");
	$sts->bindParam(1, $sessionUserid, PDO::PARAM_STR);
	$sts->execute();
	while ($row = $sts->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		$pass11=$row['pass1'];
		$pass12=$row['pass2'];
		$pass13=$row['pass3'];
	}	
if($pass11 !='' and $pass12 !='')
{	
	$sql="update his_password set pass3=? where userid=?";
	$hscquery = $db -> prepare($sql);
	$hscquery->bindValue(1,"$new_password", PDO::PARAM_STR);
	$hscquery->bindParam(2,$sessionUserid, PDO::PARAM_INT);
	$hscquery -> execute();
}	
if($pass11 !='' and $pass12 !='' and $pass13 !='')
{
	$sql="update his_password set pass1=? where userid=?";
	$hscquery = $db -> prepare($sql);
	$ddss='3';
	$hscquery->bindValue(1,"$new_password", PDO::PARAM_STR);
	$hscquery->bindParam(2,$sessionUserid, PDO::PARAM_INT);
	$hscquery -> execute();
}
	
header("Location:change_pass.php?msg=3&ipq=$keyss");
die("Password Not Changed Please RE-Login.... ");
}
}
else{echo "Access Not Valid";}
?>
<?php } ?>
