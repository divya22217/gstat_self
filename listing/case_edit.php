<?php
header("Cache-Control: private");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Cache-Control=proxy-revalidate");
date_default_timezone_set("Asia/Kolkata");
ob_start();
include "../db_inc1.php";
include "../db_inc2.php";
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
    include '../insidebar.php';
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
    z-index: 9999999;
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
    <img class="loader" src="../loading-indicator.gif">
</div>


<div class="content-wrapper">
    <div class="row" style="">
        <div class="box box-success">
            <div class="box-body">
                <section class="content-header">
                    <h1>Case No Update</h1>
                    <ol class="breadcrumb">
                        <li><a href="../index.php"><i class="fa fa-dashboard"></i> HOME</a></li>
                        <li><a href="case_edit.php">Case No Update</a></li>
                    </ol>
                </section>
            </div>
        </div>
    </div>




    <div class="row">
        <div class="box box-success">
            <div class="box-body">
                <form method="post" id="edit_case_form_id">
                    <div class="modal-body with-padding">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Select Search BY :</label>
                                    <select required="required" class="form-control" name="search_by" id="search_by"
                                        onchange="fn_case_dfr_type(this.value)">
                                        <option value="">Select Search BY</option>
                                        <option value="1">Diary No.</option>
                                        <option value="2" selected>Case Type</option>
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

                <div class="table-responsive" id="table_div_res">
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
                <h4 class="modal-title">Update Case No</h4>
            </div>
            <form method="post" id="update_case_no_form_id">
                <input type="hidden" name="filing_no_hid" id="filing_no_hid" value="">
                <div class="modal-body with-padding">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Select Bench</label>
                                <select name="bench_type" id="bench_type" class="form-control required">
                                    <option value="">Select</option>
                                    <?php
$st = $db->prepare("select * from $schemas.bench_location where display = 'TRUE'  order by bench_location_name asc");
    $st->execute();
    while ($row = $st->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        echo "<option value=" . htmlspecialchars($row['bench_location_code']) . ">" . htmlspecialchars($row['bench_location_name']) . "</option>";
    }
    ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>Old Case Type.</label>
                                <select name="old_case_type" id="old_case_type" class="form-control required" disabled
                                    readonly>
                                    <option value="">Select</option>
                                    <?php
$st = $db->prepare("select * from case_type where display = 'TRUE' order by case_type_desc asc");
    $st->execute();
    while ($row = $st->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        echo "<option value=" . $row['id'] . ">" . $row['case_type_desc'] . "</option>";
    }

    ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>Old Case No.</label>
                                <input type="text" name="old_case_no" id="old_case_no" class="form-control required"
                                    disabled readonly>
                            </div>
                            <div class="col-sm-6">
                                <label>Old Case Year</label>
                                <select class="form-control required" name="old_case_year" id="old_case_year" disabled
                                    readonly>
                                    <option value="">Select Year</option>
                                    <?php for ($i = 2019; $i >= 1970; $i--) {?>
                                    <option value="<?php echo $i; ?>"><?php echo $i ?></option>
                                    <?php }?>
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label>Select Case Type.</label>
                                <select name="case_type" id="case_type" class="form-control required">
                                    <option value="">Select</option>
                                    <?php
$st = $db->prepare("select * from case_type where display = 'TRUE' order by case_type_desc asc");
    $st->execute();
    while ($row = $st->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        echo "<option value=" . $row['id'] . ">" . $row['case_type_desc'] . "</option>";
    }

    ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>Enter Case No.</label>
                                <input type="text" name="case_no" id="case_no" class="form-control required">
                            </div>
                            <div class="col-sm-6">
                                <label>Select Case Year</label>
                                <select class="form-control required" name="case_year" id="case_year">
                                    <option value="">Select Year</option>
                                    <?php for ($i = 2019; $i >= 1970; $i--) {?>
                                    <option value="<?php echo $i; ?>"><?php echo $i ?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label>Select Court No.</label>
                                <select name="court_no" id="court_no" class="form-control required">
                                    <option value="">Select</option>
                                    <?php for ($i = 1; $i <= 10; $i++) {?>
                                    <option value="<?php echo $i; ?>">Court <?php echo $i; ?> </option>
                                    <?php }?>
                                </select>

                            </div>
                            <!-- <div class="col-sm-6">
            <label>Registration Date.</label>
                                    <input type="text" readonly autocomplete="off" name="regis_date" size="10"
                                        maxlength="10" class="datepicker"
                                        value="" />
                                        </div> -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                    <button type="button" onclick="update_case_no()" name="form_data" class="btn btn-primary">
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
fn_case_dfr_type(2);


function edit_case_no(filing_no, case_no, case_type, case_year) {
    $("#edit_group_modal").modal('show');
    $("#update_case_no_form_id #filing_no_hid").val(filing_no);
    $("#update_case_no_form_id #old_case_no").val(case_no);
    $("#update_case_no_form_id #old_case_type").val(case_type);
    $("#update_case_no_form_id #old_case_year").val(case_year);
}

function update_case_no() {
    var valid = $("#update_case_no_form_id").valid();
    if (valid) {
        //$('.load_container').show();
        var data = {};
        data['action'] = 'update_case_no_f';
        data['filing_no'] = $("#update_case_no_form_id #filing_no_hid").val();
        data['bench_type'] = $("#update_case_no_form_id #bench_type").val();
        data['case_type'] = $("#update_case_no_form_id #case_type").val();
        data['case_no'] = $("#update_case_no_form_id #case_no").val();
        data['case_year'] = $("#update_case_no_form_id #case_year").val();
        data['court_no'] = $("#update_case_no_form_id #court_no").val();
        data['old_case_no'] = $("#update_case_no_form_id #old_case_no").val();
        data['old_case_type'] = $("#update_case_no_form_id #old_case_type").val();
        data['old_case_year'] = $("#update_case_no_form_id #old_case_year").val();
        $.ajax({
            type: "POST",
            url: "ajax_case.php",
            data: data,
            dataType: "html",
            success: function(data) {
                alert(data);
				location.reload();
            },
            error: function(request, error) {
                alert("something error. please try again");

            }
        });
    }
}


function fn_case_dfr_type(value) {
    $('.load_container').show();
    var data = {};
    data['action'] = 'select_type';
    data['type_id'] = value;
    $.ajax({
        type: "POST",
        url: "ajax_case.php",
        data: data,
        dataType: "html",
        success: function(data) {
            $("#case_dfr_div_id").html(data);
            $('.load_container').hide();
        },
        error: function(request, error) {
            alert("something error. please try again");

        }
    });
}




function fn_search_by_case_dfr() {
    var valid = $("#edit_case_form_id").valid();
    if (valid) {
        $('.load_container').show();
        var search_by = $("#edit_case_form_id #search_by").val();
        var data = {};
        data['action'] = 'search_filing';
        data['search_by'] = $("#edit_case_form_id #search_by").val();
        if (search_by == '1') {
            data['dairy_no'] = $("#edit_case_form_id #dairy_no").val();
        } else if (search_by == '2') {
            // data['case_type'] = $("#edit_case_form_id #case_type").val();
            data['case_no'] = $("#edit_case_form_id #case_no").val();
            data['case_year'] = $("#edit_case_form_id #case_year").val();
        }
        $.ajax({
            type: "POST",
            url: "ajax_case.php",
            data: data,
            dataType: "html",
            success: function(data) {
                if (data != 1) {
                    $("#content_div_id").html(data);
                    $('.load_container').hide();
                } else {
                    $('.load_container').hide();
                }

            },
            error: function(request, error) {
                alert("something error");
            }
        });
    }


}
</script>


<?php }?>