<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");//database connection
ob_start();
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{

	die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
	echo "you Can't access this page";
}


if( $_POST['frm'] != $_SESSION['form_token'])
{
	echo $message = 'Invalid form submission';
}
else
{
print_r($_POST);
//die();

$y=$_REQUEST['y'];
$frmAction=$_REQUEST['frmAction'];
$bench_id=htmlspecialchars($_REQUEST['bench_id']);
	$edit_id=htmlspecialchars($_REQUEST['edit_id']);
$objection =htmlspecialchars($_REQUEST['objection']);
if($frmAction == "add")
{

$max_d = $db->prepare("select max(id) from check_list_local");
$max_d->execute();
$max_d = $max_d->fetchColumn()+1;
try
{
$sql = "insert into check_list_local (id,check_list,location_all)values(?,?,?)";
$sthaqq = $db->prepare($sql);
$param = array($max_d,$objection,$bench_id);

$sthaqq->execute($param);
}
catch(Execption $e)
{
echo "Failed:".$e->getMessage();
}
//echo $msg = "objection Added Successfully  !!!";
$_SESSION[suss_message] = 'objection Added Successfully  !!!';
header("Location:scrutiny_master.php");

}


	if($frmAction == "modify")
	{
		try
		{



			$sql=$db->prepare("update check_list_local set check_list=?,location_all =? where id=?");

			$sql->execute(array($objection,$bench_id,$edit_id));
			$_SESSION[suss_message] = 'objection Updated Successfully  !!!';
			header("Location:scrutiny_master.php?y_id=$edit_id");
		}
		catch(Execption $e)
		{
			echo "Failed:".$e->getMessage();
		}
	}

//	if($frmAction == "delete")
//	{
//		try
//		{
//			$display = $_REQUEST['display'];
//			if($display =='false'){
//				$m = 'Deleted';
//			}
//			if($display =='true'){
//				$m = 'UNDeleted';
//			}
//			$sql=$db->prepare("update $schemas.master_officer set display = ?  where id = ?");
//			$sql->execute(array($display,$judge_code));
//			$msg = "Record $m Successfully";
//			header("Location:judge_master.php?y=delete&msg=$msg");
//		}
//		catch(Execption $e)
//		{
//			echo "Failed:".$e->getMessage();
//		}
//	}

	?>
	<html>
	<head><title></title>
	<body>
	</body>
	</html>
<?php } ?>
