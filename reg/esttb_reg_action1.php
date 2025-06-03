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
'/'.$main_id=$_SESSION['main_id'];
'/'.$_SESSION['menuaccess_codeall'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
$level_level=$_SESSION['level_level'];
$dept=$_SESSION['dept'];
$leveladd=$_SESSION['level_level'];

$page_access_time= date("Y/m/d h:i:s");
$at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status)
		values (?,?,?,?,?)");
$xx='User creation';
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


if($_SESSION['menuaccess_codeall'] !='9' and $main_id =='9999' and ($localadmin =='1' OR $localadmin =='2'))
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
		echo "You are not Valid User..... please login again";
		header("Location: ../login.php");
		die();
	}
	if($locationq =='')
	{
		session_unset();     // unset $_SESSION variable for the run-time
		session_destroy();
		echo "You are not Valid User..... please login again";
		header("Location: ../login.php");
		die();
	}



 $reg_vaditate=htmlspecialchars($_REQUEST['ref_validate']);

if($_SESSION['reg_form'] != $reg_vaditate)
{
	print "Invalid Form Submit....";
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	$aazzs='108';
	header("Location: ../login.php?aa=$aazzs");
	die();
}
if($reg_vaditate =='')
{
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	print "Invalid Form Submit....";
	$aazzs='108';
	header("Location: ../login.php?aa=$aazzs");
	die();

}




$drtname=htmlspecialchars(htmlentities($_REQUEST['drtname']));

if (!is_numeric($drtname))
{
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	print "Invalid Form Entry .....";
	$aazzs='108';
	header("Location: ../login.php?aa=$aazzs");
	die();

}
if($drtname =='')
{
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	print "Invalid Form Entry .....";
	$aazzs='108';
	header("Location: ../login.php?aa=$aazzs");
	die();
}
if($drtname =='0')
{
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();
	print "Invalid Form Entry .....";
	$aazzs='108';
	header("Location: ../login.php?aa=$aazzs");
	die();
}

$useremail=htmlspecialchars(htmlentities($_REQUEST['useremail']));
list($uname,$domain) = explode("@",$useremail);
//$uname=htmlspecialchars(htmlentities(strtolower($_REQUEST['uname'])));








$fname=htmlspecialchars(htmlentities(strtoupper($_REQUEST['fname'])));

$mname=htmlspecialchars(htmlentities(strtoupper($_REQUEST['mname'])));

$lname=htmlspecialchars(htmlentities(strtoupper($_REQUEST['lname'])));

$est_id=htmlspecialchars(htmlentities($_REQUEST['est_id']));

$usermobile=htmlspecialchars(htmlentities($_REQUEST['usermobile']));
$city=htmlspecialchars(htmlentities($_REQUEST['city']));
$pin=htmlspecialchars(htmlentities($_REQUEST['pin']));



?>
</br></br>
<center><font color="red" size='5'><a href="create_user.php">BACK</a></font></center>
<?php

if ( preg_match('/[\'^£$#~?><>]/',$est_id)){
echo '<h2>Enter Correct Establishment code  </h2>';
die();
}


if($est_id !='')
  {
  	$est_id=htmlspecialchars_decode(html_entity_decode($est_id));
  	$sql_all12="select est_id from master_est_ecourt where est_id=?";
  	$sql_all=$db->prepare($sql_all12);
  	$sql_all->bindParam(1, $est_id, PDO::PARAM_STR);
  	$sql_all->execute();
  	$found_username = $sql_all->fetchColumn();
  	if($found_username !='' and $est_id !='')
	{
	echo '<h2>Establishment Already Exists ...!!!!! </h2>';
	die();
	}
  }


  if($useremail !='')
  {
  	$useremail=htmlspecialchars_decode(html_entity_decode($useremail));
  	$sql_all12="select est_id from master_est_ecourt where email=?";
  	$sql_all=$db->prepare($sql_all12);
  	$sql_all->bindParam(1, $useremail, PDO::PARAM_STR);
  	$sql_all->execute();
  	$found_username = $sql_all->fetchColumn();
  	if($found_username !='' and $useremail !='')
	{
	echo '<h2>Email Already Exists...!!!!! </h2>';
	die();
	}
  }




if(empty($fname))
{
	die("Please enter Establishment Name...");
}
if(!filter_var($useremail, FILTER_VALIDATE_EMAIL))
{
	die("Invalid E-Mail Address");
}



