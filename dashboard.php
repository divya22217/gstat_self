<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include "./db_inc1.php";
include "./db_inc2.php";
include_once 'custom/custom_function.php';
$userid = $_SESSION['id'];
$sessionUserType = htmlspecialchars($_SESSION['id']);
$location_access = $_SESSION['location'];
$schema_id = $_SESSION['schema_idccc'];
$server_date = date('Y-m-d');
if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: login.php");
    die();
}
setcookie("PHPSESSID", "", time() - 3600, "", "", true, true);
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {
    include 'header.php';
   // include 'sidebar.php';

    $date_request = isset($_REQUEST['date_request']) ? $_REQUEST['date_request'] : date('d/m/Y');

    ?>
<link rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" href="css/main.css">

<div class="load_container">
    <img class="loader" src="loading-indicator.gif">
</div>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="breadcrumb" style="display:none;">
        <a href="#"></a> <a class="active" href="#">Dashboard</a>
    </div>
    <section class="content bg-white">
        <div class="wrap_content">

            <?php


if ($localadmin == '0' and $_SESSION['menuaccess_codeall'] == '2') {

        $total_pending_cases = fresh_cases($dbonline, $location_access, 'Y', 'NA', 'All', '', '', '', $date_request, 'cases_pending');
        $total_filed_cases = fresh_cases($dbonline, $location_access, 'Y', 'NA', 'All', '', '', '', $date_request, 'filed_cases');
      //  $total_ia_pending_cases = ia_cases($dbonline, $schemas, $date_request);
       // $total_defective_cases = defective_cases($dbonline, $location_access, 'Y', 'NA', 'All', '', '', $date_request);
        $total_rejected_cases = 0;
        $total_refiled_cases = 0;
        if (!empty($total_defective_cases) && is_array($total_defective_cases)) {
            foreach ($total_defective_cases as $val) {
                $filing_no = $val['filing_no'];
               /* $upload_check = fn_defective_objection($db, $dbonline, $schemas, $filing_no, $server_date);
                if ($upload_check == 0) {
                    $total_rejected_cases++;
                }
                if ($upload_check != 0) {
                    $total_refiled_cases++;
                }
				*/
            }
        }
       
        ?>
            <form name="frm" method="post">
                <input type="hidden" name="app_pet" id="app_pet" value="All">
                <div class="row">
                    <div class="col-md-12">
                        <div class="filter-date">
                            <i class="fa fa-calendar"></i>
                            <input onchange="fn_filter_date()" type="text" name="date_request" id="filter-date"
                                value="<?php echo $date_request; ?>" readonly>
                            <i class="fa fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="menuRightSec">
                    <div id="main_datagrid" class="right_col" role="main" style="scroller: auto;">
                        <div class="row dashboard-cards">
                            <?php //print_r($_REQUEST); ?>
                            <div class="col-md-2 mb-2">
                                <div class="card text-white o-hidden h-100">
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fa fa-file"></i>
                                        </div>
                                        <div class="label"> Pending fresh
                                            Cases<span><?php echo count($total_pending_cases); ?></span></div>
                                    </div>
                                    <a class="card-footer text-white clearfix small z-1"
                                        href="index.php?teb_type=cases_pending&app_pet=All&c_case=1&date_request=<?php echo $date_request; ?>">
                                        <span> View Details <i class="fa fa-angle-right"></i></span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-2 mb-2">
                                <div class="card grey text-white o-hidden h-100">
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <div class="label">Cases
                                            Filed<span><?php echo count($total_filed_cases); ?></span></div>
                                    </div>
                                    <a class="card-footer text-white clearfix small z-1"
                                        href="index.php?teb_type=filed_cases&app_pet=All&c_case=1&date_request=<?php echo $date_request; ?>">
                                        <span>View Details <i class="fa fa-angle-right"></i></span>
                                    </a>
                                </div>
                            </div>
							<!--
                            <div class="col-md-2 mb-2">
                                <div class="card text-white o-hidden h-100">
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fa fa-globe"></i>
                                        </div>
                                        <div class="label">Cases
                                            Defective<span><?php //echo $total_rejected_cases; ?></span></div>
                                    </div>
                                    <a class="card-footer text-white clearfix small z-1"
                                        href="index.php?teb_type=defective&app_pet=All&c_case=5&date_request=<?php// echo $date_request; ?>">
                                        <span>View Details <i class="fa fa-angle-right"></i></span>
                                    </a>
                                </div>
                            </div>
							
							
                            <div class="col-md-2 mb-2">
                                <div class="card grey text-white o-hidden h-100">
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fa fa-gavel"></i>
                                        </div>
                                        <div class="label">Cases Refiled<span><?php //echo $total_refiled_cases; ?></span>
                                        </div>
                                    </div>
                                    <a class="card-footer text-white clearfix small z-1"
                                        href="index.php?teb_type=refiled&app_pet=All&c_case=5&date_request=<?php //echo $date_request; ?>">
                                        <span>View Details <i class="fa fa-angle-right"></i></span>
                                    </a>
                                </div>
                            </div>
							
							
                            <div class="col-md-2 mb-2">
                                <div class="card text-white o-hidden h-100">
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fa fa-files-o"></i>
                                        </div>
                                        <div class="label">IA`s/CA Cases
                                            Pending<span><?php //echo count($total_ia_pending_cases); ?></span></div>
                                    </div>
                                    <a class="card-footer text-white clearfix small z-1"
                                        href="index.php?c_case=3&date_request=<?php //echo $date_request; ?>">
                                        <span>View Details <i class="fa fa-angle-right"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
					-->
                    
                </div>
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-6">
						<div id="container2"></div>
					</div>
					<div class="col-md-3"></div>
                </div>
            </div>

            <?php }
			
			
    if ($_SESSION['menuaccess_codeall'] == '11') {
       
        $ar_fresh_cases_app = ar_fresh_cases($db, $dbonline, $schemas, 'A','N', $limit = null, $start_from = null);
        $ar_fresh_cases_pet = ar_fresh_cases($db, $dbonline, $schemas, 'P','N', $limit = null, $start_from = null);

        $ar_fresh_cases_app_def = ar_fresh_cases($db, $dbonline, $schemas, 'A','Y', $limit = null, $start_from = null);
        $ar_fresh_cases_pet_def = ar_fresh_cases($db, $dbonline, $schemas, 'P','Y', $limit = null, $start_from = null);


        $ia_ar_data = fn_ia_ar_fresh_cases($db, $schemas, $limit = null, $start_from = null);
        $document_scrutiny = fn_document_ar_sc($db,$schemas,$dbonline, $_SESSION['menuaccess_codeall'],'1','', $limit = null, $start_from = null);
 
        $report_data = fn_ia_ar_fresh_cases($db, $schemas, $limit = null, $start_from = null);
      
        $case_type_year = isset($_REQUEST['case_year']) ? $_REQUEST['case_year'] : date('Y');
        $case_status = isset($_REQUEST['case_status']) ? $_REQUEST['case_status'] : 'P';
        $case_type_ac_ibc = isset($_REQUEST['case_type_ac_ibc']) ? $_REQUEST['case_type_ac_ibc'] : '1';
        $case_type_counter = fn_case_type_counter($db, $schemas, $case_type_year, $case_status, $case_type_ac_ibc);
        
        ?>
            <!-- CUSTOM BOXES -->
            <!-- <form name="frm" method="post">
                <input type="hidden" name="app_pet" id="app_pet" value="All">
                <div class="row">
                    <div class="col-md-12">
                        <div class="filter-date range">
                            <i class="fa fa-calendar"></i>
                            <input type="text" name="date_request_from" id="filter-date-from"
                                value="<?php echo $date_request; ?>" readonly>
                            <i class="fa fa-chevron-down"></i>
                        </div>
                        <span class="range_separator"> - </span>
                        <div class="filter-date range">
                            <i class="fa fa-calendar"></i>
                            <input type="text" name="date_request_to" id="filter-date-to"
                                value="<?php echo $date_request; ?>" readonly>
                            <i class="fa fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
            </form> -->

            <div class="row">
                <div class="menuRightSec">
                    <div id="main_datagrid" class="right_col" role="main" style="scroller: auto;">
                        <div class="row dashboard-cards">
                            <?php //print_r($_REQUEST); ?>

                            <div class="col-md-3 mb-2">
                                <div class="card text-white o-hidden h-100">
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fa fa-file"></i>
                                        </div>
                                        <div class="label"> Fresh case for Application Pending
                                            <span><?php echo count($ar_fresh_cases_app); ?></span></div>
                                    </div>
                                    <a class="card-footer text-white clearfix small z-1"
                                        href="index.php?app_pet=A&c_case=1">
                                        <span> View Details <i class="fa fa-angle-right"></i></span>
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-3 mb-2">
                                <div class="card grey text-white o-hidden h-100">
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fa fa-gavel"></i>
                                        </div>
                                        <div class="label">Fresh case for Application Pending with Defective <span><?php
                                        
                                        echo count($ar_fresh_cases_app_def); ?></span>
                                        </div>
                                    </div>
                                    <a class="card-footer text-white clearfix small z-1"
                                        href="index.php?app_pet=A&c_case=1">
                                        <span>View Details <i class="fa fa-angle-right"></i></span>
                                    </a>
                                </div>
                            </div> 
                            <div class="col-md-3 mb-2">
                                <div class="card  text-white o-hidden h-100">
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fa fa-file"></i>
                                        </div>
                                        <div class="label"> Fresh case for Petition Pending
                                            <span><?php echo count($ar_fresh_cases_pet); ?></span></div>
                                    </div>
                                    <a class="card-footer text-white clearfix small z-1"
                                        href="index.php?app_pet=P&c_case=1">
                                        <span> View Details <i class="fa fa-angle-right"></i></span>
                                    </a>
                                </div>
                            </div>
                             <div class="col-md-3 mb-2">
                                <div class="card grey  text-white o-hidden h-100">
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fa fa-gavel"></i>
                                        </div>
                                        <div class="label">resh case for Petition Pending with Defective <span><?php echo count($ar_fresh_cases_pet_def); ?></span>
                                        </div>
                                    </div>
                                    <a class="card-footer text-white clearfix small z-1"
                                        href="index.php?app_pet=P&c_case=1">
                                        <span>View Details <i class="fa fa-angle-right"></i></span>
                                    </a>
                                </div>
                            </div> 
                            <div class="col-md-3 mb-2">
                                <div class="card  text-white o-hidden h-100">
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <div class="label">IA Cases
                                            <span><?php echo count($ia_ar_data); ?></span></div>
                                    </div>
                                    <a class="card-footer text-white clearfix small z-1" href="index.php?c_case=3">
                                        <span>View Details <i class="fa fa-angle-right"></i></span>
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-3 mb-2">
                                <div class="card grey text-white o-hidden h-100">
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fa fa-globe"></i>
                                        </div>
                                        <div class="label">Document scrutiny for
                                            court<span><?php echo count($document_scrutiny); ?></span></div>
                                    </div>
                                    <a class="card-footer text-white clearfix small z-1" href="index.php?c_case=2&dash=1">
                                        <span>View Details <i class="fa fa-angle-right"></i></span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>


               

                    <div class="row"  style="margin-left: 152px;">
                    <button id="chart1_button_show" onclick="fn_view_chart()" class="btn btn-default"> View Chart </button>
                    <button style="display:none;" id="chart1_button_hide" onclick="fn_view_chart_hide()" class="btn btn-default">  Chart Hide </button>
                        <div style="display:none;"  id="chart1" class="chartbox"></div>
                    </div>


                </div>
            </div>


            <form name="frm_case_year" id="frm_case_year" method="post">
                <div class="row">
                    <div class="col-md-3">
                        <div class="filter-date range">
                            <label>Select Case Year</label>
                            <select onchange="fn_change_year(this.value)" name="case_year" id="case_year">
                                <option value="">Select Year</option>
                                <?php for ($i = date('Y'); $i > 1950; $i--) {?>
                                <option <?php echo ($case_type_year == $i) ? 'selected' : ''; ?>
                                    value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="filter-date range">
                            <label>Select Case Status</label>
                            <select onchange="fn_change_year(this.value)" name="case_status" id="case_status">
                                <option <?php echo ($case_status == 'All') ? 'selected' : ''; ?> value="All">All
                                </option>
                                <option <?php echo ($case_status == 'P') ? 'selected' : ''; ?> value="P">Pending
                                </option>
                                <option <?php echo ($case_status == 'D') ? 'selected' : ''; ?> value="D">Disposed
                                </option>
                            </select>
                        </div>

                    </div>

                    <div class="col-md-3">
                        <div class="filter-date range">
                            <label>Select Act </label>
                            <select onchange="fn_change_year(this.value)" name="case_type_ac_ibc" id="case_type_ac_ibc">
                                <option <?php echo ($case_type_ac_ibc == 'All') ? 'selected' : ''; ?> value="All">All
                                </option>

                                <?php
$data_master_act = array();
        try {
            $query = $db->prepare("SELECT * FROM public.master_act");
            $query->execute();
            $data_master_act = $query->fetchAll();
        } catch (PDOException $ex) {
            echo $ex;
        }

        if (!empty($data_master_act) && is_array($data_master_act)) {
            foreach ($data_master_act as $val) {

                ?>
                                <option <?php echo ($case_type_ac_ibc == $val['act_id']) ? 'selected' : ''; ?>
                                    value="<?php echo $val['act_id']; ?>"><?php echo $val['act_name']; ?> </option>
                                <?php }
        }
        ?>

                            </select>
                        </div>

                    </div>
                </div>
            </form>

            <div class="row">
                <div class="menuRightSec">
                    <div id="main_datagrid" class="right_col" role="main" style="scroller: auto;">
                        <div class="row dashboard-cards">
                            <?php //print_r($_REQUEST); ?>
                            <div class="col-md-4 mb-2">
                                &nbsp;
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="card text-white o-hidden h-100">
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fa fa-file"></i>
                                        </div>
                                        <div class="label"> Total Case Registered in <?php echo $case_type_year; ?>
                                            <span><?php echo $case_type_counter[count($case_type_counter) - 1]['total_count_aa']; ?></span>
                                        </div>
                                    </div>
                                    <a class=" text-white clearfix small z-1" href="#">
                                        <span> &nbsp;&nbsp;&nbsp;</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                &nbsp;
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div id="chart2" class="chartbox" style=";"></div>
                    </div>

                    <div class="row"  style="margin-left: 152px;">

                    <button id="chart3_button_show" onclick="fn_view_chart3()" class="btn btn-default"> View Chart </button>
                    <button style="display:none;" id="chart3_button_hide" onclick="fn_view_chart3_hide()" class="btn btn-default">  Chart Hide </button>

                        <div style="display:none;" id="chart3" class="chartbox"></div>
                    </div>
                </div>
            </div>


            <?php }

    $json_data = json_encode($case_type_counter);
    ?>
        </div>
    </section>
</div>
<?php include 'footer1.php';?>
<style>
.card {
    background: rgba(109, 47, 13);
    background: -moz-linear-gradient(125deg, rgb(236, 110, 59) 0%, rgba(109, 47, 13) 100%);
    background: -webkit-linear-gradient(125deg, rgb(236, 110, 59) 0%, rgba(109, 47, 13) 100%);
    background: linear-gradient(125deg, rgb(236, 110, 59) 0%, rgba(109, 47, 13) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="rgba(236, 110, 59)",endColorstr="rgba(109, 47, 13)",GradientType=1);
}
</style>
<script src="js/highcharts.js" type="text/javascript"></script>
<script src="js/highcharts-3d.js" type="text/javascript"></script>
<script src="js/exporting.js" type="text/javascript"></script>
<script src="js/jquery-ui.js"></script>

<script>
$(document).ready(function() {

    $("#filter-date").datepicker({
        dateFormat: 'dd/mm/yy'
    });


    var dateFormat = "dd/mm/yy",
        from = $("#filter-date-from")
        .datepicker({
            dateFormat: 'dd/mm/yy',
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1
        })
        .on("change", function() {
            to.datepicker("option", "minDate", getDate(this));
        }),
        to = $("#filter-date-to").datepicker({
            dateFormat: 'dd/mm/yy',
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1
        })
        .on("change", function() {
            from.datepicker("option", "maxDate", getDate(this));
        });

    function getDate(element) {
        var date;
        try {
            date = $.datepicker.parseDate(dateFormat, element.value);
        } catch (error) {
            date = null;
        }

        return date;
    }



    Highcharts.setOptions({
        colors: ['#058DC7', '#ED561B', '#FF9655', '#50B432', '#24CBE5', '#DDDF00',
            '#64E572', '#FFF263', '#6AF9C4'
        ]
    }); <?php 
    if ($_SESSION['menuaccess_codeall'] == '2') {
        ?>
        Highcharts.chart('container2', {
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            credits: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            title: {
                text: 'Cases in National Company Law Appellate Tribunal'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 35,
                    animation: {
                        duration: 4000
                    },
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}'
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: 'Cases',
                data: [
                    ['Cases Pending   -  ' + <?php echo count($total_pending_cases);?>
                        , <?php echo count($total_pending_cases);?>
                    ],
                    ['Cases Filed  -  ' + <?php echo count($total_filed_cases);?> , <?php  echo count($total_filed_cases);?>
                    ],
                  //  ['Cases Defective  -  ' + <?php echo $total_rejected_cases;?> , <?php  echo $total_rejected_cases;?>
                   // ],
                    //['Cases Refiled  -  ' + <?php echo $total_refiled_cases;?> , <?php  echo $total_refiled_cases;?>
                   // ],
                   //['IA`s Cases Pending -  ' + <?php //echo count($total_ia_pending_cases);?> , <?php //echo count( $total_ia_pending_cases);?> ],



                ]
            }]
        }); <?php 
    }
    if ($_SESSION['menuaccess_codeall'] == '11') {
        ?>
        //CHART 1


        Highcharts.chart('chart1', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: 0,
                plotShadow: false
            },
            credits: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            title: {
                text: 'Cases<br>Detalis',
                align: 'center',
                verticalAlign: 'middle',
                y: 60
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: true,
                        distance: -50,
                        style: {
                            fontWeight: 'normal',
                            fontSize: 14,
                            color: 'black'
                        }
                    },
                    startAngle: -90,
                    endAngle: 90,
                    center: ['50%', '75%'],
                    size: '100%',
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function() {
                               // gotoChart2(this.name, this.customdata);
                            }
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Cases',
                innerSize: '50%',
                data: [{
                        name: 'Fresh Case For App. Pen.',
                        y: <?php echo count($ar_fresh_cases_app);?> ,
                        customdata : 090912
                    },
                    {
                        name: 'Fresh Case For Pet. Pen.',
                        y: <?php echo count($ar_fresh_cases_pet);?> ,
                        customdata : 090912
                    }, 
                    {
                        name: 'Fresh Case For App. Pen. with defective',
                        y: <?php echo count($ar_fresh_cases_app_def);?> ,
                        customdata : 090912
                    },
                    {
                        name: 'Fresh Case For Pet. Pen. with defective',
                        y: <?php echo count($ar_fresh_cases_pet_def);?> ,
                        customdata : 090912
                    },
                    {
                        name: 'IA CASES',
                        y: <?php echo count($ia_ar_data);?>,
                        customdata : 2
                    }
                ]
            }]
        });

        //CHART 2
        Highcharts.chart('chart2', {
            chart: {
                type: 'column'
            },
            credits: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            title: {
                text: 'Total Case Registered in <?php echo $case_type_year; ?>'
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Total Register Case'
                }

            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    cursor: 'pointer',
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    },
                    point: {
                        events: {
                            click: function() {
                                //gotoChart3(this.name, this.customdata);
                            }
                        }
                    }
                }
            },

            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>'
            },

            series: [{
                name: "Cases",
                colorByPoint: true,
                data: <?php echo  $json_data; ?>
            }]
        });

        //CHART 3
        Highcharts.setOptions({
            colors: Highcharts.map(Highcharts.getOptions().colors, function(color) {
                return {
                    radialGradient: {
                        cx: 0.5,
                        cy: 0.3,
                        r: 0.7
                    },
                    stops: [
                        [0, color],
                        [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
                    ]
                };
            })
        });

        // Build the chart
        Highcharts.chart('chart3', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'TOTAL CASE REGISTERED IN <?php echo $case_type_year; ?>'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            credits: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}',
                        connectorColor: 'silver'
                    },
                    point: {
                        events: {
                            click: function() {
                                //alert(this.name + " " + this.customdata);
                            }
                        }
                    }
                }
            },
            series: [{
                name: 'Cases',
                data: <?php echo  $json_data; ?>
            }]
        });

        <?php 
    } ?>
});



        function fn_view_chart() { 
            $("#chart1_button_show").hide();
            $("#chart1_button_hide").show();
            $("#chart1").show();
        }

        function fn_view_chart_hide() { 
            $("#chart1_button_show").show();
            $("#chart1_button_hide").hide();
            $("#chart1").hide();
        }

        function fn_view_chart3() { 
            $("#chart3_button_show").hide();
            $("#chart3_button_hide").show();
            $("#chart3").show();
        }

        function fn_view_chart3_hide() { 
            $("#chart3_button_show").show();
            $("#chart3_button_hide").hide();
            $("#chart3").hide();
        }



function fn_change_year(case_year) { 
    $('.load_container').fadeIn(500);
    with(document.frm_case_year) {
        action = "dashboard.php";
        submit();
    }

}

function gotoChart2(name, data) {
    alert(name + " - " + data);
    $('#chart2').show();
    $('#chart3').hide();
    $('html, body').animate({
        scrollTop: $("#chart2").offset().top
    }, 1000);
}

function gotoChart3(name, data) {
    alert(name + " - " + data);
    $('#chart3').show();
    $('html, body').animate({
        scrollTop: $("#chart3").offset().top
    }, 1000);
}
$('.load_container').fadeOut(500);
</script>
<?php }?>