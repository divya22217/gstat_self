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
include("../db_inc1.php");//database connection
session_start();
$localadmin=$_SESSION['localadmin'];
$main_id=$_SESSION['main_id'];
$schemas=htmlspecialchars($_SESSION['schema_name']);
$userid=$_SESSION['id'];
$level_level=$_SESSION['level_level'];
/* $dept=$_SESSION['dept']; */
$leveladd=$_SESSION['level_level'];


if($_SESSION['user'] == '' and $_SESSION['location'] =='')
{
    echo "Access Problem.....";
    header("Location: ../login.php");
    die();
}

$_SESSION['menuaccess_codeall'];
if($_SESSION['menuaccess_codeall'] !='1' and $main_id !='9999' and ($localadmin !='1' OR $localadmin !='2'))
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
	$stlu = $db->prepare("select schema_id from users_cis where id= ? ");
	$stlu->bindParam(1, $sessionUserType, PDO::PARAM_STR);
	$stlu->execute();
	while ($row = $stlu->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		$schema_idrun=$row['schema_id'];
	}
	$stlu = $db->prepare("select location from users_cis where id= ? ");
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
		echo "You are not Valied User..... please login again";
		header("Location: ../login.php");
		die();
	}
	if($locationq =='')
	{
		session_unset();     // unset $_SESSION variable for the run-time
		session_destroy();
		echo "You are not Valied User..... please login again";
		header("Location: ../login.php");
		die();
	}


?>



<html>
<head>
<title>NCLT||Assign Menu</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 <?php /* include("../includes/common_js.php"); */?> 
		<script language="javascript">
		
		// WRITE YOUR CODE HERE
		function search()
		{
		with(document.frm)
		{
		    action = "assign_menu.php";
		    submit();
		}
		}
		
		
		function goFinal()
		{
		with(document.frm)
		{
		
		    if(user_type.options[user_type.selectedIndex].value == "")
		    {
		        alert("Please Select User Name  ");
		        user_type.focus();
		        return false;
		    }
		    if(menu.options[menu.selectedIndex].value == "")
		    {
		        alert("Please Select Head Menu  ");
		        menu.focus();
		        return false;
		    }
		
		    action="assign_menu_action.php";
		    submit();
		    document.frm.submit11.disabled = true;
		    document.frm.submit11.value = 'Please Wait...';
		    return true;
		
		
		}
		}
		</script>
</head>


<body>
<?php 
include '../inheader.php';
include '../insidebar.php';

?>

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <center>Assign Menu
        </center>
      </h1>
      
    </section>
    
<div class="content-wrapper">
<main class="content-header">

<?php
if($_SESSION['menuaccess_codeall'] !='9' and $main_id !='9999' and ($localadmin !='1' OR $localadmin !='2'))
{
session_unset();     // unset $_SESSION variable for the run-time
session_destroy();
print "Invalid Form Entry .....";
$aazzs='108';
header("Location: ../login.php?aa=$aazzs");
}
?>

<?php
if($_SESSION['menuaccess_codeall'] =='9' and $main_id =='9999' and $localadmin =='2')
{
echo '<h6 style="text-transform: none; color: red;">You have not permission to use  this module .Please contact to local admin  </h6>';
}?>




