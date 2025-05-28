

<?php 

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include("../db_inc1.php");
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
	header("Location: ../login.php");
	//die();
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

?>
<?php 
include '../inheader.php';
include '../insidebar.php';



?>
<link rel="stylesheet" href="../includes/style.css" type="text/css">



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
<script src="../dist/js/pages/dashboard2.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div class="content-wrapper">
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <form id="from_id_set_priority_id" method="post">
                    <table cellspacing="0" align="center" cellpadding="2" border="1" width="50%">
                        <tbody>
                            <tr>
                                <td colspan="2">
                                    <font face="Verdana" color="red" size="4">
                                        <center><b><u>Cause List Report</u></b></center>
                                    </font>
                                    <hr>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <font color="red" size="5">*</font> Listing Date:(DD/MM/YYYY)
                                </td>
                                <td>
                                    <input type="hidden" name="ctype" id="ctype" class="ctype" value="draft">
                                    <input type="text" readonly id="next_list_date" name="next_list_date"
                                        class="datepicker" size="20" value="">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <font color="red" size="5">*</font>Court
                                </td>
                                <td align="left">
                                    <select name="court_no" id="court_no" style="width:150px;">
                                        <option value="">Select Court</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <font color="red" size="5">*</font>Seelct Type
                                </td>
                                <td align="left">
                                    <select name="select_type" id="select_type" style="width:150px;">
                                        <option value="">Select Type</option>
                                        <option value="1">Supplementary</option>
                                        <option value="2">Priority List</option>
                                        <option value="3">Ordinary</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <input type="button" id="submit11" class="btn btn-primary" name="submit11"
                                        value="Search" onclick="fn_set_priorrty();">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </section>
</div>
<script>
$(function() {
    //,minDate: 0
    $("#next_list_date").datepicker({
        dateFormat: 'dd/mm/yy'
    });
});

function fn_set_priorrty(page_name, listing_date, bench_id) {
    var next_list_date = $("#from_id_set_priority_id #next_list_date").val();
    var case_type = $("#from_id_set_priority_id #select_type").val();
    var court_no = $("#from_id_set_priority_id #court_no").val();
    var ctype = $("#from_id_set_priority_id #ctype").val();
    if (next_list_date == '') {
        alert('Please Select Listing Date');
        return false;
    } else if (court_no == '') {
        alert('Please Select Court No.');
        return false;
    } else if (case_type == '') {
        alert('Please Select Type. ');
        return false;
    } else {
        var url = "../mis/generate_causelist_priority.php?next_list_date=" + next_list_date + "&case_type=" +
            case_type +
            "&court_no=" + court_no + "&ctype=" + ctype;
        window.open(url, "_blank", "directories=no, status=no,width=900, height=650, left=300, scrollbars=yes");
    }
}
</script>


<?php 
 /// include '../infooter.php';
}
  ?>