if($_SESSION['menuaccess_codeall'] =='9' and $main_id =='9999' and $localadmin =='2')
{
	//super Admin


	$stlu = $db->prepare("select count(*) from master_schema where schema_id= ? ");
	$stlu->bindParam(1, $drtname, PDO::PARAM_STR);
	$stlu->execute();
	$drtname_count = $stlu->fetchColumn();

	if($drtname_count =='0')
	{
		session_unset();     // unset $_SESSION variable for the run-time
		session_destroy();
		print "Invalid Form Entry .....";
		$aazzs='108';
		header("Location: ../login.php?aa=$aazzs");
		die();
	}
	$stlu = $db->prepare("select location_id from master_location where schema_id= ? ");
	$stlu->bindParam(1, $drtname, PDO::PARAM_STR);
	$stlu->execute();
	$location_id_admin = $stlu->fetchColumn();

	if($location_id_admin =='')
	{
		session_unset();     // unset $_SESSION variable for the run-time
		session_destroy();
		print "Invalid Form Entry .....";
		$aazzs='108';
		header("Location: ../login.php?aa=$aazzs");
		die();
	}
        $stlu = $db->prepare("select office_name from master_location where schema_id= ? ");
	$stlu->bindParam(1, $drtname, PDO::PARAM_STR);
	$stlu->execute();
	$location_id_admin = $stlu->fetchColumn();


	$salt_sat='@2016@';
	$address='';
	$level_level1='0';
	$main_id_print='9999';
	$admin_role=1;
	$accesspoint1=0;
	$menuaccess_codeall='9';
	$country='INDIA';
	$login_stat = 'Approved';

$mlname=$mname.' '.$lname;

try
	{


	$st = $db->prepare("insert into master_est_ecourt (email,code,est_name,address1,address2,est_id,city,pin,office_name)
			 values (?,?,?,?,?,?,?,?,?)");
	
	
	$st->bindParam(1, $useremail, PDO::PARAM_STR);
	$st->bindParam(2, $drtname, PDO::PARAM_STR);
	$st->bindParam(3, $fname, PDO::PARAM_STR);
	$st->bindParam(4, $mname, PDO::PARAM_STR);
        $st->bindParam(5, $lname, PDO::PARAM_STR);
	$st->bindParam(6, $est_id, PDO::PARAM_STR);
	$st->bindParam(7, $city, PDO::PARAM_STR);
        $st->bindParam(8, $pin, PDO::PARAM_STR);
        $st->bindParam(9, $location_id_admin, PDO::PARAM_STR);
	
	
	
	
	$st->execute();
}
	catch(PDOException $ex)
	{
		die("Failed to run query: " . $ex->getMessage());
	}

	$aazzs='117';
	header("Location: create_esttb.php?aap=$aazzs");
	die();


}



if($_SESSION['menuaccess_codeall'] =='9' and $main_id =='9999' and $localadmin =='1')
{
	//Admin

	$stlu = $db->prepare("select schema_id from users where id= ? ");
	$stlu->bindParam(1, $sessionUserType, PDO::PARAM_STR);

	$stlu->execute();
	while ($row = $stlu->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		$schema_idrun=$row['schema_id'];
	}

	if($drtname != $schema_idrun)
	{
		session_unset();     // unset $_SESSION variable for the run-time
		session_destroy();
		print "Invalid Form Entry .....";
		$aazzs='108';
		header("Location: ../login.php?aa=$aazzs");
		die();
	}
	$stlu = $db->prepare("select office_name from master_location where schema_id= ? ");
	$stlu->bindParam(1, $drtname, PDO::PARAM_STR);
	$stlu->execute();
	$location_id_admin = $stlu->fetchColumn();

	if($location_id_admin =='')
	{
		session_unset();     // unset $_SESSION variable for the run-time
		session_destroy();
		print "Invalid Form Entry .....";
		$aazzs='108';
		header("Location: ../login.php?aa=$aazzs");
		die();
	}

	$m_case_type=htmlspecialchars(htmlentities($_REQUEST['m_case_type']));
	if($m_case_type ==''){$m_case_type='0';}
	if (!is_numeric($m_case_type))
	{
		session_unset();     // unset $_SESSION variable for the run-time
		session_destroy();
		print "Invalid Form Entry .....";
		$aazzs='108';
		header("Location: ../login.php?aa=$aazzs");
		die();

	}

if($m_case_type =='2')
{
	$m_case_typex=htmlspecialchars(htmlentities($_REQUEST['m_case_typex']));
	if($m_case_typex ==''){$m_case_typex ='0';}

	if (!is_numeric($m_case_typex))
	{
		session_unset();     // unset $_SESSION variable for the run-time
		session_destroy();
		print "Invalid Form Entry .....";
		$aazzs='108';
		header("Location: ../login.php?aa=$aazzs");
		die();

	}
}

	//Create Users
	$country='INDIA';
	$main_id_print='9999';
	$admin_role=0;
	$accesspoint1=0;
	$salt_sat='@2016@';
	$address='';
	$login_stat = 'Approved';


	if($m_case_type ==''){$m_case_type='0';}
	if($m_case_typex ==''){$m_case_typex ='0';}

try
	{

$st = $db->prepare("insert into master_est_ecourt(email,code,est_name,address1,address2,est_id,city,pin,office_name)
			values (?,?,?,?,?,?,?,?,?)");
			//
	$st->bindParam(1, $useremail, PDO::PARAM_STR);
	$st->bindParam(2, $schema_idrun, PDO::PARAM_STR);
	$st->bindParam(3, $fname, PDO::PARAM_STR);
	$st->bindParam(4, $mname, PDO::PARAM_STR);
        $st->bindParam(5, $lname, PDO::PARAM_STR);
	$st->bindParam(6, $est_id, PDO::PARAM_STR);
	$st->bindParam(7, $city, PDO::PARAM_STR);
        $st->bindParam(8, $pin, PDO::PARAM_STR);
        $st->bindParam(9, $location_id_admin, PDO::PARAM_STR);

	$st->execute();
	}
	catch(PDOException $ex)
	{
		die("Failed to run query : " . $ex->getMessage());
	}

	$aazzs='117';
	header("Location: create_esttb.php?aap=$aazzs");
	die();


}

} //main cont. close

?>
