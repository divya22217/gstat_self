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

/*page access */
$page_access_time= date("Y/m/d h:i:s");
$at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status)
		values (?,?,?,?,?)");
$xx='User Modified';
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
		session_unset();   session_destroy();
		echo "You are not Valied User..... please login again";
		header("Location: ../login.php");
		die();
	}
	if($locationq =='')
	{
		session_unset();  	session_destroy();
		echo "You are not Valied User..... please login again";
		header("Location: ../login.php");
		die();
	}

	
	
	print_r($_POST);
	die('sdfffds');

/*csrf validation*/
$csrf_token=htmlspecialchars($_REQUEST['csrf_token']);
if($csrf_token =='')
{
	session_unset();    session_destroy();
	print "Invalid Form Submit....";
	$aazzs='108';
	header("Location: ../login.php?aa=$aazzs");
	die();

}
if($_SESSION['reg_form'] != $csrf_token)
{
	print "Invalid Form Submit....";
	session_unset();   session_destroy();
	$aazzs='108';
	header("Location: ../login.php?aa=$aazzs");
	die();
}




/*check office name */
$drtname=htmlspecialchars(htmlentities($_REQUEST['drtname']));
if (!is_numeric($drtname))
{
	session_unset();    session_destroy();
	print "Invalid Form Entry .....";
	$aazzs='108';
	header("Location: ../login.php?aa=$aazzs");
	die();

}
if($drtname =='')
{
	session_unset();    session_destroy();
	print "Invalid Form Entry .....";
	$aazzs='108';
	header("Location: ../login.php?aa=$aazzs");
	die();
}
if($drtname =='0')
{
	session_unset();  session_destroy();
	print "Invalid Form Entry .....";
	$aazzs='108';
	header("Location: ../login.php?aa=$aazzs");
	die();
}






$useremail=htmlspecialchars(htmlentities($_REQUEST['useremail']));
list($uname,$domain) = explode("@",$useremail);


$userpassword=htmlspecialchars(htmlentities($_REQUEST['userpassword']));

$userpassword1=htmlspecialchars(htmlentities($_REQUEST['userpassword1']));


$father_name=htmlspecialchars(htmlentities($_REQUEST['father_name']));
$nationality=htmlspecialchars(htmlentities($_REQUEST['nationality']));
$religion=htmlspecialchars(htmlentities($_REQUEST['religion']));
$gender=htmlspecialchars(htmlentities($_REQUEST['gender']));
$designation=htmlspecialchars(htmlentities($_REQUEST['designation']));
$dt_of_appoint=htmlspecialchars(htmlentities($_REQUEST['dt_of_appoint']));
$dt_o_r=htmlspecialchars(htmlentities($_REQUEST['dt_o_r']));
$year_of_allotment=htmlspecialchars(htmlentities($_REQUEST['year_of_allotment']));
$type_of_appoint=htmlspecialchars(htmlentities($_REQUEST['type_of_appoint']));



$fname=htmlspecialchars(htmlentities(strtoupper($_REQUEST['fname'])));
$mname=htmlspecialchars(htmlentities(strtoupper($_REQUEST['mname'])));
$lname=htmlspecialchars(htmlentities(strtoupper($_REQUEST['lname'])));

$employee_code=htmlspecialchars(htmlentities($_REQUEST['employee_code']));
$usermobile=htmlspecialchars(htmlentities($_REQUEST['usermobile']));

$employee_code_check=htmlspecialchars(htmlentities($_REQUEST['employee_code_check']));
$check_email=htmlspecialchars(htmlentities($_REQUEST['check_email']));
?>
</br></br>
<center><font color="red" size='5'><a href="modify_user.php">BACK</a></font></center>
<?php
if($employee_code_check!='' and !isset($check_email))
if ( preg_match('/[\'^£$#~?><>]/',$employee_code_check)){
echo '<h2>Enter Correct Employee code  </h2>';
die();
}
if(!isset($check_email) and $check_email!='')
if ( preg_match('/[\'^£$#~?><>]/',$check_email)){
echo '<h2>Enter Correct Employee code  </h2>';
die();
}

