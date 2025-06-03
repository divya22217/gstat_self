<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include "../db_inc1.php";
include '../db_inc2.php';
require_once '../SrcCauselist/Causelist.php';
require_once '../custom/custom_function.php';
$causelist = new Causelist();
$bench_no = '';
$_SESSION['user'];
$_SESSION['location'];
$leveladd = $_SESSION['level_level'];
$localadmin = $_SESSION['localadmin'];
$main_id = $_SESSION['main_id'];
$schemas = htmlspecialchars($_SESSION['schema_name']);
$userid = $_SESSION['id'];
if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: ../login.php");
    die();
}
setcookie("PHPSESSID", "", time() - 3600, "", "", true, true);
//$benchlocation =htmlentities($_REQUEST['bench_location']);
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {
    $sessionUserType = htmlspecialchars($_SESSION['id']);
    $curYear = htmlspecialchars(date("Y"));
    $curMonth = htmlspecialchars(date("m"));
    $curDay = htmlspecialchars(date("d"));
    $cur_date = "$curYear-$curMonth-$curDay";
    $cur_date1 = "$curDay/$curMonth/$curYear";
    $link_scrutiny_idaccess = '1';

    date_default_timezone_set("Asia/Kolkata");
    $server_date = date('Y-m-d'); //Returns IST

    ?>
<?php
include '../inheader.php';
    include '../insidebar.php';
    ?>
<link rel="stylesheet" type="text/css" href="../datatable/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="../datatable/css/buttons.dataTables.min.css">
<style>
.load_container {
    background: rgba(0, 0, 0, 0.50);
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0;
    z-index: 99;
    left: 0;
}

.load_container .loader {
    display: block;
    width: 60px;
    height: 60px;
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    margin: auto;
}

select {
    border: solid 1px #ccc;
    border-radius: 5px;
    padding: 7px 14px;
    margin-bottom: 10px;
}

input {
    border: solid 1px #ccc;
    border-radius: 5px;
    padding: 7px 14px;
    margin-bottom: 10px;
}
</style>
<div class="load_container">
    <img class="loader" src="../loading-indicator.gif">
</div>
<script language="javascript">
function MM_openBrWindow(theURL, winName, features) { //v2.0
    window.open(theURL, winName, features);
    return false;
}

function submitForm() {
    with(document.frm) {

        action = "man_generate_case_no.php";
        submit();
    }
}

function submitForm1() {
    with(document.frm) {
        action = "man_generate_case_no_action.php";
        submit();
    }
}

function submitForm2() {
    with(document.frm) {
        submit();
    }
}
</script>
<script language="javascript">
function popsurety_pending_report(cfy) {
    var url = "./generate_case_number.php?no=" + cfy;
    window.open(url, "_blank", "directories=no, status=no,width=600, height=500, left=350, top=100, scrollbars=yes");
}
</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <!-- Content Header (Page header) -->
                <h2>
                    <center>MANUAL CASE NUMBER GENERATION </center>
                </h2>
				<hr>
                <table id="examplesssssss" class='table'>
                    <thead>
                        <tr>
                            <th>SERIAL NO.</th>
                            <th>DIARY NO.</th>
                            <th>MAIN Filing No</th>
                            <th>MAIN CASE No</th>
                            <th style="width:16%">Main Case last Court No & listing Date</th>
                            <th>DATE OF FILING</th>
                            <th>CASE TYPE</th>
                            <th>PARTY NAME</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
$count = 0;
    $sql1 = $db->prepare("select * from $schemas.case_detail where legal_aid='A' and (case_no IS NULL OR case_no='') order by dt_of_filing asc");
    $sql1->execute();
    while ($row1 = $sql1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $filing_no = $row1['filing_no'];
        $dof = $row1['dt_of_filing'];
        list($year, $month, $day) = explode('-', $dof);
        $dof = $day . '/' . $month . '/' . $year;
        $case_no = $row1['case_no'];
        $case_year = $row1['case_year'];
        $case_type = $row1['case_type'];
        $pet_name = $row1['pet_name'];
        $res_name = $row1['res_name'];
        $location_code = $row1['location_code'];
        $case_title = $pet_name . "<br> VS  <br>" . $res_name;
        $case_type_short_name = '';
        if ($case_type > 0) {
            $ref_stQ = $db->prepare("select case_type_desc from case_type where id = ?");
            $ref_stQ->bindParam(1, $case_type, PDO::PARAM_STR);
            $ref_stQ->execute();
            $case_type_short_name = $ref_stQ->fetchColumn();
        }


        $count++;
        $in_filingno = '';
        $get_in_filing_no_sql = $dbonline->prepare("select in_filingno from e_case_detail where filing_no=?");
        $get_in_filing_no_sql->bindParam(1, $filing_no, PDO::PARAM_INT);
        $get_in_filing_no_sql->execute();
        $in_filingno = $get_in_filing_no_sql->fetchColumn();

        $case_number = '';
        if ($in_filingno != '') {
            $case_number =   fn_getCaseNo($db, $schemas, $in_filingno);
        }

        $list_court_no = '';
        if ($in_filingno != '') {
            $list_court_no =   fn_getlastListdateCourt_no($db, $schemas, $in_filingno);
        }
		//print_r($list_court_no);

        ?>

                        <!-- -->
                        <tr>
                            <td align="center"><?php echo $count; ?></td>
                            <td><a href="javascript:popsurety_pending_report('<?php echo htmlspecialchars($filing_no); ?>');"
                    style="text-decoration: none;"> <?php echo $filing_no ?></font></a></td>
                            <td><?php if ($in_filingno != '') {echo $in_filingno;} else {echo "<centre>--</centre>";}?>
                            </td>
                            <td><?php  echo $case_number; ?></td>
                            <td><?php if(!empty($list_court_no) && is_array($list_court_no)) {  echo $list_court_no['listing_court'].'<br>'.$list_court_no['court']; } ?></td>
                            <td><?php echo htmlspecialchars($dof); ?></td>
                            <td><?php echo $case_type_short_name; ?></td>
                            <td><?php echo $case_title; ?></td>
                        </tr>
                        <?php
}

    ?>
                    <tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<?php include '../infooter.php';?>

<script src="../datatable/js/jquery.dataTables.min.js"></script>
<script src="../datatable/js/dataTables.buttons.min.js"></script>
<script src="../datatable/js/buttons.flash.min.js"></script>
<script src="../datatable/js/jszip.min.js"></script>
<script src="../datatable/js/pdfmake.min.js"></script>
<script src="../datatable/js/vfs_fonts.js"></script>
<script src="../datatable/js/buttons.html5.min.js"></script>
<script src="../datatable/js/buttons.print.min.js"></script>
<!-- Theme JS files -->
<script src="../datatable/js/datatables_extension_buttons_html5.js"></script>
<script>
$(document).ready(function() {
    $('#examplesssssss').DataTable({
        buttons: {
            dom: {
                button: {
                    className: 'btn btn-light'
                }
            },
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        }
    });
});

$('.load_container').fadeOut(500);
</script>

<?php }?>