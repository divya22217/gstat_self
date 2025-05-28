<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
include "db_inc1.php";
$schemas = $_SESSION['schema_name'];
//print_r($_SESSION);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(0);

?>

<style>
@media print {
    .hide_print {
        display: none;
    }

    .table .thead th {
        font-weight: bold;
    }

    .table-bordered {
        border: 0 !important;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #5a5a5a !important;
    }

    .table>tbody+tbody {
        border-top: 1px solid #5a5a5a;
    }
}
</style>

<div id="testdiv" class="text-left hide_print"><a href="javascript:window.print();"
        class="text-danger font-weight-bold">Print</a>
</div>
<?php $notice_id = $_REQUEST['notice_id'];
$notice_id = base64_decode($notice_id);
if ($notice_id != '') {
    $get_notice = $db->prepare("select notice_html from $schemas.notice_creation_details where id = ? ");
    $get_notice->bindParam(1, $notice_id, PDO::PARAM_STR);
    $get_notice->execute();
    $pdf_html = $get_notice->fetch();
    echo $pdf_html['notice_html'];
}