<?php
if($_SESSION['menuaccess_codeall'] =='1' and $main_id =='9999' and $localadmin =='1')
{

?>
<!-- <legend> <h1 ><font color="#000000" size='3'><u>ASSIGN MENU:</u></font></h1></legend> -->

<fieldset>



<?php
//$st = $db->prepare("select * from initilization where schema_id=? ");
//$st->bindParam(1, $schema_idrun, PDO::PARAM_STR);
//$st->execute();
//while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
//{
//$c_type = htmlspecialchars($row['schema_id']);
//$short_name = htmlspecialchars(ucwords($row['name']));
//$short_name = htmlspecialchars(ucwords(strtoupper($short_name)));
//}
//echo "<b>";
//echo htmlspecialchars(htmlentities(strtoupper($short_name)));
//echo "</b>";
?>


            <form name="frm"   method="post" action="assign_menu_action.php">
                <table border="1">
                <tr>
                <!--th  align="center" colspan="10">
                <b>ASSIGN MENU</b></font></th-->
                </tr>
                <?php
                $sessionUserType=htmlspecialchars($_SESSION['id']);
              	$stlu = $db->prepare("select main_id,localadmin,location from users_cis where id= ? ");
                $stlu->bindParam(1, $sessionUserType, PDO::PARAM_STR);
                $stlu->execute();
                $row = $stlu->fetch();
                $localadminzz=$row['localadmin'];
                $main_id=$row['main_id'];
               $locationq=$row['location'];
                ?>

                <tr>
                <td valign="top" align="center" width="500" colspan="10"><font color="#960000" ><b>
                <?php
                $hash=htmlspecialchars($_REQUEST['hash']);
                $hash1=htmlspecialchars(base64_decode($hash));
                if($hash1 !='')
                {
                echo htmlspecialchars($hash1);
                }

                ?>
                </b></font>
                </td></tr>


                    <?php
                    /* if user is localadmin $localadminzz=1*/
                     if($main_id == '9999' and $localadminzz == '1')
                    {

                    ?>
                    <tr>
                        <td valign="top" align="right" colspan="4" class="form-control";>USER NAME:
                        </td>
                        <td valign="top" align="left" colspan="6">
                            <?php   $usertype = isset($_REQUEST['user_type']) ? $_REQUEST['user_type'] :'';?>
                            <select name="user_type" onchange="javascript:search();" class="form-control"; >
                                <option value="">SELECT USER NAME</option>
                                <?php
                                $main_id1 = '9999';
                                $local = '0';
                                
                             
                     
                               $st = $db->prepare("select * from users_cis where main_id =? and localadmin =? and schema_id=? and login_status like 'Approved%' order by id asc");
                                $st->bindParam(1, $main_id1, PDO::PARAM_STR);
                                $st->bindParam(2, $local, PDO::PARAM_STR);
				                $st->bindParam(3, $schema_idrun, PDO::PARAM_STR);
                                $st->execute();
                                while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                                {
                                   $ctc=htmlspecialchars($row['id']);
                                    if($usertype == $ctc)
                                    {
                                        print "<option value=".htmlspecialchars($row['id'])." selected>".htmlspecialchars(ucwords(strtoupper($row['username'])))."</option>";
                                    }
                                    else
                                    {
                                        print "<option value=".htmlspecialchars($row['id']).">".htmlspecialchars(ucwords(strtoupper($row['username'])))."</option>";
                                    }
                                }

                                ?>

                            </select>

                        </td></tr>
                    <?php
                    }



                    if($usertype !='')
                    {

                        ?>
                        <tr>
                            <td valign="top" align="right" colspan="4" class="form-control"> MENU: </td>
                                <td valign="top" align="left" colspan="4">
                                <?php  $menu = isset($_REQUEST['menu']) ? $_REQUEST['menu'] :'';?>
                               <select name="menu" class="form-control" onchange="javascript:search();" >
                                    <option value="">SELECT MENU</option>
                                    <?php

                                    $type1 = '0';
                                    $type2 = '0';
                                    $type2 = '0';
                                    $type3 = '0';
                                    $orderno = '1';
                                    $display='T';

                                    $st1 = $db->prepare("select * from links where type1 !=? and type1 !=7 and type2 =? and type3 =? and order_no =?  and display=? order by id asc");
                                    $st1->bindParam(1, $type1, PDO::PARAM_STR);
                                    $st1->bindParam(2, $type2, PDO::PARAM_STR);
                                    $st1->bindParam(3, $type3, PDO::PARAM_STR);
                                    $st1->bindParam(4, $orderno, PDO::PARAM_STR);
                                    $st1->bindParam(5, $display, PDO::PARAM_STR);
                                    $st1->execute();
                                    while ($row = $st1->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                                    {
                                        $tt1=htmlspecialchars($row['type1']);
                                        $ctca=htmlspecialchars($row['id']);
                                        if($menu == $ctca)
                                        {
                                            print "<option value=".htmlspecialchars($row['id'])." selected>".htmlspecialchars(ucwords(strtoupper($row['name'])))."</option>";
                                        }
                                        else
                                        {
                                            print "<option value=".htmlspecialchars($row['id']).">".htmlspecialchars(ucwords(strtoupper($row['name'])))."</option>";
                                        }
                                    }

                                    ?>

                                </select>
                                &nbsp;
                                <?php
                                if($menu !='')
                                {
                                    ?>
                                    <!--input type="hidden" name="menucc" value="<?php echo htmlspecialchars($key)?>" /-->
                                    <input type="hidden" name="frm" value="<?php echo htmlspecialchars($key);?>" />
                                    <input id="submit11" type="button"  name="submit11" value="ADD MENU"
                                           onClick="return goFinal();" />
                                <?php } ?>


                            </td></tr>

                        <?php
                    }
                    if($usertype !='')
                    {

                    ?>

                    <table border="1"  class="std" width="55%" class="form-control">

                        <th valign="top" align="center"  colspan="2">
                            HEAD MENU (Assign Menu [Already Assign])
                        </th>

                        </tr>

                        <?php
                        $display='T';
                        $typed='0';
                        $typed1='0';
                        $typed2='0';
                        $st1a = $db->prepare("select * from menu where userid=? and display=? and type1 !=? and type2 =? and type3 =? ");
                        $st1a->bindParam(1, $usertype, PDO::PARAM_STR);
                        //$st1a->bindParam(1, $sessionUserType, PDO::PARAM_STR);
                        $st1a->bindParam(2, $display, PDO::PARAM_STR);
                        $st1a->bindParam(3, $typed, PDO::PARAM_STR);
                        $st1a->bindParam(4, $typed1, PDO::PARAM_STR);
                        $st1a->bindParam(5, $typed2, PDO::PARAM_STR);
                        $st1a->execute();
                        while ($row = $st1a->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                        {
                            $tt1=htmlspecialchars($row['type1']);
                            $tt2=htmlspecialchars($row['type2']);
                            $tt3=htmlspecialchars($row['type3']);
                            $namet1=htmlspecialchars($row['name']);
                            $serialst1=htmlspecialchars($row['serials']);
                            ?>


                            <tr class="form-control">
                                <td valign="top" align="left"  colspan="2"><?php echo htmlspecialchars($namet1);?></td>

                            </tr>
                            <?php
                        }//main menu


                        }


                        ?>

                    </table>



</table></form>
</fieldset>
<?php } ?>

 <?php 
 include '../infooter.php';
  ?> 


 <?php } ?>
 