die();
if($employee_code !='')
  {
  	$employee_code=htmlspecialchars_decode(html_entity_decode($employee_code));
  	$sql_all12="select username from users where employee_code=?";
  	$sql_all=$db->prepare($sql_all12);
  	$sql_all->bindParam(1, $employee_code, PDO::PARAM_STR);
  	$sql_all->execute();
  	$found_username = $sql_all->fetchColumn();
  	if($found_username !='' and $employee_code !='')
	{
	echo '<h2>USER NAME FOUND ... PLEASE RE-ENTER USER NAME !!!!! </h2>';
	die();
	}
  }


  if($useremail !='')
  {
  	$useremail=htmlspecialchars_decode(html_entity_decode($useremail));
  	$sql_all12="select username from users where email=?";
  	$sql_all=$db->prepare($sql_all12);
  	$sql_all->bindParam(1, $useremail, PDO::PARAM_STR);
  	$sql_all->execute();
  	$found_username = $sql_all->fetchColumn();
  	if($found_username !='' and $useremail !='')
	{
	echo '<h2>USER NAME FOUND ... PLEASE RE-ENTER USER NAME !!!!! </h2>';
	die();
	}
  }


if(strlen($uname) < '4' )

{

	die("Minimum 5 characters OF USER NAME!");

}
if(strlen($userpassword) < '7' )
{
	die("Minimum 8 characters OF PASSWORD!");

}
if(empty($fname))
{
	die("Please enter First Name...");
}
if(!filter_var($useremail, FILTER_VALIDATE_EMAIL))
{
	die("Invalid E-Mail Address");
}

if($userpassword != $userpassword1)
{
	die("PASSWORD NOT MATCH ...!");

}

if($_SESSION['menuaccess_codeall'] =='9' and $main_id =='9999' and $localadmin =='2')
{

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


$st = $db->prepare("insert into users (username,password,salt,email,location,fname,lname,country,
		address,gender,pwd_hash,main_id,localadmin,accesspoint1,menuaccess_codeall,level_level,dept,schema_id,
		father_name,nationality,religion,designation,dt_of_appoint,dt_o_r,year_of_allotment,appoint_type,employee_code,login_status)
		 values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
$st->bindParam(1, $uname, PDO::PARAM_STR);
$st->bindParam(2, $userpassword, PDO::PARAM_STR);
$st->bindParam(3, $salt_sat, PDO::PARAM_STR);
$st->bindParam(4, $useremail, PDO::PARAM_STR);
$st->bindParam(5, $location_id_admin, PDO::PARAM_STR);
$st->bindParam(6, $fname, PDO::PARAM_STR);
$st->bindParam(7, $mlname, PDO::PARAM_STR);
$st->bindParam(8, $country, PDO::PARAM_STR);
$st->bindParam(9, $address, PDO::PARAM_STR);
$st->bindParam(10, $gender, PDO::PARAM_STR);
$st->bindParam(11, $salt_sat, PDO::PARAM_STR);
$st->bindParam(12, $main_id_print, PDO::PARAM_STR);
$st->bindParam(13, $admin_role, PDO::PARAM_STR);
$st->bindParam(14, $accesspoint1, PDO::PARAM_STR);
$st->bindParam(15, $menuaccess_codeall, PDO::PARAM_STR);
$st->bindParam(16, $level_level1, PDO::PARAM_STR);
$st->bindParam(17, $location_id_admin, PDO::PARAM_STR);
$st->bindParam(18, $drtname, PDO::PARAM_STR);
$st->bindParam(19, $father_name, PDO::PARAM_STR);
$st->bindParam(20, $nationality, PDO::PARAM_STR);
$st->bindParam(21, $religion, PDO::PARAM_STR);
$st->bindParam(22, $designation, PDO::PARAM_STR);
$st->bindParam(23, $dt_of_appoint, PDO::PARAM_STR);
$st->bindParam(24, $dt_o_r, PDO::PARAM_STR);
$st->bindParam(25, $year_of_allotment, PDO::PARAM_STR);
$st->bindParam(26, $type_of_appoint, PDO::PARAM_STR);
$st->bindParam(27, $employee_code, PDO::PARAM_STR);
$st->bindParam(28, $login_stat, PDO::PARAM_STR);
$st->execute();
}
catch(PDOException $ex)
{
	die("Failed to run query: " . $ex->getMessage());
}
$aazzs='117';
header("Location: modify_user.php?aap=$aazzs");
die();
}
} //main cont. close

?>
