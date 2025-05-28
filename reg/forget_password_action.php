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
header("Cache-Control=proxy-revalid");
date_default_timezone_set("Asia/Kolkata");
include("../includes/db_inc.php");//database connection

$page_access_time= date("Y/m/d h:i:s");
$at = $db->prepare("insert into audit_trial(user_id,ip_address,date_time,action_performed,status)
		values (?,?,?,?,?)");
$xx='Password  reset ';
$x='security True Access';
$at->bindParam(1, $_SESSION[user], PDO::PARAM_STR);
$at->bindParam(2, $_SERVER[REMOTE_ADDR], PDO::PARAM_STR);
$at->bindParam(3, $page_access_time, PDO::PARAM_STR);
$at->bindParam(4, $xx, PDO::PARAM_STR);
$at->bindParam(5, $x, PDO::PARAM_STR);
$at->execute();

$sessionUserType=htmlspecialchars($_SESSION['id']);
$stlu = $db->prepare("select main_id,schema_id,localadmin,location from users where id= ? ");
$stlu->bindParam(1, $sessionUserType, PDO::PARAM_STR);

$stlu->execute();
while ($row = $stlu->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
    $localadminzz=$row['localadmin'];
    $main_id=$row['main_id'];
    $locationq=$row['location'];
	$schema_id_db=$row['schema_id'];
}
if($main_id =='9999' and $localadminzz != '0') {
// At the top of the page we check to see whether the user is logged in or not
    if (empty($_SESSION['user']) and $_SESSION['location'] == '') {

        die("Redirecting to login.php");
    }
    if (($_SESSION['user']) == '') {
        echo "you Can't access this page";
    } else {

        if (empty($_POST)) {
            session_unset();session_destroy();
            header("Location: ../login.php?aa=108");
        }
        if (!empty($_POST)) {

            if ($_POST['form1'] != $_SESSION['form_token']) {
                echo 'Invalid form submission';
                die();
            }
            if ($_SESSION['form_token'] == '') {
                print "Invalid Access";
                session_unset();     // unset $_SESSION variable for the run-time
                session_destroy();
                echo " <script type='text/javascript'>if (top.location != self.location) top.location = '../login.php?aa=108' </script>";
                die();
            }
            if (empty($_POST['password'])) {
                die("Please enter a Reset Password.");

            }
            if (strlen($_POST['password']) < 7) {
                die("Minimum 8 characters OF Password...");

            }

            $passwordzz = htmlspecialchars(htmlentities($_POST['password']));
            $passwordzz1 = htmlspecialchars(htmlentities($_POST['old_password']));
            if ($passwordzz != $passwordzz1) {
                echo "</br></br><center><font color='red' size='4'><b>Password Not Match ....</b></font></center>";

                die();

            }
            $aazz = $_REQUEST['aazz'];
             $aazzq = base64_decode($aazz); 
			
			
			
			if($localadminzz==1) {
	
	$userids = $db->prepare("select schema_id,main_id,localadmin,location from users where id= ? ");
	$userids->bindParam(1, $aazzq, PDO::PARAM_STR);
	$userids->execute();
	while ($row = $userids->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		
		
		$userlocationq=$row['location'];
		$userschema_id_db=$row['schema_id'];

	}
	 if($locationq!=$userlocationq){
	session_unset();session_destroy();
    header("Location: ../login.php?aa=108");
    die('Location2 Not Matched');
	}
	if($schema_id_db!=$userschema_id_db){
	session_unset();session_destroy();
    header("Location: ../login.php?aa=108");
    die('Location Not Matched');
	} 
	
	}
			
            //$aaro = 1;
            $stl = $db->prepare("update users set password=? where id =? ");
            $stl->bindParam(1, $passwordzz, PDO::PARAM_STR);
            //stl->bindValue(2, "$aaro", PDO::PARAM_STR);
            $stl->bindParam(2, $aazzq, PDO::PARAM_STR);
            $stl->execute();

            $sqltt = "update his_password set pass1=?  where userid=?";
            $hscquerytt = $db->prepare($sqltt);
            $hscquerytt->bindValue(1, "$passwordzz", PDO::PARAM_STR);
            $hscquerytt->bindParam(2, $aazzq, PDO::PARAM_INT);
            $hscquerytt->execute();

            echo "</br></br><center><font color='red' size='4'><b>Password Reset Successfully</b></font></center>";

            unset($_SESSION['form_token']);
			unset($_POST);
			unset($_REQUEST);
            die();
        }
    }
}
?>