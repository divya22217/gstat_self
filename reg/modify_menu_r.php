<?php 
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include("../db_inc1.php");
session_start();
$judge =htmlentities($_REQUEST['judge']);

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
	header("Location: ../login.php");
	die();
}
setcookie("PHPSESSID","",time()-3600,"","",TRUE,TRUE);
$benchlocation =htmlentities($_REQUEST['bench_location']);
if($_SESSION['user'] !='' and $_SESSION['location'] !='')
{
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	$curYear = htmlspecialchars(date("Y"));
	$curMonth = htmlspecialchars(date("m"));
	$curDay = htmlspecialchars(date("d"));
	$cur_date = "$curYear-$curMonth-$curDay";
	$cur_date1 ="$curDay/$curMonth/$curYear";
	$link_scrutiny_idaccess='1';
	
	//my code
	$sessionUserType=htmlspecialchars($_SESSION['id']);
	$stlu = $db->prepare("select schema_id from public.users where id=? ");
	//print_r($stlu);
	$stlu->bindParam(1, $sessionUserType, PDO::PARAM_STR);
	$stlu->execute();
	while ($row = $stlu->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
	{
		$schema_idrun=$row['schema_id'];
		//print_r($schema_idrun);
	}
	
?>
<?php 

include '../inheader.php';
include '../insidebar.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="javascript">
//my code
function submituser()
{
with(document.frm)
{
    action = "modify_menu_r.php";
    submit();
}
}

function DeleteMenuqry(id)
{ 
  if(confirm("Are you sure you want to delete this menu?")==true)
           window.location="delete_menu_r.php?del="+id;
    return false;
}

function DeleteSubmenuqry(sbid, uid)
{ 
  if(confirm("Are you sure you want to delete this sub-menu?")==true)
           window.location="delete_submenu_r.php?del="+sbid+"&uid="+uid;
    return false;
}

function bench_popup() {
    var myWindow = window.open("bench_composition_delete.php", "", "width=1200,height=700");
}

function bench_tab() {
    window.open("bench_composition_delete.php");
}
</script>

</head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
	<body>
    <title>Create Bench</title>
   
    
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <center>Modify Assigned Menus
        </center>
      </h1>
      
    </section>
<div class="container">
<table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">

  
<form name="frm" method="post" action="" >

<?php
$msg =htmlentities($_REQUEST['msg']);
if($msg !='')
{
?>
<tr>
<td colspan="6"><center>
<font style='font-weight:bold' color='red' size='4'> <?php echo $msg."<a href='' onclick='bench_popup()'>Click to View Bench Report</a>";?></font> 
</td>
</center>
</tr>
<?php
}


?>
                        <tr>
                            <td valign="top" align="right" colspan="4"> USER NAME: </td>
                                <td valign="top" align="left" colspan="4">
                                <?php  $auser = isset($_REQUEST['auser']) ? $_REQUEST['auser'] :'';?>
                               <select name="auser" onchange="javascript:submituser();">
                                    <option value="">--SELECT USER NAME--</option>
                                    <?php

                                    //$display='T';

                                    $select_user_sql = $db->prepare("select username, id from public.users order by username");
                                    //$select_user_sql->bindParam(1, $display, PDO::PARAM_STR);
                                    $select_user_sql->execute();
                                    while ($select_user_sql_result = $select_user_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                                    {
                                        $user_id=htmlspecialchars($select_user_sql_result['id']);
                                        $user_name=htmlspecialchars($select_user_sql_result['username']);
                                        if($auser == $user_id)
                                        {
                                            print "<option value=".htmlspecialchars($select_user_sql_result['id'])." selected>".htmlspecialchars(ucwords(strtoupper($select_user_sql_result['username'])))."</option>";
                                        }
                                        else
                                        {
                                            print "<option value=".htmlspecialchars($select_user_sql_result['id']).">".htmlspecialchars(ucwords(strtoupper($select_user_sql_result['username'])))."</option>";
                                        }
                                    }

                                    ?>

                                </select>

                            </td></tr>
							
							<?php $username = isset($_REQUEST['auser']) ? $_REQUEST['auser'] :'';

if($_GET['id']){
	$username = $_GET['id'];
}							
							
							if($username != ''){
							
							?>
							
							
							<?php 
                            $get_menu_sql = $db->prepare("select distinct menu_id from public.link_r where user_id = '$username'");
							//print_r( $get_menu_sql);
							 $get_menu_sql->execute();
							 /*if($select_user_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT)==NULL){
								 ?>
								 <tr align="center">
                        <td align="center" colspan="8" style="padding-bottom:1em;"><h3><b>NO RECORD FOUND...</b></h3></td>											
                    </tr>
								 <?php
							 }*/
                                    while ($get_menu_sql_result = $get_menu_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                                    {
										$menu_id = htmlspecialchars($get_menu_sql_result['menu_id']);
										$get_menuname_sql = $db->prepare("select menu_name from public.menu_r where menu_id = '$menu_id'");
										$get_menuname_sql->execute();
										$get_menu_sql_result = $get_menuname_sql->fetch();
										$menu_name = htmlspecialchars($get_menu_sql_result['menu_name']);
							?>
							
							<tr>
                        <td align="center" colspan="4" style="padding-bottom:1em;"><h3><?php echo $menu_name; ?></h3>
                        </td>
						
						 <td align="center" colspan="6">
						 <input type="button" name="abc" id="abc" value="Delete Menu" onclick="return DeleteMenuqry(<?php echo $menu_id ?>);"/>						 
						 </td>
						</tr>
						
						<?php 
						$get_submenu_sql = $db->prepare("select submenu_id from public.link_r where user_id = '$username' and menu_id = '$menu_id'");
						 $get_submenu_sql->execute();
                                    while ($get_submenu_sql_result = $get_submenu_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                                    {
										$submenu_id = htmlspecialchars($get_submenu_sql_result['submenu_id']);
										$get_submenuname_sql = $db->prepare("select submenu_name from public.submenu_r where submenu_id = '$submenu_id'");
										$get_submenuname_sql->execute();
										$get_submenuname_sql_result = $get_submenuname_sql->fetch();
										$submenu_name = htmlspecialchars($get_submenuname_sql_result['submenu_name']);
						?>
						
						 <ul class="list-group">						
						<tr>
                        <td align="center" colspan="4" style="padding-bottom:1em;"> <li class="list-group-item"><?php echo $submenu_name; ?></li>
                        </td>
						
												 <td align="center" colspan="6">
						 <input type="button" name="abc" id="abc" value="Delete Sub-menu" onclick='return DeleteSubmenuqry("<?php echo $submenu_id;?>" , "<?php echo $username; ?>");'/>
						 
						 </td>
                    </tr>
						
						
							<?php }
							?></ul><?php
							}} ?>
							
<!--my code end here-->



  
     </form>    
   </table>
</div>
</div>
  <?php 
  include '../bfooter.php';
  ?>
</body>
  <?php } ?>