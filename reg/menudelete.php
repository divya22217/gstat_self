<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");
date_default_timezone_set("Asia/Kolkata");
include("../includes/db_inc.php");

$page_access_time= date("Y/m/d h:i:s");
$at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status)
		values (?,?,?,?,?)");
$xx='Menu Deleted / active';
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
else 
{
	
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
		
		
		
	
	
	
	$hashd=$_REQUEST['idaa'];
	if($hashd !='') {
		$msp = htmlspecialchars(htmlentities(base64_decode($hashd)));
		$code1 = explode("/", $msp);
		$user_id = htmlspecialchars(htmlentities($code1[0]));
		$id = htmlspecialchars(htmlentities($code1[1]));
		$tablename = htmlspecialchars(htmlentities($code1[2]));
		$display = htmlspecialchars(htmlentities($code1[3]));
		$user_msg = htmlspecialchars(htmlentities($code1[4]));
		$_token = htmlspecialchars(htmlentities($code1[5]));		
		$link_schema = htmlspecialchars(htmlentities($code1[6]));
		$place = htmlspecialchars(htmlentities($code1[7]));
		if($_token!=$_SESSION[reg_form_act]){
			session_unset();session_destroy();
			header("Location: ../login.php?aa=108");
			die('ghfgh');
		}
		
		if($localadminzz==1) {
			if ($locationq != $place) {
				session_unset();
				session_destroy();
				header("Location: ../login.php?aa=108");
				die('Location2 Not Matched');
			}
			if ($schema_id_db != $link_schema) {
				session_unset();
				session_destroy();
				header("Location: ../login.php?aa=108");
				die('Location Not Matched');
			}
		}
		
		
		$sql = "update $tablename set display=? where id=? ";
		$st213 = $db->prepare($sql);
		$st213->bindParam(1, $display, PDO::PARAM_STR);
		$st213->bindParam(2, $id , PDO::PARAM_STR);
		//$st213->execute();
	}

	//echo "<h2>$user_msg <h2>";
	//$msg11=$user_msg.'/'.$user_id;
	//$hashp=base64_encode($msg11);
	//header("Location:delete_menus.php?msggg=$hashp");
	if($display=='T'){
		$show = 'activate';
	}
	if($display=='F'){
		$show = 'disable';
	}
?>
<SCRIPT language=JavaScript>

function win(){
self.close();
}

</SCRIPT>
<form name="frrm" action="menudelete_action.php" method="post"> 
<input type="hidden" name="_tokenD" value="<?php echo $_SESSION[reg_tokenD] = $hashd;//sha1(uniqid(rand()));?>">

<label>Are You Sure !<br> You want to <?php echo $show;?> this permision for this User</label>

<br>

<input type="submit" name ="submit" value="Ok">
<input type="button" name ="cancel" value="Cancel" onClick="win();">
</form>

	
	<?php 
}
	


?>	
