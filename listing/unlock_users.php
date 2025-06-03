<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");
include("../db_inc1.php");
 
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
echo "you Can't access this page";
}
else {

$curYear = date('Y');
$curMonth = date('m');
$curDay = date('d');
$cur_date = "$curDay/$curMonth/$curYear";
$curdate="$curYear-$curMonth-$curDay";

$wday = mktime(0,0,0,date("$curMonth"),date("d")-5,date("Y"));
$wday1= date("Y-m-d");


$schemas=htmlspecialchars($_SESSION['schema_name']);
$frm = md5( uniqid('auth', true) );

$loc=$_SESSION['location'];
$userid=$_SESSION['user'];

$_SESSION['form_token'] = $frm;



?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>NCLT | Dashboard</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
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



<!--[endif]-->
<style>
    input[type=text] {
        padding: 1px;
        margin: 8px 0;
        box-sizing: border-box;
    }
    select{padding: 1px;
    margin: 8px 0;
    box-sizing: border-box; }
    /*[type=text]{padding: 5px;        margin-bottom: 5px;}*/
</style>

<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>
<script language="javascript">
function submitForm2()
{
with(document.frm)
{
	action="unlock_users.php";
submit();
}
}
function submitForm1()
{
with(document.frm)
{

action="unlock_users.php";
submit();
}

}
function submitForm()
{
with(document.frm)
{

action = "unlock_users.php";
submit();
}
}
function run()
{
with(document.frm)
{


action = "unlock_users.php";
submit();
}
}
function validate()
{
with(document.frm)
{



action="unlock_users_action.php";
submit();
document.frm.submit1.disabled = true;
document.frm.submit1.value = 'Please Wait...';
return true;
}

}
function SetBg(txt)
{
txt.style.backgroundColor='#ffff99';
}
function UnSetBg(txt)
{
txt.style.backgroundColor='white';
}


function popdashboard()
{

var url = "dashboard.php";
var width = 800;
var height = 1300;

var left = (screen.width-width)/2;

var top = (screen.height - height)/2;

var params = 'width='+ width +', height='+ height+', top='+ top +', left='+ left+ ',scrollbars=1';

window.open(url,"print",params);
}



//  END
</script>
<style>
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {padding:0px;}

</style>



</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php include("../includes/banner.php");?>
<div class="wrapper">

<?php include("../includes/header.php");
include '../insidebar.php';
?>

<div class="content-wrapper" style="min-height: 946px;">
<!-- Content Header (Page header) -->
<section class="content">
 <?php 
        		$msg=$_REQUEST['msg'];
             		
        		 
        			
        			echo "<center></br><font color='red' size='4'>".htmlspecialchars($msg).'</br>';
        		
        		
         ?>
		 </font>
<table>
<tr>
<td valign="top" align="right" colspan="16"><center>
<b><font face="Verdana" size="3"><u>Unlock Users </u></font> </b>
</td>
</tr>
<tr><td valign="top" align="center" colspan="16">
<font face="Verdana" size="2">Fields marked with a <font color='red'>*</font> are compulsory.</font> </td>
</tr>



<form name="frm" method="post" action="unlock_users_action.php">


<?php

$msghash=$_REQUEST['msghash'];
if($msghash !='')
{
$msghashz=(base64_decode($msghash));

$msghashz = explode("@", $msghashz);

$msg1 = $msghashz[0];



}

?>
<?php $users = isset($_REQUEST['users']) ? $_REQUEST['users'] :'1'; ?>


<tr>
<td align="left">
<font face="Verdana" size="2" color="red">*</span> </font><font face="Verdana" size="2">Users:
<select name="users" onchange="javascript:submitForm();">
<?php






$st = $db->prepare("select * from users_cis where location='$loc' and id!='$userid' order by username asc");
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT))
{
$ctc=htmlspecialchars($row['username']);
if($users == $ctc)
{
print "<option value=".htmlspecialchars($row['username'])." selected>".htmlspecialchars($row['username'])."</option>";
}
else
{
print "<option value=".htmlspecialchars($row['username']).">".htmlspecialchars($row['username'])."</option>";
}
}
?>
</select>


</td>
</tr>
<?php



$sql_cp1="select max(id) as id from log_attempt where  user_id='$users'";

$sql_cp1 = $db->prepare($sql_cp1);

$sql_cp1->execute();


while ($row1 = $sql_cp1-> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{ 
 $entryid = $row1['id'];

}

if($entryid!='')
{
$sql_cp5="select * from log_attempt where failed > '4' and lock='Y' and user_id='$users' and id='$entryid'";

$sql_cp5 = $db->prepare($sql_cp5);

$sql_cp5->execute();


while ($row5 = $sql_cp5-> fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
{ 
 $username_l = $row5['user_id'];
 $lock= $row5['lock'];
 $lockdate= $row5['date'];
 if($lockdate !='')
{
	list($year2,$month2,$day2)=explode('-',$lockdate);
	 $user_lock_date=$day2."/".$month2."/".$year2;
	
}
 //$locktime= $row5['datetime'];
if($lock=='Y')
{
	$user_lock='YES';
}
else
{
	$user_lock='NO';
}
}

}

?>
</table>
<table class="table" align="center">
<tr>
<td align="center"><b>User Name :</b>

<?php echo htmlspecialchars($username_l) ;?></td>

</tr>
<tr>
<td align="center">
<b>User Lock:</b>

<?php echo htmlspecialchars($user_lock) ;?></td>

</tr>


<tr>
<td align="center">

<b>User Lock Date:</b>

<?php echo htmlspecialchars($user_lock_date) ;?></td>

</tr>
<input type="hidden" name="frm" value="<?php echo htmlspecialchars($frm); ?>" />
<?php
if($user_lock!='')
{
	?>
<td colspan="3" align="center">
<br>
<br>
<input id="btnSubmit" type="submit" name="submit1" value="Submit" class="button" onClick="return validate();">     
</td></tr>

<?php

}
?>
<input type="hidden" name="uid" value="<?php print htmlspecialchars($entryid);?>" />

<?php

		}
	

?>
