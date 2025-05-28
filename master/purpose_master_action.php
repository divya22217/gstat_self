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


if( $_POST['token'] != $_SESSION['csrf'])
{
	echo $message = 'Invalid form submission';
}
else
{
	$schemas=htmlspecialchars($_SESSION['schema_name']);
	
	function all_schamas($db){
		$display = TRUE;
		$sch = $db->prepare("select schema_name from mater_location_city where display = ?");
		$sch->bindParam(1, $display, PDO::PARAM_BOOL);
		$sch->execute();
		$all_schemas = $sch->fetchAll();
		return $all_schemas;
	}


	$y=$_REQUEST['y'];
	$frmAction=$_REQUEST['frmAction'];
	$edit_id=htmlspecialchars($_REQUEST['edit_id']);

	$purpose_name =htmlspecialchars($_REQUEST['purpose_name']);
	$all_schemas= all_schamas($db);
	if($frmAction == "add")
	{
		foreach($all_schemas as $k=>$value){
			$schemas = $value['schema_name'];
			$max_d = $db->prepare("select max(purpose_code) from $schemas.master_purpose");
			$max_d->execute();
			$max_d = $max_d->fetchColumn()+1;
			try
			{
			$sql = "insert into $schemas.master_purpose(purpose_code,purpose_name,display,purpose_priority,weight)values(?,?,?,?,?)";
			$sthaqq = $db->prepare($sql);
				$param = array($max_d,$purpose_name,'TRUE',$max_d,$max_d);

			$sthaqq->execute($param);
			}
			catch(Execption $e)
			{
				echo "Failed:".$e->getMessage();
			}
		}
		$_SESSION[suss_message] = 'Purpose Added Successfully  !!!';
		header("Location:purpose_master.php");

	}


	if($frmAction == "modify")
	{
		foreach($all_schemas as $k=>$value){
			$schemas = $value['schema_name'];
		try
		{
			$sql=$db->prepare("update $schemas.master_purpose set purpose_name=? where purpose_code=?");
			$sql->execute(array($purpose_name,$edit_id));
			$_SESSION[suss_message] = 'Purpose Updated Successfully  !!!';
			header("Location:purpose_master.php?y_id=$edit_id");
		}
		catch(Execption $e)
		{
			echo "Failed:".$e->getMessage();
		}
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
