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
include("../db_inc1.php");
include '../db_inc2.php';
session_start();
$_SESSION['user'];
$_SESSION['location'];
$leveladd=$_SESSION['level_level'];
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
echo "Access Problem....."; 
session_unset();     // unset $_SESSION variable for the run-time
session_destroy();
header("Location: ../login.php?aa=100");
die();
}
setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);
$_SESSION['csrf'] = md5(uniqid(rand(), TRUE));
$key=$_SESSION['csrf'];
// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
	die("#2E2E2Eirecting to login.php");
}
if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
// This code not use next time .......	Schema session create Hear....
$sessionUserType=htmlspecialchars($_SESSION['id']);
$curYear = htmlspecialchars(date("Y"));
$curMonth = htmlspecialchars(date("m"));
$curDay = htmlspecialchars(date("d"));
$cur_date = "$curYear-$curMonth-$curDay";
$cur_date1 ="$curDay/$curMonth/$curYear";
$link_scrutiny_idaccess='1';
$schemas=htmlspecialchars($_SESSION['schema_name']);
$entry_date = htmlspecialchars(date("F j, Y g:i a"));
$filing_no = htmlspecialchars($_REQUEST['filing_no']);


 $ll='1';

$st13 =$db->prepare("update $schemas.scrutiny set level_level=? where filing_no=?");
$st13->bindParam(1, $ll, PDO::PARAM_STR);
$st13->bindParam(2, $filing_no, PDO::PARAM_STR);

$st13->execute();

$st1 =$db->prepare("update $schemas.objection_details set level_level=? where filing_no=?");
$st1->bindParam(1, $ll, PDO::PARAM_STR);
$st1->bindParam(2, $filing_no, PDO::PARAM_STR);

$st1->execute();


$datte = date('Y-m-d h:i a', time());
$st1x =$dbonline->prepare("update e_case_detail set scrutiny_comp4 ='$datte' where filing_no=? ");
$st1x->bindParam(1, $filing_no, PDO::PARAM_STR);
//$st1x->execute();


$st1x =$dbonline->prepare("update e_case_detail set scrutiny_comp3 ='1' where filing_no=? ");
$st1x->bindParam(1, $filing_no, PDO::PARAM_STR);
$st1x->execute();



 $st1=$dbonline->prepare("select * from e_case_detail where  filing_no=?  ");
          $st1->bindParam(1, $filing_no, PDO::PARAM_STR);
         
         
          $st1->execute();
          while ($row = $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
          {
          
              $uniqueidno=$rowa['unique_id_no'];
		  }




$scrutiny_dc='0';
$scr_display='1';
	$st1x111 =$dbonline->prepare("update document_upload set scrutiny =?,display=? where uniqueid=? and filing_no=?  ");

$st1x111->bindParam(1, $scrutiny_dc, PDO::PARAM_STR);
$st1x111->bindParam(2, $scr_display, PDO::PARAM_STR);
$st1x111->bindParam(3, $uniqueidno, PDO::PARAM_STR);
$st1x111->bindParam(4, $filing_no, PDO::PARAM_STR);


//$st1x111->execute();



echo $message = 'Scrutiny Roll Back  Successfully ....';
$msg=base64_encode($filing_no);
$msg1=htmlspecialchars($message.'/'.$msg);
$hash=base64_encode($msg1);
header("Location:../scrutiny/astreg_modify.php?hash=$hash");
unset($_SESSION['form2_scruniny']);
die();       	
	

echo 'Invalid Entry.......';
header("Location:../scrutiny/astreg_modify.php");
}
?>
