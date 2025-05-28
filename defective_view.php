<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
header('Content-Type: text/html; charset=ISO-8859-1');
header('Content-Type: text/html; charset=iso-8859-15');

date_default_timezone_set("Asia/Kolkata");
include("db_inc1.php");
$security_par = $_REQUEST['security_par'];
$filing_no = $_REQUEST['filing_no'];
$schema = $_REQUEST['location_name'];
$location_id = $_REQUEST['location_id'];
if ($security_par != '|x5P{0m)Y~Xa') {
    echo 'Something went wrong';
    die;
}
try {

    $query = "select schema_name from mater_location_city where city_id = ?";
    $schema_name_query = $db->prepare($query);
    $schema_name_query->bindParam(1, $location_id, PDO::PARAM_STR);
    $schema_name_query->execute();
    $schema = $schema_name_query->fetchColumn();

    $query_q = "select  CONCAT(b.case_type_desc, CASE WHEN  (c.list_with_defect = '1' AND c.regis_date is null)  THEN ' D ' ELSE '' END , ' : ', c.case_no,'/',c.case_year) as main_case_no,c.list_with_defect as defect_listed,c.pet_name,c.res_name,c.filing_no,c.dt_of_filing,c.case_type,a.case_title from public.e_case_detail  as a inner join $schema.case_detail as c on a.filing_no = c.filing_no inner join case_type as b on b.id = c.case_type where a.location_id=? and a.payment_accept='Y' and c.filing_no = ? ";

    $query = $db->prepare($query_q);
    $query->bindParam(1, $location_id, PDO::PARAM_STR);
    $query->bindParam(2, $filing_no, PDO::PARAM_STR);
    $query->execute();
    $data = $query->fetch();
} catch (PDOException $ex) {
    echo  $ex;

    die;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cause List</title>
    <link rel="stylesheet" href="ajax/css/style.css">
    <link rel="stylesheet" href="ajax/css/fontawesome.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/fontawesome.css">
    <script src="ajax/js/jquery.min.js"></script>
    <link rel="stylesheet" href="ajax/css/jquery-ui.css">
    <script src="ajax/js/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <script src="/plugins/jQueryUI/jquery-1.12.4.js"></script>
    <script src="/plugins/jQueryUI/jquery-ui.js"></script>


    
    <script>
    $(function() {
        $("#date_from").datepicker({
            dateFormat: 'dd/mm/yy'
        });
        $("#date_to").datepicker({
            dateFormat: 'dd/mm/yy'
        });
    });
    </script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="ajax/js/jquery.validate.min.js"></script>
    <style>
    .accordion {
        margin-top: 30px;
    }

    .accordion .card-header h2 {
        font-size: 17px;
    }

    .accordion .card-header h2 {
        color: #fff;
    }

    .accordion .card-header.collapsed h2 {
        color: #fff;
    }

    .accordion .card-header {
        padding-right: 40px;
        position: relative;
        cursor: pointer;
        background: #f1a14d;
    }

    .accordion .card-header::after {
        content: '_';
        font-size: 30px;
        color: #fff;
        position: absolute;
        right: 15px;
        top: -14px;
    }

    .accordion .card-header.collapsed::after {
        content: '+';
        top: -1px;
        right: 13px;
    }

    .accordion .card-body {
        font-size: 15px;
        color: #6f6f6f;
    }
    </style>

    <style>
    .btn-primary,
    .btn-success {
        background-color: #da8a33;
        border-color: #b96c18;
    }

    .btn-primary:hover,
    .btn-success:hover {
        background-color: #b96c18;
        border-color: #b96c18;
    }

    .btn-primary:not(:disabled):not(.disabled):active,
    .btn-primary:not(:disabled):not(.disabled).active,
    .show>.btn-primary.dropdown-toggle,
    .btn-success:not(:disabled):not(.disabled):active,
    .btn-success:not(:disabled):not(.disabled).active,
    .show>.btn-success.dropdown-toggle {
        background-color: #da8a33;
        border-color: #b96c18;
    }

    header {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 5;
        width: 100%;
        height: 102px;
        box-shadow: 0px 1px 15px 0 rgba(0, 0, 0, 0.3);
        border-bottom: 1px solid rgba(255, 255, 255, 0.70);
        background-color: rgba(228, 150, 67, 0.90);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: bottom center;
        overflow: hidden;
    }

    .logo img {
        width: 100%;
    }

    .bg-white {
        background: #FFF;
        border-bottom: 3px solid #30a569;
    }

    .fade.in {
        overflow-y: auto;
    }

    .modal-header .close {
        margin: 0;
        position: absolute;
        top: 0px;
        right: 8px;
    }

    .load_container {
        background: rgba(0, 0, 0, 0.50);
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        z-index: 99999;
        left: 0;
        display: none;
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

    .form-group .error {
        color: red;

    }

    .modal_ngt .modal-header {
        background: #9a3129;
    }

    .form_box {
        max-width: 400px;
        margin: 40px auto 0 auto;
        background: rgba(255, 255, 255, 0.76);
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.35);
        border: 1px solid #9a3129;
        position: relative;
        z-index: 2;
    }

    .modal-header-inner {
        padding: 5px;
        font-size: 12px;
        background: #f1a14d;
        border-radius: 5px;
        margin-top: 15px;
    }

    .modal-header-inner .modal-title {
        font-size: 15px;
        text-transform: uppercase;
        font-weight: bold;
    }

    footer {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        text-align: center;
        padding: 10px 15px;
        font-size: 13px;
        background: rgba(0, 84, 50, 0.50);
        color: #fff;
        text-shadow: 1px 1px 1px #000;
        z-index: 1;
    }

    .otherlogo {
        position: absolute;
        right: 0;
        top: 0;
    }

    .mainlogo {
        margin: 10px 20px 0;
    }

    .form_heading {
        font-size: 16px;
        font-weight: bold;
        color: #fff;
        text-transform: uppercase;
        background: #846313;
        display: inline-block;
        padding: 8px 70px 8px 70px;
        border: 2px solid #846313;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.15);
        border-radius: 10px;
    }

    .headnav {
        position: fixed;
        top: 102px;
        z-index: 4;
        width: 100%;
        background: #717171;
        padding: 0px 25px;
        color: #fff;
        box-shadow: 0px 0px 10px rgb(0 0 0 / 30%);
        font-size: 14px;
    }

    .headnav a {
        display: inline-block;
        padding: 8px 15px;
        color: #fff;
        font-weight: bold;
        background: rgb(255 255 255 / 10%);
    }

    .headnav .headinfo {
        float: right;
        padding: 8px 15px;
    }

    .form_box1 {
        margin: 40px auto 30px auto;
        background: rgba(255, 255, 255, 0.76);
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.35);
        border: 1px solid #9a3129;
        position: relative;
        z-index: 2;
    }

    .fade {
        overflow-y: auto !important;
    }

    .table-bordered th,
    .table-bordered td {
        vertical-align: middle !important;
    }

    #listing_case_detaisl {
        width: 100%;
        padding: 0;
    }
    </style>


    <style>
        * {
            box-sizing: border-box;
        }

        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .header_container {
            background: #f7e0bb;
            text-align: center;
            border-bottom: 0px solid #ad9980;
        }

        .header_container img {
            display: block;
            margin: auto;
        }

        .blue_bg {
           /* background: url(./APTEL_files/bg_brown3.jpg);
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;*/
            background: linear-gradient(160deg, #85b3cc 0%, #cfb784 100%);
        }

        .formbox {
            background: #fff;
            width: 100%;
            max-width: 535px;
            height: auto;
            margin: auto;
            overflow: hidden;
            border-radius: 10px;
            position: relative;
            box-shadow: 0px 10px 50px rgba(0, 0, 0, 0.3);
            margin-top: 150px;
        }

        .fpMsg {
            font-size: 12px;
            display: block;
            margin: 30px 5px 10px 5px;
            color: #e27e32;
        }

        .fpMsg:hover {
            color: #e27e32;
            text-decoration: none;
        }

        .formleft {
            background: linear-gradient(160deg, #846414 0%, #d6ecf3 100%);
            /*background-image: url(./APTEL_files/bg_form.png);
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;*/
            position: absolute;
            left: 0;
            top: 0;
            width: 200px;
            height: 100%;
            text-align: center;
        }

        .leftcontent {
            position: relative;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #fff;
           /* text-shadow: 0px 0px 15px #fff;*/
        }

        .leftcontent span {
            font-size: 24px;
        }

        .formright {
            padding-left: 200px;
        }

        .formtitle {
            font-size: 20px;
            color: #846313;
            text-align: center;
            text-transform: uppercase;
            padding: 20px;
        }

        .inputwrap {
            padding: 10px 30px
        }

        .inputwrap input {
            width: 100%;
            border: none;
            border-bottom: 1px solid #ccc;
            font-size: 14px;
            padding: 5px 0px 5px 0px;
        }

        .forminput:focus {
            outline: 0;
        }

        .forminput {
            width: 100%;
            border: none;
            border-bottom: 1px solid #ccc;
            font-size: 14px;
            background-repeat: no-repeat;
            background-size: 20px;
            background-position: left center;
            padding: 5px 0px 5px 0px;
        }

        .forminput.username {
            padding: 5px 0px 5px 35px !important;
            background-image: url(./APTEL_files/icon_user.png);
        }

        .forminput.password {
            padding: 5px 0px 5px 35px !important;
            background-image: url(./APTEL_files/icon_lock.png);
        }

        .captchatext {
            padding: 0 30px;
        }

        .captcha_table {
            width: 100%;
            margin: 0;
        }

        .captcha_table tr td {
            /* width: 50%; */
            margin: 0;
            padding: 0;
        }

        .imgcaptcha {
            width: 87%;
            height: 25px;
            border: 1px solid #ccc;
            vertical-align: bottom;
        }

        .formsubmit {
            background: #846313;
            border: none;
            color: #fff;
            font-size: 16px;
            padding: 7px 50px;
            border-radius: 30px;
            margin-top: 20px;
            outline: none;
            cursor: pointer;
        }

        .formsubmit:hover {
            background: #294984;
        }

        .errormsg {
            /* min-height: 80px; */
            text-align: center;
            /*padding: 30px 20px;*/
            color: #d02619;
            font-size: 13px;
            z-index: 999;
            position: relative;
            background: #f5f1e2;
        }

        .copyright {
    text-align: center;
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    font-size: 13px;
    font-family: arial;
    color: #fff;
    padding: 5px;
    background: #294984;
}

        .restdiv {
            display: none;
        }

        .otpdiv {
            display: none;
        }

        header .upper {
    background: linear-gradient(160deg, #ddeaf1 0%, #f4e6c6 100%); 
    border-bottom: 3px solid #294984;
}

header {
    width: 100%;
    height: 110px;
    position:fixed;
    left:0;
    right:0;
    z-index:99;
}
header h1 {
    margin: 0;
}
header .site-title {
    font-size: 1.75em;
    display: inline;
    font-family: serif;
    vertical-align: middle;
    margin-left: 15px;
    float: left;
    padding-top: 30px;
    color: #846312;
}
header .inner {
    overflow: hidden;
    width: 100%;
    max-width: 90%;
    margin: 0 auto;
    padding: 7px 0px;
}

header .left_logo {
    height: 94px;
    float: left;
    margin-left: 10px;
    padding: 0px 0;
}

header .right_logo {
    float: right;
    padding: 20px 0;
}

header .right_logo img {
    padding: 0 10px;
    height: 40px;
}
    </style>




</head>

<body class="bg_green">
    </section>
        <header>
            <div class="upper">
                    <div class="inner">
                    <div>
                        <img src="./APTEL_files/GSTAT-Logo.png" class="left_logo">
                        <h1 class="site-title">GST Appellate Tribunal</h1>
                        </div>
                        <div class="right_logo">
                            <img src="./APTEL_files//logo_sb.png">
                            <img src="./APTEL_files//logo_di.png">
                        </div>
                        
                    </div>
                </div>
        </header>
    <div class="headnav">
        <a href="https://uat-cis.gstat.gov.in/gstat"><i class="fa fa-home"></i> Home</a>
        <!-- <a href="case_status.php"> Case Status</a>
        <a href="cause_list.php"> Cause List</a> -->
        <div class="headinfo"> <?php echo date("l jS \of F Y h:i:s A"); ?></div>
    </div>

    <div class="load_container">
        <img class="loader" src="ajax/images/loading-indicator.gif">
    </div>


    <?php

    
    $party_flag_p = "select name from e_cases_party where filing_no = '$filing_no' and party_flag = 'P' limit 1  ";
    $party_flag_p = $db->prepare($party_flag_p);
    $party_flag_p->execute();
    $party_flag_p = $party_flag_p->fetchColumn();
    $party_flag_r = "select name from e_cases_party where filing_no = '$filing_no' and party_flag = 'R' limit 1 ";
    $party_flag_r = $db->prepare($party_flag_r);
    $party_flag_r->execute();
    $party_flag_r = $party_flag_r->fetchColumn();
    $title_cause_title = $party_flag_p . ' VS ' . $party_flag_r;
    $query_scr = "SELECT distinct(date) FROM public.scrutiny_history where filing_no = '$filing_no' order by date desc ";
    $query_scr_q = $db->prepare($query_scr);
    $query_scr_q->execute();
    $query_scr_q_data = $query_scr_q->fetchAll();
    $refile_date = '';
    if (!empty($query_scr_q_data) && is_array($query_scr_q_data)) {
        foreach ($query_scr_q_data as $val_sc_date) {
            $refile_date .= date('d-m-Y', strtotime($val_sc_date['date'])) . '<br>';
        }
    } else {
        $refile_date = 'RA';
    }
    ?>
    <div class="container" style="margin-top: 160px;">
        <div class="text-center">
            <div class="form_heading" style="padding: 8px 30px;">
                <p style="color:white;">Defects Summery</p>
                <table cellspacing="0" align="center" class="table no-margin table-bordered" cellpadding="2" border="1"
                    width="95%">


                    <tr>
                        <td valign="middle" align="center" style="white-space: nowrap;">
                            <?php echo date('d/m/Y', strtotime($data['dt_of_filing'])); ?> </td>
                        <td valign="middle" align="center"> <?php echo $data['main_case_no']; ?> </td>
                        <td valign="middle" align="center"> <?php echo $data['filing_no']; ?> </td>
                        <td valign="middle" align="center"> <?php if ($data['case_title']) {
                                                                echo  $data['case_title'];
                                                            } else {
                                                                echo $title_cause_title;
                                                            } ?> </td>

                    </tr>
                </table>
            </div>
        </div>

        <div class="container" id="listing_case_detaisl" style="">
            <div class="form_box1" id="view_case_type_details">

                <table class="table no-margin table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="white-space: nowrap;">Sr. No</th>
                            <th>Description</th>
                            <th>Defect Type</th>
                            <th>Comments</th>
                            <th style="white-space: nowrap;">Defect Raised On</th>
                            <th style="width: 15%;">Re-filing Date / RA (Reply Awaited)</th>

                        </tr>
                    </thead>
                    <tbody id="row_data_data">
                        <tr>
                            <td colspan="5"> </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>



    </div>




    <!-- Modal View Case Type Details -->










    </div>

    <script>
    view_popup('<?php echo $filing_no; ?>', '<?php echo $data['case_title']; ?>');

    function view_popup(filing_no, title) {
        $("#edit_group_modal").modal('show');
        var data_tiele = title;
        $(".modal-title-title").html(data_tiele);
        fn_defective_data(filing_no);
        //$("#row_data_data").html(htm_data);
    }


    function fn_defective_data(filing_no) {
        $("#row_data_data").empty();
        $('.load_container').show();
        var data = {};
        data['action'] = 'defctive_data_search';
        data['filing_no'] = filing_no;
        data['schema'] = '<?php echo $schema; ?>';
        $.ajax({
            type: "POST",
            url: "ajax/defective.php",
            data: data,
            dataType: "html",
            success: function(data) {
                $("#row_data_data").html(data);
                $('.load_container').hide();
            },
            error: function(request, error) {
                alert("something error. please try again");

            }
        });
    }
    </script>


    <script>
    function my_function_f(date_time) {
        alert('No Data Updated for ' + date_time);
    }
    </script>
    <script type="text/javascript" src="ajax/js/custom_search.js"></script>

</body>

</html>
