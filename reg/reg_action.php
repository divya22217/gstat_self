<?php
session_start();
$localadmin=$_SESSION['localadmin'];
'/'.$main_id=$_SESSION['main_id'];
'/'.$_SESSION['menuaccess_codeall'];
 $schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
$level_level=$_SESSION['level_level'];
$dept=$_SESSION['dept'];
$leveladd=$_SESSION['level_level'];
$sessionUserType=htmlspecialchars($_SESSION['id']);
include("../db_inc1.php");//database connection
echo $useremail=htmlspecialchars(htmlentities($_REQUEST['useremail']));
list($uname,$domain) = explode("@",$useremail);
//$uname=htmlspecialchars(htmlentities(strtolower($_REQUEST['uname'])));

$userpassword=htmlspecialchars(htmlentities($_REQUEST['userpassword']));

$userpassword1=htmlspecialchars(htmlentities($_REQUEST['userpassword1']));


$father_name=htmlspecialchars(htmlentities($_REQUEST['father_name']));
$nationality=htmlspecialchars(htmlentities($_REQUEST['nationality']));
$religion=htmlspecialchars(htmlentities($_REQUEST['religion']));
$gender=htmlspecialchars(htmlentities($_REQUEST['gender']));

$dt_of_appoint=htmlspecialchars(htmlentities($_REQUEST['dt_of_appoint']));
$dt_o_r=htmlspecialchars(htmlentities($_REQUEST['dt_o_r']));
$year_of_allotment=htmlspecialchars(htmlentities($_REQUEST['year_of_allotment']));
$type_of_appoint=htmlspecialchars(htmlentities($_REQUEST['type_of_appoint']));



$fname=htmlspecialchars(htmlentities(strtoupper($_REQUEST['fname'])));

$mname=htmlspecialchars(htmlentities(strtoupper($_REQUEST['mname'])));

$lname=htmlspecialchars(htmlentities(strtoupper($_REQUEST['lname'])));

echo $employee_code=htmlspecialchars(htmlentities($_REQUEST['employee_code']));

$usermobile=htmlspecialchars(htmlentities($_REQUEST['usermobile']));


?>
</br></br>
<center><font color="red" size='5'><a href="create_user.php">BACK</a></font></center>
<?php

/* if ( preg_match('/[\'^£$#~?><>]/',$employee_code)){
echo '<h2>Enter Correct Employee code  </h2>';
die();
} */


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

if($_SESSION['menuaccess_codeall'] =='9' and $main_id =='9999' and $localadmin =='1')
{
	//super Admin
	

	$stlu = $db->prepare("select count(*) from mater_location_city where schema_id= ? ");
	$stlu->bindParam(1, $drtname, PDO::PARAM_STR);
	$stlu->execute();
	echo $drtname_count = $stlu->fetchColumn();

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
			address,gender,pwd_hash,main_id,localadmin,accesspoint1,menuaccess_codeall,level_level,schema_id,
			dt_of_appoint,login_status)
			 values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
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
	//$st->bindParam(16, $location_id_admin, PDO::PARAM_STR);
	$st->bindParam(17, $drtname, PDO::PARAM_STR);
	$st->bindParam(18, $dt_of_appoint, PDO::PARAM_STR);
	$st->bindParam(19, $login_stat, PDO::PARAM_STR);
		


	$st->execute();
}
	catch(PDOException $ex)
	{
		die("Failed to run query: " . $ex->getMessage());
	}

	$aazzs='117';
	header("Location: create_user.php?aap=$aazzs");
	die();


}



if($_SESSION['menuaccess_codeall'] =='1' and $main_id =='9999' and $localadmin =='1')
{
	//Admin
	echo "zvxcvzxcv1211212121";
	echo $stlu ="select schema_id from users where id='$sessionUserType'";
	$stlu = $db->prepare("select schema_id from users where id= ? ");
	$stlu->bindParam(1, $sessionUserType, PDO::PARAM_STR);

	$stlu->execute();
	while ($row = $stlu->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		echo $schema_idrun=$row['schema_id'];
	}

	/* if($drtname != $schema_idrun)
	{
		session_unset();     // unset $_SESSION variable for the run-time
		session_destroy();
		print "Invalid Form Entry .....";
		$aazzs='108';
		header("Location: ../login.php?aa=$aazzs");
		die();
	} */
/* 	$stlu = $db->prepare("select location_id from mater_location_city where city_id=? "); */
	$stlu->bindParam(1, $schema_idrun, PDO::PARAM_STR);
	$stlu->execute();
	echo $location_id_admin = $stlu->fetchColumn();

	if($location_id_admin =='')
	{
		session_unset();     // unset $_SESSION variable for the run-time
		session_destroy();
		print "Invalid Form Entry .....";
		$aazzs='108';
		header("Location: ../login.php?aa=$aazzs");
		die();
	}

	/* $m_case_type=htmlspecialchars(htmlentities($_REQUEST['m_case_type']));
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
 */
/* if($m_case_type =='2')
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
} */

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

	$st = $db->prepare("insert into users (username,password,salt,email,location,fname,lname,country,
			address,gender,pwd_hash,main_id,localadmin,accesspoint1,menuaccess_codeall,level_level,schema_id,
			dt_of_appoint,login_status)
			 values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
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
	//$st->bindParam(16, $location_id_admin, PDO::PARAM_STR);
	$st->bindParam(17, $drtname, PDO::PARAM_STR);
	$st->bindParam(18, $dt_of_appoint, PDO::PARAM_STR);
	$st->bindParam(19, $login_stat, PDO::PARAM_STR);
		

	$st->execute();
	}
	catch(PDOException $ex)
	{
		die("Failed to run query : " . $ex->getMessage());
	}

	$aazzs='117';
	header("Location: create_user.php?aap=$aazzs");
	die();


}

//main cont. close

?>
