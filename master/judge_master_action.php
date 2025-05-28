<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");//database connection
 /* ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL); */ 
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
	function clean($judge_name) {
	   $judge_name = str_replace(' ', '', $judge_name); 
	   $judge_name = preg_replace('/[^A-Za-z0-9\-]/', '', $judge_name);
	   return strtolower(preg_replace('/-+/', '', $judge_name));
	}

	$userid=$_SESSION['id'];
	$schemas=htmlspecialchars($_SESSION['schema_name']);
	

	//print_r($_POST);
	//die();

	//$y=$_REQUEST['y'];
	$frmAction=$_REQUEST['frmAction'];
	$edit_id=htmlspecialchars($_REQUEST['edit_id']);
	$judeg_desg_code =htmlspecialchars($_REQUEST['judeg_desg_code']);
	$judge_name =htmlspecialchars($_REQUEST['judge_name']);
	$gen =htmlspecialchars($_REQUEST['gen']);
	$hon_text =htmlspecialchars($_REQUEST['hon_text']);
	if($frmAction == "add")
	{		
		if( $_POST['token'] != $_SESSION['csrf'])
		{
			echo $message = 'Invalid form submission';
			die;
		}
			$max_d = $db->prepare("select max(judge_code) from $schemas.master_judge");
			$max_d->execute();
			$max_d = $max_d->fetchColumn()+1;
			try
			{
			$sql = "insert into $schemas.master_judge(judge_code,judge_name,display,judge_desg_code,created_by,hon_text,gen)values(?,?,?,?,?,?,?)";
			$sthaqq = $db->prepare($sql);
				$param = array($max_d,$judge_name,'TRUE',$judeg_desg_code,$userid,$hon_text,$gen);

			$sthaqq->execute($param);
			}
			catch(Execption $e)
			{
				echo "Failed:".$e->getMessage();
			}	
		//$_SESSION[suss_message] = 'Judge Added Successfully  !!!';
		//header("Location:judge_master.php");

	}


	if($frmAction == "modify")
	{
			try
			{
			if( $_POST['token'] != $_SESSION['csrf'])
			{
				echo $message = 'Invalid form submission';
				die;
			}
				$log_type = 'U';
			$query = "insert into $schemas.master_judge_logs (judge_code,judge_name,judge_desg_code,display,from_date,to_date,id_ser_c,gen,seniority,hon_text,entry_date,created_by,log_type,user_id)
			select judge_code,judge_name,judge_desg_code,display,from_date,to_date,id_ser_c,gen,seniority,hon_text,entry_date,created_by,?,? from $schemas.master_judge where judge_code = ?;";
			$sql=$db->prepare($query);
			$sql->execute(array($log_type,$userid,$edit_id));

				$sql=$db->prepare("update $schemas.master_judge set judge_desg_code=?,judge_name=?,gen=?,hon_text=? where judge_code=?");
				$sql->execute(array($judeg_desg_code,$judge_name,$gen,$hon_text,$edit_id));
				//$_SESSION[suss_message] = 'Judge Updated Successfully  !!!';
				//header("Location:judge_master.php?y_id=$edit_id");
			}
			catch(Execption $e)
			{
				echo "Failed:".$e->getMessage();
			}
	}

	if($frmAction == "show_hide")
	{
		try
		{
			
			if( $_POST['token'] != $_SESSION['csrf'])
			{
				echo $message = 'Invalid form submission';
				die;
			}
			$display = $_REQUEST['display'];
			if($display =='No'){
				$set_display = 1;
			}
			if($display =='Yes'){
				$set_display = 0;
			}

			$log_type = 'SH';
			$query = "insert into $schemas.master_judge_logs (judge_code,judge_name,judge_desg_code,display,from_date,to_date,id_ser_c,gen,seniority,hon_text,entry_date,created_by,log_type,user_id)
			select judge_code,judge_name,judge_desg_code,display,from_date,to_date,id_ser_c,gen,seniority,hon_text,entry_date,created_by,?,? from $schemas.master_judge where judge_code = ?;";
			$sql=$db->prepare($query);
			$sql->execute(array($log_type,$userid,$edit_id));
			$sql=$db->prepare("update $schemas.master_judge set display = ?  where judge_code = ?");
			$sql->execute(array($set_display,$edit_id));
			//$msg = "Record updated Successfully";
			//header("Location:judge_master.php?y=show_hide&msg=$msg");
		}
		catch(Execption $e)
		{
			echo "Failed:".$e->getMessage();
		}
	}

	if($frmAction == "delete")
	{
		try
		{
			if( $_POST['token'] != $_SESSION['csrf'])
			{
				echo $message = 'Invalid form submission';
				die;
			}
			$log_type = 'D';
			$query = "insert into $schemas.master_judge_logs (judge_code,judge_name,judge_desg_code,display,from_date,to_date,id_ser_c,gen,seniority,hon_text,entry_date,created_by,log_type,user_id)
			select judge_code,judge_name,judge_desg_code,display,from_date,to_date,id_ser_c,gen,seniority,hon_text,entry_date,created_by,?,? from $schemas.master_judge where judge_code = ?;";
			$sql=$db->prepare($query);
			$sql->execute(array($log_type,$userid,$edit_id));

			$sql=$db->prepare("delete from $schemas.master_judge where judge_code = ?");
			$sql->execute(array($edit_id));
			$msg = "judge deleted";
			//header("Location:judge_master.php?y=delete&msg=$msg");
			
		}
		catch(Execption $e)
		{
			echo "Failed:".$e->getMessage();
		}
	}

	if($frmAction == "check_judge_name")
	{
		try
		{
			
			$judge_name = $_POST['judge_name'];
			$judge_name = clean($judge_name);
			$judge_desg = $_POST['judeg_desg_code'];
			
			$query = "select * from $schemas.master_judge where judge_desg_code = ? and lower(regexp_replace(judge_name, '[^a-zA-Z0-9]+', '','g')) like '%$judge_name%'";
			

			$sql=$db->prepare($query);
			$sql->bindParam(1, $judge_desg, PDO::PARAM_STR);
			$sql->execute();
			$data = $sql->fetchAll();
            
			if(!empty($data)){
				echo '0';
				die;
			}else{
			echo '1';
			die;
			}
			die;
			
		}
		catch(Execption $e)
		{
			echo "Failed:".$e->getMessage();
		}
	}

	?>
<?php } ?>
