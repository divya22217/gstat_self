<?php

header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");

date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");//database connection

$page_access_time= date("Y/m/d h:i:s");
$at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status)
		values (?,?,?,?,?)");
$xx='Menu Assign';
$x='security True Access';
$at->bindParam(1, $_SESSION[user], PDO::PARAM_STR);
$at->bindParam(2, $_SERVER[REMOTE_ADDR], PDO::PARAM_STR);
$at->bindParam(3, $page_access_time, PDO::PARAM_STR);
$at->bindParam(4, $xx, PDO::PARAM_STR);
$at->bindParam(5, $x, PDO::PARAM_STR);
$at->execute();

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
	die();
}
 if( $_POST['frm'] != $_SESSION['csrf'])
{
	echo 'Invalid form submission';
die();
}

else
{
	

	$stlu = $db->prepare("select schema_id,main_id,localadmin,location from users_cis where id= ? ");
	$stlu->bindParam(1, $_SESSION[id], PDO::PARAM_STR);
	$stlu->execute();
	$row = $stlu->fetch();
	$localadminzz=$row['localadmin'];
	$main_id=$row['main_id'];
	$locationq=$row['location'];
	$schema_id_db=$row['schema_id'];
		
		
		
		

//$key=$_SESSION['side'];
 	$userid_back=htmlspecialchars(htmlentities($_REQUEST['user_type']));

	if (!is_numeric($userid_back))
	{
		print "Invalid Access";
		session_unset();     // unset $_SESSION variable for the run-time
		session_destroy();
		//echo " <script type='text/javascript'>if (top.location != self.location) top.location = '../login.php?aa=108' </script>";
		header("Location: ../login.php?aa=108");
		die();

	}
	
	
	/*check wheather use is that schema */
	
	$stlu = $db->prepare("select schema_id,main_id,localadmin,location from users_cis where id= ? ");
	$stlu->bindParam(1, $userid_back, PDO::PARAM_STR);
	$stlu->execute();
	$row = $stlu->fetch();
	$userpermision=$row['localadmin'];
	$usermain_id=$row['main_id'];
	$userlocationq=$row['location'];
	$userschema_id_db=$row['schema_id'];	
	

	if($schema_id_db !=$userschema_id_db){
		print "Invalid Access";
		session_unset(); session_destroy();
		header("Location: ../login.php?aa=108");
		die();
	}
	if($locationq !=$userlocationq){
		print "Invalid Access";
		session_unset(); session_destroy();
		header("Location: ../login.php?aa=108");
		die();
	}
	
		
	
	
	
 	$menu_t1=htmlspecialchars(htmlentities($_REQUEST['menu']));

if (!is_numeric($menu_t1))
	{
		print "Invalid Access";
		session_unset();     // unset $_SESSION variable for the run-time
		session_destroy();
		echo " <script type='text/javascript'>if (top.location != self.location) top.location = '../login.php?aa=108' </script>";
		die();

	}

	$ordernomenu='1';
	$st1a1m = $db->prepare("select id from links where  id=? and order_no=? ");
	$st1a1m->bindParam(1, $menu_t1, PDO::PARAM_STR);
	$st1a1m->bindParam(2, $ordernomenu, PDO::PARAM_STR);
	$st1a1m->execute();
	$foundmenu=$st1a1m->fetchColumn();
	
	
	if ($foundmenu =='')
	{
		print "Invalid Access";
		session_unset();     // unset $_SESSION variable for the run-time
		session_destroy();
		echo " <script type='text/javascript'>if (top.location != self.location) top.location = '../login.php?aa=108' </script>";
		die();

	}
	

 	$st1a = $db->prepare("select * from links where  id=? ");
	$st1a->bindParam(1, $menu_t1, PDO::PARAM_STR);
	$st1a->execute();
	
	while ($row = $st1a->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		$tt1=htmlspecialchars($row['type1']);
		$tt2=htmlspecialchars($row['type2']);
		$tt3=htmlspecialchars($row['type3']);
		$namet1=htmlspecialchars($row['name']);
		$serialst1=htmlspecialchars($row['serials']);
	
	}

	$st1a1y = $db->prepare("select userid from menu where userid=? ");
	$st1a1y->bindParam(1, $userid_back, PDO::PARAM_STR);
	$st1a1y->execute();
	$user_id=$st1a1y->fetchColumn();
	

if($user_id !='')
{
	$st1a1 = $db->prepare("select name from menu where userid=? and type1=? and type2=? and type3 =? ");
	$st1a1->bindParam(1, $user_id, PDO::PARAM_STR);
	$st1a1->bindParam(2, $tt1, PDO::PARAM_STR);
	$st1a1->bindParam(3, $tt2, PDO::PARAM_STR);
	$st1a1->bindParam(4, $tt3, PDO::PARAM_STR);
	//$st1a->bindParam(5, $serialst1, PDO::PARAM_STR);
	$st1a1->execute();
	$account_filing=$st1a1->fetchColumn();
}

	if($account_filing !='')
	{
		$message = 'Menu  All Ready Added';
				
		$msg1=$message;
		$hash=base64_encode($msg1);
		
		
		unset( $_SESSION['form_token'] );
		
		// if all is done, say thanks
		
		$url = "assign_menu.php?hash=$hash&menu=$key";
		
		// Validate url
		if (!headers_sent())
		{
			header("Location:$url");
		}
		if (headers_sent())
		{
			echo "URl Not Authorization";
		}
		die();
		
	}
		
	
	if($account_filing =='')
	{
		$st1ad = $db->prepare("select * from links where id=? ");
		//$st1ad->bindParam(1, $userid_back, PDO::PARAM_STR);
		$st1ad->bindParam(1, $menu_t1, PDO::PARAM_STR);
		
		$st1ad->execute();
		while ($row = $st1ad->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
			$tt1d=htmlspecialchars($row['type1']);
			$tt2d=htmlspecialchars($row['type2']);
			$tt3d=htmlspecialchars($row['type3']);
			$namet1d=htmlspecialchars($row['name']);
			$serialst1d=htmlspecialchars($row['serials']);
			$url1=htmlspecialchars_decode(html_entity_decode($row['value']));
		
		}
		$display_t1='T';

		$sd = $db->prepare("insert into menu (levelid,type1,type2,type3,name,display,userid,serials,value)
				 values (?,?,?,?,?,?,?,?,?)");
		$sd->bindParam(1, $userid_back, PDO::PARAM_STR);
		$sd->bindParam(2, $tt1d, PDO::PARAM_STR);
		$sd->bindParam(3, $tt2d, PDO::PARAM_STR);
		$sd->bindParam(4, $tt3d, PDO::PARAM_STR);
		$sd->bindParam(5, $namet1d, PDO::PARAM_STR);
		$sd->bindParam(6, $display_t1, PDO::PARAM_STR);
		$sd->bindParam(7, $userid_back, PDO::PARAM_STR);
		$sd->bindParam(8, $serialst1d, PDO::PARAM_STR);
		$sd->bindParam(9, $url1, PDO::PARAM_STR);
		$sd->execute();
		if($tt1d > 0)
		{
		$sspl='0';
		$ssplt3='0';
		$order_no1='2';
		$st21 = $db->prepare("select * from links where type1=? and type2 !=? and type3 =? and order_no =? ");
		$st21->bindParam(1, $tt1d, PDO::PARAM_STR);
		$st21->bindParam(2, $sspl, PDO::PARAM_STR);
		$st21->bindParam(3, $ssplt3, PDO::PARAM_STR);
		$st21->bindParam(4, $order_no1, PDO::PARAM_STR);
		$st21->execute();
		while ($row = $st21->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
		{
			$tt1d2=htmlspecialchars($row['type1']);
			$tt2d2=htmlspecialchars($row['type2']);
			$tt3d2=htmlspecialchars($row['type3']);
			$namet1d2=htmlspecialchars($row['name']);
			$url2=htmlspecialchars_decode(html_entity_decode($row['value']));
			
		
		$display_t2='T';
		
		$sd1 = $db->prepare("insert into sub_menu (levelid,type1,type2,type3,sub_name,display,userid,value)
				 values (?,?,?,?,?,?,?,?)");
		$sd1->bindParam(1, $userid_back, PDO::PARAM_STR);
		$sd1->bindParam(2, $tt1d2, PDO::PARAM_STR);
		$sd1->bindParam(3, $tt2d2, PDO::PARAM_STR);
		$sd1->bindParam(4, $tt3d2, PDO::PARAM_STR);
		$sd1->bindParam(5, $namet1d2, PDO::PARAM_STR);
		$sd1->bindParam(6, $display_t2, PDO::PARAM_STR);
		$sd1->bindParam(7, $userid_back, PDO::PARAM_STR);
		$sd1->bindParam(8, $url2, PDO::PARAM_STR);
		$sd1->execute();
		
			if($tt2d2 > 0)
			{
			$ssplt33='0';
			$order_no='3';
						
			$st213 = $db->prepare("select * from links where type1 =? and type2 =? and type3 !=? and order_no =? ");
			$st213->bindParam(1, $tt1d2, PDO::PARAM_STR);
			$st213->bindParam(2, $tt2d2, PDO::PARAM_STR);
			$st213->bindParam(3, $ssplt33, PDO::PARAM_STR);
			$st213->bindParam(4, $order_no, PDO::PARAM_STR);
			$st213->execute();
			while ($row = $st213->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
			{
				$tt1d23=htmlspecialchars($row['type1']);
				$tt2d23=htmlspecialchars($row['type2']);
				$tt3d23=htmlspecialchars($row['type3']);
				$namet1d23=htmlspecialchars($row['name']);
				$value34=htmlspecialchars_decode(html_entity_decode($row['value']));
				;
					
		
				$display_t23='T';
 
				$sd13 = $db->prepare("insert into sub_sub_menu (value,type1,type2,type3,sub_sub_name,display,userid)
				 values (?,?,?,?,?,?,?)");
				$sd13->bindParam(1, $value34, PDO::PARAM_STR);
				$sd13->bindParam(2, $tt1d23, PDO::PARAM_STR);
				$sd13->bindParam(3, $tt2d23, PDO::PARAM_STR);
				$sd13->bindParam(4, $tt3d23, PDO::PARAM_STR);
				$sd13->bindParam(5, $namet1d23, PDO::PARAM_STR);
				$sd13->bindParam(6, $display_t23, PDO::PARAM_STR);
				$sd13->bindParam(7, $userid_back, PDO::PARAM_STR);
				$sd13->execute();
			}
			}
		}
		}
		

		/*set access point 1 and login status approved*/
		$st = "update users_cis set login_status=? where id=?";
		$st=$db->prepare($st);
		$st->execute(array('Approved',$userid_back));


		$message = 'Menu Add Successfully...';
		$msg1=$message;
		$hash=base64_encode($msg1);
		unset( $_SESSION['form_token'] );
		// if all is done, say thanks
		$url = "assign_menu.php?hash=$hash&menu=$key";
		// Validate url
		if (!headers_sent())
		{
			header("Location:$url");
		}
		if (headers_sent())
		{
			echo "URl Not Authorization";
		}






	}
	
} //main else close
