<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include("../db_inc1.php");
session_start();
$_SESSION['user'];
$_SESSION['location'];
$schemas = htmlspecialchars($_SESSION['schema_name']);
$userid = $_SESSION['id'];
if ($_SESSION['user'] == '' and $_SESSION['location'] == '') {
    echo "Access Problem.....";
    header("Location: ../login.php");
    die();
}
setcookie("PHPSESSID", "", time() - 3600, "", "", TRUE, TRUE);
if ($_SESSION['user'] != '' and $_SESSION['location'] != '') {


    ?>
    <?php
    include '../inheader.php';
    include '../sidebar.php';
    ?>
    <script type="text/javascript" src="../js/jquery.validate.min.js"></script>
    <style>
        .content-wrapper {
            padding: 15px 35px;
        }

        .load_container {
            background: rgba(0, 0, 0, 0.50);
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            z-index: 99;
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

        .error {
            color: red;
        }

    </style>
    <div class="load_container">
        <img class="loader" src="images/loading-indicator.gif">
    </div>


    <div class="content-wrapper">
        <div class="row" style="">
            <div class="box box-success">
                <div class="box-body">
                    <section class="content-header">
                        <h1>Summon / Notice Generation</h1>
                        <ol class="breadcrumb">
                            <li><a href="index.php"><i class="fa fa-dashboard"></i> HOME</a></li>
                            <li><a href="view_group.php">Summon / Notice Generation</a></li>
                        </ol>
                    </section>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="box box-success">
                <div class="table-responsive">
                    <table cellspacing="0" align="center"
                           class="table no-margin table-bordered table-striped table-hover" cellpadding="2" border="1"
                           width="95%" class="std">
                        <tr style="background-color:#00a65a;color:#ffffff;">
                            <th><b>Sr.No.</b></th>
                            <th align="left" width="150"><b> GR. No.</b></th>
                            <th align="left" width="700"><B>Case No </b></th>
                            <th align="left" width="700"><B>Title </b></th>
                            <th align="left" width="250"><B>Date Of Registration</b></th>
                            <th align="left" width="500"><B>Action</b></th>
                        </tr>
                        <?php
                        $count = 0;
                        $blank = '';
                        $pre_trial = 1;
                        $summon_notice_status = 0;
                        $pre_trial_case_type = array(1, 2);
                        $implode_cases = implode(',', $pre_trial_case_type);
                        $sql1 = $db->prepare("select filing_no,case_no,case_type,case_year,pet_name,res_name,dt_of_filing,location_code,regis_date from $schemas.case_detail where (case_no is NOT NULL OR case_no != ?) and (case_year is NOT NULL OR case_year != ?) and (case_type is NOT NULL  and case_type in ($implode_cases)) and (location_code is NOT NULL) and  legal_aid IS NULL and summon_notice_status = ? order by filing_no asc");
                        $sql1->bindParam(1, $blank, PDO::PARAM_INT);
                        $sql1->bindParam(2, $blank, PDO::PARAM_INT);
                       // $sql1->bindParam(3, $pre_trial, PDO::PARAM_INT);
                        $sql1->bindParam(3, $summon_notice_status, PDO::PARAM_INT);
                        $sql1->execute();
                        while ($row1 = $sql1->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                            $filing_no = $row1['filing_no'];
                            $filing_date = $row1['dt_of_filing'];
                            $regis_date = $row1['regis_date'];
                            $case_type = $row1['case_type'];
                            $pet_name = $row1['pet_name'];
                            $pet_name = strtoupper($pet_name);
                            $res_name = $row1['res_name'];
                            $res_name = strtoupper($res_name);
                            $location_code = $row1['location_code'];
                            $case_no = $row1['case_no'];
                            $case_year = $row1['case_year'];
                            $count++
                            ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $filing_no; ?></td>
                                <td>
                                    <?php
                                    $stqq1234 = $db->prepare("select short_name from case_type where id=?");
                                    $stqq1234->bindParam(1, $case_type, PDO::PARAM_INT);
                                    $stqq1234->execute();
                                    $case_short_name = $stqq1234->fetchColumn();
                                    $stqloc = $db->prepare("select short_name from mater_location_city where city_id=?");
                                    $stqloc->bindParam(1, $location_code, PDO::PARAM_INT);
                                    $stqloc->execute();
                                    $city_name = $stqloc->fetchColumn();
                                    $case_num = $case_short_name . "/" . $case_no . "/" . $case_year;
                                    echo $case_num;
                                    ?>
                                </td>

                                <td><?php echo $pet_name . ' Vs. ' . $res_name; ?>
                                </td>

                                <td>
                                    <?php
                                    if ($regis_date != '') {
                                        list($year1, $month1, $day1) = explode('-', $regis_date);
                                        $date_of_filing = $day1 . '/' . $month1 . '/' . $year1;
                                    }
                                    echo $date_of_filing; ?>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-success btn-sm preyes"
                                            onClick="return generate_notice_popup('<?php echo $filing_no; ?>');">
                                        Generate Summon / Notice
                                    </button>
                                </td>
                            </tr>


                            <?php

                        }
                        ?>
                    </table>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="box box-success">
                <div class="box-body">
                    <form method="post" id="notice_form_id">
                        <div class="modal-body with-padding">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Select Search BY :</label>
                                        <select required="required" class="form-control" name="search_by" id="search_by"
                                                onchange="fn_case_dfr_type(this.value)">
                                            <option value="">Select Search BY</option>
                                            <option value="1">GR. No</option>
                                            <option value="2">Case Type</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="case_dfr_div_id"></div>
                        </div>

                    </form>

                    <div id="table_div_res4444" style="display: block">

                        <table class="table">
                            <tbody>

                            <tr>
                                <td colspan="8" id="ddsdsdsdsdsd"
                                    style="font-size: large; text-align: center; color: red;"></td>
                            </tr>

                            </tbody>
                        </table>

                    </div>

                    <div class="table-responsive" id="table_div_res" style="display: none">
                        <section class="content">
                            <div class="row">
                               
                                <div class="col-sm-12" id="content_div_id">
                                    
                                </div>
                            </div>
                        </section>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div id="edit_group_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Generate Summon/Notice</h4>
                </div>
                <form method="post" id="generate_notice_form_id">

                    <input type="hidden" name="filing_no_hid" id="filing_no_hid" value="">
                    <div class="modal-body with-padding">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Summon/Notice Type :</label>
                                    <select onchange="fn_notice_formate(this.value)" required="required"
                                            class="form-control" name="notice_type" id="notice_type">
                                        <option value=""> Select Summon/Notice Type</option>
                                        <?php
                                        $query = "select * from $schemas.summon_type where status = '1' order by id asc";
                                        $query_prepare = $db->prepare($query);
                                        $query_prepare->execute();
                                        $i = 0;
                                        while ($row = $query_prepare->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                                            echo '<option value=' . $row["id"] . '>' . $row["name"] . '</option>';
                                            echo '<br>';
                                        }
                                        ?>
                                        <option value="4"> Notice 1</option>
                                        <option value="5"> Notice 2</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label></label>
                                </div>
                            </div>
                        </div>
                        <div id="onchange_div_id">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                        <button type="button" onclick="generate_notice()" name="form_data" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>
    <script src="../dist/js/adminlte.min.js"></script>
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        //  fn_notice_formate(1);

        function fn_notice_formate(value) {
            var data = {};
            data['action'] = 'notice_formate_div';
            data['notice_type'] = value;
            $.ajax({
                type: "POST",
                url: "ajax_notice.php",
                data: data,
                dataType: "html",
                success: function (data11111) {
                    var filing_no = $("#generate_notice_form_id #filing_no_hid").val();
                    var data = {};
                    data['action'] = 'party_drpdown';
                    data['filing_no'] = filing_no;
                    $.ajax({
                        type: "POST",
                        url: "ajax_notice.php",
                        data: data,
                        dataType: "html",
                        success: function (data1212) {
                            $("#generate_notice_form_id #to_party_id").html(data1212);
                        },
                        error: function (request, error) {
                            alert("something error. please try again");
                        }
                    });
                    $("#onchange_div_id").html(data11111);
                },
                error: function (request, error) {
                    alert("something error. please try again");
                }
            });

        }

        function generate_notice_popup(filing_no) {
            $("#edit_group_modal").modal('show');
            $("#generate_notice_form_id #filing_no_hid").val(filing_no);
        }

        function generate_notice() {

            var valid = $("#generate_notice_form_id").valid();
            if (valid) {
                var filing_no = $("#generate_notice_form_id #filing_no_hid").val();
                var data = {};
                data['action'] = 'generate_action';
                data['filing_no'] = filing_no;
                var notice_type = $("#generate_notice_form_id #notice_type").val();
                data['notice_type'] = $("#generate_notice_form_id #notice_type").val();
                if (notice_type == '1') {
                    data['whereas_description'] = $("#generate_notice_form_id #whereas_description").val();
                    data['summoned_description'] = $("#generate_notice_form_id #summoned_description").val();
                    data['answer_clame_date'] = $("#generate_notice_form_id #answer_clame_date").val();
                    data['seal_of_court_date'] = $("#generate_notice_form_id #seal_of_court_date").val();
                } else if (notice_type == '2') {
                    data['whereas_description'] = $("#generate_notice_form_id #whereas_description").val();
                    data['civil_procedure_amount'] = $("#generate_notice_form_id #civil_procedure_amount").val();
                    data['cost_together_amount'] = $("#generate_notice_form_id #cost_together_amount").val();
                    data['exceeding_amount'] = $("#generate_notice_form_id #exceeding_amount").val();
                    data['seal_of_court_date'] = $("#generate_notice_form_id #seal_of_court_date").val();
                } else if (notice_type == '3') {
                    data['whereason_date'] = $("#generate_notice_form_id #whereason_date").val();
                    data['petion_againts_desc'] = $("#generate_notice_form_id #petion_againts_desc").val();
                    data['appear_court_date'] = $("#generate_notice_form_id #appear_court_date").val();
                    data['written_statement_date'] = $("#generate_notice_form_id #written_statement_date").val();
                    data['seal_of_court_date'] = $("#generate_notice_form_id #seal_of_court_date").val();
                }
                else if (notice_type == '4') {
                    data['whereason_description'] = $("#generate_notice_form_id #whereason_description").val();
                    data['appear_court_date'] = $("#generate_notice_form_id #appear_court_date").val();
                    data['seal_of_court_date'] = $("#generate_notice_form_id #seal_of_court_date").val();
                }

                else if (notice_type == '5') {
                    data['whereason_description'] = $("#generate_notice_form_id #whereason_description").val();
                    data['appear_court_date'] = $("#generate_notice_form_id #appear_court_date").val();
                    data['seal_of_court_date'] = $("#generate_notice_form_id #seal_of_court_date").val();
                }

                data['to_party_id'] = $("#generate_notice_form_id #to_party_id").val();
                $.ajax({
                    type: "POST",
                    url: "ajax_notice.php",
                    data: data,
                    dataType: "json",
                    success: function (data) {
                        var datassss = {};
                        datassss['schema'] = data['schema'];
                        datassss['notice_type'] = data['notice_type'];
                        datassss['dataaa'] = data['data'];
                        datassss['file_name'] = data['file_name'];
                        datassss['party_ids'] = data['party_ids'];
                        $.ajax({
                            type: "POST",
                            url: "downloadpdf.php",
                            data: datassss,
                            dataType: "html",
                            success: function (datadddddd) {
                                console.log(datadddddd);
                                alert('Summon/Notice Generated');
                                fn_notice_formate('');
                                fn_search_by_case_dfr();
                                $("#edit_group_modal").modal('hide');
                                $("#message_results").html(data);
                                fn_search_by_case_dfr();
                                location.reload(true);
                            },
                            error: function (request, error) {
                                alert("something error . please try again");
                            }
                        });
                    },
                    error: function (request, error) {
                        alert("something error . please try again");
                    }
                });
            }
        }


        function fn_case_dfr_type(value) {
            $("#ddsdsdsdsdsd").empty();
            $('.load_container').show();
            var data = {};
            data['action'] = 'select_type';
            data['type_id'] = value;
            $.ajax({
                type: "POST",
                url: "ajax_notice.php",
                data: data,
                dataType: "html",
                success: function (data) {
                    $("#case_dfr_div_id").html(data);
                    $("#table_div_res").hide();
                    $("#content_div_id").empty();
                    $('.load_container').hide();
                },
                error: function (request, error) {
                    alert("something error. please try again");

                }
            });
        }

        function fn_search_by_case_dfr() {
            var valid = $("#notice_form_id").valid();
            if (valid) {
                $('.load_container').show();
                var search_by = $("#notice_form_id #search_by").val();
                var data = {};
                data['action'] = 'search_filing';
                data['search_by'] = $("#notice_form_id #search_by").val();
                if (search_by == '1') {
                    data['dairy_no'] = $("#notice_form_id #dairy_no").val();
                    //  data['dairy_year'] = $("#notice_form_id #dairy_year").val();
                } else if (search_by == '2') {
                    data['case_type'] = $("#notice_form_id #case_type").val();
                    data['case_no'] = $("#notice_form_id #case_no").val();
                    data['case_year'] = $("#notice_form_id #case_year").val();
                }
                $.ajax({
                    type: "POST",
                    url: "ajax_notice.php",
                    data: data,
                    dataType: "html",
                    success: function (data) {
                        $("#message_results").empty();
                        if (data != 1) {
                            $("#table_div_res4444").hide();
                            $("#ddsdsdsdsdsd").empty();
                            $("#table_div_res").show();
                            $("#content_div_id").html(data);
                            $('.load_container').hide();
                        } else {
                            $("#table_div_res").hide();
                            $("#table_div_res4444").show();
                            $("#ddsdsdsdsdsd").html('Data Not Found. Please Try Again.');
                            $('.load_container').hide();
                        }

                    },
                    error: function (request, error) {
                        alert("something error");
                    }
                });
            }


        }

        function popitup(generate_id) {
            newwindow = window.open("notice_format.php?generate_id=" + generate_id, 'name', 'height=600,width=1000');
            if (window.focus) {
                newwindow.focus()
            }
            return false;
        }

    </script>


<?php } ?>



