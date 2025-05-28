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
include '../insidebar.php';

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
    action = "assign_user_menu.php";
    submit();
}
}
function submitForm1()
{
 	with(document.frm)
	{

if(auser.value == "")
        	{
        	alert("Please User Name !!!!!");
        	auser.focus();
        	return false;
        	}

var flds1=document.getElementsByName('asubmenu[]');
		for (var i=0;i<flds1.length;i++)
		{
		 	if(flds1[i].value=='')
			{
				alert("Please Select Sub-Menu");
				flds1[i].focus();
				return false;
			}
			
		}
		
		if(amenu.value == "")
        	{
        	alert("Please Select Menu Name!!!!!");
        	amenu.focus();
        	return false;
        	}

	action = "assign_user_menu.php";
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
<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
<!-- jvectormap -->
<link rel="stylesheet" href="../bower_components/jvectormap/jquery-jvectormap.css">
<!-- Theme style -->
<link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins
folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">



<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="../plugins/iCheck/all.css">
<!-- Bootstrap Color Picker -->
<link rel="stylesheet" href="../bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
<!-- Select2 -->
<link rel="stylesheet" href="../bower_components/select2/dist/css/select2.min.css">



<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Sparkline -->
<script src="../bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap  -->
<script src="../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS -->
<!-- <script src="../bower_components/Chart.js/Chart.js"></script> -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../dist/js/pages/dashboard2.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>


<!-- Select2 -->
<script src="../bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="../plugins/input-mask/jquery.inputmask.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="../bower_components/moment/min/moment.min.js"></script>
<script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap color picker -->
<script src="../bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->

<!-- AdminLTE App -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    })

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })
  })
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
        <center>Assign Menu to Users
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
                            <td valign="top" align="right" colspan="4"> USER NAME: </td>
                                <td valign="top" align="left" colspan="4">
                                <?php  $auser = isset($_REQUEST['auser']) ? $_REQUEST['auser'] :'';?>
                               <select name="auser" >
                                    <option value="">--SELECT USER NAME--</option>
                                    <?php

                                    //$display='T';

                                    $select_user_sql = $db->prepare("select username, id from public.users_cis order by username");
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
							
							<tr>
                            <td valign="top" align="right" colspan="4"> MENU: </td>
                                <td valign="top" align="left" colspan="4">
                                <?php  $amenu = isset($_REQUEST['amenu']) ? $_REQUEST['amenu'] :'';?>
                               <select name="amenu" onchange="javascript:select_menu();" >
                                    <option value="">--SELECT MENU--</option>
                                    <?php

                                    $display='T';

                                    $select_menu_sql = $db->prepare("select menu_id, menu_name from public.menu_r order by menu_name");
                                    //$select_user_sql->bindParam(1, $display, PDO::PARAM_STR);
                                    $select_menu_sql->execute();
                                    while ($select_menu_sql_result = $select_menu_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                                    {
                                        $user_id=htmlspecialchars($select_menu_sql_result['menu_id']);
                                        $user_name=htmlspecialchars($select_menu_sql_result['menu_name']);
                                        if($amenu == $user_id)
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
						 <td valign="top" align="left" colspan="4">
                                <?php  $asubmenu = isset($_REQUEST['asubmenu']) ? $_REQUEST['asubmenu'] :'';?>
                               <select name="asubmenu[]" multiple="multiple" >
                                    <option value="">--SELECT SUBMENU--</option>
                                    <?php

                                    $display='T';

                                    $select_submenu_sql = $db->prepare("select submenu_id, submenu_name from public.submenu_r where menu_id = '$amenu' order by submenu_name");
                                    //$select_user_sql->bindParam(1, $display, PDO::PARAM_STR);
                                    $select_submenu_sql->execute();
                                    while ($select_submenu_sql_result = $select_submenu_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
                                    {
                                        $user_id=htmlspecialchars($select_submenu_sql_result['submenu_id']);
                                        $user_name=htmlspecialchars($select_submenu_sql_result['submenu_name']);
                                        if($asubmenu == $user_id)
                                        {
                                            print "<option value=".htmlspecialchars($select_submenu_sql_result['submenu_id'])." selected>".htmlspecialchars(ucwords(strtoupper($select_submenu_sql_result['submenu_name'])))."</option>";
                                        }
                                        else
                                        {
                                            print "<option value=".htmlspecialchars($select_submenu_sql_result['submenu_id']).">".htmlspecialchars(ucwords(strtoupper($select_submenu_sql_result['submenu_name'])))."</option>";
                                        }
                                    }

                                    ?>

                                </select>

                            </td></
						</tr>
						
                        
						<tr>  <td valign="top" align="right" colspan="4">
                        </td><td>
                                    <input id="submitsubmenu" type="submit"  name="submitassignmenu" value="ASSIGN MENU"
                                            onClick="return submitForm1();"/></td></tr>
						
<!--my code end here-->



  
     </form>    
   </table>

  <?php 
  if(isset($_REQUEST['submitassignmenu'])){
	  //print_r($_REQUEST);
	  $user_id = $_REQUEST['auser'];
	  $menu_id = $_REQUEST['amenu'];
	  $submenu_id =$_REQUEST['asubmenu'];		 	  
	  
	  foreach ($submenu_id as $selectedOption){
		  //echo $selectedOption."\n"."uu";
		  
		  //validation
	  $check_submenu_sql =$db->prepare("select * from public.link_r where user_id='$user_id' and submenu_id='$selectedOption'");
	 // print_r($check_submenu_sql);
	  $check_submenu_sql->execute(); 
	  $check_submenu_sql_results = $check_submenu_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT);
	  
	  //offset to insert all other submenu except already assigned
	  $offset = 1;
	  
	  		  //query to get submenu name
		  $get_submenu_name_sql =$db->prepare("select submenu_name from public.submenu_r where submenu_id='$selectedOption'");
		  $get_submenu_name_sql->execute();
		  $get_submenu_name_sql_results = $get_submenu_name_sql->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT);
		  $submenu_name = $get_submenu_name_sql_results['submenu_name'];
	  if($check_submenu_sql_results){		  
		  echo '<h2><center>'.$submenu_name.' ALREADY ASSIGNED to selected user!</center></h2>';
	$offset = 0;
	  }
		  if($offset == '1'){
		  $create_link_sql =$db->prepare("insert into public.link_r (user_id, menu_id, submenu_id) values (?,?,?)");
		  $create_link_sql->bindParam(1, $user_id, PDO::PARAM_STR);
          $create_link_sql->bindParam(2, $menu_id, PDO::PARAM_STR);
          $create_link_sql->bindParam(3, $selectedOption, PDO::PARAM_STR);

          $create_link_sql->execute();
		   echo '<h2><center>'.$submenu_name.' SUCCESSFULLY ASSIGNED to selected user.</center></h2>'; 
		  }
	  }	  
	  //$create_submenu_sql =$db->prepare("insert into public.submenu_r (submenu_name, menu_id, submenu_link, priority, display) values ('$submenu_name','$menu_id','$submenu_link','$priority','$display')");
	  //print_r($create_submenu_sql);die();  
  }
 // include '../bfooter.php';
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