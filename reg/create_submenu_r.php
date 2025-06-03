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
	$stlu = $db->prepare("select schema_id from public.users_cis where id=? ");
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
//include '../insidebar.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="javascript">
//my code
function select_menu()
{
with(document.frm)
{
    action = "create_submenu_r.php";
    submit();
}
}
function submitForm1()
{
 	with(document.frm)
	{

if(bench_location.value == "")
        	{
        	alert("Please Select Bench !!!!!");
        	bench_location.focus();
        	return false;
        	}

if(bench_code.value == "")
        	{
        	alert("Please Select Bench Nature!!!!!");
        	bench_code.focus();
        	return false;
        	}
var flds1=document.getElementsByName('judge[]');
		for (var i=0;i<flds1.length;i++)
		{
		 	if(flds1[i].value=='')
			{
				alert("Please Select Coram");
				flds1[i].focus();
				return false;
			}
			
		}
if(bench_code.value ==7 && no_of_judge1.value=="" ){
	alert("Please provide number of no of judge !!!!");
        	no_of_judge1.focus();
        	return false;
}		
if(from_list_date.value == "")
        	{
        	alert("Please Select Listing Date!!!!!");
        	from_list_date.focus();
        	return false;
        	}

if(court_no.value == "")
        	{
        	alert("Please Enter Court No!!!!!");
        	court_no.focus();
        	return false;
        	}

if(isNaN(court_no.value) == true)
			{
				alert("Please Enter Numeric Court No.");
				court_no.select();
				return false;
			}

if(limit_case.value == "")
        	{
        	alert("Please Enter Limit of Case!!!!!");
        	limit_case.focus();
        	return false;
        	}
if(isNaN(limit_case.value) == true)
			{
				alert("Please Enter Numeric for Limit Case");
				limit_case.select();
				return false;
			}

if(limit_case.value!= "")
        	{
if(limit_case.value<1)
        	{
        	alert("Please Enter Valid Limit of Case!!!!!");
        	limit_case.focus();
        	return false;
        	}
}
	action = "bench_action.php";
		submit();
	}
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
        <center>Sub-Menu Creation
        </center>
      </h1>
      
    </section>
 <p> <h4><center>All <font color="red">*</font></span> is mandatory Field </center></h4></p>
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
                            <td valign="top" align="right" colspan="4"> SELECT PARENT MENU: </td>
                                <td valign="top" align="left" colspan="4">
                                <?php  $submenu = isset($_REQUEST['submenu']) ? $_REQUEST['submenu'] :'';?>
                               <select name="submenu" onchange="javascript:select_menu();" >
                                    <option value="">SELECT MENU</option>
                                    <?php

                                    $display='T';

                                    $select_menu_sql = $db->prepare("select menu_id, menu_name from menu_r where parent_menu='T' and display=? order by menu_id asc");
                                    $select_menu_sql->bindParam(1, $display, PDO::PARAM_STR);
                                    $select_menu_sql->execute();
                                    while ($select_menu_sql_result = $select_menu_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                                    {
                                        $menu_id=htmlspecialchars($select_menu_sql_result['menu_id']);
                                        $menu_name=htmlspecialchars($select_menu_sql_result['menu_name']);
                                        if($submenu == $menu_id)
                                        {
                                            print "<option value=".htmlspecialchars($select_menu_sql_result['menu_id'])." selected>".htmlspecialchars(ucwords(strtoupper($select_menu_sql_result['menu_name'])))."</option>";
                                        }
                                        else
                                        {
                                            print "<option value=".htmlspecialchars($select_menu_sql_result['menu_id']).">".htmlspecialchars(ucwords(strtoupper($select_menu_sql_result['menu_name'])))."</option>";
                                        }
                                    }

                                    ?>

                                </select>

                            </td></tr>
							
<tr>
                        <td valign="top" align="right" colspan="4">SUB-MENU NAME:
                        </td>
						 <td valign="top" align="left" colspan="6">
						 
						 <input type="text" name="submenu-name" ?><br>
						 </td>
						</tr>
						
                        <tr>
                            <td valign="top" align="right" colspan="4"> LINK:&nbsp;&nbsp;&nbsp;&nbsp;<b>GSTAT/</b>  </td>
                                <td valign="top" align="left" colspan="4">
                       
                              <input type="text" name="submenu_link"><br>
                                &nbsp;

                            </td></tr>

<tr>
                        <td valign="top" align="right" colspan="4">PRIORITY:
                        </td>
						 <td valign="top" align="left" colspan="6">
						 <input type="text" name="priority"><br>
						 </td>
						</tr>
						
						<tr>
                        <td valign="top" align="right" colspan="4">DISPLAY:
                        </td>
                        <td valign="top" align="left" colspan="6">
                            <select name="display" >
							<option value="">SELECT DISPLAY</option>
							<option value="0">FALSE</option>
							<option value="1">TRUE</option>
							
                            </select>

                        </td></tr>
						<tr>  <td valign="top" align="right" colspan="4">
                        </td><td>
                                    <input id="submitsubmenu" type="submit"  name="submitsubmenu" value="ADD SUB-MENU"
                                            /></td></tr>
						
<!--my code end here-->



  
     </form>    
   </table>

  <?php 
  if(isset($_REQUEST['submitsubmenu'])){
	  //print_r($_REQUEST);
	  $menu_id = $_REQUEST['submenu'];
	  $submenu_name =$_REQUEST['submenu-name'];
		  $submenu_link =$_REQUEST['submenu_link'];
	  $display =$_REQUEST['display'];
	  $priority =$_REQUEST['priority'];
	  
	  //validation code
	 // $select_menu_sql=$db->prepare("select menu_id from menu_r where menu_name=?");
	  //$select_menu_sql->bindParam(1, $menu_name, PDO::PARAM_STR);
      //$select_menu_sql->execute(); 
	  //$row = $select_menu_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT)

	  $create_submenu_sql =$db->prepare("insert into public.submenu_r (submenu_name, menu_id, submenu_link, priority, display) values (?,?,?,?,?)");

	  //$create_submenu_sql =$db->prepare("insert into public.submenu_r (submenu_name, menu_id, submenu_link, priority, display) values ('$submenu_name','$menu_id','$submenu_link','$priority','$display')");
	  //print_r($create_submenu_sql);die();
	  
$create_submenu_sql->bindParam(1, $submenu_name, PDO::PARAM_STR);
$create_submenu_sql->bindParam(2, $menu_id, PDO::PARAM_STR);
$create_submenu_sql->bindParam(3, $submenu_link, PDO::PARAM_STR);
$create_submenu_sql->bindParam(4, $priority, PDO::PARAM_STR);
$create_submenu_sql->bindParam(5, $display, PDO::PARAM_STR);

$create_submenu_sql->execute();
	  
	  
	  
  }
  include '../bfooter.php';
  ?>

<script>
$('#t1 select').on('change', function() {
$('option').prop('disabled', false);
$('#t1 select').each(function() {
var val = this.value;
$('#t1 select').not(this).find('option').filter(function() {
return this.value === val;
}).prop('disabled', true);
});
}).change();
 $('.timepicker').timepicker({      showInputs: false    })
</script>
</body>
  <?php } ?>