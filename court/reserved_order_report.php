<?php
// File used to connect to database
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");
include("../db_inc1.php");
$date = htmlspecialchars(date("d/m/Y"));
$date1 = htmlspecialchars(date("F j, Y g:i a"));
$msg_ip = isset($_REQUEST['msg_ip']) ? $_REQUEST['msg_ip'] : '';
$year = htmlspecialchars(date("Y"));
$msg_ip .= "User IP : " . $_SERVER["REMOTE_ADDR"] . "\r\n";
if (empty($_SESSION['user']) and $_SESSION['location'] == '') {
    die("Redirecting to login.php");
}
if (($_SESSION['user']) == '') {
    echo "you Can't access this page";
} else {

    function remove_path($file, $path = UPLOAD_PATH)
    {
        if (strpos($file, $path) !== FALSE) {
            return substr($file, strlen($path));
        }
    }

    $frm = md5(uniqid('auth', true));

    /*** set the session form token ***/
    $_SESSION['form_token'] = $frm;//csrf

    $schemas = htmlspecialchars($_SESSION['schema_name']);

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
        <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css">
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
        <script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <?php include("../includes/banner.php"); ?>
    <div class="wrapper">
        <?php include("../includes/header.php");
        include '../insidebar.php';
        ?>

        <div class="content-wrapper" style="min-height: 946px;">
            <!-- Content Header (Page header) -->
            <section class="content">

                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th style="width: 20%">Case Number</th>
                        <th>Filing Number</th>
                        <th>Section</th>
                        <th>Case Title</th>
                        <th>Last Date of Hearing</th>
                        <th>Court Number</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sql_reserved = "SELECT a.filing_no, a.listing_date, a.court_no, a.todays_action, a.entry_date,b.case_no,b.case_type,b.case_year,b.pet_name,b.res_name,b.location_code FROM delhi.case_proceeding as a INNER JOIN delhi.case_detail as b ON a.filing_no = b.filing_no where a.todays_action = '33'";
                    $mm_ar = 0;
                    $i=1;
                    foreach ($db->query($sql_reserved) as $row_data) {
                        $filing_no = $row_data['filing_no'];
                        $case_no = $row_data['case_no'];
                        $case_type = $row_data['case_type'];
                        $case_year = $row_data['case_year'];
                        $pet_name = $row_data['pet_name'];
                        $res_name = $row_data['res_name'];
                        $location_code = $row_data['location_code'];


                        $lcode = "select short_name from delhi.bench_location where bench_location_code ='$location_code'";
                        $lcode = $db->prepare($lcode);
                        $lcode->execute();
                        $lcodename = $lcode->fetchColumn();
                        if ($case_type > 0) {
                            $stQ = $db->prepare("select short_name from case_type where id = ?");
                            $stQ->bindParam(1, $case_type, PDO::PARAM_STR);
                            $stQ->execute();
                            $case_type_short_name = $stQ->fetchColumn();
                        }
                        $case_numaa = $case_no;
                        $case_year1aa = $case_year;
                        $case_num1aa = ltrim($case_numaa, 0);

                        $CASE_NO = htmlspecialchars(strtoupper($case_type_short_name) . '/' . $case_num1aa . '(' . $lcodename . ')' . $case_year1aa);



                        $st2 = $dbo->prepare("select * from e_case_detail_fees where filing_no=? ");
                        $st2->bindParam(1, $filing_no, PDO::PARAM_STR);
                        $st2->execute();
                        $r = '';
                        while ($row2 = $st2->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                            $E_sec_id = $row2['sec_id'];

                            $st3 = $dbo->prepare("select * from master_section_act where id=? ");
                            $st3->bindParam(1, $E_sec_id, PDO::PARAM_STR);
                            $st3->execute();

                            while ($row3 = $st3->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                                $E_add_sec_id = $row3['section_companies'];
                                $r.= $E_add_sec_id . ',';
                            }
                        }

                        $scetion = rtrim($r, ',');

                        ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $CASE_NO; ?></td>
                        <td><?php echo $row_data['filing_no']; ?></td>
                        <td><?php echo $scetion; ?></td>
                        <td><?php echo $pet_name. ' <span style="color:red" > VS </span> ' . $res_name; ?></td>
                        <td><?php echo $row_data['listing_date']; ?></td>
                        <td><?php echo $row_data['court_no']; ?></td>
                    </tr>
                    <?php $i++;  } ?>
                    </tbody>
                </table>
            </section>
        </div>
        <?php include '../includes/footer.php'; ?>
        <div class="control-sidebar-bg"></div>

    </div>
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
     <script src="../bower_components/bootstrap/dist/js/buttons.print.min.js"></script>
   <script src="../bower_components/bootstrap/dist/js/buttons.html5.min.js"></script>
  <script src="../bower_components/bootstrap/dist/js/vfs_fonts.js"></script>
   <script src="../bower_components/bootstrap/dist/js/pdfmake.min.js"></script>
    <script src="../bower_components/bootstrap/dist/js/jszip.min.js"></script>
   <script src="../bower_components/bootstrap/dist/js/datatables.min.css"></script>
    <script src="../bower_components/bootstrap/dist/js/datatables.min.js"></script>

    
    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            } );
        } );
    </script>



    </body>
    </html>
<?php } ?>
