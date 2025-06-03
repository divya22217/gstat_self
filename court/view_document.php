<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include("../db_inc1.php");
session_start();
$judge = htmlentities($_REQUEST['judge']);

$user = $_SESSION['user'];

$location = $_SESSION['location'];
$leveladd = $_SESSION['level_level'];
$localadmin = $_SESSION['localadmin'];
$main_id = $_SESSION['main_id'];
$schemas = htmlspecialchars($_SESSION['schema_name']);


$sessionUserType = $_SESSION['id'];

if ($sessionUserType > 0) {
    $atrrs = $db->prepare(" select * from users_cis where id=? ");
    $atrrs->bindParam(1, $sessionUserType, PDO::PARAM_STR);
    $atrrs->execute();
    while ($row = $atrrs->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        //$accesspoint1=$row['accesspoint1'];
        $localadmincontrol = $row['localadmin'];
    }
}

if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: ../login.php");
    die();
}
if (empty($_SESSION['user']) and $_SESSION['location'] == '') {

    die("Redirecting to login.php");
}
if (($_SESSION['user']) == '') {
    echo "you Can't access this page";
}
setcookie("PHPSESSID", "", time() - 3600, "", "", TRUE, TRUE);
$benchlocation = htmlentities($_REQUEST['bench_location']);

include '../inheader.php';
//include '../insidebar.php'; 
?>
<html>

<head>
    <title>
        Change Password
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <style>
        input[type=text],
        select {

            padding: 5px 40px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #000000;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type=password],
        select {
            padding: 5px 40px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #000000;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type=submit] {

            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }
    </style>
    <script>


    </script>
</head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="renderer" content="webkit">
<title>View Document</title>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <center>View Document
            </center>
        </h1>

    </section>

    <form name="frm" method="post" action="">
        <table cellspacing="0" align="center" cellpadding="2" border="1" width="90%" class="std">
            <tr>
                <td width="104" align="left valign=" top" nowrap="nowrap">
                    <font color="red">*</font>
                    <font face="Verdana" size="2">Filing No.:</font>
                </td>
                <td valign="top" align="left">
                    <input type="text" placeholder="Enter filing_no" name="filing_no">
                </td>
            </tr>

            <tr>
                <td>&nbsp;</td>
                <td colspan="8" align="left">
                    <button type="submit">search</button>
                </td>
            </tr>

    </form>
    </table>
    <?php

    if (!empty($_REQUEST['filing_no'])) {
        // $scrutiny = '1';
        // $is_deleted = false;
        $filing_no = $_REQUEST['filing_no'];
        //   select * from document_upload du where is_deleted =true and scrutiny =1 and filing_no ='9910110026732021';
        //  echo $filing_no.''.$is_deleted.''.$scrutiny;
//select filing_no,original_file ,docum_type ,deleted_date ,party_type ,fileupload from document_upload  where is_deleted =true and scrutiny =1;
        $query = $db->prepare("select * from document_upload where is_deleted = 'true' and scrutiny = '1' and filing_no = ?");
        $query->bindParam(1, $filing_no, PDO::PARAM_STR);
        $query->execute();
        $document_record = $query->fetchAll();
        // echo "<pre>";
        // print_r($document_record);
        if (empty($document_record)) {
            echo "No records found.";
        } else  ?>
        <div class="table-responsive" id="table_data">
            <table cellspacing="0" align="center" class="table no-margin table-bordered table-striped table-hover casealloc_table" cellpadding="2" border="1" width="95%" class="std">
                <thead style="background-color:#00a65a;color:#ffffff;">
                    <th><b>Sr.No.</b></th>
                    <th align="left" width="150"><b>Diary No.</b></th>
                    <th align="left" width="150"><b>Original file</b></th>
                    <th align="left" width="150"><b>Document Type</b></th>
                    <th align="left" width="150"><b>Deleted Date</b></th>
                    <th align="left" width="150"><b>Party Type </b></th>
                    <th align="left" width="700"><B>File Upload</b></th>

                </thead>
                <?php
                $serialNo = 1;
                foreach ($document_record as $values) : ?>

                    <tbody id="search_data_here">
                        <tr>
                            <td><?php echo $serialNo++ ?></td>
                            <td><?php echo $values['filing_no']; ?></td>
                            <td><?php echo $values['original_file']; ?></td>
                            <td><?php echo $values['docum_type']; ?></td>
                            <td><?php echo $values['deleted_date']; ?></td>
                            <td><?php echo $values['party_type']; ?></td>
                            <td>
                                <a href="javascript::void(0)" onClick="return view_order('<?php echo urlencode($values['fileupload']); ?>');" style="cursor: pointer"> PDF</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
            </table>
        </div>
        <?php

        include '../bfooter.php';
        ?>
        <script>
            function view_order(pdfpath) {
                var loader = "<center><img src='../loader/loader.gif'></img></center>";
                $.ajax({
                    type: "POST",
                    url: "view_document_action.php",
                    data: {
                        path: pdfpath
                    },
                    beforeSend: function() {
                        $("#view_order_body").html(loader);
                        $("#view_order").modal('show');
                    },
                    success: function(data) {
                        $("#view_order_body").html(data);
                        //alert("success");
                    },
                    error: function(textStatus, errorThrown) {
                        $("#view_order_body").html('');
                        $("#view_order").modal('hide');
                        alert("error");
                    }

                });
            }
        </script>

        <div id="view_order" class="modal fade" role="dialog">
            <div class="modal-dialog" style="width:90%">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Document</h4>
                    </div>
                    <div class="modal-body" id="view_order_body" style="height:500px;">
                        <p>Loading.........</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    <?php } ?>
