<?php
// File used to connect to database
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalid");

//  ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include("../db_inc1.php");


 include("../inheader.php");




// At the top of the page we check to see whether the user is logged in or not
if(empty($_SESSION['user']) and $_SESSION['location']=='')
{
    // If they are not, we redirect them to the login page.
    // Remember that this die statement is absolutely critical.  Without it,
    // people can view your members-only content without logging in.
    die("Redirecting to login.php");
}
if(($_SESSION['user'])=='')
{
    echo "you Can't access this page";
}
else
 {
    

$frm = md5( uniqid('auth', true) );

/*** set the session form token ***/
$_SESSION['form_token'] = $frm;//csrf
$schemas=htmlspecialchars($_SESSION['schema_name']);
 
?>







<script src="../plugins/jQueryUI/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.css">
<script src="../plugins/jQueryUI/jquery-ui.js"></script>
<script src="../plugins/jQueryUI/date.js"></script>


<link rel="stylesheet" type="text/css" href="../includes/highslide/highslide.css" />
<script type="text/javascript" src="../includes/highslide/highslide-with-html1.js"></script>
<link rel="stylesheet" type="text/css" href="../datatable/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="../datatable/css/buttons.dataTables.min.css">



<style>
table,
td,
th {
    border: 1px solid #ffffff;
}



th {
    background-color: #846312;
    color: white;
}
.th-flex {
    display: flex;
    justify-content: center;
    align-items: center;
    white-space: nowrap;
}
.th-flex select, .th-flex input {
    margin-right: 20px;
} 

</style>
</head>


<div class="wrapper" style="background-color:#ffffff;">

    <?php 
        $user_court = $_SESSION['user_court'];
        $query = "select listing_date,count(*) from $schemas.case_allocation where court_no = ? group by listing_date order by listing_date desc;";
        $res = $db->prepare($query);
        $res->bindParam(1, $user_court, PDO::PARAM_STR);
        $res->execute();
        $report_data = $res->fetchAll();

    ?>

    <div class="content-wrapper" style="min-height: 946px;">
        <!-- Content Header (Page header) -->
        <section class="content">


            <table class="table">
                <tr>
                    <th valign="top" align="center" colspan="16">
                        <b>
                            <font face="Verdana" size="3">Cases Reports</font>
                        </b>
                    </th>
                </tr>
            </table>
                

            <table class='table table-responsive table-hover table-bordered' id="case_details">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Listing Date</th>
                        <th>Listed Cases</th>
                    </tr>
                </thead>
                <tbody>
        <?php if(!empty($report_data)){ 
        foreach($report_data as $k=>$report)
        {
            
    ?>
            <tr>
                <td><?php echo ++$k; ?></td>
                <td><?php echo date('d/m/Y',strtotime($report['listing_date'])); ?></td>
                <td><?php echo $report['count']; ?></td>
            </tr>
        
    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</section>


    <?php include '../infooter.php'; ?>
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

        $('#case_details').DataTable({
            "pageLength": -1,
            "lengthMenu": [
                [100, 200, 500, -1],
                [100, 200, 500, "All"]
            ],
            buttons: [{
                    extend: 'excel',
                    title: 'excel_report'
                },
                {
                    extend: 'csv',
                    title: 'csv_report'
                }
            ]
        });
    });
    </script>

    <?php }  } ?>